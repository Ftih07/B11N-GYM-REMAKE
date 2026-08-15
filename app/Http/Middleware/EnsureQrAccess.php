<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureQrAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $validToken = env('FRONTDESK_QR_TOKEN', 'b1ng-empire-secret-frontdesk-2026');

        // 1. Cek jika link membawa ?token=...
        if ($request->filled('token')) {
            if ($request->query('token') === $validToken) {
                // Set session bahwa perangkat ini sudah discan
                session(['employee_authenticated' => true]);

                // Redirect ke URL bersih tanpa query string
                return redirect()->route('employee.members.index');
            }

            abort(403, 'Token QR Code tidak valid atau sudah diganti oleh Admin.');
        }

        // 2. Cek apakah session sudah aktif
        if (session('employee_authenticated') === true) {
            return $next($request);
        }

        // 3. Jika belum scan QR
        abort(403, 'Akses Ditolak. Halaman ini hanya dapat diakses melalui Scan QR Code Meja Front Desk.');
    }
}
