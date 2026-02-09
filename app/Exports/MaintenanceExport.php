<?php

namespace App\Exports;

use App\Models\MaintenanceReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Storage;

class MaintenanceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
    // Fetches maintenance reports filtered by creation date
    public function query()
    {
        return MaintenanceReport::query()
            ->with(['gymkos', 'equipment']) // Eager Load relations to prevent N+1 query problem
            ->whereYear('created_at', $this->year)  // Filter Year
            ->whereMonth('created_at', $this->month) // Filter Month
            ->orderBy('created_at', 'desc'); // Sort by newest report
    }

    // --- MAPPING ---
    // Formats each row for the Excel report
    public function map($record): array
    {
        // Logic: Translate Status to Indonesian
        $status = match ($record->status) {
            'pending' => 'Menunggu (Pending)',
            'in_progress' => 'Sedang Dikerjakan',
            'resolved' => 'Selesai Diperbaiki',
            'wont_fix' => 'Tidak Bisa Diperbaiki',
            default => $record->status,
        };

        // Logic: Translate Severity Level
        $severity = match ($record->severity) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'KRITIS',
            default => $record->severity,
        };

        // Logic: Generate Full URL for Evidence Photo
        // Uses Laravel's Storage facade to create a clickable link
        $photoLink = $record->evidence_photo
            ? url(Storage::url($record->evidence_photo))
            : 'Tidak ada foto';

        // Logic: Format Fixed Date (if resolved)
        $fixedDate = $record->fixed_at
            ? \Carbon\Carbon::parse($record->fixed_at)->format('d/m/Y H:i')
            : '-';

        return [
            $record->created_at->format('d/m/Y'), // Report Date
            $record->gymkos->name ?? '-',         // Branch Location
            $record->equipment->name ?? 'Umum',   // Equipment Name (or General)
            $record->reporter_name,               // Reporter Name
            $record->description,                 // Issue Description
            $severity,                            // Severity Level
            $photoLink,                           // Clickable Photo Link
            $status,                              // Current Status
            $fixedDate                            // Date Resolved
        ];
    }

    // --- HEADINGS ---
    // Defines the column titles
    public function headings(): array
    {
        return [
            'Tanggal Lapor',       // Report Date
            'Lokasi Cabang',       // Branch Location
            'Nama Alat/Fasilitas', // Equipment Name
            'Dilaporkan Oleh',     // Reported By
            'Deskripsi Masalah',   // Description
            'Tingkat Keparahan',   // Severity
            'Link Bukti Foto',     // Photo Evidence
            'Status Perbaikan',    // Repair Status
            'Waktu Selesai',       // Completion Time
        ];
    }

    // --- STYLING ---
    // Styles the header row with ORANGE background
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
                    'startColor' => ['argb' => 'FFFF9800'], // ORANGE Background (Alert color)
                ],
            ],
        ];
    }
}
