<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // 1. Redirect ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Callback dari Google
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan google_id ATAU email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if (!$user) {
                // Kalo user belum ada, Buat User Baru (Register Otomatis)
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'customer', // Default role
                    'password' => bcrypt(Str::random(16)), // Password acak (karena login pake Google)
                    'email_verified_at' => now(), // Anggap verified karena dari Google
                    'profile_picture' => $googleUser->avatar,
                ]);
            } else {
                // Kalo user udah ada tapi belum ada google_id (misal dulu daftar manual), update datanya
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'profile_picture' => $googleUser->avatar,
                        'email_verified_at' => now(),
                    ]);
                }
            }

            // --- FITUR AUTO-CONNECT MEMBER ---
            // Cek apakah email ini terdaftar di tabel Members?
            $member = Member::where('email', $user->email)->first();

            // Jika ada di tabel Member tapi belum punya user_id, sambungkan!
            if ($member && is_null($member->user_id)) {
                $member->update(['user_id' => $user->id]);
            }

            // Login User
            Auth::login($user);

            return redirect()->intended('/dashboard'); // Redirect ke halaman utama

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login Gagal: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
