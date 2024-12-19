<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryProductResource\Pages;
use App\Filament\Resources\CategoryProductResource\RelationManagers;
use App\Models\CategoryProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryProductResource extends Resource
{
    protected static ?string $model = CategoryProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                    ->label('Name Category')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('gymkos_id')
                    ->label('Gymkos Name')
                    ->relationship('gymkos', 'name')
                    ->required(),
                Forms\Components\Select::make('stores_id')
                    ->label('Store Name')
                    ->relationship('store', 'title')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                    ->label('Title Category Training')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('store.title')
                    ->limit(50)
                    ->label('Nama Store')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Gym/Kos')
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
            'index' => Pages\ListCategoryProducts::route('/'),
            'create' => Pages\CreateCategoryProduct::route('/create'),
            'edit' => Pages\EditCategoryProduct::route('/{record}/edit'),
        ];
    }
}
