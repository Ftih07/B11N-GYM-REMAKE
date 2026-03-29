<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StoreResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Manajemen Toko';
    protected static ?string $navigationLabel = 'Daftar Toko';
    protected static ?string $pluralModelLabel = 'Data Toko';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = Store::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront'; // Ikon: Etalase Toko

    // --- KONFIGURASI FORM ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Nama Toko')
                    ->required()
                    ->maxLength(255),

                // Subheading (Opsional)
                Forms\Components\TextInput::make('subheading')
                    ->label('Slogan / Subjudul')
                    ->placeholder('Contoh: Pusat Suplemen Terlengkap')
                    ->maxLength(255),

                // Upload Gambar (Opsional)
                Forms\Components\FileUpload::make('image')
                    ->label('Foto Banner')
                    ->directory('stores') // Simpan ke folder 'stores'
                    ->image(),

                // Lokasi / Alamat
                Forms\Components\Textarea::make('location')
                    ->label('Lokasi / Alamat Lengkap')
                    ->rows(2)
                    ->maxLength(500),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Toko')
                    ->nullable()
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }

    // --- KONFIGURASI TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Toko')
                    ->sortable()
                    ->searchable(),

                // Kolom Slogan (Sembunyi secara default untuk menghemat ruang)
                Tables\Columns\TextColumn::make('subheading')
                    ->label('Slogan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto Banner'),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi / Alamat')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tidak ada filter yang dibutuhkan
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'), // Hapus Data
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
