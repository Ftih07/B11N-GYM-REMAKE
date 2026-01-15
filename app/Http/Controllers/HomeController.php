<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class HomeController extends Controller
{
    public function index()
    {
        $blog = Blog::published()->take(3)->get();

        return view('index', compact('blog'));
    }
}
