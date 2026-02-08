<?php

namespace App\Http\Controllers\Member; // Perhatikan Namespacenya berubah!

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\MemberMeasurement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $member = $user->member;
        $history = $member ? $member->measurements()->latest()->get() : [];
        $blogs = Blog::published()->latest()->take(3)->get();

        // View arahkan ke folder member
        return view('member.dashboard', compact('user', 'member', 'history', 'blogs'));
    }

    public function storeMeasurement(Request $request)
    {
        $request->validate([
            'weight' => 'nullable|numeric',
            'waist_size' => 'nullable|numeric',
            'arm_size' => 'nullable|numeric',
            'thigh_size' => 'nullable|numeric', // Validasi baru
            'notes' => 'nullable|string',
            'progress_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Akun kamu belum terhubung dengan Member Gym.');
        }

        // Handle File Upload
        $photoPath = null;
        if ($request->hasFile('progress_photo')) {
            // Simpan ke folder 'storage/app/public/progress_photos'
            $photoPath = $request->file('progress_photo')->store('progress_photos', 'public');
        }

        MemberMeasurement::create([
            'member_id' => $member->id,
            'weight' => $request->weight,
            'waist_size' => $request->waist_size,
            'arm_size' => $request->arm_size,
            'thigh_size' => $request->thigh_size, // Input baru
            'progress_photo' => $photoPath,        // Path foto
            'notes' => $request->notes,
            'measured_at' => now(),
        ]);

        return back()->with('success', 'Progress berhasil disimpan!');
    }
}
