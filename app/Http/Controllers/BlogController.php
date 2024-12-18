<?php

namespace App\Http\Controllers;

use App\Models\Blog;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    //
    public function index()
    {
        $blogs = Blog::published()->where('gymkos_id', 1)->get();
        return view('frontend.blogs', compact('blogs'));
    }
}
