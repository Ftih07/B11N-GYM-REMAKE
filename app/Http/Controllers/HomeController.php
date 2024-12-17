<?php

namespace App\Http\Controllers;

use App\Models\Facilities;
use App\Models\Trainer;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $facilities = Facilities::where('gymkos_id', 1)->get();
        $trainer = Trainer::where('gymkos_id', 1)->get();

        return view('index', compact('facilities', 'trainer'));
    }
    public function product()
    {
        return view('product');
    }
}
