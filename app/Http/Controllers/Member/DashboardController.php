<?php

namespace App\Http\Controllers\Member;

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

        // 1. Fetch Measurement History (For Charts)
        $history = $member ? $member->measurements()->latest()->get() : [];

        // 2. Fetch Latest Blogs (For Dashboard Widget)
        $blogs = Blog::published()->latest()->take(3)->get();

        return view('member.dashboard', compact('user', 'member', 'history', 'blogs'));
    }

    public function storeMeasurement(Request $request)
    {
        // Validate Input
        $request->validate([
            'weight' => 'nullable|numeric',
            'waist_size' => 'nullable|numeric',
            'arm_size' => 'nullable|numeric',
            'thigh_size' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'progress_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Akun kamu belum terhubung dengan Member Gym.');
        }

        // Handle File Upload (Progress Photo)
        $photoPath = null;
        if ($request->hasFile('progress_photo')) {
            $photoPath = $request->file('progress_photo')->store('progress_photos', 'public');
        }

        // Save Measurement Data
        MemberMeasurement::create([
            'member_id' => $member->id,
            'weight' => $request->weight,
            'waist_size' => $request->waist_size,
            'arm_size' => $request->arm_size,
            'thigh_size' => $request->thigh_size,
            'progress_photo' => $photoPath,
            'notes' => $request->notes,
            'measured_at' => now(),
        ]);

        return back()->with('success', 'Progress berhasil disimpan!');
    }
}
