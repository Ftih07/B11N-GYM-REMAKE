<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - B1NG Empire</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #E31E24;
        }
    </style>
</head>

<body class="text-gray-800 flex flex-col min-h-screen">

    <nav class="bg-[#0D0D0D] shadow-lg border-b border-gray-800 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-8">
                    <a href="{{ route('employee.dashboard') }}" class="flex-shrink-0 flex items-center gap-2">
                        <span class="text-2xl font-black text-white tracking-tighter uppercase">B1NG <span class="text-[#E31E24]">EMPIRE</span></span>
                    </a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('employee.dashboard') }}" class="text-gray-300 hover:text-white hover:bg-gray-800 px-3 py-2 rounded-md text-sm font-bold uppercase tracking-wider transition">POS Kasir</a>
                        <a href="{{ route('employee.products.index') }}" class="text-white bg-gray-900 px-3 py-2 rounded-md text-sm font-bold uppercase tracking-wider">Manajemen Produk</a>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    <div class="text-sm text-gray-300 flex flex-col items-end">
                        <span class="font-bold text-white uppercase">{{ auth()->user()->name }}</span>
                        <span class="text-xs">Kasir / Trainer</span>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-[#E31E24] flex items-center justify-center text-white font-bold border-2 border-gray-700">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <form action="{{ route('employee.logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="border border-gray-600 text-gray-300 hover:bg-[#E31E24] hover:text-white hover:border-[#E31E24] px-4 py-1.5 rounded-lg text-xs font-bold uppercase transition">Sign Out</button>
                    </form>
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-300 hover:text-white focus:outline-none"><i class="fas fa-bars text-2xl"></i></button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" class="md:hidden bg-gray-900 border-t border-gray-800" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('employee.dashboard') }}" class="text-gray-300 hover:text-white hover:bg-gray-700 block px-3 py-2 rounded-md text-base font-bold uppercase">POS Kasir</a>
                <a href="{{ route('employee.products.index') }}" class="bg-gray-800 text-white block px-3 py-2 rounded-md text-base font-bold uppercase">Manajemen Produk</a>
                <div class="border-t border-gray-700 mt-2 pt-2">
                    <form action="{{ route('employee.logout') }}" method="POST" class="px-3 mt-2">
                        @csrf <button type="submit" class="w-full bg-[#E31E24] text-white px-3 py-2 rounded-md text-sm font-bold uppercase">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    </div>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 mb-10 w-full" x-data="productSystem({{ json_encode($products->items()) }})">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-xl font-black text-[#0D0D0D] uppercase tracking-tight"><i class="fas fa-box-open text-[#E31E24] mr-2"></i> Daftar Produk</h2>
                <a href="{{ route('employee.products.create') }}" class="bg-[#0D0D0D] text-white px-5 py-2.5 rounded-lg font-bold text-sm uppercase hover:bg-gray-800 transition shadow-sm inline-flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
            </div>

            <div class="p-4 bg-gray-50 border-b border-gray-100">
                <form action="{{ route('employee.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div class="lg:col-span-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Cari Produk</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / Deskripsi..." class="w-full pl-9 p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Kategori</label>
                        <select name="category_products_id" class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_products_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Status</label>
                        <select name="status" class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                            <option value="">Semua Status</option>
                            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="soldout" {{ request('status') == 'soldout' ? 'selected' : '' }}>Sold Out</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-grow">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Cabang/Toko</label>
                            <select name="stores_id" class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                                <option value="">Semua Toko</option>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ request('stores_id') == $store->id ? 'selected' : '' }}>{{ $store->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end pb-[1px]">
                            <button type="submit" class="bg-gray-200 text-gray-700 p-2.5 rounded-lg hover:bg-[#E31E24] hover:text-white transition w-[42px] h-[40px] flex items-center justify-center">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="p-4 font-bold">Produk</th>
                            <th class="p-4 font-bold">Kategori / Lokasi</th>
                            <th class="p-4 font-bold">Harga</th>
                            <th class="p-4 font-bold">Status</th>
                            <th class="p-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm">
                                <div>
                                    <div class="font-black text-[#0D0D0D]">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 font-medium">{{ $product->flavour ?? 'Tidak ada varian' }}</div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-700">{{ $product->categoryproduct->name ?? 'Tanpa Kategori' }}</div>
                                <div class="text-xs text-gray-500 mt-1"><i class="fas fa-map-marker-alt text-[#E31E24] mr-1"></i> {{ $product->store->title ?? 'Semua Cabang' }}</div>
                            </td>
                            <td class="p-4 font-black text-[#E31E24]">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                @if($product->status == 'ready')
                                <span class="bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded text-[11px] font-bold uppercase">Ready</span>
                                @else
                                <span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded text-[11px] font-bold uppercase">Sold Out</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openView('{{ $product->id }}')" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 text-blue-600 rounded-lg hover:bg-blue-50 transition shadow-sm" title="Lihat Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <a href="{{ route('employee.products.edit', $product->id) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition shadow-sm" title="Edit Produk">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>

                                    <button @click="openDelete('{{ $product->id }}')" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 text-red-600 rounded-lg hover:bg-red-50 transition shadow-sm" title="Hapus Produk">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p class="font-medium text-lg text-gray-500">Tidak ada produk ditemukan.</p>
                                <p class="text-sm">Coba ubah filter atau tambah produk baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $products->withQueryString()->links() }}
            </div>
            @endif
        </div>

        <div x-show="viewModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm px-4">
            <div @click.away="viewModalOpen = false" class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" x-transition.scale.origin.top>
                <div class="bg-gray-900 p-4 flex justify-between items-center text-white shrink-0">
                    <h3 class="font-bold uppercase tracking-wider flex items-center gap-2"><i class="fas fa-box text-[#E31E24]"></i> Detail Produk</h3>
                    <button @click="viewModalOpen = false" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
                </div>

                <div class="p-0 overflow-y-auto flex-grow" x-show="activeProduct">
                    <div class="bg-gray-100 w-full h-64 flex items-center justify-center border-b border-gray-200">
                        <img :src="'{{ asset('storage') }}/' + activeProduct?.image" class="h-full w-full object-cover">
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-black text-[#0D0D0D]" x-text="activeProduct?.name"></h2>
                                <p class="text-[#E31E24] font-black text-xl mt-1" x-text="'Rp ' + formatRp(activeProduct?.price)"></p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border"
                                :class="activeProduct?.status === 'ready' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'"
                                x-text="activeProduct?.status"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Kategori</p>
                                <p class="text-sm font-semibold text-gray-800" x-text="activeProduct?.categoryproduct?.name || '-'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Lokasi Toko</p>
                                <p class="text-sm font-semibold text-gray-800" x-text="activeProduct?.store?.title || '-'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Varian Rasa</p>
                                <p class="text-sm font-semibold text-gray-800" x-text="activeProduct?.flavour || '-'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Takaran Saji</p>
                                <p class="text-sm font-semibold text-gray-800" x-text="activeProduct?.serving_option || '-'"></p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Deskripsi Produk</p>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap" x-text="activeProduct?.description"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm px-4">
            <div @click.away="deleteModalOpen = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center" x-transition.scale.origin.bottom>
                <div class="w-16 h-16 bg-red-100 text-[#E31E24] rounded-full flex items-center justify-center text-3xl mx-auto mb-4"><i class="fas fa-trash-alt"></i></div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Hapus Produk?</h3>
                <p class="text-sm text-gray-500 mb-6">Anda yakin ingin menghapus <strong x-text="activeProduct?.name" class="text-gray-800"></strong> secara permanen?</p>

                <form :action="`{{ url('/employee/products') }}/${activeProduct?.id}`" method="POST" class="flex gap-3 justify-center">
                    @csrf @method('DELETE')
                    <button type="button" @click="deleteModalOpen = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold uppercase hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-[#E31E24] text-white rounded-xl text-sm font-bold uppercase hover:bg-red-700 transition">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </main>

    @include('components.footer-compact')

    {{-- KOTAK NOTIFIKASI BAWAAN DIHAPUS (Agar UI lebih bersih) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    </div>

    {{-- SCRIPT MODERN TOAST NOTIFICATION --}}
    <script>
        // FUNGSI UNTUK MEMUNCULKAN TOAST NOTIFICATION MODERN (Durasi 10 Detik)
        function showModernToast(message, type = 'success') {
            const existingToast = document.getElementById('modern-toast');
            if (existingToast) existingToast.remove();

            const isSuccess = type === 'success';
            const borderColor = isSuccess ? '#16a34a' : '#dc2626';
            const iconColor = isSuccess ? '#22c55e' : '#ef4444';
            const iconBg = isSuccess ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)';
            const progressBg = isSuccess ? '#16a34a' : '#dc2626';

            const svgIcon = isSuccess ?
                `<svg style="width: 24px; height: 24px; color: ${iconColor};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>` :
                `<svg style="width: 24px; height: 24px; color: ${iconColor};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;

            const toastHTML = `
                <div id="modern-toast" style="position: fixed; top: 20px; left: 50%; transform: translate(-50%, -20px); opacity: 0; z-index: 9999; transition: all 0.5s ease; pointer-events: none; width: max-content; max-width: 90vw; font-family: 'Poppins', sans-serif;">
                    <div style="background-color: #171717; border: 1px solid ${borderColor}; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); border-radius: 8px; pointer-events: auto; overflow: hidden; position: relative; min-width: 300px;">
                        
                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background-color: #262626;">
                            <div id="toast-progress-bar" style="height: 100%; width: 100%; background-color: ${progressBg};"></div>
                        </div>

                        <div style="padding: 16px; display: flex; align-items: center; gap: 16px;">
                            <div style="background-color: ${iconBg}; padding: 8px; border-radius: 50%; flex-shrink: 0; display: flex;">
                                ${svgIcon}
                            </div>
                            <div style="flex: 1; font-size: 14px; font-weight: 500; color: #ffffff; padding-right: 16px; line-height: 1.4;">
                                ${message}
                            </div>
                            <button onclick="closeModernToast()" style="background: transparent; border: none; color: #737373; cursor: pointer; flex-shrink: 0; display: flex; padding: 0;">
                                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', toastHTML);

            const toast = document.getElementById('modern-toast');
            const progressBar = document.getElementById('toast-progress-bar');

            setTimeout(() => {
                toast.style.transform = 'translate(-50%, 0)';
                toast.style.opacity = '1';
            }, 50);

            setTimeout(() => {
                progressBar.style.transition = 'width 10s linear';
                progressBar.style.width = '0%';
            }, 100);

            window.modernToastTimeout = setTimeout(() => {
                closeModernToast();
            }, 10000);
        }

        window.closeModernToast = function() {
            const toast = document.getElementById('modern-toast');
            if (!toast) return;

            clearTimeout(window.modernToastTimeout);
            toast.style.transform = 'translate(-50%, -20px)';
            toast.style.opacity = '0';
            setTimeout(() => {
                if (toast) toast.remove();
            }, 500);
        };
    </script>

    {{-- TRIGGER NOTIFIKASI SUCCESS --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showModernToast('{{ session("success") }}', 'success');
        });
    </script>
    @endif

    {{-- TRIGGER NOTIFIKASI ERROR (Berjaga-jaga jika ada error validasi) --}}
    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorMsg = "<strong>Terjadi Kesalahan:</strong><br>";
            @foreach($errors-> all() as $error)
            errorMsg += "- {{ $error }}<br>";
            @endforeach
            showModernToast(errorMsg, 'error');
        });
    </script>
    @endif

    {{-- SCRIPT ALPINE.JS BAWAAN POS PRODUK --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productSystem', (productsData) => ({
                viewModalOpen: false,
                deleteModalOpen: false,
                activeProduct: null,
                allProducts: productsData,

                openView(id) {
                    this.activeProduct = this.allProducts.find(p => p.id === id);
                    this.viewModalOpen = true;
                },
                openDelete(id) {
                    this.activeProduct = this.allProducts.find(p => p.id === id);
                    this.deleteModalOpen = true;
                },
                formatRp(angka) {
                    if (!angka) return '0';
                    return parseInt(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }));
        });
    </script>
</body>

</html>