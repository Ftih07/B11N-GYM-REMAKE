<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberMeasurement;
use App\Models\Transaction;
use App\Support\PrivateMedia;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Menyajikan berkas di disk privat setelah cek hak akses (SEC-10, PRV-02). */
class PrivateMediaController extends Controller
{
    // Middleware `admin`
    public function memberPhoto(Member $member): Response
    {
        return PrivateMedia::response($member->picture);
    }

    // Middleware `admin`
    public function transactionProof(Transaction $transaction): Response
    {
        return PrivateMedia::response($transaction->proof_of_payment);
    }

    // Middleware `auth`: admin, atau member pemilik data progres
    public function progressPhoto(Request $request, MemberMeasurement $measurement): Response
    {
        $user = $request->user();
        $member = $measurement->member;

        $allowed = in_array($user->role, ['admin', 'super_admin'], true)
            || ($member && ($member->user_id === $user->id || ($member->email && $member->email === $user->email)));

        abort_unless($allowed, 403);

        return PrivateMedia::response($measurement->progress_photo);
    }
}
