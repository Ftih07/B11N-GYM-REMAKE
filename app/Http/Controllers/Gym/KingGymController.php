<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\{About, Facilities, Trainer, Blog, CategoryTraining, Equipment, Gallery, Store, TrainingProgram, Testimoni};

class KingGymController extends Controller
{
    public function index()
    {
        // 1. Fetch Latest Blogs (Limit 3)
        $blog = Blog::published()->take(3)->get();

        // 2. Fetch Gym-Specific Data (ID = 2 for King Gym)
        $facilities = Facilities::where('gymkos_id', 2)->get();
        $trainer = Trainer::where('gymkos_id', 2)->get();
        $about = About::where('gymkos_id', 2)->get();
        $gallery = Gallery::where('gymkos_id', 2)->get();

        // Hardcoded Store ID (Example: 3)
        $store = Store::find(3);

        // 3. Featured Equipments (Latest 3 Active Items)
        $featuredEquipments = Equipment::where('gymkos_id', 2)
            ->with(['gallery']) // Preload images for efficiency
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        // 4. Training Programs (Grouped by Category)
        $trainingprograms = TrainingProgram::where('gymkos_id', 2)->get();
        $groupedTrainingPrograms = $trainingprograms->groupBy('category_trainings_id');
        $categories = CategoryTraining::whereIn('id', $groupedTrainingPrograms->keys())->get()->keyBy('id');

        // 5. Testimonials (Ensure minimum rating of 1)
        $testimonis = \App\Models\Testimoni::where('gymkos_id', 2)
            ->get()
            ->map(function ($testimoni) {
                $testimoni->rating = max(1, $testimoni->rating); // Set minimal rating ke 1
                return $testimoni;
            });

        // 6. Return View with Data
        return view('gym.king-gym.index', compact('facilities', 'trainer', 'blog', 'store', 'about', 'groupedTrainingPrograms', 'categories', 'testimonis', 'gallery', 'featuredEquipments'));
    }
}
