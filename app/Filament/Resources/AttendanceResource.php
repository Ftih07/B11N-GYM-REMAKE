<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Rekap Absensi';
    protected static ?string $pluralModelLabel = 'Data Absensi';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;

    // Kita matikan tombol "Create" karena absen inputnya dari Halaman AbsensiPage, bukan dari sini
    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. KOLOM NAMA (Hybrid: Member / Visitor)
                TextColumn::make('member.name')
                    ->label('Nama Pengunjung')
                    ->searchable(['visitor_name']) // Bisa search visitor juga
                    ->getStateUsing(function (Attendance $record) {
                        // Logika: Kalau ada member_id, ambil nama member. Kalau ga ada, ambil visitor_name
                        return $record->member_id ? $record->member->name : $record->visitor_name;
                    })
                    ->description(
                        fn(Attendance $record) =>
                        $record->member_id
                            ? 'Member Tetap'
                            : 'Non-Member (' . ($record->visitor_phone ?? '-') . ')'
                    )
                    ->sortable(),

                // 2. KOLOM TIPE KUNJUNGAN (Badge Warna-warni)
                TextColumn::make('visit_type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'member' => 'Membership',
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'member' => 'success', // Hijau
                        'daily' => 'info',    // Biru
                        'weekly' => 'warning', // Kuning/Oranye
                        default => 'gray',
                    })
                    ->sortable(),

                // 3. WAKTU CHECK-IN
                TextColumn::make('check_in_time')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                // 4. METODE ABSEN
                TextColumn::make('method')
                    ->label('Metode')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'face_scan' => 'Scan Wajah',
                        'manual' => 'Input Manual',
                        'manual_visitor' => 'Tamu (Admin)',
                        default => $state,
                    }),
            ])
            ->defaultSort('check_in_time', 'desc') // Data terbaru paling atas

            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
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
                            ->default(now()->format('m')) // Default bulan sekarang
                            ->required(),
                        Select::make('year')
                            ->label('Tahun')
                            ->options(function () {
                                // Generate tahun dari 5 tahun lalu sampai 1 tahun ke depan
                                $years = range(Carbon::now()->year - 5, Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year) // Default tahun sekarang
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $filename = 'Laporan-Absensi-' . $data['month'] . '-' . $data['year'] . '.xlsx';

                        return Excel::download(
                            new AttendanceExport($data['month'], $data['year']),
                            $filename
                        );
                    }),
            ])

            // --- BAGIAN FILTER ---
            ->filters([
                // Filter 1: Berdasarkan Tipe (Member vs Harian vs Mingguan)
                SelectFilter::make('visit_type')
                    ->label('Filter Tipe Kunjungan')
                    ->options([
                        'member' => 'Membership',
                        'daily' => 'Harian (Non-Member)',
                        'weekly' => 'Mingguan (Non-Member)',
                    ]),

                // Filter 2: Berdasarkan Metode (Scan vs Manual)
                SelectFilter::make('method')
                    ->label('Filter Metode')
                    ->options([
                        'face_scan' => 'Scan Wajah',
                        'manual' => 'Manual Member',
                        'manual_visitor' => 'Manual Tamu',
                    ]),

                // Filter 3: Rentang Tanggal (Date Range)
                Filter::make('check_in_time')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('check_in_time', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('check_in_time', '<=', $date),
                            );
                    })
            ]);
    }

    // Tambahkan ini di dalam class AttendanceResource

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['member']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['member.name', 'visitor_name', 'visitor_phone'];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
        ];
    }
}
