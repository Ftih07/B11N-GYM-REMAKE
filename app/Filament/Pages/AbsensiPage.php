<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\Gymkos; // PASTIKAN MODEL INI DI-IMPORT
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;

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
        $this->selectedGymId = $id;
    }

    public function resetGym()
    {
        $this->selectedGymId = null;
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
                    ->placeholder('Ketik nama, email, atau no. hp...')
                    ->searchable()
                    ->visible(fn(Get $get) => $get('attendance_type') === 'member')
                    ->required(fn(Get $get) => $get('attendance_type') === 'member')
                    ->getSearchResultsUsing(function (string $search) {
                        return Member::query()
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->limit(10)
                            ->get()
                            ->mapWithKeys(function ($member) {
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
                    ->allowHtml()
                    ->reactive(),

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

    // --- CORE LOGIC ---
    private function processAttendance(Member $member, string $method)
    {
        // Kumpulkan data member terlebih dahulu agar rapi dan tidak diulang-ulang
        $memberData = [
            'member_name' => $member->name,
            'photo_url' => $member->picture ? asset('storage/' . $member->picture) : null,
            'email' => $member->email ?? '-',
            'phone' => $member->phone ?? '-',
            'address' => $member->address ?? '-',
            // Gunakan Carbon parse untuk berjaga-jaga jika datanya berupa string
            'expired_date' => $member->membership_end_date ? \Carbon\Carbon::parse($member->membership_end_date)->format('d M Y') : '-',
        ];

        if ($member->status !== 'active') {
            Notification::make()->title("Gagal: Membership {$member->name} Tidak Aktif!")->danger()->send();
            // Gabungkan status expired dengan data member
            return array_merge(['status' => 'expired', 'message' => 'Membership Expired'], $memberData);
        }

        $exists = Attendance::where('member_id', $member->id)
            ->whereDate('check_in_time', today())
            ->exists();

        if ($exists) {
            Notification::make()->title("{$member->name} sudah absen hari ini!")->warning()->send();
            // PERBAIKAN: Gabungkan status warning dengan data member lengkap!
            return array_merge(['status' => 'warning', 'message' => 'Sudah absen hari ini.'], $memberData);
        }

        Attendance::create([
            'gymkos_id' => $this->selectedGymId,
            'member_id' => $member->id,
            'check_in_time' => now(),
            'method' => $method,
        ]);

        Notification::make()->title("Berhasil Absen: {$member->name}")->success()->send();

        // Gabungkan status success dengan data member lengkap
        return array_merge(['status' => 'success'], $memberData);
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
            Attendance::create([
                'gymkos_id'     => $this->selectedGymId,
                'member_id'     => null,
                'visitor_name'  => $state['visitor_name'],
                'visitor_phone' => $state['visitor_phone'],
                'visit_type'    => $state['visit_type'],
                'check_in_time' => now(),
                'method'        => 'manual_visitor',
            ]);

            Notification::make()->title("Berhasil: Tamu {$state['visitor_name']} Masuk")->success()->send();

            // PERBAIKAN: Tambahkan data No Telp, Email kosong, dan Masa Berlaku untuk Tamu
            $visitorResult = [
                'status' => 'success',
                'member_name' => $state['visitor_name'] . ' (Non-Member)',
                'photo_url' => 'https://ui-avatars.com/api/?name=' . urlencode($state['visitor_name']) . '&background=random',
                'message' => 'Paket: ' . ucfirst($state['visit_type']),
                'email' => '-',
                'phone' => $state['visitor_phone'] ?? '-',
                'expired_date' => $state['visit_type'] === 'weekly' ? now()->addDays(7)->format('d M Y') : 'Hanya Berlaku Hari Ini',
            ];

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

    // --- DATA PROVIDER ---
    public function getViewData(): array
    {
        $members = Member::whereNotNull('face_descriptor')->get()->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'descriptor' => $member->face_descriptor,
            ];
        });

        // Ambil data Gym untuk tombol pemilihan
        $gyms = Gymkos::all();

        return [
            'members_data' => $members->toArray(),
            'gyms' => $gyms,
        ];
    }
}
