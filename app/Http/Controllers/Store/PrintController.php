<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- Jangan lupa import Auth

class PrintController extends Controller
{
    public function printStruk($code)
    {
        // --- PENGECEKAN ROLE MANUAL TANPA MIDDLEWARE ---
        $userRole = Auth::user()->role;

        // Kalau yang login BUKAN admin dan BUKAN employee, tendang keluar!
        if (!in_array($userRole, ['admin', 'employee'])) {
            abort(403, 'Akses Ditolak. Hanya Admin dan Kasir yang bisa mencetak struk.');
        }

        // 1. Find Transaction by CODE
        $transaction = Transaction::with(['items', 'trainer', 'gymkos'])
            ->where('code', $code)
            ->firstOrFail();

        // 2. Return the print view
        return view('print.struk', compact('transaction'));
    }
}
