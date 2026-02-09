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

    // --- CONSTRUCTOR ---
    // Receives export mode ('all' or 'period') and date filters
    public function __construct($mode, $month = null, $year = null)
    {
        $this->mode = $mode;
        $this->month = $month;
        $this->year = $year;
    }

    // --- QUERY DATA ---
    // Fetches member data based on the selected mode
    public function query()
    {
        $query = Member::query()
            ->with('gymkos') // Eager load branch relationship
            ->orderBy('join_date', 'desc');

        // Logic: Apply date filter ONLY if mode is 'period'
        if ($this->mode === 'period' && $this->month && $this->year) {
            $query->whereYear('join_date', $this->year)
                ->whereMonth('join_date', $this->month);
        }

        return $query;
    }

    // --- MAPPING ---
    // Formats each row (Calculates remaining days, formats dates)
    public function map($member): array
    {
        // Logic: Calculate Remaining Membership Days
        $endDate = Carbon::parse($member->membership_end_date);
        $sisaHari = now()->diffInDays($endDate, false); // 'false' allows negative values for expired dates

        $statusMasa = '';
        if ($sisaHari > 0) {
            $statusMasa = "Aktif (" . floor($sisaHari) . " hari lagi)";
        } elseif ($sisaHari == 0) {
            $statusMasa = "Habis Hari Ini";
        } else {
            $statusMasa = "Expired (" . abs(floor($sisaHari)) . " hari lalu)";
        }

        return [
            $member->gymkos->name ?? '-',          // Branch Name
            $member->name,                         // Member Name
            $member->phone,                        // Phone Number
            $member->email,                        // Email
            Carbon::parse($member->join_date)->format('d/m/Y'), // Join Date
            $endDate->format('d/m/Y'),             // Expiration Date
            $statusMasa,                           // Calculated Status (Active/Expired)
            $member->status === 'active' ? 'AKTIF' : 'TIDAK AKTIF', // System Status
            $member->address,                      // Address
        ];
    }

    // --- HEADINGS ---
    // Defines column titles
    public function headings(): array
    {
        return [
            'Cabang Gym',          // Branch
            'Nama Member',         // Name
            'No. WhatsApp',        // Phone
            'Email',               // Email
            'Tanggal Bergabung',   // Join Date
            'Membership Berakhir', // End Date
            'Sisa Masa Aktif',     // Remaining Days
            'Status Sistem',       // System Status
            'Alamat Lengkap',      // Address
        ];
    }

    // --- STYLING ---
    // Styles the header row with TEAL background and WHITE text
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
                    'startColor' => ['argb' => 'FF009688'], // TEAL Background
                ],
            ],
        ];
    }
}
