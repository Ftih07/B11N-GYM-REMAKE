<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    // Function Goal: Filter requests, allow only 'admin' role
    public function handle(Request $request, Closure $next): Response
    {
        // Check 1: Is user logged in?
        // Check 2: Is user's role 'admin'?
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Allow access
        }

        // If not admin, stop here and show Error 403 (Forbidden)
        abort(403, 'Anda tidak memiliki akses untuk mencetak struk.');
    }
}
