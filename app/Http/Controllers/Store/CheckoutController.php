<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    //
    public function index()
    {
        return view('checkout');
    }

    public function checkout()
    {
        // Ambil data cart dari session
        $cart = session()->get('cart', []); // Ambil data atau array kosong jika belum ada

        // Kirimkan data ke view
        return view('store.checkout.index', compact('cart'));
    }
}
