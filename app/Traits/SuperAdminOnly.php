<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait SuperAdminOnly
{
    /**
     * Cek apakah user yang login punya role super_admin.
     */
    protected static function isSuperAdmin(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // 1. Jika menggunakan Spatie Permission:
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('super_admin');
        }

        // 2. Jika menggunakan kolom 'role' biasa di tabel users:
        return ($user->role ?? null) === 'super_admin';
    }

    /**
     * Membatasi akses masuk ke Resource / halaman.
     */
    public static function canAccess(): bool
    {
        return static::isSuperAdmin();
    }

    /**
     * Menyembunyikan menu dari sidebar navigation.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return static::isSuperAdmin();
    }

    /**
     * Otorisasi CRUD (jika tidak menggunakan Model Policy terpisah)
     */
    public static function canViewAny(): bool
    {
        return static::isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return static::isSuperAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return static::isSuperAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return static::isSuperAdmin();
    }
}
