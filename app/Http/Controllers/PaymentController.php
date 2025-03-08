<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function uploadPayment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8096',
            'membership_type' => 'required|string',
            'payment' => 'required|string',
        ]);

        // Generate Order ID
        $orderID = 'B11N-K1NG-' . strtoupper(Str::random(8));

        // Simpan Bukti Pembayaran
        $imagePath = $request->file('image')->store('payment_receipts', 'public');

        // Simpan ke Database
        $payment = Payment::create([
            'order_id' => $orderID,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imagePath,
            'membership_type' => $request->membership_type,
            'payment' => $request->payment,
            'status' => 'confirmed', // Default status pending
        ]);

        // Kirim Email Konfirmasi ke User
        Mail::to($request->email)->send(new PaymentConfirmationMail($payment));

        return response()->json(['message' => 'Bukti pembayaran berhasil diupload!', 'order_id' => $orderID]);
    }
}
