<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PrintController extends Controller
{
    public function printStruk($code)
    {
        // Cetak struk dipanggil dari panel admin Filament (TransactionResource),
        // jadi hanya role yang bisa masuk panel yang boleh mencetak.
        if (! in_array(Auth::user()->role, ['admin', 'super_admin'], true)) {
            abort(403, 'Akses Ditolak. Hanya Admin yang bisa mencetak struk.');
        }

        // 1. Find Transaction by CODE
        $transaction = Transaction::with(['items', 'trainer', 'gymkos'])
            ->where('code', $code)
            ->firstOrFail();

        // 2. Return the print view
        return view('print.struk', compact('transaction'));
    }
}
