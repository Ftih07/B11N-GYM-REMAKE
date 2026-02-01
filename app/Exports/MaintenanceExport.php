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

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        return MaintenanceReport::query()
            ->with(['gymkos', 'equipment']) // Eager Load
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('created_at', 'desc');
    }

    public function map($record): array
    {
        // 1. Translate Status
        $status = match ($record->status) {
            'pending' => 'Menunggu (Pending)',
            'in_progress' => 'Sedang Dikerjakan',
            'resolved' => 'Selesai Diperbaiki',
            'wont_fix' => 'Tidak Bisa Diperbaiki',
            default => $record->status,
        };

        // 2. Translate Severity (Keparahan)
        $severity = match ($record->severity) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'KRITIS',
            default => $record->severity,
        };

        // 3. Generate Link Foto (Jika ada)
        // Pastikan APP_URL di .env sudah benar agar link bisa dibuka
        $photoLink = $record->evidence_photo 
            ? url(Storage::url($record->evidence_photo)) 
            : 'Tidak ada foto';

        // 4. Tanggal Selesai
        $fixedDate = $record->fixed_at 
            ? \Carbon\Carbon::parse($record->fixed_at)->format('d/m/Y H:i') 
            : '-';

        return [
            $record->created_at->format('d/m/Y'), // A. Tanggal Lapor
            $record->gymkos->name ?? '-',         // B. Lokasi
            $record->equipment->name ?? 'Umum',   // C. Alat
            $record->reporter_name,               // D. Pelapor
            $record->description,                 // E. Keluhan
            $severity,                            // F. Tingkat Keparahan
            $photoLink,                           // G. Link Bukti Foto (Klikable)
            $status,                              // H. Status Terkini
            $fixedDate                            // I. Tanggal Selesai
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Lapor',
            'Lokasi Cabang',
            'Nama Alat/Fasilitas',
            'Dilaporkan Oleh',
            'Deskripsi Masalah',
            'Tingkat Keparahan',
            'Link Bukti Foto',
            'Status Perbaikan',
            'Waktu Selesai',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Baris 1 (Header) dengan Warna Orange
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FF000000'], // Text Hitam
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFF9800'], // BACKGROUND ORANGE
                ],
            ],
        ];
    }
}