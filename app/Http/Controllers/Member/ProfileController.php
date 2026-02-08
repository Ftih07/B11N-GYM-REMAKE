<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index() // Saya ubah nama functionnya jadi index biar standar
    {
        $user = Auth::user();
        $member = $user->member;
        return view('member.profile', compact('user', 'member'));
    }

    public function update(Request $request) // Saya ubah jadi update
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
