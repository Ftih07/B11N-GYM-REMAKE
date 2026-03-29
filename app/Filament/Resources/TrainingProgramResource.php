<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingProgramResource\Pages;
use App\Models\TrainingProgram;
use App\Models\Gymkos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\ReplicateAction; // Import Replicate Action

class TrainingProgramResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Program Latihan';
    protected static ?string $navigationLabel = 'Daftar Program';
    protected static ?string $pluralModelLabel = 'Data Program Latihan';
    protected static ?string $model = TrainingProgram::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire'; // Ikon: Api/Energi
    protected static ?int $navigationSort = 7;

    // --- KONFIGURASI FORM ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Program Latihan')
                    ->required(),

                // Editor WYSIWYG untuk Deskripsi
                Forms\Components\RichEditor::make('description')
                    ->label('Deskripsi Program')
                    ->required()
                    ->maxLength(2000)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->label('Gambar / Banner Program')
                    ->required()
                    ->directory('training_program')
                    ->columnSpanFull(),

                // Relasi
                Forms\Components\Select::make('gymkos_id')
                    ->label('Cabang (Gym/Kos)')
                    ->relationship('gymkos', 'name')
                    ->required(),

                Forms\Components\Select::make('category_trainings_id')
                    ->label('Kategori Program')
                    ->relationship('categorytraining', 'title')
                    ->required(),
            ]);
    }

    // --- KONFIGURASI TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Program')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('categorytraining.title')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Cabang')
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // 1. Filter Lokasi Gym/Kos
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Filter Cabang')
                    ->searchable()
                    ->preload(),

                // 2. Filter Kategori Program Latihan
                Tables\Filters\SelectFilter::make('category_trainings_id')
                    ->relationship('categorytraining', 'title')
                    ->label('Filter Kategori')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),

                // === AKSI DUPLIKASI KUSTOM ===
                // Mengizinkan user mengkloning program ke Gym yang BERBEDA
                ReplicateAction::make()
                    ->label('Duplikasi ke Cabang Lain')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning')
                    ->modalHeading('Duplikasi Program Latihan')
                    ->modalDescription('Pilih Cabang tujuan. Semua data (Judul, Deskripsi, Gambar) akan disalin persis.')
                    ->form([
                        // Form Modal: Pilih Gym Tujuan
                        Forms\Components\Select::make('gymkos_id')
                            ->label('Pilih Cabang Tujuan')
                            ->options(Gymkos::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    // Logika: Timpa 'gymkos_id' dengan yang dipilih dari modal
                    ->beforeReplicaSaved(function (TrainingProgram $replica, array $data) {
                        $replica->gymkos_id = $data['gymkos_id'];
                    })
                    ->successNotificationTitle('Program berhasil diduplikasi ke Cabang lain!'),

                Tables\Actions\DeleteAction::make()->label('Hapus'), // Tambahan tombol hapus
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
            'index' => Pages\ListTrainingPrograms::route('/'),
            'create' => Pages\CreateTrainingProgram::route('/create'),
            'edit' => Pages\EditTrainingProgram::route('/{record}/edit'),
        ];
    }
}
