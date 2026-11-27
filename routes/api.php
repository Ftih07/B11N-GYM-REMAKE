<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('/v1/sync/daily-recap', function (Request $request) {
    // 1. CEK API KEY
    // Perbandingan timing-safe; gagal-tertutup bila OCR_SYNC_SECRET belum diatur.
    $secretKey = (string) config('services.ocr.secret');
    $clientKey = (string) $request->header('X-API-KEY');

    if ($secretKey === '' || $clientKey === '' || ! hash_equals($secretKey, $clientKey)) {
        return response()->json([
            'status' => 'unauthorized',
            'message' => 'Akses ditolak! Kunci otorisasi tidak valid.',
        ], 401);
    }

    try {
        $data = $request->validate([
            'trainer_id' => 'required|integer|exists:trainers,id',
            'gymkos_id' => 'required|integer|exists:gymkos,id',
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|in:paid,pending,cancelled',
            'total_amount' => 'required|numeric|min:0',
            'created_at' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|string|exists:products,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'expense' => 'nullable|array', // Data Pengeluaran untuk tabel finances
            'expense.amount' => 'required_with:expense|numeric|min:0',
            'expense.description' => 'required_with:expense|string|max:1000',
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
        // Detail galat hanya ke log; klien cukup menerima pesan generik.
        Log::error('OCR daily-recap gagal', ['exception' => $th]);

        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server. Data tidak disimpan.',
        ], 500);
    }
})->middleware('throttle:ocr-sync');
