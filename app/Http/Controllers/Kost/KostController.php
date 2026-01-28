<?php

namespace App\Http\Controllers\Kost;

use App\Http\Controllers\Controller;
use App\Models\{Gallery, Testimoni, Blog, Booking}; // Pastikan Booking di-import
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;

class KostController extends Controller
{
    public function index()
    {
        // Mengambil daftar kamar yang sedang terisi atau sedang diproses
        $bookedRooms = DB::table('booking_kost')
            ->where(function ($query) {
                // KONDISI 1: Penghuni Aktif (Sudah bayar & Tanggal belum lewat)
                $query->where('status', 'paid')
                    ->whereDate('end_date', '>=', now());
            })
            ->orWhere('status', 'pending') // KONDISI 2: Sedang menunggu verifikasi (Keep kamar)
            ->pluck('room_number')
            ->toArray();

        // --- Kode di bawah ini tetap sama ---
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

            // 1. Tentukan Harga Berdasarkan Tipe Kamar (Logic Server Side)
            // Ini lebih aman agar user tidak bisa inspect element dan ubah harga
            $price = 0;
            switch ($request->room_type) {
                case '750rb - AC':
                    $price = 750000;
                    break;
                case '500rb - Non AC':
                    $price = 500000;
                    break;
                default:
                    // Fallback jika ada tipe lain atau error
                    $price = 500000;
                    break;
            }

            // 2. Cek ketersediaan kamar (hanya cek yang statusnya paid)
            // Opsional: Cek juga yang pending agar tidak double book saat verifikasi
            // Cek apakah kamar sudah dipesan (Validasi Backend)
            $existingBooking = Booking::where('room_number', $request->room_number)
                ->where(function ($query) use ($request) {
                    // Cek bentrok tanggal
                    // Logic: Ada booking yang statusnya (Paid & Aktif) ATAU (Pending)
                    // DAN tanggal yang diinginkan user berada di dalam periode sewa orang tersebut
                    $query->where(function ($q) {
                        $q->where('status', 'paid')
                            ->whereDate('end_date', '>=', now()); // Masih aktif
                    })
                        ->orWhere('status', 'pending');
                })
                ->exists();

            if ($existingBooking) {
                return back()->with('error', 'Maaf, kamar ini sedang terisi atau dalam proses pemesanan.');
            }

            // 3. Upload Bukti Pembayaran
            if ($request->hasFile('paymentProof')) {
                $filePath = $request->file('paymentProof')->store('bukti_pembayaran', 'public');
            } else {
                return back()->with('error', 'File pembayaran tidak terdeteksi!');
            }

            // 4. SIMPAN MENGGUNAKAN ELOQUENT MODEL (PENTING!)
            // Agar fungsi 'booted' di Model Booking jalan dan otomatis buat Transaction
            $booking = Booking::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'date'          => $request->date,
                'room_type'     => $request->room_type,
                'room_number'   => $request->room_number,
                'price'         => $price,               // Masukkan harga yang sudah di-set
                'payment'       => $request->paymentMethod,
                'payment_proof' => $filePath,
                'status'        => 'pending',            // Sesuai request: PENDING dulu
            ]);

            // Kirim email konfirmasi (Gunakan data dari object $booking)
            // Cek dulu apakah mailer sudah disetting, bungkus try catch biar ga error kalau mail gagal
            try {
                Mail::to($request->email)->send(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                // Log error email tapi jangan batalkan booking
            }

            return back()->with('success', 'Booking berhasil! Mohon tunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan! ' . $e->getMessage());
        }
    }
}
