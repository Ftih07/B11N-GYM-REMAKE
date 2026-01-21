<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Gymkos;
use Illuminate\Http\Request;

class EquipmentPageController extends Controller
{
    // Halaman List Semua Alat (Global)
    public function index(Request $request)
    {
        $gyms = Gymkos::all();

        $query = Equipment::where('status', 'active')
            ->with(['gallery', 'gymkos']);

        if ($request->has('category') && $request->category != null) {
            $query->where('category', $request->category);
        }

        if ($request->filled('gym')) {
            $query->where('gymkos_id', $request->gym);
        }

        $equipments = $query->latest()->paginate(9)->withQueryString();

        return view('gym.equipments.index', compact('equipments', 'gyms'));
    }

    // Halaman Detail
    public function show($slug) // <--- Parameter terima $slug (bukan $id lagi)
    {
        // 1. Cari alat berdasarkan slug
        $equipment = Equipment::with(['gallery', 'gymkos'])
            ->where('slug', $slug)
            ->firstOrFail(); // Kalau slug salah -> 404

        // 2. Logic Related (Tetap pakai ID buat exclude, ini lebih aman & cepat)
        $relatedEquipments = Equipment::where('status', 'active')
            ->where('id', '!=', $equipment->id) // Exclude ID alat yang sedang dibuka
            // ->where('gymkos_id', $equipment->gymkos_id) // Opsional: kalau mau related by lokasi yg sama
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('gym.equipments.show', compact('equipment', 'relatedEquipments'));
    }
}
