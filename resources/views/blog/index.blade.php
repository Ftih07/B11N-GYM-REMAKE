<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- SEO & Meta Tags --}}
    <title>Blog B1NG EMPIRE - Tips Fitness, Gym & Info Kost Eksklusif</title>
    <meta name="description" content="Temukan artikel terbaru seputar tips latihan fitness di B11N & K1NG Gym, panduan hidup sehat, serta info kost eksklusif Istana Merdeka 03.">

    {{-- Tambahan Keywords spesifik target lokal Purwokerto --}}
    <meta name="keywords" content="blog b1ng empire, tips fitness, artikel gym purwokerto, b11n gym, k1ng gym, kost eksklusif purwokerto, istana merdeka 03, gaya hidup sehat">
    <meta name="author" content="B1NG EMPIRE">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Social Media (Untuk preview link di WA/Sosmed) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Blog B1NG EMPIRE - Tips Fitness & Info Kost">
    <meta property="og:description" content="Temukan artikel terbaru seputar tips latihan fitness di B11N & K1NG Gym, panduan hidup sehat, serta info kost eksklusif Istana Merdeka 03.">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:site_name" content="B1NG EMPIRE">

    {{-- Karena ini halaman index blog, pakai logo default atau banner blog kalau ada --}}
    <meta property="og:image" content="{{ asset('assets/default-image-og.webp') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/empire.png'))">

    {{-- 1. FONTS: Oswald & Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />

    {{-- 2. ICONS & TAILWIND --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        heading: ['Oswald', 'sans-serif'],
                    },
                    colors: {
                        'brand-red': '#dc030a',
                        'brand-orange': '#f97316',
                        'brand-black': '#0a0a0a',
                        'brand-gray': '#171717',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-brand-black dark:bg-brand-black dark:text-gray-200 font-sans antialiased transition-colors duration-300 pt-20">

    @include('components.global-loader')

    <!--Navbar-->
    @include('components.navbar-cta')

    <!--Hero-->
    <div class="relative w-full h-[100vh] flex items-center justify-center bg-gray-900 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/Hero/b11ngym.jpg') }}" alt="Blog Background" class="w-full h-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-black via-brand-black/60 to-transparent"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <p class="text-brand-red font-heading font-bold tracking-[0.2em] uppercase mb-3 animate-fade-in-up">
                Latest Updates & Articles
            </p>
            <h1 class="text-white font-heading font-bold text-5xl md:text-7xl leading-tight mb-6 drop-shadow-2xl uppercase">
                B1NG <span class="text-brand-red">Empire</span> Blog
            </h1>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-light">
                Tips latihan, panduan nutrisi, dan informasi hunian eksklusif dalam satu tempat.
            </p>
        </div>
    </div>

    <!--Main Content-->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-200 dark:border-gray-800 pb-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-brand-black dark:text-white uppercase">
                    Artikel <span class="text-brand-red">Terbaru</span>
                </h2>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Wawasan baru untuk gaya hidup sehatmu.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($blogs as $blog)
            <article class="group bg-white dark:bg-brand-gray rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-800 flex flex-col h-full">

                <a href="{{ route('blogs.show', $blog->slug) }}" class="relative w-full h-64 overflow-hidden block">
                    <img src="{{ asset('storage/' . $blog->image) }}"
                        alt="{{ $blog->title }}"
                        class="w-full h-full object-cover transform transition duration-700 group-hover:scale-110">

                    <div class="absolute top-4 left-4 bg-white/90 dark:bg-black/80 backdrop-blur px-3 py-1.5 rounded text-xs font-bold uppercase tracking-wider text-brand-black dark:text-white shadow-lg">
                        {{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}
                    </div>
                </a>

                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 leading-tight group-hover:text-brand-red transition-colors">
                        <a href="{{ route('blogs.show', $blog->slug) }}">
                            {{ $blog->title }}
                        </a>
                    </h3>

                    <div class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4 line-clamp-3 flex-grow">
                        {!! Str::limit(strip_tags($blog->content), 120) !!}
                    </div>

                    <a href="{{ route('blogs.show', $blog->slug) }}" class="inline-flex items-center text-sm font-bold text-brand-orange hover:text-brand-red transition-colors mt-auto uppercase tracking-wide">
                        Baca Selengkapnya <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        @if($blogs->isEmpty())
        <div class="text-center py-20">
            <div class="inline-block p-6 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                <i class="far fa-newspaper text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-heading font-bold text-gray-600 dark:text-gray-300">Belum ada artikel</h3>
            <p class="text-gray-500 mt-2">Cek kembali nanti untuk update terbaru.</p>
        </div>
        @endif

    </main>

    <!--Footer-->
    @include('components.footer-bing-empire')

</body>

</html>