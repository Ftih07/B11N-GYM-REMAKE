<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class HomeController extends Controller
{
    // Goal: Show landing page with latest blog posts
    public function index()
    {
        // Get 3 latest published blogs (based on created_at)
        $blog = Blog::published()
            ->latest()
            ->take(3)
            ->get();

        return view('index', compact('blog'));
    }
}
