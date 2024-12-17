<?php

namespace App\Http\Controllers;

use App\Models\Facilities;
use Illuminate\Http\Request;

class FacilitiesController extends Controller
{
    //
    public function index()
    {
        // Ambil data facility yang memiliki gymkos_id untuk B11N Gym
        // B11N Gym memiliki gymkos_id = 1
        $facilities = Facilities::where('gymkos_id', 1)->get();

        return view('index', compact('facilities'));
    }

    public function kinggym()
    {
        // Ambil data facility yang memiliki gymkos_id untuk K1NG Gym
        // K1NG Gym memiliki gymkos_id = 2
        $facilities = Facilities::where('gymkos_id', 2)->get();

        return view('kinggym', compact('facilities'));
    }
}
