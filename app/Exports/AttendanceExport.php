<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $month;
    protected $year;

    // --- CONSTRUCTOR ---
    // Receives the month and year inputs passed from the Filament action
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    // --- QUERY DATA ---
    // Fetches the raw data from the database based on the filters
    public function query()
    {
        return Attendance::query()
            ->with('member') // Eager load 'member' to speed up the process
            ->whereYear('check_in_time', $this->year)  // Filter by Year
            ->whereMonth('check_in_time', $this->month) // Filter by Month
            ->orderBy('check_in_time', 'desc'); // Sort by newest first
    }

    // --- MAPPING ---
    // Formats each row before it enters the Excel file (Human-readable format)
    public function map($attendance): array
    {
        // Logic: Determine if it's a Member or a Visitor
        // If member_id exists, use Member Name. If not, use Visitor Name.
        $nama = $attendance->member_id ? $attendance->member->name : $attendance->visitor_name;

        // Logic: Determine Membership Status
        $statusMember = $attendance->member_id
            ? 'Member Tetap' // Permanent Member
            : 'Non-Member (' . ($attendance->visitor_phone ?? '-') . ')'; // Visitor + Phone

        // Logic: Translate 'Visit Type' to Indonesian
        $tipeKunjungan = match ($attendance->visit_type) {
            'member' => 'Membership',
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            default => $attendance->visit_type,
        };

        // Logic: Translate 'Attendance Method' to Indonesian
        $metode = match ($attendance->method) {
            'face_scan' => 'Scan Wajah',
            'manual' => 'Input Manual',
            'manual_visitor' => 'Tamu (Admin)',
            default => $attendance->method,
        };

        // Return the row data
        return [
            $attendance->check_in_time->format('d/m/Y H:i'), // Format: DD/MM/YYYY HH:MM
            $nama,
            $statusMember,
            $tipeKunjungan,
            $metode,
        ];
    }

    // --- HEADINGS ---
    // Defines the titles for the columns in the first row
    public function headings(): array
    {
        return [
            'Waktu Masuk',       // Check-in Time
            'Nama Pengunjung',   // Visitor Name
            'Status Keanggotaan', // Membership Status
            'Tipe Kunjungan',    // Visit Type
            'Metode Absen',      // Attendance Method
        ];
    }

    // --- STYLING ---
    // Applies CSS-like styles to the Excel sheet
    public function styles(Worksheet $sheet)
    {
        return [
            // Target Row 1 (The Header)
            1 => [
                'font' => [
                    'bold' => true, // Make text Bold
                    'size' => 12,
                    'color' => ['argb' => 'FF000000'], // Black Text color
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFFFF00'], // YELLOW Background color
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center align text
                ],
            ],
        ];
    }
}
