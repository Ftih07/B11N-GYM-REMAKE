<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class PrintController extends Controller
{
    public function printStruk($code)
    {
        // Akses dibatasi middleware `admin` (admin / super_admin) di routes/web.php.
        // 1. Find Transaction by CODE
        $transaction = Transaction::with(['items', 'trainer', 'gymkos'])
            ->where('code', $code)
            ->firstOrFail();

        // 2. Return the print view
        return view('print.struk', compact('transaction'));
    }
}
