<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    @vite('resources/css/app.css')
    @vite('resources/css/index.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

    <nav class="fixed bg-white dark:bg-black">
        <div class="nav__bar">
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="#"><img src="assets/logo.png" alt="logo" /></a>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links dark:text-white text-black" id="nav-links">
                <li>
                    <a href="{{ route('index') }}"
                        class="{{ Route::currentRouteName() === 'index' ? 'active' : '' }}">HOME</a>
                </li>
                <li>
                    <a href="{{ route('product.index') }}"
                        class="{{ Route::currentRouteName() === 'product.index' ? 'active' : '' }}">B11N & K1NG GYM STORE</a>
                </li>
                <li>
                    <a href="{{ route('b11n.store') }}"
                        class="{{ Route::currentRouteName() === 'b11n.store' ? 'active' : '' }}">B11N GYM STORE</a>
                </li>
                <li>
                    <a href="{{ route('king.store') }}"
                        class="{{ Route::currentRouteName() === 'king.store' ? 'active' : '' }}">K1NG GYM STORE</a>
                </li>
                <li>
                    <a href="{{ route('cart.view') }}"
                        class="{{ Route::currentRouteName() === 'cart.view' ? 'active' : '' }}">KERANJANG</a>
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

    {{-- resources/views/product/show.blade.php --}}
    <div class="max-w-[1300px] mx-auto p-4">

        <!-- Product Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-28">
            <!-- Image Section -->
            <div>
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100 h-[33em] object-cover rounded-lg lg:rounded-lg object-top">
            </div>

            <!-- Details Section -->
            <div>
                <h1 class="text-6xl font-bold text-black dark:text-white mb-2">{{ $product->name }}</h1>
                <p class="text-3xl font-semibold text-red-600 mb-5">Rp{{ number_format($product->price, 2) }}</p>
                <hr class="mb-5 bg-black dark:bg-white">
                <div class="text-gray-300 text-sm space-y-0.5">
                    <h1 class="text-xl font-semibold text-black dark:text-white mb-2">Product Details</h1>
                    <p class="text-black dark:text-white"> @if ($product->stores_id == 1)
                        From: B11N Gym Store
                        @elseif ($product->stores_id == 2)
                        From: K1NG Gym Store
                        @endif
                    </p>
                    <p class="text-black dark:text-white">Flavour: {{ $product->flavour }}</p>
                    <p class="text-black dark:text-white">Serving Option: {{ $product->serving_option }}</p>
                    <p class="text-black dark:text-white">Category: {{ $product->categoryproduct->name ?? 'No Category' }}</p>
                </div>
                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-black dark:text-white mb-2">Deskripsi Produk</h2>
                    <p class="text-sm text-black dark:text-white">{{ $product->description ?? 'No description available' }}</p>
                </div>

                <form action="{{ route('cart.add', $product->id) }}" method="post">
                    @csrf
                    <button type="submit" class="{{ $product->stores_id == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-500 hover:bg-yellow-600' }} w-1/4 bg-red-600 text-white font-medium py-2 rounded-md hover:bg-red-700 transition mt-6">Add To Cart</button>
                </form>

            </div>
        </div>
    </div>

    <div class="mt-8 max-w-[1300px] mx-auto p-4">
        <hr class="mb-10">
        <h3 class="text-2xl font-bold mb-5">Produk Serupa</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($relatedProducts as $relatedProduct)
            <div
                class="shadow rounded-lg p-4 border transition {{ $relatedProduct->stores_id == 1 ? 'hover:border-red-600' : 'hover:border-yellow-600' }}">
                <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover rounded-md">
                <div class="mt-4">
                    <h4 class="text-lg font-semibold">{{ $relatedProduct->name }}</h4>
                    <p class="text-red-600 font-bold text-xl mt-2">${{ number_format($relatedProduct->price, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        @if ($relatedProduct->stores_id == 1)
                        Dari: B11N Gym Store
                        @elseif ($relatedProduct->stores_id == 2)
                        Dari: K1NG Gym Store
                        @endif
                    </p>
                    <div class="flex mt-1 text-gray-500 text-xs">
                        <span class="flex items-center"><i class="fas fa-utensils mr-1"></i>{{ $product->flavour }}</span>
                        <span class="mx-2">|</span>
                        <span class="flex items-center"><i class="fas fa-box-open mr-1"></i>{{ $product->serving_option }}</span>
                    </div>
                    <a href="{{ route('product.show', $relatedProduct->id) }}"
                        class="{{ $relatedProduct->stores_id == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-500 hover:bg-yellow-600' }} block text-white text-center py-2 rounded mt-4 transition">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <footer class="footer mt-10" id="contact">
        <div class="footer__bar">
            Copyright © 2024 Multicore. All rights reserved.
        </div>
    </footer>

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

            // Cek mode dari LocalStorage
            if (localStorage.theme === 'dark') {
                htmlElement.classList.add('dark');
            } else if (localStorage.theme === 'light') {
                htmlElement.classList.remove('dark');
            }

            // Event toggle
            toggleDarkMode.addEventListener('click', function() {
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.theme = 'light'; // Simpan ke LocalStorage
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.theme = 'dark'; // Simpan ke LocalStorage
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
    </script>
</body>

</html>