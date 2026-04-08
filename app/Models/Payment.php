<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment_membership';

    use HasFactory;

    protected $fillable = ['order_id', 'member_id', 'name', 'image', 'membership_type', 'price', 'status', 'email', 'phone', 'payment'];

    public $gym_id_temporary = 1;

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'payable');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    protected static function booted()
    {
        // ... Logika static::created(...) milikmu DIBIARKAN SAJA, JANGAN DIHAPUS ...
        static::created(function ($membership) {
            $gymId = $membership->gym_id_temporary ?? 1;
            $transaction = $membership->transaction()->create([
                'code'           => 'TRX-MEM-' . time(),
                'total_amount'   => $membership->price,
                'status'         => 'pending',
                'payment_method' => $membership->payment,
                'gymkos_id'      => $gymId,
                'customer_name'  => $membership->name,
            ]);

            $productName = 'Membership ' . ucfirst($membership->membership_type);

            $product = \App\Models\Product::firstOrCreate(
                ['name' => $productName],
                [
                    'price'                => $membership->price,
                    'description'          => 'Auto generated from Online Membership',
                    'stores_id'            => 3,
                    'status'               => 'ready',
                    'image'                => null,
                    'serving_option'       => null,
                    'category_products_id' => null
                ]
            );

            \App\Models\TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $product->id,
                'product_name'   => $productName . ' (' . $membership->name . ')',
                'price'          => $membership->price,
                'quantity'       => 1,
                'subtotal'       => $membership->price,
            ]);
        });

        // --- TAMBAHAN BARU: EVENT SAAT PAYMENT DIHAPUS ---
        static::deleting(function ($payment) {
            // Hapus transaksi polymorphic yang terkait dengan payment ini.
            // Karena relasi morphOne, kita bisa langsung memanggil ->delete()
            if ($payment->transaction) {
                $payment->transaction->delete();
            }
        });
    }
}
