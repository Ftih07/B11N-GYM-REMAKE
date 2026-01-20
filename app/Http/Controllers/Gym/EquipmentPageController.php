<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request; 

class EquipmentPageController extends Controller
{
    // Halaman List Semua Alat (Global)
    public function index(Request $request)
    {
        // 1. Mulai Query dasar
        $query = Equipment::where('status', 'active')
            ->with(['gallery', 'gymkos']);

        // 2. Cek apakah ada filter category di URL?
        if ($request->has('category') && $request->category != null) {
            $query->where('category', $request->category);
        }

        // 3. Eksekusi query dengan pagination
        // withQueryString() penting biar pas pindah halaman (page 2), filternya ga ilang
        $equipments = $query->latest()->paginate(9)->withQueryString();

        return view('gym.equipments.index', compact('equipments'));
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
