<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;

/**
 * Login member lewat Google / Facebook.
 *
 * Login sosial HANYA untuk akun role `customer` (SEC-05). Akun admin/super_admin wajib masuk
 * lewat /admin dengan email + password, supaya pemilik akun Google/Facebook dengan email yang
 * sama tidak otomatis menjadi admin.
 */
class AuthController extends Controller
{
    private const PROVIDERS = [
        'google' => ['column' => 'google_id', 'label' => 'Google'],
        'facebook' => ['column' => 'facebook_id', 'label' => 'Facebook'],
    ];

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        return $this->handleCallback($request, 'google');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(Request $request)
    {
        return $this->handleCallback($request, 'facebook');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function handleCallback(Request $request, string $provider): RedirectResponse
    {
        $label = self::PROVIDERS[$provider]['label'];

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            // Mis. pengguna menekan "Batal" di halaman Google/Facebook, atau state kedaluwarsa
            Log::warning("Login {$label} gagal", ['exception' => $e]);

            return $this->failed("Login {$label} gagal. Silakan coba lagi.");
        }

        $email = Str::lower(trim((string) $socialUser->getEmail()));

        if ($email === '') {
            return $this->failed("Akun {$label} Anda tidak membagikan alamat email. Izinkan akses email lalu coba lagi.");
        }

        // Google menyertakan status verifikasi email; tolak bila jelas belum terverifikasi.
        if ($provider === 'google' && ($socialUser->user['email_verified'] ?? true) === false) {
            return $this->failed('Email akun Google Anda belum terverifikasi.');
        }

        $user = $this->findOrCreateUser($provider, $socialUser, $email);

        if ($user->role !== 'customer') {
            Log::warning('Login sosial ditolak untuk akun non-customer', [
                'provider' => $provider,
                'user_id' => $user->id,
                'role' => $user->role,
            ]);

            return $this->failed('Akun ini adalah akun admin. Silakan masuk lewat halaman admin dengan email dan password.');
        }

        // Hubungkan otomatis ke data member (diinput admin) yang emailnya sama (members.email unik)
        Member::where('email', $user->email)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    private function findOrCreateUser(string $provider, SocialUser $socialUser, string $email): User
    {
        $column = self::PROVIDERS[$provider]['column'];

        $user = User::where($column, $socialUser->getId())->first()
            ?? User::where('email', $email)->first();

        if (! $user) {
            return User::create([
                'name' => $socialUser->getName() ?: Str::before($email, '@'),
                'email' => $email,
                $column => $socialUser->getId(),
                'role' => 'customer',
                'password' => Str::random(40), // di-hash oleh cast `hashed`; login tetap lewat sosial
                'email_verified_at' => now(),
                'profile_picture' => $socialUser->getAvatar(),
            ]);
        }

        // Akun non-customer tidak diubah sama sekali (akan ditolak oleh pemanggil)
        if ($user->role === 'customer' && ! $user->{$column}) {
            $user->update([
                $column => $socialUser->getId(),
                'profile_picture' => $user->profile_picture ?: $socialUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        }

        return $user;
    }

    private function failed(string $message): RedirectResponse
    {
        return redirect()->route('login')->with('error', $message);
    }
}
