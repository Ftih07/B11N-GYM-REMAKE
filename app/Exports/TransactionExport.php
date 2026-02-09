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

    // --- CONSTRUCTOR ---
    // Initializes the export class with the selected period
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    // --- QUERY DATA ---
    // Fetches transaction records filtered by date
    public function query()
    {
        return Transaction::query()
            ->with(['trainer', 'gymkos', 'items.product']) // Eager Load to avoid N+1 queries
            ->whereYear('created_at', $this->year)  // Filter Year
            ->whereMonth('created_at', $this->month) // Filter Month
            ->orderBy('created_at', 'desc'); // Sort by newest transaction
    }

    // --- MAPPING ---
    // Formats each row for the Excel report
    public function map($trx): array
    {
        // Logic: Determine Transaction Source (Online vs Manual POS)
        $source = match ($trx->payable_type) {
            'App\Models\Booking' => 'Booking Kost (Online)',
            'App\Models\Payment' => 'Member (Online)',
            default => 'Kasir / POS (Manual)',
        };

        // Logic: Consolidate Purchased Items into One String
        // Example Output: "Mineral Water (2), Towel (1)"
        $itemsList = $trx->items->map(function ($item) {
            $productName = $item->product ? $item->product->name : 'Item Terhapus';
            return "$productName ($item->quantity)";
        })->join(', ');

        return [
            $trx->created_at->format('d/m/Y H:i'), // A. Transaction Time
            $trx->code,                            // B. Invoice Code
            $trx->gymkos->name ?? '-',             // C. Branch
            $trx->trainer->name ?? '-',            // D. Cashier Name
            $trx->customer_name ?? 'Umum',         // E. Customer Name
            $source,                               // F. Source
            $itemsList ?: 'Membership/Sewa',       // G. Item Details (Fallback if empty)
            strtoupper($trx->payment_method),      // H. Payment Method
            strtoupper($trx->status),              // I. Status
            $trx->total_amount,                    // J. Total Amount (Raw Number)
        ];
    }

    // --- HEADINGS ---
    // Defines the column titles
    public function headings(): array
    {
        return [
            'Waktu Transaksi',     // Transaction Time
            'Kode Invoice',        // Invoice Code
            'Cabang',              // Branch
            'Kasir Bertugas',      // Cashier
            'Nama Customer',       // Customer
            'Sumber Transaksi',    // Source
            'Detail Item Belanja', // Item Details
            'Metode Pembayaran',   // Payment Method
            'Status',              // Status
            'Total Nominal (IDR)', // Total Amount
        ];
    }

    // --- STYLING ---
    // Styles the header row with DARK GREEN background (Receipt theme)
    public function styles(Worksheet $sheet)
    {
        return [
            // Target Row 1
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'], // White Text
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF388E3C'], // DARK GREEN Background
                ],
            ],
        ];
    }

    // --- COLUMN FORMATTING ---
    // Formats the 'Total Amount' column as a Number
    public function columnFormats(): array
    {
        return [
            'J' => '#,##0', // Column J: Number format with thousands separator
        ];
    }
}
