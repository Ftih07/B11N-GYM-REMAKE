<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    // Function Goal: Show single product detail & related items
    public function show($id)
    {
        // 1. Get Main Product
        $product = Product::findOrFail($id);

        // 2. Logic: Related Products
        // Get products from SAME category, but EXCLUDE current product
        $relatedProducts = Product::where('category_products_id', $product->category_products_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'ready')
            ->inRandomOrder(crc32(session()->getId()))
            ->limit(4)
            ->get();

        return view('store.product.show', compact('product', 'relatedProducts'));
    }
}
