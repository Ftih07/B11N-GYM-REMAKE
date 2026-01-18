<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Member;
use App\Models\Attendance;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get; // Penting untuk logika kondisional

class AbsensiPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Absensi Harian';
    protected static ?string $title = 'Absensi Member & Tamu';
    protected static string $view = 'filament.pages.absensi-page';

    // Variabel penampung data form manual
    public ?array $data = [];

    // 1. MOUNT: Wajib ada biar form manual jalan
    public function mount(): void
    {
        $this->form->fill();
    }

    // 2. FORM SCHEMA: Dropdown Pencarian Canggih
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. Pilihan Mode Absensi
                Radio::make('attendance_type')
                    ->label('Tipe Pengunjung')
                    ->options([
                        'member' => 'Member Terdaftar',
                        'visitor' => 'Non-Member (Harian/Mingguan)',
                    ])
                    ->default('member')
                    ->inline()
                    ->live() // KUNCI: Biar form dibawahnya reaktif
                    ->afterStateUpdated(fn($state, callable $set) => $set('member_id', null)), // Reset member_id kalau ganti mode

                // 2. Input Khusus MEMBER (Hanya muncul jika pilih member)
                Select::make('member_id')
                    ->label('Cari Member')
                    ->placeholder('Ketik nama, email, atau no. hp...')
                    ->searchable()
                    ->visible(fn(Get $get) => $get('attendance_type') === 'member') // Kondisional
                    ->required(fn(Get $get) => $get('attendance_type') === 'member')
                    ->getSearchResultsUsing(function (string $search) {
                        return Member::query()
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->limit(10)
                            ->get()
                            ->mapWithKeys(function ($member) {
                                // HTML Tampilan di Dropdown
                                $avatarUrl = $member->picture
                                    ? asset('storage/' . $member->picture)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=random';

                                $html = '
                                    <div class="flex items-center gap-3 py-1">
                                        <img src="' . $avatarUrl . '" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        <div class="flex flex-col text-left">
                                            <span class="font-bold text-gray-800">' . $member->name . '</span>
                                            <span class="text-xs text-gray-500">' . $member->email . ' • ' . ($member->phone ?? '-') . '</span>
                                        </div>
                                    </div>
                                ';
                                return [$member->id => $html];
                            });
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $member = Member::find($value);
                        return $member ? $member->name : null;
                    })
                    ->allowHtml()
                    ->reactive(),

                // 3. Input Khusus NON-MEMBER (Hanya muncul jika pilih visitor)
                TextInput::make('visitor_name')
                    ->label('Nama Pengunjung')
                    ->required(fn(Get $get) => $get('attendance_type') === 'visitor')
                    ->visible(fn(Get $get) => $get('attendance_type') === 'visitor'),

                TextInput::make('visitor_phone')
                    ->label('No. WhatsApp / HP')
                    ->tel()
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
            ])
            ->statePath('data');
    }

    // --- 3. CORE LOGIC (Pusat Pemrosesan Absen) ---
    // Function ini private, dipakai oleh Manual maupun Face Scan biar logicnya SATU PINTU.
    private function processAttendance(Member $member, string $method)
    {
        // A. Cek Status Membership (Aktif/Tidak)
        if ($member->status !== 'active') {
            Notification::make()
                ->title("Gagal: Membership {$member->name} Tidak Aktif!")
                ->danger()
                ->send();

            return [
                'status' => 'expired',
                'member_name' => $member->name,
                'message' => 'Membership Expired / Tidak Aktif',
            ];
        }

        // B. Cek Double Absen (Hari ini sudah absen belum?)
        $exists = Attendance::where('member_id', $member->id)
            ->whereDate('check_in_time', today())
            ->exists();

        if ($exists) {
            Notification::make()->title("{$member->name} sudah absen hari ini!")->warning()->send();

            // Return data warning (tetap kirim info member buat ditampilkan)
            return [
                'status' => 'warning',
                'member_name' => $member->name,
                'message' => 'Sudah absen hari ini.',
                'photo_url' => $member->picture ? asset('storage/' . $member->picture) : null,
                'email' => $member->email ?? '-',
                'phone' => $member->phone ?? '-',
                'address' => $member->address ?? '-',
                'expired_date' => $member->membership_end_date ? $member->membership_end_date->format('d M Y') : '-',
            ];
        }

        // C. Rekam Absen Baru
        Attendance::create([
            'gymkos_id' => $member->gymkos_id,
            'member_id' => $member->id,
            'check_in_time' => now(),
            'method' => $method,
        ]);

        Notification::make()->title("Berhasil Absen: {$member->name}")->success()->send();

        // D. Return Data Sukses Lengkap (Untuk JS/Frontend)
        return [
            'status' => 'success',
            'member_name' => $member->name,
            'photo_url' => $member->picture ? asset('storage/' . $member->picture) : null,
            // Data Detail Tambahan
            'email' => $member->email ?? '-',
            'phone' => $member->phone ?? '-',
            'address' => $member->address ?? '-',
            'expired_date' => $member->membership_end_date ? $member->membership_end_date->format('d M Y') : '-',
        ];
    }

    // --- 4. ACTION: ABSEN MANUAL (Klik Tombol) ---
    public function submitManualAttendance()
    {
        $state = $this->form->getState();
        $type = $state['attendance_type'];

        // --- SKENARIO 1: MEMBER ---
        if ($type === 'member') {
            $memberId = $state['member_id'];
            $member = Member::find($memberId);

            if ($member) {
                $result = $this->processAttendance($member, 'manual');

                // Reset form & kirim event
                $this->form->fill(['attendance_type' => 'member']); // Reset ke default
                $this->dispatch('attendance-processed', result: $result);
            }
        }

        // --- SKENARIO 2: NON-MEMBER (VISITOR) ---
        else {
            // Simpan data Non-Member
            Attendance::create([
                'gymkos_id'     => 1, // Sesuaikan logika gymkos_id kamu (hardcode atau ambil dari auth user)
                'member_id'     => null, // Nullable
                'visitor_name'  => $state['visitor_name'],
                'visitor_phone' => $state['visitor_phone'],
                'visit_type'    => $state['visit_type'], // daily/weekly
                'check_in_time' => now(),
                'method'        => 'manual_visitor',
            ]);

            Notification::make()
                ->title("Berhasil: Tamu {$state['visitor_name']} Masuk")
                ->success()
                ->send();

            // Return result sederhana untuk JS (karena tamu tidak punya foto profil di DB)
            $visitorResult = [
                'status' => 'success',
                'member_name' => $state['visitor_name'] . ' (Non-Member)',
                'photo_url' => 'https://ui-avatars.com/api/?name=' . urlencode($state['visitor_name']) . '&background=random',
                'message' => 'Paket: ' . ucfirst($state['visit_type']),
                'expired_date' => 'Hari ini', // Atau hitung +1 hari/+7 hari
            ];

            $this->form->fill(['attendance_type' => 'visitor']); // Reset tapi tetep di tab visitor biar gampang input lagi
            $this->dispatch('attendance-processed', result: $visitorResult);
        }
    }

    // --- 5. ACTION: ABSEN WAJAH (Dari JS Face API) ---
    public function submitFaceAttendance($memberId)
    {
        $member = Member::find($memberId);

        if (!$member) {
            return ['status' => 'error', 'message' => 'Member tidak ditemukan.'];
        }

        // Panggil Core Logic & Return langsung hasilnya ke JS (Promise)
        return $this->processAttendance($member, 'face_scan');
    }

    // --- 6. DATA UNTUK FACE API (Initial Load) ---
    public function getViewData(): array
    {
        $members = Member::whereNotNull('face_descriptor')->get()->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'descriptor' => $member->face_descriptor,
            ];
        });

        return [
            'members_data' => $members->toArray(),
        ];
    }
}
