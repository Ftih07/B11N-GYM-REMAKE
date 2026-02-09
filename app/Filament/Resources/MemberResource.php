<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\ViewField;
use App\Exports\MemberExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Get;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;

class MemberResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    public static function getNavigationBadge(): ?string
    {
        return Member::count();
    }

    protected static ?string $model = Member::class;
    protected static ?string $navigationIcon = 'heroicon-o-users'; // Icon: Users
    protected static ?string $navigationGroup = 'Membership & Absensi';
    protected static ?string $navigationLabel = 'Manajemen Membership';
    protected static ?string $pluralModelLabel = 'Data Membership';
    protected static ?int $navigationSort = 2;

    // --- FORM CONFIGURATION (Create/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Member')
                    ->columns(2)
                    ->schema([
                        // Relationship to Gym Branch
                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')->email(),
                        Forms\Components\TextInput::make('phone')->tel(),

                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('join_date')
                            ->default(now())
                            ->required(),

                        // --- REAL-TIME STATUS LOGIC ---
                        Forms\Components\DatePicker::make('membership_end_date')
                            ->label('Berlaku Sampai')
                            ->required()
                            ->live() // Listens for changes
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                // Logic: If date is today or future -> Active, else -> Inactive
                                if ($state) {
                                    $isActive = \Carbon\Carbon::parse($state)->endOfDay()->isFuture() || \Carbon\Carbon::parse($state)->isToday();
                                    $set('status', $isActive ? 'active' : 'inactive');
                                }
                            }),

                        // Status is auto-set by date logic above
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                            ])
                            ->default('active')
                            ->required()
                            ->dehydrated(), // Ensures value is saved even if disabled

                        // --- WEBCAM & FACE RECOGNITION INPUT ---

                        // 1. Custom Webcam View
                        ViewField::make('picture')
                            ->view('filament.forms.components.webcam-input') // Loads custom Blade view
                            ->viewData([
                                'descriptorField' => 'face_descriptor' // Pass target field name for AI data
                            ])
                            ->label('Foto Wajah')
                            ->columnSpanFull(),

                        // 2. Face Descriptor (Hidden AI Data)
                        Forms\Components\Textarea::make('face_descriptor')
                            ->label('Face Descriptor (Auto Generated)')
                            ->rows(3)
                            ->readOnly() // Prevent manual editing
                            ->required() // Must be generated from webcam
                            ->helperText('Otomatis terisi saat wajah terdeteksi di kamera.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('picture')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable(),

                // Membership Expiry & Diff
                Tables\Columns\TextColumn::make('membership_end_date')
                    ->label('Masa Berlaku')
                    ->date()
                    ->description(fn(Member $record) => $record->membership_end_date?->diffForHumans()),

                // Status Badge
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Expired',
                    }),

                Tables\Columns\TextColumn::make('join_date')->date(),
            ])

            // --- HEADER ACTION: DYNAMIC EXCEL EXPORT ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Member')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->form([
                        // 1. Export Mode Selection (All vs Period)
                        Radio::make('mode')
                            ->label('Pilih Tipe Export')
                            ->options([
                                'all' => 'Semua Data Member (All Time)',
                                'period' => 'Filter Berdasarkan Bulan Bergabung',
                            ])
                            ->default('all')
                            ->live(), // Toggles visibility of inputs below

                        // 2. Month Selector (Visible only if mode == period)
                        Select::make('month')
                            ->label('Bulan Bergabung')
                            ->options([
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ])
                            ->default(now()->format('m'))
                            ->visible(fn(Get $get) => $get('mode') === 'period')
                            ->required(fn(Get $get) => $get('mode') === 'period'),

                        // 3. Year Selector (Visible only if mode == period)
                        Select::make('year')
                            ->label('Tahun Bergabung')
                            ->options(function () {
                                $years = range(Carbon::now()->year - 5, Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->visible(fn(Get $get) => $get('mode') === 'period')
                            ->required(fn(Get $get) => $get('mode') === 'period'),
                    ])
                    ->action(function (array $data) {
                        // Determine Filename & Params
                        if ($data['mode'] === 'all') {
                            $filename = 'Semua-Data-Member-' . date('d-m-Y') . '.xlsx';
                            $month = null;
                            $year = null;
                        } else {
                            $filename = 'Data-Member-Join-' . $data['month'] . '-' . $data['year'] . '.xlsx';
                            $month = $data['month'];
                            $year = $data['year'];
                        }

                        // Trigger Download
                        return Excel::download(
                            new MemberExport($data['mode'], $month, $year),
                            $filename
                        );
                    }),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
