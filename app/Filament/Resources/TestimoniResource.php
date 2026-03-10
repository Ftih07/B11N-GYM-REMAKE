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
            ->filters([
                // 1. Filter Berdasarkan Rating (Bintang)
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Filter Rating')
                    ->options([
                        5 => '⭐⭐⭐⭐⭐ (5 Stars)',
                        4 => '⭐⭐⭐⭐ (4 Stars)',
                        3 => '⭐⭐⭐ (3 Stars)',
                        2 => '⭐⭐ (2 Stars)',
                        1 => '⭐ (1 Star)',
                    ]),

                // 2. Filter Berdasarkan Lokasi Gym/Kos
                Tables\Filters\SelectFilter::make('gymkos_id')
                    ->relationship('gymkos', 'name')
                    ->label('Lokasi Gym/Kos')
                    ->searchable()
                    ->preload(),

                // 3. Filter Rentang Waktu Ulasan Dibuat
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when($data['created_from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
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
