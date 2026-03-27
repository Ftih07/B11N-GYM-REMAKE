<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - B1NG Empire</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
    </style>
</head>

<body class="text-gray-800 flex flex-col min-h-screen">

    <nav class="bg-[#0D0D0D] p-4 shadow-lg border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
            <div class="font-extrabold text-xl tracking-tighter uppercase flex items-center gap-2">
                <i class="fas fa-edit text-[#E31E24]"></i> Edit <span class="text-[#E31E24]">{{ $product->name }}</span>
            </div>
            <a href="{{ route('employee.products.index') }}" class="text-sm font-bold uppercase hover:text-[#E31E24] transition">&larr; Kembali</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm mb-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3 text-xl mt-0.5"></i>
                <ul class="text-red-700 font-medium text-sm list-disc list-inside">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 mb-10 w-full">
        <form action="{{ route('employee.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <div class="mb-5 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-[#E31E24] text-lg"></i>
                            <div>
                                <h2 class="text-lg font-black text-[#0D0D0D] tracking-tight uppercase">Informasi Produk</h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Nama Produk <span class="text-[#E31E24]">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50 font-bold">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Deskripsi <span class="text-[#E31E24]">*</span></label>
                                <textarea name="description" rows="4" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Varian Rasa</label>
                                <input type="text" name="flavour" value="{{ old('flavour', $product->flavour) }}" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50">
                            </div>

                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Takaran Saji</label>
                                <input type="text" name="serving_option" value="{{ old('serving_option', $product->serving_option) }}" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200" x-data="imageViewer('{{ asset('storage/' . $product->image) }}')">
                        <div class="mb-5 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-image text-[#E31E24] text-lg"></i>
                            <div>
                                <h2 class="text-lg font-black text-[#0D0D0D] tracking-tight uppercase">Gambar Produk</h2>
                                <p class="text-[11px] text-gray-500 font-bold tracking-widest uppercase">Biarkan kosong jika tidak mengubah gambar.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 relative overflow-hidden transition">
                                <img :src="imageUrl" x-show="imageUrl" class="absolute inset-0 w-full h-full object-contain p-2 bg-white">
                                <input type="file" name="image" class="hidden" accept="image/*" @change="fileChosen" />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <h2 class="text-sm font-black text-gray-400 tracking-widest uppercase mb-4 border-b border-gray-100 pb-2">Harga & Status</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Harga (IDR) <span class="text-[#E31E24]">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500 font-bold">Rp</span>
                                    <input type="number" name="price" value="{{ old('price', round($product->price)) }}" required class="w-full pl-10 p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50 font-black">
                                </div>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Status <span class="text-[#E31E24]">*</span></label>
                                <select name="status" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50 font-bold text-gray-700">
                                    <option value="ready" {{ old('status', $product->status) == 'ready' ? 'selected' : '' }}>🟢 Ready Stock</option>
                                    <option value="soldout" {{ old('status', $product->status) == 'soldout' ? 'selected' : '' }}>🔴 Sold Out</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <h2 class="text-sm font-black text-gray-400 tracking-widest uppercase mb-4 border-b border-gray-100 pb-2">Kategori & Lokasi</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Kategori <span class="text-[#E31E24]">*</span></label>
                                <select name="category_products_id" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50 font-medium">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_products_id', $product->category_products_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Toko / Cabang <span class="text-[#E31E24]">*</span></label>
                                <select name="stores_id" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50 font-medium">
                                    @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('stores_id', $product->stores_id) == $store->id ? 'selected' : '' }}>{{ $store->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#E31E24] text-white py-4 rounded-xl font-black text-lg uppercase tracking-wider hover:bg-red-700 transition shadow-lg shadow-red-500/30 flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Revisi
                    </button>
                </div>
            </div>
        </form>
    </main>

    @include('components.footer-compact')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('imageViewer', (initialImage = '') => ({
                imageUrl: initialImage,
                fileChosen(event) {
                    this.fileToDataUrl(event, src => this.imageUrl = src)
                },
                fileToDataUrl(event, callback) {
                    if (!event.target.files.length) return
                    let file = event.target.files[0],
                        reader = new FileReader()
                    reader.readAsDataURL(file)
                    reader.onload = e => callback(e.target.result)
                },
            }));
        });
    </script>
</body>

</html>