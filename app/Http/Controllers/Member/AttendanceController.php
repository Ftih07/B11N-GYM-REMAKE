<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $member = Auth::user()->member;
        $attendances = $member ? $member->attendances()->latest()->paginate(10) : [];

        return view('member.attendance', compact('attendances'));
    }
}