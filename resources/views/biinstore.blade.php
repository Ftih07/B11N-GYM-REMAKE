<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Cars</title>
    @vite('resources/css/app.css')
    @vite('resources/css/index.css')

</head>

<body class="m-0 p-0 bg-black text-white font-poppins">
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
    @foreach ($banner as $banner)
    <section
        style="background-image: url('{{ asset('storage/' . $banner->image) }}'); background-size: cover; background-position: center;"
        class="header mx-auto px-4 py-15"
        id="store">
        <div class="header__container max-w-[1200px] mx-auto px-4 py-20">
            <div class="header__content">
                <h1>{{ $banner->title }}</h1>
                <h2>{{ $banner->subheading }}</h2>
                <p>{{ $banner->description }}</p>
                <div class="header__btn">
                    <a href="{{ route('product.index') }}" class="btn btn__primary">VISIT STORE</a>
                </div>
            </div>
        </div>
    </section>
    @endforeach


    @if (session('success'))
    <div
        id="notification"
        class="flex items-center bg-green-500 text-white text-center py-2 px-4 rounded-lg shadow-lg fixed top-4 right-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif


    <div class="text-center py-8">
        <h1 class="text-4xl font-bold">B11N Gym Store</h1>
        <p class="text-gray-500 mt-2">Available {{ $totalProducts }} Products</p>
    </div>

    <!-- Filter Buttons -->
    <div class="flex justify-center gap-4 mb-8">
        <a href="{{ route('b11n.store', ['category' => null]) }}">
            <button class="px-4 py-2 {{ is_null(request('category')) ? 'bg-gray-700 text-white' : 'bg-gray-800 hover:bg-gray-700' }} rounded">All</button>
        </a>
        <a href="{{ route('b11n.store', ['category' => 1]) }}">
            <button class="px-4 py-2 {{ request('category') == 1 ? 'bg-gray-700 text-white' : 'bg-gray-800 hover:bg-gray-700' }} rounded">Makanan</button>
        </a>
        <a href="{{ route('b11n.store', ['category' => 2]) }}">
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
    </script>
</body>

</html>