<div class="flex justify-end mb-4">
        <a href="{{ route('cart.view') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            View Cart
        </a>
    </div>
    <!-- Cars Grid -->
    {{-- resources/views/product/show.blade.php --}}
    <div class="container mx-auto px-4 py-8">
        <div class="mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <!-- Gambar Produk -->
                <div class="lg:w-1/4">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100 h-96 object-cover rounded-t-lg lg:rounded-l-lg">
                </div>

                <!-- Detail Produk -->
                <div class="lg:w-1/2 p-6 flex flex-col justify-between">
                    <div>
                        <!-- Nama dan Harga -->
                        <h1 class="text-3xl font-semibold text-gray-800">{{ $product->name }}</h1>
                        <p class="text-red-600 font-bold text-2xl mt-2">${{ number_format($product->price, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            @if ($product->stores_id == 1)
                            Dari: B11N Gym Store
                            @elseif ($product->stores_id == 2)
                            Dari: K1NG Gym Store
                            @endif
                        </p>

                        <!-- Deskripsi -->
                        <div class="mt-4 text-gray-600 text-sm space-y-2">
                            <p><strong>Flavour:</strong> {{ $product->flavour }}</p>
                            <p><strong>Serving Option:</strong> {{ $product->serving_option }}</p>
                            <p><strong>Category:</strong> {{ $product->categoryproduct->name ?? 'No Category' }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <!-- Deskripsi Produk -->
                        <p class="font-semibold text-lg text-gray-800">Description:</p>
                        <p class="text-gray-600">{{ $product->description ?? 'No description available' }}</p>
                    </div>

                    <!-- Tombol Action -->
                    <div class="mt-6 flex gap-4">
                        <form action="{{ route('cart.add', $product->id) }}" method="post">
                            @csrf
                            <button
                                type="submit"
                                class="{{ $product->stores_id == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-500 hover:bg-yellow-600' }} text-white px-6 py-3 rounded-lg transition">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-2xl font-bold mb-4">Produk Serupa</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($relatedProducts as $relatedProduct)
            <div
                class="shadow rounded-lg p-4 border transition {{ $relatedProduct->stores_id == 1 ? 'hover:border-red-600' : 'hover:border-yellow-600' }}">
                <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover rounded-md">
                <div class="mt-4">
                    <h4 class="text-lg font-semibold">{{ $relatedProduct->name }}</h4>
                    <p class="text-red-600 font-bold text-xl mt-2">${{ number_format($relatedProduct->price, 2) }}</p>
                    <p class="text-sm text-gray-500">
                        @if ($relatedProduct->stores_id == 1)
                        Dari: B11N Gym Store
                        @elseif ($relatedProduct->stores_id == 2)
                        Dari: K1NG Gym Store
                        @endif
                    </p>
                    <a href="{{ route('product.show', $relatedProduct->id) }}"
                        class="{{ $relatedProduct->stores_id == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-500 hover:bg-yellow-600' }} block text-white text-center py-2 rounded mt-4 transition">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>