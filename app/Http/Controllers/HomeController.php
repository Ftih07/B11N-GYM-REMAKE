<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class HomeController extends Controller
{
    // Goal: Show landing page with latest blog posts
    public function index()
    {
        // Logic: Get 3 latest published blogs
        $blog = Blog::published()->take(3)->get();

        return view('index', compact('blog'));
    }
}
