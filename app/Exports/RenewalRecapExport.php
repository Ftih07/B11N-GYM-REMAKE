<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RenewalRecapExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{
    use Exportable;

    private const MONTHS = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /** @param array{rows: array, months: int[]} $recap */
    public function __construct(private array $recap, private string $gymName, private int $year) {}

    public function headings(): array
    {
        return array_merge(
            ['No', 'Kode', 'Berlaku Sampai', 'Nama', 'No. HP', 'Tgl Join'],
            array_map(fn ($m) => self::MONTHS[$m].' '.$this->year, $this->recap['months'])
        );
    }

    public function array(): array
    {
        $out = [];
        foreach ($this->recap['rows'] as $i => $row) {
            $line = [$i + 1, $row['code'] ?? '-', $row['end'] ?? '-', $row['name'], $row['phone'] ?? '-', $row['join'] ?? '-'];
            foreach ($this->recap['months'] as $m) {
                $line[] = $row['cells'][$m] ?? '';
            }
            $out[] = $line;
        }

        return $out;
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
