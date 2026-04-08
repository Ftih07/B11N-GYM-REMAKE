<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            $transaction->code = 'TRX-' . time();
        });

        // --- TAMBAHAN BARU: EVENT SAAT TRANSAKSI DIHAPUS ---
        static::deleting(function ($transaction) {
            // 1. Hapus Item Keranjang agar tidak nyangkut (Orphan data)
            $transaction->items()->delete();

            // 2. Hapus data induk (Payment/Booking) jika ada
            if ($transaction->payable) {
                // Hapus relasi agar tidak looping (infinite loop) saat model parent menghapus kembali.
                $payable = $transaction->payable;

                // Set null dulu di database untuk memutus rantai sementara
                $transaction->update([
                    'payable_id' => null,
                    'payable_type' => null
                ]);

                // Baru hapus parent-nya
                $payable->delete();
            }
        });
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function gymkos()
    {
        return $this->belongsTo(Gymkos::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function finance()
    {
        return $this->hasOne(Finance::class);
    }
}
