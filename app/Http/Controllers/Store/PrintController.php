<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    // Function Goal: Generate a printable receipt view
    // Parameter: $code (Transaction Code, e.g., TRX-12345)
    public function printStruk($code)
    {
        // 1. Find Transaction by CODE (Not ID)
        // We use 'where' instead of 'find' because $code is a string column
        $transaction = Transaction::with(['items', 'trainer', 'gymkos']) // Eager load relationships for performance
            ->where('code', $code)
            ->firstOrFail(); // Throw 404 if code not found

        // 2. Return the print view
        return view('print.struk', compact('transaction'));
    }
}
