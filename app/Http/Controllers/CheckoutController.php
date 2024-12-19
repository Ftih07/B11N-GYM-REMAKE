<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('checkout', compact('cart'));
    }
}
