<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EquipmentResource extends Resource
{
    // --- NAVIGATION SETTINGS ---

    // Show total equipment count in sidebar badge
    public static function getNavigationBadge(): ?string
    {
        return Equipment::count();
    }

    protected static ?string $model = Equipment::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench'; // Icon: Wrench (Tools)
    protected static ?string $navigationGroup = 'Gym Management';
    protected static ?int $navigationSort = 3;

    // --- FORM CONFIGURATION ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SECTION 1: Main Information
                Forms\Components\Section::make('Equipment Details')
                    ->description('Informasi utama alat dan tutorial penggunaannya.')
                    ->schema([
                        // Relation to Gym Location
                        Forms\Components\Select::make('gymkos_id')
                            ->relationship('gymkos', 'name')
                            ->label('Lokasi Gym/Kost')
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Alat')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('category')
                            ->options([
                                'Cardio' => 'Cardio',
                                'Strength' => 'Strength',
                                'Furniture' => 'Furniture',
                                'Electronics' => 'Electronics',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'maintenance' => 'Under Maintenance',
                                'broken' => 'Broken',
                            ])
                            ->default('active')
                            ->required(),

                        // Video Tutorial Input
                        Forms\Components\TextInput::make('video_url')
                            ->label('Link Video Tutorial')
                            ->placeholder('https://youtube.com/embed/...')
                            ->url() // Validates input as URL
                            ->suffixIcon('heroicon-m-video-camera')
                            ->columnSpanFull(), // Make it full width

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Fisik')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2), // Split section into 2 columns

                // SECTION 2: Photo Gallery (Using Repeater)
                Forms\Components\Section::make('Photo Gallery')
                    ->description('Upload foto-foto kondisi fisik alat.')
                    ->schema([
                        // Repeater allows adding multiple photos dynamically
                        Forms\Components\Repeater::make('gallery')
                            ->relationship() // Connects to 'gallery_equipments' table via relationship
                            ->schema([
                                Forms\Components\FileUpload::make('file_path')
                                    ->label('Foto')
                                    ->image()
                                    ->directory('equipment-gallery') // Save to storage/app/public/equipment-gallery
                                    ->required(),

                                Forms\Components\TextInput::make('caption')
                                    ->placeholder('Contoh: Tampak Depan'),

                                Forms\Components\TextInput::make('order_index')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->grid(2) // Display uploaded items in a grid
                            ->defaultItems(0)
                            ->collapsible(), // Allow collapsing items to save space
                    ]),
            ]);
    }

    // --- TABLE CONFIGURATION ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->sortable(),

                // Status Badge with Colors
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'maintenance',
                        'danger' => 'broken',
                    ]),

                // Show count of related Maintenance Reports
                Tables\Columns\TextColumn::make('maintenance_reports_count')
                    ->counts('maintenanceReports') // Laravel relationship count
                    ->label('Reports'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
