<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Traits\SuperAdminOnly;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

/**
 * Kelola akun login (khusus super_admin).
 *
 * - super_admin / admin  : bisa masuk panel /admin (lihat User::canAccessPanel).
 * - customer             : akun member dari login Google/Facebook, tidak bisa masuk panel.
 *
 * "Menonaktifkan" admin = ubah role-nya ke customer. Super admin tidak bisa mengubah
 * role atau menghapus akunnya sendiri, sehingga selalu ada minimal satu super_admin.
 */
class UserResource extends Resource
{
    use SuperAdminOnly;

    public const ROLES = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'customer' => 'Customer (Member)',
    ];

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Directory';

    protected static ?string $navigationLabel = 'Akun Pengguna';

    protected static ?string $modelLabel = 'Akun';

    protected static ?string $pluralModelLabel = 'Akun Pengguna';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Akun')
                    ->description('Super Admin & Admin bisa masuk panel ini. Customer adalah akun member dari login Google/Facebook.')
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

                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options(self::ROLES)
                            ->default('admin')
                            ->required()
                            ->native(false)
                            // Tidak bisa mengubah role sendiri (mencegah super admin terkunci di luar)
                            ->disabled(fn (?User $record): bool => self::isCurrentUser($record))
                            ->helperText(fn (?User $record): ?string => self::isCurrentUser($record)
                                ? 'Role akun Anda sendiri tidak bisa diubah.'
                                : 'Ubah ke Customer untuk mencabut akses panel admin.'),

                        Forms\Components\TextInput::make('password')
                            ->label(fn (string $operation): string => $operation === 'create' ? 'Password' : 'Password Baru')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->maxLength(255)
                            ->confirmed()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText(fn (string $operation): ?string => $operation === 'edit'
                                ? 'Kosongkan bila tidak ingin mengganti password.'
                                : 'Minimal 8 karakter.'),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Ulangi Password')
                            ->password()
                            ->revealable()
                            ->requiredWith('password')
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_picture')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name='.urlencode($record->name)),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->description(fn (User $record): ?string => self::isCurrentUser($record) ? 'Akun Anda' : null)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => self::ROLES[$state] ?? (string) $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('login_via')
                    ->label('Login Lewat')
                    ->state(fn (User $record): string => collect([
                        $record->google_id ? 'Google' : null,
                        $record->facebook_id ? 'Facebook' : null,
                    ])->filter()->join(', ') ?: 'Email & Password')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options(self::ROLES),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (User $record): bool => self::isCurrentUser($record))
                    ->modalDescription('Akun dihapus permanen. Member/trainer yang tertaut ke akun ini tetap ada, hanya tautan akunnya yang dilepas.'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        // Jumlah akun yang bisa masuk panel admin
        return (string) static::getModel()::whereIn('role', ['admin', 'super_admin'])->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Jumlah akun Admin & Super Admin';
    }

    public static function getPages(): array
    {
        return [
            // Tambah & edit memakai modal di halaman daftar
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    private static function isCurrentUser(?User $record): bool
    {
        return $record !== null && $record->is(auth()->user());
    }
}
