<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Gymkos;
use Illuminate\Http\Request;

class EquipmentPageController extends Controller
{
    // --- PAGE: LIST ALL EQUIPMENTS ---
    public function index(Request $request)
    {
        $gyms = Gymkos::all();

        // Start Query
        $query = Equipment::where('status', 'active')
            ->with(['gallery', 'gymkos']); // Eager loading

        // Filter by Category (if present in URL)
        if ($request->has('category') && $request->category != null) {
            $query->where('category', $request->category);
        }

        // Filter by Gym Location
        if ($request->filled('gym')) {
            $query->where('gymkos_id', $request->gym);
        }

        // Pagination & Append Query String (so filters persist on page 2, 3...)
        $equipments = $query->latest()->paginate(9)->withQueryString();

        return view('gym.equipments.index', compact('equipments', 'gyms'));
    }

    // --- PAGE: EQUIPMENT DETAIL ---
    public function show($slug)
    {
        // 1. Find equipment by SLUG (SEO Friendly)
        $equipment = Equipment::with(['gallery', 'gymkos'])
            ->where('slug', $slug)
            ->firstOrFail(); // Returns 404 if not found

        // 2. Logic: Related Equipments (Exclude current item)
        $relatedEquipments = Equipment::where('status', 'active')
            ->where('id', '!=', $equipment->id)
            ->inRandomOrder() // Shuffle results
            ->take(3)
            ->get();

        return view('gym.equipments.show', compact('equipment', 'relatedEquipments'));
    }
}
