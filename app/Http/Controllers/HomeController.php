<?php

namespace App\Http\Controllers;

use App\Models\Facilities;
use App\Models\Trainer;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Store;
use App\Models\Banner;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $blog = Blog::published()->where('gymkos_id', 1)->get(); // Pastikan hanya blog yang 'publish'

        $facilities = Facilities::where('gymkos_id', 1)->get();
        $trainer = Trainer::where('gymkos_id', 1)->get();
        $banner = Banner::where('stores_id', 3)->get();

        return view('index', compact('facilities', 'trainer', 'blog', 'banner'));
    }

    public function product()
    {
        $stores = Store::withCount('products')->get();
        // Mendapatkan produk dengan status 'ready' yang terhubung dengan gym 'B11N Gym'
        $products = Product::where('status', 'ready')
        ->whereHas('gymkos', function ($query) {
            $query->whereNotNull('name')  // Pastikan 'name' tidak null
                  ->where('name', 'B11N Gym');
            })
            ->get();

        return view('product', compact('products'));
    }
}
