<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Header keamanan dasar untuk semua respons (SEC-12).
 *
 * CSP belum dipasang karena halaman masih memuat banyak skrip CDN dan skrip inline;
 * akan ditambahkan bertahap setelah aset di-build sendiri.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            // Cegah browser menebak tipe berkas (mis. unggahan dianggap skrip)
            'X-Content-Type-Options' => 'nosniff',
            // Cegah halaman (termasuk panel admin) dibingkai situs lain / clickjacking
            'X-Frame-Options' => 'SAMEORIGIN',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            // Kamera hanya untuk situs ini sendiri (scan wajah absensi & foto member)
            'Permissions-Policy' => 'camera=(self), microphone=(), geolocation=(), payment=()',
        ];

        // HSTS hanya bila request memang HTTPS (tidak memaksa HTTPS di lokal/HTTP)
        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=15552000';
        }

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}
