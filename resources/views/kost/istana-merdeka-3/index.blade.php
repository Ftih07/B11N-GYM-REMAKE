<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

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

        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-left.revealed {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-right.revealed {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-stagger>* {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-stagger.revealed>*:nth-child(1) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.1s;
        }

        .reveal-stagger.revealed>*:nth-child(2) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.2s;
        }

        .reveal-stagger.revealed>*:nth-child(3) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.3s;
        }

        .reveal-stagger.revealed>*:nth-child(4) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.4s;
        }

        .reveal-stagger.revealed>*:nth-child(5) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.5s;
        }

        .reveal-stagger.revealed>*:nth-child(6) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.6s;
        }

        .reveal-stagger.revealed>*:nth-child(7) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.7s;
        }

        .reveal-stagger.revealed>*:nth-child(8) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.8s;
        }

        .reveal-stagger.revealed>*:nth-child(9) {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.9s;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .swiper {
            padding-bottom: 3rem;
        }

        .swiper-pagination-bullet {
            height: 12px;
            width: 12px;
        }

        .swiper-pagination-bullet-active {
            background-color: #f6ac0f;
        }

        .room-layout .room {
            width: 60px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #0f1a2c;
            border-radius: 4px;
            font-size: 18px;
            font-weight: bold;
            color: #0f1a2c;
            cursor: pointer;
            transition: all 0.3s;
        }

        .room-layout .room:hover:not(.booked) {
            border-color: #f6ac0f;
            color: #f6ac0f;
        }

        .room-layout .room.booked {
            background-color: #ef4444;
            color: white;
            border-color: #ef4444;
            cursor: not-allowed;
        }

        .room-layout .room.selected {
            background-color: #22c55e;
            color: white;
            border-color: #22c55e;
        }

        button:disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .form-hidden {
            display: none !important;
        }
    </style>
</head>

<body class="bg-white font-body">

    <!-- NAVIGATION -->
    @include('components.navbar-cta')

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
                        <img src="assets/home/istana-merdeka.jpg" alt="Kamar Non AC" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
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
                            <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> WiFi kecepatan tinggi</li>
                            <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> Kamar mandi bersih</li>
                        </ul>
                        <div class="flex items-end gap-2 mb-6">
                            <span class="font-heading text-3xl font-bold text-primary">Rp 500.000</span>
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
                            <li class="flex items-center gap-3 text-textLight"><i class="ri-check-line text-secondary text-lg"></i> WiFi & kamar mandi</li>
                        </ul>
                        <div class="flex items-end gap-2 mb-6">
                            <span class="font-heading text-3xl font-bold text-primary">Rp 750.000</span>
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
                        <iframe src="https://www.youtube.com/embed/Sy3d9kAt4rY?si=cs_6LSDLp3PHBkw4" title="Room Tour Kost Istana Merdeka 03" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
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
            ['icon' => 'fas fa-tint', 'title' => 'Air Bersih', 'desc' => 'Suplai air bersih 24/7 untuk kebutuhan mandi dan mencuci.'],
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
    <section id="testimonials" class="relative py-20 sm:py-24 lg:py-32 bg-extraLight">
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
                        <div class="p-6">
                            <h4 class="font-heading text-white font-bold text-lg uppercase tracking-wider">Kamar AC</h4>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded aspect-[4/3] shadow-lg hover:shadow-2xl transition-all duration-500">
                    <img src="assets/facilities/ber-ac.png" alt="Gallery" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end">
                        <div class="p-6">
                            <h4 class="font-heading text-white font-bold text-lg uppercase tracking-wider">Kamar Non AC</h4>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded aspect-[4/3] shadow-lg hover:shadow-2xl transition-all duration-500">
                    <img src="assets/facilities/ac.png" alt="Gallery" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end">
                        <div class="p-6">
                            <h4 class="font-heading text-white font-bold text-lg uppercase tracking-wider">Fasilitas</h4>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- BOOKING FORM -->
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
                    Silakan lengkapi data, pilih nomor kamar, dan tentukan tipe kamar yang diinginkan.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                <div class="bg-white rounded p-8 sm:p-10 shadow-2xl reveal">
                    <h3 class="font-heading text-2xl font-bold text-textDark mb-8 tracking-tight uppercase">Form Pemesanan</h3>

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
                            <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">No. WhatsApp</label>
                            <input type="tel" name="phone" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body placeholder:text-textLight" />
                        </div>

                        <div>
                            <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Tanggal Masuk</label>
                            <input type="text" name="date" id="datePicker" required placeholder="Pilih tanggal" class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body placeholder:text-textLight" />
                        </div>

                        <div>
                            <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Pilih Jenis Kamar</label>
                            <div class="relative">
                                <select name="room_type" id="roomType" required class="w-full px-4 py-3 bg-extraLight border-2 border-gray-200 rounded focus:border-secondary focus:ring-0 focus:outline-none transition-colors duration-300 text-textDark font-body appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Tipe --</option>
                                    <option value="500rb - Non AC">Non AC - Rp 500.000/bulan</option>
                                    <option value="750rb - AC">AC - Rp 750.000/bulan</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-textDark">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="room_number" id="selectedRoom" required />

                        <div>
                            <label class="block font-heading text-textDark font-semibold text-sm mb-4 uppercase tracking-wider">Pilih Nomor Kamar</label>
                            <div class="room-layout bg-extraLight rounded p-6 border-2 border-gray-200">

                                <div class="grid grid-cols-5 gap-3">
                                    @foreach(range(1, 10) as $i)
                                    @php $isBooked = in_array($i, $bookedRooms ?? []); @endphp
                                    <div class="room-cell aspect-square rounded flex items-center justify-center cursor-pointer border-2 border-gray-300 bg-white hover:border-secondary transition-all duration-300 font-heading font-bold text-textDark text-sm
                                    {{ $isBooked ? 'booked !bg-red-100 !border-red-300 !text-red-400 !cursor-not-allowed' : '' }}"
                                        data-room="{{ $i }}">
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-4 mt-6 text-xs font-body text-textLight border-t border-gray-300 pt-4">
                                    <div class="flex items-center gap-1">
                                        <div class="w-3 h-3 rounded border-2 border-gray-300 bg-white"></div> Kosong
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <div class="w-3 h-3 rounded bg-secondary border-2 border-secondary"></div> Dipilih
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <div class="w-3 h-3 rounded bg-red-100 border-2 border-red-300"></div> Terisi
                                    </div>
                                </div>

                                <div id="roomSelectionInfo" class="mt-4 p-3 bg-secondary/10 border border-secondary rounded text-center hidden">
                                    <p class="text-sm text-textDark font-semibold">Anda memilih: <span id="infoRoomNumber" class="text-secondary font-bold">-</span></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Metode Pembayaran</label>
                            <div class="flex gap-4">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="paymentMethod" value="qris" class="hidden peer" />
                                    <div class="peer-checked:border-secondary peer-checked:bg-secondary/10 border-2 border-gray-200 rounded p-4 text-center transition-all duration-300 hover:border-secondary h-full flex flex-col items-center justify-center">
                                        <i class="ri-qr-code-line text-2xl text-textDark peer-checked:text-secondary mb-1 block"></i>
                                        <span class="font-heading font-semibold text-sm text-textDark uppercase tracking-wider">QRIS</span>
                                    </div>
                                </label>

                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="paymentMethod" value="transfer" class="hidden peer" />
                                    <div class="peer-checked:border-secondary peer-checked:bg-secondary/10 border-2 border-gray-200 rounded p-4 text-center transition-all duration-300 hover:border-secondary h-full flex flex-col items-center justify-center">
                                        <i class="ri-bank-card-line text-2xl text-textDark peer-checked:text-secondary mb-1 block"></i>
                                        <span class="font-heading font-semibold text-sm text-textDark uppercase tracking-wider">Transfer</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div id="paymentDetails" class="hidden bg-gray-50 border-2 border-dashed border-gray-300 rounded p-6 text-center">
                            <div id="qrisContent" class="hidden space-y-3">
                                <p class="text-sm font-semibold text-textDark">Scan QRIS di bawah ini:</p>
                                <div class="flex justify-center">
                                    <img src="/assets/img/pembayaran/qris-barcode.png" alt="QRIS Barcode" class="max-w-[200px] w-full border border-gray-200 rounded shadow-sm">
                                </div>
                            </div>

                            <div id="transferContent" class="hidden space-y-3">
                                <p class="text-sm font-semibold text-textDark">Silakan transfer ke:</p>
                                <div class="flex justify-center mb-2">
                                    <img src="/assets/img/pembayaran/bca.png" alt="BCA" class="h-8 object-contain">
                                </div>
                                <div class="bg-white p-3 rounded border border-gray-200 inline-block">
                                    <p class="text-lg font-bold text-textDark tracking-widest select-all">0461701506</p>
                                    <p class="text-xs text-gray-500 uppercase">A.N. Sobiin</p>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <label class="block font-heading text-textDark font-semibold text-sm mb-2 uppercase tracking-wider">Upload Bukti Pembayaran</label>
                                <input type="file" name="paymentProof" id="paymentProof" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20 cursor-pointer" />
                            </div>
                        </div>

                        <button type="submit" id="bookingNow" class="w-full font-heading px-8 py-4 bg-secondary text-white font-semibold text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 uppercase tracking-wider flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="btnText">Booking Sekarang</span>
                            <span id="btnLoading" class="hidden flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>
                </div>

                <div class="space-y-8 reveal sticky top-24">
                    <div class="bg-white/10 backdrop-blur rounded p-8 border border-white/20">
                        <h3 class="font-heading text-white text-2xl font-bold mb-6 tracking-tight uppercase">Informasi Harga</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-white/10">
                                <span class="font-body text-gray-300">Kamar Non AC</span>
                                <span class="font-heading text-secondary font-bold text-xl">Rp 500.000</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-white/10">
                                <span class="font-body text-gray-300">Kamar AC</span>
                                <span class="font-heading text-secondary font-bold text-xl">Rp 750.000</span>
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
                            <a href="https://wa.me/6283194288423" target="_blank" class="flex items-center gap-4 text-gray-300 hover:text-secondary transition-colors duration-300 group">
                                <div class="w-12 h-12 bg-secondary/20 rounded-full flex items-center justify-center group-hover:bg-secondary/40 transition-colors duration-300">
                                    <i class="ri-whatsapp-line text-xl text-secondary"></i>
                                </div>
                                <span class="font-body">0831-9428-8423</span>
                            </a>
                            <a href="https://www.instagram.com/biin_gym" target="_blank" class="flex items-center gap-4 text-gray-300 hover:text-secondary transition-colors duration-300 group">
                                <div class="w-12 h-12 bg-secondary/20 rounded-full flex items-center justify-center group-hover:bg-secondary/40 transition-colors duration-300">
                                    <i class="ri-instagram-line text-xl text-secondary"></i>
                                </div>
                                <span class="font-body">@biin_gym</span>
                            </a>
                            <div class="flex items-center gap-4 text-gray-300">
                                <div class="w-12 h-12 bg-secondary/20 rounded-full flex items-center justify-center">
                                    <i class="ri-map-pin-line text-xl text-secondary"></i>
                                </div>
                                <span class="font-body">Jl. Masjid Baru, Arcawinangun, Purwokerto Timur, Banyumas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BLOG SECTION -->
    <section id="blog" class="relative py-20 sm:py-24 lg:py-32 bg-extraLight">
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

    <!-- CUSTOM FOOTER SECTION -->
    <footer id="contact" class="relative bg-neutral-900/95 text-white border-t border-gray-800 font-sans">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#f6ac0f] to-transparent opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12 mb-12 reveal-stagger">

                <div class="lg:col-span-5 space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 relative flex-shrink-0">
                            <a href="{{ route('home') }}" class="block">
                                <img src="{{ asset('assets/Logo/kost.png') }}" alt="B1NG Empire Logo" class="w-full h-full object-contain" />
                            </a>
                        </div>
                        <div>
                            <h3 class="font-black text-2xl leading-none tracking-wide text-white">
                                KOST ISTANA <span class="text-[#f6ac0f]">MERDEKA 03</span>
                            </h3>
                            <span class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">Premium Gym & Residence</span>
                        </div>
                    </div>

                    <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                        Hunian premium eksklusif di atas B11N Gym Purwokerto. Nyaman, strategis, dan terjangkau untuk mahasiswa dan profesional muda.
                    </p>

                    <div class="flex items-center gap-3 pt-2">
                        @php
                        $socials = [
                        ['icon' => 'ri-whatsapp-line', 'url' => 'https://wa.me/6283194288423'],
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
                            <a href="https://wa.me/6283194288423" target="_blank" class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-[#f6ac0f]/50 hover:bg-[#f6ac0f]/10 transition-all duration-300 group">

                                <div class="w-10 h-10 bg-[#f6ac0f] rounded-lg flex items-center justify-center flex-shrink-0 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="ri-whatsapp-fill text-lg"></i>
                                </div>

                                <div>
                                    <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-1 opacity-70">WhatsApp</h5>
                                    <p class="text-gray-300 font-medium text-sm group-hover:text-[#f6ac0f] transition-colors">+62 831 9428 8423</p>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="https://maps.app.goo.gl/ZEHmkWKQV7JmNZnG9" target="_blank" class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-[#f6ac0f]/50 hover:bg-[#f6ac0f]/10 transition-all duration-300 group">
                                <div class="w-10 h-10 bg-[#f6ac0f] rounded-lg flex items-center justify-center flex-shrink-0 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="ri-map-pin-2-fill text-lg"></i>
                                </div>
                                <div>
                                    <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-1 opacity-70">Lokasi Kost</h5>
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
                    &copy; {{ date('Y') }} Kost Istana Merdeka 03. All rights reserved. Part of
                    <a href="{{ route('home') }}" class="text-secondary hover:text-white transition-colors duration-300 font-semibold">B1NG Empire</a>.
                </p>

                <div class="flex items-center gap-6 text-xs text-gray-500">
                    <a href="{{ route('legal') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('legal') }}" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>

        </div>
    </footer>
    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Swiper init
            new Swiper('.testimonialSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30
                    },
                },
            });

            // 1. Inisialisasi Flatpickr (Kalender)
            flatpickr('#datePicker', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disableMobile: true,
            });

            // 2. Variabel Elemen
            const roomCells = document.querySelectorAll('.room-cell');
            const selectedRoomInput = document.getElementById('selectedRoom');
            const roomTypeSelect = document.getElementById('roomType'); // Dropdown
            const roomInfoBox = document.getElementById('roomSelectionInfo');
            const infoRoomNumber = document.getElementById('infoRoomNumber');

            const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
            const paymentDetails = document.getElementById('paymentDetails');
            const qrisContent = document.getElementById('qrisContent');
            const transferContent = document.getElementById('transferContent');
            const paymentProofInput = document.getElementById('paymentProof');
            const bookingBtn = document.getElementById('bookingNow');

            // 3. Logika Pemilihan Kamar (Hanya pilih nomor)
            roomCells.forEach(cell => {
                cell.addEventListener('click', function() {
                    if (this.classList.contains('booked')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kamar Terisi',
                            text: 'Maaf, kamar ini sudah dibooking orang lain.',
                            confirmButtonColor: '#d33'
                        });
                        return;
                    }

                    // Reset visual seleksi sebelumnya
                    roomCells.forEach(c => {
                        if (!c.classList.contains('booked')) {
                            c.classList.remove('!bg-secondary', '!border-secondary', '!text-white');
                        }
                    });

                    // Highlight seleksi saat ini
                    this.classList.add('!bg-secondary', '!border-secondary', '!text-white');

                    // Ambil nomor kamar
                    const roomNum = this.dataset.room;

                    // Isi ke Hidden Input
                    selectedRoomInput.value = roomNum;

                    // Update text feedback (hanya nomor kamar, tipe kamar dipilih di dropdown)
                    roomInfoBox.classList.remove('hidden');
                    infoRoomNumber.textContent = "Kamar " + roomNum.toString().padStart(2, '0');
                });
            });

            // 4. Logika Metode Pembayaran
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    paymentDetails.classList.remove('hidden');

                    if (this.value === 'qris') {
                        qrisContent.classList.remove('hidden');
                        transferContent.classList.add('hidden');
                    } else if (this.value === 'transfer') {
                        transferContent.classList.remove('hidden');
                        qrisContent.classList.add('hidden');
                    }
                });
            });

            // 5. Handle Submit Validasi
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                const roomVal = selectedRoomInput.value;
                const typeVal = roomTypeSelect.value;
                const payMethod = document.querySelector('input[name="paymentMethod"]:checked');
                const proofVal = paymentProofInput.files.length;

                // Validasi Nomor Kamar
                if (!roomVal) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Kamar',
                        text: 'Silakan klik nomor kamar pada denah.',
                        confirmButtonColor: '#f6ac0f'
                    });
                    return;
                }

                // Validasi Dropdown Tipe Kamar
                if (!typeVal) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Jenis Kamar',
                        text: 'Silakan pilih jenis kamar (AC / Non-AC) pada dropdown.',
                        confirmButtonColor: '#f6ac0f'
                    });
                    return;
                }

                // Validasi Metode Bayar
                if (!payMethod) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Metode Pembayaran',
                        text: 'Silakan pilih metode pembayaran.',
                        confirmButtonColor: '#f6ac0f'
                    });
                    return;
                }

                // Validasi Bukti Transfer
                if (proofVal === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Upload Bukti',
                        text: 'Mohon upload bukti pembayaran.',
                        confirmButtonColor: '#f6ac0f'
                    });
                    return;
                }

                // Jika lolos, loading state
                const btnText = document.getElementById('btnText');
                const btnLoading = document.getElementById('btnLoading');
                bookingBtn.setAttribute('disabled', 'true');
                bookingBtn.classList.add('opacity-75', 'cursor-not-allowed');
                btnText.classList.add('hidden');
                btnLoading.classList.remove('hidden');
            });

            // 6. SweetAlert Feedback dari Session Laravel
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6',
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
            });
            @endif

            // 7. Scroll Reveal Animation
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    }
                });
            });
            document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => observer.observe(el));

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
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