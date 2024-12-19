<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product(Request $request)
    {
        $categoryId = $request->input('category'); // Ambil ID kategori dari query string
    
        // Ambil semua produk, dengan filter kategori jika diperlukan
        $products = Product::where('status', 'ready')
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_products_id', $categoryId);
            })
            ->get();
    
        return view('product', compact('products', 'categoryId'));
    }
    



    public function show($id)
    {
        $product = Product::findOrFail($id); // Produk utama

        // Cari produk serupa berdasarkan kategori yang sama
        $relatedProducts = Product::where('category_products_id', $product->category_products_id)
            ->where('id', '!=', $product->id) // Tidak termasuk produk yang sedang dilihat
            ->limit(4) // Batasi jumlah produk serupa
            ->get();

        return view('product.show', compact('product', 'relatedProducts'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Ambil cart dari session atau buat array kosong
        $cart = session()->get('cart', []);

        // Periksa apakah produk sudah ada di cart
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "image" => $product->image,
            ];
        }

        // Simpan kembali cart ke session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function updateCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->input('quantity');
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]); // Hapus item jika kuantitas <= 0
            } else {
                session()->put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
}
