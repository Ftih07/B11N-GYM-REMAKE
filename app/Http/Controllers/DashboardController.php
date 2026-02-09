<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MemberMeasurement;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // --- DASHBOARD HOME ---
    public function index()
    {
        $user = Auth::user();
        $member = $user->member; // Get linked Member data

        // Get Measurement History (if member exists)
        $history = $member ? $member->measurements()->latest()->get() : [];

        // Show latest blogs in dashboard
        $blogs = Blog::published()->latest()->take(3)->get();

        return view('member.dashboard', compact('user', 'member', 'history', 'blogs'));
    }

    // --- MEASUREMENT: STORE PROGRESS ---
    public function storeMeasurement(Request $request)
    {
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

        // Handle File Upload
        $photoPath = null;
        if ($request->hasFile('progress_photo')) {
            $photoPath = $request->file('progress_photo')->store('progress_photos', 'public');
        }

        // Save to Database
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

    // --- ATTENDANCE PAGE ---
    public function attendance()
    {
        $member = Auth::user()->member;

        // Pagination for attendance history
        $attendances = $member ? $member->attendances()->latest()->paginate(10) : [];

        return view('member.attendance', compact('attendances'));
    }

    // --- PROFILE PAGE ---
    public function profile()
    {
        $user = Auth::user();
        $member = $user->member;
        return view('member.profile', compact('user', 'member'));
    }

    // --- PROFILE: UPDATE ---
    public function updateProfile(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $member = $user->member;

        // Only update Member data (User table data like email/name is managed via Google)
        if ($member) {
            $member->update([
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            return back()->with('success', 'Profil berhasil diperbarui!');
        }

        return back()->with('error', 'Member tidak ditemukan.');
    }
}
