@extends('layouts.main')

@section('title')
{{ request('category') ? 'Kategori: ' . request('category') : 'Daftar Peralatan Gym Lengkap' }} - B1NG Empire
@endsection

@section('meta_description')
Katalog lengkap peralatan fitness B1NG Empire. Temukan alat Cardio, Strength, dan Machine terbaik untuk latihanmu di Purwokerto.
@endsection

@section('meta_keywords', 'alat gym purwokerto, treadmill, smith machine, leg press, b1ng gym equipment')

{{-- Schema Markup CollectionPage --}}
@push('json-ld')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "Koleksi Peralatan B1NG Empire",
        "description": "Daftar peralatan gym lengkap mulai dari Cardio, Strength, hingga Machine.",
        "url": "{{ url()->current() }}"
    }
</script>
@endpush

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

<nav class="fixed top-0 left-0 w-full z-50 bg-white/95 dark:bg-neutral-900/95 backdrop-blur-md border-b border-gray-200 dark:border-neutral-800 shadow-sm transition-all duration-300 h-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
        <div class="flex items-center justify-between h-full">

            {{-- LOGO --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    {{-- Logo Image --}}
                    <img class="h-10 w-auto object-contain transition-transform group-hover:scale-105" src="{{ asset('assets/Logo/empire.png') }}" alt="B1NG Empire Logo">
                    {{-- Logo Text: Putih di Dark Mode, Merah saat Hover --}}
                    <span class="font-heading font-bold text-xl uppercase tracking-wider sm:block text-gray-900 dark:text-gray-100 group-hover:text-red-600 dark:group-hover:text-red-500 transition-colors">
                        B1NG Empire
                    </span>
                </a>
            </div>

            {{-- DESKTOP MENU (LOOPING) --}}
            <div class="hidden md:block">
                <div class="ml-10 flex items-center space-x-6">
                    @foreach($navMenus as $menu)
                    <div class="relative group h-20 flex items-center">
                        {{-- Parent Link --}}
                        {{-- Logic Warna: 
                             - Jika Aktif: Merah (text-red-600)
                             - Default Dark: Abu terang (dark:text-gray-300)
                             - Hover: Merah (hover:text-red-600) 
                        --}}
                        <a href="{{ route($menu['route']) }}"
                            class="font-heading uppercase text-sm tracking-widest py-2 flex items-center gap-1 transition-colors duration-200
                                  {{ Route::currentRouteName() === $menu['route'] 
                                     ? 'text-red-600 dark:text-red-500 font-bold' 
                                     : 'text-gray-800 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500' 
                                  }}">
                            {{ $menu['label'] }}

                            @if(!empty($menu['submenu']))
                            <i class="fas fa-chevron-down text-[10px] opacity-50 group-hover:opacity-100 transition-opacity"></i>
                            @endif
                        </a>

                        {{-- Dropdown Menu --}}
                        @if(!empty($menu['submenu']))
                        {{--
                            Dropdown Container:
                            - dark:bg-neutral-800 (Lebih terang dikit dari navbar)
                            - dark:border-red-600 (Aksen border atas merah)
                        --}}
                        <div class="absolute top-[80%] left-0 w-64 bg-white dark:bg-neutral-800 shadow-xl rounded-b-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:top-full transition-all duration-300 border-t-4 border-red-600 overflow-hidden">
                            <div class="py-2">
                                @foreach($menu['submenu'] as $sub)
                                <a href="{{ $sub['url'] }}"
                                    class="block px-4 py-3 text-sm font-sans border-b last:border-0 transition-colors
                                          text-gray-700 dark:text-gray-300 border-gray-100 dark:border-neutral-700
                                          hover:bg-gray-50 dark:hover:bg-neutral-700 
                                          hover:text-red-600 dark:hover:text-red-500">
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

            {{-- MOBILE MENU BUTTON --}}
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="relative w-10 h-10 flex items-center justify-center text-gray-800 dark:text-gray-100 hover:text-red-600 dark:hover:text-red-500 focus:outline-none transition-colors">
                    <i id="nav-icon-bars" class="fas fa-bars text-2xl transition-all duration-300 transform scale-100 opacity-100 absolute"></i>
                    <i id="nav-icon-times" class="fas fa-times text-2xl transition-all duration-300 transform scale-0 opacity-0 absolute"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU (ACCORDION STYLE) --}}
    {{-- Background Mobile: dark:bg-neutral-900 --}}
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-800 absolute w-full left-0 shadow-lg h-[calc(100vh-80px)] overflow-y-auto pb-20 transition-colors duration-300">
        <div class="px-4 pt-2 pb-4 space-y-1">
            @foreach($navMenus as $index => $menu)
            <div class="border-b border-gray-100 dark:border-neutral-800 last:border-0">

                @if(empty($menu['submenu']))
                {{-- Link Tanpa Submenu --}}
                <a href="{{ route($menu['route']) }}"
                    class="block px-3 py-4 text-base font-heading uppercase tracking-widest transition-colors
                          {{ Route::currentRouteName() === $menu['route'] 
                             ? 'text-red-600 dark:text-red-500 font-bold' 
                             : 'text-gray-800 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-500' 
                          }}">
                    {{ $menu['label'] }}
                </a>

                @else
                {{-- Tombol Accordion --}}
                <button onclick="toggleMobileSubmenu('submenu-{{ $index }}')"
                    class="w-full flex justify-between items-center px-3 py-4 text-base font-heading uppercase tracking-widest transition-colors focus:outline-none
                               text-gray-800 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-500">
                    <span>{{ $menu['label'] }}</span>
                    <i id="icon-submenu-{{ $index }}" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                </button>

                {{-- Submenu Mobile Container --}}
                {{-- Background Submenu: dark:bg-neutral-950 (Lebih gelap dari menu utama untuk kedalaman) --}}
                <div id="submenu-{{ $index }}" class="hidden bg-gray-50 dark:bg-neutral-950 pl-6 pr-3 py-2 space-y-2 rounded-md mb-2">
                    @foreach($menu['submenu'] as $sub)
                    <a href="{{ $sub['url'] }}" class="block py-2 text-sm font-sans text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">
                        <i class="fas fa-angle-right mr-2 text-red-500 text-xs"></i> {{ $sub['label'] }}
                    </a>
                    @endforeach
                </div>
                @endif

            </div>
            @endforeach
        </div>
    </div>
</nav>

{{-- SCRIPT TETAP SAMA --}}
<script>
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const iconBars = document.getElementById('nav-icon-bars');
    const iconTimes = document.getElementById('nav-icon-times');

    if (btn && menu) {
        btn.addEventListener('click', () => {
            const isHidden = menu.classList.toggle('hidden');
            if (isHidden) {
                iconBars.classList.remove('scale-0', 'opacity-0');
                iconBars.classList.add('scale-100', 'opacity-100');
                iconTimes.classList.remove('scale-100', 'opacity-100');
                iconTimes.classList.add('scale-0', 'opacity-0');
            } else {
                iconBars.classList.remove('scale-100', 'opacity-100');
                iconBars.classList.add('scale-0', 'opacity-0');
                iconTimes.classList.remove('scale-0', 'opacity-0');
                iconTimes.classList.add('scale-100', 'opacity-100');
            }
        });
    }

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

@section('content')

{{--
    =============================================
    1. HERO SECTION (DARK MODE)
    ============================================= 
--}}
<div class="relative bg-neutral-900 pt-36 pb-24 border-b-4 border-red-600">
    {{-- Background Image with Overlay --}}
    <div class="absolute inset-0 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop"
            class="w-full h-full object-cover opacity-20 grayscale">
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-transparent to-neutral-900"></div>
    </div>

    <div class="relative container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tight mb-4">
            Gym <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-yellow-500">Arsenal</span>
        </h1>
        <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto font-light">
            Eksplorasi koleksi mesin tempur standar internasional kami. Pilih senjatamu, mulai transformasi.
        </p>
    </div>
</div>

{{--
    =============================================
    2. MAIN CONTENT (LIGHT MODE)
    ============================================= 
--}}
<div class="bg-gray-50 min-h-screen pb-20">
    <div class="container mx-auto px-4 relative -mt-8 z-10">

        {{-- FILTER BAR (Floating Card) --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 mb-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">

                {{-- Left: Category Pills --}}
                <div class="flex flex-wrap justify-center md:justify-start gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
                        class="px-5 py-2 rounded-full font-bold text-xs uppercase tracking-wider transition border
                       {{ !request('category') 
                          ? 'bg-black text-white border-black shadow-md transform scale-105' 
                          : 'bg-gray-100 text-gray-500 border-transparent hover:bg-gray-200' }}">
                        All Units
                    </a>

                    @foreach(['Cardio', 'Strength', 'Machine'] as $cat)
                    <a href="{{ request()->fullUrlWithQuery(['category' => $cat]) }}"
                        class="px-5 py-2 rounded-full font-bold text-xs uppercase tracking-wider transition border
                       {{ request('category') == $cat 
                          ? 'bg-red-600 text-white border-red-600 shadow-md transform scale-105' 
                          : 'bg-gray-100 text-gray-500 border-transparent hover:bg-gray-200' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                </div>

                {{-- Right: Location Dropdown --}}
                <div class="relative w-full md:w-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ri-map-pin-line text-gray-400"></i>
                    </div>
                    <select onchange="window.location.href = this.value"
                        class="appearance-none w-full md:w-64 bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold py-2.5 pl-10 pr-8 rounded-lg focus:outline-none focus:bg-white focus:border-red-500 transition cursor-pointer">

                        <option value="{{ request()->fullUrlWithQuery(['gym' => null]) }}" {{ !request('gym') ? 'selected' : '' }}>
                            Semua Lokasi
                        </option>
                        @foreach($gyms as $gym)
                        <option value="{{ request()->fullUrlWithQuery(['gym' => $gym->id]) }}" {{ request('gym') == $gym->id ? 'selected' : '' }}>
                            {{ $gym->name }}
                        </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="ri-arrow-down-s-fill text-xs"></i>
                    </div>
                </div>

            </div>
        </div>

        {{-- GRID SYSTEM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($equipments as $item)

            {{-- Logic Warna Label per Kartu --}}
            @php
            $gymName = strtoupper($item->gymkos->name ?? '');
            $isKing = \Illuminate\Support\Str::contains($gymName, 'KING');

            // Set warna badge lokasi berdasarkan brand
            $badgeBg = $isKing ? 'bg-yellow-500 text-black' : 'bg-red-600 text-white';
            $hoverBorder = $isKing ? 'group-hover:border-yellow-500' : 'group-hover:border-red-600';

            $thumbnail = $item->gallery->first()
            ? asset('storage/' . $item->gallery->first()->file_path)
            : 'https://placehold.co/600x400/eee/999?text=No+Image';
            @endphp

            {{-- CARD ITEM --}}
            <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 {{ $hoverBorder }}">

                {{-- Image Area --}}
                <div class="relative h-56 overflow-hidden bg-gray-100">
                    <img src="{{ $thumbnail }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">

                    {{-- Overlay Gradient saat Hover --}}
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition duration-300"></div>

                    {{-- Badge Kategori (Pojok Kiri Atas) --}}
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur text-gray-800 text-[10px] font-black px-3 py-1 rounded uppercase tracking-widest shadow-sm">
                            {{ $item->category }}
                        </span>
                    </div>

                    {{-- Play Icon (Center) --}}
                    <a href="{{ route('gym.equipments.show', $item->slug) }}" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 transform scale-50 group-hover:scale-100">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <i class="ri-play-fill text-xl text-black"></i>
                        </div>
                    </a>
                </div>

                {{-- Content Area --}}
                <div class="p-5">
                    {{-- Badge Lokasi (Dynamic Color) --}}
                    <div class="flex justify-between items-start mb-2">
                        <span class="{{ $badgeBg }} text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                            {{ $item->gymkos->name ?? 'Gym N/A' }}
                        </span>

                        {{-- Status Dot --}}
                        @if($item->status == 'active')
                        <span class="flex h-2 w-2 relative" title="Active">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-black text-gray-800 uppercase leading-tight mb-2 group-hover:text-gray-600 transition">
                        <a href="{{ route('gym.equipments.show', $item->slug) }}">
                            {{ $item->name }}
                        </a>
                    </h3>

                    <p class="text-gray-500 text-sm line-clamp-2 mb-4">
                        {{ $item->description }}
                    </p>

                    <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                        <span class="text-xs text-gray-400 font-medium">Lihat Detail</span>
                        <a href="{{ route('gym.equipments.show', $item->slug) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 hover:bg-black hover:text-white transition text-gray-600">
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty

            {{-- Empty State --}}
            <div class="col-span-full py-16 text-center">
                <div class="inline-block p-6 rounded-full bg-gray-100 mb-4">
                    <i class="ri-database-2-line text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 uppercase">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 mt-2">Tidak ada alat yang sesuai dengan filter pencarianmu.</p>
                <a href="{{ route('gym.equipments.index') }}" class="inline-block mt-6 px-6 py-2 bg-black text-white text-sm font-bold uppercase tracking-wider rounded hover:bg-gray-800 transition">
                    Reset Filter
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-12 flex justify-center">
            {{ $equipments->links() }}
        </div>
    </div>
</div>
@endsection