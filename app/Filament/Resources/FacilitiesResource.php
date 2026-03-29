<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilitiesResource\Pages;
use App\Models\Facilities;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FacilitiesResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Manajemen Website';
    protected static ?string $navigationLabel = 'Fasilitas';
    protected static ?string $pluralModelLabel = 'Data Fasilitas';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Facilities::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern'; // Ikon untuk gedung/fasilitas

    // --- KONFIGURASI FORM (Tambah/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Nama Fasilitas')
                    ->required()
                    ->maxLength(255),

                // RELASI: Hubungkan fasilitas ini ke Gym/Kos tertentu
                Forms\Components\Select::make('gymkos_id')
                    ->label('Cabang (Gym/Kos)')
                    ->relationship('gymkos', 'name') // Pilih gymkos, tampilkan 'name'-nya
                    ->required(),

                // Upload gambar fasilitas ke 'storage/app/public/facilities'
                Forms\Components\FileUpload::make('image')
                    ->label('Foto Fasilitas')
                    ->directory('facilities')
                    ->image()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Fasilitas')
                    ->nullable() // Field opsional
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Fasilitas')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(50), // Tampilkan hanya 50 karakter pertama

                // Tampilkan Nama Cabang Terkait menggunakan Dot Notation
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Cabang Gym / Kos')
                    ->sortable()
                    ->searchable(), // Aktifkan pencarian berdasarkan Nama Gym

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Filter Cabang')
                    ->searchable()
                    ->preload(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacilities::route('/create'),
            'edit' => Pages\EditFacilities::route('/{record}/edit'),
        ];
    }
}
