<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment_membership';

    use HasFactory;

    protected $fillable = ['order_id', 'member_id', 'name', 'image', 'membership_type', 'price', 'status', 'email', 'phone', 'payment'];

    // --- TEMPORARY PROPERTY ---
    // Used to pass Gym ID from Controller to the Model Event below.
    // It is NOT stored in the 'payment_membership' table.
    public $gym_id_temporary = 1;

    // --- RELATIONSHIPS ---

    // Links to the main Transaction table (Polymorphic)
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'payable');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    // --- AUTO-LOGIC (THE MAGIC) ---
    protected static function booted()
    {
        // Event: Triggered immediately after a Payment record is created
        static::created(function ($membership) {

            // Retrieve the Gym ID passed from controller
            $gymId = $membership->gym_id_temporary ?? 1;

            // 1. Create Financial Transaction Header
            // This ensures the payment appears in the Finance Report/POS
            $transaction = $membership->transaction()->create([
                'code'           => 'TRX-MEM-' . time(),
                'total_amount'   => $membership->price,
                'status'         => 'pending',
                'payment_method' => $membership->payment,
                'gymkos_id'      => $gymId,
                'customer_name'  => $membership->name,
            ]);

            // 2. Find or Create Product (Virtual Product)
            // Example name: "Membership Bulanan"
            $productName = 'Membership ' . ucfirst($membership->membership_type);

            $product = \App\Models\Product::firstOrCreate(
                ['name' => $productName],
                [
                    'price'                => $membership->price,
                    'description'          => 'Auto generated from Online Membership',
                    'stores_id'            => 3,       // Hardcoded for 'Gym Gabungan' Store
                    'status'               => 'ready',
                    'image'                => null,
                    'serving_option'       => null,
                    'category_products_id' => null
                ]
            );

            // 3. Link Product to Transaction Items
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
