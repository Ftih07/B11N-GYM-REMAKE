<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    // --- QUERY DATA ---
    // Mengambil semua data produk langsung
    public function query()
    {
        // Panggil with() agar query relasinya efisien dan tidak lemot
        return Product::query()
            ->with(['categoryproduct', 'store'])
            ->orderBy('created_at', 'desc');
    }

    // --- MAPPING ---
    // Format baris excel
    public function map($product): array
    {
        // Bikin URL lengkap gambar, atau kasih teks jika kosong
        $imageUrl = $product->image ? asset('storage/' . $product->image) : 'Tidak ada gambar';

        return [
            $product->name,
            $product->categoryproduct ? $product->categoryproduct->name : '-',
            $product->flavour ?? '-',
            $product->serving_option ?? '-',
            $product->price,
            $product->status === 'ready' ? 'Stok Tersedia' : 'Habis Terjual',
            $product->store ? $product->store->title : '-',
            $product->description,
            $imageUrl, // <-- Ini data link gambarnya
            $product->created_at->format('d/m/Y H:i'),
        ];
    }

    // --- HEADINGS ---
    // Baris judul paling atas
    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kategori',
            'Varian Rasa',
            'Takaran Saji',
            'Harga (Rp)',
            'Status',
            'Lokasi Toko',
            'Deskripsi',
            'Link Gambar',
            'Tanggal Ditambahkan',
        ];
    }

    // --- STYLING ---
    // Memberikan warna pada baris judul
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'], // Teks Putih
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF10B981'], // Background Hijau (Emerald)
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
