<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Manajemen Website'; // Grup Sidebar
    protected static ?string $navigationLabel = 'Galeri Foto';
    protected static ?string $pluralModelLabel = 'Data Galeri';
    protected static ?int $navigationSort = 5; // Urutan Menu

    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo'; // Ikon: Foto/Gambar

    // --- KONFIGURASI FORM (Tambah/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input Judul
                Forms\Components\TextInput::make('title')
                    ->label('Judul Foto')
                    ->required()
                    ->maxLength(255),

                // Konfigurasi Upload Gambar
                Forms\Components\FileUpload::make('image')
                    ->label('Gambar (Rasio 1:1)')
                    ->directory('gallery')
                    ->image()
                    ->required()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                    ])
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('1080')
                    ->imageResizeTargetHeight('1080'),

                // RELASI: Hubungkan gambar ini ke Cabang Gym/Kos tertentu
                Forms\Components\Select::make('gymkos_id')
                    ->label('Cabang (Gym/Kos)')
                    ->relationship('gymkos', 'name') // Pilih gymkos, tampilkan 'name'-nya
                    ->required(),
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Foto')
                    ->sortable()
                    ->searchable(),

                // Tampilkan Nama Cabang Terkait
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Cabang')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y'),
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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
