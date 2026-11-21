<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Gymkos;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Logika absensi yang dipakai bersama oleh halaman admin (Absensi Harian)
 * dan halaman absen mandiri (QR), supaya aturannya tidak ditulis dua kali.
 */
class AttendanceService
{
    // Lokasi yang boleh dipakai untuk absensi: 1 = B11N GYM, 2 = K1NG GYM.
    // Kost (Istana Merdeka, id 3-5) tidak ikut. Tambahkan id di sini kalau ada cabang gym baru.
    public const GYM_IDS = [1, 2];

    /**
     * Rapikan Member ID yang diketik orang menjadi format database (B-00123 / K-02541).
     * Menerima huruf kecil, spasi, tanpa strip, tanpa nol di depan, dan huruf O sebagai angka 0.
     * Mengembalikan null kalau bentuknya tidak mungkin Member ID.
     */
    public static function normalizeMemberCode(?string $input): ?string
    {
        $value = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $input));

        if (!preg_match('/^([BK])([0-9O]{1,5})$/', $value, $m)) {
            return null;
        }

        return $m[1] . '-' . str_pad(str_replace('O', '0', $m[2]), 5, '0', STR_PAD_LEFT);
    }

    public function findMemberByCode(?string $input): ?Member
    {
        $code = self::normalizeMemberCode($input);

        return $code
            ? Member::with('gymkos')->where('member_code', $code)->first()
            : null;
    }

    /**
     * Catat absen member.
     *
     * Hasil: array dengan status 'success' | 'warning' (sudah absen hari ini) | 'expired',
     * ditambah data member yang dipakai kartu hasil di halaman admin.
     */
    public function checkInMember(
        Member $member,
        int $gymId,
        string $method,
        string $status = Attendance::STATUS_VERIFIED,
        ?string $nickname = null
    ): array {
        $gymName = Gymkos::whereKey($gymId)->value('name');

        $payload = [
            'member_name' => $member->name,
            'photo_url' => $member->picture ? asset('storage/' . $member->picture) : null,
            'email' => $member->email ?? '-',
            'phone' => $member->phone ?? '-',
            'address' => $member->address ?? '-',
            'expired_date' => $member->membership_end_date
                ? Carbon::parse($member->membership_end_date)->format('d M Y')
                : '-',
            'member_code' => $member->member_code ?: null,
            'home_gym_name' => $member->gymkos?->name,
            'gym_name' => $gymName,
            'attendance_id' => null,
            'message' => null,
        ];

        if ($this->isExpired($member)) {
            return array_merge($payload, ['status' => 'expired', 'message' => 'Membership Expired']);
        }

        // Kunci per member: mencegah ketuk ganda / dua perangkat sekaligus mencatat dua kali.
        $result = Cache::lock('attendance-member-' . $member->id, 10)->get(
            function () use ($member, $gymId, $method, $status, $nickname, $payload) {
                // Absen yang ditolak admin sudah dihapus, jadi member asli boleh absen lagi.
                $existing = Attendance::with('gymkos')
                    ->where('member_id', $member->id)
                    ->whereDate('check_in_time', today())
                    ->latest('check_in_time')
                    ->first();

                if ($existing) {
                    $where = $existing->gymkos?->name ?? 'cabang lain';
                    $time = $existing->check_in_time?->format('H:i') ?? '-';

                    return array_merge($payload, [
                        'status' => 'warning',
                        'message' => "Sudah absen hari ini di {$where} pukul {$time}.",
                        'gym_name' => $where,
                    ]);
                }

                $attendance = Attendance::create([
                    'gymkos_id' => $gymId,
                    'member_id' => $member->id,
                    'nickname' => $nickname,
                    'check_in_time' => now(),
                    'method' => $method,
                    'status' => $status,
                ]);

                return array_merge($payload, [
                    'status' => 'success',
                    'attendance_id' => $attendance->id,
                ]);
            }
        );

        return $result ?: array_merge($payload, [
            'status' => 'warning',
            'message' => 'Absensi Anda sedang diproses. Tidak perlu absen lagi.',
        ]);
    }

    /**
     * Catat absen tamu (non-member): harian / mingguan.
     */
    public function checkInVisitor(
        int $gymId,
        string $name,
        string $visitType,
        ?string $paymentMethod,
        string $method,
        string $status = Attendance::STATUS_VERIFIED
    ): array {
        $visitType = $visitType === 'weekly' ? 'weekly' : 'daily';
        $paymentMethod = ($paymentMethod && isset(Attendance::PAYMENT_METHODS[$paymentMethod])) ? $paymentMethod : null;

        $attendance = Attendance::create([
            'gymkos_id' => $gymId,
            'member_id' => null,
            'visitor_name' => $name,
            'visit_type' => $visitType,
            'payment_method' => $paymentMethod,
            'check_in_time' => now(),
            'method' => $method,
            'status' => $status,
        ]);

        return [
            'status' => 'success',
            'attendance_id' => $attendance->id,
            'member_name' => $name . ' (Non-Member)',
            'photo_url' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random',
            'message' => 'Paket: ' . ($visitType === 'weekly' ? 'Mingguan' : 'Harian')
                . ($paymentMethod ? ' • ' . Attendance::PAYMENT_METHODS[$paymentMethod] : ''),
            'email' => '-',
            'phone' => '-',
            'member_code' => null,
            'home_gym_name' => null,
            'gym_name' => Gymkos::whereKey($gymId)->value('name'),
            'expired_date' => $visitType === 'weekly' ? now()->addDays(7)->format('d M Y') : 'Hanya Berlaku Hari Ini',
        ];
    }

    /**
     * Tamu dengan nama yang sama sudah tercatat hari ini di cabang ini?
     * Dipakai absen mandiri untuk menahan pengiriman ganda / spam.
     */
    public function visitorAlreadyToday(int $gymId, string $name): bool
    {
        return Attendance::whereNull('member_id')
            ->where('gymkos_id', $gymId)
            ->where('visitor_name', $name)
            ->whereDate('check_in_time', today())
            ->exists();
    }

    private function isExpired(Member $member): bool
    {
        if ($member->status !== 'active') {
            return true;
        }

        return $member->membership_end_date !== null
            && Carbon::parse($member->membership_end_date)->endOfDay()->isPast();
    }
}
