<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Front Desk Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-3xl shadow-xl text-center max-w-sm w-full border border-slate-200">
        <div class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full mb-3">
            FRONT DESK PORTAL
        </div>
        <h2 class="text-xl font-bold text-slate-800">Master Staff Access</h2>
        <p class="text-xs text-slate-500 mt-1 mb-6">Scan QR di bawah ini untuk mengelola data membership.</p>

        <!-- Gambar QR Code -->
        <div class="flex justify-center p-4 bg-slate-50 border border-slate-200 rounded-2xl mb-6 shadow-inner">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data={{ urlencode($accessUrl) }}"
                alt="QR Code" class="w-56 h-56 rounded-lg">
        </div>

        <button onclick="window.print()"
            class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak / Print QR
        </button>

        <!-- Tombol Test Buka di Laptop -->
        <div class="mt-4 pt-4 border-t border-slate-100">
            <a href="{{ $accessUrl }}" target="_blank"
                class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 font-semibold underline">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Test Buka Langsung (Simulasi Scan)
            </a>
        </div>
    </div>
</body>

</html>
