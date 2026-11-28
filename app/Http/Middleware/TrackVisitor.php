<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Catat pengunjung unik per IP per hari (web_visitors) — PERF-01.
 *
 * Hanya halaman publik yang dibuka langsung (GET HTML). Panel admin, Livewire, API, polling,
 * dan aset tidak dihitung. Kunjungan berikutnya di sesi yang sama pada hari yang sama tidak
 * menyentuh database sama sekali; kunjungan pertama hanya satu INSERT IGNORE (index unik).
 */
class TrackVisitor
{
    private const SKIP_PATHS = ['admin', 'admin/*', 'livewire/*', 'filament/*', 'api/*', 'storage/*', 'print/*', 'up'];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldTrack($request)) {
            $today = now()->toDateString();

            if ($request->session()->get('visitor_tracked_on') !== $today) {
                DB::table('web_visitors')->insertOrIgnore([
                    'ip_address' => (string) $request->ip(),
                    'visit_date' => $today,
                    'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $request->session()->put('visitor_tracked_on', $today);
            }
        }

        return $next($request);
    }

    private function shouldTrack(Request $request): bool
    {
        return $request->isMethod('GET')
            && ! $request->expectsJson()
            && ! $request->ajax()
            && ! $request->is(...self::SKIP_PATHS)
            && $request->hasSession();
    }
}
