<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Member - Front Desk</title>
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
                <i class="fa-solid fa-user-pen text-amber-500"></i> Edit Data Member
            </h2>
            <a href="{{ route('employee.members.index') }}" class="text-zinc-400 hover:text-white text-xs">
                <i class="fa-solid fa-xmark text-sm"></i>
            </a>
        </div>

        <form action="{{ route('employee.members.update', $member->id) }}" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">ID Member</label>
                <input type="text" value="{{ $member->member_code ?? '-' }}" disabled
                    class="w-full bg-zinc-900/60 border border-borderDark rounded-xl px-3.5 py-2.5 text-xs text-zinc-400 font-mono">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" required
                    class="w-full bg-customDark border border-borderDark rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">Nomor WhatsApp / HP</label>
                <input type="tel" name="phone" value="{{ old('phone', $member->phone) }}"
                    class="w-full bg-customDark border border-borderDark rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">Berlaku Sampai (End Date) *</label>
                <input type="date" name="membership_end_date"
                    value="{{ old('membership_end_date', $member->membership_end_date ? \Carbon\Carbon::parse($member->membership_end_date)->format('Y-m-d') : '') }}"
                    required
                    class="w-full bg-customDark border border-borderDark rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-zinc-400 mb-1">Status Membership *</label>
                <select name="status"
                    class="w-full bg-customDark border border-borderDark rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
                    <option value="active" {{ $member->status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $member->status === 'inactive' ? 'selected' : '' }}>Expired (Tidak
                        Aktif)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-borderDark mt-4">
                <a href="{{ route('employee.members.index') }}"
                    class="px-4 py-2 text-xs font-semibold text-zinc-400 hover:text-white">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-bold rounded-xl text-xs transition">
                    Update Data
                </button>
            </div>
        </form>
    </div>

</body>

</html>
