<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Logo;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Ambil semua blog yang statusnya publish, urutkan dari yang terbaru
        $blogs = Blog::published()->latest()->get();

        // Tampilkan ke view dengan satu variabel saja
        return view('blog.index', compact('blogs'));
    }

    public function show($slug) // <--- Parameter terima string $slug
    {
        // Cari blog yang slug-nya sesuai parameter & status publish
        $blog = Blog::published()
            ->where('slug', $slug)
            ->firstOrFail(); // Kalau slug ngawur, auto 404

        // Cari related blogs (kecuali blog yang sedang dibuka)
        // Kita tetap exclude pakai ID ($blog->id) karena ID lebih cepat buat query exclude
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        return view('blog.show', compact('blog', 'relatedBlogs'));
    }
}
