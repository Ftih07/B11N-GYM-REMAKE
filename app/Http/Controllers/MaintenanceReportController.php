<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Gymkos;
use App\Models\MaintenanceReport;
use Illuminate\Http\Request;

class MaintenanceReportController extends Controller
{
    // Page: Show Maintenance Form
    public function create()
    {
        // Send Gym list so user can select location first
        $gyms = Gymkos::all();
        return view('maintenance.create', compact('gyms'));
    }

    // API Endpoint: Get equipments by Gym ID
    // Used by Javascript/AJAX in the frontend form
    public function getEquipments($gymId)
    {
        $equipments = Equipment::where('gymkos_id', $gymId)
            ->where('status', '!=', 'broken') // Don't show already broken items
            ->with(['gallery' => function ($query) {
                $query->orderBy('order_index', 'asc')->limit(1); // Get thumbnail
            }])
            ->get()
            ->map(function ($item) {
                // Determine Image URL
                $firstPhoto = $item->gallery->first();
                $imageUrl = $firstPhoto
                    ? asset('storage/' . $firstPhoto->file_path)
                    : 'https://placehold.co/400x300?text=No+Image';

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'description' => \Illuminate\Support\Str::limit($item->description, 50),
                    'image_url' => $imageUrl
                ];
            });

        return response()->json($equipments);
    }

    // Logic: Store Report
    public function store(Request $request)
    {
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

        // Find Gym ID based on Equipment
        $equipment = Equipment::find($request->equipment_id);

        MaintenanceReport::create([
            'gymkos_id' => $equipment->gymkos_id, // Auto-fill Gym ID
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
