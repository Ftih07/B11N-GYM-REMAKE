<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;


class TrainerController extends Controller
{
    //
    public function index()
    {
        // Ambil data facility yang memiliki gymkos_id untuk B11N Gym
        // B11N Gym memiliki gymkos_id = 1
        $trainer = Trainer::where('gymkos_id', 1)->get();

        return view('index', compact('trainer'));
    }
}
