<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleEmployee
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan punya role employee
        if (Auth::check() && Auth::user()->role === 'employee') {
            return $next($request);
        }

        // Kalau bukan employee, tendang balik ke home atau halaman login
        return redirect('/');
    }
}
