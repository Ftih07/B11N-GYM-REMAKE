<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\{About, Facilities, Trainer, Blog, CategoryTraining, Equipment, Gallery, Logo, Store, TrainingProgram, Testimoni};

class KingGymController extends Controller
{
    public function index()
    {
        $blog = Blog::published()->take(3)->get();
        $facilities = Facilities::where('gymkos_id', 2)->get();
        $trainer = Trainer::where('gymkos_id', 2)->get();
        $store = Store::find(3);
        $logo = Logo::where('gymkos_id', 2)->get();
        $about = About::where('gymkos_id', 2)->get();
        $trainingprograms = TrainingProgram::where('gymkos_id', 1)->get();
        $gallery = Gallery::where('gymkos_id', 2)->get();

        $featuredEquipments = Equipment::where('gymkos_id', 2)
            ->with(['gallery']) // Eager load gallery buat ambil foto thumbnail
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        $groupedTrainingPrograms = $trainingprograms->groupBy('category_trainings_id');

        // Mengambil data kategori
        $categories = CategoryTraining::whereIn('id', $groupedTrainingPrograms->keys())->get()->keyBy('id');

        $testimonis = \App\Models\Testimoni::where('gymkos_id', 2)
            ->get()
            ->map(function ($testimoni) {
                $testimoni->rating = max(1, $testimoni->rating); // Set minimal rating ke 1
                return $testimoni;
            });

        return view('gym.king-gym.index', compact('facilities', 'trainer', 'blog', 'store', 'logo', 'about', 'groupedTrainingPrograms', 'categories', 'testimonis', 'gallery', 'featuredEquipments'));
    }
}
