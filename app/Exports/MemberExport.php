<?php

namespace App\Exports;

use App\Models\Member;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MemberExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $mode;

    protected $month;

    protected $year;

    protected $gymkosId;

    public function __construct($mode = 'all', $month = null, $year = null, $gymkosId = null)
    {
        $this->mode = $mode;
        $this->month = $month;
        $this->year = $year;
        $this->gymkosId = $gymkosId;
    }

    // --- QUERY DATA ---
    public function query()
    {
        $query = Member::query()
            ->with('gymkos')
            ->orderByRaw('CAST(SUBSTRING(member_code, 3) AS UNSIGNED) ASC'); // Urut dari nomor terkecil

        if ($this->mode === 'period' && $this->month && $this->year) {
            $query->whereYear('join_date', $this->year)
                ->whereMonth('join_date', $this->month);
        }

        if ($this->gymkosId !== null) {
            $query->where('gymkos_id', $this->gymkosId);
        }

        return $query;
    }

    // --- MAPPING (Sesuaikan Urutan Kolom dengan Template Excel Asli) ---
    public function map($member): array
    {
        $endDate = $member->membership_end_date ? Carbon::parse($member->membership_end_date) : null;
        $sisaHari = $endDate ? now()->diffInDays($endDate, false) : null;

        $statusMasa = '-';
        if ($sisaHari !== null) {
            if ($sisaHari > 0) {
                $statusMasa = 'Aktif ('.floor($sisaHari).' hari lagi)';
            } elseif ($sisaHari == 0) {
                $statusMasa = 'Habis Hari Ini';
            } else {
                $statusMasa = 'Expired ('.abs(floor($sisaHari)).' hari lalu)';
            }
        }

        return [
            $member->member_code ?? '-',                                                      // Kolom A: NO / ID
            $endDate ? $endDate->format('d/m/Y') : '',                                         // Kolom B: END DATE
            $member->name,                                                                     // Kolom C: NAMA
            $member->phone ?? '-',                                                             // Kolom D: NO. HP
            $member->join_date ? Carbon::parse($member->join_date)->format('d F Y') : '-',     // Kolom E: TGL JOIN (misal: 24 May 2026)
            $member->gymkos->name ?? '-',                                                      // Kolom F: CABANG
            $member->status === 'active' ? 'AKTIF' : 'EXPIRED',                                // Kolom G: STATUS
            $statusMasa,                                                                       // Kolom H: SISA MASA AKTIF
            $member->email ?? '-',                                                             // Kolom I: EMAIL
            $member->address ?? '-',                                                           // Kolom J: ALAMAT
        ];
    }

    // --- HEADINGS (Header Persis Sesuai Template) ---
    public function headings(): array
    {
        return [
            'NO',                  // A1
            'END DATE',            // B1
            'NAMA',                // C1
            'NO. HP',              // D1
            'TGL JOIN',            // E1
            'CABANG GYM',          // F1
            'STATUS',              // G1
            'SISA MASA AKTIF',     // H1
            'EMAIL',               // I1
            'ALAMAT LENGKAP',      // J1
        ];
    }

    // --- STYLING (Warna Kuning & Peach Seperti Template Aslinya) ---
    public function styles(Worksheet $sheet)
    {
        // 1. Style Kolom A1 (Header NO - Warna Peach Pastel)
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFDE9D9'], // Peach Pastel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 2. Style Kolom B1 s/d E1 (Header END DATE, NAMA, NO. HP, TGL JOIN - Warna Kuning Terang)
        $sheet->getStyle('B1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFFF00'], // Kuning Terang
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 3. Style Kolom Tambahan F1 s/d J1 (Cabang, Status, dsb - Dark Gray/Zinc Header Elegan)
        $sheet->getStyle('F1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'name' => 'Calibri',
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF27272A'], // Dark Zinc
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 4. Border Tipis untuk Seluruh Header Baris 1
        $sheet->getStyle('A1:J1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        return [];
    }
}
