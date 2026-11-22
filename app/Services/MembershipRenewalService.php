<?php

namespace App\Services;

use App\Models\Member;
use App\Models\MembershipRenewal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Pencatatan riwayat perpanjangan membership.
 * Pencatatan dipanggil eksplisit (bukan observer pada membership_end_date) supaya koreksi tanggal
 * di form edit tidak terhitung sebagai perpanjangan.
 */
class MembershipRenewalService
{
    public const GYM_IDS = [1, 2];

    /**
     * Tebak bulan & jumlah bulan dari perubahan "Berlaku Sampai" di form edit.
     * Masih aktif -> periode menyambung dari tanggal berakhir lama; sudah lewat/baru -> mulai dari hari ini.
     *
     * @return array{period_month: Carbon, months: int}
     */
    public static function inferPeriod(?Carbon $oldEnd, Carbon $newEnd, ?Carbon $today = null): array
    {
        $today = ($today ?? Carbon::today())->copy()->startOfDay();
        $base = ($oldEnd && $oldEnd->copy()->endOfDay()->gte($today)) ? $oldEnd->copy()->startOfDay() : $today;
        $months = max(1, (int) round($base->diffInDays($newEnd->copy()->startOfDay(), false) / 30.4));

        return ['period_month' => $base->copy()->startOfMonth(), 'months' => min($months, 36)];
    }

    /** True bila tanggal berlaku dimajukan (indikasi pembayaran perpanjangan, bukan koreksi mundur). */
    public static function isExtension(?Carbon $oldEnd, Carbon $newEnd): bool
    {
        return ! $oldEnd || $newEnd->copy()->startOfDay()->gt($oldEnd->copy()->startOfDay());
    }

    /** Catat riwayat dari hasil edit form member (masa berlaku sudah disimpan oleh Filament). */
    public function recordFromEdit(Member $member, ?Carbon $oldEnd, Carbon $newEnd, ?string $method): ?MembershipRenewal
    {
        $p = self::inferPeriod($oldEnd, $newEnd);

        return Cache::lock("renewal-member-{$member->id}", 10)->block(5, fn () => $this->record($member, [
            'paid_at' => today(),
            'period_month' => $p['period_month'],
            'months' => $p['months'],
            'valid_until' => $newEnd,
            'payment_method' => $method,
            'source' => 'admin',
        ]));
    }

    /**
     * Catat satu baris riwayat tanpa mengubah masa berlaku member.
     * Mengembalikan null bila member bukan member gym (cabang 1/2) atau catatan identik sudah ada.
     */
    public function record(Member $member, array $data): ?MembershipRenewal
    {
        if (! in_array((int) $member->gymkos_id, self::GYM_IDS, true)) {
            return null;
        }

        $periodMonth = Carbon::parse($data['period_month'] ?? today())->startOfMonth()->toDateString();
        $paidAt = Carbon::parse($data['paid_at'] ?? today())->toDateString();
        $months = max(1, (int) ($data['months'] ?? 1));

        // Pembayaran online: payment_id unik -> approve ulang tidak menambah baris.
        if (! empty($data['payment_id']) && MembershipRenewal::where('payment_id', $data['payment_id'])->exists()) {
            return null;
        }

        // Klik ganda: member + tanggal bayar + bulan + jumlah bulan yang sama.
        $duplicate = MembershipRenewal::where('member_id', $member->id)
            ->whereDate('paid_at', $paidAt)
            ->whereDate('period_month', $periodMonth)
            ->where('months', $months)
            ->exists();
        if ($duplicate) {
            return null;
        }

        return MembershipRenewal::create([
            'member_id' => $member->id,
            'gymkos_id' => $member->gymkos_id,
            'paid_at' => $paidAt,
            'period_month' => $periodMonth,
            'months' => $months,
            'valid_until' => $data['valid_until'] ?? $member->membership_end_date,
            'payment_method' => $data['payment_method'] ?? null,
            'source' => $data['source'] ?? 'admin',
            'payment_id' => $data['payment_id'] ?? null,
            'recorded_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Data rekap bulanan: baris = member, kolom = bulan (Jan–Des), isi = metode bayar.
     * Query langsung ke renewals (join members) agar Member::retrieved tidak terpicu.
     *
     * @return array{rows: array<int, array{code: ?string, end: ?string, name: string, phone: ?string, join: ?string, cells: array<int, ?string>}>, months: int[]}
     */
    public function recap(int $gymkosId, int $year, ?int $month = null, ?string $search = null): array
    {
        $from = Carbon::create($year, 1, 1)->subMonthsNoOverflow(11)->toDateString(); // pembayaran multi-bulan dari tahun lalu
        $to = Carbon::create($year, 12, 1)->toDateString();

        $q = MembershipRenewal::query()
            ->join('members', 'members.id', '=', 'membership_renewals.member_id')
            ->where('membership_renewals.gymkos_id', $gymkosId)
            ->whereBetween('membership_renewals.period_month', [$from, $to])
            ->select('membership_renewals.*', 'members.name as member_name', 'members.member_code as member_code', 'members.phone as member_phone', 'members.join_date as member_join', 'members.membership_end_date as member_end')
            ->orderBy('membership_renewals.paid_at');

        if ($search) {
            $q->where(function ($w) use ($search) {
                $w->where('members.name', 'like', "%{$search}%")->orWhere('members.member_code', 'like', "%{$search}%");
            });
        }

        $members = [];
        foreach ($q->get() as $r) {
            $label = MembershipRenewal::PAYMENT_METHODS[$r->payment_method] ?? '✓';
            foreach ($r->coveredMonths() as $ym) {
                [$y, $m] = array_map('intval', explode('-', $ym));
                if ($y !== $year || ($month && $m !== $month)) {
                    continue;
                }
                $members[$r->member_id]['code'] = $r->member_code;
                $members[$r->member_id]['name'] = $r->member_name;
                $members[$r->member_id]['phone'] = $r->member_phone;
                $members[$r->member_id]['join'] = $r->member_join ? Carbon::parse($r->member_join)->locale('id')->translatedFormat('d M Y') : null;
                $members[$r->member_id]['end'] = $r->member_end ? Carbon::parse($r->member_end)->locale('id')->translatedFormat('d M Y') : null;
                $members[$r->member_id]['cells'][$m] = $label; // bayar ganda di bulan sama: yang terbaru menang
            }
        }

        $rows = collect($members)
            ->filter(fn ($m) => ! empty($m['cells']))
            ->sortBy(fn ($m) => strtolower($m['name']))
            ->values()
            ->all();

        return ['rows' => $rows, 'months' => $month ? [$month] : range(1, 12)];
    }
}
