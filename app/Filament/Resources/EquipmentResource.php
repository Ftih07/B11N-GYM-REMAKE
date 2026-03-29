<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EquipmentResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---

    // Menampilkan jumlah total alat di badge sidebar
    public static function getNavigationBadge(): ?string
    {
        return Equipment::count() ?: null;
    }

    protected static ?string $model = Equipment::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench'; // Ikon: Kunci Pas
    protected static ?string $navigationGroup = 'Manajemen Gym';
    protected static ?string $navigationLabel = 'Inventaris Alat';
    protected static ?string $pluralModelLabel = 'Data Alat Gym';
    protected static ?int $navigationSort = 3;

    // --- KONFIGURASI FORM ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // BAGIAN 1: Informasi Utama
                Forms\Components\Section::make('Detail Alat')
                    ->description('Informasi utama alat dan tutorial penggunaannya.')
                    ->schema([
                        // Relasi ke Lokasi Gym
                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->label('Lokasi Gym / Kos')
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Alat')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('category')
                            ->label('Kategori Alat')
                            ->options([
                                'Cardio' => 'Kardio',
                                'Strength' => 'Beban / Strength',
                                'Furniture' => 'Furnitur',
                                'Electronics' => 'Elektronik',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Kondisi')
                            ->options([
                                'active' => 'Aktif',
                                'maintenance' => 'Dalam Perbaikan',
                                'broken' => 'Rusak',
                            ])
                            ->default('active')
                            ->required(),

                        // Input Video Tutorial
                        Forms\Components\TextInput::make('video_url')
                            ->label('Link Video Panduan')
                            ->placeholder('Contoh: https://youtube.com/embed/...')
                            ->url() // Validasi input sebagai URL
                            ->suffixIcon('heroicon-m-video-camera')
                            ->columnSpanFull(), // Lebar penuh

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Fisik')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2), // Bagi bagian ini jadi 2 kolom

                // BAGIAN 2: Galeri Foto (Menggunakan Repeater)
                Forms\Components\Section::make('Galeri Foto')
                    ->description('Upload foto-foto kondisi fisik alat.')
                    ->schema([
                        // Repeater memungkinkan penambahan banyak foto secara dinamis
                        Forms\Components\Repeater::make('gallery')
                            ->relationship() // Terhubung ke tabel 'gallery_equipments' via relasi
                            ->label('Daftar Foto')
                            ->addActionLabel('Tambah Foto') // Tombol bahasa Indonesia
                            ->schema([
                                Forms\Components\FileUpload::make('file_path')
                                    ->label('Upload Foto')
                                    ->image()
                                    ->directory('equipment-gallery') // Simpan ke storage/app/public/equipment-gallery
                                    ->required(),

                                Forms\Components\TextInput::make('caption')
                                    ->label('Keterangan Foto')
                                    ->placeholder('Contoh: Tampak Depan'),

                                Forms\Components\TextInput::make('order_index')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->grid(2) // Tampilkan item yang diupload dalam bentuk grid
                            ->defaultItems(0)
                            ->collapsible(), // Izinkan menutup/minimize item untuk menghemat ruang
                    ]),
            ]);
    }

    // --- KONFIGURASI TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Alat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'Cardio' => 'Kardio',
                        'Strength' => 'Beban',
                        'Furniture' => 'Furnitur',
                        'Electronics' => 'Elektronik',
                        default => $state,
                    })
                    ->sortable(),

                // Badge Status Kondisi
                Tables\Columns\TextColumn::make('status')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'maintenance' => 'warning',
                        'broken' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'maintenance' => 'Perbaikan',
                        'broken' => 'Rusak',
                        default => $state,
                    }),

                // Menampilkan jumlah Laporan Perbaikan terkait
                Tables\Columns\TextColumn::make('maintenance_reports_count')
                    ->counts('maintenanceReports') // Laravel relationship count
                    ->label('Laporan Kerusakan')
                    ->badge()
                    ->color('info'),
            ])
            // --- FILTER TABEL ---
            ->filters([
                // 1. Filter Lokasi Gym
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Lokasi Gym')
                    ->searchable()
                    ->preload(),

                // 2. Filter Kategori Alat
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Cardio' => 'Kardio',
                        'Strength' => 'Beban / Strength',
                        'Furniture' => 'Furnitur',
                        'Electronics' => 'Elektronik',
                    ]),

                // 3. Filter Status Kondisi
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Alat')
                    ->options([
                        'active' => 'Aktif',
                        'maintenance' => 'Dalam Perbaikan',
                        'broken' => 'Rusak',
                    ]),
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
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
