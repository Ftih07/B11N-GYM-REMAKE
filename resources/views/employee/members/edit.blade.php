<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Member - Front Desk</title>
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

        <!-- Header Modal/Card -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 leading-tight">Edit Data Member</h2>
                    <p class="text-[11px] text-slate-500">Perbarui profil atau masa aktif member</p>
                </div>
            </div>
            <a href="{{ route('employee.members.index') }}"
                class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </a>
        </div>

        <form action="{{ route('employee.members.update', $member->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- ID Member (Read Only) -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">ID Member</label>
                <div class="relative">
                    <input type="text" value="{{ $member->member_code ?? '-' }}" disabled
                        class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-600 font-mono font-bold cursor-not-allowed">
                    <i class="fa-solid fa-lock absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">ID dibuat otomatis dan tidak bisa diubah.</p>
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span
                        class="text-rose-500 font-bold">*</span></label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" required
                    placeholder="Masukkan nama lengkap member"
                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-xs">
            </div>

            <!-- Nomor WhatsApp / HP -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                <div class="relative">
                    <input type="tel" name="phone" value="{{ old('phone', $member->phone) }}"
                        placeholder="Contoh: 08123456789"
                        class="w-full bg-white border border-slate-300 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-xs font-mono">
                    <i class="fa-brands fa-whatsapp absolute left-3 top-3 text-emerald-600 text-xs"></i>
                </div>
            </div>

            <!-- Berlaku Sampai (Tanggal) -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Masa Berlaku Sampai <span
                        class="text-rose-500 font-bold">*</span></label>
                <input type="date" name="membership_end_date"
                    value="{{ old('membership_end_date', $member->membership_end_date ? \Carbon\Carbon::parse($member->membership_end_date)->format('Y-m-d') : '') }}"
                    required
                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-xs">
            </div>

            <!-- Status Membership -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Status Member <span
                        class="text-rose-500 font-bold">*</span></label>
                <select name="status"
                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-xs">
                    <option value="active" {{ $member->status === 'active' ? 'selected' : '' }}>🟢 Aktif (Bisa Masuk)
                    </option>
                    <option value="inactive" {{ $member->status === 'inactive' ? 'selected' : '' }}>🔴 Habis / Nonaktif
                    </option>
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
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</body>

</html>
