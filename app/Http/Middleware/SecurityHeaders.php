<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Header keamanan untuk semua respons (SEC-12).
 *
 * CSP membatasi asal skrip/CSS/font/iframe ke situs ini + CDN yang terdaftar di config/cdn.php.
 * 'unsafe-inline' & 'unsafe-eval' masih diperlukan (skrip inline di view, Alpine/Livewire/Filament,
 * Tailwind Play), jadi CSP ini terutama menolak skrip dari domain asing, plugin, <base> palsu,
 * dan pembingkaian oleh situs lain. Bisa dimatikan lewat CSP_ENABLED=false.
 */
class SecurityHeaders
{
    /** @var array<string, list<string>> */
    private const CSP = [
        'default-src' => ["'self'"],
        'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'", 'https://cdn.jsdelivr.net'],
        'style-src' => ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com', 'https://fonts.bunny.net', 'https://cdn.jsdelivr.net', 'https://cdnjs.cloudflare.com'],
        'font-src' => ["'self'", 'data:', 'https://fonts.gstatic.com', 'https://fonts.bunny.net', 'https://cdn.jsdelivr.net', 'https://cdnjs.cloudflare.com'],
        // Gambar boleh dari HTTPS mana pun (avatar Filament, ikon login, gambar di artikel)
        'img-src' => ["'self'", 'data:', 'blob:', 'https:'],
        'media-src' => ["'self'", 'data:', 'blob:'],
        'connect-src' => ["'self'"],
        'worker-src' => ["'self'", 'blob:'],
        // Embed Google Maps (halaman gym) & YouTube (halaman kost)
        'frame-src' => ["'self'", 'https://www.google.com', 'https://maps.google.com', 'https://www.youtube.com', 'https://www.youtube-nocookie.com'],
        'frame-ancestors' => ["'self'"],
        'object-src' => ["'none'"],
        'base-uri' => ["'self'"],
        'form-action' => ["'self'"],
    ];

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

        if (config('b1ng.csp.enabled')) {
            $headers['Content-Security-Policy'] = $this->contentSecurityPolicy($request);
        }

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }

    private function contentSecurityPolicy(Request $request): string
    {
        $directives = array_map(
            fn (string $name, array $sources) => $name.' '.implode(' ', $sources),
            array_keys(self::CSP),
            self::CSP,
        );

        if ($request->isSecure()) {
            $directives[] = 'upgrade-insecure-requests';
        }

        if ($reportUri = config('b1ng.csp.report_uri')) {
            $directives[] = 'report-uri '.$reportUri;
        }

        return implode('; ', $directives);
    }
}
