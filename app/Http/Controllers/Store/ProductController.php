<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);

        $relatedProducts = Product::where('category_products_id', $product->category_products_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('store.product.show', compact('product', 'relatedProducts'));
    }
}
