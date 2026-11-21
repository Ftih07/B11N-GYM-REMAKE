<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    // Metode pembayaran tamu (non-member). Key sama dengan yang dipakai di data Member
    // (cash / qr / cash_qr). Dipakai di form absensi (admin & mandiri) dan Rekap Absensi.
    public const PAYMENT_METHODS = [
        'qr' => 'QRIS',
        'cash' => 'Tunai',
        'cash_qr' => 'Split (Tunai + QRIS)',
    ];

    // Status verifikasi absensi. Absen yang ditolak admin langsung dihapus, jadi tidak ada status "rejected".
    public const STATUS_VERIFIED = 'verified'; // dibuat admin / sudah dicek
    public const STATUS_PENDING = 'pending';   // absen mandiri (QR), menunggu dicek admin

    public const STATUS_LABELS = [
        self::STATUS_VERIFIED => 'Terverifikasi',
        self::STATUS_PENDING => 'Perlu Dicek',
    ];

    protected $guarded = [];

    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // Cabang tempat absen dilakukan (attendances.gymkos_id)
    public function gymkos(): BelongsTo
    {
        return $this->belongsTo(Gymkos::class);
    }
}
