<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tambah Member - Front Desk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        customDark: '#09090b',
                        cardDark: '#141417',
                        borderDark: '#27272a',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-customDark text-zinc-200 min-h-screen p-4 flex items-center justify-center antialiased">

    <div class="bg-cardDark border border-borderDark max-w-md w-full rounded-2xl p-5 shadow-xl">
        <div class="flex items-center justify-between pb-3 border-b border-borderDark mb-4">
            <h2 class="text-sm font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-amber-500"></i> Pendaftaran Member Baru
            </h2>
            <a href="{{ route('employee.members.index') }}" class="text-zinc-400 hover:text-white text-xs">
                <i class="fa-solid fa-xmark text-sm"></i>
            </a>
        </div>

        <form action="{{ route('employee.members.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <!-- PILIHAN CABANG GYM -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">Pilih Cabang Gym *</label>
                <select name="gymkos_id" required
                    class="w-full bg-customDark border border-borderDark rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500 font-semibold">
                    <option value="1">B11N GYM (ID Prefix: B-)</option>
                    <option value="2">K1NG GYM (ID Prefix: K-)</option>
                </select>
                <p class="text-[10px] text-zinc-500 mt-1">ID Member akan dibuat otomatis sesuai urutan cabang yang
                    dipilih.</p>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                    class="w-full bg-customDark border border-borderDark rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">Nomor WhatsApp / HP</label>
                <input type="tel" name="phone" placeholder="Contoh: 08123456789"
                    class="w-full bg-customDark border border-borderDark rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">Durasi Paket Membership *</label>
                <select name="membership_months"
                    class="w-full bg-customDark border border-borderDark rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
                    <option value="1">1 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="12">1 Tahun (12 Bulan)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-borderDark mt-4">
                <a href="{{ route('employee.members.index') }}"
                    class="px-4 py-2 text-xs font-semibold text-zinc-400 hover:text-white">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-bold rounded-xl text-xs transition">
                    Simpan Member
                </button>
            </div>
        </form>
    </div>

</body>

</html>
