<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    // --- PENGATURAN NAVIGASI ---
    protected static ?string $navigationGroup = 'Manajemen Toko';
    protected static ?string $navigationLabel = 'Katalog Produk';
    protected static ?string $pluralModelLabel = 'Data Produk';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag'; // Ikon: Tas Belanja

    public static function getNavigationBadge(): ?string
    {
        return Product::count() ?: null;
    }

    // --- KONFIGURASI FORM ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // KIRI: Informasi Utama Produk
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('Informasi Produk')
                            ->description('Detail nama, deskripsi, dan rasa.')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(), // Memanjang penuh

                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->rows(4)
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('flavour')
                                    ->label('Varian Rasa')
                                    ->placeholder('Contoh: Coklat, Vanilla')
                                    ->columnSpan(1), // Setengah kolom

                                Forms\Components\TextInput::make('serving_option')
                                    ->label('Takaran Saji')
                                    ->placeholder('Contoh: 1 Scoop (30g)')
                                    ->columnSpan(1),
                            ])->columns(2), // Dalam section ini dibagi 2 kolom

                        Section::make('Gambar Produk')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Upload Gambar')
                                    ->image() // Validasi harus gambar
                                    ->imageEditor() // Fitur crop/edit bawaan Filament
                                    ->directory('product')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2), // Grup Kiri memakan 2/3 lebar layar

                // KANAN: Harga, Status & Relasi
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('Harga & Status')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga (IDR)')
                                    ->numeric()
                                    ->prefix('Rp') // Ada tulisan Rp di depan input
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->label('Status Ketersediaan')
                                    ->options([
                                        'ready' => 'Stok Tersedia',
                                        'soldout' => 'Habis Terjual',
                                    ])
                                    ->required()
                                    ->native(false), // Tampilan dropdown lebih modern
                            ]),

                        Section::make('Kategori & Lokasi')
                            ->schema([
                                Forms\Components\Select::make('category_products_id')
                                    ->label('Kategori')
                                    ->relationship('categoryproduct', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        // Opsional: Bisa bikin kategori baru langsung dari sini
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Kategori')
                                            ->required(),
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('stores_id')
                                    ->label('Toko / Lokasi')
                                    ->relationship('store', 'title')
                                    ->required(),
                            ]),
                    ])->columnSpan(1), // Grup Kanan memakan 1/3 lebar layar
            ])
            ->columns(3); // Layout utama dibagi 3 kolom (2 kiri, 1 kanan)
    }

    // --- KONFIGURASI TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Gambar kecil di awal
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular(), // Biar bulat (opsional, hapus jika mau kotak)

                // Nama Produk & Varian digabung biar hemat tempat
                Tables\Columns\TextColumn::make('name')
                    ->label('Produk')
                    ->description(fn(Product $record): string => $record->flavour ?? '-') // Subtitle di bawah nama
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Kategori
                Tables\Columns\TextColumn::make('categoryproduct.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge() // Tampil seperti tag
                    ->color('gray'),

                // Harga dengan format Rupiah
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id') // Otomatis format Rp 100.000
                    ->sortable(),

                // Status dengan Warna
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'ready' => 'success', // Hijau
                        'soldout' => 'danger', // Merah
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'ready' => 'Tersedia',
                        'soldout' => 'Habis',
                        default => $state,
                    }),

                // Toko
                Tables\Columns\TextColumn::make('store.title')
                    ->label('Toko')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Default sembunyi, user bisa centang kalau mau lihat

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc') // Produk terbaru di atas
            ->filters([
                // Filter berdasarkan Status
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'ready' => 'Stok Tersedia',
                        'soldout' => 'Habis Terjual',
                    ]),
                // Filter berdasarkan Kategori
                Tables\Filters\SelectFilter::make('category_products_id')
                    ->label('Kategori')
                    ->relationship('categoryproduct', 'name'),
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

    // --- PENGATURAN PENCARIAN GLOBAL ---
    // 1. Kolom utama pencarian
    protected static ?string $recordTitleAttribute = 'name';

    // 2. Kolom yang bisa dicari (Nama, Varian Rasa, dan Deskripsi)
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'flavour', 'description'];
    }

    // 3. Eager load relasi kategori dan toko biar query efisien
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['categoryproduct', 'store']);
    }

    // 4. Judul Utama di Hasil Pencarian
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        $flavour = $record->flavour ? " ({$record->flavour})" : "";
        return "{$record->name}{$flavour}";
    }

    // 5. Detail Informatif di Bawah Judul
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Kategori' => $record->categoryproduct ? $record->categoryproduct->name : '-',
            'Harga' => 'Rp ' . number_format($record->price, 0, ',', '.'),
            'Status' => $record->status === 'ready' ? '✅ Tersedia' : '❌ Habis',
            'Toko/Lokasi' => $record->store ? $record->store->title : '-',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
