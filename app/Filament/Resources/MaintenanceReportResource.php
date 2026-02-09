<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceReportResource\Pages;
use App\Models\MaintenanceReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Exports\MaintenanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;

class MaintenanceReportResource extends Resource
{
    // --- NAVIGATION SETTINGS ---

    // Badge: Shows total count of reports in the sidebar
    public static function getNavigationBadge(): ?string
    {
        return MaintenanceReport::count();
    }

    protected static ?string $model = MaintenanceReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Gym Management';
    protected static ?string $navigationLabel = 'Laporan Kerusakan';
    protected static ?int $navigationSort = 3;

    // --- FORM CONFIGURATION ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SECTION 1: Report Information (Read-Only / Disabled)
                // This ensures admin sees exactly what user reported without accidental edits
                Forms\Components\Section::make('Informasi Laporan')
                    ->description('Data yang dikirim oleh pelapor (Read Only)')
                    ->schema([
                        Forms\Components\TextInput::make('reporter_name')
                            ->label('Nama Pelapor')
                            ->readOnly(),

                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->label('Lokasi')
                            ->disabled()
                            ->dehydrated(), // IMPORTANT: Ensures value is saved even if input is disabled

                        Forms\Components\Select::make('equipment_id')
                            ->relationship('equipment', 'name')
                            ->label('Alat')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('severity')
                            ->label('Tingkat Keparahan')
                            ->readOnly(),

                        Forms\Components\Textarea::make('description')
                            ->label('Keluhan / Masalah')
                            ->columnSpanFull()
                            ->readOnly(),

                        // Evidence Photo Viewer
                        Forms\Components\FileUpload::make('evidence_photo')
                            ->label('Bukti Foto')
                            ->image()
                            ->directory('maintenance-evidence')
                            ->downloadable() // Admin can download original file
                            ->openable() // Admin can zoom/view file in new tab
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(2),

                // SECTION 2: Admin Action (Status Update)
                Forms\Components\Section::make('Tindak Lanjut (Admin)')
                    ->description('Update status perbaikan disini.')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending (Belum Dicek)',
                                'in_progress' => 'Sedang Diperbaiki',
                                'resolved' => 'Selesai (Resolved)',
                                'wont_fix' => 'Tidak Bisa Diperbaiki',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\DateTimePicker::make('fixed_at')
                            ->label('Tanggal Selesai')
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    // --- TABLE CONFIGURATION ---
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Newest reports first
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Lapor')
                    ->dateTime('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('equipment.name')
                    ->label('Alat')
                    ->searchable()
                    // Limit description length in table view
                    ->description(fn(MaintenanceReport $record): string => \Illuminate\Support\Str::limit($record->description, 30)),

                Tables\Columns\ImageColumn::make('evidence_photo')
                    ->label('Foto')
                    ->circular(),

                // Severity Badge (Green/Low -> Red/Critical)
                Tables\Columns\BadgeColumn::make('severity')
                    ->label('Keparahan')
                    ->colors([
                        'success' => 'low',
                        'warning' => 'medium',
                        'danger' => ['high', 'critical'],
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                // Status Badge
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'pending',
                        'warning' => 'in_progress',
                        'success' => 'resolved',
                        'danger' => 'wont_fix',
                    ]),
            ])

            // --- HEADER ACTION: EXCEL EXPORT ---
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Laporan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        // Modal Form: Select Month & Year
                        Select::make('month')
                            ->label('Bulan Laporan')
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
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Download Excel
                        return Excel::download(
                            new MaintenanceExport($data['month'], $data['year']),
                            'Laporan-Maintenance-' . $data['month'] . '-' . $data['year'] . '.xlsx'
                        );
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'resolved' => 'Resolved',
                    ]),
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Lokasi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // --- CUSTOM ROW ACTION: MARK RESOLVED ---
                // Quick button to resolve issue without opening Edit form
                Tables\Actions\Action::make('mark_resolved')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn(MaintenanceReport $record) => $record->update([
                        'status' => 'resolved',
                        'fixed_at' => now(), // Set finish time to now
                    ]))
                    // Only show this button if status is NOT resolved
                    ->visible(fn(MaintenanceReport $record) => $record->status !== 'resolved'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMaintenanceReports::route('/'),
            'create' => Pages\CreateMaintenanceReport::route('/create'),
            'edit' => Pages\EditMaintenanceReport::route('/{record}/edit'),
        ];
    }
}
