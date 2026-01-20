<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    // 1. Ubah parameter $id jadi $code (biar sesuai sama Route)
    public function printStruk($code)
    {
        // 2. JANGAN pakai findOrFail($code) langsung, karena itu nyari ID.
        // Ganti jadi logic where() manual ke kolom code.

        $transaction = Transaction::with(['items', 'trainer', 'gymkos'])
            ->where('code', $code)  // Cari baris dimana kolom 'code' = $code
            ->firstOrFail();        // Ambil data pertama, kalau gak ada return 404

        return view('print.struk', compact('transaction'));
    }
}
