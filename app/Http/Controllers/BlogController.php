<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Logo;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $logo = Logo::where('gymkos_id', 1)->get();
        // Ambil blog berdasarkan foreign key
        $b11nBlogs = Blog::published()->where('gymkos_id', 1)->get();
        $k1ngBlogs = Blog::published()->where('gymkos_id', 2)->get();

        // Tampilkan ke view
        return view('blog.index', compact('b11nBlogs', 'k1ngBlogs', 'logo'));
    }

    public function show($slug) // <--- Parameter terima string $slug
    {
        $logo = Logo::where('gymkos_id', 1)->get();

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

        return view('blog.show', compact('blog', 'relatedBlogs', 'logo'));
    }
}
