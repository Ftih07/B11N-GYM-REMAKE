<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- SEO & Meta Tags --}}
    @php
    $pageTitle = match(request('category')) {
    '1' => 'Menu Makanan Sehat & Diet',
    '2' => 'Minuman Protein & Suplemen',
    default => 'Katalog Lengkap B11N & K1NG Gym Store'
    };
    @endphp
    <title>{{ $pageTitle }} - Pusat Nutrisi Fitness Purwokerto</title>
    <meta name="description" content="Belanja kebutuhan fitness dari B11N Gym dan K1NG Gym.">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/empire.png'))">

    {{-- 1. FONTS: Mengambil font 'Oswald' untuk Headings (Bold/Industrial) dan 'Poppins' untuk Body --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- 2. ICONS: FontAwesome & RemixIcon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />

    {{-- 3. TAILWIND CSS (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        heading: ['Oswald', 'sans-serif'], // Font khusus judul
                    },
                    colors: {
                        'brand-red': '#dc030a', // Warna B11N
                        'brand-orange': '#f97316', // Warna K1NG (Tailwind Orange-500)
                        'brand-black': '#0a0a0a',
                        'brand-gray': '#171717',
                    }
                }
            }
        }
    </script>

    {{-- Custom Styles untuk Menu Button (Opsional jika CSS external belum load) --}}
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>
</head>

<body class="bg-gray-50 text-brand-black dark:bg-brand-black dark:text-gray-200 font-sans antialiased transition-colors duration-300">

    @if (session('success'))
    <div id="notification" class="fixed top-4 right-4 z-[9999] flex items-center bg-green-600 text-white px-6 py-4 rounded-lg shadow-2xl transform transition-all duration-500 hover:scale-105">
        <i class="fas fa-check-circle text-2xl mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- NAVBAR -->
    @include('components.navbar-cta')

    <!-- HERO -->
    @if ($store)
    <div class="relative w-full h-[100vh] md:h-[100vh] flex items-center justify-center bg-gray-900 overflow-hidden mt-20">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/' . $store->image) }}" alt="Store Background" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-black via-brand-black/70 to-transparent"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto" data-aos="fade-up">
            <h2 class="text-brand-red font-heading font-bold text-xl md:text-2xl tracking-[0.2em] mb-2 uppercase">{{ $store->subheading }}</h2>
            <h1 class="text-white font-heading font-bold text-5xl md:text-7xl lg:text-8xl leading-tight mb-6 drop-shadow-lg">{{ $store->title }}</h1>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto mb-8 font-light">{{ $store->description }}</p>

            <div class="inline-flex items-center justify-center gap-2 text-white/80 border border-white/30 px-6 py-2 rounded-full backdrop-blur-sm hover:bg-white/10 transition cursor-default">
                <i class="fas fa-map-marker-alt text-brand-red"></i>
                <span class="text-sm tracking-wider uppercase">{{ $store->location }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- MAIN CONTENT -->
    <main id="product-display" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-200 dark:border-gray-800 pb-6">
            <div>
                <h2 class="text-4xl md:text-5xl font-heading font-bold text-brand-black dark:text-white uppercase">
                    Katalog <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-brand-orange">Produk</span>
                </h2>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Pilih nutrisi terbaik untuk menunjang latihanmu.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="text-6xl font-heading font-bold text-gray-200 dark:text-gray-800">{{ str_pad($totalProducts, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="text-sm font-bold uppercase tracking-widest text-gray-400 -ml-2">Items</span>
            </div>
        </div>

        {{-- === FILTER & SEARCH BAR (REFINED UI) === --}}
        <div class="mb-12">
            {{--
                FIX 1: Tambahkan #product-display di akhir route action.
                Ini memaksa browser untuk langsung loncat ke ID "product-display" setelah reload/submit.
            --}}
            <form action="{{ route('store.biin-king') }}#product-display" method="GET" class="w-full">

                {{-- Container Flex: Mobile Stack (Vertical), Desktop Row (Horizontal) --}}
                <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">

                    {{-- 1. SEARCH INPUT (Paling Lebar) --}}
                    <div class="relative w-full md:flex-grow group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 group-focus-within:text-brand-red transition-colors"></i>
                        </div>
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari produk..."
                            class="w-full pl-11 pr-4 py-3 rounded-lg border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand-red focus:border-transparent dark:bg-brand-gray dark:border-gray-700 dark:text-gray-200 dark:focus:ring-brand-orange transition-all shadow-sm">
                    </div>

                    {{-- 2. CATEGORY DROPDOWN (Fixed Width di Desktop biar rapi) --}}
                    {{-- FIX 2: Saya ubah width md:w-64 jadi md:min-w-[220px] biar lega --}}
                    <div class="relative w-full md:w-auto md:min-w-[220px]">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-filter text-gray-400"></i>
                        </div>

                        <select name="category"
                            class="w-full pl-11 pr-10 py-3 rounded-lg border border-gray-300 bg-white text-gray-700 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-brand-red focus:border-transparent dark:bg-brand-gray dark:border-gray-700 dark:text-gray-200 dark:focus:ring-brand-orange transition-all shadow-sm truncate">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>

                        {{-- Custom Chevron Icon (Pojok Kanan) --}}
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>

                    {{-- 3. APPLY BUTTON --}}
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-3 bg-brand-black text-white font-heading font-bold uppercase tracking-wider rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 dark:bg-white dark:text-brand-black dark:hover:bg-gray-200 transition-all shadow-lg transform active:scale-95 flex items-center justify-center gap-2 whitespace-nowrap">
                        <span>Apply</span>
                        {{-- Ikon panah kecil opsional --}}
                        {{-- <i class="fas fa-arrow-right text-sm"></i> --}}
                    </button>

                    {{-- 4. RESET BUTTON (Hanya Icon, Muncul jika ada filter) --}}
                    @if(request('search') || request('category'))
                    <a href="{{ route('store.biin-king') }}#product-display"
                        class="w-full md:w-auto px-4 py-3 flex items-center justify-center text-red-600 font-bold hover:bg-red-50 rounded-lg transition-colors dark:text-red-400 dark:hover:bg-brand-gray border border-gray-200 md:border-transparent hover:border-red-200"
                        title="Reset Filter">
                        <i class="fas fa-sync-alt"></i>
                        <span class="ml-2 md:hidden">Reset Filter</span> {{-- Teks muncul cuma di HP --}}
                    </a>
                    @endif

                </div>
            </form>
        </div>
        {{-- === END FILTER BAR === --}}

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($products as $product)
            {{--
                   LOGIC WARNA CARD:
                   Jika stores_id == 3 (B11N) -> Merah
                   Jika stores_id == 2 (Kost/Lain) -> Orange/Kuning 
                --}}
            @php
            $isB11n = $product->stores_id == 3;
            $accentColor = $isB11n ? 'text-brand-red' : 'text-brand-orange';
            $btnColor = $isB11n ? 'bg-brand-red hover:bg-red-700' : 'bg-brand-orange hover:bg-orange-600';
            $storeName = ($product->stores_id == 2) ? 'ISTANA MERDEKA 03' : 'B11N & K1NG Store'; // Sesuaikan nama store
            @endphp

            <div class="group relative bg-white dark:bg-brand-gray rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-800 flex flex-col h-full">

                <div class="relative w-full pt-[100%] overflow-hidden bg-gray-100 dark:bg-gray-900">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1560393464-5c69a73c5770?q=80&w=500&auto=format&fit=crop' }}"
                        alt="{{ $product->name }}"
                        class="absolute top-0 left-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                    <div class="absolute top-3 right-3 bg-black/70 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                        {{ $isB11n ? 'B11N & K1NG Store' : 'ISTANA MERDEKA 03' }}
                    </div>
                </div>

                <div class="p-5 flex flex-col flex-grow">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">
                        {{ $product->categoryproduct->name ?? 'General' }}
                    </div>

                    <h3 class="text-lg font-heading font-bold text-gray-900 dark:text-white leading-tight mb-2 group-hover:{{ $accentColor }} transition-colors">
                        {{ $product->name }}
                    </h3>

                    <div class="text-xl font-bold {{ $accentColor }} mb-4">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    <div class="flex-grow text-sm text-gray-500 dark:text-gray-400 space-y-2 mb-4">
                        <p class="line-clamp-2 text-xs leading-relaxed">
                            {{ $product->description ?? 'No description.' }}
                        </p>
                    </div>

                    <a href="{{ route('store.product.show', $product->id) }}"
                        class="w-full block text-center py-3 rounded-lg font-heading font-bold uppercase tracking-wider text-sm text-white transition-all transform active:scale-95 {{ $btnColor }}">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 px-4 flex justify-center">
            @if ($products->hasPages())
            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-2">

                {{-- Tombol Previous --}}
                @if ($products->onFirstPage())
                <span class="px-4 py-2 text-sm text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed dark:border-gray-700">
                    Previous
                </span>
                @else
                <a href="{{ $products->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}#product-display"
                    class="px-4 py-2 text-sm text-brand-black bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-brand-red transition-colors dark:bg-brand-gray dark:text-gray-300 dark:border-gray-700 dark:hover:text-white">
                    Previous
                </a>
                @endif

                {{-- Loop Nomor Halaman --}}
                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if ($page == $products->currentPage())
                {{-- Halaman Aktif (Merah/Hitam) --}}
                <span class="px-4 py-2 text-sm font-bold text-white bg-brand-black border border-brand-black rounded-lg dark:bg-white dark:text-brand-black dark:border-white">
                    {{ $page }}
                </span>
                @else
                {{-- Halaman Tidak Aktif --}}
                <a href="{{ $url . '&' . http_build_query(request()->except('page')) }}#product-display"
                    class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-brand-red hover:text-brand-red transition-all dark:bg-brand-gray dark:text-gray-400 dark:border-gray-700 dark:hover:text-white">
                    {{ $page }}
                </a>
                @endif
                @endforeach

                {{-- Tombol Next --}}
                @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}#product-display"
                    class="px-4 py-2 text-sm text-brand-black bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-brand-red transition-colors dark:bg-brand-gray dark:text-gray-300 dark:border-gray-700 dark:hover:text-white">
                    Next
                </a>
                @else
                <span class="px-4 py-2 text-sm text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed dark:border-gray-700">
                    Next
                </span>
                @endif
            </nav>
            @endif
        </div>

        @if($products->isEmpty())
        <div class="text-center py-20">
            <div class="inline-block p-6 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                <i class="fas fa-box-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-heading font-bold text-gray-600 dark:text-gray-300">Produk Tidak Ditemukan</h3>
            <p class="text-gray-500 mt-2">Coba pilih kategori lain atau cek kembali nanti.</p>
        </div>
        @endif

    </main>

    <!-- FOOTER -->
    @include('components.footer-bing-empire')

</body>

</html>