<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class GenerateProductSlugs extends Command
{
    // Ini nama command yang akan kita panggil nanti
    protected $signature = 'product:generate-slugs';

    // Deskripsi command
    protected $description = 'Generate unique slugs untuk produk existing yang belum punya slug';

    public function handle()
    {
        // Ambil produk yang slug-nya masih null atau kosong
        $products = Product::whereNull('slug')->orWhere('slug', '')->get();

        if ($products->isEmpty()) {
            $this->info('Aman! Semua produk sudah memiliki slug.');
            return;
        }

        $this->info("Menyiapkan update untuk {$products->count()} produk...");

        // Pakai progress bar biar kelihatan prosesnya kalau dijalankan di terminal
        $this->withProgressBar($products, function ($product) {
            $product->slug = Product::generateUniqueSlug($product->name);
            $product->save();
        });

        $this->newLine();
        $this->info('Selesai! Berhasil meng-generate slug untuk ' . $products->count() . ' produk!');
    }
}
