<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Data Membership - Front Desk</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen pb-16 antialiased">

    <!-- Top App Bar -->
    <header
        class="bg-white border-b border-slate-200 px-4 py-3.5 sticky top-0 z-40 shadow-xs flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div
                class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                <i class="fa-solid fa-dumbbell"></i>
            </div>
            <div>
                <p class="text-[11px] text-slate-500 font-semibold tracking-wide uppercase">Front Desk</p>
                <h1 class="text-base font-bold text-slate-900 leading-tight">Data Member</h1>
            </div>
        </div>

        <a href="{{ route('employee.members.create') }}"
            class="bg-amber-500 hover:bg-amber-600 active:scale-95 text-slate-950 px-3.5 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i> Tambah Member
        </a>
    </header>

    <main class="max-w-xl mx-auto px-4 mt-4">

        <!-- Flash Alert -->
        @if (session('success'))
            <div
                class="p-3 mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium flex items-center gap-2.5 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Search Bar -->
        <form method="GET" action="{{ route('employee.members.index') }}" class="mb-4">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Ketik Nama, No. HP, atau ID Member..."
                        class="w-full bg-white border border-slate-300 rounded-xl py-2.5 pl-10 pr-9 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition shadow-xs">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>

                    @if ($search)
                        <a href="{{ route('employee.members.index') }}"
                            class="absolute right-3 top-3 text-slate-400 hover:text-slate-700 text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white font-semibold text-xs rounded-xl flex items-center gap-1.5 shadow-xs transition">
                    Cari
                </button>
            </div>
        </form>

        <!-- Member List Container -->
        <div class="space-y-3">
            @forelse($members as $m)
                <div
                    class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-sm hover:border-slate-300 transition">

                    <!-- Top Row: Avatar, Nama, ID, Phone & Status -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Avatar Circular -->
                            @if ($m->picture)
                                <img src="{{ asset('storage/' . $m->picture) }}" alt="{{ $m->name }}"
                                    class="w-11 h-11 rounded-full object-cover shrink-0 border border-slate-200">
                            @else
                                <div
                                    class="w-11 h-11 rounded-full bg-sky-100 text-sky-700 font-bold text-xs flex items-center justify-center shrink-0 border border-sky-200">
                                    {{ strtoupper(substr($m->name, 0, 2)) }}
                                </div>
                            @endif

                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if ($m->member_code)
                                        <span
                                            class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-mono font-semibold">
                                            {{ $m->member_code }}
                                        </span>
                                    @endif
                                    <h2 class="font-bold text-slate-900 text-sm tracking-tight truncate">
                                        {{ $m->name }}
                                    </h2>
                                </div>
                                <p class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1">
                                    <i class="fa-brands fa-whatsapp text-emerald-600 text-xs"></i>
                                    {{ $m->phone ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <span
                            class="shrink-0 px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide border {{ $m->status === 'active' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700' }}">
                            <i
                                class="fa-solid {{ $m->status === 'active' ? 'fa-circle-check text-emerald-500' : 'fa-circle-exclamation text-rose-500' }} mr-0.5"></i>
                            {{ $m->status === 'active' ? 'Aktif' : 'Expired' }}
                        </span>
                    </div>

                    <!-- Meta Details: Tanggal Gabung & Habis Masa -->
                    <div
                        class="mt-3.5 pt-3 border-t border-slate-100 grid grid-cols-2 gap-3 text-xs bg-slate-50/70 -mx-4 px-4 py-2">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Tanggal
                                Join</span>
                            <span class="text-slate-700 font-semibold">
                                {{ \Carbon\Carbon::parse($m->join_date)->format('d M Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Masa
                                Berlaku Sampai</span>
                            <span class="text-slate-900 font-bold">
                                {{ $m->membership_end_date ? \Carbon\Carbon::parse($m->membership_end_date)->format('d M Y') : '-' }}
                            </span>
                            @if ($m->membership_end_date)
                                <span class="text-[10px] text-slate-500 block leading-tight mt-0.5">
                                    ({{ \Carbon\Carbon::parse($m->membership_end_date)->diffForHumans() }})
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-3 flex items-center justify-between">
                        <!-- Cabang Badge -->
                        <div>
                            @if ($m->gymkos)
                                <span
                                    class="px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-semibold">
                                    <i class="fa-solid fa-location-dot text-amber-600 mr-0.5"></i>
                                    {{ $m->gymkos->name }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Quick +1 Bulan Action -->
                            <form action="{{ route('employee.members.extend', $m->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Perpanjang masa aktif 1 Bulan untuk {{ $m->name }}?')"
                                    class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 active:bg-amber-200 text-amber-900 border border-amber-300 rounded-lg text-xs font-bold flex items-center gap-1 transition">
                                    <i class="fa-solid fa-bolt text-amber-600"></i> +1 Bulan
                                </button>
                            </form>

                            <!-- Edit Button -->
                            <a href="{{ route('employee.members.edit', $m->id) }}"
                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1 transition">
                                <i class="fa-regular fa-pen-to-square text-slate-500"></i> Edit
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-500 shadow-xs">
                    <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-400"></i>
                    <p class="text-xs font-medium">Tidak ada data member yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Custom Number Pagination -->
        @if ($members->hasPages())
            <div class="mt-6 pt-3 border-t border-slate-200 flex flex-col items-center gap-3">

                <!-- Result Count Text -->
                <div class="text-xs text-slate-500 font-medium">
                    Menampilkan <span class="text-slate-900 font-bold">{{ $members->firstItem() ?? 0 }}</span> - <span
                        class="text-slate-900 font-bold">{{ $members->lastItem() ?? 0 }}</span> dari total <span
                        class="text-slate-900 font-bold">{{ $members->total() }}</span> member
                </div>

                <!-- Pagination Number Container -->
                <div class="w-full flex justify-center">
                    <nav role="navigation" aria-label="Pagination Navigation"
                        class="inline-flex items-center rounded-xl bg-white border border-slate-200 p-1 shadow-xs max-w-full overflow-x-auto no-scrollbar">

                        {{-- Previous Page Link --}}
                        @if ($members->onFirstPage())
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 text-xs cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </span>
                        @else
                            <a href="{{ $members->previousPageUrl() }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 text-xs transition">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($members->getUrlRange(max(1, $members->currentPage() - 2), min($members->lastPage(), $members->currentPage() + 2)) as $page => $url)
                            @if ($page == $members->currentPage())
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500 text-slate-950 font-bold text-xs shadow-xs">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 text-xs transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($members->hasMorePages())
                            <a href="{{ $members->nextPageUrl() }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 text-xs transition">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        @else
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 text-xs cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </span>
                        @endif

                    </nav>
                </div>
            </div>
        @endif

    </main>

</body>

</html>
