<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'date' => 'required|date',
            'room_type' => 'required',
            'payment' => 'required',
        ]);

        $booking = Booking::create($validated);

        return response()->json([
            'message' => 'Booking berhasil, lanjutkan pembayaran!',
            'booking_id' => $booking->id
        ]);
    }

    public function uploadProof(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $booking = Booking::find($request->booking_id);
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $booking->update(['payment_proof' => $path, 'status' => 'paid']);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diunggah!',
            'status' => 'paid'
        ]); 
    }
    
}
