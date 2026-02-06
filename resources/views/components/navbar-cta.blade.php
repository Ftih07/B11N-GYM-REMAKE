{{-- NAVIGASI UTAMA --}}
<nav class="fixed top-0 left-0 w-full z-50 bg-neutral-900/95 backdrop-blur-md border-b border-neutral-800 shadow-lg transition-all duration-300 h-20 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
        <div class="flex items-center justify-between h-full">

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

            {{-- 2. DESKTOP MENU (TENGAH) --}}
            <div class="hidden md:flex flex-1 justify-center">
                <div class="flex items-center space-x-6">
                    @foreach($navMenus as $menu)
                    <div class="relative group h-20 flex items-center">
                        <a href="{{ route($menu['route']) }}"
                            class="font-header uppercase text-sm font-bold tracking-widest py-2 flex items-center gap-1 transition-colors
                           {{ Route::currentRouteName() === $menu['route'] ? 'text-red-500' : 'text-gray-300 hover:text-white' }}">
                            {{ $menu['label'] }}
                            @if(!empty($menu['submenu']))
                            <svg class="w-3 h-3 opacity-50 group-hover:opacity-100 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            @endif
                        </a>

                        {{-- Dropdown Desktop --}}
                        @if(!empty($menu['submenu']))
                        <div class="absolute top-[80%] left-0 w-56 bg-neutral-800 border border-neutral-700 shadow-xl rounded-b-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:top-full transition-all duration-300 overflow-hidden z-50">
                            <div class="py-2">
                                @foreach($menu['submenu'] as $sub)
                                <a href="{{ $sub['url'] }}" class="block px-4 py-3 text-sm text-gray-400 hover:bg-red-600 hover:text-white transition-colors border-b border-neutral-700 last:border-0">
                                    {{ $sub['label'] }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. LOGIN / PROFILE (KANAN - DESKTOP) --}}
            <div class="hidden md:flex items-center gap-4">
                @guest
                <a href="{{ route('login') }}" class="px-5 py-2 border border-red-600 text-red-500 hover:bg-red-600 hover:text-white rounded font-header font-bold uppercase tracking-wider text-sm transition shadow-[0_0_10px_rgba(220,38,38,0.2)] hover:shadow-[0_0_20px_rgba(220,38,38,0.6)]">
                    Login Member
                </a>
                @else
                {{-- Dropdown Profile --}}
                <div class="relative group h-20 flex items-center">
                    <button class="flex items-center gap-3 focus:outline-none">
                        <div class="text-right hidden xl:block">
                            <p class="text-sm font-bold text-white uppercase font-header">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">Member Area</p>
                        </div>
                        <img class="h-10 w-10 rounded border-2 border-neutral-600 object-cover"
                            src="{{ Auth::user()->profile_picture ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=DC2626&color=fff' }}"
                            alt="">
                    </button>

                    {{-- Dropdown Content --}}
                    <div class="absolute top-[80%] right-0 w-48 bg-neutral-800 border border-neutral-700 shadow-xl rounded-b-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:top-full transition-all duration-300 z-50">
                        <div class="py-1">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">Dashboard</a>
                            <a href="{{ route('attendance') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">Riwayat Absensi</a>
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">Edit Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-900/30 font-bold">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endguest
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="relative w-10 h-10 flex items-center justify-center text-white hover:text-red-500 focus:outline-none">
                    {{-- Icon Bars --}}
                    <svg id="nav-icon-bars" class="w-6 h-6 transition-all duration-300 transform scale-100 opacity-100 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    {{-- Icon Times --}}
                    <svg id="nav-icon-times" class="w-6 h-6 transition-all duration-300 transform scale-0 opacity-0 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU (ACCORDION STYLE) --}}
    <div id="mobile-menu" class="hidden md:hidden bg-neutral-900 border-t border-neutral-800 absolute w-full left-0 shadow-2xl h-[calc(100vh-80px)] overflow-y-auto pb-20">

        {{-- BAGIAN SPESIAL MOBILE: MEMBER AREA (Paling Atas) --}}
        <div class="px-4 pt-6 pb-2">
            @guest
            <a href="{{ route('login') }}" class="block w-full text-center bg-red-600 text-white font-header font-bold py-3 rounded mb-4 uppercase tracking-wider hover:bg-red-700 transition shadow-lg shadow-red-900/50">
                LOGIN MEMBER AREA
            </a>
            @else
            <div class="flex items-center gap-3 p-4 bg-neutral-800 rounded-lg mb-4 border border-neutral-700">
                <img class="h-12 w-12 rounded object-cover" src="{{ Auth::user()->profile_picture ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=DC2626&color=fff' }}">
                <div>
                    <div class="text-base font-bold text-white font-header uppercase">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-green-400 font-medium tracking-wide">● Active Member</div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-6">
                <a href="{{ route('dashboard') }}" class="text-center py-3 bg-neutral-800 text-gray-300 rounded hover:bg-neutral-700 hover:text-white hover:border-red-500 border border-neutral-700 text-xs font-bold uppercase transition">
                    📊 Dashboard
                </a>
                <a href="{{ route('attendance') }}" class="text-center py-3 bg-neutral-800 text-gray-300 rounded hover:bg-neutral-700 hover:text-white hover:border-red-500 border border-neutral-700 text-xs font-bold uppercase transition">
                    📅 Riwayat Absensi
                </a>
            </div>
            <div class="border-b border-neutral-800 mb-4"></div>
            @endguest
        </div>

        {{-- LOOPING MENU UTAMA --}}
        <div class="px-4 space-y-1">
            @foreach($navMenus as $index => $menu)
            <div class="border-b border-neutral-800 last:border-0">
                @if(empty($menu['submenu']))
                {{-- Single Link --}}
                <a href="{{ route($menu['route']) }}" class="block px-3 py-4 text-sm font-header uppercase tracking-widest {{ Route::currentRouteName() === $menu['route'] ? 'text-red-500 font-bold' : 'text-gray-400 hover:text-white' }}">
                    {{ $menu['label'] }}
                </a>
                @else
                {{-- Accordion Button --}}
                <button onclick="toggleMobileSubmenu('submenu-{{ $index }}')" class="w-full flex justify-between items-center px-3 py-4 text-sm font-header uppercase tracking-widest text-gray-400 hover:text-white focus:outline-none group">
                    <span>{{ $menu['label'] }}</span>
                    <svg id="icon-submenu-{{ $index }}" class="w-3 h-3 transition-transform duration-300 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                {{-- Submenu Content --}}
                <div id="submenu-{{ $index }}" class="hidden bg-neutral-950/50 pl-6 pr-3 py-2 space-y-1 rounded mb-2">
                    @foreach($menu['submenu'] as $sub)
                    <a href="{{ $sub['url'] }}" class="block py-2 text-sm font-sans text-gray-500 hover:text-red-400 transition">
                        <span class="text-red-600 mr-2">›</span> {{ $sub['label'] }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach

            @auth
            <form method="POST" action="{{ route('logout') }}" class="pt-6 pb-10">
                @csrf
                <button type="submit" class="block w-full text-center px-3 py-3 rounded border border-red-900/50 text-red-500 hover:bg-red-900/20 hover:text-red-400 font-bold uppercase text-sm transition">
                    Logout / Keluar
                </button>
            </form>
            @endauth
        </div>
    </div>
</nav>

{{-- JAVASCRIPT BAWAANMU (Sedikit disesuaikan ID-nya) --}}
<script>
    // --- Navbar Mobile Toggle ---
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const iconBars = document.getElementById('nav-icon-bars');
    const iconTimes = document.getElementById('nav-icon-times');

    if (btn && menu) {
        btn.addEventListener('click', () => {
            const isHidden = menu.classList.toggle('hidden');
            if (isHidden) {
                // Menu Tutup
                iconBars.classList.remove('scale-0', 'opacity-0');
                iconBars.classList.add('scale-100', 'opacity-100');
                iconTimes.classList.remove('scale-100', 'opacity-100');
                iconTimes.classList.add('scale-0', 'opacity-0');
            } else {
                // Menu Buka
                iconBars.classList.remove('scale-100', 'opacity-100');
                iconBars.classList.add('scale-0', 'opacity-0');
                iconTimes.classList.remove('scale-0', 'opacity-0');
                iconTimes.classList.add('scale-100', 'opacity-100');
            }
        });
    }

    // --- Mobile Submenu Accordion ---
    function toggleMobileSubmenu(id) {
        const submenu = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>