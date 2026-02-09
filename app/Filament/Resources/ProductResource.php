<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    // --- NAVIGATION SETTINGS ---

    // Badge: Shows total number of products
    public static function getNavigationBadge(): ?string
    {
        return Product::count();
    }

    protected static ?string $navigationGroup = 'Store Management';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag'; // Icon: Shopping Bag

    // --- FORM CONFIGURATION (Create/Edit Product) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\Textarea::make('description')->required()->maxLength(255),

                // Image Upload
                Forms\Components\FileUpload::make('image')->directory('product'),

                // Pricing Input
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->label('Price')
                    ->placeholder('e.g., 10000, 20000'),

                // Product Details (Serving Size & Flavour)
                Forms\Components\TextInput::make('serving_option')
                    ->label('Takaran Sajian Product')
                    ->placeholder('e.g., 1kg, 1 scoop, 1 sajian'),

                Forms\Components\TextInput::make('flavour')
                    ->label('Flavour')
                    ->placeholder('e.g., Coklat, Vanilla'),

                // Status Selection
                Forms\Components\Select::make('status')
                    ->options([
                        'soldout' => 'Sold Out',
                        'ready' => 'Ready',
                    ])->required(),

                // RELATIONSHIP: Link to Store
                Forms\Components\Select::make('stores_id')
                    ->relationship('store', 'title')
                    ->required(),

                // RELATIONSHIP: Link to Product Category
                Forms\Components\Select::make('category_products_id')
                    ->relationship('categoryproduct', 'name')
                    ->required(),
            ]);
    }

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description Product')
                    ->sortable()
                    ->limit(50) // Truncate text
                    ->searchable(),

                Tables\Columns\TextColumn::make('serving_option')
                    ->label('Takaran Sajian Product')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('flavour')
                    ->label('Rasa Product')
                    ->sortable()
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Product Price')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Product Image')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')->sortable(),

                // Display Related Category Name (Dot Notation)
                Tables\Columns\TextColumn::make('categoryproduct.name')
                    ->limit(50)
                    ->label('Category Product')
                    ->sortable()
                    ->searchable(),

                // Display Related Store Title (Dot Notation)
                Tables\Columns\TextColumn::make('store.title')
                    ->limit(50)
                    ->label('Store')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
