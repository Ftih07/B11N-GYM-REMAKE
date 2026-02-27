<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- SEO Meta Tags --}}
    <title>{{ $blog->title }} - B1NG EMPIRE Blog</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 150) }}">
    {{-- Ganti Str::slug dengan judul asli atau biarkan title-nya saja --}}
    <meta name="keywords" content="gym purwokerto, fitness, kost istana merdeka, b11n gym, k1ng gym, {{ $blog->title }}">
    <meta name="author" content="B1NG EMPIRE">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph (Facebook, WA, LinkedIn) --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $blog->title }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 150) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="B1NG EMPIRE">
    {{-- Kasih fallback image jaga-jaga kalau $blog->image kosong --}}
    <meta property="og:image" content="{{ $blog->image ? asset('storage/' . $blog->image) : asset('assets/default-image-og.webp') }}">

    {{-- Khusus tipe "article" --}}
    <meta property="article:published_time" content="{{ $blog->created_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $blog->updated_at->toIso8601String() }}">
    {{-- Kalau ada relasi ke kategori blog, bisa di-uncomment ini: --}}
    {{-- <meta property="article:section" content="{{ $blog->category->name ?? 'Fitness & Health' }}"> --}}

    {{-- Twitter Card (Opsional tapi sangat disarankan) --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $blog->title }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 150) }}">
    <meta name="twitter:image" content="{{ $blog->image ? asset('storage/' . $blog->image) : asset('assets/default-image-og.webp') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/empire.png'))">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Poppins:wght@300;400;500;600&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300;1,400&display=swap" rel="stylesheet">

    {{-- Tailwind & Icons --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        heading: ['Oswald', 'sans-serif'],
                        serif: ['Merriweather', 'serif'], // Font khusus bacaan panjang
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

    {{-- Custom Styles for Typography --}}
    <style>
        /* Styling untuk konten artikel dari Rich Text Editor */
        .prose p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .prose h2 {
            font-family: 'Oswald', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: inherit;
        }

        .prose h3 {
            font-family: 'Oswald', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: inherit;
        }

        .prose ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .prose ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .prose blockquote {
            border-left: 4px solid #dc030a;
            padding-left: 1rem;
            font-style: italic;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .prose img {
            border-radius: 0.5rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
            width: 100%;
        }

        .dark .prose blockquote {
            color: #9ca3af;
        }
    </style>
</head>

<body class="bg-white text-brand-black dark:bg-brand-black dark:text-gray-200 font-sans antialiased transition-colors duration-300 pt-20">

    @include('components.global-loader')

    <!--Navbar-->
    @include('components.navbar-cta')

    <!--Hero-->
    <div class="relative w-full h-[60vh] md:h-[70vh] bg-gray-900 overflow-hidden">
        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-brand-black via-transparent to-black/30"></div>

        <div class="absolute bottom-0 left-0 w-full p-6 md:p-12 max-w-4xl mx-auto">
            <div class="mb-4 flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-brand-red text-white text-xs font-bold uppercase tracking-wider rounded">Article</span>
                <span class="px-3 py-1 bg-white/20 backdrop-blur text-white text-xs font-bold uppercase tracking-wider rounded">{{ $blog->category ?? 'General' }}</span>
            </div>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-heading font-bold text-white leading-tight mb-4 drop-shadow-lg">
                {{ $blog->title }}
            </h1>
            <div class="flex items-center text-gray-500 text-sm font-medium gap-4">
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar-alt"></i>
                    <span>{{ $blog->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="far fa-user"></i>
                    <span>Admin B1NG</span>
                </div>
            </div>
        </div>
    </div>

    <!--Main Content-->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-12">

        <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800 pb-4" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('blogs.index') }}" class="hover:text-brand-red transition">Blog</a>
                </li>

                <li class="inline-flex items-center text-xs">
                    <i class="fas fa-chevron-right"></i>
                </li>

                <li class="min-w-0">
                    <span class="text-brand-black dark:text-white font-medium block break-words">
                        {{ $blog->title }}
                    </span>
                </li>
            </ol>
        </nav>

        <article class="prose prose-lg dark:prose-invert max-w-none font-serif
                text-gray-800 dark:text-gray-300
                break-words overflow-x-hidden
                prose-a:break-all prose-li:break-words prose-p:break-words">
            {!! $blog->content !!}
        </article>

        <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Share Article:</div>
            <div class="flex gap-4">
                {{-- Link Share WA (Dinamis) --}}
                <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:scale-110 transition shadow-lg">
                    <i class="fab fa-whatsapp text-lg"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition shadow-lg">
                    <i class="fab fa-facebook-f text-lg"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ url()->current() }}"
                    target="_blank"
                    class="w-10 h-10 rounded-full bg-black dark:bg-gray-700 text-white flex items-center justify-center hover:scale-110 transition shadow-lg">
                    <i class="fab fa-twitter text-lg"></i>
                </a>
            </div>
        </div>

    </main>

    <!--Related Blog-->
    @if($relatedBlogs->count() > 0)
    <section class="bg-gray-50 dark:bg-brand-gray py-16 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-8 uppercase text-center">
                Baca Juga <span class="text-brand-red">Artikel Lainnya</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($relatedBlogs as $related)
                <div class="group bg-white dark:bg-brand-black rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 flex flex-col h-full">
                    <a href="{{ route('blogs.show', $related->slug) }}" class="relative h-48 overflow-hidden block">
                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transform transition duration-500 group-hover:scale-110">
                    </a>
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-lg font-heading font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-brand-red transition">
                            <a href="{{ route('blogs.show', $related->slug) }}">{{ $related->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2 flex-grow">
                            {{ Str::limit(strip_tags($related->content), 80) }}
                        </p>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-auto">
                            {{ $related->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!--Footer-->
    @include('components.footer-bing-empire')

</body>

</html>