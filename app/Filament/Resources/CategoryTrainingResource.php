<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryTrainingResource\Pages;
use App\Models\CategoryTraining;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryTrainingResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'Training Program'; // Sidebar Group
    protected static ?string $model = CategoryTraining::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap'; // Icon for 'Training'
    protected static ?int $navigationSort = 7; // Order position

    // --- FORM CONFIGURATION (Create/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input field for the Training Category name
                Forms\Components\TextInput::make('title')
                    ->label('Title Category Training')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display Record ID
                Tables\Columns\TextColumn::make('id'),

                // Display Category Title
                Tables\Columns\TextColumn::make('title')
                    ->label('Title Category Training')
                    ->sortable()
                    ->searchable(), // Enable search

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
            'index' => Pages\ListCategoryTrainings::route('/'),
            'create' => Pages\CreateCategoryTraining::route('/create'),
            'edit' => Pages\EditCategoryTraining::route('/{record}/edit'),
        ];
    }
}
