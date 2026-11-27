<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $guarded = [];

    /**
     * Kode unik untuk kolom `transactions.code` (UNIQUE), mis. TRX-261128-K7Q2M9XD.
     * Dulu `TRX-{time()}` sehingga dua transaksi di detik yang sama bentrok (INT-04).
     * Kode lama di database tidak diubah.
     */
    public static function generateCode(string $prefix = 'TRX'): string
    {
        do {
            $code = $prefix.'-'.now()->format('ymd').'-'.Str::upper(Str::random(8));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    // Kolom payable_type/payable_id kini hanya catatan asal transaksi lama dari fitur pembayaran
    // membership online & booking kost (dihapus 2026-11-28); tidak ada lagi relasi morphTo.
    protected static function booted()
    {
        static::creating(function ($transaction) {
            // Hanya isi bila pemanggil belum memberi kode sendiri
            if (blank($transaction->code)) {
                $transaction->code = static::generateCode();
            }
        });

        static::deleting(function ($transaction) {
            // Hapus item keranjang agar tidak menjadi data yatim
            $transaction->items()->delete();
        });
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class)->withTrashed();
    }

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function finance()
    {
        return $this->hasOne(Finance::class);
    }
}
