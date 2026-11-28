<?php

namespace App\Http\Middleware;

use App\Services\MembershipStatusService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pastikan status member kedaluwarsa sudah `inactive` sebelum halaman yang menampilkannya
 * (panel admin, portal member) dibuka. Murah: hanya satu cek cache, UPDATE massal
 * paling banyak sekali per jam (lihat MembershipStatusService).
 */
class ExpireOverdueMemberships
{
    public function __construct(private MembershipStatusService $memberships) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->memberships->expireOverdueThrottled();

        return $next($request);
    }
}
