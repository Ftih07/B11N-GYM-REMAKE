<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment_membership';

    use HasFactory;

    protected $fillable = ['order_id', 'member_id', 'name', 'image', 'membership_type', 'price', 'status', 'email', 'phone', 'payment'];

    // Properti sementara untuk menampung Gym ID dari Controller
    public $gym_id_temporary = 1; // Default 1 jika tidak diset

    // Relasi ke Transaction
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'payable');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    // --- LOGIC OTOMATIS ---
    protected static function booted()
    {
        static::created(function ($membership) {

            $gymId = $membership->gym_id_temporary ?? 1;

            // 1. Buat Transaction
            $transaction = $membership->transaction()->create([
                'code'           => 'TRX-MEM-' . time(),
                'total_amount'   => $membership->price,
                'status'         => 'pending',
                'payment_method' => $membership->payment,
                'gymkos_id'      => $gymId,
                'customer_name'  => $membership->name,
            ]);

            // 2. Cari/Buat Produk
            $productName = 'Membership ' . ucfirst($membership->membership_type);

            $product = \App\Models\Product::firstOrCreate(
                ['name' => $productName],
                [
                    'price'        => $membership->price,
                    'description'  => 'Auto generated from Online Membership',

                    // --- UPDATE BARU DISINI ---
                    'stores_id'    => 3,       // ID Store Gym Gabungan
                    'status'       => 'ready', // Status Ready
                    'image'        => null,
                    'serving_option' => null,
                    'category_products_id' => null
                ]
            );

            // 3. Isi Transaction Items
            \App\Models\TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $product->id,
                'product_name'   => $productName . ' (' . $membership->name . ')',
                'price'          => $membership->price,
                'quantity'       => 1,
                'subtotal'       => $membership->price,
            ]);
        });
    }
}
