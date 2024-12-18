<?php

namespace App\Http\Controllers;

use App\Models\Facilities;
use App\Models\Trainer;
use App\Models\Blog;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $blog = Blog::published()->where('gymkos_id', 1)->get(); // Pastikan hanya blog yang 'publish'

        $facilities = Facilities::where('gymkos_id', 1)->get();
        $trainer = Trainer::where('gymkos_id', 1)->get();

        return view('index', compact('facilities', 'trainer', 'blog'));
    }
    public function product()
    {
        return view('product');
    }
}
