<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipRenewal extends Model
{
    public const PAYMENT_METHODS = [
        'cash' => 'Tunai',
        'qris' => 'QRIS',
        'transfer' => 'Transfer',
        'cash_qris' => 'Split (Tunai + QRIS)',
        'other' => 'Lainnya',
    ];

    public const SOURCES = [
        'admin' => 'Admin',
        'online' => 'Online',
        'manual' => 'Input Manual',
    ];

    // Padanan ke kolom lama members.payment_method (cash|qr|cash_qr) agar badge di tabel member tetap benar.
    public const MEMBER_PAYMENT_MAP = [
        'cash' => 'cash',
        'qris' => 'qr',
        'transfer' => 'qr',
        'cash_qris' => 'cash_qr',
    ];

    protected $fillable = [
        'member_id', 'gymkos_id', 'paid_at', 'period_month', 'months', 'valid_until',
        'payment_method', 'source', 'payment_id', 'recorded_by', 'notes',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'period_month' => 'date',
        'valid_until' => 'date',
        'months' => 'integer',
    ];

    protected static function booted()
    {
        // Bulan selalu disimpan sebagai tanggal 1; jumlah bulan minimal 1.
        static::saving(function (self $r) {
            $r->period_month = Carbon::parse($r->period_month)->startOfMonth()->toDateString();
            $r->months = max(1, (int) $r->months);

            // Baris yang dibuat lewat relation manager belum punya cabang: ambil dari member.
            if (! $r->gymkos_id && $r->member_id) {
                $r->gymkos_id = Member::whereKey($r->member_id)->value('gymkos_id'); // value() tidak memicu Member::retrieved
            }
        });
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function gymkos(): BelongsTo
    {
        return $this->belongsTo(Gymkos::class, 'gymkos_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /** Daftar bulan (Y-m) yang tercakup oleh pembayaran ini. */
    public function coveredMonths(): array
    {
        return self::expandMonths($this->period_month, $this->months);
    }

    /** @return string[] mis. ['2026-11','2026-12','2027-01'] */
    public static function expandMonths($start, int $months): array
    {
        $c = Carbon::parse($start)->startOfMonth();
        $out = [];
        for ($i = 0; $i < max(1, $months); $i++) {
            $out[] = $c->copy()->addMonthsNoOverflow($i)->format('Y-m');
        }

        return $out;
    }
}
