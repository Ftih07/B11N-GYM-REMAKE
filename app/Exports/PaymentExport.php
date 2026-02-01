<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Storage;

class PaymentExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        return Payment::query()
            ->with('member') // Eager load relasi member
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('created_at', 'desc');
    }

    public function map($payment): array
    {
        // 1. Logic Status
        $status = match ($payment->status) {
            'pending' => 'MENUNGGU VERIFIKASI',
            'confirmed' => 'LUNAS (CONFIRMED)',
            'rejected' => 'DITOLAK',
            default => strtoupper($payment->status),
        };

        // 2. Logic Tipe Member (Baru vs Lama)
        $tipeUser = $payment->member_id
            ? 'Member Lama (Perpanjang)'
            : 'Pendaftar Baru';

        // 3. Link Bukti Bayar
        // Pastikan APP_URL di .env sudah benar (http://localhost:8000 atau domain kamu)
        $buktiBayar = $payment->image
            ? url(Storage::url($payment->image))
            : 'Tidak ada bukti';

        // 4. Nama (Ambil dari relasi jika ada, atau dari input form)
        $nama = $payment->member_id && $payment->member
            ? $payment->member->name
            : $payment->name;

        return [
            $payment->created_at->format('d/m/Y H:i'), // A. Tanggal Transaksi
            $payment->order_id,                        // B. Order ID
            $nama,                                     // C. Nama
            $tipeUser,                                 // D. Status User
            $payment->membership_type,                 // E. Paket
            $payment->payment,                         // F. Metode Bayar
            $payment->phone,                           // G. No WA
            $status,                                   // H. Status Pembayaran
            $buktiBayar,                               // I. Link Bukti (Klikable)
        ];
    }

    public function headings(): array
    {
        return [
            'Waktu Transaksi',
            'Order ID',
            'Nama Member/Pendaftar',
            'Tipe Pendaftar',
            'Paket Membership',
            'Metode Pembayaran',
            'No. WhatsApp',
            'Status',
            'Link Bukti Transfer',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header: Background BIRU, Text Putih (Biar beda dan fresh)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'], // Text Putih
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF2196F3'], // BACKGROUND BIRU MATERIAL
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
