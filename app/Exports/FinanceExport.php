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

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        return Finance::query()
            ->with('gymkos') // Eager load relasi biar cepat
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->orderBy('date', 'asc'); // Biasanya keuangan urut dari tanggal awal ke akhir
    }

    public function map($finance): array
    {
        // Translate Tipe biar bahasa Indonesia
        $tipe = match ($finance->type) {
            'income' => 'Pemasukan',
            'expense' => 'Pengeluaran',
            default => $finance->type,
        };

        return [
            $finance->date->format('d/m/Y'), // A. Tanggal
            $finance->gymkos->name,          // B. Cabang
            $tipe,                           // C. Tipe
            $finance->description,           // D. Keterangan
            $finance->amount,                // E. Nominal (Raw Number)
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Cabang Gym/Kos',
            'Tipe Transaksi',
            'Keterangan',
            'Nominal (IDR)',
        ];
    }

    // --- LOGIC STYLING KUNING & BOLD ---
    public function styles(Worksheet $sheet)
    {
        return [
            // Baris 1 (Header)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FF000000'], // Teks Hitam
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFFFF00'], // BACKGROUND KUNING
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    // Ini FITUR PENTING buat Keuangan
    // Mengatur kolom E (Nominal) supaya formatnya Accounting/Number di Excel
    public function columnFormats(): array
    {
        return [
            'E' => '#,##0', // Format angka dengan pemisah ribuan
        ];
    }
}
