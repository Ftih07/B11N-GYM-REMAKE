<nav class="bg-neutral-900 border-b border-neutral-800 sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">

        {{-- 1. LOGO (KIRI) --}}
        <div class="flex-shrink-0 flex items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                {{-- Ganti src logo sesuai asetmu --}}
                <img class="h-10 w-auto object-contain transition transform group-hover:scale-110" src="{{ asset('assets/Logo/empire.png') }}" alt="B1NG Logo">
                <span class="font-header font-bold text-xl uppercase tracking-wider text-white">
                    B1NG <span class="text-red-600">Empire</span>
                </span>
            </a>
        </div>

        {{-- DESKTOP MENU --}}
        <div class="hidden md:flex items-center gap-6 font-body text-sm font-medium tracking-wide">
            {{-- Tambahan: Link Balik ke Website Utama --}}
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-white transition flex items-center gap-1 text-xs uppercase mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Website Utama
            </a>

            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-red-500 font-bold' : 'text-gray-400 hover:text-white' }} transition uppercase">
                Dashboard
            </a>
            <a href="{{ route('attendance') }}" class="{{ request()->routeIs('attendance') ? 'text-red-500 font-bold' : 'text-gray-400 hover:text-white' }} transition uppercase">
                Riwayat Absensi
            </a>
            <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'text-red-500 font-bold' : 'text-gray-400 hover:text-white' }} transition uppercase">
                Profil
            </a>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-gray-500 hover:text-red-500 transition ml-4 border-l border-gray-700 pl-6">
                    <span class="text-xs uppercase font-bold">Sign Out</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- MOBILE MENU BUTTON --}}
        <div class="md:hidden">
            <button id="dashboard-mobile-btn" class="text-white focus:outline-none hover:text-red-500 transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- MOBILE MENU CONTENT (Slide Down) --}}
    <div id="dashboard-mobile-menu" class="hidden md:hidden bg-neutral-900 border-t border-neutral-800 shadow-xl absolute w-full left-0 z-40">
        <div class="px-6 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block text-gray-500 hover:text-white text-xs uppercase font-bold tracking-wider mb-4">
                ← Kembali ke Website
            </a>

            <a href="{{ route('dashboard') }}" class="block {{ request()->routeIs('dashboard') ? 'text-red-500 font-bold' : 'text-gray-300' }} hover:text-white text-sm uppercase tracking-wide py-2 border-b border-neutral-800">
                Dashboard
            </a>
            <a href="{{ route('attendance') }}" class="block {{ request()->routeIs('attendance') ? 'text-red-500 font-bold' : 'text-gray-300' }} hover:text-white text-sm uppercase tracking-wide py-2 border-b border-neutral-800">
                Riwayat Absensi
            </a>
            <a href="{{ route('profile') }}" class="block {{ request()->routeIs('profile') ? 'text-red-500 font-bold' : 'text-gray-300' }} hover:text-white text-sm uppercase tracking-wide py-2 border-b border-neutral-800">
                Profil Saya
            </a>

            <form action="{{ route('logout') }}" method="POST" class="pt-2">
                @csrf
                <button type="submit" class="w-full text-left text-red-500 hover:text-red-400 font-bold uppercase text-sm py-2">
                    Sign Out / Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    // Logic Toggle Mobile Menu Dashboard
    const dashBtn = document.getElementById('dashboard-mobile-btn');
    const dashMenu = document.getElementById('dashboard-mobile-menu');

    if (dashBtn && dashMenu) {
        dashBtn.addEventListener('click', () => {
            dashMenu.classList.toggle('hidden');
        });
    }
</script>