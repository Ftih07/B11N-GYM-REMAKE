<?php

namespace App\Exports;

use App\Models\Finance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FinanceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    use Exportable;

    protected $month;
    protected $year;

    // --- CONSTRUCTOR ---
    // Initializes the export class with the selected financial period
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    // --- QUERY DATA ---
    // Fetches financial records filtered by date
    public function query()
    {
        return Finance::query()
            ->with('gymkos') // Eager load the branch relationship for speed
            ->whereYear('date', $this->year)  // Filter Year
            ->whereMonth('date', $this->month) // Filter Month
            ->orderBy('date', 'asc'); // Sort chronologically (Oldest -> Newest)
    }

    // --- MAPPING ---
    // Formats each row for the Excel report
    public function map($finance): array
    {
        // Logic: Translate Transaction Type to Indonesian
        $tipe = match ($finance->type) {
            'income' => 'Pemasukan',
            'expense' => 'Pengeluaran',
            default => $finance->type,
        };

        return [
            $finance->date->format('d/m/Y'), // Date (DD/MM/YYYY)
            $finance->gymkos->name,          // Branch Name (Gym/Kost)
            $tipe,                           // Transaction Type (Pemasukan/Pengeluaran)
            $finance->description,           // Description/Notes
            $finance->amount,                // Amount (Raw number for Excel calculation)
        ];
    }

    // --- HEADINGS ---
    // Defines the column titles for the first row
    public function headings(): array
    {
        return [
            'Tanggal',          // Date
            'Cabang Gym/Kos',   // Branch
            'Tipe Transaksi',   // Transaction Type
            'Keterangan',       // Description
            'Nominal (IDR)',    // Amount
        ];
    }

    // --- STYLING ---
    // Styles the header row with yellow background and bold text
    public function styles(Worksheet $sheet)
    {
        return [
            // Target Row 1
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FF000000'], // Black Text
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFFFF00'], // YELLOW Background
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center align
                ],
            ],
        ];
    }

    // --- COLUMN FORMATTING ---
    // IMPORTANT: Formats the 'Amount' column as a proper Number in Excel
    // This allows the admin to use SUM() formulas immediately without formatting issues.
    public function columnFormats(): array
    {
        return [
            'E' => '#,##0', // Column E (Nominal): Number format with thousands separator (e.g., 10,000)
        ];
    }
}
