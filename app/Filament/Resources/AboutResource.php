<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutResource\Pages;
use App\Models\About;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AboutResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Manajemen Website'; // Pengelompokan di sidebar
    protected static ?string $navigationLabel = 'Tentang Kami';
    protected static ?string $pluralModelLabel = 'Data Tentang Kami';
    protected static ?int $navigationSort = 5; // Urutan di menu
    protected static ?string $model = About::class; // Model Terhubung
    protected static ?string $navigationIcon = 'heroicon-o-information-circle'; // Ikon Sidebar

    // --- KONFIGURASI FORM (Halaman Tambah/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('image')
                    ->label('Gambar Utama')
                    ->directory('about') // Disimpan ke storage/app/public/about
                    ->image()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->nullable()
                    ->maxLength(1000)
                    ->columnSpanFull(), // Biar lebih lega saat ngetik

                Forms\Components\Select::make('gymkos_id')
                    ->label('Cabang (Gym/Kos)')
                    ->relationship('gymkos', 'name') // Terhubung ke model Gymkos, tampilkan 'name'
                    ->required(),
            ]);
    }

    // --- KONFIGURASI TABEL (Halaman List) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(50), // Hanya tampilkan 50 karakter pertama

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Cabang')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbouts::route('/'),
            'create' => Pages\CreateAbout::route('/create'),
            'edit' => Pages\EditAbout::route('/{record}/edit'),
        ];
    }
}
