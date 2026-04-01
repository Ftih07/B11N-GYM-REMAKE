<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // UBAH BAGIAN INI: Ambil langsung dari database, bukan dari relasi auth
        $member = \App\Models\Member::where('email', $user->email)->first();

        return view('member.profile', compact('user', 'member'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // UBAH BAGIAN INI JUGA
        $member = \App\Models\Member::where('email', $user->email)->first();

        // Update Member Details
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
