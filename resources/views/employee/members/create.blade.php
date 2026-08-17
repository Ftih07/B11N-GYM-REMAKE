<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tambah Member - Front Desk</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 min-h-screen p-4 flex items-center justify-center antialiased">

    <div class="bg-white border border-slate-200/80 max-w-md w-full rounded-2xl p-6 shadow-lg">

        <!-- Header Card -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 leading-tight">Pendaftaran Member Baru</h2>
                    <p class="text-[11px] text-slate-500">Isi data calon member di bawah ini</p>
                </div>
            </div>
            <a href="{{ route('employee.members.index') }}"
                class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </a>
        </div>

        <form action="{{ route('employee.members.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Pilihan Cabang Gym -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Cabang Gym <span
                        class="text-rose-500 font-bold">*</span></label>
                <select name="gymkos_id" required
                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-xs">
                    <option value="1">B11N GYM (Kode Awal: B-)</option>
                    <option value="2">K1NG GYM (Kode Awal: K-)</option>
                </select>
                <p class="text-[10px] text-slate-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info text-amber-600"></i> Nomor ID otomatis dibuat sesuai urutan
                    cabang.
                </p>
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span
                        class="text-rose-500 font-bold">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-xs">
            </div>

            <!-- Nomor WhatsApp / HP -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                <div class="relative">
                    <input type="tel" name="phone" placeholder="Contoh: 08123456789"
                        class="w-full bg-white border border-slate-300 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-xs font-mono">
                    <i class="fa-brands fa-whatsapp absolute left-3 top-3 text-emerald-600 text-xs"></i>
                </div>
            </div>

            <!-- Durasi Paket Membership -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Paket Durasi <span
                        class="text-rose-500 font-bold">*</span></label>
                <select name="membership_months"
                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-xs">
                    <option value="1">1 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="12">1 Tahun (12 Bulan)</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 mt-5">
                <a href="{{ route('employee.members.index') }}"
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 active:scale-95 text-slate-950 font-bold rounded-xl text-xs shadow-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-xs"></i> Daftarkan Member
                </button>
            </div>
        </form>
    </div>

</body>

</html>
