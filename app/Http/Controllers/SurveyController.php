<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        return view('survey.form');
    }

    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'is_membership' => 'boolean',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',

            // Wajib diisi HANYA JIKA is_membership dicentang (bernilai 1/true)
            'member_duration' => 'required_if:is_membership,1',
            'renewal_chance' => 'required_if:is_membership,1|integer|min:1|max:5',

            'fitness_goal' => 'required|string',
            'rating_equipment' => 'required|integer|min:1|max:5',
            'rating_cleanliness' => 'required|integer|min:1|max:5',
            'nps_score' => 'required|integer|min:1|max:10',
            'feedback' => 'nullable|string',
            'promo_interest' => 'required|string',
        ]);

        // Simpan Data
        Survey::create($validated);

        return redirect()->back()->with('success', 'Terima kasih atas masukan Anda!');
    }
}
