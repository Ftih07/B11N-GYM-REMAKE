<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Exports\SurveyExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;

class SurveyResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    public static function getNavigationBadge(): ?string
    {
        return Survey::count() ?: null;
    }

    protected static ?string $model = Survey::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Data Survei';
    protected static ?string $pluralModelLabel = 'Hasil Survei Pengunjung';
    protected static ?string $navigationGroup = 'Manajemen Gym';
    protected static ?int $navigationSort = 3;

    // Nonaktifkan tombol "Buat Survei Baru" (Data hanya masuk dari form publik)
    public static function canCreate(): bool
    {
        return false;
    }

    // --- KONFIGURASI FORM (Hanya Baca / Review) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Bagian 1: Identitas
                Forms\Components\Section::make('Identitas Responden')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap'),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor WhatsApp'),

                        // Toggle: Apakah Member Aktif?
                        Forms\Components\Toggle::make('is_membership')
                            ->label('Member Aktif?')
                            ->inline(false),
                    ])->columns(3),

                // Bagian 2: Detail Keanggotaan (Muncul jika kondisi terpenuhi)
                Forms\Components\Section::make('Detail Keanggotaan')
                    ->schema([
                        Forms\Components\TextInput::make('member_duration')
                            ->label('Durasi Menjadi Member'),
                        Forms\Components\TextInput::make('renewal_chance')
                            ->label('Peluang Perpanjang (Skala 1-5)'),
                    ])
                    // Hanya tampilkan bagian ini jika 'is_membership' bernilai TRUE
                    ->visible(fn($record) => $record?->is_membership ?? false)
                    ->columns(2),

                // Bagian 3: Hasil Survei
                Forms\Components\Section::make('Hasil Survei')
                    ->schema([
                        Forms\Components\TextInput::make('fitness_goal')
                            ->label('Tujuan Fitness'),
                        Forms\Components\TextInput::make('rating_equipment')
                            ->label('Penilaian Alat (Rating)'),
                        Forms\Components\TextInput::make('rating_cleanliness')
                            ->label('Penilaian Kebersihan (Rating)'),

                        Forms\Components\TextInput::make('nps_score')
                            ->label('Skor NPS (1-10)')
                            ->numeric(),

                        Forms\Components\Textarea::make('feedback')
                            ->label('Ulasan & Saran')
                            ->columnSpanFull(),
                    ])->columns(4),

                // Bagian 4: Marketing / Promo
                Forms\Components\Section::make('Marketing')
                    ->schema([
                        Forms\Components\TextInput::make('promo_interest')
                            ->label('Minat Promo')
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'paket_a' => 'Paket A (6+2)',
                                'paket_b' => 'Paket B (9+3)',
                                'paket_c' => 'Paket C (12+4)',
                                default => 'Tidak Tertarik',
                            }),
                    ]),
            ]);
    }

    // --- KONFIGURASI TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->label('Waktu Masuk')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Responden')
                    ->searchable()
                    ->description(fn(Survey $record): string => $record->phone ?? '-'),

                Tables\Columns\IconColumn::make('is_membership')
                    ->label('Member?')
                    ->boolean(),

                // Badge untuk Minat Promo
                Tables\Columns\TextColumn::make('promo_interest')
                    ->label('Minat Promo')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'paket_a' => 'Paket A',
                        'paket_b' => 'Paket B',
                        'paket_c' => 'Paket C',
                        default => 'Tidak Minat',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'paket_c' => 'success',
                        'paket_a', 'paket_b' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('nps_score')
                    ->label('Skor NPS')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info'),
            ])

            // --- AKSI HEADER: EXPORT EXCEL ---
            ->headerActions([
                // TOMBOL 1: EXPORT SEMUA DATA (Langsung download tanpa form)
                Action::make('export_all')
                    ->label('Export Semua Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        // Langsung tembak 'all' dari sistem, bukan dari form user
                        return Excel::download(
                            new SurveyExport('all', 'all'),
                            'Hasil-Survei-Gym-Semua-Data.xlsx'
                        );
                    }),

                // TOMBOL 2: EXPORT FILTER BULAN & TAHUN (Pakai Form)
                Action::make('export_filter')
                    ->label('Export Per Bulan')
                    ->icon('heroicon-o-calendar-days')
                    ->color('warning') // Pakai warna beda biar gampang dibedakan
                    ->form([
                        Select::make('month')
                            ->label('Bulan')
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
                            ->required(),
                        Select::make('year')
                            ->label('Tahun')
                            ->options(function () {
                                $years = range(Carbon::now()->year - 2, Carbon::now()->year + 1);
                                return array_combine($years, $years); // Opsi 'all' dihapus
                            })
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return Excel::download(
                            new SurveyExport($data['month'], $data['year']),
                            'Hasil-Survei-Gym-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('promo_interest')
                    ->options([
                        'paket_a' => 'Paket A',
                        'paket_b' => 'Paket B',
                        'paket_c' => 'Paket C',
                    ])->label('Filter Minat Promo'),

                Tables\Filters\Filter::make('is_membership')
                    ->query(fn($query) => $query->where('is_membership', true))
                    ->label('Hanya Tampilkan Member'),
            ])
            ->actions([
                // Gunakan ViewAction untuk popup detail tanpa bisa diedit
                Tables\Actions\ViewAction::make()->label('Lihat Detail'),
                Tables\Actions\DeleteAction::make()->label('Hapus'), // Tambah fitur hapus untuk bersih-bersih data
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Pilihan'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            // Halaman 'create' dan 'edit' dihapus karena read-only
        ];
    }
}
