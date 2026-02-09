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

    // --- CONSTRUCTOR ---
    // Initializes the export class with the selected month and year filters
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    // --- QUERY DATA ---
    // Fetches booking records filtered by start date (month & year)
    public function query()
    {
        return Booking::query()
            ->whereYear('date', $this->year)  // Filter by Start Year
            ->whereMonth('date', $this->month) // Filter by Start Month
            ->orderBy('date', 'desc'); // Sort by newest booking first
    }

    // --- MAPPING ---
    // Formats the data for each row in the Excel file
    public function map($booking): array
    {
        // Logic: Translate Booking Status to Indonesian
        $status = match ($booking->status) {
            'paid' => 'Lunas (Aktif)',
            'pending' => 'Belum Bayar',
            'cancelled' => 'Dibatalkan',
            default => $booking->status,
        };

        // Logic: Translate Payment Method to Readable Text
        $payment = match ($booking->payment) {
            'qris' => 'QRIS',
            'transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            default => ucfirst($booking->payment), // Capitalize first letter for others
        };

        // Return the formatted row data
        return [
            $booking->date,        // Start Date (Raw format for Excel flexibility)
            $booking->end_date,    // End Date
            $booking->name,        // Tenant Name
            'Kamar ' . $booking->room_number, // Room Number with prefix
            $booking->room_type,   // Room Type
            $booking->phone,       // Phone Number
            $payment,              // Payment Method (Translated)
            $status,               // Booking Status (Translated)
        ];
    }

    // --- HEADINGS ---
    // Defines the column titles for the Excel header row
    public function headings(): array
    {
        return [
            'Tanggal Masuk',      // Check-in Date
            'Berakhir Tanggal',   // Check-out Date
            'Nama Penghuni',      // Tenant Name
            'Nomor Kamar',        // Room Number
            'Tipe & Harga',       // Room Type & Price info
            'No. WhatsApp',       // Phone Number
            'Metode Pembayaran',  // Payment Method
            'Status Pembayaran',  // Payment Status
        ];
    }

    // --- STYLING ---
    // Styles the header row (Row 1) with yellow background and bold text
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
                    'startColor' => ['argb' => 'FFFFFF00'], // YELLOW Background
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center align
                ],
            ],
        ];
    }
}
