<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MemberMeasurement;
use Illuminate\Support\Facades\Storage; // Tambahkan ini buat handle file

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek apakah user ini terhubung ke member gym
        $member = $user->member;

        // Ambil history measurements (jika member ada)
        $history = $member ? $member->measurements()->latest()->get() : [];
        $blogs = Blog::published()->latest()->take(3)->get();

        return view('dashboard', compact('user', 'member', 'history', 'blogs'));
    }

    // ... namespace dan use statements yang sudah ada
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

    // Menampilkan halaman Absensi
    public function attendance()
    {
        $member = Auth::user()->member;

        // Jika member belum connect, return array kosong
        $attendances = $member ? $member->attendances()->latest()->paginate(10) : [];

        return view('attendance', compact('attendances'));
    }

    // Menampilkan halaman Profile
    public function profile()
    {
        $user = Auth::user();
        $member = $user->member;
        return view('profile', compact('user', 'member'));
    }

    // Update Profile (Simpan Perubahan)
    public function updateProfile(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $member = $user->member;

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
