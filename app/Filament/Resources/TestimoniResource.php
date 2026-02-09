<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimoniResource\Pages;
use App\Models\Testimoni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimoniResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'General Management Website';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Testimoni::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left'; // Icon: Chat Bubble

    // --- FORM CONFIGURATION ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Testimonial')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(65535),

                        // Image Upload
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->required()
                            ->directory('testimoni'), // Save to 'testimoni' folder

                        // Rating Selection (1-5 Stars)
                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->required()
                            ->options([
                                1 => '1 Star',
                                2 => '2 Stars',
                                3 => '3 Stars',
                                4 => '4 Stars',
                                5 => '5 Stars',
                            ])
                            ->default(0),

                        // Relationship to Gym Branch
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
                Tables\Columns\ImageColumn::make('image')->label('Image'),

                // Custom Formatting for Rating Column
                Tables\Columns\TextColumn::make('rating')
                    ->sortable()
                    ->label('Rating')
                    ->formatStateUsing(fn($record) => match ($record->rating) {
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                        default => '-',
                    }),

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Gym/Kos')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
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
            'index' => Pages\ListTestimonis::route('/'),
            'create' => Pages\CreateTestimoni::route('/create'),
            'edit' => Pages\EditTestimoni::route('/{record}/edit'),
        ];
    }
}
