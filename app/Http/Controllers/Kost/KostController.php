<?php

namespace App\Http\Controllers\Kost;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\{Gallery, Testimoni, Blog, Booking};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        // 1. Validate Input (di luar try agar pesan validasi tetap tampil normal)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date' => 'required|date',
            'room_type' => ['required', Rule::in(['750rb - AC', '500rb - Non AC'])], // = enum booking_kost.room_type
            'room_number' => 'required|integer|between:1,10',
            'paymentMethod' => 'required|string|max:50',
            'paymentProof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:8192',
        ]);

        try {
            // 2. Secure Server-Side Pricing
            $price = match ($request->room_type) {
                '750rb - AC' => 750000,
                '500rb - Non AC' => 500000,
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
                // Booking tetap sah walau email gagal; catat agar ketahuan (REL-01)
                Log::warning('Email konfirmasi booking gagal dikirim', ['booking_id' => $booking->id, 'exception' => $e]);
            }

            return back()->with('success', 'Booking berhasil! Mohon tunggu verifikasi admin.');
        } catch (\Exception $e) {
            Log::error('Booking kost gagal', ['exception' => $e]);

            return back()->with('error', 'Terjadi kesalahan, booking belum tersimpan. Silakan coba lagi atau hubungi admin.');
        }
    }
}
