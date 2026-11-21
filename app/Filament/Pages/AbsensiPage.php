<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\Gymkos;
use App\Services\AttendanceService;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Support\Facades\DB;

class AbsensiPage extends Page implements HasForms
{
    use InteractsWithForms;

    // --- NAVIGATION CONFIG ---
    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Absensi Harian';
    protected static ?string $title = 'Absensi Member & Tamu';
    protected static ?string $navigationGroup = 'Membership & Absensi';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.absensi-page';

    // Holds the form data
    public ?array $data = [];

    // State untuk menyimpan ID Lokasi Gym yang dipilih
    public ?int $selectedGymId = null;

    // --- MOUNT ---
    public function mount(): void
    {
        $this->form->fill();
    }

    // --- FUNGSI PILIH & RESET LOKASI ---
    public function setGym($id)
    {
        // Tolak id di luar daftar (mis. kost), termasuk kalau dipanggil manual lewat Livewire.
        // Daftar gym diatur di AttendanceService::GYM_IDS (dipakai juga oleh absen mandiri).
        if (!in_array((int) $id, AttendanceService::GYM_IDS, true)) {
            return;
        }

        $this->selectedGymId = (int) $id;
    }

    public function resetGym()
    {
        $this->selectedGymId = null;
    }

    // --- HTML UNTUK OPSI DROPDOWN MEMBER ---
    // Semua data user di-escape dengan e() karena Select memakai allowHtml().
    protected function memberOptionHtml(Member $member): string
    {
        $avatarUrl = $member->picture
            ? asset('storage/' . $member->picture)
            : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=random';

        $name = e($member->name);
        $detail = e(collect([$member->email, $member->phone])->filter()->implode(' • ') ?: '-');

        $codeBadge = $member->member_code
            ? '<span style="display:inline-block;margin-left:6px;padding:1px 8px;border-radius:9999px;background:#fef9c3;color:#854d0e;font-size:11px;font-weight:700;">'
            . e($member->member_code) . '</span>'
            : '';

        return '
            <div class="flex items-center gap-3 py-1">
                <img src="' . e($avatarUrl) . '" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                <div class="flex flex-col text-left">
                    <span class="font-bold text-gray-800 dark:text-white">' . $name . $codeBadge . '</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">' . $detail . '</span>
                </div>
            </div>
        ';
    }

    // --- FORM SCHEMA ---
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Radio::make('attendance_type')
                    ->label('Tipe Pengunjung')
                    ->options([
                        'member' => 'Member Terdaftar',
                        'visitor' => 'Non-Member (Harian/Mingguan)',
                    ])
                    ->default('member')
                    ->inline()
                    ->live()
                    ->afterStateUpdated(fn($state, callable $set) => $set('member_id', null)),

                Select::make('member_id')
                    ->label('Cari Member')
                    ->placeholder('Ketik nama, Member ID, email, atau no. hp...')
                    ->searchable()
                    ->visible(fn(Get $get) => $get('attendance_type') === 'member')
                    ->required(fn(Get $get) => $get('attendance_type') === 'member')
                    ->getSearchResultsUsing(function (string $search): array {
                        $search = trim($search);

                        return Member::query()
                            ->where(function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search}%")
                                    ->orWhere('member_code', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            })
                            // Member ID yang persis sama selalu tampil paling atas
                            ->orderByRaw('CASE WHEN member_code = ? THEN 0 ELSE 1 END', [$search])
                            ->orderBy('name')
                            ->limit(10)
                            ->get()
                            ->mapWithKeys(fn(Member $member) => [
                                $member->id => $this->memberOptionHtml($member),
                            ])
                            ->all();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $member = Member::find($value);

                        return $member ? $this->memberOptionHtml($member) : null;
                    })
                    ->allowHtml()
                    ->reactive(),

                TextInput::make('visitor_name')
                    ->label('Nama Pengunjung')
                    ->required(fn(Get $get) => $get('attendance_type') === 'visitor')
                    ->visible(fn(Get $get) => $get('attendance_type') === 'visitor'),

                Select::make('visit_type')
                    ->label('Paket Kunjungan')
                    ->options([
                        'daily' => 'Harian (Daily Visit)',
                        'weekly' => 'Mingguan (Weekly Pass)',
                    ])
                    ->default('daily')
                    ->required(fn(Get $get) => $get('attendance_type') === 'visitor')
                    ->visible(fn(Get $get) => $get('attendance_type') === 'visitor'),

                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->placeholder('Tidak diisi')
                    ->options(Attendance::PAYMENT_METHODS)
                    ->nullable()
                    ->visible(fn(Get $get) => $get('attendance_type') === 'visitor'),
            ])
            ->statePath('data');
    }

    // --- CORE LOGIC ---
    // Aturan absensi (expired, sekali sehari, pencatatan) ada di AttendanceService,
    // dipakai bersama dengan halaman absen mandiri (QR). Absen dari admin langsung "verified".
    private function processAttendance(Member $member, string $method)
    {
        $result = app(AttendanceService::class)->checkInMember($member, (int) $this->selectedGymId, $method);

        if ($result['status'] === 'expired') {
            Notification::make()->title("Gagal: Membership {$member->name} Tidak Aktif!")->danger()->send();
        } elseif ($result['status'] === 'warning') {
            Notification::make()->title("{$member->name}: {$result['message']}")->warning()->send();
        } else {
            Notification::make()->title("Berhasil Absen: {$member->name}")->success()->send();
        }

        return $result;
    }

    // --- ACTION: MANUAL SUBMIT ---
    public function submitManualAttendance()
    {
        // Keamanan: Tolak submit jika belum pilih lokasi
        if (!$this->selectedGymId) {
            Notification::make()->title("Silakan pilih lokasi Gym terlebih dahulu!")->danger()->send();
            return;
        }

        $state = $this->form->getState();
        $type = $state['attendance_type'];

        if ($type === 'member') {
            $memberId = $state['member_id'];
            $member = Member::find($memberId);

            if ($member) {
                $result = $this->processAttendance($member, 'manual');
                $this->form->fill(['attendance_type' => 'member']);
                $this->dispatch('attendance-processed', result: $result);
            }
        } else {
            $visitorResult = app(AttendanceService::class)->checkInVisitor(
                (int) $this->selectedGymId,
                $state['visitor_name'],
                $state['visit_type'],
                $state['payment_method'] ?? null,
                'manual_visitor'
            );

            Notification::make()->title("Berhasil: Tamu {$state['visitor_name']} Masuk")->success()->send();

            $this->form->fill(['attendance_type' => 'visitor']);
            $this->dispatch('attendance-processed', result: $visitorResult);
        }
    }

    // --- ACTION: FACE SCAN SUBMIT ---
    public function submitFaceAttendance($memberId)
    {
        // Keamanan: Tolak dari javascript jika lokasi belum dipilih
        if (!$this->selectedGymId) {
            return ['status' => 'error', 'message' => 'Pilih lokasi terlebih dahulu.'];
        }

        $member = Member::find($memberId);

        if (!$member) {
            return ['status' => 'error', 'message' => 'Member not found.'];
        }

        return $this->processAttendance($member, 'face_scan');
    }

    // --- DATA WAJAH (DIPANGGIL JS HANYA SAAT PANEL SCAN DIBUKA) ---
    // Pakai query builder (bukan model) supaya hook auto-expire di Member
    // tidak jalan untuk ratusan baris dan tidak ada query write yang ikut terpicu.
    public function loadFaceMembers(): array
    {
        return DB::table('members')
            ->whereNotNull('face_descriptor')
            ->get(['id', 'name', 'face_descriptor'])
            ->map(fn($member) => [
                'id' => $member->id,
                'name' => $member->name,
                'descriptor' => $member->face_descriptor, // string JSON, di-parse oleh JS
            ])
            ->all();
    }

    // --- DATA PROVIDER ---
    public function getViewData(): array
    {
        return [
            'gyms' => Gymkos::whereIn('id', AttendanceService::GYM_IDS)->orderBy('id')->get(),
        ];
    }
}
