<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'General Management Website'; // Sidebar Group
    protected static ?int $navigationSort = 5; // Order Position

    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo'; // Icon: Photo/Image

    // --- FORM CONFIGURATION (Create/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Title Input
                Forms\Components\TextInput::make('title')
                    ->label('Title Image')
                    ->required()
                    ->maxLength(255),

                // Image Upload Configuration
                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->directory('gallery') // Save to 'storage/app/public/gallery'
                    ->image() // Validate file is an image
                    ->required(),

                // RELATIONSHIP: Link this image to a specific Gym/Kost
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
                    ->label('Title Image')
                    ->sortable()
                    ->searchable(),

                // Thumbnail Preview
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),

                // Display Related Gym Name (Dot Notation)
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Gym/Kos')
                    ->sortable()
                    ->searchable(), // Allow searching by Gym Name

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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
