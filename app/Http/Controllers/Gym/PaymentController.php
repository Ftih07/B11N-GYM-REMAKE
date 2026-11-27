<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Mail\PaymentConfirmationMail;
use App\Models\Member;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Harga paket ditentukan server (bukan dari form) agar tidak bisa dimanipulasi.
     * Nama paket juga dipakai Payment::booted() untuk membuat produk "Membership …",
     * jadi hanya nilai di daftar ini yang diterima (INT-09).
     */
    private const PRICES = [
        'Member Harian' => 10000,
        'Member Mingguan' => 35000,
        'Member Bulanan' => 90000,
    ];

    public function uploadPayment(Request $request)
    {
        // 1. Validate User Input
        $request->validate([
            'gym_id' => 'required|integer|in:1,2', // hanya cabang gym (B11N/K1NG)
            'member_id' => 'nullable|integer|exists:members,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8096', // Max 8MB
            'membership_type' => ['required', Rule::in(array_keys(self::PRICES))],
            'payment' => 'required|string|max:50',
        ]);

        // 2. member_id hanya boleh milik akun yang sedang login (SEC-14).
        //    Pengunjung tanpa login (form di halaman gym) tidak boleh menyebut member tertentu.
        $memberId = null;
        if ($request->filled('member_id')) {
            $user = $request->user();
            $member = $user
                ? Member::query()
                    ->whereKey($request->member_id)
                    ->where(fn ($q) => $q->where('user_id', $user->id)->orWhere('email', $user->email))
                    ->first()
                : null;

            if (! $member) {
                throw ValidationException::withMessages([
                    'member_id' => 'Data member tidak cocok dengan akun yang sedang login.',
                ]);
            }

            $memberId = $member->id;
        }

        // 3. Server-Side Pricing
        $price = self::PRICES[$request->membership_type];

        // 4. Generate Unique Order ID
        $orderID = 'B11N-K1NG-'.strtoupper(Str::random(8));

        // 5. Upload Image
        $imagePath = $request->file('image')->store('payment_receipts', 'public');

        // 6. Save to Database
        $payment = new Payment;
        $payment->order_id = $orderID;
        $payment->member_id = $memberId;
        $payment->name = $request->name;
        $payment->email = $request->email;
        $payment->phone = $request->phone;
        $payment->image = $imagePath;
        $payment->membership_type = $request->membership_type;
        $payment->payment = $request->payment;
        $payment->price = $price;
        $payment->status = 'pending'; // Default status

        // Pass 'gym_id' as a temporary attribute so Model Event (created) can catch it
        $payment->gym_id_temporary = (int) $request->gym_id;

        $payment->save();

        // 7. Send Email Notification (pembayaran tetap tersimpan walau email gagal)
        try {
            Mail::to($request->email)->send(new PaymentConfirmationMail($payment));
        } catch (\Exception $e) {
            Log::warning('Email konfirmasi pembayaran gagal dikirim', ['payment_id' => $payment->id, 'exception' => $e]);
        }

        $message = 'Bukti pembayaran berhasil dikirim! Silakan tunggu konfirmasi Admin.';

        // 8. Form biasa (modal "Perpanjang" di dashboard member) → redirect dengan pesan;
        //    AJAX (modal "Beli Sekarang" di halaman gym) → JSON.
        if (! $request->expectsJson()) {
            return back()->with('success', $message);
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'order_id' => $orderID,
        ], 200);
    }
}
