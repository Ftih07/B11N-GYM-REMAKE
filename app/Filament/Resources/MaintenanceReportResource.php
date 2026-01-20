<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceReportResource\Pages;
use App\Models\MaintenanceReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MaintenanceReportResource extends Resource
{
    protected static ?string $model = MaintenanceReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Gym Management';
    protected static ?string $navigationLabel = 'Laporan Kerusakan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Laporan')
                    ->description('Data yang dikirim oleh pelapor (Read Only)')
                    ->schema([
                        Forms\Components\TextInput::make('reporter_name')
                            ->label('Nama Pelapor')
                            ->readOnly(),

                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->label('Lokasi')
                            ->disabled() // Ganti readOnly() jadi ini
                            ->dehydrated(), // PENTING: Agar datanya tetap terkirim saat save (kadang disabled bikin data ga masuk)

                        Forms\Components\Select::make('equipment_id')
                            ->relationship('equipment', 'name')
                            ->label('Alat')
                            ->disabled() // Ganti readOnly() jadi ini
                            ->dehydrated(),

                        Forms\Components\TextInput::make('severity')
                            ->label('Tingkat Keparahan')
                            ->readOnly(),

                        Forms\Components\Textarea::make('description')
                            ->label('Keluhan / Masalah')
                            ->columnSpanFull()
                            ->readOnly(),

                        Forms\Components\FileUpload::make('evidence_photo')
                            ->label('Bukti Foto')
                            ->image()
                            ->directory('maintenance-evidence')
                            ->downloadable() // Agar admin bisa download fotonya
                            ->openable() // Agar bisa di klik zoom
                            ->columnSpanFull()
                            ->disabled() // Ganti readOnly() jadi ini
                            ->dehydrated(),
                    ])->columns(2),

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

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Lapor')
                    ->dateTime('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gymkos.name') // Relasi ke gym
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('equipment.name')
                    ->label('Alat')
                    ->searchable()
                    ->description(fn(MaintenanceReport $record): string => \Illuminate\Support\Str::limit($record->description, 30)),

                Tables\Columns\ImageColumn::make('evidence_photo')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\BadgeColumn::make('severity')
                    ->label('Keparahan')
                    ->colors([
                        'success' => 'low',
                        'warning' => 'medium',
                        'danger' => ['high', 'critical'],
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'pending',
                        'warning' => 'in_progress',
                        'success' => 'resolved',
                        'danger' => 'wont_fix',
                    ]),
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

                // Action Cepat untuk menandai "Selesai" tanpa masuk form edit
                Tables\Actions\Action::make('mark_resolved')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn(MaintenanceReport $record) => $record->update([
                        'status' => 'resolved',
                        'fixed_at' => now(),
                    ]))
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
