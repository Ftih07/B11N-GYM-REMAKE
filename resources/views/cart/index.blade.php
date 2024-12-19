<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Cars</title>
    @vite('resources/css/app.css')
</head>

<body class="m-0 p-0 bg-black text-white font-poppins">

    @extends('layouts.main')

    @section('title', 'Your Cart')

    @section('content')
    <h1 class="text-2xl font-bold mb-4">Shopping Cart</h1>

    @if (empty($cart))
    <p>Your cart is empty.</p>
    @else
    <table class="min-w-full rounded-lg shadow">
        <thead>
            <tr class="">
                <th class="py-2 px-4">Product</th>
                <th class="py-2 px-4">Quantity</th>
                <th class="py-2 px-4">Price</th>
                <th class="py-2 px-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $id => $item)
            <tr>
                <td class="py-2 px-4">{{ $item['name'] }}</td>
                <td class="py-2 px-4">
                    <div class="flex items-center">
                        <form action="{{ route('cart.update', $id) }}" method="post" class="inline">
                            @csrf
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                            <button type="submit" class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">-</button>
                        </form>

                        <span class="px-4">{{ $item['quantity'] }}</span>

                        <form action="{{ route('cart.update', $id) }}" method="post" class="inline">
                            @csrf
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                            <button type="submit" class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">+</button>
                        </form>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                </td>
                <td class="py-2 px-4">
                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                </td>
                <td class="py-2 px-4">
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

    @if (!empty($cart))
    <div class="mt-4 text-right">
        <h2 class="text-xl font-bold">
            Total: ${{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) }}
        </h2>
        <a href="{{ route('checkout') }}" class="inline-block bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition mt-4">
            Checkout
        </a>
    </div>
    @endif


    @endif
    @endsection


</body>

</html>