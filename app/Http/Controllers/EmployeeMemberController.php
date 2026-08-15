<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeMemberController extends Controller
{
    // 1. Tampilan Cetak QR Code Global
    public function generateQr()
    {
        $token = env('FRONTDESK_QR_TOKEN', 'b1ng-empire-secret-frontdesk-2026');
        $accessUrl = route('employee.members.index', ['token' => $token]);

        return view('employee.qr-display', compact('accessUrl'));
    }

    // 2. List & Search Member (Global untuk Semua Member)
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Member::query();

        if ($search) {
            $words = array_filter(explode(' ', trim($search)));
            $query->where(function ($subQuery) use ($words) {
                foreach ($words as $word) {
                    $subQuery->where(function ($w) use ($word) {
                        $w->where('member_code', 'like', "%{$word}%")
                            ->orWhere('name', 'like', "%{$word}%")
                            ->orWhere('phone', 'like', "%{$word}%");
                    });
                }
            });
        }

        $members = $query->latest('updated_at')->paginate(15)->withQueryString();

        return view('employee.members.index', compact('members', 'search'));
    }

    // 3. Form Tambah Member
    public function create()
    {
        return view('employee.members.create');
    }

    // 4. Simpan Member Baru
    public function store(Request $request)
    {
        $request->validate([
            'gymkos_id' => 'required|in:1,2',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'membership_months' => 'required|integer|min:1',
        ]);

        $gymkosId = (int) $request->gymkos_id;

        // 1. Tentukan Prefix Awalan berdasarkan Cabang Gym
        // ID 1 = B11N Gym (Prefix: B-), ID 2 = K1NG Gym (Prefix: K-)
        $prefix = ($gymkosId === 1) ? 'B-' : 'K-';

        // 2. Cari member terakhir dari cabang tersebut yang punya prefix yang sesuai
        $lastMember = Member::where('gymkos_id', $gymkosId)
            ->where('member_code', 'like', "{$prefix}%")
            ->orderByRaw('CAST(SUBSTRING(member_code, 3) AS UNSIGNED) DESC')
            ->first();

        // 3. Ekstrak angka dan tambahkan +1
        if ($lastMember && ! empty($lastMember->member_code)) {
            // Ambil angka setelah "B-" atau "K-" (misal "02252" -> 2252)
            $lastNumber = (int) substr($lastMember->member_code, 2);
            $nextNumber = $lastNumber + 1;
        } else {
            // Default awal jika belum ada data sama sekali
            $nextNumber = 1;
        }

        // 4. Format menjadi 5 digit (misal: B-02253 atau K-01751)
        $memberCode = $prefix.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // 5. Hitung tanggal join & end date
        $joinDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->addMonths((int) $request->membership_months)->format('Y-m-d');

        // 6. Simpan Member ke Database
        Member::create([
            'gymkos_id' => $gymkosId,
            'member_code' => $memberCode,
            'name' => $request->name,
            'phone' => $request->phone,
            'join_date' => $joinDate,
            'membership_end_date' => $endDate,
            'status' => 'active',
        ]);

        return redirect()->route('employee.members.index')
            ->with('success', "Member {$request->name} berhasil didaftarkan dengan ID: {$memberCode}!");
    }

    // 5. Form Edit
    public function edit(Member $member)
    {
        return view('employee.members.edit', compact('member'));
    }

    // 6. Update Member
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'membership_end_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($request->only('name', 'phone', 'membership_end_date', 'status'));

        return redirect()->route('employee.members.index')->with('success', "Data {$member->name} berhasil diperbarui!");
    }

    // 7. Perpanjang Cepat (+1 Bulan)
    public function extend(Member $member)
    {
        $currentEnd = $member->membership_end_date && Carbon::parse($member->membership_end_date)->isFuture()
            ? Carbon::parse($member->membership_end_date)
            : Carbon::now();

        $member->update([
            'membership_end_date' => $currentEnd->addMonth()->format('Y-m-d'),
            'status' => 'active',
        ]);

        return back()->with('success', "Membership {$member->name} diperpanjang 1 Bulan!");
    }
}
