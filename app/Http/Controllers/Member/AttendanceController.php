<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // UBAH BAGIAN INI: Query langsung ke tabel members
        $member = \App\Models\Member::where('email', $user->email)->first();

        // Fetch Attendance History (Paginated)
        // Logika ternary ($member ? ...) ini sudah sangat aman
        $attendances = $member ? $member->attendances()->latest()->paginate(10) : [];

        return view('member.attendance', compact('attendances', 'member'));
    }
}
