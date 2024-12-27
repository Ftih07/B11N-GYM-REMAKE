<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/colab.png'))">

    <title>Checkout Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/index.css')
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
        rel="stylesheet" />
</head>

<body class="bg-gray-100 dark:bg-black transition-colors duration-300">
    @extends('layouts.main')

    <nav class="bg-white dark:bg-black">
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

    <menu class="z-50">
        <a href="{{ route('home') }}" class="action"><i class="fas fa-home"></i></a>
        <a href="{{ route('kost') }}" class="action"><i class="fas fa-bed"></i></a>
        <a href="{{ route('index') }}" class="action"><img src="assets/Logo/biin.png" alt="B11N Gym" /></a>
        <a href="{{ route('kinggym') }}" class="action bg-cover object-cover"><img src="assets/Logo/last.png" alt="K1NG Gym" /></a>
        <a href="#" class="trigger"><i class="fas fa-plus"></i></a>
    </menu>

    @section('title', 'Your Cart')

    <main class="container mx-auto mt-0 sm:mt-10 px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

            <div class="col-span-2 bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-md mt-32 sm:mt-0">
                <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 dark:text-white text-black">Opsi Pembayaran</h1>

                <!-- Payment Options -->
                <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-6">
                    <button
                        class="flex-1 flex justify-center items-center py-2 px-4 border border-gray-300 dark:bg-gray-700 bg-blue-100 text-blue-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="qris-button">
                        <img src="https://home.oxygen.id/assets/images/info-pembayaran/qris-logo.png" alt="QRIS Logo" class="h-10 w-auto">
                    </button>

                    <button
                        class="flex-1 flex justify-center items-center py-2 px-4 border border-gray-300 dark:bg-gray-700 bg-blue-100 text-blue-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="bca-button">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA Logo" class="h-10 w-auto">
                    </button>
                </div>

                <form>
                    <div class="text-center sm:place-items-center" id="payment-form">
                        <!-- Default Content -->
                        <p class="text-gray-600 dark:text-gray-400">Silahkan pilih opsi pembayaran anda.</p>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-md">
                <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 flex justify-between items-center dark:text-white text-black">
                    Order Details
                    <button id="toggleDetails" class="text-blue-500 text-base flex items-center">
                        Details <span class="arrow ml-2">▲</span>
                    </button>
                </h1>
                <div class="space-y-4">

                    <!-- Table Wrapper for Responsiveness -->
                    <div class="overflow-x-auto">
                        <table id="orderDetails" class="min-w-full rounded-lg shadow bg-white dark:bg-gray-800 text-black">
                            @if (empty($cart))
                            <p class="text-gray-400 dark:text-white">Your cart is empty.</p>
                            @else
                            <thead>
                                <tr class="text-left border-b bg-gray-200 dark:bg-gray-700">
                                    <th class="py-3 px-4 dark:text-white text-black">Product</th>
                                    <th class="py-3 px-4 dark:text-white text-black">Quantity</th>
                                    <th class="py-3 px-4 dark:text-white text-black">Total</th>
                                    <th class="py-3 px-4 dark:text-white text-black">Gym</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $id => $item)
                                <tr class="border-b">
                                    <td class="py-4 px-4 flex items-center">
                                        <p class="ml-4 dark:text-white text-black">{{ $item['name'] }}</p>
                                    </td>
                                    <td class="px-4 dark:text-white text-black">{{ $item['quantity'] }}</td>
                                    <td class="py-4 px-4 dark:text-white text-black">
                                        Rp{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </td>
                                    <td class="py-4 px-4 dark:text-white text-black">
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr class="border-t border-black mt-5 dark:border-white">

                    <!-- Summary Section -->
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 mt-5 dark:text-white text-black">Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="dark:text-white text-black">Subtotal</span>
                            <span class="dark:text-white text-black">
                                Rp{{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="dark:text-white text-black">Taxes</span>
                            <span class="dark:text-white text-black">Rp0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="dark:text-white text-black">Shipping</span>
                            <span class="dark:text-white text-black">Rp0.00</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg sm:text-xl">
                            <span class="dark:text-white text-black">Total</span>
                            <span class="dark:text-white text-black">
                                Rp{{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
    <script src="assets/js/script.js"></script>


    <!-- Footer -->
    <script>
        document.getElementById('toggleDetails').addEventListener('click', function() {
            const orderDetails = document.getElementById('orderDetails');
            const button = this;
            if (orderDetails.style.display === 'none') {
                orderDetails.style.display = 'block';
                button.textContent = 'Details ▲';
            } else {
                orderDetails.style.display = 'none';
                button.textContent = ' Details ▼';
            }
        });
        // Get elements
        const qrisButton = document.getElementById('qris-button');
        const bcaButton = document.getElementById('bca-button');
        const paymentForm = document.getElementById('payment-form');

        // QRIS Content
        const qrisContent = `
            <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm place-items-center dark:bg-gray-700">
                <h1 class="font-bold text-xl mb-5 dark:text-white text-black">Pembayaran via Qris</h1>
                <div class="flex flex-col items-center mb-4">
                <img src="assets/img/pembayaran/qris-barcode.png" alt="QR Code" class="w-screen h-auto sm:w-48 sm:h-48">
                </div>
                <div class="text-gray-800 text-sm mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                </svg>
                <span class="font-medium dark:text-white text-black">Cara membayar</span>
                </div>

                <div class="bg-blue-50 p-4 rounded-md text-gray-700 text-xs sm:text-sm text-left">
                <ol class="list-decimal pl-4">
                    <li class="mb-3 sm:mb-0">
                    Buka aplikasi e-money anda.
                    </li>
                    <li class="mb-3 sm:mb-0">
                    <span class="font-medium">Scan QRIS</span> yang ada dilayar.
                    </li>
                    <li class="mb-3 sm:mb-0">
                    Masukkan jumlah nominal total pembelian anda.
                    </li>
                    <li class="mb-3 sm:mb-0">
                    Simpan bukti pembayaran lalu konfirmasi pembayaran anda melalui <span class="font-medium">Whatsapp</span> atau <span class="font-medium">Email</span>
                    </li>
                    <li class="mb-3 sm:mb-0">
                    Pembayaran Selesai.
                    </li>
                </ol>
                </div>
            </div>

            <div class="place-items-center mt-5">
                <h1 class="font-semibold text-xl mb-5 dark:text-white text-black">Pilih Opsi Konfirmasi Pembayaran</h1>
            </div>

            <div class="sm:flex justify-between mb-6 items-center w-full">
                <a href="https://wa.me/+6281226110988" target="_blank" class="flex py-2 px-4 w-full border border-gray-300 bg-[#25D366] rounded-md focus:outline-none focus:ring-2 focus:ring-[#25D366] mr-3 mb-4 sm:mb-0">
                    <img src="https://www.svgrepo.com/show/38250/whatsapp.svg" class="h-5 w-auto mr-3">
                    <p>WhatsApp</p>
                </a>
                <a href="mailto:naufalfathi37@gmail.com" class="flex py-2 px-4 w-full border border-gray-300 bg-[#F77737] rounded-md focus:outline-none focus:ring-2 focus:ring-[#F77737]">
                    <img src="https://www.svgrepo.com/show/521128/email-1.svg" class="h-7 w-auto mr-2">
                    <p>Email</p>
                </a>
            </div>
    `;

        // BCA Content
        const bcaContent = `
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md w-full max-w-sm place-items-center">
                <h1 class="font-bold text-xl mb-5 dark:text-white text-black">Pembayaran via BCA</h1>
                
                <div class="flex flex-col items-center mb-14 mt-10">
                    <p class="font-semibold dark:text-white text-black">Bank: BCA</p>
                    <p class="dark:text-white text-black">No. Rekening: 0461701506</p>
                    <p class="dark:text-white text-black">Atas Nama: Sobiin</p>      
                </div>

                <div class="text-gray-800 text-sm mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                </svg>
                <span class="font-medium dark:text-white text-black">Cara membayar</span>
                </div>

                <div class="bg-blue-50 p-4 rounded-md text-gray-700 text-xs sm:text-sm text-left">
                <ol class="list-decimal pl-4">
                    <li class="mb-3 sm:mb-0">
                    Buka Aplikasi E-money atau Mobile Banking anda.
                    </li>
                    <li class="mb-3 sm:mb-0">
                    Cari Menu Transfer
                    </li>
                    <li class="mb-3 sm:mb-0">
                    Pilih tujuan transfer anda dan masukkan no. rekening yang tertera diatas.
                    </li>
                    <li class="mb-3 sm:mb-0">
                    Masukkan jumlah nominal total pembelian anda.
                    </li>
                    <li class="mb-3 sm:mb-0">
                    Simpan bukti pembayaran lalu konfirmasi pembayaran anda melalui <span class="font-medium">Whatsapp</span> atau <span class="font-medium">Email</span>
                    </li>
                    <li class="mb-3 sm:mb-0">
                    Pembayaran Selesai.
                    </li>
                </ol>
                </div>
            </div>

            <div class="place-items-center mt-5">
                <h1 class="font-semibold text-xl mb-5 dark:text-white text-black">Pilih Opsi Konfirmasi Pembayaran</h1>
            </div>

            <div class="sm:flex justify-between mb-6 items-center w-full">
                <button class="flex py-2 px-4 w-full border border-gray-300 bg-[#25D366] rounded-md focus:outline-none focus:ring-2 focus:ring-[#25D366] mr-3 mb-4 sm:mb-0" href="https://wa.me/+6281226110988">
                    <img src="https://www.svgrepo.com/show/38250/whatsapp.svg" class="h-5 w-auto mr-3">
                    <p>WhatsApp</p>
                </button>
                <button class="flex py-2 px-4 w-full border border-gray-300 bg-[#F77737] rounded-md focus:outline-none focus:ring-2 focus:ring-[#F77737]">
                    <img src="https://www.svgrepo.com/show/521128/email-1.svg" class="h-7 w-auto mr-2">
                    <p>Email</p>
                </button>
            </div>
    `;

        // Event listeners for buttons
        qrisButton.addEventListener('click', () => {
            paymentForm.innerHTML = qrisContent;
        });

        bcaButton.addEventListener('click', () => {
            paymentForm.innerHTML = bcaContent;
        });

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

        // Nav Utama
        const trigger = document.querySelector("menu > .trigger");
        trigger.addEventListener('click', (e) => {
            e.currentTarget.parentElement.classList.toggle("open");
        });
    </script>
</body>

</html>