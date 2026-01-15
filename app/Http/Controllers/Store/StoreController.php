<?php

namespace App\Http\Controllers\Store;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Logo;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function showBiinKingGymStore(Request $request)
    {
        $totalProducts = Product::count(); // Menghitung jumlah total produk

        $categoryId = $request->input('category'); // Ambil ID kategori dari query string

        // Ambil semua produk, dengan filter kategori jika diperlukan
        $products = Product::where('status', 'ready')
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_products_id', $categoryId);
            })
            ->get();

        $banner = Banner::where('stores_id', 3)->get();


        return view('store.biin-king-gym-store.index', compact('products', 'categoryId', 'totalProducts', 'banner'));
    }

    public function showBiinStore(Request $request)
    {
        $logo = Logo::where('gymkos_id', 1)->get();
        $storeId = 1; // ID untuk B11N Store
        $categoryId = $request->input('category'); // Ambil ID kategori dari query string

        // Ambil produk dengan stores_id 1, status ready, dan filter kategori jika diperlukan
        $products = Product::where('stores_id', $storeId)
            ->where('status', 'ready')
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_products_id', $categoryId);
            })
            ->get();

        $banner = Banner::where('stores_id', 1)->get();

        // Hitung jumlah produk
        $totalProducts = $products->count();

        // Kirim data ke view
        return view('store.biin-gym-store.index', compact('products', 'totalProducts', 'banner', 'categoryId', 'logo'));
    }

    public function showKingStore(Request $request)
    {
        $storeId = 2; // ID untuk King Gym Store
        $categoryId = $request->input('category'); // Ambil ID kategori dari query string

        // Ambil produk dengan stores_id 2, status ready, dan filter kategori jika diperlukan
        $products = Product::where('stores_id', $storeId)
            ->where('status', 'ready')
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_products_id', $categoryId);
            })
            ->get();

        $banner = Banner::where('stores_id', 2)->get();

        // Hitung jumlah produk
        $totalProducts = $products->count();

        // Kirim data ke view
        return view('store.king-gym-store.index', compact('products', 'totalProducts', 'banner', 'categoryId'));
    }
}
