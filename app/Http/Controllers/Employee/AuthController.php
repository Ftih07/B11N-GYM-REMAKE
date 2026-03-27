<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('employee.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Proteksi tambahan: Kalau bukan karyawan, tendang keluar
            if (Auth::user()->role !== 'employee') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akses ditolak. Anda bukan karyawan B1NG Empire.']);
            }

            return redirect()->intended(route('employee.dashboard'));
        }

        // Kalau gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect kembali ke halaman Home atau Login Karyawan
        return redirect()->route('employee.login');
    }
}
