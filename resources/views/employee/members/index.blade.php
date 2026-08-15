<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Data Membership - Front Desk</title>
    <!-- Tailwind CSS CDN -->
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
                        filamentAmber: '#f59e0b',
                        filamentGold: '#d97706',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        /* Hide scrollbar on pagination container */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-customDark text-zinc-200 min-h-screen pb-16 antialiased">

    <!-- Top App Bar -->
    <header
        class="bg-cardDark border-b border-borderDark px-4 py-3.5 sticky top-0 z-40 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div
                class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 font-bold text-xs">
                <i class="fa-solid fa-dumbbell"></i>
            </div>
            <div>
                <p class="text-[10px] text-zinc-400 font-medium tracking-wide uppercase">Front Desk Desk</p>
                <h1 class="text-sm font-bold text-white tracking-tight">Data Membership</h1>
            </div>
        </div>

        <a href="{{ route('employee.members.create') }}"
            class="bg-amber-500 hover:bg-amber-600 active:scale-95 text-zinc-950 px-3.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition shadow-sm">
            <i class="fa-solid fa-plus text-[10px]"></i> Buat
        </a>
    </header>

    <main class="max-w-xl mx-auto px-4 mt-4">

        <!-- Flash Alert -->
        @if (session('success'))
            <div
                class="p-3 mb-3 bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 rounded-xl text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Search Bar with Search Button (Filament Style) -->
        <form method="GET" action="{{ route('employee.members.index') }}" class="mb-4">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Cari ID, Nama, No. HP..."
                        class="w-full bg-cardDark border border-borderDark rounded-xl py-2.5 pl-10 pr-9 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-amber-500/70 transition shadow-inner">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-zinc-500 text-xs"></i>

                    @if ($search)
                        <a href="{{ route('employee.members.index') }}"
                            class="absolute right-3 top-2.5 text-zinc-400 hover:text-white text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 active:scale-95 text-zinc-950 font-bold text-xs rounded-xl flex items-center gap-1.5 shadow-sm transition">
                    <i class="fa-solid fa-magnifying-glass text-[11px]"></i> Cari
                </button>
            </div>
        </form>

        <!-- Member List Container -->
        <div class="space-y-2.5">
            @forelse($members as $m)
                <div
                    class="bg-cardDark border border-borderDark/80 rounded-2xl p-3.5 shadow-sm transition active:border-zinc-700">

                    <!-- Top Row: Avatar, Nama, ID, Phone & Status -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <!-- Avatar Circular -->
                            @if ($m->picture)
                                <img src="{{ asset('storage/' . $m->picture) }}" alt="{{ $m->name }}"
                                    class="w-10 h-10 rounded-full object-cover shrink-0 border border-zinc-700">
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-sky-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($m->name, 0, 2)) }}
                                </div>
                            @endif

                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    @if ($m->member_code)
                                        <span
                                            class="px-1.5 py-0.5 rounded bg-zinc-800 border border-zinc-700 text-zinc-300 text-[10px] font-mono font-semibold">
                                            {{ $m->member_code }}
                                        </span>
                                    @endif
                                    <h2 class="font-bold text-white text-xs tracking-tight truncate">{{ $m->name }}
                                    </h2>
                                </div>
                                <p class="text-[11px] text-zinc-400 font-mono mt-0.5">
                                    <i class="fa-brands fa-whatsapp text-emerald-500 mr-0.5"></i>
                                    {{ $m->phone ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <span
                            class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-semibold tracking-wide border {{ $m->status === 'active' ? 'bg-emerald-950/80 border-emerald-500/30 text-emerald-400' : 'bg-rose-950/80 border-rose-500/30 text-rose-400' }}">
                            {{ $m->status === 'active' ? 'Aktif' : 'Expired' }}
                        </span>
                    </div>

                    <!-- Meta Details: Tgl Join, End Date -->
                    <div class="mt-3 pt-2.5 border-t border-zinc-800/80 grid grid-cols-2 gap-2 text-[11px]">
                        <div>
                            <span class="text-zinc-500 block text-[9px] uppercase font-semibold">Tgl Join</span>
                            <span
                                class="text-zinc-300 font-medium">{{ \Carbon\Carbon::parse($m->join_date)->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500 block text-[9px] uppercase font-semibold">End Date</span>
                            <span
                                class="text-zinc-200 font-semibold">{{ $m->membership_end_date ? \Carbon\Carbon::parse($m->membership_end_date)->format('d M Y') : '-' }}</span>
                            <span
                                class="text-[10px] text-zinc-500 block leading-none mt-0.5">{{ $m->membership_end_date ? \Carbon\Carbon::parse($m->membership_end_date)->diffForHumans() : '' }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-3 pt-2 border-t border-zinc-800/80 flex items-center justify-between">
                        <!-- Cabang Badge -->
                        <div>
                            @if ($m->gymkos)
                                <span
                                    class="px-2 py-0.5 rounded bg-amber-950/50 border border-amber-500/30 text-amber-400 text-[10px] font-semibold">
                                    {{ $m->gymkos->name }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Quick +1 Bulan Action -->
                            <form action="{{ route('employee.members.extend', $m->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Perpanjang 1 Bulan untuk {{ $m->name }}?')"
                                    class="px-2.5 py-1 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-lg text-[11px] font-semibold flex items-center gap-1 transition">
                                    <i class="fa-solid fa-bolt text-[9px]"></i> +1 Bulan
                                </button>
                            </form>

                            <!-- Edit Button -->
                            <a href="{{ route('employee.members.edit', $m->id) }}"
                                class="px-2.5 py-1 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-lg text-[11px] font-semibold flex items-center gap-1">
                                <i class="fa-regular fa-pen-to-square text-[10px]"></i> Edit
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="bg-cardDark border border-borderDark rounded-2xl p-8 text-center text-zinc-500">
                    <i class="fa-regular fa-user text-3xl mb-2 text-zinc-600"></i>
                    <p class="text-xs">Tidak ada data member yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Custom Filament-Style Number Pagination (Mobile & Desktop) -->
        @if ($members->hasPages())
            <div class="mt-6 pt-3 border-t border-borderDark flex flex-col items-center gap-3">

                <!-- Result Count Text -->
                <div class="text-[11px] text-zinc-400 font-medium">
                    Showing <span class="text-zinc-200 font-semibold">{{ $members->firstItem() ?? 0 }}</span> to <span
                        class="text-zinc-200 font-semibold">{{ $members->lastItem() ?? 0 }}</span> of <span
                        class="text-zinc-200 font-semibold">{{ $members->total() }}</span> results
                </div>

                <!-- Pagination Number Container -->
                <div class="w-full flex justify-center">
                    <nav role="navigation" aria-label="Pagination Navigation"
                        class="inline-flex items-center rounded-xl bg-cardDark border border-borderDark p-1 shadow-sm max-w-full overflow-x-auto no-scrollbar">

                        {{-- Previous Page Link --}}
                        @if ($members->onFirstPage())
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-600 text-xs cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </span>
                        @else
                            <a href="{{ $members->previousPageUrl() }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 text-xs transition">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($members->getUrlRange(max(1, $members->currentPage() - 2), min($members->lastPage(), $members->currentPage() + 2)) as $page => $url)
                            @if ($page == $members->currentPage())
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500 text-zinc-950 font-bold text-xs shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 text-xs transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($members->hasMorePages())
                            <a href="{{ $members->nextPageUrl() }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 text-xs transition">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        @else
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-600 text-xs cursor-not-allowed">
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
