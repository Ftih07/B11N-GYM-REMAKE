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
        $totalProducts = Product::count();

        // Get 'category' from URL parameter (?category=1)
        $categoryId = $request->input('category');

        // 1. Fetch Categories (For Sidebar/Filter Buttons)
        $categories = CategoryProduct::all();

        // 2. Fetch Products with Logic
        $products = Product::where('status', 'ready')
            // Conditional Query: Only apply where clause if $categoryId exists
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_products_id', $categoryId);
            })
            ->get();

        // 3. Fetch Store Info (Hardcoded ID 3 based on your logic)
        $store = Store::find(3);

        return view('store.biin-king-gym-store.index', compact(
            'products',
            'categoryId',
            'totalProducts',
            'store',
            'categories' // Pass categories to view
        ));
    }
}
