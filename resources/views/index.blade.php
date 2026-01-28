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
    </style>
</head>

<body class="bg-primary">

<!-- NAVIGATION BAR -->
<nav class="fixed top-0 left-0 right-0 z-40 bg-primary/95 backdrop-blur-md border-b border-secondary/10 shadow-2xl">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Logo -->
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-14 h-14 sm:w-16 sm:h-16 relative">
                    <a href="#" class="block">
                        <img src="assets/Logo/empire.png" alt="logo" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6" />
                        <div class="absolute inset-0 bg-secondary/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </a>
                </div>
                <span class="text-white font-black text-xl sm:text-2xl leading-tight tracking-wider">
                    B1NG<br />
                    <span class="text-secondary">EMPIRE</span>
                </span>
            </div>

            <!-- Desktop Navigation Links -->
            <ul class="hidden lg:flex items-center gap-8">
                <li><a href="#header" class="text-extraLight hover:text-secondary font-bold text-sm tracking-widest transition-all duration-300 hover:scale-110 inline-block relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-secondary after:transition-all after:duration-300 hover:after:w-full">HOME</a></li>
                <li><a href="#about" class="text-extraLight hover:text-secondary font-bold text-sm tracking-widest transition-all duration-300 hover:scale-110 inline-block relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-secondary after:transition-all after:duration-300 hover:after:w-full">TENTANG KAMI</a></li>
                <li><a href="#website" class="text-extraLight hover:text-secondary font-bold text-sm tracking-widest transition-all duration-300 hover:scale-110 inline-block relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-secondary after:transition-all after:duration-300 hover:after:w-full">WEBSITE KAMI</a></li>
                <li><a href="#store" class="text-extraLight hover:text-secondary font-bold text-sm tracking-widest transition-all duration-300 hover:scale-110 inline-block relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-secondary after:transition-all after:duration-300 hover:after:w-full">STORE</a></li>
                <li><a href="#blog" class="text-extraLight hover:text-secondary font-bold text-sm tracking-widest transition-all duration-300 hover:scale-110 inline-block relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-secondary after:transition-all after:duration-300 hover:after:w-full">BLOG</a></li>
                <li><a href="#contact" class="px-6 py-2.5 bg-gradient-to-r from-secondary to-yellow-500 text-textDark font-black text-sm tracking-widest rounded-sm hover:shadow-lg hover:shadow-secondary/50 transition-all duration-300 hover:scale-105">HUBUNGI KAMI</a></li>
            </ul>

            <!-- Mobile Menu Button -->
            <button id="menu-btn" class="lg:hidden text-secondary text-3xl hover:text-yellow-400 transition-colors duration-300 hover:rotate-90 transform">
                <i class="ri-menu-line"></i>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="nav-links" class="hidden lg:hidden overflow-hidden transition-all duration-300 ease-in-out">
            <ul class="pb-6 space-y-1 border-t border-secondary/10 mt-2 pt-4">
                <li><a href="#header" class="block px-4 py-3 text-extraLight hover:text-secondary hover:bg-secondary/5 font-bold text-sm tracking-widest transition-all duration-300 rounded-sm">HOME</a></li>
                <li><a href="#about" class="block px-4 py-3 text-extraLight hover:text-secondary hover:bg-secondary/5 font-bold text-sm tracking-widest transition-all duration-300 rounded-sm">TENTANG KAMI</a></li>
                <li><a href="#website" class="block px-4 py-3 text-extraLight hover:text-secondary hover:bg-secondary/5 font-bold text-sm tracking-widest transition-all duration-300 rounded-sm">WEBSITE KAMI</a></li>
                <li><a href="#store" class="block px-4 py-3 text-extraLight hover:text-secondary hover:bg-secondary/5 font-bold text-sm tracking-widest transition-all duration-300 rounded-sm">STORE</a></li>
                <li><a href="#blog" class="block px-4 py-3 text-extraLight hover:text-secondary hover:bg-secondary/5 font-bold text-sm tracking-widest transition-all duration-300 rounded-sm">BLOG</a></li>
                <li><a href="#contact" class="block px-4 py-3 text-secondary hover:text-yellow-400 font-black text-sm tracking-widest transition-all duration-300 bg-secondary/10 rounded-sm hover:bg-secondary/20">HUBUNGI KAMI</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- FLOATING ACTION MENU -->
<div class="floating-menu fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-50 flex flex-col items-center gap-4">
    <!-- Action Buttons (Hidden by default - Muncul ke ATAS) -->
    <a href="{{ route('gym.king') }}" class="action w-14 h-14 sm:w-16 sm:h-16 bg-primary border-2 border-secondary/30 rounded-full flex items-center justify-center shadow-lg shadow-secondary/20 hover:shadow-secondary/50 hover:border-secondary hover:scale-110 transition-all duration-300 backdrop-blur-sm group">
        <img src="assets/Logo/last.png" alt="K1NG Gym" class="w-10 h-10 sm:w-12 sm:h-12 object-cover rounded-full group-hover:scale-110 transition-transform duration-300" />
    </a>
    <a href="{{ route('gym.biin') }}" class="action w-14 h-14 sm:w-16 sm:h-16 bg-primary border-2 border-secondary/30 rounded-full flex items-center justify-center shadow-lg shadow-secondary/20 hover:shadow-secondary/50 hover:border-secondary hover:scale-110 transition-all duration-300 backdrop-blur-sm group">
        <img src="assets/Logo/biin.png" alt="B11N Gym" class="w-10 h-10 sm:w-12 sm:h-12 object-contain group-hover:scale-110 transition-transform duration-300" />
    </a>
    <a href="{{ route('kost') }}" class="action w-14 h-14 sm:w-16 sm:h-16 bg-primary border-2 border-secondary/30 rounded-full flex items-center justify-center shadow-lg shadow-secondary/20 hover:shadow-secondary/50 hover:border-secondary hover:scale-110 transition-all duration-300 backdrop-blur-sm group">
        <img src="assets/Logo/kost.png" alt="Istana Merdeka 03" class="w-10 h-10 sm:w-12 sm:h-12 object-contain group-hover:scale-110 transition-transform duration-300" />
    </a>
    <a href="{{ route('home') }}" class="action w-14 h-14 sm:w-16 sm:h-16 bg-primary border-2 border-secondary/30 rounded-full flex items-center justify-center shadow-lg shadow-secondary/20 hover:shadow-secondary/50 hover:border-secondary hover:scale-110 transition-all duration-300 backdrop-blur-sm group">
        <img src="assets/Logo/empire.png" alt="B1NG Empire" class="w-10 h-10 sm:w-12 sm:h-12 object-contain group-hover:scale-110 transition-transform duration-300" />
    </a>
    
    <!-- Trigger Button (Di Paling Bawah) -->
    <a href="#" class="trigger w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-secondary to-yellow-500 rounded-full flex items-center justify-center shadow-xl shadow-secondary/40 hover:shadow-2xl hover:shadow-secondary/60 hover:scale-110 transition-all duration-300 text-textDark text-xl sm:text-2xl font-bold">
        <i class="fas fa-plus transition-transform duration-300"></i>
    </a>
</div>

<!-- HERO HEADER SECTION -->
<header id="header" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/95 via-primary/70 to-transparent z-10"></div>
        <img src="assets/Hero/b11ngym.jpg" alt="B1NG Empire Hero" class="w-full h-full object-cover object-center" />
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-secondary/10 rounded-full blur-3xl z-0"></div>
    <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-secondary/5 rounded-full blur-3xl z-0"></div>

    <!-- Content Container - Left Aligned -->
    <div class="relative z-20 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-32 sm:py-40">
        <div class="max-w-2xl lg:max-w-xl">
            <!-- Subheader -->
            <p class="text-secondary font-bold text-sm sm:text-base tracking-[0.3em] mb-4 sm:mb-6 uppercase animate-pulse">
                WELCOME TO
            </p>
            
            <!-- Main Title -->
            <h1 class="text-white font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl leading-none mb-8 sm:mb-10 tracking-tighter">
                B1NG<br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary via-yellow-400 to-yellow-500">
                    EMPIRE
                </span>
            </h1>
            
            <!-- CTA Button -->
            <button class="group relative px-8 sm:px-10 py-4 sm:py-5 bg-gradient-to-r from-secondary to-yellow-500 text-textDark font-black text-sm sm:text-base tracking-wider rounded-sm overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-secondary/50">
                <span class="relative z-10 flex items-center gap-2">
                    Halo, senang melihat anda 
                    <span class="text-lg sm:text-xl">👋🏻</span>
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>

            <!-- Additional Decorative Text -->
            <div class="mt-12 sm:mt-16 flex items-center gap-6">
                <div class="h-0.5 w-16 sm:w-20 bg-gradient-to-r from-secondary via-secondary/50 to-transparent"></div>
                <p class="text-textLight text-xs sm:text-sm tracking-widest uppercase">Premium Fitness & Property Ecosystem</p>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
        <div class="w-6 h-10 border-2 border-secondary/50 rounded-full flex items-start justify-center p-2">
            <div class="w-1.5 h-3 bg-secondary rounded-full"></div>
        </div>
    </div>
</header>

<!-- ABOUT SECTION -->
<section id="about" class="relative py-20 sm:py-24 lg:py-32 bg-gradient-to-b from-primary via-[#0d1623] to-primary overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-20 left-0 w-72 h-72 bg-secondary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 right-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>

    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Content Section (PINDAH KE ATAS) -->
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20">
            <!-- Subheader -->
            <p class="text-secondary font-bold text-sm sm:text-base tracking-[0.3em] mb-4 uppercase">
                TENTANG KAMI
            </p>

            <!-- Main Heading -->
            <h2 class="text-white font-black text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 sm:mb-8 tracking-tight leading-tight">
                Apa itu <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary via-yellow-400 to-yellow-500">B1NG EMPIRE</span>?
            </h2>

            <!-- Description -->
            <p class="text-textLight text-base sm:text-lg leading-relaxed mb-8 sm:mb-10 max-w-2xl mx-auto">
                B1NG EMPIRE adalah sebuah konsep di mana beberapa bisnis atau layanan yang berbeda-beda, namun memiliki kesamaan dalam hal kepemilikan atau target audiens, digabungkan ke dalam satu website. Tujuannya adalah untuk memberikan pengalaman pengguna yang lebih baik, meningkatkan efisiensi, dan memperkuat branding.
            </p>

            <!-- CTA Button -->
            <a href="#website" class="inline-block group relative px-8 sm:px-10 py-4 sm:py-5 bg-gradient-to-r from-secondary to-yellow-500 text-textDark font-black text-sm sm:text-base tracking-wider rounded-sm overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-secondary/50">
                <span class="relative z-10">Get Started</span>
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>

        <!-- Grid Layout: Images & Cards (PINDAH KE BAWAH) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- B11N & K1NG Gym Image -->
            <div class="group relative overflow-hidden rounded-lg aspect-[4/5] shadow-xl hover:shadow-2xl hover:shadow-secondary/20 transition-all duration-500">
                <img src="assets/home/biin-gym.jpg" alt="B11N & K1NG Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/50 to-transparent opacity-60 group-hover:opacity-40 transition-opacity duration-500"></div>
            </div>

            <!-- B11N & K1NG Gym Card -->
            <div class="group relative bg-gradient-to-br from-primary to-[#0d1623] border border-secondary/20 rounded-lg p-8 hover:border-secondary/50 transition-all duration-500 hover:shadow-xl hover:shadow-secondary/20 hover:-translate-y-2">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 mb-6 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center shadow-lg shadow-secondary/30 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-dumbbell text-2xl text-primary"></i>
                    </div>
                    <h4 class="text-xl sm:text-2xl font-black text-white mb-4 tracking-tight">B11N & K1NG GYM</h4>
                    <p class="text-textLight leading-relaxed">
                        Dua tempat fitness & gym kami yang berada di Purwokerto
                    </p>
                </div>
            </div>

            <!-- Kost Image -->
            <div class="group relative overflow-hidden rounded-lg aspect-[4/5] shadow-xl hover:shadow-2xl hover:shadow-secondary/20 transition-all duration-500">
                <img src="assets/home/kost.jpg" alt="Kost Istana Merdeka 3" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/50 to-transparent opacity-60 group-hover:opacity-40 transition-opacity duration-500"></div>
            </div>

            <!-- Kost Card -->
            <div class="group relative bg-gradient-to-br from-primary to-[#0d1623] border border-secondary/20 rounded-lg p-8 hover:border-secondary/50 transition-all duration-500 hover:shadow-xl hover:shadow-secondary/20 hover:-translate-y-2">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 mb-6 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center shadow-lg shadow-secondary/30 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-bed text-2xl text-primary"></i>
                    </div>
                    <h4 class="text-xl sm:text-2xl font-black text-white mb-4 tracking-tight">Kost Istana Merdeka 3</h4>
                    <p class="text-textLight leading-relaxed">
                        Tempat Kost Putra yang terletak diatas B11N Gym Purwokerto
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- WEBSITE/PRODUCT SECTION -->
<section id="website" class="relative py-20 sm:py-24 lg:py-32 bg-primary overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-[800px] h-[800px] bg-secondary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16 lg:mb-20">
            <p class="text-secondary font-bold text-sm sm:text-base tracking-[0.3em] mb-4 uppercase">
                Beberapa Ekosistem Kami Yang Menunjukkan Tempat Usaha Kami
            </p>
            <h2 class="text-white font-black text-3xl sm:text-4xl md:text-5xl lg:text-6xl tracking-tight">
                Ekosistem <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary via-yellow-400 to-yellow-500">Kami</span>
            </h2>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- B11N Gym Card -->
            <div class="group relative bg-gradient-to-b from-[#0d1623] to-primary border border-secondary/10 rounded-xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:shadow-2xl hover:shadow-secondary/20 hover:-translate-y-2">
                <!-- Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden">
                    <img src="assets/home/biin-gym.jpg" alt="B11N Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/60 to-transparent"></div>
                    
                    <!-- Badge -->
                    <div class="absolute top-4 right-4 bg-secondary/90 backdrop-blur-sm px-3 py-1.5 rounded-full">
                        <span class="text-primary font-black text-xs tracking-wider">TERMURAH</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <h4 class="text-white font-black text-xl sm:text-2xl mb-3 tracking-tight">B11N Gym Purwokerto</h4>
                    <p class="text-textLight leading-relaxed mb-6">
                        Tempat gym yang saat ini menyandang status sebagai tempat gym termurah di Purwokerto
                    </p>
                    
                    <!-- Button -->
                    <a href="{{ route('gym.biin') }}" target="_blank" class="inline-block group/btn relative px-6 py-3 bg-gradient-to-r from-secondary to-yellow-500 text-primary font-black text-sm tracking-wider rounded-md overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-secondary/50">
                        <span class="relative z-10">Kunjungi Ekosistem</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-secondary opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>

            <!-- K1NG Gym Card -->
            <div class="group relative bg-gradient-to-b from-[#0d1623] to-primary border border-secondary/10 rounded-xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:shadow-2xl hover:shadow-secondary/20 hover:-translate-y-2">
                <!-- Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden">
                    <img src="assets/home/king-gym.jpg" alt="K1NG Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/60 to-transparent"></div>
                    
                    <!-- Badge -->
                    <div class="absolute top-4 right-4 bg-secondary/90 backdrop-blur-sm px-3 py-1.5 rounded-full">
                        <span class="text-primary font-black text-xs tracking-wider">CABANG BARU</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <h4 class="text-white font-black text-xl sm:text-2xl mb-3 tracking-tight">K1NG Gym Purwokerto</h4>
                    <p class="text-textLight leading-relaxed mb-6">
                        Cabang dari B11N Gym yang baru buka beberapa bulan yang juga menyandang status sebagai tempat gym termurah di Purwokerto
                    </p>
                    
                    <!-- Button -->
                    <a href="{{ route('gym.king') }}" target="_blank" class="inline-block group/btn relative px-6 py-3 bg-gradient-to-r from-secondary to-yellow-500 text-primary font-black text-sm tracking-wider rounded-md overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-secondary/50">
                        <span class="relative z-10">Kunjungi Ekosistem</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-secondary opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>

            <!-- Kost Istana Merdeka Card -->
            <div class="group relative bg-gradient-to-b from-[#0d1623] to-primary border border-secondary/10 rounded-xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:shadow-2xl hover:shadow-secondary/20 hover:-translate-y-2">
                <!-- Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden">
                    <img src="assets/home/istana-merdeka.jpg" alt="Kost Istana Merdeka 3" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/60 to-transparent"></div>
                    
                    <!-- Badge -->
                    <div class="absolute top-4 right-4 bg-secondary/90 backdrop-blur-sm px-3 py-1.5 rounded-full">
                        <span class="text-primary font-black text-xs tracking-wider">KHUSUS PUTRA</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <h4 class="text-white font-black text-xl sm:text-2xl mb-3 tracking-tight">Kost Istana Merdeka 3</h4>
                    <p class="text-textLight leading-relaxed mb-6">
                        Kost khusus putra yang letaknya berada di lantai 2 B11N Gym Purwokerto
                    </p>
                    
                    <!-- Button -->
                    <a href="{{ route('kost') }}" target="_blank" class="inline-block group/btn relative px-6 py-3 bg-gradient-to-r from-secondary to-yellow-500 text-primary font-black text-sm tracking-wider rounded-md overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-secondary/50">
                        <span class="relative z-10">Kunjungi Ekosistem</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-secondary opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- STORE SECTION -->
<section id="store" class="relative py-20 sm:py-24 lg:py-32 bg-primary overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-1/2 left-0 transform -translate-y-1/2 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
    <div class="absolute top-1/4 right-0 w-80 h-80 bg-secondary/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            
            <!-- Content Side (Left) -->
            <div class="order-2 lg:order-1">
                <!-- Subheader -->
                <p class="text-secondary font-bold text-sm sm:text-base tracking-[0.3em] mb-4 uppercase">
                    STORE
                </p>

                <!-- Main Heading -->
                <h2 class="text-white font-black text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 sm:mb-8 tracking-tight leading-tight">
                    B11N & K1NG <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary via-yellow-400 to-yellow-500">Gym Store</span>
                </h2>

                <!-- Description -->
                <p class="text-textLight text-base sm:text-lg leading-relaxed mb-8 sm:mb-10">
                    B11N & K1NG Gym Store adalah toko yang menjual berbagai minuman protein yang dijual di B11N Gym & K1NG Gym Purwokerto, disini ada banyak jenis minuman baik itu susu protein, air mineral, suplement untuk gym, dan lainnya
                </p>

                <!-- Feature List -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8 sm:mb-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-primary font-bold"></i>
                        </div>
                        <span class="text-white font-semibold text-sm sm:text-base">Susu Protein</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-primary font-bold"></i>
                        </div>
                        <span class="text-white font-semibold text-sm sm:text-base">Air Mineral</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-primary font-bold"></i>
                        </div>
                        <span class="text-white font-semibold text-sm sm:text-base">Supplement Gym</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-primary font-bold"></i>
                        </div>
                        <span class="text-white font-semibold text-sm sm:text-base">Dan Lainnya</span>
                    </div>
                </div>

                <!-- CTA Button -->
                <a href="{{ route('store.biin-king') }}" target="_blank" class="inline-block group relative px-8 sm:px-10 py-4 sm:py-5 bg-gradient-to-r from-secondary to-yellow-500 text-primary font-black text-sm sm:text-base tracking-wider rounded-sm overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-secondary/50">
                    <span class="relative z-10 flex items-center gap-3">
                        Kunjungi Website
                        <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
            </div>

            <!-- Image Side (Right) -->
            <div class="order-1 lg:order-2">
                <div class="group relative">
                    <!-- Main Image Container -->
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl hover:shadow-secondary/30 transition-all duration-500">
                        <img src="assets/home/store.png" alt="B11N & K1NG Gym Store" class="w-full h-auto transition-transform duration-700 group-hover:scale-105" />
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-secondary/20 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-secondary/10 rounded-full blur-2xl"></div>
                    
                    <!-- Badge -->
                    <div class="absolute top-6 right-6 bg-secondary backdrop-blur-sm px-4 py-2 rounded-lg shadow-lg">
                        <p class="text-primary font-black text-xs tracking-wider">OFFICIAL STORE</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- BLOG SECTION -->
<section id="blog" class="relative py-20 sm:py-24 lg:py-32 bg-primary overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-20 right-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-0 w-80 h-80 bg-secondary/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="max-w-3xl mb-12 sm:mb-16 lg:mb-20">
            <p class="text-secondary font-bold text-sm sm:text-base tracking-[0.3em] mb-4 uppercase">
                BLOG
            </p>
            <h2 class="text-white font-black text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 sm:mb-8 tracking-tight leading-tight">
                B1NG EMPIRE <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary via-yellow-400 to-yellow-500">Blog</span>
            </h2>
            <p class="text-textLight text-base sm:text-lg leading-relaxed">
                B1NG EMPIRE Blog adalah Website Blog pribadi kami yang didalamnya berisi informasi, tips & trick, dan berita - berita terbaru yang berkaitan dengan B11N Gym, K1NG Gym, dan Kost Istana Merdeka 3.
            </p>
        </div>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($blog as $blog)
            <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="group block">
                <div class="relative bg-gradient-to-b from-[#0d1623] to-primary border border-secondary/10 rounded-xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:shadow-2xl hover:shadow-secondary/20 hover:-translate-y-2">
                    
                    <!-- Image Container -->
                    <div class="relative h-48 sm:h-56 overflow-hidden">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/40 to-transparent"></div>
                        
                        <!-- Date Badge -->
                        <div class="absolute top-4 left-4 bg-secondary/90 backdrop-blur-sm px-3 py-1.5 rounded-lg">
                            <p class="text-primary font-black text-xs tracking-wider">
                                {{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 sm:p-8">
                        <h4 class="text-white font-black text-lg sm:text-xl mb-3 tracking-tight leading-tight line-clamp-2 group-hover:text-secondary transition-colors duration-300">
                            {{ $blog->title }}
                        </h4>
                        <p class="text-textLight leading-relaxed text-sm sm:text-base line-clamp-3">
                            {!! Str::limit(strip_tags($blog->content), 100) !!}
                        </p>
                        
                        <!-- Read More Link -->
                        <div class="mt-4 flex items-center gap-2 text-secondary font-bold text-sm group-hover:gap-3 transition-all duration-300">
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
        <div class="mt-12 sm:mt-16 text-center">
            <a href="{{ route('blogs.index') }}" target="_blank" class="inline-block group relative px-8 sm:px-10 py-4 sm:py-5 bg-gradient-to-r from-secondary to-yellow-500 text-primary font-black text-sm sm:text-base tracking-wider rounded-sm overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-secondary/50">
                <span class="relative z-10 flex items-center gap-3">
                    View All Posts
                    <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
        </div>
        @endif

    </div>
</section>

<!-- FOOTER SECTION -->
<footer id="contact" class="relative bg-gradient-to-b from-primary to-[#0a0f1a] overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-secondary/5 rounded-full blur-3xl"></div>

    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16 mb-12">
            
            <!-- Brand Column -->
            <div class="lg:col-span-1">
                <!-- Logo -->
                <div class="flex items-center gap-3 mb-6 group">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 relative">
                        <a href="#" class="block">
                            <img src="assets/Logo/empire.png" alt="logo" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6" />
                            <div class="absolute inset-0 bg-secondary/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </a>
                    </div>
                    <span class="text-white font-black text-xl sm:text-2xl leading-tight tracking-wider">
                        B1NG<br />
                        <span class="text-secondary">EMPIRE</span>
                    </span>
                </div>

                <!-- Description -->
                <p class="text-textLight text-sm sm:text-base leading-relaxed mb-6">
                    B1NG EMPIRE adalah sebuah konsep di mana beberapa bisnis atau layanan yang berbeda-beda, namun memiliki kesamaan dalam hal kepemilikan atau target audiens, digabungkan ke dalam satu website. Tujuannya adalah untuk memberikan pengalaman pengguna yang lebih baik, meningkatkan efisiensi, dan memperkuat branding.
                </p>

                <!-- Social Media Links -->
                <div class="flex items-center gap-3">
                    <a href="mailto:sobiin77@gmail.com" class="w-10 h-10 bg-gradient-to-br from-primary to-[#0d1623] border border-secondary/20 rounded-lg flex items-center justify-center text-secondary hover:border-secondary hover:bg-secondary hover:text-primary transition-all duration-300 hover:scale-110">
                        <i class="fas fa-envelope text-lg"></i>
                    </a>
                    <a href="https://wa.me/6281226110988" target="_blank" class="w-10 h-10 bg-gradient-to-br from-primary to-[#0d1623] border border-secondary/20 rounded-lg flex items-center justify-center text-secondary hover:border-secondary hover:bg-secondary hover:text-primary transition-all duration-300 hover:scale-110">
                        <i class="ri-whatsapp-line text-lg"></i>
                    </a>
                    <a href="https://www.instagram.com/biin_gym/" target="_blank" class="w-10 h-10 bg-gradient-to-br from-primary to-[#0d1623] border border-secondary/20 rounded-lg flex items-center justify-center text-secondary hover:border-secondary hover:bg-secondary hover:text-primary transition-all duration-300 hover:scale-110">
                        <i class="ri-instagram-fill text-lg"></i>
                    </a>
                    <a href="https://www.threads.net/@biin_gym?xmt=AQGzKh5EYkbE4G7JIjSwlirbjIADsXrxWWU6UuUKi1XKhFU" target="_blank" class="w-10 h-10 bg-gradient-to-br from-primary to-[#0d1623] border border-secondary/20 rounded-lg flex items-center justify-center text-secondary hover:border-secondary hover:bg-secondary hover:text-primary transition-all duration-300 hover:scale-110">
                        <i class="ri-threads-fill text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div>
                <h4 class="text-white font-black text-lg sm:text-xl mb-6 tracking-tight">Quick Link</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" target="_blank" class="text-textLight hover:text-secondary transition-colors duration-300 flex items-center gap-2 group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>B1NG EMPIRE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gym.biin') }}" target="_blank" class="text-textLight hover:text-secondary transition-colors duration-300 flex items-center gap-2 group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>B11N Gym Website</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gym.king') }}" target="_blank" class="text-textLight hover:text-secondary transition-colors duration-300 flex items-center gap-2 group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>K1NG Gym Website</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kost') }}" target="_blank" class="text-textLight hover:text-secondary transition-colors duration-300 flex items-center gap-2 group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>Kost Istana Merdeka 03 Website</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store.biin-king') }}" target="_blank" class="text-textLight hover:text-secondary transition-colors duration-300 flex items-center gap-2 group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>B11N & K1NG Gym Store</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blogs.index') }}" target="_blank" class="text-textLight hover:text-secondary transition-colors duration-300 flex items-center gap-2 group">
                            <i class="fas fa-chevron-right text-xs text-secondary opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                            <span>B1NG EMPIRE Blog</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div>
                <h4 class="text-white font-black text-lg sm:text-xl mb-6 tracking-tight">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li>
                        <a href="tel:+6289653847651" class="flex items-start gap-4 group hover:bg-secondary/5 p-3 rounded-lg transition-all duration-300">
                            <div class="w-10 h-10 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-phone-fill text-primary text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-white font-bold text-sm mb-1">No. Telephone</h5>
                                <p class="text-textLight text-sm group-hover:text-secondary transition-colors duration-300">+62 896 5384 7651</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:sobiin77@gmail.com" class="flex items-start gap-4 group hover:bg-secondary/5 p-3 rounded-lg transition-all duration-300">
                            <div class="w-10 h-10 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-record-mail-line text-primary text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-white font-bold text-sm mb-1">Email</h5>
                                <p class="text-textLight text-sm group-hover:text-secondary transition-colors duration-300">sobiin77@gmail.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.google.com/maps?q=Jl.+Masjid+Baru,+Arcawinangun,Kec.+Purwokerto+Timur,+Kab.+Banyumas" target="_blank" class="flex items-start gap-4 group hover:bg-secondary/5 p-3 rounded-lg transition-all duration-300">
                            <div class="w-10 h-10 bg-gradient-to-br from-secondary to-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-map-pin-2-fill text-primary text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-white font-bold text-sm mb-1">Alamat</h5>
                                <p class="text-textLight text-sm group-hover:text-secondary transition-colors duration-300">Jl. Masjid Baru, Arcawinangun, Kec. Purwokerto Timur, Kab. Banyumas</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Footer Bottom Bar -->
        <div class="pt-8 border-t border-secondary/10">
            <p class="text-center text-textLight text-sm">
                Copyright © {{ date('Y') }} <span class="text-secondary font-bold">B1NG EMPIRE</span>. All rights reserved.
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
    let lastScroll = 0;
    const navbar = document.querySelector('nav');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            navbar.classList.add('shadow-2xl');
        } else {
            navbar.classList.remove('shadow-2xl');
        }
        
        lastScroll = currentScroll;
    });
</script>
</body>
</html>