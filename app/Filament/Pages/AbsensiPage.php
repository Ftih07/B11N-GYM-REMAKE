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
use Filament\Forms\Get; // Essential for conditional logic

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

    // --- MOUNT ---
    // Initialize the form when the page loads
    public function mount(): void
    {
        $this->form->fill();
    }

    // --- FORM SCHEMA ---
    // Defines the layout and inputs of the manual attendance form
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. Attendance Mode Selection (Member vs Visitor)
                Radio::make('attendance_type')
                    ->label('Tipe Pengunjung')
                    ->options([
                        'member' => 'Member Terdaftar',
                        'visitor' => 'Non-Member (Harian/Mingguan)',
                    ])
                    ->default('member')
                    ->inline()
                    ->live() // Reactive: Updates the form fields below immediately
                    ->afterStateUpdated(fn($state, callable $set) => $set('member_id', null)), // Reset ID on switch

                // 2. MEMBER INPUT (Visible only if 'member' is selected)
                Select::make('member_id')
                    ->label('Cari Member')
                    ->placeholder('Ketik nama, email, atau no. hp...')
                    ->searchable()
                    ->visible(fn(Get $get) => $get('attendance_type') === 'member') // Conditional Visibility
                    ->required(fn(Get $get) => $get('attendance_type') === 'member')
                    // Logic: Search for members dynamically
                    ->getSearchResultsUsing(function (string $search) {
                        return Member::query()
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->limit(10)
                            ->get()
                            ->mapWithKeys(function ($member) {
                                // Logic: Render Custom HTML in Dropdown (Avatar + Info)
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
                    ->allowHtml() // Allow the custom HTML above to render
                    ->reactive(),

                // 3. VISITOR INPUTS (Visible only if 'visitor' is selected)
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
    // Central function to process member attendance (Used by both Manual & Face Scan)
    private function processAttendance(Member $member, string $method)
    {
        // A. Check Membership Status
        if ($member->status !== 'active') {
            Notification::make()->title("Gagal: Membership {$member->name} Tidak Aktif!")->danger()->send();
            return ['status' => 'expired', 'member_name' => $member->name, 'message' => 'Membership Expired'];
        }

        // B. Check Double Attendance (Prevent multiple check-ins per day)
        $exists = Attendance::where('member_id', $member->id)
            ->whereDate('check_in_time', today())
            ->exists();

        if ($exists) {
            Notification::make()->title("{$member->name} sudah absen hari ini!")->warning()->send();
            return [
                'status' => 'warning',
                'member_name' => $member->name,
                'message' => 'Sudah absen hari ini.',
                'photo_url' => $member->picture ? asset('storage/' . $member->picture) : null,
            ];
        }

        // C. Record New Attendance
        Attendance::create([
            'gymkos_id' => $member->gymkos_id,
            'member_id' => $member->id,
            'check_in_time' => now(),
            'method' => $method, // 'manual' or 'face_scan'
        ]);

        Notification::make()->title("Berhasil Absen: {$member->name}")->success()->send();

        // D. Return Success Data (For Frontend Display)
        return [
            'status' => 'success',
            'member_name' => $member->name,
            'photo_url' => $member->picture ? asset('storage/' . $member->picture) : null,
            'email' => $member->email ?? '-',
            'phone' => $member->phone ?? '-',
            'address' => $member->address ?? '-',
            'expired_date' => $member->membership_end_date ? $member->membership_end_date->format('d M Y') : '-',
        ];
    }

    // --- ACTION: MANUAL SUBMIT ---
    // Triggered when the admin clicks the submit button on the form
    public function submitManualAttendance()
    {
        $state = $this->form->getState();
        $type = $state['attendance_type'];

        // Scenario 1: Registered Member
        if ($type === 'member') {
            $memberId = $state['member_id'];
            $member = Member::find($memberId);

            if ($member) {
                $result = $this->processAttendance($member, 'manual');
                $this->form->fill(['attendance_type' => 'member']); // Reset Form
                $this->dispatch('attendance-processed', result: $result); // Send event to JS
            }
        }
        // Scenario 2: Visitor (Non-Member)
        else {
            Attendance::create([
                'gymkos_id'     => 1, // Default Gym ID
                'member_id'     => null,
                'visitor_name'  => $state['visitor_name'],
                'visitor_phone' => $state['visitor_phone'],
                'visit_type'    => $state['visit_type'],
                'check_in_time' => now(),
                'method'        => 'manual_visitor',
            ]);

            Notification::make()->title("Berhasil: Tamu {$state['visitor_name']} Masuk")->success()->send();

            // Return simple result for JS
            $visitorResult = [
                'status' => 'success',
                'member_name' => $state['visitor_name'] . ' (Non-Member)',
                'photo_url' => 'https://ui-avatars.com/api/?name=' . urlencode($state['visitor_name']) . '&background=random',
                'message' => 'Paket: ' . ucfirst($state['visit_type']),
            ];

            $this->form->fill(['attendance_type' => 'visitor']); // Reset Form
            $this->dispatch('attendance-processed', result: $visitorResult);
        }
    }

    // --- ACTION: FACE SCAN SUBMIT ---
    // Triggered by JavaScript Face API when a face is detected
    public function submitFaceAttendance($memberId)
    {
        $member = Member::find($memberId);

        if (!$member) {
            return ['status' => 'error', 'message' => 'Member not found.'];
        }

        return $this->processAttendance($member, 'face_scan');
    }

    // --- DATA PROVIDER ---
    // Sends Face Descriptors to the Frontend for the Face API to load
    public function getViewData(): array
    {
        $members = Member::whereNotNull('face_descriptor')->get()->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'descriptor' => $member->face_descriptor, // This is the mathematical representation of the face
            ];
        });

        return [
            'members_data' => $members->toArray(),
        ];
    }
}
