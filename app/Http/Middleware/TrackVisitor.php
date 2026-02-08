<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\WebVisitor; // Pastikan import model

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Logic: Kita catat IP dan Tanggal hari ini
        // firstOrCreate berfungsi: Kalau IP ini udah mampir hari ini, jangan buat baru (biar jadi Unique Visitor per hari)
        // Kalau mau hitung "Total Hits" (bukan unique), pakai create() biasa aja.

        WebVisitor::firstOrCreate([
            'ip_address' => $request->ip(),
            'visit_date' => now()->toDateString(),
        ], [
            'user_agent' => $request->header('User-Agent'),
        ]);

        return $next($request);
    }
}
