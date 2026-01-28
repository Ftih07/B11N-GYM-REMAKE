<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
// HAPUS baris SoftDeletingScope karena kamu tidak pakai soft delete
// use Illuminate\Database\Eloquent\SoftDeletingScope; 

class StoreResource extends Resource
{
    protected static ?string $navigationGroup = 'Store Management';
    protected static ?int $navigationSort = 6;

    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Store Title')
                    ->required()
                    ->maxLength(255),

                // --- TAMBAHAN BARU: SUBHEADING ---
                Forms\Components\TextInput::make('subheading')
                    ->label('Subheading / Slogan')
                    ->placeholder('Contoh: Pusat Gym Terlengkap')
                    ->maxLength(255),

                // --- PERUBAHAN: IMAGE JADI TIDAK WAJIB ---
                // Hapus '->required()' karena di database sudah nullable
                Forms\Components\FileUpload::make('image')
                    ->label('Store Image')
                    ->directory('stores') // Saran: rapikan ke folder stores
                    ->image(),

                // --- TAMBAHAN BARU: LOCATION ---
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Store Title')
                    ->sortable()
                    ->searchable(),

                // --- TAMBAHAN BARU: SUBHEADING ---
                Tables\Columns\TextColumn::make('subheading')
                    ->label('Slogan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan biar tabel ga penuh

                Tables\Columns\ImageColumn::make('image')
                    ->label('Store Image'),

                // --- TAMBAHAN BARU: LOCATION ---
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
                // Tidak perlu filter Trash karena tidak ada soft delete
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Delete biasa (langsung hilang)
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
