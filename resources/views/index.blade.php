<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- 1. TITLE: Branding Kuat + Keyword Utama --}}
    <title>B1NG EMPIRE - Unit Usaha Gym & Kost Premium Purwokerto</title>

    {{-- 2. META DESCRIPTION: Menjelaskan hubungan antar bisnis --}}
    <meta name="description" content="B1NG EMPIRE adalah induk usaha dari B11N Gym, K1NG Gym, dan Kost Istana Merdeka 03. Solusi gaya hidup sehat dan hunian nyaman di Purwokerto dalam satu unit usaha.">

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

        /* Base Typography */
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
    </style>
</head>

<body class="bg-primary font-body overflow-x-hidden w-full">

    @include('components.global-loader')

    <!-- NAVIGATION BAR -->
    @include('components.navbar-cta')

    <!-- HERO HEADER SECTION -->
    {{-- UPDATE: Class bg-gradient diubah jadi abu-abu (gray-300/100) biar ga silau --}}
    <header id="header" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-300 via-gray-100 to-gray-300 pt-24">

        {{-- UPDATE: Opacity dinaikkan dikit jadi 10 biar tekstur gym-nya lebih kelihatan --}}
        <div class="absolute inset-0 z-0 opacity-10 mix-blend-multiply">
            <img src="assets/Hero/b11ngym.jpg" alt="B1NG Empire Hero" class="w-full h-full object-cover object-center" />
        </div>

        {{-- UPDATE: Tambahan Overlay Hitam Tipis (Opsional, hapus kalau terlalu gelap) --}}
        <div class="absolute inset-0 bg-black/5 z-0 pointer-events-none"></div>

        <div class="absolute top-20 right-10 w-72 h-72 bg-secondary/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>

        <div class="relative z-20 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24">
            <div class="max-w-3xl mx-auto text-center reveal">
                <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-6 uppercase">
                    MEMPERSEMBAHKAN
                </p>

                <h1 class="font-heading text-textDark font-bold text-5xl sm:text-6xl md:text-7xl lg:text-8xl leading-none mb-8 sm:mb-10 tracking-tight uppercase">
                    B1NG<br />
                    <span class="text-secondary">EMPIRE</span>
                </h1>

                {{-- UPDATE: Tambah 'font-medium' biar teks deskripsi lebih tebal sedikit & gampang dibaca di background abu --}}
                <p class="font-body text-textDark font-medium text-lg sm:text-xl leading-relaxed mb-10 max-w-xl mx-auto">
                    Menghadirkan fitness, wellness, dan hunian modern dalam satu unit usaha eksklusif di Purwokerto.
                </p>

                <a href="#about"
                    class="font-heading px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider inline-block">
                    Jelajahi B1NG Empire
                </a>
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
                    B1NG EMPIRE adalah sebuah konsep di mana beberapa bisnis atau layanan yang berbeda-beda, namun memiliki kesamaan dalam hal kepemilikan atau target audiens, digabungkan ke dalam satu unit usaha. Tujuannya adalah untuk memberikan pengalaman pengguna yang lebih baik, meningkatkan efisiensi, dan memperkuat branding.
                </p>

                <!-- CTA Button -->
                <a href="#ecosystem" class="font-heading inline-block px-10 py-5 bg-secondary text-white font-semibold text-base sm:text-lg rounded shadow-lg hover:bg-white hover:text-secondary border-2 border-secondary hover:shadow-xl transition-all duration-300 hover:scale-105 uppercase tracking-wider">
                    Mulai Sekarang
                </a>
            </div>

        </div>
    </section>

    <!-- WEBSITE/PRODUCT SECTION -->
    <section id="ecosystem" class="relative py-20 sm:py-24 lg:py-32 bg-extraLight">
        <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center mb-16 lg:mb-20 reveal">
                <p class="font-heading text-secondary font-semibold text-base sm:text-lg tracking-[0.3em] mb-4 uppercase">
                    Beberapa Unit Usaha Kami
                </p>
                <h2 class="font-heading text-textDark font-bold text-3xl sm:text-4xl md:text-5xl lg:text-6xl tracking-tight uppercase">
                    Unit Usaha <span class="text-secondary">Kami</span>
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
                            <span class="font-heading text-white font-semibold text-xs tracking-wider uppercase">GYM</span>
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
                            Kunjungi Unit Usaha
                        </a>
                    </div>
                </div>

                <!-- K1NG Gym Card -->
                <div class="group bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col h-full">
                    <!-- Image Container -->
                    <div class="relative h-64 sm:h-72 overflow-hidden flex-shrink-0">
                        <img src="assets/king-gym/hero.jpg" alt="K1NG Gym" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                        <!-- Badge -->
                        <div class="absolute top-4 right-4 bg-secondary px-4 py-2 rounded shadow-md">
                            <span class="font-heading text-white font-semibold text-xs tracking-wider uppercase">GYM</span>
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
                            Kunjungi Unit Usaha
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
                            <span class="font-heading text-white font-semibold text-xs tracking-wider uppercase">KOST KHUSUS PUTRA</span>
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
                            Kunjungi Unit Usaha
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
                        Kunjungi Store
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

    <!-- FOOTER -->
    @include('components.footer-bing-empire')

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