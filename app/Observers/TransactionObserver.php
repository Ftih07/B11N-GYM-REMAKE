<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Finance;

class TransactionObserver
{
    // Handle create dan update
    public function saved(Transaction $transaction): void
    {
        // Hanya proses jika status 'paid' dan total > 0
        if ($transaction->status === 'paid' && $transaction->total_amount > 0) {

            Finance::updateOrCreate(
                ['transaction_id' => $transaction->id],
                [
                    'gymkos_id' => $transaction->gymkos_id,
                    'type' => 'income',
                    'amount' => $transaction->total_amount,
                    'description' => 'Penjualan POS #' . $transaction->code . ' via ' . ucfirst($transaction->payment_method),
                    'date' => $transaction->created_at->format('Y-m-d'),
                ]
            );
        }
        // Opsional: Jika status diubah dari paid balik ke pending, hapus financenya
        elseif ($transaction->status !== 'paid') {
            $transaction->finance()->delete();
        }
    }

    public function deleted(Transaction $transaction): void
    {
        $transaction->finance()->delete();
    }
}
