<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Page: List All Blogs
    public function index()
    {
        // Fetch published blogs, newest first
        $blogs = Blog::published()->latest()->get();

        return view('blog.index', compact('blogs'));
    }

    // Page: Detail Blog
    public function show($slug)
    {
        // 1. Find blog by Slug (returns 404 if not found)
        $blog = Blog::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // 2. Fetch Related Blogs
        // Logic: Get 3 latest blogs, EXCLUDING the current one
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        return view('blog.show', compact('blog', 'relatedBlogs'));
    }
}
