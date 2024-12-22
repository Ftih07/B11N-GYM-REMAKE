<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Banner;

class BannerController extends Controller
{
    //
    public function index()
    {
        // Ambil data facility yang memiliki gymkos_id untuk B11N Gym
        // B11N Gym memiliki gymkos_id = 1
        $banner = Banner::where('gymkos_id', 1)->get();

        return view('index', compact('banner'));
    }
}
