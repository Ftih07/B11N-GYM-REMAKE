<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Directory';
    protected static ?int $navigationSort = 10;

    // --- TAMBAHAN: FORM CREATE/EDIT ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Akun Karyawan')
                    ->description('Buat kredensial login untuk karyawan baru. Role otomatis diset sebagai Employee.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            // Wajib diisi saat create, opsional saat edit
                            ->required(fn(string $context): bool => $context === 'create')
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->maxLength(255),

                        // KUNCI ROLE: Disembunyikan dari form tapi nilainya dikirim ke database
                        Forms\Components\Hidden::make('role')
                            ->default('employee'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_picture')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?name=User&background=0D8ABC&color=fff')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger', // Merah buat Admin biar match tema B1NG Empire
                        'employee' => 'warning', // Kuning/Warning untuk karyawan
                        'user' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'employee' => 'Karyawan / Employee',
                        'user' => 'User / Customer',
                    ]),
            ])
            // Tambahkan tombol Create di Header Tabel
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Karyawan')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // 1. HAPUS FUNGSI canCreate() AGAR DEFAULTNYA JADI TRUE
    // public static function canCreate(): bool
    // {
    //     return false; 
    // }

    public static function getPages(): array
    {
        return [
            // Karena hanya ada halaman index, kita arahkan aksi create menggunakan Modal.
            // Form akan otomatis muncul di popup modal saat tombol "Tambah Karyawan" diklik.
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
