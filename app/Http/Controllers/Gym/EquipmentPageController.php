<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\Equipment;

class EquipmentPageController extends Controller
{
    // Halaman List Semua Alat (Global)
    public function index()
    {
        $equipments = Equipment::where('status', 'active') // Hapus where gymkos_id
            ->with(['gallery', 'gymkos']) // Load relasi gymkos supaya bisa nampilin nama lokasinya di card
            ->latest() // Urutkan dari yang terbaru
            ->paginate(9);

        return view('gym.equipments.index', compact('equipments'));
    }

    // Halaman Detail
    public function show($id)
    {
        // Ambil data alat
        $equipment = Equipment::with(['gallery', 'gymkos'])->findOrFail($id);

        // Logic Related: Ambil alat lain TAPI yang satu lokasi (Gym ID-nya sama dengan alat ini)
        $relatedEquipments = Equipment::where('status', 'active')
            ->where('id', '!=', $id) // Jangan tampilkan alat yang sedang dibuka
            ->inRandomOrder() // Acak biar ga itu-itu aja yang muncul
            ->take(3)
            ->get();

        return view('gym.equipments.show', compact('equipment', 'relatedEquipments'));
    }
}
