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

    // Terima parameter filter dari Filament
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        // Query data sesuai filter bulan & tahun
        // Kita load relasi member biar query-nya cepat (Eager Loading)
        return Attendance::query()
            ->with('member')
            ->whereYear('check_in_time', $this->year)
            ->whereMonth('check_in_time', $this->month)
            ->orderBy('check_in_time', 'desc');
    }

    // Mapping data biar "Manusiawi" dibaca Admin
    public function map($attendance): array
    {
        // Logic Nama (Sama kayak di Resource kamu)
        $nama = $attendance->member_id ? $attendance->member->name : $attendance->visitor_name;

        // Logic Status Member
        $statusMember = $attendance->member_id
            ? 'Member Tetap'
            : 'Non-Member (' . ($attendance->visitor_phone ?? '-') . ')';

        // Translate Tipe Kunjungan
        $tipeKunjungan = match ($attendance->visit_type) {
            'member' => 'Membership',
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            default => $attendance->visit_type,
        };

        // Translate Metode Absen
        $metode = match ($attendance->method) {
            'face_scan' => 'Scan Wajah',
            'manual' => 'Input Manual',
            'manual_visitor' => 'Tamu (Admin)',
            default => $attendance->method,
        };

        return [
            $attendance->check_in_time->format('d/m/Y H:i'), // Format Tanggal Rapi
            $nama,
            $statusMember,
            $tipeKunjungan,
            $metode,
        ];
    }

    // Judul Header Kolom
    public function headings(): array
    {
        return [
            'Waktu Masuk',
            'Nama Pengunjung',
            'Status Keanggotaan',
            'Tipe Kunjungan',
            'Metode Absen',
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
}
