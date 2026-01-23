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
        public static function getNavigationBadge(): ?string
    {
        return Equipment::count(); 
    }
    protected static ?string $model = Equipment::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench';
    protected static ?string $navigationGroup = 'Gym Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Equipment Details')
                    ->description('Informasi utama alat dan tutorial penggunaannya.')
                    ->schema([
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

                        // Video URL ditaruh disini (Tabel Equipment)
                        Forms\Components\TextInput::make('video_url')
                            ->label('Link Video Tutorial')
                            ->placeholder('https://youtube.com/embed/...')
                            ->url()
                            ->suffixIcon('heroicon-m-video-camera')
                            ->columnSpanFull(), // Biar panjang field-nya full 1 baris

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Fisik')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                // Gallery khusus FOTO saja
                Forms\Components\Section::make('Photo Gallery')
                    ->description('Upload foto-foto kondisi fisik alat.')
                    ->schema([
                        Forms\Components\Repeater::make('gallery')
                            ->relationship() // Relasi ke tabel gallery_equipments
                            ->schema([
                                Forms\Components\FileUpload::make('file_path')
                                    ->label('Foto')
                                    ->image()
                                    ->directory('equipment-gallery')
                                    ->required(), // Wajib karena repeater ini khusus foto
                                Forms\Components\TextInput::make('caption')
                                    ->placeholder('Contoh: Tampak Depan'),
                                Forms\Components\TextInput::make('order_index')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->grid(2) // Tampilan grid 2 kolom biar rapi
                            ->defaultItems(0)
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'maintenance',
                        'danger' => 'broken',
                    ]),
                Tables\Columns\TextColumn::make('maintenance_reports_count')
                    ->counts('maintenanceReports')
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
