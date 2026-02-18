<?php

namespace App\Http\Controllers\Store;

use App\Models\Product;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;

class StoreController extends Controller
{
    // Function Goal: Show Store Catalog with Category Filter
    public function showBiinKingGymStore(Request $request)
    {
        // 1. Ambil Parameter
        $categoryId = $request->input('category');
        $search = $request->input('search'); 

        // 2. Fetch Categories & Store
        $categories = CategoryProduct::all();
        $store = Store::find(3);

        // 3. Query Products
        $products = Product::where('status', 'ready')
            // Filter Category (tetap sama)
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_products_id', $categoryId);
            })
            // Filter Search (TAMBAHAN BARU)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });

        // 4. Sorting Logic
        // Kalau sedang search, jangan di-random biar user tidak bingung
        if ($search) {
            $products->latest();
        } else {
            $products->inRandomOrder(crc32(session()->getId()));
        }

        // 5. Eksekusi Pagination
        // Gunakan appends supaya parameter search tidak hilang saat ganti halaman
        $products = $products->paginate(8)->appends(request()->query());

        // Hitung total setelah filter (opsional, biar angka di atas berubah sesuai hasil search)
        $totalProducts = $products->total();

        return view('store.biin-king-gym-store.index', compact(
            'products',
            'categoryId',
            'totalProducts',
            'store',
            'categories'
        ));
    }
}
