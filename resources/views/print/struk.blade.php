<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->code }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            /* Font kasir */
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 58mm;
            /* Ukuran kertas thermal standard */
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 10px;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
        }

        td {
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        /* Hilangkan elemen browser saat print */
        @media print {
            @page {
                margin: 0;
                size: auto;
            }

            body {
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <div class="bold">{{ strtoupper($transaction->gymkos->name ?? 'B11N GYM') }}</div>
        <div>Jl. Merdeka No. 3, Purwokerto</div>
        <div>Telp: 0812-3456-7890</div>
    </div>

    <div class="line"></div>

    <div>
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
            <td colspan="2">{{ $item->product_name }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Total</td>
            <td class="right bold">{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
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
        <div>Selamat Berlatih! 💪</div>
        <div style="margin-top: 5px; font-size: 10px;">Powered by B1NG EMPIRE</div>
    </div>

</body>

</html>