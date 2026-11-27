<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alias `admin`: hanya akun yang boleh masuk panel Filament (admin / super_admin).
 * Dipakai untuk halaman cetak yang dibuka dari panel (struk transaksi, QR code).
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('filament.admin.auth.login'));
        }

        abort_unless(in_array($user->role, ['admin', 'super_admin'], true), 403, 'Halaman ini hanya untuk admin.');

        return $next($request);
    }
}
