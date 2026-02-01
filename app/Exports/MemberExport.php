<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class MemberExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $mode;
    protected $month;
    protected $year;

    // Terima parameter mode, bulan, dan tahun
    public function __construct($mode, $month = null, $year = null)
    {
        $this->mode = $mode;
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        $query = Member::query()
            ->with('gymkos') // Load relasi cabang
            ->orderBy('join_date', 'desc');

        // Jika mode-nya 'period', baru kita filter tanggalnya
        if ($this->mode === 'period' && $this->month && $this->year) {
            $query->whereYear('join_date', $this->year)
                ->whereMonth('join_date', $this->month);
        }

        return $query;
    }

    public function map($member): array
    {
        // Hitung sisa hari membership
        $endDate = Carbon::parse($member->membership_end_date);
        $sisaHari = now()->diffInDays($endDate, false); // false biar ada nilai minus kalau lewat

        $statusMasa = '';
        if ($sisaHari > 0) {
            $statusMasa = "Aktif (" . floor($sisaHari) . " hari lagi)";
        } elseif ($sisaHari == 0) {
            $statusMasa = "Habis Hari Ini";
        } else {
            $statusMasa = "Expired (" . abs(floor($sisaHari)) . " hari lalu)";
        }

        return [
            $member->gymkos->name ?? '-',             // A. Cabang
            $member->name,                            // B. Nama
            $member->phone,                           // C. No HP
            $member->email,                           // D. Email
            Carbon::parse($member->join_date)->format('d/m/Y'), // E. Tgl Gabung
            $endDate->format('d/m/Y'),                // F. Tgl Habis
            $statusMasa,                              // G. Status Hitungan Hari
            $member->status === 'active' ? 'AKTIF' : 'TIDAK AKTIF', // H. Status Sistem
            $member->address,                         // I. Alamat
        ];
    }

    public function headings(): array
    {
        return [
            'Cabang Gym',
            'Nama Member',
            'No. WhatsApp',
            'Email',
            'Tanggal Bergabung',
            'Membership Berakhir',
            'Sisa Masa Aktif',
            'Status Sistem',
            'Alamat Lengkap',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header: Background TEAL, Text Putih
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF009688'], // TEAL
                ],
            ],
        ];
    }
}
