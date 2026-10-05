<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('/v1/sync/daily-recap', function (Request $request) {
    // 1. CEK API KEY
    $secretKey = config('services.ocr.secret');
    $clientKey = $request->header('X-API-KEY');

    if (! $clientKey || $clientKey !== $secretKey) {
        return response()->json([
            'status' => 'unauthorized',
            'message' => 'Akses ditolak! Kunci otorisasi tidak valid.',
        ], 401);
    }

    try {
        $data = $request->validate([
            'trainer_id' => 'required|integer',
            'gymkos_id' => 'required|integer',
            'customer_name' => 'required|string',
            'payment_method' => 'required|string',
            'status' => 'required|string',
            'total_amount' => 'required|numeric',
            'created_at' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|string',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',
            'expense' => 'nullable|array', // Data Pengeluaran untuk tabel finances
        ]);

        return DB::transaction(function () use ($data) {
            $trxTime = date('Y-m-d H:i:s', strtotime($data['created_at']));
            $trxDate = date('Y-m-d', strtotime($data['created_at']));

            // 1. Simpan Transaksi Pemasukan (transactions)
            $transaction = Transaction::create([
                'trainer_id' => $data['trainer_id'],
                'gymkos_id' => $data['gymkos_id'],
                'customer_name' => $data['customer_name'],
                'payment_method' => $data['payment_method'],
                'total_amount' => $data['total_amount'],
                'paid_amount' => $data['total_amount'],
                'change_amount' => 0,
                'status' => $data['status'],
                'proof_of_payment' => null,
                'payable_type' => null,
                'payable_id' => null,
                'created_at' => $trxTime,
                'updated_at' => $trxTime,
            ]);

            // 2. Simpan Item Belanja (transaction_items)
            $items = [];
            foreach ($data['items'] as $item) {
                $items[] = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => $trxTime,
                    'updated_at' => $trxTime,
                ];
            }
            DB::table('transaction_items')->insert($items);

            // 3. Simpan Pengeluaran ke tabel finances (jika ada)
            $expenseMsg = '';
            if (! empty($data['expense']) && $data['expense']['amount'] > 0) {
                DB::table('finances')->insert([
                    'gymkos_id' => $data['gymkos_id'],
                    'transaction_id' => null,
                    'type' => 'expense',
                    'amount' => $data['expense']['amount'],
                    'description' => $data['expense']['description'],
                    'date' => $trxDate,
                    'created_at' => $trxTime,
                    'updated_at' => $trxTime,
                ]);
                $expenseMsg = ' & Pengeluaran Rp '.number_format($data['expense']['amount'], 0, ',', '.').' tersimpan di Finance!';
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi rekap'.$expenseMsg,
                'code' => $transaction->code,
                'total' => $data['total_amount'],
            ], 201);
        });

    } catch (\Illuminate\Validation\ValidationException $ve) {
        return response()->json([
            'status' => 'validation_error',
            'errors' => $ve->errors(),
        ], 422);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => 'error',
            'message' => $th->getMessage(),
        ], 500);
    }
});
