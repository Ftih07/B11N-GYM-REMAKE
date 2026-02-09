<?php

namespace App\Http\Controllers\Kost;

use App\Http\Controllers\Controller;
use App\Models\{Gallery, Testimoni, Blog, Booking};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;

class KostController extends Controller
{
    public function index()
    {
        // 1. Get Occupied Rooms (For Frontend Disabled State)
        $bookedRooms = DB::table('booking_kost')
            ->where(function ($query) {
                // Condition A: Active & Paid
                $query->where('status', 'paid')
                    ->whereDate('end_date', '>=', now());
            })
            ->orWhere('status', 'pending') // Condition B: Pending Verification (Hold)
            ->pluck('room_number')
            ->toArray();

        // 2. Fetch CMS Data (Specific to Gym/Kost ID 3)
        $gallery = Gallery::where('gymkos_id', 3)->get();
        $blog = Blog::published()->take(3)->get();
        $testimonis = Testimoni::where('gymkos_id', 3)->get()->map(function ($t) {
            $t->rating = max(1, $t->rating);
            return $t;
        });

        return view('kost.istana-merdeka-3.index', compact(
            'blog',
            'testimonis',
            'gallery',
            'bookedRooms'
        ));
    }

    public function store(Request $request)
    {
        try {
            // 1. Validate Input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string',
                'date' => 'required|date',
                'room_type' => 'required|string',
                'room_number' => 'required|integer',
                'paymentMethod' => 'required|string',
                'paymentProof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:8192',
            ]);

            // 2. Secure Server-Side Pricing
            $price = match ($request->room_type) {
                '750rb - AC' => 750000,
                '500rb - Non AC' => 500000,
                default => 500000,
            };

            // 3. Double Check Availability (Backend Validation)
            // Prevents race conditions or inspecting element hacks
            $existingBooking = Booking::where('room_number', $request->room_number)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('status', 'paid')
                            ->whereDate('end_date', '>=', now()); // Active Lease
                    })
                        ->orWhere('status', 'pending'); // Pending Verification
                })
                ->exists();

            if ($existingBooking) {
                return back()->with('error', 'Maaf, kamar ini sedang terisi atau dalam proses pemesanan.');
            }

            // 4. Upload Proof
            $filePath = $request->file('paymentProof')->store('bukti_pembayaran', 'public');

            // 5. Create Booking Record
            $booking = Booking::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date' => $request->date,
                'room_type' => $request->room_type,
                'room_number' => $request->room_number,
                'price' => $price,
                'payment' => $request->paymentMethod,
                'payment_proof' => $filePath,
                'status' => 'pending',
            ]);

            // 6. Send Email Confirmation
            try {
                Mail::to($request->email)->send(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                // Log error silently
            }

            return back()->with('success', 'Booking berhasil! Mohon tunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan! ' . $e->getMessage());
        }
    }
}
