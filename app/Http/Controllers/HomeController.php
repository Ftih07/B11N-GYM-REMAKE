<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Facilities;
use App\Models\Trainer;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Store;
use App\Models\Banner;
use App\Models\CategoryTraining;
use App\Models\Gallery;
use App\Models\Logo;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use App\Models\Testimoni;
use Illuminate\Support\Facades\DB;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    //
    public function index()
    {
        $blog = Blog::published()->take(3)->get();
        $facilities = Facilities::where('gymkos_id', 1)->get();
        $trainer = Trainer::where('gymkos_id', 1)->get();
        $banner = Banner::where('stores_id', 3)->get();
        $logo = Logo::where('gymkos_id', 1)->get();
        $about = About::where('gymkos_id', 1)->get();
        $trainingprograms = TrainingProgram::where('gymkos_id', 1)->get();
        $gallery = Gallery::where('gymkos_id', 1)->get();

        // Mengelompokkan berdasarkan category_trainings_id
        $groupedTrainingPrograms = $trainingprograms->groupBy('category_trainings_id');

        // Mengambil data kategori
        $categories = CategoryTraining::whereIn('id', $groupedTrainingPrograms->keys())->get()->keyBy('id');

        $testimonis = \App\Models\Testimoni::where('gymkos_id', 1)
            ->get()
            ->map(function ($testimoni) {
                $testimoni->rating = max(1, $testimoni->rating); // Set minimal rating ke 1
                return $testimoni;
            });

        return view('index', compact('facilities', 'trainer', 'blog', 'banner', 'logo', 'about', 'groupedTrainingPrograms', 'categories', 'testimonis', 'gallery'));
    }


    public function product()
    {
        $stores = Store::withCount('products')->get();
        // Mendapatkan produk dengan status 'ready' yang terhubung dengan gym 'B11N Gym'
        $products = Product::where('status', 'ready')
            ->whereHas('gymkos', function ($query) {
                $query->whereNotNull('name')  // Pastikan 'name' tidak null
                    ->where('name', 'B11N Gym');
            })
            ->get();

        return view('product', compact('products'));
    }

    public function blog()
    {
        $blogs = Blog::published()->get();
        return view('blog', compact('blogs'));
    }

    public function kinggym()
    {
        $blog = Blog::published()->take(3)->get();
        $facilities = Facilities::where('gymkos_id', 2)->get();
        $trainer = Trainer::where('gymkos_id', 2)->get();
        $banner = Banner::where('stores_id', 3)->get();
        $logo = Logo::where('gymkos_id', 2)->get();
        $about = About::where('gymkos_id', 2)->get();
        $trainingprograms = TrainingProgram::where('gymkos_id', 1)->get();
        $gallery = Gallery::where('gymkos_id', 2)->get();

        $groupedTrainingPrograms = $trainingprograms->groupBy('category_trainings_id');

        // Mengambil data kategori
        $categories = CategoryTraining::whereIn('id', $groupedTrainingPrograms->keys())->get()->keyBy('id');

        $testimonis = \App\Models\Testimoni::where('gymkos_id', 2)
            ->get()
            ->map(function ($testimoni) {
                $testimoni->rating = max(1, $testimoni->rating); // Set minimal rating ke 1
                return $testimoni;
            });

        return view('kinggym', compact('facilities', 'trainer', 'blog', 'banner', 'logo', 'about', 'groupedTrainingPrograms', 'categories', 'testimonis', 'gallery'));
    }

    public function home()
    {
        $blog = Blog::published()->take(3)->get();

        return view('home', compact('blog'));
    }

    public function kost()
    {
        $bookedRooms  = DB::table('bookings')
            ->where('status', 'paid')
            ->pluck('room_number') // Ambil hanya kolom tanggal
            ->toArray(); // Ubah jadi array

        $gallery = Gallery::where('gymkos_id', 2)->get();
        $blog = Blog::published()->take(3)->get();

        $testimonis = \App\Models\Testimoni::where('gymkos_id', 3)
            ->get()
            ->map(function ($testimoni) {
                $testimoni->rating = max(1, $testimoni->rating); // Set minimal rating ke 1
                return $testimoni;
            });

        return view('kost', compact('blog', 'testimonis', 'gallery', 'bookedRooms'));
    }

    public function bookKost(Request $request)
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
            $existingBooking = DB::table('bookings')
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
            $bookingId = DB::table('bookings')->insertGetId([
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
            $bookingData = DB::table('bookings')->where('id', $bookingId)->first();

            // Kirim email konfirmasi
            Mail::to($request->email)->send(new BookingConfirmationMail($bookingData));

            return back()->with('success', 'Booking berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan! ' . $e->getMessage());
        }
    }
}
