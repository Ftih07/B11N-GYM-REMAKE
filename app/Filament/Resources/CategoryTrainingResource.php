<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryTrainingResource\Pages;
use App\Models\CategoryTraining;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryTrainingResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Program Latihan'; // Grup Sidebar
    protected static ?string $navigationLabel = 'Kategori Program';
    protected static ?string $pluralModelLabel = 'Data Kategori Latihan';
    protected static ?string $model = CategoryTraining::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap'; // Ikon untuk 'Training'
    protected static ?int $navigationSort = 7; // Urutan posisi

    // --- KONFIGURASI FORM (Tambah/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input untuk nama Kategori Latihan
                Forms\Components\TextInput::make('title')
                    ->label('Nama Kategori Latihan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan ID Record
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                // Menampilkan Judul Kategori
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Kategori')
                    ->sortable()
                    ->searchable(), // Aktifkan pencarian

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCategoryTrainings::route('/'),
            'create' => Pages\CreateCategoryTraining::route('/create'),
            'edit' => Pages\EditCategoryTraining::route('/{record}/edit'),
        ];
    }
}
