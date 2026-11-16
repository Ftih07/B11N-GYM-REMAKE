<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Analisis Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111827; }
        h1 { font-size: 16px; margin: 0 0 2px; }
        h2 { font-size: 12px; margin: 16px 0 6px; border-bottom: 1px solid #d1d5db; padding-bottom: 3px; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        th, td { border: 1px solid #e5e7eb; padding: 4px 6px; text-align: left; }
        th { background: #f3f4f6; }
        td.num, th.num { text-align: right; }
        ul { margin: 0; padding-left: 14px; }
        li { margin-bottom: 3px; }
    </style>
</head>
<body>
@php use App\Services\SalesAnalytics as A; @endphp

<h1>Analisis Penjualan - B1NG EMPIRE</h1>
<div class="muted">Periode: {{ $periode }} &nbsp;|&nbsp; Cabang: {{ $cabang }} &nbsp;|&nbsp; Dibuat: {{ $dibuat }}</div>

<h2>Ringkasan</h2>
<table>
    @foreach ($kpi as [$label, $value])
        <tr><th style="width:45%">{{ $label }}</th><td>{{ $value }}</td></tr>
    @endforeach
</table>

<h2>Analisis Singkat</h2>
<ul>
    @foreach ($insights as $i)
        <li>{{ $i }}</li>
    @endforeach
</ul>

<h2>Tren Bulanan</h2>
<table>
    <tr>
        <th></th>
        @foreach ($monthly['labels'] as $l)<th class="num">{{ $l }}</th>@endforeach
    </tr>
    @foreach ($monthly['series'] as $s)
        <tr>
            <td>{{ $s['name'] }}</td>
            @foreach ($s['values'] as $v)<td class="num">{{ number_format($v, 0, ',', '.') }}</td>@endforeach
        </tr>
    @endforeach
</table>

<h2>Rata-rata Pendapatan per Hari (Rp)</h2>
<table>
    <tr>
        <th></th>
        @foreach ($weekday['labels'] as $l)<th class="num">{{ $l }}</th>@endforeach
    </tr>
    @foreach ($weekday['series'] as $s)
        <tr>
            <td>{{ $s['name'] }}</td>
            @foreach ($s['values'] as $v)<td class="num">{{ number_format($v, 0, ',', '.') }}</td>@endforeach
        </tr>
    @endforeach
</table>

<h2>Produk Terlaris</h2>
<table>
    <tr><th>Produk</th><th class="num">Terjual</th><th class="num">Nilai (Rp)</th></tr>
    @foreach ($products as $p)
        <tr>
            <td>{{ $p->product_name }}</td>
            <td class="num">{{ number_format($p->qty, 0, ',', '.') }}</td>
            <td class="num">{{ number_format($p->total, 0, ',', '.') }}</td>
        </tr>
    @endforeach
</table>

<h2>Metode Pembayaran</h2>
<table>
    <tr><th>Metode</th><th class="num">Transaksi</th><th class="num">Nilai (Rp)</th></tr>
    @foreach ($payments as $p)
        <tr>
            <td>{{ strtoupper($p->payment_method) }}</td>
            <td class="num">{{ number_format($p->qty, 0, ',', '.') }}</td>
            <td class="num">{{ number_format($p->total, 0, ',', '.') }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>