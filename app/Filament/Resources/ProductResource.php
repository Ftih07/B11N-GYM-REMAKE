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

class ProductResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'Store Management';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function getNavigationBadge(): ?string
    {
        return Product::count();
    }

    // --- FORM CONFIGURATION ---
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
                                    ->options([
                                        'ready' => 'Ready Stock',
                                        'soldout' => 'Sold Out',
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
                                        Forms\Components\TextInput::make('name')->required(),
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

    // --- TABLE CONFIGURATION ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Gambar kecil di awal
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular(), // Biar bulat (opsional, hapus jika mau kotak)

                // Nama Produk & Varian digabung biar hemat tempat
                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->description(fn(Product $record): string => $record->flavour ?? '-') // Subtitle di bawah nama
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Kategori
                Tables\Columns\TextColumn::make('categoryproduct.name')
                    ->label('Category')
                    ->sortable()
                    ->badge() // Tampil seperti tag
                    ->color('gray'),

                // Harga dengan format Rupiah
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR', locale: 'id') // Otomatis format Rp 100.000
                    ->sortable(),

                // Status dengan Warna
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'ready' => 'success', // Hijau
                        'soldout' => 'danger', // Merah
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'ready' => 'Ready',
                        'soldout' => 'Sold Out',
                        default => $state,
                    }),

                // Toko
                Tables\Columns\TextColumn::make('store.title')
                    ->label('Store')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Default sembunyi, user bisa centang kalau mau lihat

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc') // Produk terbaru di atas
            ->filters([
                // Filter berdasarkan Status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ready' => 'Ready',
                        'soldout' => 'Sold Out',
                    ]),
                // Filter berdasarkan Kategori
                Tables\Filters\SelectFilter::make('category_products_id')
                    ->label('Category')
                    ->relationship('categoryproduct', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
