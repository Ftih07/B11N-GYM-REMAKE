<?php

namespace App\Http\Controllers\Kost;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\Testimoni;

class KostController extends Controller
{
    // Booking kamar dilakukan lewat WhatsApp (fitur booking online dihapus 2026-11-28).
    public function index()
    {
        // CMS data khusus Kost (gymkos ID 3)
        $gallery = Gallery::where('gymkos_id', 3)->get();
        $blog = Blog::published()->take(3)->get();
        $testimonis = Testimoni::where('gymkos_id', 3)->get()->map(function ($t) {
            $t->rating = max(1, $t->rating);

            return $t;
        });

        return view('kost.istana-merdeka-3.index', compact(
            'blog',
            'testimonis',
            'gallery'
        ));
    }
}
