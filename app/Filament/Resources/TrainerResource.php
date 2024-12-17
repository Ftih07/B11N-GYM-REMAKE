<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainerResource\Pages;
use App\Filament\Resources\TrainerResource\RelationManagers;
use App\Models\Trainer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainerResource extends Resource
{
    protected static ?string $model = Trainer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Forms\Components\
                Forms\Components\Section::make('Trainer Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),

                        Forms\Components\TextInput::make('description')
                            ->required()
                            ->maxLength(65535),

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('urls.whatsapp')
                                ->label('WhatsApp URL')
                                ->url()
                                ->required(),
                            Forms\Components\TextInput::make('urls.instagram')
                                ->label('Instagram URL')
                                ->url()
                                ->required(),
                            Forms\Components\TextInput::make('urls.facebook')
                                ->label('Facebook URL')
                                ->url()
                                ->required(),
                        ])->columns(3),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->required()
                            ->directory('trainer'),

                        Forms\Components\Select::make('gymkos_id')
                            ->label('Gymkos')
                            ->relationship('gymkos', 'name')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\TextColumn::make('urls.whatsapp')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('urls.instagram')->label('Instagram'),
                Tables\Columns\TextColumn::make('urls.facebook')->label('Facebook'),
                Tables\Columns\ImageColumn::make('image')->label('Image'),
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
            'index' => Pages\ListTrainers::route('/'),
            'create' => Pages\CreateTrainer::route('/create'),
            'edit' => Pages\EditTrainer::route('/{record}/edit'),
        ];
    }
}
