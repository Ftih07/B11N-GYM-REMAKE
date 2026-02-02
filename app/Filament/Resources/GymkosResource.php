<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GymkosResource\Pages;
use App\Filament\Resources\GymkosResource\RelationManagers;
use App\Models\Gymkos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GymkosResource extends Resource
{
    protected static ?string $navigationGroup = 'General Management Website';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Gymkos::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->label('Address')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                    ->limit(40)
                    ->wrap()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->date('F d, Y')
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
