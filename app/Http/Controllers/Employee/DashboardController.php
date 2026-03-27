<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Gymkos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $trainerId = $user->trainer ? $user->trainer->id : null;

        $products = Product::latest()->get();
        $gymkosList = Gymkos::all();

        // Query dasar: Hanya ambil transaksi milik kasir ini
        $query = Transaction::with(['items', 'gymkos'])->where('trainer_id', $trainerId)->latest();

        // 1. Filter Pencarian (Berdasarkan Kode TRX atau Nama Customer)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // 2. Filter Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 3. Filter Metode Pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // 4. Filter Cabang (Gymkos)
        if ($request->filled('gymkos_id')) {
            $query->where('gymkos_id', $request->gymkos_id);
        }

        // Eksekusi dengan Pagination (10 data per halaman) + Pertaankan URL Filter
        $transactions = $query->paginate(10)->withQueryString();

        return view('employee.dashboard.index', compact('products', 'gymkosList', 'transactions', 'trainerId'));
    }
}
