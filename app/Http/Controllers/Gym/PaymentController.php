<?php

namespace App\Http\Controllers\Gym;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function uploadPayment(Request $request)
    {
        // 1. Validate User Input
        $request->validate([
            'gym_id' => 'required|integer',
            'member_id' => 'nullable|integer|exists:members,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8096', // Max 8MB
            'membership_type' => 'required|string',
            'payment' => 'required|string',
        ]);

        // 2. Server-Side Pricing Logic (Secure)
        // Prevents users from manipulating price via HTML inspector
        $price = 0;

        switch ($request->membership_type) {
            case 'Member Harian':
                $price = 10000;
                break;
            case 'Member Mingguan':
                $price = 30000;
                break;
            case 'Member Bulanan':
                $price = 85000;
                break;
            default:
                $price = 10000; // Fallback
        }

        // 3. Generate Unique Order ID
        $orderID = 'B11N-K1NG-' . strtoupper(Str::random(8));

        // 4. Upload Image
        $imagePath = $request->file('image')->store('payment_receipts', 'public');

        // 5. Save to Database
        $payment = new Payment();
        $payment->order_id = $orderID;
        $payment->member_id = $request->member_id;
        $payment->name = $request->name;
        $payment->email = $request->email;
        $payment->phone = $request->phone;
        $payment->image = $imagePath;
        $payment->membership_type = $request->membership_type;
        $payment->payment = $request->payment;
        $payment->price = $price;
        $payment->status = 'pending'; // Default status

        // Pass 'gym_id' as a temporary attribute so Model Event (created) can catch it
        // This is used to automatically create a Finance entry later
        $payment->gym_id_temporary = $request->gym_id;

        $payment->save();

        // 6. Send Email Notification (Try-Catch block prevents crash if mail fails)
        try {
            Mail::to($request->email)->send(new PaymentConfirmationMail($payment));
        } catch (\Exception $e) {
            // Log error silently, don't stop the process
        }

        // 7. Return JSON Response (for AJAX frontend)
        return response()->json(['message' => 'Bukti pembayaran berhasil diupload!', 'order_id' => $orderID]);
    }
}
