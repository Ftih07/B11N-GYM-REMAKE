<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\WebVisitor;

class TrackVisitor
{
    // Function Goal: Record visitor IP and Date
    public function handle(Request $request, Closure $next): Response
    {
        // Logic: Track Unique Visitors (Per Day)
        // 'firstOrCreate' checks if [ip_address + visit_date] exists.
        // If YES -> Do nothing (It's the same person returning today).
        // If NO  -> Create new record (New unique visitor for today).
        WebVisitor::firstOrCreate([
            'ip_address' => $request->ip(),
            'visit_date' => now()->toDateString(), // Save date only (Y-m-d)
        ], [
            'user_agent' => $request->header('User-Agent'), // Track browser/device info
        ]);

        return $next($request);
    }
}
