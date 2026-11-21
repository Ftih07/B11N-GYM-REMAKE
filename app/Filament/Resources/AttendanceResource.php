<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use App\Models\Gymkos;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    // --- BADGE DI MENU: jumlah absensi mandiri yang perlu dicek ---
    public static function getNavigationBadge(): ?string
    {
        $count = Attendance::where('status', Attendance::STATUS_PENDING)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Absensi yang perlu dicek';
    }

    // --- EAGER LOAD (hindari N+1 karena kolom memakai member & cabang) ---
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['member.gymkos', 'gymkos']);
    }

    // --- TABLE SCHEMA ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. CHECK-IN TIME
                TextColumn::make('check_in_time')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->description(fn(Attendance $record) => $record->check_in_time?->diffForHumans())
                    ->sortable(),

                // 2. NAME COLUMN (Hybrid Logic)
                TextColumn::make('member.name')
                    ->label('Nama Pengunjung')
                    ->getStateUsing(function (Attendance $record) {
                        // Logic: If member_id exists, show Member Name. If not, show Visitor Name.
                        return $record->member_id ? $record->member?->name : $record->visitor_name;
                    })
                    // Untuk absen mandiri, panggilan yang diketik ikut tampil supaya mudah dicocokkan
                    ->description(function (Attendance $record): string {
                        if (!$record->member_id) {
                            return 'Non-Member';
                        }

                        return $record->nickname
                            ? 'Member • panggilan: ' . $record->nickname
                            : 'Member Tetap';
                    })
                    ->sortable()
                    // Cari lewat nama, no. HP, dan Member ID. Dibungkus satu grup supaya
                    // tidak merusak kondisi lain (filter/tab) saat search dipakai.
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $q) use ($search) {
                            $q->where('visitor_name', 'like', "%{$search}%")
                                ->orWhereHas('member', function (Builder $m) use ($search) {
                                    $m->where('name', 'like', "%{$search}%")
                                        ->orWhere('member_code', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%");
                                });
                        });
                    }),

                // 3. MEMBER ID
                TextColumn::make('member.member_code')
                    ->label('Member ID')
                    ->fontFamily('mono')
                    ->placeholder('—')
                    ->copyable()
                    ->toggleable(),

                // 4. CABANG TEMPAT ABSEN (attendances.gymkos_id)
                TextColumn::make('gymkos.name')
                    ->label('Lokasi Absen')
                    ->badge()
                    ->color(fn(Attendance $record): string => match ((int) $record->gymkos_id) {
                        1 => 'warning',
                        2 => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                // 4b. STATUS VERIFIKASI
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(?string $state): string => Attendance::STATUS_LABELS[$state] ?? (string) $state)
                    ->color(fn(?string $state): string => match ($state) {
                        Attendance::STATUS_PENDING => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                // 5. CABANG ASAL MEMBER (sembunyi default, bisa dimunculkan lewat toggle kolom)
                TextColumn::make('member.gymkos.name')
                    ->label('Cabang Asal Member')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 6. VISIT TYPE (Badges)
                TextColumn::make('visit_type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'membership' => 'Membership',
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'membership' => 'success',
                        'daily' => 'info',
                        'weekly' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                // 7. METODE PEMBAYARAN (khusus tamu, boleh kosong)
                TextColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->formatStateUsing(fn(?string $state): ?string => Attendance::PAYMENT_METHODS[$state] ?? $state)
                    ->toggleable(),

                // 8. ATTENDANCE METHOD
                TextColumn::make('method')
                    ->label('Metode')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'face_scan' => 'success',
                        'self_scan' => 'info',
                        'manual_visitor' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'face_scan' => 'Scan Wajah',
                        'self_scan' => 'Scan QR (Mandiri)',
                        'manual' => 'Input Manual',
                        'manual_visitor' => 'Tamu (Admin)',
                        default => $state,
                    }),
            ])
            ->defaultSort('check_in_time', 'desc') // Show newest first
            ->striped()
            ->paginated([25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->persistFiltersInSession()
            ->emptyStateHeading('Belum ada data absensi')
            ->emptyStateDescription('Coba ubah filter atau pilih cabang lain.')

            // --- VERIFIKASI ABSENSI MANDIRI ---
            // Tombol hanya muncul di baris "Perlu Dicek". Yang sudah terverifikasi tidak bisa
            // diverifikasi / ditolak lagi. "Tolak" = hapus permanen, supaya data tetap bersih.
            ->actions([
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Attendance $record): bool => $record->status === Attendance::STATUS_PENDING)
                    ->action(function (Attendance $record): void {
                        $record->update(['status' => Attendance::STATUS_VERIFIED]);

                        Notification::make()->title('Absensi diverifikasi')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak dan hapus absensi ini?')
                    ->modalDescription('Absensi akan dihapus permanen dan tidak dihitung sebagai kunjungan.')
                    ->modalSubmitActionLabel('Ya, hapus')
                    ->visible(fn(Attendance $record): bool => $record->status === Attendance::STATUS_PENDING)
                    ->action(function (Attendance $record): void {
                        $record->delete();

                        Notification::make()->title('Absensi ditolak dan dihapus')->warning()->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // Hanya baris "Perlu Dicek" yang diproses; baris terverifikasi yang ikut terpilih dilewati.
                    BulkAction::make('verify_selected')
                        ->label('Verifikasi yang dipilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records): void {
                            $count = Attendance::whereIn('id', $records->pluck('id'))
                                ->where('status', Attendance::STATUS_PENDING)
                                ->update(['status' => Attendance::STATUS_VERIFIED]);

                            Notification::make()->title("{$count} absensi diverifikasi")->success()->send();
                        }),

                    BulkAction::make('reject_selected')
                        ->label('Tolak & hapus yang dipilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak dan hapus absensi terpilih?')
                        ->modalDescription('Hanya absensi "Perlu Dicek" yang dihapus permanen. Yang sudah terverifikasi tidak disentuh.')
                        ->modalSubmitActionLabel('Ya, hapus')
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records): void {
                            $count = Attendance::whereIn('id', $records->pluck('id'))
                                ->where('status', Attendance::STATUS_PENDING)
                                ->delete();

                            Notification::make()->title("{$count} absensi ditolak dan dihapus")->warning()->send();
                        }),
                ]),
            ])

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
            // Filter per cabang tempat absen ada di tab (lihat ListAttendances).
            ->filters([
                // Filter 0: Status verifikasi
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Attendance::STATUS_LABELS),

                // Filter 1: Hari ini
                Filter::make('today')
                    ->label('Hari ini')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->whereDate('check_in_time', today())),

                // Filter 2: Rentang tanggal
                Filter::make('check_in_time')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'] ?? null, fn(Builder $query, $date) => $query->whereDate('check_in_time', '>=', $date))
                            ->when($data['created_until'] ?? null, fn(Builder $query, $date) => $query->whereDate('check_in_time', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Dari ' . Carbon::parse($data['created_from'])->format('d M Y'))
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Sampai ' . Carbon::parse($data['created_until'])->format('d M Y'))
                                ->removeField('created_until');
                        }

                        return $indicators;
                    }),

                // Filter 3: Tipe kunjungan
                SelectFilter::make('visit_type')
                    ->label('Tipe Kunjungan')
                    ->options([
                        'membership' => 'Membership',
                        'daily' => 'Harian (Non-Member)',
                        'weekly' => 'Mingguan (Non-Member)',
                    ]),

                // Filter 4: Metode
                SelectFilter::make('method')
                    ->label('Metode')
                    ->options([
                        'face_scan' => 'Scan Wajah',
                        'self_scan' => 'Scan QR (Mandiri)',
                        'manual' => 'Manual Member',
                        'manual_visitor' => 'Manual Tamu',
                    ]),

                // Filter 4b: Metode pembayaran (tamu)
                SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options(Attendance::PAYMENT_METHODS),

                // Filter 5: Cabang asal member
                SelectFilter::make('home_gym')
                    ->label('Cabang Asal Member')
                    ->options(fn(): array => Gymkos::query()
                        ->whereIn('id', fn($sub) => $sub->select('gymkos_id')->from('members'))
                        ->orderBy('id')
                        ->pluck('name', 'id')
                        ->all())
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn(Builder $query, $gymId) => $query->whereHas('member', fn(Builder $m) => $m->where('gymkos_id', $gymId))
                        );
                    }),

                // Filter 6: Kunjungan lintas cabang (member absen di cabang selain cabang asalnya)
                Filter::make('cross_branch')
                    ->label('Kunjungan lintas cabang')
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->whereHas(
                        'member',
                        fn(Builder $m) => $m->whereColumn('members.gymkos_id', '!=', 'attendances.gymkos_id')
                    )),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3);
    }

    // --- GLOBAL SEARCH CONFIG ---
    // 1. Eager load relasi biar cepat
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['member', 'gymkos']);
    }

    // 2. Kolom apa saja yang bisa dicari
    public static function getGloballySearchableAttributes(): array
    {
        return ['member.name', 'member.member_code', 'visitor_name'];
    }

    // 3. Judul Utama di Hasil Pencarian (Nama Member atau Nama Tamu)
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->member_id ? $record->member->name : ($record->visitor_name ?? 'Tamu Tanpa Nama');
    }

    // 4. Detail Informatif di bawah Judul
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Status' => $record->member_id ? 'Member Tetap' : 'Tamu Non-Member',
            'Lokasi' => $record->gymkos?->name ?? '-',
            'Tipe Kunjungan' => match ($record->visit_type) {
                'membership' => 'Membership',
                'daily' => 'Harian',
                'weekly' => 'Mingguan',
                default => $record->visit_type,
            },
            'Waktu Masuk' => $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('d M Y, H:i') : '-',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
        ];
    }
}
