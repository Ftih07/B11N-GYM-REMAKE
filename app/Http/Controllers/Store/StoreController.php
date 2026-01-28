<?php

namespace App\Http\Controllers\Store;

use App\Models\Product;
use App\Models\CategoryProduct; // <--- Tambahkan Model CategoryProduct
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;

class StoreController extends Controller
{
    public function showBiinKingGymStore(Request $request)
    {
        $totalProducts = Product::count();
        $categoryId = $request->input('category');

        // 1. Ambil semua kategori untuk dijadikan tombol filter
        $categories = CategoryProduct::all(); 

        // Ambil semua produk, dengan filter kategori jika diperlukan
        $products = Product::where('status', 'ready')
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_products_id', $categoryId);
            })
            ->get();

        $store = Store::find(3); 

        // 2. Jangan lupa masukkan 'categories' ke dalam compact
        return view('store.biin-king-gym-store.index', compact('products', 'categoryId', 'totalProducts', 'store', 'categories'));
    }
}