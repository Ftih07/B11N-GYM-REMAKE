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
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/colab.png'))">

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

    @include('components.navbar-cta')

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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

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

        <div class="flex flex-wrap gap-3 mb-12">
            {{-- Tombol All --}}
            <a href="{{ route('store.biin-king', ['category' => null]) }}"
                class="px-6 py-2 rounded-full font-heading uppercase tracking-wide text-sm transition-all duration-300 border 
               {{ is_null(request('category')) 
                  ? 'bg-brand-black text-white border-brand-black dark:bg-white dark:text-black' 
                  : 'bg-transparent text-gray-500 border-gray-300 hover:border-brand-black hover:text-brand-black dark:border-gray-700 dark:text-gray-400 dark:hover:text-white' 
               }}">
                All Products
            </a>

            {{-- Loop Kategori --}}
            @foreach($categories as $category)
            <a href="{{ route('store.biin-king', ['category' => $category->id]) }}"
                class="px-6 py-2 rounded-full font-heading uppercase tracking-wide text-sm transition-all duration-300 border
               {{ request('category') == $category->id 
                  ? 'bg-brand-black text-white border-brand-black dark:bg-white dark:text-black' 
                  : 'bg-transparent text-gray-500 border-gray-300 hover:border-brand-black hover:text-brand-black dark:border-gray-700 dark:text-gray-400 dark:hover:text-white' 
               }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

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
                    <img src="{{ asset('storage/' . $product->image) }}"
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
                        @if(empty($product->flavour) && empty($product->serving_option))
                        <p class="line-clamp-2 text-xs leading-relaxed">
                            {{ $product->description ?? 'No description.' }}
                        </p>
                        @else
                        <div class="flex flex-col gap-1 text-xs font-medium">
                            @if(!empty($product->flavour))
                            <div class="flex items-center gap-2">
                                <i class="fas fa-utensils w-4 text-center opacity-50"></i>
                                <span>{{ $product->flavour }}</span>
                            </div>
                            @endif
                            @if(!empty($product->serving_option))
                            <div class="flex items-center gap-2">
                                <i class="fas fa-box-open w-4 text-center opacity-50"></i>
                                <span>{{ $product->serving_option }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('store.product.show', $product->id) }}"
                        class="w-full block text-center py-3 rounded-lg font-heading font-bold uppercase tracking-wider text-sm text-white transition-all transform active:scale-95 {{ $btnColor }}">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
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

    <footer class="bg-brand-black text-white py-10 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <img src="assets/Logo/colab.png" alt="Logo" class="h-10 mx-auto mb-6 opacity-80 grayscale hover:grayscale-0 transition">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} <span class="text-brand-red font-bold">B1NG EMPIRE</span>. All Rights Reserved.
            </p>
        </div>
    </footer>

</body>

</html>