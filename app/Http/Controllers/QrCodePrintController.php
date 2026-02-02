<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;

class QrCodePrintController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(QrCode $qrCode)
    {
        // Kita passing data QrCode ke view khusus print
        return view('print.qr-code', compact('qrCode'));
    }
}
