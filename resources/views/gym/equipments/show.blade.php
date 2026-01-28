@extends('layouts.main')

@section('content')

{{--
    =============================================
    DYNAMIC BRANDING LOGIC
    =============================================
--}}
@php
$gymName = $equipment->gymkos->name ?? '';
$isKing = \Illuminate\Support\Str::contains(strtoupper($gymName), 'K1NG GYM');

// =====================
// BRAND ROUTE TARGET
// =====================
$gymRoute = $isKing ? route('gym.king') : route('gym.biin');
$membershipUrl = $gymRoute . '#membership';

// Warna Utama
$brandColor = $isKing ? 'text-yellow-600' : 'text-red-600';
$brandBg = $isKing ? 'bg-yellow-500' : 'bg-red-600'; // Untuk tombol/badge solid
$brandBorder = $isKing ? 'border-yellow-500' : 'border-red-600';

// Warna Hover
$hoverText = $isKing ? 'hover:text-yellow-600' : 'hover:text-red-600';
$groupHoverText = $isKing ? 'group-hover:text-yellow-600' : 'group-hover:text-red-600';
@endphp

@section('title')
{{ $equipment->name }} - Panduan Latihan | {{ $isKing ? 'K1NG GYM' : 'B11N GYM' }}
@endsection

@section('meta_description')
Panduan lengkap menggunakan {{ $equipment->name }}. {{ \Illuminate\Support\Str::limit($equipment->description, 120) }}.
@endsection

@push('json-ld')
{{-- Schema Markup tetap sama --}}
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": ["Product", "VideoObject"],
        "name": "{{ $equipment->name }}",
        "description": "{{ $equipment->description }}",
        "thumbnailUrl": ["{{ $equipment->gallery->first() ? asset('storage/' . $equipment->gallery->first()->file_path) : asset('assets/Logo/biin.png') }}"],
        "uploadDate": "{{ $equipment->created_at->toIso8601String() }}",
        "contentUrl": "{{ $equipment->video_url ?? url()->current() }}",
        "embedUrl": "{{ $equipment->video_url }}"
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

{{-- BACKGROUND UTAMA: ABU-ABU TERANG (Clean Look) --}}
<div class="bg-gray-50 min-h-screen font-sans pb-20 pt-20">

    {{-- HERO HEADER: Tetap Gelap agar kontras dengan logo & judul --}}
    <div class="bg-black text-white py-16 relative overflow-hidden">
        {{-- Aksen Background Samar --}}
        <div class="absolute top-0 right-0 w-64 h-64 {{ $brandBg }} opacity-10 rounded-full blur-3xl -translate-y-10 translate-x-10"></div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-3">
                {{ $equipment->name }}
            </h1>

            {{-- Breadcrumb --}}
            <div class="flex justify-center items-center gap-2 text-sm text-gray-400 uppercase tracking-widest font-bold">
                <a href="/" class="hover:text-white transition">Home</a>
                <span>/</span>
                <a href="{{ route('gym.equipments.index') }}" class="hover:text-white transition">Equipments</a>
                <span>/</span>
                <span class="{{ $brandColor }}">{{ $equipment->name }}</span>
            </div>
        </div>
    </div>

    {{-- CONTENT CONTAINER --}}
    <div class="container mx-auto px-4 -mt-10 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT COLUMN: VIDEO & DESCRIPTION --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- VIDEO PLAYER: Frame Hitam agar fokus, tapi shadow lembut --}}
                <div class="bg-black rounded-xl overflow-hidden shadow-2xl border-4 border-white">
                    <div class="aspect-video relative">
                        @if($equipment->video_url)
                        <iframe class="w-full h-full"
                            src="{{ $equipment->video_url }}"
                            title="Tutorial Video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                        @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-500 bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="uppercase font-bold tracking-wider text-sm">Video Belum Tersedia</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- DESCRIPTION CARD: Putih Bersih (Sesuai screenshot 'Tentang Kami') --}}
                <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="h-6 w-1 {{ $brandBg }} rounded-full"></div>
                        <h3 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Fungsi & Cara Pakai</h3>
                    </div>

                    <p class="text-gray-600 leading-relaxed text-lg">
                        {{ $equipment->description }}
                    </p>

                    {{-- METADATA GRID: Style 'Fasilitas' (Icon Bulat / Box Rapi) --}}
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">

                        {{-- Meta 1 --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-center hover:shadow-md transition">
                            <div class="inline-flex items-center justify-center w-10 h-10 mb-2 rounded-full bg-white shadow-sm text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest mb-1">Kategori</span>
                            <span class="font-bold text-gray-800">{{ $equipment->category }}</span>
                        </div>

                        {{-- Meta 2 --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-center hover:shadow-md transition">
                            <div class="inline-flex items-center justify-center w-10 h-10 mb-2 rounded-full bg-white shadow-sm text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest mb-1">Status</span>
                            <span class="font-bold uppercase {{ $equipment->status == 'active' ? 'text-green-600' : 'text-red-500' }}">
                                {{ ucfirst($equipment->status) }}
                            </span>
                        </div>

                        {{-- Meta 3 --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-center hover:shadow-md transition">
                            <div class="inline-flex items-center justify-center w-10 h-10 mb-2 rounded-full bg-white shadow-sm text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest mb-1">Lokasi</span>
                            <span class="font-bold text-gray-800">{{ $equipment->gymkos->name ?? '-' }}</span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: SIDEBAR --}}
            <div class="space-y-8">

                {{-- GALLERY WIDGET --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 uppercase tracking-tight">
                        Galeri Foto
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        @forelse($equipment->gallery as $photo)
                        <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 group cursor-pointer relative" onclick="window.open('{{ asset('storage/'.$photo->file_path) }}', '_blank')">
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition z-10"></div>
                            <img src="{{ asset('storage/' . $photo->file_path) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        @empty
                        <div class="col-span-2 py-6 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-xs text-gray-400 italic">Tidak ada foto tambahan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- RELATED EQUIPMENT WIDGET --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 uppercase tracking-tight">
                        Alat Terkait
                    </h3>
                    <div class="space-y-4">
                        @foreach($relatedEquipments as $related)
                        <a href="{{ route('gym.equipments.show', $related->slug) }}" class="flex items-center gap-4 group p-2 hover:bg-gray-50 transition rounded-lg border border-transparent hover:border-gray-100">
                            {{-- Thumbnail --}}
                            <div class="w-16 h-16 bg-gray-200 rounded-md flex-shrink-0 overflow-hidden shadow-sm">
                                @php $img = $related->gallery->first() ? asset('storage/'.$related->gallery->first()->file_path) : 'https://placehold.co/100?text=No+Img'; @endphp
                                <img src="{{ $img }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-300">
                            </div>

                            {{-- Info --}}
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm {{ $groupHoverText }} transition uppercase tracking-wide">
                                    {{ $related->name }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $related->category }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- CTA: Gabung Member --}}
                <div class="{{ $brandBg }} rounded-xl p-6 text-center shadow-lg text-white">
                    <h4 class="font-black uppercase text-xl mb-1">Mulai Sekarang</h4>
                    <p class="text-white/80 text-sm mb-4">Dapatkan akses ke alat ini dan instruktur profesional.</p>
                    <a href="{{ $membershipUrl }}" class="scroll-smooth inline-block bg-white {{ $brandColor }} px-6 py-2 rounded-full text-sm font-bold uppercase tracking-wider hover:bg-gray-100 transition shadow-md">
                        Daftar Member
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection