<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutResource\Pages;
use App\Models\About;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AboutResource extends Resource
{
    // --- NAVIGATION CONFIG ---
    protected static ?string $navigationGroup = 'General Management Website'; // Grouping in sidebar
    protected static ?int $navigationSort = 5; // Order in the menu
    protected static ?string $model = About::class; // Connected Model
    protected static ?string $navigationIcon = 'heroicon-o-information-circle'; // Sidebar Icon

    // --- FORM SCHEMA (Create/Edit Page) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input: Title
                Forms\Components\TextInput::make('title')
                    ->label('About Title')
                    ->required()
                    ->maxLength(255),

                // Input: Image Upload
                Forms\Components\FileUpload::make('image')
                    ->label('About Image')
                    ->directory('about') // Saves to storage/app/public/about
                    ->image()
                    ->required(),

                // Input: Description (Long Text)
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->nullable()
                    ->maxLength(1000),

                // Input: Relationship Dropdown (Select Gym/Kos)
                Forms\Components\Select::make('gymkos_id')
                    ->label('Gymkos')
                    ->relationship('gymkos', 'name') // Connects to Gymkos model, shows 'name'
                    ->required(),
            ]);
    }

    // --- TABLE SCHEMA (List Page) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Column: Title
                Tables\Columns\TextColumn::make('title')
                    ->label('About Title')
                    ->sortable()
                    ->searchable(),

                // Column: Image Preview
                Tables\Columns\ImageColumn::make('image')
                    ->label('About Image'),

                // Column: Description (Shortened)
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50), // Only show first 50 chars

                // Column: Related Gym Name (Dot notation accesses relationship)
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Gym/Kos')
                    ->sortable()
                    ->searchable(),

                // Column: Created Date
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                // No filters defined yet
            ])
            ->actions([
                // Action Buttons on each row
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Actions for selecting multiple rows
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // --- PAGES ---
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbouts::route('/'),
            'create' => Pages\CreateAbout::route('/create'),
            'edit' => Pages\EditAbout::route('/{record}/edit'),
        ];
    }
}
