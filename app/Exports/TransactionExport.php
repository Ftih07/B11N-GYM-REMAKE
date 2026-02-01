<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    use Exportable;

    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        return Transaction::query()
            ->with(['trainer', 'gymkos', 'items.product']) // Eager load relasi biar ringan
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('created_at', 'desc');
    }

    public function map($trx): array
    {
        // 1. Logic Asal Transaksi (Source)
        $source = match ($trx->payable_type) {
            'App\Models\Booking' => 'Booking Kost (Online)',
            'App\Models\Payment' => 'Member (Online)',
            default => 'Kasir / POS (Manual)',
        };

        // 2. Logic Menggabungkan Item Belanjaan jadi 1 String
        // Hasil: "Air Mineral (2), Handuk (1)"
        $itemsList = $trx->items->map(function ($item) {
            $productName = $item->product ? $item->product->name : 'Item Terhapus';
            return "$productName ($item->quantity)";
        })->join(', ');

        return [
            $trx->created_at->format('d/m/Y H:i'), // A. Waktu
            $trx->code,                            // B. Kode TRX
            $trx->gymkos->name ?? '-',             // C. Cabang
            $trx->trainer->name ?? '-',            // D. Kasir
            $trx->customer_name ?? 'Umum',         // E. Customer
            $source,                               // F. Sumber
            $itemsList ?: 'Membership/Sewa',       // G. Detail Item
            strtoupper($trx->payment_method),      // H. Metode Bayar
            strtoupper($trx->status),              // I. Status
            $trx->total_amount,                    // J. Total (Angka Murni)
        ];
    }

    public function headings(): array
    {
        return [
            'Waktu Transaksi',
            'Kode Invoice',
            'Cabang',
            'Kasir Bertugas',
            'Nama Customer',
            'Sumber Transaksi',
            'Detail Item Belanja',
            'Metode Pembayaran',
            'Status',
            'Total Nominal (IDR)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header: Background HIJAU TUA, Text Putih
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF388E3C'], // Hijau Struk
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => '#,##0', // Format Kolom Total jadi Angka Accounting
        ];
    }
}
