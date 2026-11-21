<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Gymkos;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * Halaman absen mandiri (publik) yang dibuka dari QR per cabang: /absen/{token}.
 *
 * Pengaman (karena halaman ini tanpa login):
 * - Member diverifikasi lewat Member ID; hanya 1x absen per member per hari (di service).
 * - Tamu: nama yang sama hanya 1x per hari per cabang, dan maksimal 5 absen tamu per HP per hari.
 * - Rate limit per IP, plus batas khusus untuk percobaan gagal (menebak Member ID).
 * - Semua absen mandiri berstatus "pending" dan dicek admin di Rekap Absensi.
 */
class SelfCheckin extends Component
{
    private const COOKIE_MEMBER = 'selfcheckin_member';   // pengingat: panggilan + Member ID
    private const COOKIE_VISITOR = 'selfcheckin_visitor'; // pengingat: nama + paket tamu
    private const COOKIE_COUNT = 'selfcheckin_count';     // hitungan absen tamu per HP per hari
    private const COOKIE_MINUTES = 60 * 24 * 365;
    private const MAX_VISITORS_PER_DEVICE = 5;
    private const MAX_REQUESTS_PER_MINUTE = 40;
    private const MAX_FAILURES_PER_MINUTE = 15;

    #[Locked]
    public int $gymId = 0;

    #[Locked]
    public string $gymName = '';

    // choose | member | visitor | result
    public string $step = 'choose';

    // Form member
    public string $nickname = '';
    public string $memberCode = '';

    // Form tamu
    public string $visitorName = '';
    public string $visitType = 'daily';
    public string $paymentMethod = '';

    // Hasil
    public string $resultType = ''; // success | warning | error
    public string $resultTitle = '';
    public string $resultText = '';

    #[Locked]
    public string $token = '';

    public function mount(string $token): void
    {
        // Cabang dicari lewat token acak di QR (bukan id), jadi URL tidak bisa ditebak.
        $gym = DB::table((new Gymkos)->getTable())
            ->where('absen_token', $token)
            ->whereIn('id', AttendanceService::GYM_IDS)
            ->first(['id', 'name']);

        abort_unless($gym, 404);

        $this->token = $token;
        $this->gymId = (int) $gym->id;
        $this->gymName = (string) $gym->name;

        // Isi otomatis dari kunjungan sebelumnya di HP ini
        $member = json_decode((string) request()->cookie(self::COOKIE_MEMBER), true);
        if (is_array($member)) {
            $this->nickname = mb_substr((string) ($member['nickname'] ?? ''), 0, 40);
            $this->memberCode = mb_substr((string) ($member['code'] ?? ''), 0, 20);
        }

        $visitor = json_decode((string) request()->cookie(self::COOKIE_VISITOR), true);
        if (is_array($visitor)) {
            $this->visitorName = mb_substr((string) ($visitor['name'] ?? ''), 0, 60);
            $this->visitType = ($visitor['type'] ?? 'daily') === 'weekly' ? 'weekly' : 'daily';
        }
    }

    // ------------------------------------------------------------------
    // Navigasi
    // ------------------------------------------------------------------
    public function chooseMember(): void
    {
        $this->resetErrorBag();
        $this->step = 'member';
    }

    public function chooseVisitor(): void
    {
        $this->resetErrorBag();
        $this->step = 'visitor';
    }

    public function backToStart(): void
    {
        $this->resetErrorBag();
        $this->step = 'choose';
    }

    // Kembali ke layar awal dengan halaman bersih (isian diambil ulang dari cookie)
    public function finish()
    {
        return $this->redirectRoute('self-checkin', ['token' => $this->token]);
    }

    // ------------------------------------------------------------------
    // Absen member
    // ------------------------------------------------------------------
    public function submitMember(): void
    {
        $this->nickname = trim(preg_replace('/\s+/', ' ', $this->nickname));
        $this->memberCode = trim($this->memberCode);

        $this->validate([
            'nickname' => ['required', 'string', 'max:40'],
            'memberCode' => ['required', 'string', 'max:20'],
        ], [
            'nickname.required' => 'Isi nama panggilan Anda.',
            'nickname.max' => 'Nama panggilan terlalu panjang.',
            'memberCode.required' => 'Isi Member ID Anda.',
            'memberCode.max' => 'Member ID terlalu panjang.',
        ]);

        if ($this->blocked()) {
            return;
        }

        $service = app(AttendanceService::class);
        $member = $service->findMemberByCode($this->memberCode);

        if (!$member) {
            RateLimiter::hit($this->ipKey('fail'), 60);
            $this->addError('memberCode', 'Member ID tidak ditemukan. Cek lagi (contoh: B-00123) atau tanya kasir.');

            return;
        }

        $result = $service->checkInMember(
            $member,
            $this->gymId,
            'self_scan',
            Attendance::STATUS_PENDING,
            $this->nickname
        );

        if ($result['status'] === 'expired') {
            RateLimiter::hit($this->ipKey('fail'), 60);
            $this->showResult('error', 'Membership sudah berakhir', 'Silakan ke kasir untuk perpanjang.');

            return;
        }

        if ($result['status'] === 'warning') {
            $this->showResult('warning', 'Sudah absen hari ini', (string) $result['message']);

            return;
        }

        Cookie::queue(
            self::COOKIE_MEMBER,
            json_encode(['nickname' => $this->nickname, 'code' => $member->member_code]),
            self::COOKIE_MINUTES
        );

        // Sengaja menampilkan panggilan yang diketik, bukan nama asli dari database,
        // supaya Member ID yang ditebak tidak membocorkan nama member lain.
        $this->showResult('success', 'Berhasil!', "Selamat berlatih, {$this->nickname}!");
    }

    // ------------------------------------------------------------------
    // Absen tamu (harian / mingguan)
    // ------------------------------------------------------------------
    public function submitVisitor(): void
    {
        $this->visitorName = trim(preg_replace('/\s+/', ' ', $this->visitorName));

        $this->validate([
            'visitorName' => ['required', 'string', 'max:60'],
            'visitType' => ['required', Rule::in(['daily', 'weekly'])],
            'paymentMethod' => ['required', Rule::in(array_keys(Attendance::PAYMENT_METHODS))],
        ], [
            'paymentMethod.required' => 'Pilih metode pembayaran.',
            'visitorName.required' => 'Isi nama Anda.',
            'visitorName.max' => 'Nama terlalu panjang.',
            'visitType.required' => 'Pilih paket kunjungan.',
            'visitType.in' => 'Pilih paket kunjungan.',
            'paymentMethod.in' => 'Pilih metode pembayaran yang tersedia.',
        ]);

        if ($this->blocked()) {
            return;
        }

        // Batas per HP (cookie): cukup untuk menahan pengiriman iseng berulang
        $today = today()->toDateString();
        $device = json_decode((string) request()->cookie(self::COOKIE_COUNT), true);
        $count = (is_array($device) && ($device['d'] ?? null) === $today) ? (int) ($device['n'] ?? 0) : 0;

        if ($count >= self::MAX_VISITORS_PER_DEVICE) {
            $this->addError('form', 'Batas absen dari HP ini hari ini sudah tercapai. Silakan ke kasir.');

            return;
        }

        $service = app(AttendanceService::class);

        if ($service->visitorAlreadyToday($this->gymId, $this->visitorName)) {
            $this->showResult('warning', 'Sudah absen hari ini', "{$this->visitorName} sudah tercatat hari ini.");

            return;
        }

        $service->checkInVisitor(
            $this->gymId,
            $this->visitorName,
            $this->visitType,
            $this->paymentMethod,
            'self_scan',
            Attendance::STATUS_PENDING
        );

        Cookie::queue(self::COOKIE_COUNT, json_encode(['d' => $today, 'n' => $count + 1]), 60 * 24);
        Cookie::queue(
            self::COOKIE_VISITOR,
            json_encode(['name' => $this->visitorName, 'type' => $this->visitType]),
            self::COOKIE_MINUTES
        );

        $this->showResult(
            'success',
            'Berhasil!',
            "Selamat datang, {$this->visitorName}! Jangan lupa selesaikan pembayaran di kasir."
        );
    }

    // ------------------------------------------------------------------
    // Pembantu
    // ------------------------------------------------------------------
    private function showResult(string $type, string $title, string $text): void
    {
        $this->resultType = $type;
        $this->resultTitle = $title;
        $this->resultText = $text;
        $this->step = 'result';
    }

    private function ipKey(string $scope): string
    {
        return 'selfcheckin:' . $scope . ':' . request()->ip();
    }

    // True kalau IP ini terlalu sering mengirim (dan menampilkan pesannya).
    private function blocked(): bool
    {
        foreach ([['all', self::MAX_REQUESTS_PER_MINUTE], ['fail', self::MAX_FAILURES_PER_MINUTE]] as [$scope, $max]) {
            $key = $this->ipKey($scope);

            if (RateLimiter::tooManyAttempts($key, $max)) {
                $this->addError(
                    'form',
                    'Terlalu banyak percobaan. Coba lagi dalam ' . RateLimiter::availableIn($key) . ' detik, atau tanya kasir.'
                );

                return true;
            }
        }

        RateLimiter::hit($this->ipKey('all'), 60);

        return false;
    }

    public function render()
    {
        $theme = $this->theme();

        return view('livewire.self-checkin', [
            'paymentMethods' => Attendance::PAYMENT_METHODS,
            'theme' => $theme,
        ])->layout('layouts.absen-layout', [
            'theme' => $theme,
            'title' => 'Absen Mandiri - ' . $this->gymName,
        ]);
    }

    // Warna & gambar mengikuti website tiap cabang (1 = B11N merah, 2 = K1NG oranye).
    private function theme(): array
    {
        return match ($this->gymId) {
            2 => [
                'accent' => '#f0761f',
                'dark' => '#c85d12',
                'rgb' => '240, 118, 31',
                'hero' => 'assets/king-gym/hero.jpg',
                'logo' => 'assets/Logo/last.png',
                'tagline' => 'Empower The King On You',
            ],
            default => [
                'accent' => '#dc030a',
                'dark' => '#87060c',
                'rgb' => '220, 3, 10',
                'hero' => 'assets/home/biin-gym.jpg',
                'logo' => 'assets/Logo/biin.png',
                'tagline' => 'Give Up Or Get Up',
            ],
        };
    }
}
