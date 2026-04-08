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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MemberResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    public static function getNavigationBadge(): ?string
    {
        return Member::count();
    }

    protected static ?string $model = Member::class;
    protected static ?string $navigationIcon = 'heroicon-o-users'; // Ikon: Pengguna
    protected static ?string $navigationGroup = 'Membership & Absensi';
    protected static ?string $navigationLabel = 'Manajemen Membership';
    protected static ?string $pluralModelLabel = 'Data Membership';
    protected static ?int $navigationSort = 2;

    // --- KONFIGURASI FORM (Tambah/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Member')
                    ->columns(2)
                    ->schema([
                        // Relasi ke Cabang Gym
                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->label('Cabang (Gym/Kos)')
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->helperText('Opsional')
                            ->email(),

                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor HP / WhatsApp')
                            ->helperText('Opsional')
                            ->tel(),

                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->rows(2)
                            ->helperText('Opsional')
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('join_date')
                            ->label('Tanggal Bergabung')
                            ->default(now())
                            ->required(),

                        // --- LOGIKA STATUS REAL-TIME ---
                        Forms\Components\DatePicker::make('membership_end_date')
                            ->label('Berlaku Sampai')
                            ->required()
                            ->live() // Memantau perubahan
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                // Logika: Jika tanggal adalah hari ini atau masa depan -> Aktif, jika tidak -> Tidak Aktif
                                if ($state) {
                                    $isActive = \Carbon\Carbon::parse($state)->endOfDay()->isFuture() || \Carbon\Carbon::parse($state)->isToday();
                                    $set('status', $isActive ? 'active' : 'inactive');
                                }
                            }),

                        // Status diatur otomatis oleh logika tanggal di atas
                        Forms\Components\Select::make('status')
                            ->label('Status Membership')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                            ])
                            ->default('active')
                            ->required()
                            ->dehydrated(), // Memastikan nilai tetap tersimpan meskipun disabled

                        // --- INPUT WEBCAM & DETEKSI WAJAH ---

                        // 1. Tampilan Webcam Kustom
                        ViewField::make('picture')
                            ->view('filament.forms.components.webcam-input') // Memuat view Blade kustom
                            ->viewData([
                                'descriptorField' => 'face_descriptor' // Meneruskan nama field target untuk data AI
                            ])
                            ->label('Foto Wajah')
                            ->columnSpanFull(),

                        // 2. Deskriptor Wajah (Data AI Tersembunyi)
                        Forms\Components\Textarea::make('face_descriptor')
                            ->label('Deskriptor Wajah (Dibuat Otomatis)')
                            ->rows(3)
                            ->readOnly() // Mencegah edit manual
                            ->helperText('Otomatis terisi saat wajah terdeteksi di kamera.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Table $table): Table
    {
        return $table
            // Default Sort berdasarkan yang terakhir diupdate
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('picture')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Member')
                    ->searchable(['name', 'email']),

                // Masa Berlaku & Selisih Waktu
                Tables\Columns\TextColumn::make('membership_end_date')
                    ->label('Masa Berlaku')
                    ->date('d M Y')
                    ->description(fn(Member $record) => $record->membership_end_date?->diffForHumans()),

                // Badge Status
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Expired',
                    }),

                Tables\Columns\TextColumn::make('join_date')
                    ->label('Tanggal Gabung')
                    ->date('d M Y'),

                // TAMBAHAN: LOKASI CABANG (GYMKOS)
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Lokasi Cabang')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),
            ])

            // --- AKSI HEADER: EXPORT EXCEL DINAMIS ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Data Member')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->form([
                        // Filter Cabang (Gymkos)
                        Select::make('gymkos_id')
                            ->label('Cabang (Gym/Kos)')
                            ->options(\App\Models\Gymkos::all()->pluck('name', 'id'))
                            ->placeholder('Semua Cabang') // Kosong berarti 'All'
                            ->default(null),

                        // 1. Pilihan Mode Export
                        Radio::make('mode')
                            ->label('Pilih Tipe Export')
                            ->options([
                                'all' => 'Semua Data Member (Keseluruhan)',
                                'period' => 'Filter Berdasarkan Bulan Bergabung',
                            ])
                            ->default('all')
                            ->live(), // Mengubah visibilitas input di bawahnya

                        // 2. Pemilih Bulan (Muncul hanya jika mode == period)
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

                        // 3. Pemilih Tahun (Muncul hanya jika mode == period)
                        Select::make('year')
                            ->label('Tahun Bergabung')
                            ->options(function () {
                                $years = range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->visible(fn(Get $get) => $get('mode') === 'period')
                            ->required(fn(Get $get) => $get('mode') === 'period'),
                    ])
                    ->action(function (array $data) {
                        // Ambil variabel Gymkos
                        $gymkosId = $data['gymkos_id'] ?? null;
                        $gymName = $gymkosId ? \App\Models\Gymkos::find($gymkosId)->name : 'Semua-Cabang';

                        // Tentukan Nama File & Parameter
                        if ($data['mode'] === 'all') {
                            $filename = "Semua-Data-Member-{$gymName}-" . date('d-m-Y') . ".xlsx";
                            $month = null;
                            $year = null;
                        } else {
                            $filename = "Data-Member-Join-{$gymName}-" . $data['month'] . "-" . $data['year'] . ".xlsx";
                            $month = $data['month'];
                            $year = $data['year'];
                        }

                        // Memicu Download
                        return Excel::download(
                            new MemberExport($data['mode'], $month, $year, $gymkosId),
                            $filename
                        );
                    }),
            ])
            // --- FILTER TABEL ---
            ->filters([
                // Filter berdasarkan relasi Gymkos
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Cabang Gym / Kos')
                    ->searchable()
                    ->preload(),

                // Filter tambahan: berdasarkan Status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif (Expired)',
                    ])
                    ->label('Status Membership'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'), // Sekalian saya tambahkan tombol Hapus
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Pilihan'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    // --- PENGATURAN PENCARIAN GLOBAL ---
    // 1. Kolom utama pencarian
    protected static ?string $recordTitleAttribute = 'name';

    // 2. Kolom yang bisa dicari dari global search
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }

    // 3. Eager load relasi gymkos biar query nggak bengkak (N+1)
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['gymkos']);
    }

    // 4. Judul Utama Pencarian
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    // 5. Detail Informatif di bawah Judul
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'No. HP' => $record->phone ?? '-',
            'Cabang' => $record->gymkos ? $record->gymkos->name : '-',
            'Status' => $record->status === 'active' ? '🟢 Aktif' : '🔴 Expired',
            'Expired Pada' => $record->membership_end_date ? \Carbon\Carbon::parse($record->membership_end_date)->format('d M Y') : '-',
        ];
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
