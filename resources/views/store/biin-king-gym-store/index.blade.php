<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- 1. TITLE DINAMIS: Menyesuaikan Kategori Produk --}}
    @php
    $pageTitle = match(request('category')) {
    '1' => 'Menu Makanan Sehat & Diet',
    '2' => 'Minuman Protein & Suplemen',
    default => 'Katalog Lengkap B11N & K1NG Gym Store'
    };
    @endphp
    <title>{{ $pageTitle }} - Pusat Nutrisi Fitness Purwokerto</title>

    {{-- 2. META DESCRIPTION: Mencakup dua brand sekaligus --}}
    <meta name="description" content="Belanja kebutuhan fitness dari B11N Gym dan K1NG Gym dalam satu tempat. Jual makanan diet, whey protein, pre-workout, dan merchandise gym official di Purwokerto.">

    {{-- 3. KEYWORDS: Gabungan keyword kedua lokasi --}}
    <meta name="keywords" content="toko gym purwokerto, suplemen fitness ledug, makanan diet arcawinangun, b11n store, k1ng gym store, jual whey purwokerto, catering sehat banyumas">

    <meta name="author" content="B1NG EMPIRE">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- 4. OPEN GRAPH (E-Commerce Catalog) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="B11N & K1NG Gym Store - Official Merchandise & Nutrition">
    <meta property="og:description" content="Satu destinasi untuk semua kebutuhan nutrisimu. Cek katalog produk terbaru kami sekarang!">
    <meta property="og:url" content="{{ url()->current() }}">
    {{-- Gunakan logo Colab (gabungan) jika ada, atau fallback ke salah satu --}}
    <meta property="og:image" content="{{ asset('assets/Logo/colab.png') }}">
    <meta property="og:site_name" content="B1NG EMPIRE Store">

    {{-- 5. GEO TAGS (Menggunakan Pusat Kota Purwokerto agar netral) --}}
    <meta name="geo.region" content="ID-JT" />
    <meta name="geo.placename" content="Purwokerto" />
    <meta name="geo.position" content="-7.4243;109.2391" />
    <meta name="ICBM" content="-7.4243, 109.2391" />

    {{-- 6. SCHEMA MARKUP (CollectionPage) --}}
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CollectionPage",
            "name": "Katalog B11N & K1NG Gym Store",
            "description": "Daftar lengkap produk makanan, minuman, dan perlengkapan dari B11N dan K1NG Gym.",
            "url": "{{ url()->current() }}",
            "mainEntity": {
                "@type": "ItemList",
                "itemListElement": [{
                        "@type": "ListItem",
                        "position": 1,
                        "url": "{{ route('store.biin-king', ['category' => 1]) }}",
                        "name": "Makanan Sehat"
                    },
                    {
                        "@type": "ListItem",
                        "position": 2,
                        "url": "{{ route('store.biin-king', ['category' => 2]) }}",
                        "name": "Minuman & Suplemen"
                    }
                ]
            }
        }
    </script>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/colab.png'))">

    {{-- Stylesheets --}}
    @vite(['resources/css/app.css', 'resources/css/store/biin-king-gym-store/index.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body class="m-0 p-0 bg-white text-white font-poppins dark:bg-black transition-colors duration-300">
    @if (session('success'))
    <div
        id="notification"
        class="flex items-center bg-green-500 text-white text-center py-2 px-4 rounded-lg shadow-lg fixed top-4 right-4 z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Navigation Section -->
    <nav class="fixed bg-white dark:bg-black">
        <div class="nav__bar">
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="#"><img src="assets/Logo/colab.png" alt="logo" /></a>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links dark:text-white text-black" id="nav-links">
                <li>
                    <a href="{{ route('home') }}"
                        class="{{ Route::currentRouteName() === 'home' ? 'active' : '' }}">HOME</a>
                </li>
                <li>
                    <a href="{{ route('store.biin-king') }}"
                        class="{{ Route::currentRouteName() === 'store.biin-king' ? 'active' : '' }}">B11N & K1NG GYM STORE</a>
                </li>
                <li>
                    <a href="{{ route('store.biin') }}"
                        class="{{ Route::currentRouteName() === 'store.biin' ? 'active' : '' }}">B11N GYM STORE</a>
                </li>
                <li>
                    <a href="{{ route('store.king') }}"
                        class="{{ Route::currentRouteName() === 'store.king' ? 'active' : '' }}">K1NG GYM STORE</a>
                </li>
                <div class="mode rounded-full" id="switch-mode">
                    <div class="btn__indicator">
                        <div class="btn__icon-container">
                            <i class="btn__icon fa-solid"></i>
                        </div>
                    </div>
                </div>
            </ul>
        </div>
    </nav>

    <menu class="z-50">
        <a href="{{ route('home') }}" class="action"><img src="assets/Logo/empire.png" alt="B1NG Empire" /></a>
        <a href="{{ route('kost') }}" class="action"><img src="assets/Logo/kost.png" alt="Istana Merdeka 03" /></a>
        <a href="{{ route('gym.biin') }}" class="action"><img src="assets/Logo/biin.png" alt="B11N Gym" /></a>
        <a href="{{ route('gym.king') }}" class="action bg-cover object-cover"><img src="assets/Logo/last.png" alt="K1NG Gym" /></a>
        <a href="#" class="trigger"><i class="fas fa-plus"></i></a>
    </menu>

    <!-- Header Section -->

    @foreach ($banner as $banner)
    <section
        style="background-image: url('{{ asset('storage/' . $banner->image) }}'); background-size: cover; background-position: center;"
        class="header mx-auto px-4 py-15 bg-gray-300 dark:bg-black"
        id="store">
        <div class="header__container max-w-[1200px] mx-auto px-4 py-20">
            <div class="header__content">
                <h1 class="text-[#dc030a]">{{ $banner->title }}</h1>
                <h2 class="text-black dark:text-white">{{ $banner->subheading }}</h2>
                <p class="text-black dark:text-white">{{ $banner->description }}</p>
                <div class="header__btn">
                    <a href="#" class="btn btn__primary">{{ $banner->location }}</a>
                </div>
            </div>
        </div>
    </section>
    @endforeach

    <div class="text-center py-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-black dark:text-white">B11N & K1NG Gym Store</h1>
        <p class="text-gray-500 mt-2">Available {{ $totalProducts }} Products</p>
    </div>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap justify-center gap-4 mb-8">
        <a href="{{ route('store.biin-king', ['category' => null]) }}">
            <button class="px-3 py-2 sm:px-4 sm:py-2 {{ is_null(request('category')) ? 'bg-gray-700 text-white' : 'bg-gray-800 hover:bg-gray-700' }} rounded">All</button>
        </a>
        <a href="{{ route('store.biin-king', ['category' => 1]) }}">
            <button class="px-3 py-2 sm:px-4 sm:py-2 {{ request('category') == 1 ? 'bg-gray-700 text-white' : 'bg-gray-800 hover:bg-gray-700' }} rounded">Makanan</button>
        </a>
        <a href="{{ route('store.biin-king', ['category' => 2]) }}">
            <button class="px-3 py-2 sm:px-4 sm:py-2 {{ request('category') == 2 ? 'bg-gray-700 text-white' : 'bg-gray-800 hover:bg-gray-700' }} rounded">Minuman</button>
        </a>
    </div>


    <div class="container max-w-[1400px] mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach ($products as $product)
        <div
            class="shadow rounded-lg p-4 border transition {{ $product->stores_id == 1 ? 'hover:border-red-600' : 'hover:border-yellow-600' }}">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                <p class="text-red-600 font-bold text-xl mt-2">Rp{{ number_format($product->price, 2) }}</p>
                <p class="text-gray-500 text-xs mt-2">
                    @if ($product->stores_id == 1)
                    Dari: B11N Gym Store
                    @elseif ($product->stores_id == 2)
                    Dari: K1NG Gym Store
                    @endif
                </p>
                <div class="flex items-center mt-1 text-gray-500 text-xs">
                    <span class="flex items-center"><i class="fas fa-utensils mr-1"></i>{{ $product->flavour }}</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-box-open mr-1"></i>{{ $product->serving_option }}</span>
                </div>
                <a href="{{ route('store.product.show', $product->id) }}"
                    class="{{ $product->stores_id == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-500 hover:bg-yellow-600' }} block text-white text-center py-2 rounded mt-4 transition">
                    View Details
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <footer class="footer mt-10" id="contact">
        <div class="footer__bar">
            Copyright © {{ date('Y') }} B1NG EMPIRE. All rights reserved.
        </div>
    </footer>
    <script src="assets/js/script.js"></script>

    <script>
        // Automatically remove notification after 3 seconds
        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.transition = 'opacity 0.5s';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500); // Remove after transition
            }
        }, 3000);

        document.addEventListener('DOMContentLoaded', function() {
            const toggleDarkMode = document.getElementById('switch-mode');
            const htmlElement = document.documentElement;
            const icon = document.querySelector('.btn__icon');

            // Jika localStorage belum ada, set default ke light mode
            if (!localStorage.theme) {
                localStorage.theme = 'light';
            }

            // Terapkan mode yang tersimpan di localStorage
            if (localStorage.theme === 'dark') {
                htmlElement.classList.add('dark');
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                htmlElement.classList.remove('dark');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }

            // Event toggle dark mode
            toggleDarkMode.addEventListener('click', function() {
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.theme = 'light';
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.theme = 'dark';
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
            });
        });


        const body = document.querySelector('body');
        const btn = document.querySelector('.mode');
        const icon = document.querySelector('.btn__icon');

        //to save the dark mode use the object "local storage".

        //function that stores the value true if the dark mode is activated or false if it's not.
        function store(value) {
            localStorage.setItem('darkmode', value);
        }

        //function that indicates if the "darkmode" property exists. It loads the page as we had left it.
        function load() {
            const darkmode = localStorage.getItem('darkmode');

            //if the dark mode was never activated
            if (!darkmode) {
                store(false);
                icon.classList.add('fa-sun');
            } else if (darkmode == 'true') { //if the dark mode is activated
                body.classList.add('darkmode');
                icon.classList.add('fa-moon');
            } else if (darkmode == 'false') { //if the dark mode exists but is disabled
                icon.classList.add('fa-sun');
            }
        }


        load();

        btn.addEventListener('click', () => {

            body.classList.toggle('darkmode');
            icon.classList.add('animated');

            //save true or false
            store(body.classList.contains('darkmode'));

            if (body.classList.contains('darkmode')) {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }

            setTimeout(() => {
                icon.classList.remove('animated');
            }, 500)
        })

        // Nav Utama
        const trigger = document.querySelector("menu > .trigger");
        trigger.addEventListener('click', (e) => {
            e.currentTarget.parentElement.classList.toggle("open");
        });
    </script>

</body>

</html>