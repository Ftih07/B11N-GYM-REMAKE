<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainerResource\Pages;
use App\Models\Trainer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TrainerResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    public static function getNavigationBadge(): ?string
    {
        return Trainer::count();
    }

    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = Trainer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group'; // Icon: User Group

    // --- FORM CONFIGURATION ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Trainer Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(65535),

                        // Social Media Links Group
                        // Uses dot notation (e.g., urls.whatsapp) for JSON column mapping
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

    // --- TABLE CONFIGURATION ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(50),

                // Display Social URLs
                Tables\Columns\TextColumn::make('urls.whatsapp')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('urls.instagram')->label('Instagram'),
                Tables\Columns\TextColumn::make('urls.facebook')->label('Facebook'),

                Tables\Columns\ImageColumn::make('image')->label('Image'),

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Gym/Kos')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
