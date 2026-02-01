<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BookingExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        // Ambil data berdasarkan tanggal mulai sewa (date)
        return Booking::query()
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->orderBy('date', 'desc');
    }

    public function map($booking): array
    {
        // Translate Status
        $status = match ($booking->status) {
            'paid' => 'Lunas (Aktif)',
            'pending' => 'Belum Bayar',
            'cancelled' => 'Dibatalkan',
            default => $booking->status,
        };

        // Translate Payment
        $payment = match ($booking->payment) {
            'qris' => 'QRIS',
            'transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            default => ucfirst($booking->payment),
        };

        return [
            $booking->date,         // A. Tanggal Mulai (Raw date biar bisa di-format Excel)
            $booking->end_date,     // B. Tanggal Selesai
            $booking->name,         // C. Nama Penghuni
            'Kamar ' . $booking->room_number, // D. Nomor Kamar
            $booking->room_type,    // E. Tipe Kamar
            $booking->phone,        // F. No HP
            $payment,               // G. Metode Bayar
            $status,                // H. Status
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Masuk',
            'Berakhir Tanggal',
            'Nama Penghuni',
            'Nomor Kamar',
            'Tipe & Harga',
            'No. WhatsApp',
            'Metode Pembayaran',
            'Status Pembayaran',
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
