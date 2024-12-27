<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Facilities;
use App\Models\Trainer;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Store;
use App\Models\Banner;
use App\Models\CategoryTraining;
use App\Models\Gallery;
use App\Models\Logo;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use App\Models\Testimoni;

class HomeController extends Controller
{
    //
    public function index()
    {
        $blog = Blog::published()->take(3)->get();
        $facilities = Facilities::where('gymkos_id', 1)->get();
        $trainer = Trainer::where('gymkos_id', 1)->get();
        $banner = Banner::where('stores_id', 3)->get();
        $logo = Logo::where('gymkos_id', 1)->get();
        $about = About::where('gymkos_id', 1)->get();
        $trainingprograms = TrainingProgram::where('gymkos_id', 1)->get();
        $gallery = Gallery::where('gymkos_id', 1)->get();

        // Mengelompokkan berdasarkan category_trainings_id
        $groupedTrainingPrograms = $trainingprograms->groupBy('category_trainings_id');

        // Mengambil data kategori
        $categories = CategoryTraining::whereIn('id', $groupedTrainingPrograms->keys())->get()->keyBy('id');

        $testimonis = \App\Models\Testimoni::where('gymkos_id', 1)
            ->get()
            ->map(function ($testimoni) {
                $testimoni->rating = max(1, $testimoni->rating); // Set minimal rating ke 1
                return $testimoni;
            });

        return view('index', compact('facilities', 'trainer', 'blog', 'banner', 'logo', 'about', 'groupedTrainingPrograms', 'categories', 'testimonis', 'gallery'));
    }


    public function product()
    {
        $stores = Store::withCount('products')->get();
        // Mendapatkan produk dengan status 'ready' yang terhubung dengan gym 'B11N Gym'
        $products = Product::where('status', 'ready')
            ->whereHas('gymkos', function ($query) {
                $query->whereNotNull('name')  // Pastikan 'name' tidak null
                    ->where('name', 'B11N Gym');
            })
            ->get();

        return view('product', compact('products'));
    }

    public function blog()
    {
        $blogs = Blog::published()->get();
        return view('blog', compact('blogs'));
    }

    public function kinggym()
    {
        $blog = Blog::published()->take(3)->get();
        $facilities = Facilities::where('gymkos_id', 2)->get();
        $trainer = Trainer::where('gymkos_id', 2)->get();
        $banner = Banner::where('stores_id', 3)->get();
        $logo = Logo::where('gymkos_id', 2)->get();
        $about = About::where('gymkos_id', 2)->get();
        $trainingprograms = TrainingProgram::where('gymkos_id', 1)->get();
        $gallery = Gallery::where('gymkos_id', 2)->get();

        $groupedTrainingPrograms = $trainingprograms->groupBy('category_trainings_id');

        // Mengambil data kategori
        $categories = CategoryTraining::whereIn('id', $groupedTrainingPrograms->keys())->get()->keyBy('id');

        $testimonis = \App\Models\Testimoni::where('gymkos_id', 2)
            ->get()
            ->map(function ($testimoni) {
                $testimoni->rating = max(1, $testimoni->rating); // Set minimal rating ke 1
                return $testimoni;
            });

        return view('kinggym', compact('facilities', 'trainer', 'blog', 'banner', 'logo', 'about', 'groupedTrainingPrograms', 'categories', 'testimonis', 'gallery'));
    }

    public function home()
    {
        $blog = Blog::published()->take(3)->get();

        return view('home', compact('blog'));
    }

    public function kost()
    {
        return view('kost');
    }
}
