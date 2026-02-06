<!DOCTYPE html>
<html lang="en">

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
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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
                    },
                    fontFamily: {
                        heading: ['Oswald', 'sans-serif'],
                        body: ['Poppins', 'sans-serif'],
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
        .floating-menu.active .action:nth-child(1) { transition-delay: 0.05s; }
        .floating-menu.active .action:nth-child(2) { transition-delay: 0.1s; }
        .floating-menu.active .action:nth-child(3) { transition-delay: 0.15s; }
        .floating-menu.active .action:nth-child(4) { transition-delay: 0.2s; }

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
        .reveal-stagger > * {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal-stagger.active > *:nth-child(1) { opacity: 1; transform: translateY(0); transition-delay: 0.1s; }
        .reveal-stagger.active > *:nth-child(2) { opacity: 1; transform: translateY(0); transition-delay: 0.2s; }
        .reveal-stagger.active > *:nth-child(3) { opacity: 1; transform: translateY(0); transition-delay: 0.3s; }
        .reveal-stagger.active > *:nth-child(4) { opacity: 1; transform: translateY(0); transition-delay: 0.4s; }
        .reveal-stagger.active > *:nth-child(5) { opacity: 1; transform: translateY(0); transition-delay: 0.5s; }
        .reveal-stagger.active > *:nth-child(6) { opacity: 1; transform: translateY(0); transition-delay: 0.6s; }

        /* Base Typography */
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
    </style>
</head>

<body class="bg-primary font-body">

<!-- NAVIGATION BAR -->
<nav class="fixed top-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Logo -->
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-14 h-14 sm:w-16 sm:h-16 relative">
                    <a href="#" class="block">
                        <img src="assets/Logo/empire.png" alt="logo" class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-110" />
                    </a>
                </div>
                <span class="font-heading text-primary font-bold text-xl sm:text-2xl leading-tight tracking-wider uppercase">
                    B1NG<br />
                    <span class="text-secondary">EMPIRE</span>
                </span>
            </div>

            <!-- Desktop Navigation Links -->
            <ul class="hidden lg:flex items-center gap-8">
                <li><a href="#header" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">HOME</a></li>
                <li><a href="#about" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">TENTANG KAMI</a></li>
                <li><a href="#website" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">WEBSITE KAMI</a></li>
                <li><a href="#store" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">STORE</a></li>
                <li><a href="#blog" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">BLOG</a></li>
                <li><a href="#contact" class="font-heading px-6 py-3 bg-secondary text-white font-semibold text-base rounded hover:bg-white hover:text-secondary border-2 border-secondary transition-all duration-300 shadow-md hover:shadow-lg tracking-wider">HUBUNGI KAMI</a></li>
            </ul>

            <!-- Mobile Menu Button -->
            <button id="menu-btn" class="lg:hidden text-primary text-3xl hover:text-secondary transition-colors duration-300">
                <i class="ri-menu-line"></i>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="nav-links" class="hidden lg:hidden overflow-hidden transition-all duration-300 ease-in-out">
            <ul class="pb-6 space-y-2 border-t border-gray-100 mt-2 pt-4">
                <li><a href="#header" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">HOME</a></li>
                <li><a href="#about" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">TENTANG KAMI</a></li>
                <li><a href="#website" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">WEBSITE KAMI</a></li>
                <li><a href="#store" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">STORE</a></li>
                <li><a href="#blog" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">BLOG</a></li>
                <li><a href="#contact" class="block px-4 py-3 text-white bg-secondary hover:bg-white hover:text-secondary border-2 border-secondary font-bold text-base transition-all duration-300 rounded text-center shadow-md">HUBUNGI KAMI</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- FLOATING ACTION MENU -->
<div class="floating-menu fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-50 flex flex-col items-center gap-4">
    <!-- Action Buttons (Hidden by default - Muncul ke ATAS) -->
    <a href="{{ route('gym.king') }}" class="action w-14 h-14 sm:w-16 sm:h-16 bg-white border-2 border-gray-200 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl hover:border-secondary transition-all duration-300 group">
        <img src="assets/Logo/last.png" alt="K1NG Gym" class="w-10 h-10 sm:w-12 sm:h-12 object-cover rounded-full group-hover:scale-110 transition-transform duration-300" />
    </a>
    <a href="{{ route('gym.biin') }}" class="action w-14 h-14 sm:w-16 sm:h-16 bg-white border-2 border-gray-200 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl hover:border-secondary transition-all duration-300 group">
        <img src="assets/Logo/biin.png" alt="B11N Gym" class="w-10 h-10 sm:w-12 sm:h-12 object-contain group-hover:scale-110 transition-transform duration-300" />
    </a>
    <a href="{{ route('kost') }}" class="action w-14 h-14 sm:w-16 sm:h-16 bg-white border-2 border-gray-200 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl hover:border-secondary transition-all duration-300 group">
        <img src="assets/Logo/kost.png" alt="Istana Merdeka 03" class="w-10 h-10 sm:w-12 sm:h-12 object-contain group-hover:scale-110 transition-transform duration-300" />
    </a>
    <a href="{{ route('home') }}" class="action w-14 h-14 sm:w-16 sm:h-16 bg-white border-2 border-gray-200 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl hover:border-secondary transition-all duration-300 group">
        <img src="assets/Logo/empire.png" alt="B1NG Empire" class="w-10 h-10 sm:w-12 sm:h-12 object-contain group-hover:scale-110 transition-transform duration-300" />
    </a>
    
    <!-- Trigger Button (Di Paling Bawah) -->
    <a href="#" class="trigger w-14 h-14 sm:w-16 sm:h-16 bg-secondary rounded-full flex items-center justify-center shadow-xl hover:bg-yellow-600 transition-all duration-300 text-white text-xl sm:text-2xl font-bold">
        <i class="fas fa-plus transition-transform duration-300"></i>
    </a>
</div>

<!-- HERO HEADER SECTION -->
<header id="header" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-extraLight via-white to-gray-50 pt-24">
    <!-- Background Image with Light Overlay -->
    <div class="absolute inset-0 z-0 opacity-5">
        <img src="assets/Hero/b11ngym.jpg" alt="B1NG Empire Hero" class="w-full h-full object-cover object-center" />
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-secondary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>

    <!-- Content Container - Left Aligned -->
    <div class="relative z-20 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24">
        <div class="max-w-3xl mx-auto text-center reveal">
            <!-- Subheader -->
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-6 uppercase">
                MEMPERSEMBAHKAN
            </p>
            
            <!-- Main Title -->
            <h1 class="font-heading text-textDark font-bold text-5xl sm:text-6xl md:text-7xl lg:text-8xl leading-none mb-8 sm:mb-10 tracking-tight uppercase">
                B1NG<br />
                <span class="text-secondary">EMPIRE</span>
            </h1>
            
            <!-- Description -->
            <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed mb-10 max-w-xl">
                Menghadirkan fitness, wellness, dan hunian modern dalam satu ekosistem eksklusif di Purwokerto.
            </p>
            
            <!-- CTA Button -->
            <button class="font-heading px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                Jelajahi B1NG Empire
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
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">
                TENTANG KAMI
            </p>

            <!-- Main Heading -->
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 sm:mb-8 tracking-tight leading-tight uppercase">
                Apa itu <span class="text-secondary">B1NG EMPIRE</span>?
            </h2>

            <!-- Description -->
            <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed mb-10 max-w-2xl mx-auto">
                B1NG EMPIRE adalah sebuah konsep di mana beberapa bisnis atau layanan yang berbeda-beda, namun memiliki kesamaan dalam hal kepemilikan atau target audiens, digabungkan ke dalam satu website. Tujuannya adalah untuk memberikan pengalaman pengguna yang lebih baik, meningkatkan efisiensi, dan memperkuat branding.
            </p>

            <!-- CTA Button -->
            <a href="#website" class="font-heading inline-block px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                Mulai Sekarang
            </a>
        </div>

        <!-- Grid Layout: Images & Cards (DI BAWAH) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 reveal-stagger">
            
            <!-- B11N & K1NG Gym Image -->
            <div class="group relative overflow-hidden rounded aspect-[4/5] shadow-lg hover:shadow-2xl transition-all duration-500">
                <img src="assets/home/biin-gym.jpg" alt="B11N & K1NG Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            </div>

            <!-- B11N & K1NG Gym Card -->
            <div class="bg-extraLight rounded p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="w-16 h-16 mb-6 bg-secondary rounded-sm flex items-center justify-center shadow-md">
                    <i class="fas fa-dumbbell text-2xl text-white"></i>
                </div>
                <h4 class="font-heading text-2xl font-bold text-textDark mb-4 tracking-tight uppercase">B11N & K1NG GYM</h4>
                <p class="font-body text-textLight leading-relaxed text-base">
                    Dua tempat fitness & gym kami yang berada di Purwokerto
                </p>
            </div>

            <!-- Kost Image -->
            <div class="group relative overflow-hidden rounded aspect-[4/5] shadow-lg hover:shadow-2xl transition-all duration-500">
                <img src="assets/home/kost.jpg" alt="Kost Istana Merdeka 3" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            </div>

            <!-- Kost Card -->
            <div class="bg-extraLight rounded p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="w-16 h-16 mb-6 bg-secondary rounded-sm flex items-center justify-center shadow-md">
                    <i class="fas fa-bed text-2xl text-white"></i>
                </div>
                <h4 class="font-heading text-2xl font-bold text-textDark mb-4 tracking-tight uppercase">Kost Istana Merdeka 3</h4>
                <p class="font-body text-textLight leading-relaxed text-base">
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
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">
                Beberapa Ekosistem Kami
            </p>
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl tracking-tight uppercase">
                Ekosistem <span class="text-secondary">Kami</span>
            </h2>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger">
            
            <!-- B11N Gym Card -->
            <div class="group bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col h-full">
                <!-- Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden flex-shrink-0">
                    <img src="assets/home/biin-gym.jpg" alt="B11N Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    
                    <!-- Badge -->
                    <div class="absolute top-4 right-4 bg-secondary px-4 py-2 rounded shadow-md">
                        <span class="font-heading text-white font-semibold text-xs tracking-wider uppercase">TERMURAH</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8 flex flex-col flex-grow">
                    <h4 class="font-heading text-textDark font-bold text-2xl mb-3 tracking-tight uppercase">B11N Gym Purwokerto</h4>
                    <p class="font-body text-textLight leading-relaxed text-base flex-grow">
                        Tempat gym yang saat ini menyandang status sebagai tempat gym termurah di Purwokerto
                    </p>
                    
                    <!-- Button -->
                    <a href="{{ route('gym.biin') }}" target="_blank" class="font-heading inline-block px-6 py-3 bg-secondary text-white font-semibold text-sm rounded shadow-md hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-lg transition-all duration-300 hover:scale-105 uppercase tracking-wider mt-6 self-start">
                        Kunjungi Ekosistem
                    </a>
                </div>
            </div>

            <!-- K1NG Gym Card -->
            <div class="group bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col h-full">
                <!-- Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden flex-shrink-0">
                    <img src="assets/home/king-gym.jpg" alt="K1NG Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    
                    <!-- Badge -->
                    <div class="absolute top-4 right-4 bg-secondary px-4 py-2 rounded shadow-md">
                        <span class="font-heading text-white font-semibold text-xs tracking-wider uppercase">CABANG BARU</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8 flex flex-col flex-grow">
                    <h4 class="font-heading text-textDark font-bold text-2xl mb-3 tracking-tight uppercase">K1NG Gym Purwokerto</h4>
                    <p class="font-body text-textLight leading-relaxed text-base flex-grow">
                        Cabang dari B11N Gym yang baru buka beberapa bulan yang juga menyandang status sebagai tempat gym termurah di Purwokerto
                    </p>
                    
                    <!-- Button -->
                    <a href="{{ route('gym.king') }}" target="_blank" class="font-heading inline-block px-6 py-3 bg-secondary text-white font-semibold text-sm rounded shadow-md hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-lg transition-all duration-300 hover:scale-105 uppercase tracking-wider mt-6 self-start">
                        Kunjungi Ekosistem
                    </a>
                </div>
            </div>

            <!-- Kost Istana Merdeka Card -->
            <div class="group bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col h-full">
                <!-- Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden flex-shrink-0">
                    <img src="assets/home/istana-merdeka.jpg" alt="Kost Istana Merdeka 3" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    
                    <!-- Badge -->
                    <div class="absolute top-4 right-4 bg-secondary px-4 py-2 rounded shadow-md">
                        <span class="font-heading text-white font-semibold text-xs tracking-wider uppercase">KHUSUS PUTRA</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8 flex flex-col flex-grow">
                    <h4 class="font-heading text-textDark font-bold text-2xl mb-3 tracking-tight uppercase">Kost Istana Merdeka 3</h4>
                    <p class="font-body text-textLight leading-relaxed text-base flex-grow">
                        Kost khusus putra yang letaknya berada di lantai 2 B11N Gym Purwokerto
                    </p>
                    
                    <!-- Button -->
                    <a href="{{ route('kost') }}" target="_blank" class="font-heading inline-block px-6 py-3 bg-secondary text-white font-semibold text-sm rounded shadow-md hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-lg transition-all duration-300 hover:scale-105 uppercase tracking-wider mt-6 self-start">
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
                <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">
                    STORE
                </p>

                <!-- Main Heading -->
                <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-8 tracking-tight leading-tight uppercase">
                    B11N & K1NG <span class="text-secondary">Gym Store</span>
                </h2>

                <!-- Description -->
                <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed mb-10">
                    B11N & K1NG Gym Store adalah toko yang menjual berbagai minuman protein yang dijual di B11N Gym & K1NG Gym Purwokerto, disini ada banyak jenis minuman baik itu susu protein, air mineral, suplement untuk gym, dan lainnya
                </p>

                <!-- Feature List -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-secondary rounded-sm flex items-center justify-center shadow-md flex-shrink-0">
                            <i class="fas fa-check text-white font-bold text-lg"></i>
                        </div>
                        <span class="font-heading text-textDark font-semibold text-base uppercase">Susu Protein</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-secondary rounded-sm flex items-center justify-center shadow-md flex-shrink-0">
                            <i class="fas fa-check text-white font-bold text-lg"></i>
                        </div>
                        <span class="font-heading text-textDark font-semibold text-base uppercase">Air Mineral</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-secondary rounded-sm flex items-center justify-center shadow-md flex-shrink-0">
                            <i class="fas fa-check text-white font-bold text-lg"></i>
                        </div>
                        <span class="font-heading text-textDark font-semibold text-base uppercase">Supplement Gym</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-secondary rounded-sm flex items-center justify-center shadow-md flex-shrink-0">
                            <i class="fas fa-check text-white font-bold text-lg"></i>
                        </div>
                        <span class="font-heading text-textDark font-semibold text-base uppercase">Dan Lainnya</span>
                    </div>
                </div>

                <!-- CTA Button -->
                <a href="{{ route('store.biin-king') }}" target="_blank" class="font-heading inline-flex items-center gap-3 px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                    Kunjungi Website
                    <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>

            <!-- Image Side (Right) -->
            <div class="order-1 lg:order-2 reveal-right">
                <div class="group relative">
                    <!-- Main Image Container -->
                    <div class="relative rounded overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 bg-extraLight">
                        <img src="assets/home/store.png" alt="B11N & K1NG Gym Store" class="w-full h-auto transition-transform duration-700 group-hover:scale-105" />
                    </div>
                    
                    <!-- Badge -->
                    <div class="absolute top-6 right-6 bg-secondary px-5 py-2.5 rounded-sm shadow-lg">
                        <p class="font-heading text-white font-semibold text-sm tracking-wider uppercase">OFFICIAL STORE</p>
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
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">
                BLOG
            </p>
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-8 tracking-tight leading-tight uppercase">
                B1NG EMPIRE <span class="text-secondary">Blog</span>
            </h2>
            <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed">
                B1NG EMPIRE Blog adalah Website Blog pribadi kami yang didalamnya berisi informasi, tips & trick, dan berita - berita terbaru yang berkaitan dengan B11N Gym, K1NG Gym, dan Kost Istana Merdeka 3.
            </p>
        </div>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger">
            @foreach ($blog as $blog)
            <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="group block">
                <div class="bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    
                    <!-- Image Container -->
                    <div class="relative h-48 sm:h-56 overflow-hidden">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        
                        <!-- Date Badge -->
                        <div class="absolute top-4 left-4 bg-secondary px-4 py-2 rounded shadow-md">
                            <p class="font-heading text-white font-semibold text-xs tracking-wider uppercase">
                                {{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 sm:p-8">
                        <h4 class="font-heading text-textDark font-bold text-lg sm:text-xl mb-3 tracking-tight leading-tight line-clamp-2 group-hover:text-secondary transition-colors duration-300 uppercase">
                            {{ $blog->title }}
                        </h4>
                        <p class="font-body text-textLight leading-relaxed text-base line-clamp-3 mb-4">
                            {!! Str::limit(strip_tags($blog->content), 100) !!}
                        </p>
                        
                        <!-- Read More Link -->
                        <div class="font-heading flex items-center gap-2 text-secondary font-semibold text-sm group-hover:gap-3 transition-all duration-300 uppercase tracking-wider">
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
            <a href="{{ route('blogs.index') }}" target="_blank" class="font-heading inline-flex items-center gap-3 px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                View All Posts
                <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
        </div>
        @endif

    </div>
</section>

<!-- FOOTER SECTION -->
<footer id="contact" class="relative bg-primary overflow-hidden">
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-16 sm:pt-20 lg:pt-24 pb-4 sm:pb-5 lg:pb-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16 mb-12 reveal-stagger">
            
            <!-- Brand Column -->
            <div class="lg:col-span-1">
                <!-- Logo -->
                <div class="flex items-center gap-3 mb-6 group">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 relative">
                        <a href="#" class="block">
                            <img src="assets/Logo/empire.png" alt="logo" class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-110" />
                        </a>
                    </div>
                    <span class="font-heading text-white font-bold text-xl sm:text-2xl leading-tight tracking-wider uppercase">
                        B1NG<br />
                        <span class="text-secondary">EMPIRE</span>
                    </span>
                </div>

                <!-- Description -->
                <p class="font-body text-gray-300 text-base leading-relaxed mb-6">
                    B1NG EMPIRE adalah sebuah konsep di mana beberapa bisnis atau layanan yang berbeda-beda, namun memiliki kesamaan dalam hal kepemilikan atau target audiens, digabungkan ke dalam satu website. Tujuannya adalah untuk memberikan pengalaman pengguna yang lebih baik, meningkatkan efisiensi, dan memperkuat branding.
                </p>

                <!-- Social Media Links -->
                <div class="flex items-center gap-3">
                    <a href="mailto:sobiin77@gmail.com" class="w-11 h-11 bg-white/10 border border-white/20 rounded-sm flex items-center justify-center text-white hover:bg-secondary hover:border-secondary transition-all duration-300 hover:scale-110">
                        <i class="fas fa-envelope text-lg"></i>
                    </a>
                    <a href="https://wa.me/6281226110988" target="_blank" class="w-11 h-11 bg-white/10 border border-white/20 rounded-sm flex items-center justify-center text-white hover:bg-secondary hover:border-secondary transition-all duration-300 hover:scale-110">
                        <i class="ri-whatsapp-line text-lg"></i>
                    </a>
                    <a href="https://www.instagram.com/biin_gym/" target="_blank" class="w-11 h-11 bg-white/10 border border-white/20 rounded-sm flex items-center justify-center text-white hover:bg-secondary hover:border-secondary transition-all duration-300 hover:scale-110">
                        <i class="ri-instagram-fill text-lg"></i>
                    </a>
                    <a href="https://www.threads.net/@biin_gym?xmt=AQGzKh5EYkbE4G7JIjSwlirbjIADsXrxWWU6UuUKi1XKhFU" target="_blank" class="w-11 h-11 bg-white/10 border border-white/20 rounded-sm flex items-center justify-center text-white hover:bg-secondary hover:border-secondary transition-all duration-300 hover:scale-110">
                        <i class="ri-threads-fill text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div>
                <h4 class="font-heading text-white font-bold text-xl mb-6 tracking-tight uppercase">Quick Link</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" target="_blank" class="text-gray-300 hover:text-secondary transition-colors duration-300 flex items-center gap-2 text-base group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>B1NG EMPIRE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gym.biin') }}" target="_blank" class="text-gray-300 hover:text-secondary transition-colors duration-300 flex items-center gap-2 text-base group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>B11N Gym Website</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gym.king') }}" target="_blank" class="text-gray-300 hover:text-secondary transition-colors duration-300 flex items-center gap-2 text-base group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>K1NG Gym Website</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kost') }}" target="_blank" class="text-gray-300 hover:text-secondary transition-colors duration-300 flex items-center gap-2 text-base group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>Kost Istana Merdeka 03 Website</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store.biin-king') }}" target="_blank" class="text-gray-300 hover:text-secondary transition-colors duration-300 flex items-center gap-2 text-base group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>B11N & K1NG Gym Store</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blogs.index') }}" target="_blank" class="text-gray-300 hover:text-secondary transition-colors duration-300 flex items-center gap-2 text-base group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>B1NG EMPIRE Blog</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div>
                <h4 class="font-heading text-white font-bold text-xl mb-6 tracking-tight uppercase">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li>
                        <a href="tel:+6289653847651" class="flex items-start gap-4 group hover:bg-white/5 p-3 rounded-sm transition-all duration-300">
                            <div class="w-12 h-12 bg-secondary rounded-sm flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-md">
                                <i class="ri-phone-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h5 class="font-heading text-white font-semibold text-base mb-1 uppercase">No. Telephone</h5>
                                <p class="font-body text-gray-300 text-base group-hover:text-secondary transition-colors duration-300">+62 896 5384 7651</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:sobiin77@gmail.com" class="flex items-start gap-4 group hover:bg-white/5 p-3 rounded-sm transition-all duration-300">
                            <div class="w-12 h-12 bg-secondary rounded-sm flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-md">
                                <i class="ri-record-mail-line text-white text-xl"></i>
                            </div>
                            <div>
                                <h5 class="font-heading text-white font-semibold text-base mb-1 uppercase">Email</h5>
                                <p class="font-body text-gray-300 text-base group-hover:text-secondary transition-colors duration-300">sobiin77@gmail.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.google.com/maps?q=Jl.+Masjid+Baru,+Arcawinangun,Kec.+Purwokerto+Timur,+Kab.+Banyumas" target="_blank" class="flex items-start gap-4 group hover:bg-white/5 p-3 rounded-sm transition-all duration-300">
                            <div class="w-12 h-12 bg-secondary rounded-sm flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-md">
                                <i class="ri-map-pin-2-fill text-white text-xl"></i>
                            </div>
                            <div>
                                <h5 class="font-heading text-white font-semibold text-base mb-1 uppercase">Alamat</h5>
                                <p class="text-gray-300 text-base group-hover:text-secondary transition-colors duration-300">Jl. Masjid Baru, Arcawinangun, Kec. Purwokerto Timur, Kab. Banyumas</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Footer Bottom Bar -->
        <div class="pt-8 border-t border-white/10">
            <p class="font-body text-center text-gray-300 text-base">
                Copyright © {{ date('Y') }} <span class="font-heading text-secondary font-semibold uppercase">B1NG EMPIRE</span>. All rights reserved.
            </p>
        </div>

    </div>
</footer>

<script>
    // Mobile Menu Toggle
    const menuBtn = document.getElementById('menu-btn');
    const navLinks = document.getElementById('nav-links');
    const menuIcon = menuBtn.querySelector('i');

    menuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('hidden');
        
        // Toggle icon
        if (navLinks.classList.contains('hidden')) {
            menuIcon.classList.remove('ri-close-line');
            menuIcon.classList.add('ri-menu-line');
        } else {
            menuIcon.classList.remove('ri-menu-line');
            menuIcon.classList.add('ri-close-line');
        }
    });

    // Floating Menu Toggle
    const floatingMenu = document.querySelector('.floating-menu');
    const trigger = floatingMenu.querySelector('.trigger');
    const triggerIcon = trigger.querySelector('i');

    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        floatingMenu.classList.toggle('active');
        
        // Rotate icon
        if (floatingMenu.classList.contains('active')) {
            triggerIcon.style.transform = 'rotate(45deg)';
        } else {
            triggerIcon.style.transform = 'rotate(0deg)';
        }
    });

    // Close mobile menu when clicking nav links
    const navLinkItems = navLinks.querySelectorAll('a');
    navLinkItems.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.add('hidden');
            menuIcon.classList.remove('ri-close-line');
            menuIcon.classList.add('ri-menu-line');
        });
    });

    // Navbar scroll effect
    const navbar = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 50) {
            navbar.classList.add('shadow-md');
        } else {
            navbar.classList.remove('shadow-md');
        }
    });

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
