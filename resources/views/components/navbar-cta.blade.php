@php
// DEFINISI STRUKTUR MENU (Ganti URL sesuai route/anchor aslimu)
$navMenus = [
[
'label' => 'Home',
'route' => 'home',
'submenu' => [
['label' => 'Tentang Kami', 'url' => route('home').'#about'],
['label' => 'Ekosistem Kami', 'url' => route('home').'#ecosystem'],
['label' => 'Store', 'url' => route('home').'#store'],
['label' => 'Blog', 'url' => route('home').'#blog'],
['label' => 'Hubungi Kami', 'url' => route('home').'#contact'],
]
],
[
'label' => 'B11N Gym',
'route' => 'gym.biin',
'submenu' => [
['label' => 'Tentang Kami', 'url' => route('gym.biin').'#about'],
['label' => 'Fasilitas', 'url' => route('gym.biin').'#facility'],
['label' => 'Training Program', 'url' => route('gym.biin').'#training'],
['label' => 'Equipments & Tutorials', 'url' => route('gym.biin').'#equipments'],
['label' => 'Trainer', 'url' => route('gym.biin').'#trainer'],
['label' => 'Membership', 'url' => route('gym.biin').'#membership'],
['label' => 'Store', 'url' => route('gym.biin').'#store'],
['label' => 'Testimonial', 'url' => route('gym.biin').'#testimonial'],
['label' => 'Blog', 'url' => route('gym.biin').'#blog'],
['label' => 'Contact Us', 'url' => route('gym.biin').'#contact'],
]
],
[
'label' => 'K1NG Gym',
'route' => 'gym.king',
'submenu' => [
['label' => 'Tentang Kami', 'url' => route('gym.king').'#about'],
['label' => 'Fasilitas', 'url' => route('gym.king').'#facilities'],
['label' => 'Training Program', 'url' => route('gym.king').'#program'],
['label' => 'Equipments & Tutorials', 'url' => route('gym.king').'#equipments'],
['label' => 'Trainer', 'url' => route('gym.king').'#trainer'],
['label' => 'Membership', 'url' => route('gym.king').'#membership'],
['label' => 'Store', 'url' => route('gym.king').'#store'],
['label' => 'Testimonial', 'url' => route('gym.king').'#testimonials'],
['label' => 'Blog', 'url' => route('gym.king').'#blog'],
['label' => 'Contact Us', 'url' => route('gym.king').'#contact'],
]
],
[
'label' => 'Kost Istana 03',
'route' => 'kost',
'submenu' => [
['label' => 'Tentang Kami', 'url' => route('kost').'#about'],
['label' => 'Tipe Kamar', 'url' => route('kost').'#room'],
['label' => 'Fasilitas', 'url' => route('kost').'#feature'],
['label' => 'Testimonials', 'url' => route('kost').'#testimonials'],
['label' => 'Gallery', 'url' => route('kost').'#gallery'],
['label' => 'Pemesanan', 'url' => route('kost').'#booking'],
['label' => 'Blog', 'url' => route('kost').'#blog'],
['label' => 'Hubungi Kami', 'url' => route('kost').'#contact'],
]
],
[
'label' => 'Store',
'route' => 'store.biin-king',
'submenu' => [] // Kosong karena direct link
],
[
'label' => 'Blog',
'route' => 'blogs.index',
'submenu' => [] // Kosong karena direct link
],
[
'label' => 'Support',
'route' => 'maintenance.create',
'submenu' => [
['label' => 'Laporan Kerusakan', 'url' => route('maintenance.create')],
['label' => 'Survey Pengunjung', 'url' => route('survey.index')],
]
],
];
@endphp

{{-- NAVIGASI UTAMA --}}
<nav class="fixed top-0 left-0 w-full z-50 bg-white/95 dark:bg-brand-black/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 shadow-sm transition-all duration-300 h-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
        <div class="flex items-center justify-between h-full">

            {{-- LOGO --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img class="h-10 w-auto object-contain" src="{{ asset('assets/Logo/empire.png') }}" alt="B1NG Empire Logo">
                    <span class="font-heading font-bold text-xl uppercase tracking-wider sm:block">B1NG Empire</span>
                </a>
            </div>

            {{-- DESKTOP MENU (LOOPING) --}}
            <div class="hidden md:block">
                <div class="ml-10 flex items-center space-x-6">
                    @foreach($navMenus as $menu)
                    <div class="relative group h-20 flex items-center">
                        {{-- Parent Link --}}
                        <a href="{{ route($menu['route']) }}"
                            class="font-heading uppercase text-sm tracking-widest hover:text-brand-red transition-colors py-2 flex items-center gap-1
                               {{ Route::currentRouteName() === $menu['route'] ? 'text-brand-red font-bold' : 'text-gray-800 dark:text-white' }}">
                            {{ $menu['label'] }}
                            @if(!empty($menu['submenu']))
                            <i class="fas fa-chevron-down text-[10px] opacity-50 group-hover:opacity-100 transition-opacity"></i>
                            @endif
                        </a>

                        {{-- Dropdown Menu (Hanya muncul jika ada submenu) --}}
                        @if(!empty($menu['submenu']))
                        <div class="absolute top-[80%] left-0 w-64 bg-white dark:bg-brand-gray shadow-xl rounded-b-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:top-full transition-all duration-300 border-t-4 border-brand-red overflow-hidden">
                            <div class="py-2">
                                @foreach($menu['submenu'] as $sub)
                                <a href="{{ $sub['url'] }}" class="block px-4 py-3 text-sm font-sans text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-brand-red transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
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

            {{-- MOBILE MENU BUTTON (UPDATE: ANIMASI BARS <-> TIMES) --}}
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="relative w-10 h-10 flex items-center justify-center text-gray-800 dark:text-white hover:text-brand-red focus:outline-none">
                    {{-- Icon Bars (Default Muncul) --}}
                    <i id="nav-icon-bars" class="fas fa-bars text-2xl transition-all duration-300 transform scale-100 opacity-100 absolute"></i>

                    {{-- Icon Times/Silang (Default Hidden) --}}
                    <i id="nav-icon-times" class="fas fa-times text-2xl transition-all duration-300 transform scale-0 opacity-0 absolute"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU (ACCORDION STYLE) --}}
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-brand-gray border-t border-gray-200 dark:border-gray-700 absolute w-full left-0 shadow-lg h-[calc(100vh-80px)] overflow-y-auto pb-20">
        <div class="px-4 pt-2 pb-4 space-y-1">
            @foreach($navMenus as $index => $menu)
            <div class="border-b border-gray-100 dark:border-gray-700 last:border-0">
                @if(empty($menu['submenu']))
                {{-- Jika tidak ada submenu, langsung link --}}
                <a href="{{ route($menu['route']) }}" class="block px-3 py-4 text-base font-heading uppercase tracking-widest {{ Route::currentRouteName() === $menu['route'] ? 'text-brand-red font-bold' : 'text-gray-800 dark:text-white' }}">
                    {{ $menu['label'] }}
                </a>
                @else
                {{-- Jika ada submenu, pakai tombol accordion --}}
                <button onclick="toggleMobileSubmenu('submenu-{{ $index }}')" class="w-full flex justify-between items-center px-3 py-4 text-base font-heading uppercase tracking-widest text-gray-800 dark:text-white hover:text-brand-red focus:outline-none">
                    <span>{{ $menu['label'] }}</span>
                    <i id="icon-submenu-{{ $index }}" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                </button>

                {{-- Container Submenu Mobile --}}
                <div id="submenu-{{ $index }}" class="hidden bg-gray-50 dark:bg-black/20 pl-6 pr-3 py-2 space-y-2">
                    @foreach($menu['submenu'] as $sub)
                    <a href="{{ $sub['url'] }}" class="block py-2 text-sm font-sans text-gray-600 dark:text-gray-300 hover:text-brand-red">
                        <i class="fas fa-angle-right mr-2 text-brand-red text-xs"></i> {{ $sub['label'] }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</nav>

{{-- JAVASCRIPT --}}
<script>
    // --- Navbar Mobile Toggle (UPDATED) ---
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const iconBars = document.getElementById('nav-icon-bars');
    const iconTimes = document.getElementById('nav-icon-times');

    if (btn && menu) {
        btn.addEventListener('click', () => {
            const isHidden = menu.classList.toggle('hidden');

            if (isHidden) {
                // MENU TUTUP → tampilkan BARS
                iconBars.classList.remove('scale-0', 'opacity-0');
                iconBars.classList.add('scale-100', 'opacity-100');

                iconTimes.classList.remove('scale-100', 'opacity-100');
                iconTimes.classList.add('scale-0', 'opacity-0');
            } else {
                // MENU BUKA → tampilkan TIMES
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

    // --- CTA Floating ---
    let fabOpen = false;

    function toggleFab() {
        const fabMenu = document.getElementById('fab-menu');
        const iconOpen = document.getElementById('fab-icon-open');
        const iconClose = document.getElementById('fab-icon-close');
        const fabBtn = document.getElementById('fab-btn');

        fabOpen = !fabOpen;

        if (fabOpen) {
            fabMenu.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
            iconOpen.classList.add('opacity-0', 'scale-0');
            iconClose.classList.remove('opacity-0', 'scale-0');
            fabBtn.classList.add('ring-4', 'ring-red-300');
        } else {
            fabMenu.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
            iconOpen.classList.remove('opacity-0', 'scale-0');
            iconClose.classList.add('opacity-0', 'scale-0');
            fabBtn.classList.remove('ring-4', 'ring-red-300');
        }
    }

    document.addEventListener('click', function(event) {
        const container = document.getElementById('fab-btn').parentElement;
        if (!container.contains(event.target) && fabOpen) {
            toggleFab();
        }
    });
</script>