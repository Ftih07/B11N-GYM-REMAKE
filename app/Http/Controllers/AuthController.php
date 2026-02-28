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
    // 1. Redirect user to Google Login Page
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handle Callback from Google
    public function handleGoogleCallback()
    {
        try {
            // Get user data from Google
            $googleUser = Socialite::driver('google')->user();

            // Find existing user by Google ID OR Email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if (!$user) {
                // Scenario A: User doesn't exist -> Create New User
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'customer', // Default role
                    'password' => bcrypt(Str::random(16)), // Random password (since they use Google)
                    'email_verified_at' => now(), // Auto verify
                    'profile_picture' => $googleUser->avatar,
                ]);
            } else {
                // Scenario B: User exists -> Update Google ID/Avatar if missing
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'profile_picture' => $googleUser->avatar,
                        'email_verified_at' => now(),
                    ]);
                }
            }

            // --- AUTO-CONNECT MEMBER LOGIC ---
            // Check if this email exists in the 'members' table (from admin input)
            $member = Member::where('email', $user->email)->first();

            // If found in Members table but not linked to a User account yet -> Link them!
            if ($member && is_null($member->user_id)) {
                $member->update(['user_id' => $user->id]);
            }

            // Log the user in
            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login Gagal: ' . $e->getMessage());
        }
    }

    // 3. Redirect user to Facebook Login Page
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // 4. Handle Callback from Facebook
    public function handleFacebookCallback()
    {
        try {
            // Get user data from Facebook
            $facebookUser = Socialite::driver('facebook')->user();

            // Find existing user by Facebook ID OR Email
            $user = User::where('facebook_id', $facebookUser->id)
                ->orWhere('email', $facebookUser->email)
                ->first();

            if (!$user) {
                // Scenario A: User doesn't exist -> Create New User
                $user = User::create([
                    'name' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'facebook_id' => $facebookUser->id,
                    'role' => 'customer', // Default role
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'profile_picture' => $facebookUser->avatar,
                ]);
            } else {
                // Scenario B: User exists -> Update Facebook ID/Avatar if missing
                if (!$user->facebook_id) {
                    $user->update([
                        'facebook_id' => $facebookUser->id,
                        // Boleh di-update avatarnya atau nggak, opsional
                        'profile_picture' => $user->profile_picture ?? $facebookUser->avatar,
                    ]);
                }
            }

            // --- AUTO-CONNECT MEMBER LOGIC ---
            $member = Member::where('email', $user->email)->first();

            if ($member && is_null($member->user_id)) {
                $member->update(['user_id' => $user->id]);
            }

            // Log the user in
            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login Facebook Gagal: ' . $e->getMessage());
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
