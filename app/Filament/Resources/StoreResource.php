<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StoreResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'Store Management';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = Store::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront'; // Icon: Storefront

    // --- FORM CONFIGURATION ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Store Title')
                    ->required()
                    ->maxLength(255),

                // Subheading (Optional)
                Forms\Components\TextInput::make('subheading')
                    ->label('Subheading / Slogan')
                    ->placeholder('Contoh: Pusat Gym Terlengkap')
                    ->maxLength(255),

                // Image Upload (Optional / Nullable)
                Forms\Components\FileUpload::make('image')
                    ->label('Store Image')
                    ->directory('stores') // Save to 'stores' folder
                    ->image(),

                // Address / Location
                Forms\Components\Textarea::make('location')
                    ->label('Location / Alamat')
                    ->rows(2)
                    ->maxLength(500),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->nullable()
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }

    // --- TABLE CONFIGURATION ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Store Title')
                    ->sortable()
                    ->searchable(),

                // Toggleable Slogan Column (Hidden by default to save space)
                Tables\Columns\TextColumn::make('subheading')
                    ->label('Slogan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Store Image'),

                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // No filters needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Hard Delete
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
