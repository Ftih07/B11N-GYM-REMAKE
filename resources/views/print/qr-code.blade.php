<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Code - {{ $qrCode->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS khusus saat di-print agar tampilan bersih */
        @media print {
            body {
                background: white;
            }
            .no-print {
                display: none;
            }
            .print-container {
                border: 1px solid #ddd;
                padding: 2rem;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-10 flex justify-center items-center min-h-screen">

    <div class="print-container bg-white p-8 rounded-xl shadow-lg text-center max-w-md w-full mx-auto">
        <h1 class="text-2xl font-bold mb-2 text-gray-800">{{ $qrCode->name }}</h1>
        
        <div class="my-6 flex justify-center">
            {{-- Menampilkan gambar dari storage --}}
            <img src="{{ asset('storage/' . $qrCode->qr_path) }}" 
                 alt="{{ $qrCode->name }}" 
                 class="w-64 h-64 border-2 border-gray-200 rounded-lg p-2">
        </div>

        <p class="text-sm text-gray-500 font-mono break-all border-t pt-4">
            {{ $qrCode->target_url }}
        </p>
    </div>

    {{-- Script auto print saat halaman terbuka --}}
    <script>
        window.onload = function() {
             window.print();
        }
    </script>
</body>
</html>