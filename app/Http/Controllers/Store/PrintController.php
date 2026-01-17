<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printStruk($id)
    {
        $transaction = Transaction::with(['items', 'trainer', 'gymkos'])->findOrFail($id);

        return view('print.struk', compact('transaction'));
    }
}
