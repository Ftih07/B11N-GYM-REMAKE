<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser; // <--- 1. Tambahkan ini
use Filament\Panel; // <--- 2. Tambahkan ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser // <--- 3. Tambahkan "implements FilamentUser"
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'profile_picture',
        'email_verified_at'
    ];

    // Relasi: User mungkin adalah seorang Member
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Fungsi Dashboard Filament hanya bisa diakses user role admin
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
}
