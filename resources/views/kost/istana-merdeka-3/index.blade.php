<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- 1. TITLE: Brand + Jenis Kost + Target Lokasi (Kampus) --}}
    <title>Kost Putra Istana Merdeka 03 - Kost Nyaman Dekat UNSOED & UMP Purwokerto</title>

    {{-- 2. META DESCRIPTION: Harga + Fasilitas Utama + Lokasi --}}
    <meta name="description" content="Terima kost putra di Purwokerto mulai Rp 500rb. Fasilitas lengkap: AC/Non-AC, WiFi kencang, kasur empuk, aman & nyaman. Lokasi strategis di Arcawinangun dekat UNSOED & UMP.">

    {{-- 3. KEYWORDS: Kata kunci pencarian anak kost --}}
    <meta name="keywords" content="kost putra purwokerto, kost dekat unsoed, kost dekat ump, kost arcawinangun, kost murah purwokerto, kost ac purwokerto, kost istana merdeka 03, kost harian purwokerto">

    <meta name="author" content="Kost Istana Merdeka 03">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- 4. GEO TAGS (Lokasi sama dengan B11N Gym karena satu gedung) --}}
    <meta name="geo.region" content="ID-JT" />
    <meta name="geo.placename" content="Purwokerto" />
    <meta name="geo.position" content="-7.4243;109.2391" />
    <meta name="ICBM" content="-7.4243, 109.2391" />

    {{-- 5. OPEN GRAPH (Tampilan Share WA/IG) --}}
    <meta property="og:type" content="business.business">
    <meta property="og:title" content="Kost Putra Istana Merdeka 03 - Mulai Rp 500rb/Bulan">
    <meta property="og:description" content="Hunian kost eksklusif di atas B11N Gym. Fasilitas lengkap, lokasi strategis, lingkungan kondusif. Pesan kamar sekarang!">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/home/istana-merdeka.jpg') }}">
    <meta property="og:site_name" content="Kost Istana Merdeka 03">

    {{-- 6. SCHEMA MARKUP (LodgingBusiness / Hostel) --}}
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "LodgingBusiness",
            "name": "Kost Putra Istana Merdeka 03",
            "image": "{{ asset('assets/home/istana-merdeka.jpg') }}",
            "telephone": "+6289653847651",
            "url": "{{ url('/') }}",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Jl. Masjid Baru, Arcawinangun (Lantai 2 B11N Gym)",
                "addressLocality": "Purwokerto Timur",
                "addressRegion": "Jawa Tengah",
                "postalCode": "53113",
                "addressCountry": "ID"
            },
            "geo": {
                "@type": "GeoCoordinates",
                "latitude": -7.4243,
                "longitude": 109.2391
            },
            "priceRange": "Rp 500.000 - Rp 750.000",
            "amenityFeature": [{
                    "@type": "LocationFeatureSpecification",
                    "name": "AC",
                    "value": "True"
                },
                {
                    "@type": "LocationFeatureSpecification",
                    "name": "WiFi High Speed",
                    "value": "True"
                },
                {
                    "@type": "LocationFeatureSpecification",
                    "name": "Gym Access (B11N)",
                    "value": "True"
                }
            ]
        }
    </script>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/kost.png'))">

    {{-- Stylesheets & Scripts --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
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

    <style>
        .floating-menu .action { opacity: 0; transform: translateY(20px); transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .floating-menu.active .action { opacity: 1; transform: translateY(0); }
        .floating-menu.active .action:nth-child(1) { transition-delay: 0.05s; }
        .floating-menu.active .action:nth-child(2) { transition-delay: 0.1s; }
        .floating-menu.active .action:nth-child(3) { transition-delay: 0.15s; }
        .floating-menu.active .action:nth-child(4) { transition-delay: 0.2s; }

        .reveal { opacity: 0; transform: translateY(50px); transition: all 0.8s cubic-bezier(0.5, 0, 0, 1); }
        .reveal.revealed { opacity: 1; transform: translateY(0); }
        .reveal-left { opacity: 0; transform: translateX(-50px); transition: all 0.8s cubic-bezier(0.5, 0, 0, 1); }
        .reveal-left.revealed { opacity: 1; transform: translateX(0); }
        .reveal-right { opacity: 0; transform: translateX(50px); transition: all 0.8s cubic-bezier(0.5, 0, 0, 1); }
        .reveal-right.revealed { opacity: 1; transform: translateX(0); }
        .reveal-stagger > * { opacity: 0; transform: translateY(30px); transition: all 0.6s cubic-bezier(0.5, 0, 0, 1); }
        .reveal-stagger.revealed > *:nth-child(1) { opacity: 1; transform: translateY(0); transition-delay: 0.1s; }
        .reveal-stagger.revealed > *:nth-child(2) { opacity: 1; transform: translateY(0); transition-delay: 0.2s; }
        .reveal-stagger.revealed > *:nth-child(3) { opacity: 1; transform: translateY(0); transition-delay: 0.3s; }
        .reveal-stagger.revealed > *:nth-child(4) { opacity: 1; transform: translateY(0); transition-delay: 0.4s; }
        .reveal-stagger.revealed > *:nth-child(5) { opacity: 1; transform: translateY(0); transition-delay: 0.5s; }
        .reveal-stagger.revealed > *:nth-child(6) { opacity: 1; transform: translateY(0); transition-delay: 0.6s; }
        .reveal-stagger.revealed > *:nth-child(7) { opacity: 1; transform: translateY(0); transition-delay: 0.7s; }
        .reveal-stagger.revealed > *:nth-child(8) { opacity: 1; transform: translateY(0); transition-delay: 0.8s; }
        .reveal-stagger.revealed > *:nth-child(9) { opacity: 1; transform: translateY(0); transition-delay: 0.9s; }

        body { font-family: 'Poppins', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 0.02em; }

        .swiper { padding-bottom: 3rem; }
        .swiper-pagination-bullet { height: 12px; width: 12px; }
        .swiper-pagination-bullet-active { background-color: #f6ac0f; }

        .room-layout .room { width: 60px; height: 50px; display: flex; align-items: center; justify-content: center; border: 2px solid #0f1a2c; border-radius: 4px; font-size: 18px; font-weight: bold; color: #0f1a2c; cursor: pointer; transition: all 0.3s; }
        .room-layout .room:hover:not(.booked) { border-color: #f6ac0f; color: #f6ac0f; }
        .room-layout .room.booked { background-color: #ef4444; color: white; border-color: #ef4444; cursor: not-allowed; }
        .room-layout .room.selected { background-color: #22c55e; color: white; border-color: #22c55e; }

        button:disabled { background-color: #ccc; color: #666; cursor: not-allowed; opacity: 0.6; }
        .form-hidden { display: none !important; }
    </style>
</head>
<body class="bg-white font-body">

<!-- NAVIGATION -->
<nav class="fixed top-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-14 h-14 sm:w-16 sm:h-16">
                    <a href="#"><img src="assets/Logo/kost.png" alt="logo" class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-110" /></a>
                </div>
                <span class="font-heading text-primary font-bold text-lg sm:text-xl leading-tight tracking-wider uppercase">
                    KOST ISTANA<br /><span class="text-secondary">MERDEKA 03</span>
                </span>
            </div>
            <ul class="hidden lg:flex items-center gap-8">
                <li><a href="{{ route('home') }}" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">HOME</a></li>
                <li><a href="#about" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">TENTANG KAMI</a></li>
                <li><a href="#room" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">KAMAR</a></li>
                <li><a href="#feature" class="font-heading text-textDark hover:text-secondary font-semibold text-base transition-colors duration-300 tracking-wider">FASILITAS</a></li>
                <li><a href="#booking" class="font-heading px-6 py-3 bg-secondary text-white font-semibold text-base rounded hover:bg-white hover:text-secondary border-2 border-secondary transition-all duration-300 shadow-md hover:shadow-lg tracking-wider">PESAN KAMAR</a></li>
            </ul>
            <button id="menu-btn" class="lg:hidden text-primary text-3xl hover:text-secondary transition-colors duration-300">
                <i class="ri-menu-line"></i>
            </button>
        </div>
        <div id="nav-links" class="hidden lg:hidden overflow-hidden transition-all duration-300 ease-in-out">
            <ul class="pb-6 space-y-2 border-t border-gray-100 mt-2 pt-4">
                <li><a href="{{ route('home') }}" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">HOME</a></li>
                <li><a href="#about" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">TENTANG KAMI</a></li>
                <li><a href="#room" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">KAMAR</a></li>
                <li><a href="#feature" class="block px-4 py-3 text-textDark hover:text-secondary hover:bg-extraLight font-semibold text-base transition-all duration-300 rounded">FASILITAS</a></li>
                <li><a href="#booking" class="block px-4 py-3 text-white bg-secondary hover:bg-white hover:text-secondary border-2 border-secondary font-bold text-base transition-all duration-300 rounded text-center shadow-md">PESAN KAMAR</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- FLOATING ACTION MENU -->
<div class="floating-menu fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-50 flex flex-col items-center gap-4">
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
    <a href="#" class="trigger w-14 h-14 sm:w-16 sm:h-16 bg-secondary rounded-full flex items-center justify-center shadow-xl hover:bg-yellow-600 transition-all duration-300 text-white text-xl sm:text-2xl font-bold">
        <i class="fas fa-plus transition-transform duration-300"></i>
    </a>
</div>

<!-- HERO HEADER -->
<header id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
    <div class="absolute inset-0 z-0">
        <img src="assets/facilities/ber-ac.png" alt="Kost Istana Merdeka 03" class="w-full h-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 to-black/30"></div>
    </div>
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24">
        <div class="max-w-2xl reveal">
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-6 uppercase">WELCOME TO</p>
            <h1 class="font-heading text-white font-bold text-5xl sm:text-6xl md:text-7xl lg:text-8xl leading-none mb-8 tracking-tight uppercase">
                Kost Putra<br /><span class="text-secondary">Istana Merdeka 03</span>
            </h1>
            <p class="font-body text-gray-300 text-lg sm:text-xl leading-relaxed mb-10 max-w-xl">
                Hunian premium eksklusif di atas B11N Gym Purwokerto. Nyaman, strategis, dan terjangkau.
            </p>
            <a href="#booking" class="font-heading inline-block px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                Pesan Kamar Sekarang
            </a>
        </div>
    </div>
</header>

<!-- ABOUT SECTION -->
<section id="about" class="relative py-20 sm:py-24 lg:py-32 bg-white">
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20 reveal">
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">ABOUT US</p>
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 tracking-tight leading-tight uppercase">
                Kost <span class="text-secondary">Istana Merdeka 03</span>
            </h2>
            <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed mb-10 max-w-2xl mx-auto">
                Selamat datang di Kost Istana Merdeka 3, pilihan terbaik bagi Anda yang mencari hunian premium dengan kenyamanan maksimal. Terletak strategis di atas B11N Gym Purwokerto, kost khusus putra ini menawarkan suasana eksklusif dan tenang, jauh dari kebisingan meskipun berada di area gym. Nikmati fasilitas terbaik seperti AC di setiap kamar dan WiFi berkecepatan tinggi.
            </p>
            <a href="#booking" class="font-heading inline-block px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                Pesan Sekarang
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 reveal-stagger">
            <div class="group relative overflow-hidden rounded aspect-[4/5] shadow-lg hover:shadow-2xl transition-all duration-500">
                <img src="assets/facilities/ac.png" alt="Fasilitas AC" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            </div>
            <div class="bg-extraLight rounded p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="w-16 h-16 mb-6 bg-secondary rounded-sm flex items-center justify-center shadow-md">
                    <i class="ri-user-line text-2xl text-white"></i>
                </div>
                <h4 class="font-heading text-2xl font-bold text-textDark mb-4 tracking-tight uppercase">Fasilitas Premium</h4>
                <p class="font-body text-textLight leading-relaxed text-base">Kamar ber-AC, WiFi cepat, kasur empuk, dan lemari luas untuk kenyamanan maksimal.</p>
            </div>
            <div class="group relative overflow-hidden rounded aspect-[4/5] shadow-lg hover:shadow-2xl transition-all duration-500">
                <img src="assets/home/biin-gym.jpg" alt="Lokasi Strategis" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            </div>
            <div class="bg-extraLight rounded p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="w-16 h-16 mb-6 bg-secondary rounded-sm flex items-center justify-center shadow-md">
                    <i class="ri-calendar-check-line text-2xl text-white"></i>
                </div>
                <h4 class="font-heading text-2xl font-bold text-textDark mb-4 tracking-tight uppercase">Lokasi Strategis</h4>
                <p class="font-body text-textLight leading-relaxed text-base">Dekat UNSOED dan UMP, memudahkan akses ke kampus dan fasilitas umum.</p>
            </div>
        </div>
    </div>
</section>
<!-- ROOM SECTION -->
<section id="room" class="relative py-20 sm:py-24 lg:py-32 bg-extraLight">
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20 reveal">
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">ROOMS</p>
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 tracking-tight leading-tight uppercase">
                Tipe <span class="text-secondary">Kamar Kami</span>
            </h2>
            <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
                Kami menawarkan dua tipe kamar yang dirancang untuk memenuhi kebutuhan Anda.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto reveal-stagger">
            <!-- Non AC Room -->
            <div class="group bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <div class="relative overflow-hidden aspect-[4/3]">
                    <img src="assets/facilities/non-ac.png" alt="Kamar Non AC" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute top-4 left-4">
                        <span class="bg-primary text-white font-heading text-sm font-bold px-4 py-2 rounded tracking-wider uppercase">Non AC</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
                <div class="p-8">
                    <h3 class="font-heading text-2xl font-bold text-textDark mb-4 tracking-tight uppercase">Kamar Non AC</h3>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> Kasur empuk premium</li>
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> Lemari pakaian besar</li>
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> Meja belajar</li>
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> WiFi kecepatan tinggi</li>
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> Kamar mandi bersih</li>
                    </ul>
                    <div class="flex items-end gap-2 mb-6">
                        <span class="font-heading text-3xl font-bold text-primary">Rp 550.000</span>
                        <span class="text-textLight text-base mb-1">/ bulan</span>
                    </div>
                    <a href="#booking" class="block text-center font-heading px-6 py-4 bg-secondary text-white font-semibold rounded hover:bg-white hover:text-secondary border-2 border-secondary transition-all duration-300 shadow-md hover:shadow-lg uppercase tracking-wider">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
            <!-- AC Room -->
            <div class="group bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 ring-2 ring-secondary">
                <div class="relative overflow-hidden aspect-[4/3]">
                    <img src="assets/facilities/ber-ac.png" alt="Kamar AC" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute top-4 left-4">
                        <span class="bg-secondary text-white font-heading text-sm font-bold px-4 py-2 rounded tracking-wider uppercase">Populer</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
                <div class="p-8">
                    <h3 class="font-heading text-2xl font-bold text-textDark mb-4 tracking-tight uppercase">Kamar AC</h3>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> AC sejuk 24 jam</li>
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> Kasur empuk premium</li>
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> Lemari pakaian besar</li>
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> Meja belajar</li>
                        <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> WiFi & kamar mandi</li>
                    </ul>
                    <div class="flex items-end gap-2 mb-6">
                        <span class="font-heading text-3xl font-bold text-primary">Rp 700.000</span>
                        <span class="text-textLight text-base mb-1">/ bulan</span>
                    </div>
                    <a href="#booking" class="block text-center font-heading px-6 py-4 bg-secondary text-white font-semibold rounded hover:bg-white hover:text-secondary border-2 border-secondary transition-all duration-300 shadow-md hover:shadow-lg uppercase tracking-wider">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ROOM TOUR VIDEO -->
<section class="relative py-20 sm:py-24 lg:py-32 bg-primary overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><rect fill=%22none%22 width=%2260%22 height=%2260%22/><path d=%22M0 0h60v60H0z%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>'); background-size: 60px 60px;"></div>
    </div>
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="reveal">
                <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">ROOM TOUR</p>
                <h2 class="font-heading text-white font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 tracking-tight leading-tight uppercase">
                    Lihat <span class="text-secondary">Kamar Kami</span>
                </h2>
                <p class="font-body text-gray-300 text-lg sm:text-xl leading-relaxed mb-8">
                    Lihat langsung suasana kamar kami melalui video room tour. Rasakan kenyamanan dan keindahan kamar kami sebelum Anda memutuskan untuk memesan.
                </p>
                <a href="#booking" class="font-heading inline-block px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                    Pesan Sekarang
                </a>
            </div>
            <div class="reveal">
                <div class="relative rounded overflow-hidden shadow-2xl aspect-video">
                    <iframe src="https://www.youtube.com/embed/YOUR_VIDEO_ID" title="Room Tour Kost Istana Merdeka 03" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FEATURES SECTION -->
<section id="feature" class="relative py-20 sm:py-24 lg:py-32 bg-white">
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20 reveal">
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">FEATURES</p>
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 tracking-tight leading-tight uppercase">
                Fasilitas <span class="text-secondary">Kami</span>
            </h2>
            <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
                Kami menyediakan berbagai fasilitas unggulan untuk menunjang kenyamanan hunian Anda.
            </p>
        </div>

        @php
        $features = [
            ['icon' => 'fas fa-snowflake', 'title' => 'AC Berkualitas', 'desc' => 'AC modern di setiap kamar untuk kenyamanan suhu optimal sepanjang hari.'],
            ['icon' => 'fas fa-wifi', 'title' => 'WiFi Cepat', 'desc' => 'Internet berkecepatan tinggi untuk mendukung aktivitas online dan belajar.'],
            ['icon' => 'fas fa-bed', 'title' => 'Kasur Premium', 'desc' => 'Kasur empuk berkualitas tinggi untuk tidur yang nyenyak dan nyaman.'],
            ['icon' => 'fas fa-lock', 'title' => 'Keamanan Terjamin', 'desc' => 'Kunci kamar individual dan akses masuk yang aman 24 jam.'],
            ['icon' => 'fas fa-parking', 'title' => 'Parkir Luas', 'desc' => 'Area parkir yang luas untuk motor dan kendaraan penghuni kost.'],
            ['icon' => 'fas fa-tint', 'title' => 'Air Bersih', 'desc' => 'Suplai air bersih 24/7 untuk kebutuhan mandi dan mencuci.'],
            ['icon' => 'fas fa-tshirt', 'title' => 'Mesin Cuci', 'desc' => 'Fasilitas mesin cuci bersama untuk memudahkan kebutuhan laundry.'],
            ['icon' => 'fas fa-utensils', 'title' => 'Dapur Bersama', 'desc' => 'Dapur lengkap dengan peralatan masak untuk kebutuhan memasak.'],
            ['icon' => 'fas fa-dumbbell', 'title' => 'Akses Gym', 'desc' => 'Akses langsung ke B11N Gym di lantai bawah untuk berolahraga.'],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger">
            @foreach ($features as $feature)
            <div class="group bg-extraLight rounded p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:bg-primary">
                <div class="w-16 h-16 mb-6 bg-secondary rounded-sm flex items-center justify-center shadow-md group-hover:bg-white transition-colors duration-300">
                    <i class="{{ $feature['icon'] }} text-2xl text-white group-hover:text-secondary transition-colors duration-300"></i>
                </div>
                <h4 class="font-heading text-xl font-bold text-textDark mb-3 tracking-tight uppercase group-hover:text-white transition-colors duration-300">{{ $feature['title'] }}</h4>
                <p class="font-body text-textLight leading-relaxed text-base group-hover:text-gray-300 transition-colors duration-300">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- TESTIMONIALS SECTION -->
<section class="relative py-20 sm:py-24 lg:py-32 bg-extraLight">
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20 reveal">
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">TESTIMONIALS</p>
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 tracking-tight leading-tight uppercase">
                Apa Kata <span class="text-secondary">Penghuni Kami</span>
            </h2>
            <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
                Dengarkan pengalaman para penghuni yang telah merasakan kenyamanan kost kami.
            </p>
        </div>
        <div class="swiper testimonialSwiper reveal">
            <div class="swiper-wrapper pb-12">
                @if(isset($testimonis) && count($testimonis) > 0)
                    @foreach($testimonis as $testi)
                    <div class="swiper-slide">
                        <div class="bg-white rounded p-8 shadow-lg hover:shadow-xl transition-all duration-300 mx-2">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 bg-secondary rounded-full flex items-center justify-center text-white font-heading font-bold text-xl uppercase">
                                    {{ substr($testi->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-heading text-textDark font-bold text-lg">{{ $testi->name }}</h4>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="ri-star-fill text-secondary text-sm"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="font-body text-textLight leading-relaxed text-base italic">"{{ $testi->content }}"</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="swiper-slide">
                        <div class="bg-white rounded p-8 shadow-lg mx-2">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 bg-secondary rounded-full flex items-center justify-center text-white font-heading font-bold text-xl">A</div>
                                <div>
                                    <h4 class="font-heading text-textDark font-bold text-lg">Ahmad R.</h4>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="ri-star-fill text-secondary text-sm"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="font-body text-textLight leading-relaxed text-base italic">"Kost yang sangat nyaman dan bersih. Fasilitas lengkap dengan harga terjangkau. Sangat direkomendasikan!"</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="bg-white rounded p-8 shadow-lg mx-2">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 bg-secondary rounded-full flex items-center justify-center text-white font-heading font-bold text-xl">B</div>
                                <div>
                                    <h4 class="font-heading text-textDark font-bold text-lg">Budi S.</h4>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="ri-star-fill text-secondary text-sm"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="font-body text-textLight leading-relaxed text-base italic">"Lokasi sangat strategis, dekat kampus UNSOED. WiFi kencang dan kamar luas. Worth it!"</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="swiper-pagination !relative mt-8"></div>
        </div>
    </div>
</section>

<!-- GALLERY SECTION -->
<section id="gallery" class="relative py-20 sm:py-24 lg:py-32 bg-white">
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20 reveal">
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">GALLERY</p>
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 tracking-tight leading-tight uppercase">
                Galeri <span class="text-secondary">Foto</span>
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal-stagger">
            @if(isset($gallery) && count($gallery) > 0)
                @foreach($gallery as $item)
                <div class="group relative overflow-hidden rounded aspect-[4/3] shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer">
                    <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end">
                        <div class="p-6">
                            <h4 class="font-heading text-white font-bold text-lg uppercase tracking-wider">{{ $item->title }}</h4>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="group relative overflow-hidden rounded aspect-[4/3] shadow-lg hover:shadow-2xl transition-all duration-500">
                    <img src="assets/facilities/ber-ac.png" alt="Gallery" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end">
                        <div class="p-6"><h4 class="font-heading text-white font-bold text-lg uppercase tracking-wider">Kamar AC</h4></div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded aspect-[4/3] shadow-lg hover:shadow-2xl transition-all duration-500">
                    <img src="assets/facilities/non-ac.png" alt="Gallery" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end">
                        <div class="p-6"><h4 class="font-heading text-white font-bold text-lg uppercase tracking-wider">Kamar Non AC</h4></div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded aspect-[4/3] shadow-lg hover:shadow-2xl transition-all duration-500">
                    <img src="assets/facilities/ac.png" alt="Gallery" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end">
                        <div class="p-6"><h4 class="font-heading text-white font-bold text-lg uppercase tracking-wider">Fasilitas</h4></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
<!-- BOOKING SECTION -->
<section id="booking" class="relative py-20 sm:py-24 lg:py-32 bg-primary overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><rect fill=%22none%22 width=%2260%22 height=%2260%22/><path d=%22M0 0h60v60H0z%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>'); background-size: 60px 60px;"></div>
    </div>
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20 reveal">
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">BOOKING</p>
            <h2 class="font-heading text-white font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 tracking-tight leading-tight uppercase">
                Pesan <span class="text-secondary">Kamar</span>
            </h2>
            <p class="font-body text-gray-300 text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
                Isi formulir di bawah ini untuk memesan kamar kost. Pilih tipe kamar dan nomor kamar sesuai ketersediaan.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Booking Form -->
            <div class="bg-white rounded p-8 sm:p-10 shadow-2xl reveal">
                <h3 class="font-heading text-2xl font-bold text-textDark mb-8 tracking-tight uppercase">Form Pemesanan</h3>

                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded">
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded">
                    <p class="text-red-700 font-semibold">{{ session('error') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('kost.book') }}" enctype="multipart/form-data" id="bookingForm" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Masukkan nama lengkap" class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body placeholder:text-textLight" />
                    </div>
                    <div>
                        <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" required placeholder="Masukkan email" class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body placeholder:text-textLight" />
                    </div>
                    <div>
                        <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">No. Telepon</label>
                        <input type="tel" name="phone" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body placeholder:text-textLight" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Tanggal Masuk</label>
                            <input type="text" name="date" id="datePicker" required placeholder="Pilih tanggal" class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body placeholder:text-textLight" />
                        </div>
                        <div>
                            <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Tanggal Keluar</label>
                            <input type="text" name="end_date" id="endDatePicker" required placeholder="Pilih tanggal" class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body placeholder:text-textLight" />
                        </div>
                    </div>
                    <div>
                        <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Tipe Kamar</label>
                        <select name="room_type" id="roomType" required class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body">
                            <option value="">Pilih tipe kamar</option>
                            <option value="500rb - Non AC">Non AC - Rp 550.000/bulan</option>
                            <option value="750rb - AC">AC - Rp 700.000/bulan</option>
                        </select>
                    </div>

                    <input type="hidden" name="room_number" id="selectedRoom" required />
                    <input type="hidden" name="price" id="roomPrice" />

                    <!-- Room Layout -->
                    <div>
                        <label class="block font-heading text-textDark font-semibold text-sm mb-4 uppercase tracking-wider">Pilih Kamar</label>
                        <div class="room-layout bg-extraLight rounded p-6 border-2 border-gray-200">
                            <div class="grid grid-cols-5 gap-3 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                <div class="room-cell aspect-square rounded flex items-center justify-center cursor-pointer border-2 border-gray-300 bg-white hover:border-secondary transition-all duration-300 font-heading font-bold text-textDark text-sm
                                    {{ in_array($i, $bookedRooms ?? []) ? 'booked !bg-red-100 !border-red-300 !text-red-400 !cursor-not-allowed' : '' }}"
                                    data-room="{{ $i }}" data-type="500rb - Non AC">
                                    {{ $i }}
                                </div>
                                @endfor
                            </div>
                            <div class="w-full h-px bg-gray-300 my-4"></div>
                            <div class="grid grid-cols-5 gap-3 mb-4">
                                @for($i = 6; $i <= 10; $i++)
                                <div class="room-cell aspect-square rounded flex items-center justify-center cursor-pointer border-2 border-gray-300 bg-white hover:border-secondary transition-all duration-300 font-heading font-bold text-textDark text-sm
                                    {{ in_array($i, $bookedRooms ?? []) ? 'booked !bg-red-100 !border-red-300 !text-red-400 !cursor-not-allowed' : '' }}"
                                    data-room="{{ $i }}" data-type="750rb - AC">
                                    {{ $i }}
                                </div>
                                @endfor
                            </div>
                            <div class="flex items-center gap-6 mt-4 text-sm font-body text-textLight">
                                <div class="flex items-center gap-2"><div class="w-4 h-4 rounded border-2 border-gray-300 bg-white"></div> Tersedia</div>
                                <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-secondary border-2 border-secondary"></div> Dipilih</div>
                                <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-red-100 border-2 border-red-300"></div> Terisi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Metode Pembayaran</label>
                        <div class="flex gap-4">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="paymentMethod" value="cash" class="hidden peer" checked />
                                <div class="peer-checked:border-secondary peer-checked:bg-secondary/10 border-2 border-gray-200 rounded p-4 text-center transition-all duration-300 hover:border-secondary">
                                    <i class="ri-money-dollar-circle-line text-2xl text-textDark peer-checked:text-secondary mb-1 block"></i>
                                    <span class="font-heading font-semibold text-sm text-textDark uppercase tracking-wider">Cash</span>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="paymentMethod" value="transfer" class="hidden peer" />
                                <div class="peer-checked:border-secondary peer-checked:bg-secondary/10 border-2 border-gray-200 rounded p-4 text-center transition-all duration-300 hover:border-secondary">
                                    <i class="ri-bank-card-line text-2xl text-textDark peer-checked:text-secondary mb-1 block"></i>
                                    <span class="font-heading font-semibold text-sm text-textDark uppercase tracking-wider">Transfer</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Proof (shown when transfer selected) -->
                    <div id="paymentProofContainer" class="hidden">
                        <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Bukti Transfer</label>
                        <input type="file" name="paymentProof" accept="image/*" class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-secondary file:text-white file:font-semibold file:cursor-pointer" />
                    </div>

                    <button type="submit" id="submitBtn" class="w-full font-heading px-8 py-4 bg-secondary text-white font-semibold text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 uppercase tracking-wider flex items-center justify-center gap-2">
                        <span id="btnText">Pesan Sekarang</span>
                        <span id="btnLoader" class="hidden"><i class="ri-loader-4-line animate-spin text-xl"></i></span>
                    </button>
                </form>
            </div>

            <!-- Room Info Side -->
            <div class="space-y-8 reveal">
                <div class="bg-white/10 backdrop-blur rounded p-8 border border-white/20">
                    <h3 class="font-heading text-white text-2xl font-bold mb-6 tracking-tight uppercase">Informasi Harga</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-white/10">
                            <span class="font-body text-gray-300">Kamar Non AC</span>
                            <span class="font-heading text-secondary font-bold text-xl">Rp 550.000</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-white/10">
                            <span class="font-body text-gray-300">Kamar AC</span>
                            <span class="font-heading text-secondary font-bold text-xl">Rp 700.000</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-body text-gray-300">Pembayaran</span>
                            <span class="font-heading text-white font-bold">Per Bulan</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded p-8 border border-white/20">
                    <h3 class="font-heading text-white text-2xl font-bold mb-6 tracking-tight uppercase">Kontak Kami</h3>
                    <div class="space-y-4">
                        <a href="https://wa.me/6285747437700" target="_blank" class="flex items-center gap-4 text-gray-300 hover:text-secondary transition-colors duration-300 group">
                            <div class="w-12 h-12 bg-secondary/20 rounded-full flex items-center justify-center group-hover:bg-secondary/40 transition-colors duration-300">
                                <i class="ri-whatsapp-line text-xl text-secondary"></i>
                            </div>
                            <span class="font-body">0857-4743-7700</span>
                        </a>
                        <a href="https://www.instagram.com/istana_merdeka_03" target="_blank" class="flex items-center gap-4 text-gray-300 hover:text-secondary transition-colors duration-300 group">
                            <div class="w-12 h-12 bg-secondary/20 rounded-full flex items-center justify-center group-hover:bg-secondary/40 transition-colors duration-300">
                                <i class="ri-instagram-line text-xl text-secondary"></i>
                            </div>
                            <span class="font-body">@istana_merdeka_03</span>
                        </a>
                        <div class="flex items-center gap-4 text-gray-300">
                            <div class="w-12 h-12 bg-secondary/20 rounded-full flex items-center justify-center">
                                <i class="ri-map-pin-line text-xl text-secondary"></i>
                            </div>
                            <span class="font-body">Jl. Merdeka No. 03, Purwokerto</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- BLOG SECTION -->
<section class="relative py-20 sm:py-24 lg:py-32 bg-extraLight">
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20 reveal">
            <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">BLOG</p>
            <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl mb-6 tracking-tight leading-tight uppercase">
                Artikel <span class="text-secondary">Terbaru</span>
            </h2>
            <p class="font-body text-textLight text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
                Baca artikel terbaru seputar tips hunian, gaya hidup sehat, dan informasi kost.
            </p>
        </div>
        @if(isset($blog) && count($blog) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger">
            @foreach($blog as $item)
            <article class="group bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <div class="relative overflow-hidden aspect-[16/10]">
                    <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute top-4 left-4">
                        <span class="bg-secondary text-white font-heading text-xs font-bold px-3 py-1.5 rounded tracking-wider uppercase">Blog</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4 text-sm text-textLight font-body">
                        <span class="flex items-center gap-1.5"><i class="ri-calendar-line text-secondary"></i> {{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-textDark mb-3 tracking-tight leading-tight uppercase group-hover:text-secondary transition-colors duration-300">
                        {{ Str::limit($item->title, 50) }}
                    </h3>
                    <p class="font-body text-textLight leading-relaxed text-sm mb-4">
                        {{ Str::limit(strip_tags($item->content), 100) }}
                    </p>
                    <a href="{{ route('blogs.show', $item->slug) }}" class="font-heading text-secondary font-semibold text-sm flex items-center gap-2 hover:gap-3 transition-all duration-300 uppercase tracking-wider">
                        Baca Selengkapnya <i class="ri-arrow-right-up-line"></i>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        <div class="text-center mt-12 reveal">
            <a href="{{ route('blogs.index') }}" class="font-heading inline-block px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                Lihat Semua Artikel
            </a>
        </div>
        @else
        <div class="text-center py-12 reveal">
            <i class="ri-article-line text-6xl text-gray-300 mb-4 block"></i>
            <p class="font-body text-textLight text-lg">Belum ada artikel tersedia.</p>
        </div>
        @endif
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-primary pt-20 pb-8">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16 pb-16 border-b border-white/10">
            <!-- Brand -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <img src="assets/Logo/kost.png" alt="Logo" class="w-14 h-14 object-contain" />
                    <span class="font-heading text-white font-bold text-xl leading-tight tracking-wider uppercase">
                        KOST ISTANA<br /><span class="text-secondary">MERDEKA 03</span>
                    </span>
                </div>
                <p class="font-body text-gray-400 leading-relaxed text-base mb-6">
                    Hunian premium eksklusif di atas B11N Gym Purwokerto. Nyaman, strategis, dan terjangkau untuk mahasiswa dan profesional muda.
                </p>
                <div class="flex items-center gap-4">
                    <a href="https://www.instagram.com/istana_merdeka_03" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-gray-400 hover:bg-secondary hover:text-white transition-all duration-300">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="https://wa.me/6285747437700" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-gray-400 hover:bg-secondary hover:text-white transition-all duration-300">
                        <i class="ri-whatsapp-line"></i>
                    </a>
                    <a href="https://www.tiktok.com/@b1ng_empire" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-gray-400 hover:bg-secondary hover:text-white transition-all duration-300">
                        <i class="ri-tiktok-line"></i>
                    </a>
                </div>
            </div>
            <!-- Quick Links -->
            <div>
                <h4 class="font-heading text-white font-bold text-lg mb-6 tracking-wider uppercase">Quick Links</h4>
                <ul class="space-y-4">
                    <li><a href="#about" class="font-body text-gray-400 hover:text-secondary transition-colors duration-300 flex items-center gap-2"><i class="ri-arrow-right-s-line text-secondary"></i> Tentang Kami</a></li>
                    <li><a href="#room" class="font-body text-gray-400 hover:text-secondary transition-colors duration-300 flex items-center gap-2"><i class="ri-arrow-right-s-line text-secondary"></i> Tipe Kamar</a></li>
                    <li><a href="#feature" class="font-body text-gray-400 hover:text-secondary transition-colors duration-300 flex items-center gap-2"><i class="ri-arrow-right-s-line text-secondary"></i> Fasilitas</a></li>
                    <li><a href="#gallery" class="font-body text-gray-400 hover:text-secondary transition-colors duration-300 flex items-center gap-2"><i class="ri-arrow-right-s-line text-secondary"></i> Galeri</a></li>
                    <li><a href="#booking" class="font-body text-gray-400 hover:text-secondary transition-colors duration-300 flex items-center gap-2"><i class="ri-arrow-right-s-line text-secondary"></i> Pesan Kamar</a></li>
                    <li><a href="{{ route('home') }}" class="font-body text-gray-400 hover:text-secondary transition-colors duration-300 flex items-center gap-2"><i class="ri-arrow-right-s-line text-secondary"></i> B1NG Empire</a></li>
                </ul>
            </div>
            <!-- Contact -->
            <div>
                <h4 class="font-heading text-white font-bold text-lg mb-6 tracking-wider uppercase">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 text-gray-400">
                        <i class="ri-map-pin-line text-secondary mt-1"></i>
                        <span class="font-body">Jl. Merdeka No. 03, Purwokerto, Jawa Tengah</span>
                    </li>
                    <li>
                        <a href="https://wa.me/6285747437700" target="_blank" class="flex items-center gap-3 text-gray-400 hover:text-secondary transition-colors duration-300">
                            <i class="ri-whatsapp-line text-secondary"></i>
                            <span class="font-body">0857-4743-7700</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/istana_merdeka_03" target="_blank" class="flex items-center gap-3 text-gray-400 hover:text-secondary transition-colors duration-300">
                            <i class="ri-instagram-line text-secondary"></i>
                            <span class="font-body">@istana_merdeka_03</span>
                        </a>
                    </li>
                </ul>
                <div class="mt-6">
                    <a href="https://wa.me/6285747437700" target="_blank" class="font-heading inline-block px-6 py-3 bg-secondary text-white font-semibold text-sm rounded shadow-md hover:bg-white hover:text-secondary border-2 border-secondary transition-all duration-300 uppercase tracking-wider">
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
        <div class="pt-8 text-center">
            <p class="font-body text-gray-500 text-sm">
                &copy; {{ date('Y') }} Kost Istana Merdeka 03. All rights reserved. Part of
                <a href="{{ route('home') }}" class="text-secondary hover:text-white transition-colors duration-300 font-semibold">B1NG Empire</a>.
            </p>
        </div>
    </div>
</footer>
<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Mobile menu toggle
    const menuBtn = document.getElementById('menu-btn');
    const navLinks = document.getElementById('nav-links');
    if (menuBtn && navLinks) {
        menuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('hidden');
            const icon = menuBtn.querySelector('i');
            if (icon) {
                icon.classList.toggle('ri-menu-line');
                icon.classList.toggle('ri-close-line');
            }
        });
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.add('hidden');
                const icon = menuBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('ri-close-line');
                    icon.classList.add('ri-menu-line');
                }
            });
        });
    }

    // Navbar scroll effect
    const nav = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.classList.add('shadow-md');
        } else {
            nav.classList.remove('shadow-md');
        }
    });

    // Floating menu toggle
    const floatingMenu = document.querySelector('.floating-menu');
    const trigger = floatingMenu?.querySelector('.trigger');
    const actions = floatingMenu?.querySelectorAll('.action');
    let menuOpen = false;
    if (trigger) {
        actions.forEach(a => {
            a.style.opacity = '0';
            a.style.transform = 'scale(0.5) translateY(20px)';
            a.style.pointerEvents = 'none';
        });
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            menuOpen = !menuOpen;
            const icon = trigger.querySelector('i');
            if (menuOpen) {
                icon.style.transform = 'rotate(45deg)';
                actions.forEach((a, i) => {
                    setTimeout(() => {
                        a.style.opacity = '1';
                        a.style.transform = 'scale(1) translateY(0)';
                        a.style.pointerEvents = 'auto';
                    }, i * 80);
                });
            } else {
                icon.style.transform = 'rotate(0deg)';
                actions.forEach(a => {
                    a.style.opacity = '0';
                    a.style.transform = 'scale(0.5) translateY(20px)';
                    a.style.pointerEvents = 'none';
                });
            }
        });
    }

    // Swiper init
    new Swiper('.testimonialSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        breakpoints: {
            640: { slidesPerView: 1, spaceBetween: 20 },
            768: { slidesPerView: 2, spaceBetween: 24 },
            1024: { slidesPerView: 3, spaceBetween: 30 },
        },
    });

    // Flatpickr
    flatpickr('#datePicker', {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disableMobile: true,
    });
    flatpickr('#endDatePicker', {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disableMobile: true,
    });

    // Room selection
    const roomCells = document.querySelectorAll('.room-cell');
    const selectedRoomInput = document.getElementById('selectedRoom');
    const roomPriceInput = document.getElementById('roomPrice');
    const roomTypeSelect = document.getElementById('roomType');

    roomCells.forEach(cell => {
        cell.addEventListener('click', function() {
            if (this.classList.contains('booked')) return;
            roomCells.forEach(c => {
                if (!c.classList.contains('booked')) {
                    c.classList.remove('!bg-secondary', '!border-secondary', '!text-white');
                }
            });
            this.classList.add('!bg-secondary', '!border-secondary', '!text-white');
            const roomNum = this.dataset.room;
            const roomType = this.dataset.type;
            selectedRoomInput.value = roomNum;
            roomTypeSelect.value = roomType;
            roomPriceInput.value = roomType === '750rb - AC' ? '700000' : '550000';
        });
    });

    // Payment method toggle
    const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
    const proofContainer = document.getElementById('paymentProofContainer');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'transfer') {
                proofContainer.classList.remove('hidden');
            } else {
                proofContainer.classList.add('hidden');
            }
        });
    });

    // Form submit loading
    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            if (!selectedRoomInput.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Kamar',
                    text: 'Silakan pilih nomor kamar terlebih dahulu.',
                    confirmButtonColor: '#f6ac0f',
                });
                return;
            }
            submitBtn.disabled = true;
            btnText.textContent = 'Memproses...';
            btnLoader.classList.remove('hidden');
        });
    }

    // Scroll reveal animation
    const revealElements = document.querySelectorAll('.reveal, .reveal-stagger');
    const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                if (entry.target.classList.contains('reveal-stagger')) {
                    const children = entry.target.children;
                    Array.from(children).forEach((child, i) => {
                        setTimeout(() => {
                            child.style.opacity = '1';
                            child.style.transform = 'translateY(0)';
                        }, i * 150);
                    });
                }
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    revealElements.forEach(el => observer.observe(el));

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

});
</script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#f6ac0f',
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        confirmButtonColor: '#f6ac0f',
    });
</script>
@endif

</body>
</html>