<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->code }}</title>
    <style>
        body {
            font-family: 'Consolas', 'Monaco', monospace;
            color: #000000;

            /* Trik agar hasil print lebih tebal/tajam */
            -webkit-font-smoothing: none;
            font-weight: bold;

            /* UKURAN FONT UTAMA DINAIKKAN */
            font-size: 14px;

            margin: 0;
            /* Padding 0 agar teks mentok kiri kanan, space lebih luas */
            padding: 2px 0;
            width: 58mm;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 10px;
        }

        /* Khusus Nama Toko Besar */
        .store-name {
            font-size: 20px;
            font-weight: 900;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .address {
            font-size: 12px;
            /* Alamat tetap kecil biar muat */
            font-weight: normal;
        }

        .line {
            border-bottom: 2px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }

        /* Khusus Baris Total agar lebih menonjol */
        .total-row {
            font-size: 16px;
            font-weight: 900;
        }

        @media print {
            @page {
                margin: 0;
                size: 58mm auto;
            }

            body {
                margin: 0;
                padding: 2px 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <div class="store-name">{{ strtoupper($transaction->gymkos->name ?? 'B11N GYM') }}</div>
        <div class="address">{{ strtoupper($transaction->gymkos->address ?? 'Jl. Masjid Baru, Arcawinangun') }}</div>
        <div class="address">Telp: 0896-5384-7651</div>
    </div>

    <div class="line"></div>

    <div style="font-size: 12px;">
        <div>No: {{ $transaction->code }}</div>
        <div>Tgl: {{ $transaction->created_at->format('d-m-Y H:i') }}</div>
        <div>Kasir: {{ $transaction->trainer->name }}</div>

        @if(!empty($transaction->customer_name))
        <div>Pembeli: {{ $transaction->customer_name }}</div>
        @endif
    </div>

    <div class="line"></div>

    <table>
        @foreach($transaction->items as $item)
        <tr>
            <td colspan="2" style="padding-bottom: 2px;">{{ $item->product_name }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr class="total-row">
            <td>TOTAL</td>
            <td class="right">{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 5px;"></td>
        </tr>
        <tr>
            <td>Bayar ({{ ucfirst($transaction->payment_method) }})</td>
            <td class="right">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="right">{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer">
        <div>Terima Kasih</div>
        <div>Selamat Berlatih!</div>
        <div style="margin-top: 10px; font-size: 10px; font-weight: normal;">Powered by B1NG EMPIRE</div>
    </div>

</body>

</html>