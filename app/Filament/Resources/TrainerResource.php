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
    // --- PENGATURAN NAVIGASI ---
    public static function getNavigationBadge(): ?string
    {
        return Trainer::count() ?: null;
    }

    protected static ?string $navigationGroup = 'Manajemen Karyawan'; // Typo diperbaiki jadi Manajemen
    protected static ?string $navigationLabel = 'Data Trainer';
    protected static ?string $pluralModelLabel = 'Data Trainer';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = Trainer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group'; // Ikon: Grup Pengguna

    // --- KONFIGURASI FORM ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Trainer')
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
                            ->label('Deskripsi')
                            ->required()
                            ->maxLength(65535),

                        // Grup Tautan Media Sosial
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('urls.whatsapp')
                                ->label('Tautan WhatsApp')
                                ->url()
                                ->required(),
                            Forms\Components\TextInput::make('urls.instagram')
                                ->label('Tautan Instagram')
                                ->url()
                                ->required(),
                            Forms\Components\TextInput::make('urls.facebook')
                                ->label('Tautan Facebook')
                                ->url()
                                ->required(),
                        ])->columns(3),

                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Trainer')
                            ->image()
                            ->required()
                            ->directory('trainer'),

                        Forms\Components\Select::make('gymkos_id')
                            ->label('Cabang (Gym/Kos)')
                            ->relationship('gymkos', 'name')
                            ->required(),
                    ])
            ]);
    }

    // --- KONFIGURASI TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->circular(), // Biar fotonya bulat (opsional)

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
                    ->label('Nama Cabang')
                    ->sortable(),

                // Tampilkan Tautan Sosial (Bisa di-toggle hidden biar tabel nggak kepanjangan)
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
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'), // Tambahan Delete action biar lengkap
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Pilihan'),
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
