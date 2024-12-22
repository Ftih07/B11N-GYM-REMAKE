<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Cars</title>
    @vite('resources/css/app.css')
</head>

<body class="m-0 p-0 bg-black text-white font-poppins">

    @if (session('success'))
    <div class="bg-green-500 text-white text-center py-2">
        {{ session('success') }}
    </div>
    @endif

    <div class="text-center py-8">
        <h1 class="text-4xl font-bold">K1NG Gym Store</h1>
        <p class="text-gray-500 mt-2">Available {{ $totalProducts }} Products</p>
    </div>

    <div class="flex justify-center gap-4 mb-8">
        <a href="{{ route('b11n.store') }}">
            <button class="px-4 py-2 bg-gray-700 text-white hover:bg-gray-700 rounded">B11N Gym Store</button>
        </a>
        <a href="{{ route('king.store') }}">
            <button class="px-4 py-2 bg-gray-700 text-white hover:bg-gray-700 rounded">K1NG Gym Store</button>
        </a>
    </div>
    <!-- Filter Buttons -->
    <div class="flex justify-center gap-4 mb-8">
        <a href="{{ route('king.store', ['category' => null]) }}">
            <button class="px-4 py-2 {{ is_null(request('category')) ? 'bg-gray-700 text-white' : 'bg-gray-800 hover:bg-gray-700' }} rounded">All</button>
        </a>
        <a href="{{ route('king.store', ['category' => 1]) }}">
            <button class="px-4 py-2 {{ request('category') == 1 ? 'bg-gray-700 text-white' : 'bg-gray-800 hover:bg-gray-700' }} rounded">Makanan</button>
        </a>
        <a href="{{ route('king.store', ['category' => 2]) }}">
            <button class="px-4 py-2 {{ request('category') == 2 ? 'bg-gray-700 text-white' : 'bg-gray-800 hover:bg-gray-700' }} rounded">Minuman</button>
        </a>
    </div>


    <div class="container mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach ($products as $product)
        <div
            class="shadow rounded-lg p-4 border transition {{ $product->stores_id == 1 ? 'hover:border-red-600' : 'hover:border-yellow-600' }}">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-md">
            <div class="mt-4">
                <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                <p class="text-red-600 font-bold text-xl mt-2">${{ number_format($product->price, 2) }}</p>
                <p class="text-gray-500 text-sm">
                    @if ($product->stores_id == 1)
                    Dari: B11N Gym Store
                    @elseif ($product->stores_id == 2)
                    Dari: K1NG Gym Store
                    @endif
                </p>
                <div class="flex items-center mt-4 text-gray-500 text-sm">
                    <span class="flex items-center"><i class="fas fa-cogs mr-1"></i>{{ $product->flavour }}</span>
                    <span class="mx-2">|</span>
                    <span class="flex items-center"><i class="fas fa-box-open mr-1"></i>{{ $product->serving_option }}</span>
                </div>
                <div class="flex mt-4 gap-2">
                    <form action="{{ route('cart.add', $product->id) }}" method="post">
                        @csrf
                        <button
                            type="submit"
                            class="{{ $product->stores_id == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-500 hover:bg-yellow-600' }} text-white px-6 py-3 rounded-lg transition">
                            Add to Cart
                        </button>
                    </form>
                    <button class="bg-gray-300 text-black px-3 py-1 rounded">
                        <a href="{{ route('product.show', $product->id) }}">View Details</a>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>


</body>

</html>