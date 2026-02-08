<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- SEO & Meta Tags --}}
    <title>{{ $product->name }} ({{ $product->flavour }}) - Rp{{ number_format($product->price, 0, ',', '.') }}</title>
    <meta name="description" content="Jual {{ $product->name }} rasa {{ $product->flavour }}. {{ \Illuminate\Support\Str::limit($product->description, 100) }}.">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/empire.png'))">

    {{-- 1. FONTS: Oswald & Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- 2. ICONS & TAILWIND --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
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

{{-- Tambahkan pt-24 agar tidak ketabrak navbar fixed --}}

<body class="bg-gray-50 text-brand-black dark:bg-brand-black dark:text-gray-200 font-sans antialiased transition-colors duration-300 pt-24">

    @include('components.navbar-cta')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('store.biin-king') }}" class="hover:text-brand-black dark:hover:text-white">Store</a>
                </li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li aria-current="page">
                    <span class="text-brand-black dark:text-white font-medium truncate max-w-[150px] md:max-w-none">{{ $product->name }}</span>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">

            <div class="relative group">
                <div class="aspect-square w-full overflow-hidden rounded-2xl bg-gray-100 dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700">
                    <img src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover object-center transform transition duration-500 hover:scale-105">
                </div>
                <div class="absolute top-4 left-4 bg-black/80 backdrop-blur text-white text-xs font-bold px-3 py-1.5 rounded uppercase tracking-wider shadow-lg">
                    {{ $product->stores_id == 3 ? 'B11N & K1NG Store' : 'ISTANA MERDEKA 03' }}
                </div>
            </div>

            <div class="flex flex-col justify-center">

                {{-- Category --}}
                <div class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-2">
                    {{ $product->categoryproduct->name ?? 'General Product' }}
                </div>

                {{-- Title --}}
                <h1 class="text-4xl md:text-5xl font-heading font-bold text-gray-900 dark:text-white mb-4 leading-tight">
                    {{ $product->name }}
                </h1>

                {{-- Price --}}
                <div class="text-3xl font-bold {{ $product->stores_id == 3 ? 'text-brand-red' : 'text-brand-orange' }} mb-6">
                    Rp{{ number_format($product->price, 0, ',', '.') }}
                </div>

                <hr class="border-gray-200 dark:border-gray-700 mb-6">

                {{-- Specs / Attributes --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    @if(!empty($product->flavour))
                    <div class="bg-gray-50 dark:bg-brand-gray p-4 rounded-lg border border-gray-100 dark:border-gray-800">
                        <span class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Rasa / Varian</span>
                        <span class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-utensils text-gray-400"></i> {{ $product->flavour }}
                        </span>
                    </div>
                    @endif

                    @if(!empty($product->serving_option))
                    <div class="bg-gray-50 dark:bg-brand-gray p-4 rounded-lg border border-gray-100 dark:border-gray-800">
                        <span class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Kemasan / Porsi</span>
                        <span class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-box-open text-gray-400"></i> {{ $product->serving_option }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="prose prose-sm dark:prose-invert text-gray-600 dark:text-gray-300 mb-8">
                    <h3 class="text-lg font-heading font-bold text-gray-900 dark:text-white mb-2 uppercase">Deskripsi Produk</h3>
                    <p class="leading-relaxed">
                        {{ $product->description ?? 'Tidak ada deskripsi tersedia untuk produk ini.' }}
                    </p>
                </div>

                {{-- Call to Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                    {{-- Logic WA Link --}}
                    @php
                    $waMessage = "Halo admin, saya mau pesan *{$product->name}* ({$product->flavour}). Apakah stok masih ada?";
                    $waLink = "https://wa.me/6281226110988?text=" . urlencode($waMessage); // Ganti nomor sesuai kebutuhan
                    $btnClass = ($product->stores_id == 3) ? 'bg-brand-red hover:bg-red-700' : 'bg-brand-orange hover:bg-orange-600';
                    @endphp

                    <a href="{{ $waLink }}" target="_blank"
                        class="flex-1 text-center py-4 rounded-lg font-heading font-bold uppercase tracking-wider text-white shadow-lg transform transition hover:-translate-y-1 {{ $btnClass }}">
                        <i class="fab fa-whatsapp mr-2 text-lg"></i> Beli via WhatsApp
                    </a>

                    <a href="{{ route('store.biin-king') }}"
                        class="px-8 py-4 text-center rounded-lg font-heading font-bold uppercase tracking-wider text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        @if($relatedProducts->count() > 0)
        <div class="border-t border-gray-200 dark:border-gray-800 pt-16">
            <h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-8 uppercase">
                Produk <span class="{{ $product->stores_id == 3 ? 'text-brand-red' : 'text-brand-orange' }}">Serupa</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($relatedProducts as $related)
                {{--
                       LOGIC WARNA CARD (Diterapkan ke $related):
                       Menggunakan logika yang sama persis dengan index.blade.php
                    --}}
                @php
                $isB11n = $related->stores_id == 3;
                $accentColor = $isB11n ? 'text-brand-red' : 'text-brand-orange';
                $btnColor = $isB11n ? 'bg-brand-red hover:bg-red-700' : 'bg-brand-orange hover:bg-orange-600';
                $storeName = ($related->stores_id == 2) ? 'ISTANA MERDEKA 03' : 'B11N & K1NG Store';
                @endphp

                <div class="group relative bg-white dark:bg-brand-gray rounded-xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-800 flex flex-col h-full">

                    {{-- Image Section --}}
                    <div class="relative w-full pt-[100%] overflow-hidden bg-gray-100 dark:bg-gray-900">
                        <img src="{{ asset('storage/' . $related->image) }}"
                            alt="{{ $related->name }}"
                            class="absolute top-0 left-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                        {{-- Badge Store --}}
                        <div class="absolute top-3 right-3 bg-black/70 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                            {{ $storeName }}
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5 flex flex-col flex-grow">
                        {{-- Category --}}
                        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">
                            {{ $related->categoryproduct->name ?? 'General' }}
                        </div>

                        {{-- Title --}}
                        <h3 class="text-lg font-heading font-bold text-gray-900 dark:text-white leading-tight mb-2 group-hover:{{ $accentColor }} transition-colors">
                            {{ $related->name }}
                        </h3>

                        {{-- Price --}}
                        <div class="text-xl font-bold {{ $accentColor }} mb-4">
                            Rp{{ number_format($related->price, 0, ',', '.') }}
                        </div>

                        {{-- Details / Description Logic --}}
                        <div class="flex-grow text-sm text-gray-500 dark:text-gray-400 space-y-2 mb-4">
                            @if(empty($related->flavour) && empty($related->serving_option))
                            <p class="line-clamp-2 text-xs leading-relaxed">
                                {{ $related->description ?? 'No description.' }}
                            </p>
                            @else
                            <div class="flex flex-col gap-1 text-xs font-medium">
                                @if(!empty($related->flavour))
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-utensils w-4 text-center opacity-50"></i>
                                    <span>{{ $related->flavour }}</span>
                                </div>
                                @endif
                                @if(!empty($related->serving_option))
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-box-open w-4 text-center opacity-50"></i>
                                    <span>{{ $related->serving_option }}</span>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        {{-- Button --}}
                        <a href="{{ route('store.product.show', $related->id) }}"
                            class="w-full block text-center py-3 rounded-lg font-heading font-bold uppercase tracking-wider text-sm text-white transition-all transform active:scale-95 {{ $btnColor }}">
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </main>

    @include('components.footer-bing-empire')

</body>

</html>