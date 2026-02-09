<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;

class QrCodePrintController extends Controller
{
    // Logic: Handle Single Invocation
    // This controller only does one thing: show the print view
    public function __invoke(QrCode $qrCode)
    {
        // Pass the QrCode model to the view
        return view('print.qr-code', compact('qrCode'));
    }
}
