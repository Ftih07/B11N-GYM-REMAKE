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
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'General Management Website';
    protected static ?int $navigationSort = 5;

    protected static ?string $model = Gymkos::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase'; // Icon: Briefcase

    // --- FORM CONFIGURATION (Create/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Branch Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label('Address')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(), // Take full width of the form
            ]);
    }

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Branch Name')
                    ->sortable()
                    ->searchable(),

                // Address Column: Wrapped and limited for better UI
                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                    ->limit(40) // Show first 40 chars only
                    ->wrap() // Wrap text if too long
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->date('F d, Y') // Format: Month Day, Year
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListGymkos::route('/'),
            'create' => Pages\CreateGymkos::route('/create'),
            'edit' => Pages\EditGymkos::route('/{record}/edit'),
        ];
    }
}
