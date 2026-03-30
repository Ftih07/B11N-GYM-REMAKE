<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'gymkos_id' => 'required|exists:gymkos,id',
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,qris,transfer',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'proof_of_payment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $trainer = auth()->user()->trainer;

        if (!$trainer) {
            return back()->withErrors([
                'Akses ditolak: Akun Anda belum ditautkan dengan Trainer.'
            ]);
        }

        DB::beginTransaction();

        try {
            // Upload bukti
            $proofPath = $request->hasFile('proof_of_payment')
                ? $request->file('proof_of_payment')->store('transaction-proofs', 'public')
                : null;

            // Hitung kembalian
            $changeAmount = $request->payment_method === 'cash'
                ? $request->paid_amount - $request->total_amount
                : 0;

            // ❗ Tidak perlu isi 'code'
            $transaction = Transaction::create([
                'trainer_id' => $trainer->id,
                'gymkos_id' => $request->gymkos_id,
                'customer_name' => $request->customer_name,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $changeAmount,
                'status' => 'paid',
                'proof_of_payment' => $proofPath,
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $transaction->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('employee.dashboard')->with([
                'success' => 'Transaksi berhasil disimpan!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    // --- Nampilin Halaman Full Edit Transaksi ---
    public function edit(Transaction $transaction)
    {
        // Pastikan hanya kasirnya sendiri yang bisa akses
        if ($transaction->trainer_id != auth()->user()->trainer->id) {
            abort(403, 'Akses Ditolak. Ini bukan transaksi Anda.');
        }

        $products = \App\Models\Product::latest()->get();
        $gymkosList = \App\Models\Gymkos::all();
        $transaction->load('items'); // Load detail belanjaan

        return view('employee.dashboard.edit-transaction', compact('transaction', 'products', 'gymkosList'));
    }

    // --- Simpan Perubahan Full Transaksi ---
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->trainer_id != auth()->user()->trainer->id) {
            abort(403, 'Akses Ditolak. Ini bukan transaksi Anda.');
        }

        $request->validate([
            'gymkos_id' => 'required|exists:gymkos,id',
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,qris,transfer',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'proof_of_payment' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // Update Gambar (Jika ada gambar baru)
            if ($request->hasFile('proof_of_payment')) {
                if ($transaction->proof_of_payment) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->proof_of_payment);
                }
                $transaction->proof_of_payment = $request->file('proof_of_payment')->store('transaction-proofs', 'public');
            }

            $changeAmount = $request->payment_method === 'cash' ? $request->paid_amount - $request->total_amount : 0;

            // 1. Update Info Transaksi Utama
            $transaction->update([
                'gymkos_id' => $request->gymkos_id,
                'customer_name' => $request->customer_name,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $changeAmount,
            ]);

            // 2. Hapus Semua Keranjang Lama, Ganti dengan yang Baru
            $transaction->items()->delete();

            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                $transaction->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('employee.dashboard')->with('success', 'Transaksi berhasil direvisi!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Fungsi Hapus Transaksi
    public function destroy(Transaction $transaction)
    {
        // Pastikan hanya kasir yang bersangkutan yang bisa hapus
        if ($transaction->trainer_id != auth()->user()->trainer->id) {
            abort(403, 'Akses Ditolak. Ini bukan transaksi Anda.');
        }

        // Hapus file bukti pembayaran kalau ada
        if ($transaction->proof_of_payment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->proof_of_payment);
        }

        // Hapus detail barangnya dulu (relasi), baru hapus transaksinya
        $transaction->items()->delete();
        $transaction->delete();

        return back()->with('success', 'Transaksi berhasil dihapus dari riwayat!');
    }
}
