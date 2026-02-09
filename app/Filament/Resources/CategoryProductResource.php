<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryProductResource\Pages;
use App\Models\CategoryProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryProductResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'Store Management'; // Grouping in sidebar
    protected static ?int $navigationSort = 6; // Order position (6th)

    protected static ?string $model = CategoryProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    // --- FORM CONFIGURATION (Create/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Simple text input for Category Name
                Forms\Components\TextInput::make('name')
                    ->label('Name Category')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Category Name Column
                Tables\Columns\TextColumn::make('name')
                    ->label('Title Category Training') // Custom label for table header
                    ->sortable()
                    ->searchable(), // Enable search bar for this column

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                // No filters needed for now
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
