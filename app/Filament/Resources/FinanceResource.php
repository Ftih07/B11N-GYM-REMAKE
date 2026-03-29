<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceResource\Pages;
use App\Models\Finance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Exports\FinanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FinanceResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $model = Finance::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes'; // Ikon: Uang Kertas
    protected static ?string $navigationLabel = 'Rekap Keuangan';
    protected static ?string $pluralModelLabel = 'Laporan Keuangan';
    protected static ?string $navigationGroup = 'Laporan'; // Dikelompokkan di bawah "Laporan"
    protected static ?int $navigationSort = 1;

    // --- KONFIGURASI FORM (Input Pemasukan/Pengeluaran) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Input Keuangan')
                    ->schema([
                        // Pilihan Tipe: Pemasukan vs Pengeluaran
                        Forms\Components\Select::make('type')
                            ->label('Jenis Transaksi')
                            ->options([
                                'income' => 'Pemasukan',
                                'expense' => 'Pengeluaran',
                            ])
                            ->required()
                            ->default('expense'), // Bawaan ke pengeluaran

                        // Relasi: Menghubungkan catatan keuangan ke Cabang Gym tertentu
                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->required()
                            ->label('Cabang Gym / Kos'),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(now())
                            ->label('Tanggal'),

                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp') // Prefiks visual
                            ->label('Nominal'),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(255)
                            ->label('Keterangan')
                            ->placeholder('Contoh: Bayar Listrik Bulan Juni'),
                    ])
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan Laporan) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal'),

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Cabang')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(30),

                // Badge Tipe: Hijau untuk Pemasukan, Merah untuk Pengeluaran
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'income' => 'success', // Hijau
                        'expense' => 'danger', // Merah
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                        default => $state,
                    }),

                // Kolom Nominal: Diformat sebagai mata uang Rupiah
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->searchable()
                    ->money('IDR', locale: 'id') // Format lokal Indonesia
                    ->sortable()
                    ->color(fn($record) => $record->type === 'expense' ? 'danger' : 'success'),
            ])
            ->defaultSort('date', 'desc') // Urutkan dari tanggal terbaru

            // --- AKSI HEADER: EXPORT EXCEL ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Laporan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        // Filter Cabang (Gymkos)
                        Select::make('gymkos_id')
                            ->label('Cabang (Gym/Kos)')
                            ->options(\App\Models\Gymkos::all()->pluck('name', 'id'))
                            ->placeholder('Semua Cabang') // Memungkinkan opsi All (null)
                            ->default(null),

                        // Form Modal: Pilih Bulan & Tahun
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
                                $years = range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Ambil Data
                        $month = $data['month'];
                        $year = $data['year'];
                        $gymkosId = $data['gymkos_id'] ?? null;

                        // Bikin nama file dinamis biar jelas laporannya
                        $gymName = $gymkosId ? \App\Models\Gymkos::find($gymkosId)->name : 'Semua-Cabang';
                        $fileName = "Laporan-Keuangan-{$gymName}-{$month}-{$year}.xlsx";

                        // Memicu download menggunakan class 'FinanceExport'
                        return Excel::download(
                            new FinanceExport($month, $year, $gymkosId), // Tambah parameter ke-3
                            $fileName
                        );
                    }),
            ])

            // --- KONFIGURASI PENGELOMPOKAN (GROUPING) ---
            ->groups([
                // Kelompokkan baris berdasarkan Nama Cabang (Bisa di-collapse/tutup)
                Tables\Grouping\Group::make('gymkos.name')
                    ->label('Cabang')
                    ->collapsible(),
            ])
            ->defaultGroup('gymkos.name') // Aktifkan pengelompokan secara bawaan

            ->filters([
                // Filter dropdown: Tampilkan hanya Pemasukan atau Pengeluaran
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                    ]),

                // --- TAMBAHAN: Filter Bulan dan Tahun ---
                Filter::make('bulan_tahun')
                    ->label('Filter Bulan & Tahun')
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
                            ->placeholder('Semua Bulan'),
                        Select::make('year')
                            ->label('Tahun')
                            ->options(function () {
                                $years = range(Carbon::now()->year - 5, Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->placeholder('Semua Tahun'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['month'] ?? null,
                                fn(Builder $query, $month): Builder => $query->whereMonth('date', $month),
                            )
                            ->when(
                                $data['year'] ?? null,
                                fn(Builder $query, $year): Builder => $query->whereYear('date', $year),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['month'] ?? null) {
                            $monthName = Carbon::createFromFormat('m', $data['month'])->translatedFormat('F');
                            $indicators[] = 'Bulan: ' . $monthName;
                        }
                        if ($data['year'] ?? null) {
                            $indicators[] = 'Tahun: ' . $data['year'];
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Pilihan'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinances::route('/'),
            'create' => Pages\CreateFinance::route('/create'),
            'edit' => Pages\EditFinance::route('/{record}/edit'),
        ];
    }
}
