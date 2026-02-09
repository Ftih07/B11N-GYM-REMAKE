<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilitiesResource\Pages;
use App\Models\Facilities;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FacilitiesResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'General Management Website';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Facilities::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern'; // Icon for buildings/facilities

    // --- FORM CONFIGURATION (Create/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Facilities Title')
                    ->required()
                    ->maxLength(255),

                // Upload facility image to 'storage/app/public/facilities'
                Forms\Components\FileUpload::make('image')
                    ->label('Facilities Image')
                    ->directory('facilities')
                    ->image()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->nullable() // Optional field
                    ->maxLength(1000),

                // RELATIONSHIP: Link this facility to a specific Gym/Kos
                Forms\Components\Select::make('gymkos_id')
                    ->label('Gymkos')
                    ->relationship('gymkos', 'name') // Select gymkos, display its 'name'
                    ->required(),
            ]);
    }

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Facilities Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Facilities Image'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50), // Show only first 50 chars

                // Display Related Gym Name using Dot Notation
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Gym/Kos')
                    ->sortable()
                    ->searchable(), // Enable searching by Gym Name

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
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacilities::route('/create'),
            'edit' => Pages\EditFacilities::route('/{record}/edit'),
        ];
    }
}
