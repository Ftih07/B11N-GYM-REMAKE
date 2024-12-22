<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCodeMania Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/index.css')

</head>

<body class="bg-gray-100">
    <nav>
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

    @section('content')
    <main class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="col-span-2 bg-white p-6 rounded-lg shadow-md">

                <table class="min-w-full rounded-lg shadow bg-white text-black">
                    @if (empty($cart))
                    <p class="text-gray-400">Your cart is empty.</p>
                    @else
                    <thead>
                        <tr class="text-left border-b bg-gray-200">
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
                        <tr class="border-b">

                            <!-- NAME & IMAGE PRODUCT -->
                            <td class="py-4 px-4 flex items-center">
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                <p class="ml-4 ">{{ $item['name'] }}</p>
                            </td>

                            <!-- PRICE -->
                            <td class="py-4 px-4">${{ number_format($item['price']) }}</td>

                            <!-- QUANTITY -->
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <form action="{{ route('cart.update', $id) }}" method="post" class="inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                        <button type="submit" class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">-</button>
                                    </form>
                                    <span class="mx-4">{{ $item['quantity'] }}</span>
                                    <form action="{{ route('cart.update', $id) }}" method="post" class="inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button type="submit" class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">+</button>
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

            <div class="bg-white p-6 rounded-lg shadow-md" style="max-height: 300px;">
                <h2 class="text-2xl font-bold mb-4">Summary</h2>
                <div class="space-y-3">
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
                <a href="{{ route('checkout') }}">
                    <button class="w-full mt-6 bg-blue-500 text-white px-4 py-2 rounded-md">
                        Checkout
                    </button>
                </a>
            </div>
            @endif
        </div>

    </main>
    @endsection


    <!-- Footer -->
</body>

</html>