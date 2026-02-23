<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    // Function Goal: Show single product detail & related items

    // Ubah parameter dari $id menjadi langsung Model-nya (Product $product)
    public function show(Product $product)
    {
        // Get products from SAME category, but EXCLUDE current product
        $relatedProducts = Product::where('category_products_id', $product->category_products_id)
            ->where('id', '!=', $product->id) // Nggak perlu diubah, tetap pakai $product->id
            ->where('status', 'ready')
            ->inRandomOrder(crc32(session()->getId()))
            ->limit(4)
            ->get();

        return view('store.product.show', compact('product', 'relatedProducts'));
    }
}
