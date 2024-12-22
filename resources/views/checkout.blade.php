<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCodeMania Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/index.css')
    @vite('resources/css/index.css')

</head>

<body class="bg-gray-100">
    <nav>
        @if (session('success'))
        <div class="bg-green-500 text-white text-center py-2">
            {{ session('success') }}
        </div>
        @endif

        <div class="nav__bar">
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="#"><img src="assets/logo.png" alt="logo" /></a>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links" id="nav-links">
                <li><a href="{{ route('index') }}">HOME</a></li>
                <li><a href="{{ route('product.index') }}">B11N & K1NG GYM STORE</a></li>
                <li><a href="{{ route('product.index') }}">B11N GYM STORE</a></li>
                <li><a href="{{ route('product.index') }}">K1NG GYM STORE</a></li>
                <li><a href="{{ route('cart.view') }}">KERANJANG</a></li>
            </ul>
        </div>
    </nav>

    @extends('layouts.main')

    @section('title', 'Your Cart')

    <main class="container mx-auto mt-10 px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

            <div class="col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h1 class="text-2xl font-bold mb-6">Opsi Pembayaran</h1>

                <div class="flex justify-between mb-6">
                    <button
                        class="flex-1 py-2 px-4 border border-gray-300 bg-blue-100 text-blue-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mr-5 place-items-center"
                        id="qris-button">
                        <img src="https://home.oxygen.id/assets/images/info-pembayaran/qris-logo.png" class="h-10 w-auto">
                    </button>

                    <button
                        class="flex-1 py-2 px-4 border border-gray-300 bg-blue-100 text-blue-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 place-items-center"
                        id="bca-button">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="h-10 w-auto">
                    </button>
                </div>

                <form>
                    <div class="place-items-center mb-5" id="payment-form">
                        <!-- Default Content -->
                        <p class="text-gray-600">Silahkan pilih opsi pembayaran anda.</p>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h1 class="text-2xl font-bold mb-6 flex justify-between items-center">
                    Order Details
                    <button id="toggleDetails" class="text-blue-500 text-base">
                        Details <span class="arrow">▲</span>
                    </button>
                </h1>
                <div class="space-y-4">

                    <table id="orderDetails" class="min-w-full rounded-lg shadow bg-white text-black">
                        @if (empty($cart))
                        <p class="text-gray-400">Your cart is empty.</p>
                        @else
                        <thead>
                            <tr class="text-left border-b bg-gray-200">
                                <th class="py-3 px-4">Product</th>
                                <th class="py-3 px-4">Quantity</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Gym</th> <!-- Kolom untuk gym -->
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($cart as $id => $item)
                            <tr class="border-b">

                                <!-- NAME & IMAGE PRODUCT -->
                                <td class="py-4 px-0 flex items-center">
                                    <p class="ml-4 ">{{ $item['name'] }}</p>
                                </td>

                                <!-- QUANTITY -->
                                <td class="px-10">{{ $item['quantity'] }}</td>

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
                                @endforeach
                        </tbody>
                    </table>
                    <hr class="border-t border-black mt-5">

                    <h2 class="text-2xl font-bold mb-4 mt-5">Summary</h2>
                    <div class="space-y-3 mb-3">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span> Total: ${{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }} </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Taxes</span>
                            <span>$1.99</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span>$0.00</span>
                        </div>
                        <div class="flex justify-between font-bold text-xl">
                            <span>Total</span>
                            <span> Total: ${{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }} </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>

    </main>


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
            <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm place-items-center">
                <h1 class="font-bold text-xl mb-5">Pembayaran via Qris</h1>
                <div class="flex flex-col items-center mb-4">
                <img src="assets/Pembayaran/qris-barcode.png" alt="QR Code" class="w-48 h-48">
                </div>

                <div class="text-gray-800 text-sm mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                </svg>
                <span class="font-medium">Cara membayar</span>
                </div>

                <div class="bg-blue-50 p-4 rounded-md text-gray-700 text-sm">
                <ol class="list-decimal pl-4">
                    <li>
                    Buka aplikasi e-money anda.
                    </li>
                    <li>
                    <span class="font-medium">Scan QRIS</span> yang ada dilayar.
                    </li>
                    <li>
                    Masukkan jumlah nominal total pembelian anda.
                    </li>
                    <li>
                    Simpan bukti pembayaran lalu konfirmasi pembayaran anda melalui <span class="font-medium">Whatsapp</span> atau <span class="font-medium">Email</span>
                    </li>
                    <li>
                    Pembayaran Selesai.
                    </li>
                </ol>
                </div>
            </div>

            <div class="place-items-center mt-5">
                <h1 class="font-semibold text-xl mb-5">Pilih Opsi Konfirmasi Pembayaran</h1>
            </div>

            <div class="flex justify-between mb-6 items-center w-full">
                <a href="https://wa.me/+6281226110988" target="_blank" class="flex py-2 px-4 w-full border border-gray-300 bg-[#25D366] rounded-md focus:outline-none focus:ring-2 focus:ring-[#25D366] mr-3">
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
            <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm place-items-center">
                <h1 class="font-bold text-xl mb-5">Pembayaran via BCA</h1>
                
                <div class="flex flex-col items-center mb-14 mt-10">
                    <p class="font-semibold">Bank: BCA</p>
                    <p>No. Rekening: 0461701506</p>
                    <p>Atas Nama: Sobiin</p>      
                </div>

                <div class="text-gray-800 text-sm mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                </svg>
                <span class="font-medium">Cara membayar</span>
                </div>

                <div class="bg-blue-50 p-4 rounded-md text-gray-700 text-sm">
                <ol class="list-decimal pl-4">
                    <li>
                    Buka Aplikasi E-money atau Mobile Banking anda.
                    </li>
                    <li>
                    Cari Menu Transfer
                    </li>
                    <li>
                    Pilih tujuan transfer anda dan masukkan no. rekening yang tertera diatas.
                    </li>
                    <li>
                    Masukkan jumlah nominal total pembelian anda.
                    </li>
                    <li>
                    Simpan bukti pembayaran lalu konfirmasi pembayaran anda melalui <span class="font-medium">Whatsapp</span> atau <span class="font-medium">Email</span>
                    </li>
                    <li>
                    Pembayaran Selesai.
                    </li>
                </ol>
                </div>
            </div>

            <div class="place-items-center mt-5">
                <h1 class="font-semibold text-xl mb-5">Pilih Opsi Konfirmasi Pembayaran</h1>
            </div>

            <div class="flex justify-between mb-6 items-center w-full">
                <button class="flex py-2 px-4 w-full border border-gray-300 bg-[#25D366] rounded-md focus:outline-none focus:ring-2 focus:ring-[#25D366] mr-3" href="https://wa.me/+6281226110988">
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
    </script>
</body>

</html>