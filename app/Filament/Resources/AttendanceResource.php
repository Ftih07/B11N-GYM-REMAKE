<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Resources\Resource;
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
    // --- RESOURCE CONFIG ---
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Rekap Absensi'; // Menu Label
    protected static ?string $pluralModelLabel = 'Data Absensi'; // Page Title
    protected static ?string $navigationGroup = 'Laporan'; // Grouping
    protected static ?int $navigationSort = 1;

    // --- DISABLE CREATE BUTTON ---
    // Attendance should be created via the "Absensi Page", not here.
    public static function canCreate(): bool
    {
        return false;
    }

    // --- TABLE SCHEMA ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. NAME COLUMN (Hybrid Logic)
                TextColumn::make('member.name')
                    ->label('Nama Pengunjung')
                    ->searchable(['visitor_name']) // Allow searching by visitor name too
                    ->getStateUsing(function (Attendance $record) {
                        // Logic: If member_id exists, show Member Name. If not, show Visitor Name.
                        return $record->member_id ? $record->member->name : $record->visitor_name;
                    })
                    ->description(
                        fn(Attendance $record) =>
                        $record->member_id
                            ? 'Member Tetap'
                            : 'Non-Member (' . ($record->visitor_phone ?? '-') . ')'
                    )
                    ->sortable(),

                // 2. VISIT TYPE (Badges)
                TextColumn::make('visit_type')
                    ->label('Tipe')
                    ->badge() // Display as a colored badge
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'member' => 'Membership',
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'member' => 'success', // Green
                        'daily' => 'info',    // Blue
                        'weekly' => 'warning', // Orange
                        default => 'gray',
                    })
                    ->sortable(),

                // 3. CHECK-IN TIME
                TextColumn::make('check_in_time')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                // 4. ATTENDANCE METHOD
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
            ->defaultSort('check_in_time', 'desc') // Show newest first

            // --- HEADER ACTION: EXPORT EXCEL ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        // Modal Form: Select Month & Year
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
                                $years = range(Carbon::now()->year - 5, Carbon::now()->year + 1);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Trigger Download
                        return Excel::download(
                            new AttendanceExport($data['month'], $data['year']),
                            'Laporan-Absensi-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])

            // --- FILTERS ---
            ->filters([
                // Filter 1: By Visit Type
                SelectFilter::make('visit_type')
                    ->label('Filter Tipe Kunjungan')
                    ->options([
                        'member' => 'Membership',
                        'daily' => 'Harian (Non-Member)',
                        'weekly' => 'Mingguan (Non-Member)',
                    ]),

                // Filter 2: By Method
                SelectFilter::make('method')
                    ->label('Filter Metode')
                    ->options([
                        'face_scan' => 'Scan Wajah',
                        'manual' => 'Manual Member',
                        'manual_visitor' => 'Manual Tamu',
                    ]),

                // Filter 3: Date Range
                Filter::make('check_in_time')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn(Builder $query, $date) => $query->whereDate('check_in_time', '>=', $date))
                            ->when($data['created_until'], fn(Builder $query, $date) => $query->whereDate('check_in_time', '<=', $date));
                    }),
            ]);
    }

    // --- GLOBAL SEARCH CONFIG ---
    // Allows searching attendance records from the top bar
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
