<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\{About, Facilities, Trainer, Blog, Banner, CategoryTraining, Equipment, Gallery, Store, TrainingProgram, Testimoni};

class BiinGymController extends Controller
{
    public function index()
    {
        $blog = Blog::published()->take(3)->get();
        $facilities = Facilities::where('gymkos_id', 1)->get();
        $trainer = Trainer::where('gymkos_id', 1)->get();
        $store = Store::find(3);
        $about = About::where('gymkos_id', 1)->get();
        $trainingprograms = TrainingProgram::where('gymkos_id', 1)->get();
        $gallery = Gallery::where('gymkos_id', 1)->get();

        $featuredEquipments = Equipment::where('gymkos_id', 1)
            ->with(['gallery']) // Eager load gallery buat ambil foto thumbnail
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        $groupedTrainingPrograms = $trainingprograms->groupBy('category_trainings_id');
        $categories = CategoryTraining::whereIn('id', $groupedTrainingPrograms->keys())->get()->keyBy('id');

        $testimonis = Testimoni::where('gymkos_id', 1)->get()->map(function ($t) {
            $t->rating = max(1, $t->rating);
            return $t;
        });

        return view('gym.biin-gym.index', compact(
            'facilities',
            'trainer',
            'blog',
            'store',
            'about',
            'groupedTrainingPrograms',
            'categories',
            'testimonis',
            'gallery',
            'featuredEquipments'
        ));
    }
}
