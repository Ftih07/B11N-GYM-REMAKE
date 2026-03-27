<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainerResource\Pages;
use App\Models\Trainer;
use App\Models\User; // <-- Pastikan Model User di-import
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

    protected static ?string $navigationGroup = 'Managemen Karyawan';
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
                        // --- TAMBAHAN: Select User Akun Karyawan ---
                        Forms\Components\Select::make('user_id')
                            ->label('Tautkan ke Akun Login (Opsional)')
                            ->relationship('user', 'name', function ($query) {
                                // Filter hanya tampilkan user yang role-nya 'employee' 
                                // dan belum ditautkan ke trainer lain (kecuali yang sedang diedit)
                                return $query->where('role', 'employee');
                            })
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Pilih akun login karyawan. Biarkan kosong jika trainer belum punya akun.'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Trainer')
                            ->required(),

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
                            ->label('Gymkos (Cabang)')
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
                Tables\Columns\ImageColumn::make('image')->label('Image'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Trainer')
                    ->sortable()
                    ->searchable(),

                // --- TAMBAHAN: Tampilkan Email Akun Tertaut di Tabel ---
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Akun Login')
                    ->badge()
                    ->color('info')
                    ->placeholder('Belum ditautkan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Nama Gym/Kos')
                    ->sortable(),

                // Display Social URLs (Bisa di-toggle hidden biar tabel nggak kepanjangan)
                Tables\Columns\TextColumn::make('urls.whatsapp')
                    ->label('WhatsApp')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('urls.instagram')
                    ->label('Instagram')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('urls.facebook')
                    ->label('Facebook')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Tambahan Delete action biar lengkap
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
