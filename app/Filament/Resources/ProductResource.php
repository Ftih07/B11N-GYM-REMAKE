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
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\Textarea::make('description')->required()->maxLength(255),
                Forms\Components\FileUpload::make('image')->required()->directory('product'),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->label('Price')
                    ->placeholder('e.g., 10000, 20000'),
                Forms\Components\TextInput::make('serving_option')
                    ->label('Takaran Sajian Product')
                    ->required()
                    ->placeholder('e.g., 1kg, 1 scoop, 1 sajian'),
                Forms\Components\TextInput::make('flavour')
                    ->label('Flavour')
                    ->placeholder('e.g., Coklat, Vanilla'),
                Forms\Components\Select::make('status')
                    ->options([
                        'soldout' => 'Sold Out',
                        'ready' => 'Ready',
                    ])->required(),
                Forms\Components\Select::make('stores_id')
                    ->relationship('store', 'title')
                    ->required(),
                Forms\Components\Select::make('category_products_id')
                    ->relationship('categoryproduct', 'name')
                    ->required(),
                Forms\Components\Select::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description Product')
                    ->sortable()
                    ->limit(50)
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


                Tables\Columns\TextColumn::make('categoryproduct.name')
                    ->limit(50)
                    ->label('Category Product')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('store.title')
                    ->limit(50)
                    ->label('Store')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Gym/Kos Name')
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
