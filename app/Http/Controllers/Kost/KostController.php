<?php

namespace App\Http\Controllers\Kost;

use App\Http\Controllers\Controller;
use App\Models\{Gallery, Testimoni, Blog};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;

class KostController extends Controller
{
    public function index()
    {
        $bookedRooms = DB::table('booking_kost')
            ->where('status', 'paid')
            ->pluck('room_number')
            ->toArray();

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

            // Cek apakah kamar sudah dipesan dengan status "paid"
            $existingBooking = DB::table('booking_kost')
                ->where('date', $request->date)
                ->where('room_number', $request->room_number)
                ->where('status', 'paid')
                ->exists();

            if ($existingBooking) {
                return back()->with('error', 'Maaf, kamar ini sudah dipesan.');
            }

            // Simpan bukti pembayaran
            if ($request->hasFile('paymentProof')) {
                $filePath = $request->file('paymentProof')->store('bukti_pembayaran', 'public');
            } else {
                return back()->with('error', 'File pembayaran tidak terdeteksi!');
            }

            // Simpan ke database
            $bookingId = DB::table('booking_kost')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date' => $request->date,
                'room_type' => $request->room_type,
                'room_number' => $request->room_number,
                'payment' => $request->paymentMethod,
                'payment_proof' => $filePath,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Ambil data booking yang baru saja disimpan
            $bookingData = DB::table('booking_kost')->where('id', $bookingId)->first();

            // Kirim email konfirmasi
            Mail::to($request->email)->send(new BookingConfirmationMail($bookingData));

            return back()->with('success', 'Booking berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan! ' . $e->getMessage());
        }
    }
}
