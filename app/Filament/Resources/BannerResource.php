<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    protected static ?string $navigationGroup = 'Store Management';
    protected static ?int $navigationSort = 6;

    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('title')
                    ->label('Title Banner')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subheading')
                    ->label('Subheading Banner')
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->label('Description Banner')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->label('Location Store Banner')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->label('Image Banner')
                    ->required()
                    ->directory('banner'),
                Forms\Components\Select::make('gymkos_id')
                    ->label('Gymkos')
                    ->relationship('gymkos', 'name')
                    ->required(),
                Forms\Components\Select::make('stores_id')
                    ->label('Store')
                    ->relationship('store', 'title')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Banner Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subheading')
                    ->label('Subheading Banner')
                    ->sortable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description Banner')
                    ->sortable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('location')
                    ->label('Location Store Banner')
                    ->sortable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\ImageColumn::make('image'),
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
                Tables\Actions\ViewAction::make(), // Adds view action
                Tables\Actions\DeleteAction::make(), // Adds delete action
                Tables\Actions\EditAction::make(), // Adds edit action
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
