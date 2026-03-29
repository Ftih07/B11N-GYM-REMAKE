<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GymkosResource\Pages;
use App\Models\Gymkos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GymkosResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Manajemen Website';
    protected static ?string $navigationLabel = 'Cabang Gym/Kos';
    protected static ?string $pluralModelLabel = 'Data Cabang';
    protected static ?int $navigationSort = 5;

    protected static ?string $model = Gymkos::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase'; // Ikon: Tas Kerja

    // --- KONFIGURASI FORM (Tambah/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Cabang')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(), // Ambil lebar penuh dari form
            ]);
    }

    // --- KONFIGURASI TABEL (Tampilan List) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Cabang')
                    ->sortable()
                    ->searchable(),

                // Kolom Alamat: Dibungkus dan dibatasi agar UI lebih rapi
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(40) // Tampilkan 40 karakter pertama saja
                    ->wrap() // Bungkus teks jika terlalu panjang
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->date('d M Y') // Format: Tanggal Bulan Tahun
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListGymkos::route('/'),
            'create' => Pages\CreateGymkos::route('/create'),
            'edit' => Pages\EditGymkos::route('/{record}/edit'),
        ];
    }
}
