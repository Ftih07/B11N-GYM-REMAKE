<?php

namespace App\Http\Controllers\Gym;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Carbon\Carbon; // Jangan lupa import Carbon

class PaymentController extends Controller
{
    public function uploadPayment(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|integer', // Validasi ID Gym
            'member_id' => 'nullable|integer|exists:members,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8096',
            'membership_type' => 'required|string',
            'payment' => 'required|string',
        ]);

        // 1. Tentukan Harga & Durasi (Logic Server Side)
        $price = 0;
        $endDate = now(); // Default hari ini

        switch ($request->membership_type) {
            case 'Member Harian':
                $price = 10000;
                $endDate = now()->addDay(); // +1 Hari
                break;
            case 'Member Mingguan':
                $price = 30000;
                $endDate = now()->addWeek(); // +1 Minggu
                break;
            case 'Member Bulanan':
                $price = 85000;
                $endDate = now()->addMonth(); // +1 Bulan
                break;
            default:
                $price = 10000; // Fallback
                $endDate = now()->addDay();
        }

        // Generate Order ID
        $orderID = 'B11N-K1NG-' . strtoupper(Str::random(8));

        // Simpan Bukti Pembayaran
        $imagePath = $request->file('image')->store('payment_receipts', 'public');

        // Simpan ke Database
        // PENTING: Kita kirim gym_id via request attributes agar bisa ditangkap oleh Model Event nanti (opsional) 
        // ATAU kita simpan gym_id di tabel payment jika tabel payment punya kolom gym_id (Recommended).
        // TAPI karena di instruksi sebelumnya tabel payment tidak punya gym_id, kita pakai trik "Temporary Attribute".

        $payment = new Payment();
        $payment->order_id = $orderID;
        $payment->member_id = $request->member_id;
        $payment->name = $request->name;
        $payment->email = $request->email;
        $payment->phone = $request->phone;
        $payment->image = $imagePath;
        $payment->membership_type = $request->membership_type;
        $payment->payment = $request->payment;
        $payment->price = $price;       // Harga otomatis
        $payment->status = 'pending';   // Default pending (Tunggu admin confirm)

        // Simpan Gym ID di properti sementara (bukan kolom database) agar bisa dibaca di Model
        $payment->gym_id_temporary = $request->gym_id;

        $payment->save();

        // Kirim Email (Opsional: Bungkus try-catch biar ga error kalau mail gagal)
        try {
            Mail::to($request->email)->send(new PaymentConfirmationMail($payment));
        } catch (\Exception $e) {
            // Log error email
        }

        return response()->json(['message' => 'Bukti pembayaran berhasil diupload!', 'order_id' => $orderID]);
    }
}
