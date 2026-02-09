<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking_kost';
    protected $fillable = ['name', 'email', 'phone', 'date', 'end_date', 'room_type', 'room_number', 'price', 'payment', 'payment_proof', 'status'];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
    ];

    // Relation: Connects booking to a financial transaction
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'payable');
    }

    // --- AUTO-LOGIC ON CREATE ---
    protected static function booted()
    {
        static::created(function ($booking) {

            // 1. Create Transaction Header
            $transaction = $booking->transaction()->create([
                'code'           => 'TRX-KOST-' . time(),
                'total_amount'   => $booking->price,
                'status'         => 'pending',
                'payment_method' => $booking->payment,
                'gymkos_id'      => 3, // Hardcoded for Kost Location ID
                'customer_name'  => $booking->name,
            ]);

            // 2. Find or Create Product (So it appears in reports)
            $product = \App\Models\Product::firstOrCreate(
                ['name' => 'Sewa Kost - ' . $booking->room_type],
                [
                    'price'                => $booking->price,
                    'description'          => 'Auto generated from Booking Kost',
                    'stores_id'            => 2,       // Store ID for Kost
                    'status'               => 'ready',
                    'image'                => null,
                    'serving_option'       => null,
                    'category_products_id' => null
                ]
            );

            // 3. Add Item Detail to Transaction
            \App\Models\TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $product->id,
                'product_name'   => $product->name . ' (Kamar ' . $booking->room_number . ')',
                'price'          => $booking->price,
                'quantity'       => 1,
                'subtotal'       => $booking->price,
            ]);
        });
    }
}
