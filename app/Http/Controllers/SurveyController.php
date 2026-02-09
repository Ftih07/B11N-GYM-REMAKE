<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    // Page: Show Survey Form
    public function index()
    {
        return view('survey.form');
    }

    // Logic: Store Survey Data
    public function store(Request $request)
    {
        // 1. Validation Logic
        $validated = $request->validate([
            'is_membership' => 'boolean',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',

            // Conditional Validation: Only required if 'is_membership' is checked
            'member_duration' => 'required_if:is_membership,1',
            'renewal_chance' => 'required_if:is_membership,1|integer|min:1|max:5',

            'fitness_goal' => 'required|string',
            'rating_equipment' => 'required|integer|min:1|max:5',
            'rating_cleanliness' => 'required|integer|min:1|max:5',
            'nps_score' => 'required|integer|min:1|max:10',
            'feedback' => 'nullable|string',
            'promo_interest' => 'required|string',
        ]);

        // 2. Save Data
        Survey::create($validated);

        return redirect()->back()->with('success', 'Terima kasih atas masukan Anda!');
    }
}
