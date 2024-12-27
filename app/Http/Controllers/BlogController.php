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

    public function show($id)
    {
        $logo = Logo::where('gymkos_id', 1)->get();
        $blog = Blog::published()->findOrFail($id);
        $relatedBlogs = Blog::published()->where('id', '!=', $id)->latest()->take(3)->get();

        return view('blog.show', compact('blog', 'relatedBlogs', 'logo'));
    }
}
