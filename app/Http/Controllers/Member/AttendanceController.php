<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        // Get Authenticated Member
        $member = Auth::user()->member;

        // Fetch Attendance History (Paginated)
        $attendances = $member ? $member->attendances()->latest()->paginate(10) : [];

        return view('member.attendance', compact('attendances', 'member'));
    }
}
