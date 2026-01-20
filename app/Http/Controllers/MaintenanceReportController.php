<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Gymkos; // Pastikan model Gymkos di-import
use App\Models\MaintenanceReport;
use Illuminate\Http\Request;

class MaintenanceReportController extends Controller
{
    public function create()
    {
        // Kita kirim daftar Gym, bukan Equipment
        $gyms = Gymkos::all();
        return view('maintenance.create', compact('gyms'));
    }

    // Method API untuk mengambil alat berdasarkan Gym ID
    public function getEquipments($gymId)
    {
        $equipments = Equipment::where('gymkos_id', $gymId)
            ->where('status', '!=', 'broken')
            ->with(['gallery' => function ($query) {
                $query->orderBy('order_index', 'asc')->limit(1); // Ambil 1 foto pertama saja
            }])
            ->get()
            ->map(function ($item) {
                // Cek apakah ada foto di gallery, jika tidak pakai placeholder
                $firstPhoto = $item->gallery->first();
                $imageUrl = $firstPhoto
                    ? asset('storage/' . $firstPhoto->file_path)
                    : 'https://placehold.co/400x300?text=No+Image'; // Gambar default kalau kosong

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'description' => \Illuminate\Support\Str::limit($item->description, 50), // Deskripsi pendek
                    'image_url' => $imageUrl
                ];
            });

        return response()->json($equipments);
    }

    public function store(Request $request)
    {
        // ... (Kode store kamu sama seperti sebelumnya, tidak perlu ubah)
        // Cuma pastikan validationnya tetap berjalan
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'equipment_id' => 'required|exists:equipments,id',
            'severity' => 'required|in:low,medium,high,critical',
            'description' => 'required|string',
            'evidence_photo' => 'nullable|image|max:5120',
        ]);

        $photoPath = null;
        if ($request->hasFile('evidence_photo')) {
            $photoPath = $request->file('evidence_photo')->store('maintenance-evidence', 'public');
        }

        $equipment = Equipment::find($request->equipment_id);

        MaintenanceReport::create([
            'gymkos_id' => $equipment->gymkos_id,
            'equipment_id' => $validated['equipment_id'],
            'reporter_name' => $validated['reporter_name'],
            'severity' => $validated['severity'],
            'description' => $validated['description'],
            'evidence_photo' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim. Terima kasih!');
    }
}
