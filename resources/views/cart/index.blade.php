<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/index.css')

</head>

<body class="bg-gray-100 dark:bg-black transition-colors duration-300">
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

    @extends('layouts.main')

    @section('title', 'Your Cart')

    @section('content')
    <main class="container mx-auto mt-28 px-6">
        <h1 class="text-3xl font-bold mb-6 text-black dark:text-white">Shopping Cart</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">

                <table class="min-w-full rounded-lg shadow bg-white dark:bg-gray-800 text-black dark:text-white">
                    @if (empty($cart))
                    <p class="text-gray-400 dark:text-gray-500">Your cart is empty.</p>
                    @else
                    <thead>
                        <tr class="text-left border-b bg-gray-200 dark:bg-gray-700">
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Quantity</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Gym</th> <!-- Kolom untuk gym -->
                            <th class="py-3 px-4">Remove</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($cart as $id => $item)
                        <tr class="border-b dark:border-gray-700">

                            <!-- NAME & IMAGE PRODUCT -->
                            <td class="py-4 px-4 flex items-center">
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                <p class="ml-4">{{ $item['name'] }}</p>
                            </td>

                            <!-- PRICE -->
                            <td class="py-4 px-4">${{ number_format($item['price']) }}</td>

                            <!-- QUANTITY -->
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <form action="{{ route('cart.update', $id) }}" method="post" class="inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                        <button type="submit" class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500">-</button>
                                    </form>
                                    <span class="mx-4">{{ $item['quantity'] }}</span>
                                    <form action="{{ route('cart.update', $id) }}" method="post" class="inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button type="submit" class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500">+</button>
                                    </form>
                                </div>
                            </td>

                            <!-- TOTAL PRICE -->
                            <td class="py-4 px-4">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>

                            <!-- STORE NAME -->
                            <td class="py-4 px-4">
                                @if (isset($item['store_id']))
                                @if ($item['store_id'] == 1)
                                B11N Gym Store
                                @elseif ($item['store_id'] == 2)
                                K1NG Gym Store
                                @else
                                Unknown Store
                                @endif
                                @else
                                Unknown Store
                                @endif
                            </td>

                            <!-- DELETE -->
                            <td class="py-4 px-4">
                                <form action="{{ route('cart.remove', $id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md text-black dark:text-white" style="max-height: 300px;">
                <h2 class="text-2xl font-bold mb-4">Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Total: Rp{{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }} </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak</span>
                        <span>Rp0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkir</span>
                        <span>Rp0.00</span>
                    </div>
                    <div class="flex justify-between font-bold text-xl">
                        <span>Total</span>
                        <span> Total: Rp{{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }} </span>
                    </div>
                </div>
                <a href="{{ route('checkout') }}">
                    <button class="w-full mt-6 bg-blue-500 dark:bg-blue-600 text-white px-4 py-2 rounded-md">
                        Checkout
                    </button>
                </a>
            </div>
            @endif
        </div>

    </main>

    @endsection

    


    <!-- JS -->
    <script>
        // Automatically remove notification after 3 seconds
        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.transition = '0.5s';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500); // Remove after transition
            }
        }, 3000);

        // resources/js/darkMode.js
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