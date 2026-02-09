<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Finance;

class TransactionObserver
{
    // Logic: Triggered on Create and Update
    public function saved(Transaction $transaction): void
    {
        // Condition: Only record to Finance if Status is 'PAID'
        if ($transaction->status === 'paid' && $transaction->total_amount > 0) {

            // Create or Update Finance Record
            Finance::updateOrCreate(
                ['transaction_id' => $transaction->id], // Identifier
                [
                    'gymkos_id'   => $transaction->gymkos_id,
                    'type'        => 'income',
                    'amount'      => $transaction->total_amount,
                    'description' => 'Penjualan POS #' . $transaction->code . ' via ' . ucfirst($transaction->payment_method),
                    'date'        => $transaction->created_at->format('Y-m-d'),
                ]
            );
        }
        // If status changed from 'paid' back to 'pending', remove the income record
        elseif ($transaction->status !== 'paid') {
            $transaction->finance()->delete();
        }
    }

    public function deleted(Transaction $transaction): void
    {
        // Clean up finance record if transaction is deleted
        $transaction->finance()->delete();
    }
}
