<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- 1. TITLE: Branding Kuat + Keyword Utama --}}
    <title>B1NG EMPIRE - Ekosistem Gym & Kost Premium Purwokerto</title>

    {{-- 2. META DESCRIPTION: Menjelaskan hubungan antar bisnis --}}
    <meta name="description" content="B1NG EMPIRE adalah induk usaha dari B11N Gym, K1NG Gym, dan Kost Istana Merdeka 03. Solusi gaya hidup sehat dan hunian nyaman di Purwokerto dalam satu ekosistem.">

    {{-- 3. KEYWORDS: Gabungan semua brand --}}
    <meta name="keywords" content="b1ng empire, b11n gym, k1ng gym, kost istana merdeka 03, gym purwokerto, fitness center banyumas, kost putra purwokerto, bisnis gym purwokerto">

    <meta name="author" content="B1NG EMPIRE">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- 4. GEO TAGS (Menggunakan lokasi HQ/Pusat di Arcawinangun) --}}
    <meta name="geo.region" content="ID-JT" />
    <meta name="geo.placename" content="Purwokerto" />
    <meta name="geo.position" content="-7.4243;109.2391" />
    <meta name="ICBM" content="-7.4243, 109.2391" />

    {{-- 5. OPEN GRAPH (Brand Awareness) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="B1NG EMPIRE - Gym, Store & Residence">
    <meta property="og:description" content="Satu tujuan untuk kebugaran dan kenyamanan. Kunjungi B11N Gym, K1NG Gym, dan Kost Istana Merdeka 03.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/Logo/empire.png') }}">
    <meta property="og:site_name" content="B1NG EMPIRE">

    {{-- 6. SCHEMA MARKUP (Organization - Parent Company) --}}
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "B1NG EMPIRE",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('assets/Logo/empire.png') }}",
            "description": "Holding company yang bergerak di bidang kebugaran (Gym) dan properti (Kost) di Purwokerto.",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Jl. Masjid Baru, Arcawinangun",
                "addressLocality": "Purwokerto",
                "addressRegion": "Jawa Tengah",
                "addressCountry": "ID"
            },
            "subOrganization": [{
                    "@type": "ExerciseGym",
                    "name": "B11N Gym",
                    "url": "{{ route('gym.biin') }}"
                },
                {
                    "@type": "ExerciseGym",
                    "name": "K1NG Gym",
                    "url": "{{ route('gym.king') }}"
                },
                {
                    "@type": "LodgingBusiness",
                    "name": "Kost Istana Merdeka 03",
                    "url": "{{ route('kost') }}"
                }
            ]
        }
    </script>

    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/empire.png'))">

    {{-- Stylesheets --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0f1a2c',
                        secondary: '#f6ac0f',
                        textDark: '#0f172a',
                        textLight: '#64748b',
                        extraLight: '#f8fafc',
                    }
                }
            }
        }
    </script>

    {{-- JS Libraries --}}
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    @vite('resources/css/home.css')

    <style>
        /* Minimal custom styles only for floating menu animation */
        .floating-menu .action {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .floating-menu.active .action {
            opacity: 1;
            transform: translateY(0);
        }

        .floating-menu.active .action:nth-child(1) {
            transition-delay: 0.05s;
        }

        .floating-menu.active .action:nth-child(2) {
            transition-delay: 0.1s;
        }

        .floating-menu.active .action:nth-child(3) {
            transition-delay: 0.15s;
        }

        .floating-menu.active .action:nth-child(4) {
            transition-delay: 0.2s;
        }

        /* Scroll Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-left.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-scale.active {
            opacity: 1;
            transform: scale(1);
        }

        /* Staggered children animation */
        .reveal-stagger>* {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-stagger.active>*:nth-child(1) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.1s;
        }

        .reveal-stagger.active>*:nth-child(2) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.2s;
        }

        .reveal-stagger.active>*:nth-child(3) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.3s;
        }

        .reveal-stagger.active>*:nth-child(4) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.4s;
        }

        .reveal-stagger.active>*:nth-child(5) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.5s;
        }

        .reveal-stagger.active>*:nth-child(6) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.6s;
        }
    </style>
</head>

<body class="bg-primary">

    <!-- NAVIGATION BAR -->
    @include('components.navbar-cta')

    <!-- HERO HEADER SECTION -->
    <header id="header" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-extraLight via-white to-gray-50 pt-24">
        <!-- Background Image with Light Overlay -->
        <div class="absolute inset-0 z-0 opacity-5">
            <img src="assets/Hero/b11ngym.jpg" alt="B1NG Empire Hero" class="w-full h-full object-cover object-center" />
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-20 right-10 w-72 h-72 bg-secondary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>

        <!-- Content Container - Left Aligned -->
        <div class="relative z-20 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24">
            <div class="max-w-3xl mx-auto text-center reveal">
                <!-- Subheader -->
                <p class="text-secondary font-bold text-base sm:text-lg tracking-wider mb-6 uppercase">
                    MEMPERSEMBAHKAN
                </p>

                <!-- Main Title -->
                <h1 class="text-textDark font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl leading-none mb-8 sm:mb-10 tracking-tight">
                    B1NG<br />
                    <span class="text-secondary">EMPIRE</span>
                </h1>

                <!-- Description -->
                <p class="text-textLight text-lg sm:text-xl leading-relaxed mb-10 max-w-xl">
                    Menghadirkan fitness, wellness, dan hunian modern dalam satu ekosistem eksklusif di Purwokerto.
                </p>

                <!-- CTA Button -->
                <button class="px-10 py-5 bg-secondary text-white font-bold text-base sm:text-lg rounded-lg shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105">
                    Jelajahi B1NG EMPIRE
                </button>
            </div>
        </div>
    </header>

    <!-- ABOUT SECTION -->
    <section id="about" class="relative py-20 sm:py-24 lg:py-32 bg-white">
        <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Content Section (DI ATAS) -->
            <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20 reveal">
                <!-- Subheader -->
                <p class="text-secondary font-bold text-base sm:text-lg tracking-wider mb-4 uppercase">
                    TENTANG KAMI
                </p>

                <!-- Main Heading -->
                <h2 class="text-textDark font-black text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 sm:mb-8 tracking-tight leading-tight">
                    Apa itu <span class="text-secondary">B1NG EMPIRE</span>?
                </h2>

                <!-- Description -->
                <p class="text-textLight text-lg sm:text-xl leading-relaxed mb-10 max-w-2xl mx-auto">
                    B1NG EMPIRE adalah sebuah konsep di mana beberapa bisnis atau layanan yang berbeda-beda, namun memiliki kesamaan dalam hal kepemilikan atau target audiens, digabungkan ke dalam satu website. Tujuannya adalah untuk memberikan pengalaman pengguna yang lebih baik, meningkatkan efisiensi, dan memperkuat branding.
                </p>

                <!-- CTA Button -->
                <a href="#website" class="inline-block px-10 py-5 bg-secondary text-white font-bold text-base sm:text-lg rounded-lg shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105">
                    Get Started
                </a>
            </div>

            <!-- Grid Layout: Images & Cards (DI BAWAH) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 reveal-stagger">

                <!-- B11N & K1NG Gym Image -->
                <div class="group relative overflow-hidden rounded-2xl aspect-[4/5] shadow-lg hover:shadow-2xl transition-all duration-500">
                    <img src="assets/home/biin-gym.jpg" alt="B11N & K1NG Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>

                <!-- B11N & K1NG Gym Card -->
                <div class="bg-extraLight rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 mb-6 bg-secondary rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-dumbbell text-2xl text-white"></i>
                    </div>
                    <h4 class="text-2xl font-black text-textDark mb-4 tracking-tight">B11N & K1NG GYM</h4>
                    <p class="text-textLight leading-relaxed text-base">
                        Dua tempat fitness & gym kami yang berada di Purwokerto
                    </p>
                </div>

                <!-- Kost Image -->
                <div class="group relative overflow-hidden rounded-2xl aspect-[4/5] shadow-lg hover:shadow-2xl transition-all duration-500">
                    <img src="assets/home/kost.jpg" alt="Kost Istana Merdeka 3" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>

                <!-- Kost Card -->
                <div class="bg-extraLight rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 mb-6 bg-secondary rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-bed text-2xl text-white"></i>
                    </div>
                    <h4 class="text-2xl font-black text-textDark mb-4 tracking-tight">Kost Istana Merdeka 3</h4>
                    <p class="text-textLight leading-relaxed text-base">
                        Tempat Kost Putra yang terletak diatas B11N Gym Purwokerto
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- WEBSITE/PRODUCT SECTION -->
    <section id="website" class="relative py-20 sm:py-24 lg:py-32 bg-extraLight">
        <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center mb-16 lg:mb-20 reveal">
                <p class="text-secondary font-bold text-base sm:text-lg tracking-wider mb-4 uppercase">
                    Beberapa Ekosistem Kami Yang Menunjukkan Tempat Usaha Kami
                </p>
                <h2 class="text-textDark font-black text-3xl sm:text-4xl md:text-5xl lg:text-6xl tracking-tight">
                    Ekosistem <span class="text-secondary">Kami</span>
                </h2>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger">

                <!-- B11N Gym Card -->
                <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <!-- Image Container -->
                    <div class="relative h-64 sm:h-72 overflow-hidden">
                        <img src="assets/home/biin-gym.jpg" alt="B11N Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                        <!-- Badge -->
                        <div class="absolute top-4 right-4 bg-secondary px-4 py-2 rounded-lg shadow-md">
                            <span class="text-white font-black text-xs tracking-wider">TERMURAH</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-8">
                        <h4 class="text-textDark font-black text-2xl mb-3 tracking-tight">B11N Gym Purwokerto</h4>
                        <p class="text-textLight leading-relaxed mb-6 text-base">
                            Tempat gym yang saat ini menyandang status sebagai tempat gym termurah di Purwokerto
                        </p>

                        <!-- Button -->
                        <a href="{{ route('gym.biin') }}" target="_blank" class="inline-block px-6 py-3 bg-secondary text-white font-bold text-sm rounded-lg shadow-md hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-lg transition-all duration-300 hover:scale-105">
                            Kunjungi Ekosistem
                        </a>
                    </div>
                </div>

                <!-- K1NG Gym Card -->
                <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <!-- Image Container -->
                    <div class="relative h-64 sm:h-72 overflow-hidden">
                        <img src="assets/home/king-gym.jpg" alt="K1NG Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                        <!-- Badge -->
                        <div class="absolute top-4 right-4 bg-secondary px-4 py-2 rounded-lg shadow-md">
                            <span class="text-white font-black text-xs tracking-wider">CABANG BARU</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-8">
                        <h4 class="text-textDark font-black text-2xl mb-3 tracking-tight">K1NG Gym Purwokerto</h4>
                        <p class="text-textLight leading-relaxed mb-6 text-base">
                            Cabang dari B11N Gym yang baru buka beberapa bulan yang juga menyandang status sebagai tempat gym termurah di Purwokerto
                        </p>

                        <!-- Button -->
                        <a href="{{ route('gym.king') }}" target="_blank" class="inline-block px-6 py-3 bg-secondary text-white font-bold text-sm rounded-lg shadow-md hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-lg transition-all duration-300 hover:scale-105">
                            Kunjungi Ekosistem
                        </a>
                    </div>
                </div>

                <!-- Kost Istana Merdeka Card -->
                <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <!-- Image Container -->
                    <div class="relative h-64 sm:h-72 overflow-hidden">
                        <img src="assets/home/istana-merdeka.jpg" alt="Kost Istana Merdeka 3" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                        <!-- Badge -->
                        <div class="absolute top-4 right-4 bg-secondary px-4 py-2 rounded-lg shadow-md">
                            <span class="text-white font-black text-xs tracking-wider">KHUSUS PUTRA</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-8">
                        <h4 class="text-textDark font-black text-2xl mb-3 tracking-tight">Kost Istana Merdeka 3</h4>
                        <p class="text-textLight leading-relaxed mb-6 text-base">
                            Kost khusus putra yang letaknya berada di lantai 2 B11N Gym Purwokerto
                        </p>

                        <!-- Button -->
                        <a href="{{ route('kost') }}" target="_blank" class="inline-block px-6 py-3 bg-secondary text-white font-bold text-sm rounded-lg shadow-md hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-lg transition-all duration-300 hover:scale-105">
                            Kunjungi Ekosistem
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- STORE SECTION -->
    <section id="store" class="relative py-20 sm:py-24 lg:py-32 bg-white">
        <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- Content Side (Left) -->
                <div class="order-2 lg:order-1 reveal-left">
                    <!-- Subheader -->
                    <p class="text-secondary font-bold text-base sm:text-lg tracking-wider mb-4 uppercase">
                        STORE
                    </p>

                    <!-- Main Heading -->
                    <h2 class="text-textDark font-black text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-8 tracking-tight leading-tight">
                        B11N & K1NG <span class="text-secondary">Gym Store</span>
                    </h2>

                    <!-- Description -->
                    <p class="text-textLight text-lg sm:text-xl leading-relaxed mb-10">
                        B11N & K1NG Gym Store adalah toko yang menjual berbagai minuman protein yang dijual di B11N Gym & K1NG Gym Purwokerto, disini ada banyak jenis minuman baik itu susu protein, air mineral, suplement untuk gym, dan lainnya
                    </p>

                    <!-- Feature List -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                                <i class="fas fa-check text-white font-bold text-lg"></i>
                            </div>
                            <span class="text-textDark font-semibold text-base">Susu Protein</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                                <i class="fas fa-check text-white font-bold text-lg"></i>
                            </div>
                            <span class="text-textDark font-semibold text-base">Air Mineral</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                                <i class="fas fa-check text-white font-bold text-lg"></i>
                            </div>
                            <span class="text-textDark font-semibold text-base">Supplement Gym</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                                <i class="fas fa-check text-white font-bold text-lg"></i>
                            </div>
                            <span class="text-textDark font-semibold text-base">Dan Lainnya</span>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <a href="{{ route('store.biin-king') }}" target="_blank" class="inline-flex items-center gap-3 px-10 py-5 bg-secondary text-white font-bold text-base sm:text-lg rounded-lg shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105">
                        Kunjungi Store
                        <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                </div>

                <!-- Image Side (Right) -->
                <div class="order-1 lg:order-2 reveal-right">
                    <div class="group relative">
                        <!-- Main Image Container -->
                        <div class="relative rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 bg-extraLight">
                            <img src="assets/home/store.png" alt="B11N & K1NG Gym Store" class="w-full h-auto transition-transform duration-700 group-hover:scale-105" />
                        </div>

                        <!-- Badge -->
                        <div class="absolute top-6 right-6 bg-secondary px-5 py-2.5 rounded-xl shadow-lg">
                            <p class="text-white font-black text-sm tracking-wider">OFFICIAL STORE</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- BLOG SECTION -->
    <section id="blog" class="relative py-20 sm:py-24 lg:py-32 bg-extraLight">
        <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="max-w-3xl mb-16 lg:mb-20 reveal">
                <p class="text-secondary font-bold text-base sm:text-lg tracking-wider mb-4 uppercase">
                    BLOG
                </p>
                <h2 class="text-textDark font-black text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-8 tracking-tight leading-tight">
                    B1NG EMPIRE <span class="text-secondary">Blog</span>
                </h2>
                <p class="text-textLight text-lg sm:text-xl leading-relaxed">
                    B1NG EMPIRE Blog adalah Website Blog pribadi kami yang didalamnya berisi informasi, tips & trick, dan berita - berita terbaru yang berkaitan dengan B11N Gym, K1NG Gym, dan Kost Istana Merdeka 3.
                </p>
            </div>

            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger">
                @foreach ($blog as $blog)
                <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="group block">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">

                        <!-- Image Container -->
                        <div class="relative h-48 sm:h-56 overflow-hidden">
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>

                            <!-- Date Badge -->
                            <div class="absolute top-4 left-4 bg-secondary px-4 py-2 rounded-lg shadow-md">
                                <p class="text-white font-black text-xs tracking-wider">
                                    {{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 sm:p-8">
                            <h4 class="text-textDark font-black text-lg sm:text-xl mb-3 tracking-tight leading-tight line-clamp-2 group-hover:text-secondary transition-colors duration-300">
                                {{ $blog->title }}
                            </h4>
                            <p class="text-textLight leading-relaxed text-base line-clamp-3 mb-4">
                                {!! Str::limit(strip_tags($blog->content), 100) !!}
                            </p>

                            <!-- Read More Link -->
                            <div class="flex items-center gap-2 text-secondary font-bold text-sm group-hover:gap-3 transition-all duration-300">
                                <span>Baca Selengkapnya</span>
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                            </div>
                        </div>

                    </div>
                </a>
                @endforeach
            </div>

            <!-- View All Button (Conditional) -->
            @if($blog->count() > 3)
            <div class="mt-16 text-center">
                <a href="{{ route('blogs.index') }}" target="_blank" class="inline-flex items-center gap-3 px-10 py-5 bg-secondary text-white font-bold text-base sm:text-lg rounded-lg shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105">
                    View All Posts
                    <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
            @endif

        </div>
    </section>

    <footer id="contact" class="relative bg-[#0f1a2c] text-white border-t border-gray-800 font-sans">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#f6ac0f] to-transparent opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12 mb-12 reveal-stagger">

                <div class="lg:col-span-5 space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 relative flex-shrink-0">
                            <a href="{{ route('home') }}" class="block">
                                <img src="{{ asset('assets/Logo/empire.png') }}" alt="B1NG Empire Logo" class="w-full h-full object-contain" />
                            </a>
                        </div>
                        <div>
                            <h3 class="font-black text-2xl leading-none tracking-wide text-white">
                                B1NG <span class="text-[#f6ac0f]">EMPIRE</span>
                            </h3>
                            <span class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">Premium Gym & Residence</span>
                        </div>
                    </div>

                    <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                        Konsep "One-Stop Solution" yang menggabungkan kebugaran, gaya hidup sehat, dan hunian nyaman dalam satu ekosistem terpadu di Purwokerto. Tingkatkan kualitas hidup Anda bersama kami.
                    </p>

                    <div class="flex items-center gap-3 pt-2">
                        @php
                        $socials = [
                        ['icon' => 'ri-whatsapp-line', 'url' => 'https://wa.me/6281226110988'],
                        ['icon' => 'ri-instagram-fill', 'url' => 'https://www.instagram.com/biin_gym/'],
                        ['icon' => 'ri-threads-fill', 'url' => 'https://www.threads.net/@biin_gym'],
                        ['icon' => 'fas fa-envelope', 'url' => 'mailto:sobiin77@gmail.com'],
                        ];
                        @endphp

                        @foreach($socials as $social)
                        <a href="{{ $social['url'] }}" target="_blank" class="w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:bg-[#f6ac0f] hover:text-white hover:border-[#f6ac0f] transition-all duration-300 transform hover:-translate-y-1">
                            <i class="{{ $social['icon'] }} text-lg"></i>
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-6 border-l-4 border-[#f6ac0f] pl-3">
                        Navigasi
                    </h4>
                    <ul class="space-y-3">
                        @foreach($navMenus as $menu)
                        <li>
                            <a href="{{ route($menu['route']) }}" class="group flex items-center gap-3 text-sm text-gray-400 hover:text-white transition-colors duration-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#f6ac0f] opacity-0 group-hover:opacity-100 transition-all duration-300 transform -translate-x-2 group-hover:translate-x-0"></span>
                                <span class="group-hover:translate-x-1 transition-transform duration-300">{{ $menu['label'] }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="lg:col-span-4">
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-6 border-l-4 border-[#f6ac0f] pl-3">
                        Hubungi Kami
                    </h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="tel:+6289653847651" class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-[#f6ac0f]/50 hover:bg-[#f6ac0f]/10 transition-all duration-300 group">
                                <div class="w-10 h-10 bg-[#f6ac0f] rounded-lg flex items-center justify-center flex-shrink-0 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="ri-phone-fill text-lg"></i>
                                </div>
                                <div>
                                    <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-1 opacity-70">Telepon / WhatsApp</h5>
                                    <p class="text-gray-300 font-medium text-sm group-hover:text-[#f6ac0f] transition-colors">+62 896 5384 7651</p>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="https://maps.google.com" target="_blank" class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-[#f6ac0f]/50 hover:bg-[#f6ac0f]/10 transition-all duration-300 group">
                                <div class="w-10 h-10 bg-[#f6ac0f] rounded-lg flex items-center justify-center flex-shrink-0 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="ri-map-pin-2-fill text-lg"></i>
                                </div>
                                <div>
                                    <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-1 opacity-70">Lokasi Utama</h5>
                                    <p class="text-gray-300 text-sm leading-snug group-hover:text-[#f6ac0f] transition-colors">
                                        Jl. Masjid Baru, Arcawinangun,<br>Purwokerto Timur, Banyumas
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-xs text-center md:text-left">
                    Copyright &copy; {{ date('Y') }} <span class="text-[#f6ac0f] font-bold">B1NG EMPIRE</span>. All rights reserved.
                </p>

                <div class="flex items-center gap-6 text-xs text-gray-500">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>

        </div>
    </footer>

    <script>
        // Scroll Reveal Animation with Intersection Observer
        const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-stagger');

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        });

        revealElements.forEach(el => revealObserver.observe(el));
    </script>
</body>

</html>