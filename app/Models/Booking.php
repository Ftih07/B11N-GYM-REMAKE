<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking_kost';

    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'date', 'end_date', 'room_type', 'room_number', 'price', 'payment', 'payment_proof', 'status'];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
    ];

    // Relasi ke Transaction    
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'payable');
    }

    // --- LOGIC OTOMATIS ---
    protected static function booted()
    {
        static::created(function ($booking) {

            // 1. Buat Header Transaction
            $transaction = $booking->transaction()->create([
                'code'           => 'TRX-KOST-' . time(),
                'total_amount'   => $booking->price,
                'status'         => 'pending',
                'payment_method' => $booking->payment,
                'gymkos_id'      => 3,
                'customer_name'  => $booking->name,
            ]);

            // 2. Cari/Buat Produk (Auto Create Product)
            $product = \App\Models\Product::firstOrCreate(
                ['name' => 'Sewa Kost - ' . $booking->room_type],
                [
                    'price'        => $booking->price,
                    'description'  => 'Auto generated from Booking Kost',

                    // --- UPDATE BARU DISINI ---
                    'stores_id'    => 2,      // ID Store Kost
                    'status'       => 'ready', // Status Ready
                    'image'        => null,    // Udah boleh null sekarang
                    'serving_option' => null,  // Udah boleh null sekarang
                    'category_products_id' => null // Udah boleh null sekarang
                ]
            );

            // 3. Isi Transaction Items
            \App\Models\TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $product->id,
                'product_name'   => $product->name . ' (Kamar ' . $booking->room_number . ')',
                'price'          => $booking->price,
                'quantity'       => 1,
                'subtotal'          => $booking->price,
            ]);
        });
    }
}
