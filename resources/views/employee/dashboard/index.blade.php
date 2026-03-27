<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Sistem - B1NG Empire</title>
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
                        <a href="{{ route('employee.dashboard') }}" class="text-white bg-gray-900 px-3 py-2 rounded-md text-sm font-bold uppercase tracking-wider">POS Kasir</a>
                        <a href="{{ route('employee.products.index') }}" class="text-gray-300 hover:text-white hover:bg-gray-800 px-3 py-2 rounded-md text-sm font-bold uppercase tracking-wider transition">Manajemen Produk</a>
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
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-300 hover:text-white focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" class="md:hidden bg-gray-900 border-t border-gray-800" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('employee.dashboard') }}" class="bg-gray-800 text-white block px-3 py-2 rounded-md text-base font-bold uppercase">POS Kasir</a>
                <a href="{{ route('employee.products.index') }}" class="text-gray-300 hover:text-white hover:bg-gray-700 block px-3 py-2 rounded-md text-base font-bold uppercase">Manajemen Produk</a>
                <div class="border-t border-gray-700 mt-2 pt-2">
                    <p class="text-gray-400 px-3 py-1 text-sm">Login sebagai: <span class="text-white font-bold">{{ auth()->user()->name }}</span></p>
                    <form action="{{ route('employee.logout') }}" method="POST" class="px-3 mt-2">
                        @csrf
                        <button type="submit" class="w-full bg-[#E31E24] text-white px-3 py-2 rounded-md text-sm font-bold uppercase">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm mb-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                <p class="text-green-700 font-semibold">{{ session('success') }}</p>
            </div>
        </div>
        @endif
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

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 mb-10 w-full" x-data="posSystem({{ json_encode($products) }})">
        <form action="{{ route('employee.transaction.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8 flex flex-col h-full">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex-grow flex flex-col overflow-hidden">
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h2 class="text-lg font-black text-[#0D0D0D] tracking-tight uppercase flex items-center gap-2">
                                <i class="fas fa-shopping-cart text-[#E31E24]"></i> Keranjang Belanja
                            </h2>
                            <button type="button" @click="addItem()" class="bg-[#0D0D0D] text-white px-4 py-2 rounded-lg font-bold text-sm uppercase tracking-wide hover:bg-gray-800 transition shadow-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah
                            </button>
                        </div>

                        <div class="p-5 space-y-4 overflow-y-auto flex-grow" style="max-height: 60vh;">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center bg-white p-4 rounded-xl border border-gray-200 shadow-sm relative group hover:border-[#E31E24] transition">
                                    <button type="button" @click="removeItem(index)" class="absolute -top-3 -right-3 bg-white text-gray-400 border border-gray-200 rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition shadow-sm z-10"><i class="fas fa-times"></i></button>

                                    <div class="flex-1 w-full">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Pilih Produk</label>
                                        <select x-model="item.productId" @change="updateItem(index)" :name="'items['+index+'][product_id]'" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] focus:border-[#E31E24] bg-gray-50 font-medium">
                                            <option value="">-- Cari / Pilih Produk --</option>
                                            @foreach($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->name }} - Rp {{ number_format($prod->price, 0, ',', '.') }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-full sm:w-24">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Qty</label>
                                        <input type="number" x-model.number="item.qty" @input="updateItem(index)" :name="'items['+index+'][quantity]'" min="1" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50 font-bold text-center">
                                    </div>

                                    <div class="w-full sm:w-40">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Subtotal</label>
                                        <div class="w-full p-2.5 bg-gray-100 border border-gray-200 rounded-lg text-right font-black text-gray-700 text-sm" x-text="'Rp ' + formatRupiah(item.subtotal)"></div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="items.length === 0" class="text-center py-12 flex flex-col items-center justify-center opacity-60">
                                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 font-medium">Keranjang masih kosong.</p>
                                <p class="text-sm text-gray-400 mt-1">Klik tombol tambah untuk memulai transaksi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                        <h2 class="text-sm font-black text-gray-400 tracking-widest uppercase mb-4 border-b border-gray-100 pb-2">Informasi</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Cabang (Gymkos)</label>
                                <select name="gymkos_id" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50 font-medium">
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($gymkosList as $cabang)
                                    <option value="{{ $cabang->id }}">{{ $cabang->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Nama Pelanggan</label>
                                <input type="text" name="customer_name" placeholder="Opsional..." class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex-grow flex flex-col">
                        <h2 class="text-sm font-black text-gray-400 tracking-widest uppercase mb-4 border-b border-gray-100 pb-2">Pembayaran</h2>

                        <div class="bg-gray-900 rounded-xl p-4 mb-5 text-center shadow-inner relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-1 bg-[#E31E24]"></div>
                            <span class="block text-xs text-gray-400 font-bold mb-1 tracking-wider">TOTAL TAGIHAN</span>
                            <span class="text-3xl font-black text-white" x-text="'Rp ' + formatRupiah(totalAmount)">Rp 0</span>
                            <input type="hidden" name="total_amount" :value="totalAmount">
                        </div>

                        <div class="space-y-4 flex-grow">
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Metode</label>
                                <select x-model="paymentMethod" @change="updatePayment()" name="payment_method" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] font-bold text-gray-700">
                                    <option value="cash">💵 TUNAI (CASH)</option>
                                    <option value="qris">📱 QRIS</option>
                                    <option value="transfer">🏦 TRANSFER BANK</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Uang Diterima</label>
                                    <input type="number" x-model.number="paidAmount" @input="updatePayment()" name="paid_amount" :readonly="paymentMethod !== 'cash'" required
                                        class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] font-black text-center"
                                        :class="{'bg-gray-100 text-gray-400 cursor-not-allowed': paymentMethod !== 'cash'}">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Kembali</label>
                                    <div class="w-full p-2.5 border border-gray-200 bg-gray-50 rounded-lg text-center font-black text-sm"
                                        :class="{'text-[#E31E24]': changeAmount < 0, 'text-green-600': changeAmount >= 0}"
                                        x-text="'Rp ' + formatRupiah(changeAmount)">Rp 0</div>
                                </div>
                            </div>

                            <div x-show="paymentMethod !== 'cash'" x-transition class="pt-2">
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Upload Bukti</label>
                                <input type="file" name="proof_of_payment" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-[#E31E24] hover:file:bg-red-100 cursor-pointer border border-gray-200 rounded-lg p-1 bg-gray-50">
                            </div>
                        </div>

                        <button type="submit" :disabled="items.length === 0 || changeAmount < 0"
                            class="w-full mt-6 bg-[#E31E24] text-white py-4 rounded-xl font-black text-lg uppercase tracking-wider hover:bg-red-700 transition shadow-lg shadow-red-500/30 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i> Selesaikan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20 w-full" x-data="riwayatSystem({{ json_encode($transactions->items()) }})">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-black text-[#0D0D0D] uppercase tracking-tight mb-4"><i class="fas fa-history text-gray-400 mr-2"></i> Riwayat Transaksi Saya</h2>

                <form action="{{ route('employee.dashboard') }}" method="GET" class="bg-gray-50 p-4 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                    <div class="lg:col-span-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Cari Data</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode TRX / Nama..." class="w-full pl-9 p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Tgl Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Tgl Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Metode</label>
                        <select name="payment_method" class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                            <option value="">Semua</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-grow">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-1">Cabang</label>
                            <select name="gymkos_id" class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24]">
                                <option value="">Semua</option>
                                @foreach($gymkosList as $cabang)
                                <option value="{{ $cabang->id }}" {{ request('gymkos_id') == $cabang->id ? 'selected' : '' }}>{{ $cabang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end pb-[1px]">
                            <button type="submit" class="bg-[#0D0D0D] text-white p-2.5 rounded-lg hover:bg-[#E31E24] transition w-[42px] h-[40px] flex items-center justify-center">
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
                            <th class="p-4 font-bold">Detail Transaksi</th>
                            <th class="p-4 font-bold">Cabang</th>
                            <th class="p-4 font-bold">Total</th>
                            <th class="p-4 font-bold">Metode</th>
                            <th class="p-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4">
                                <div class="font-black text-[#0D0D0D]">{{ $trx->code }}</div>
                                <div class="text-xs text-gray-500 mt-1"><i class="far fa-clock mr-1"></i> {{ $trx->created_at->format('d M Y, H:i') }}</div>
                                @if($trx->customer_name)
                                <div class="text-xs text-gray-600 mt-1"><i class="far fa-user mr-1"></i> {{ $trx->customer_name }}</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="bg-gray-200 text-gray-700 px-2.5 py-1 rounded text-[11px] font-bold uppercase">{{ $trx->gymkos->name ?? '-' }}</span>
                            </td>
                            <td class="p-4 font-black text-[#E31E24]">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase border 
                                    {{ $trx->payment_method == 'cash' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                                    {{ $trx->payment_method }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openDetail({{ $trx->id }})" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 text-blue-600 rounded-lg hover:bg-blue-50 transition shadow-sm" title="Lihat Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <a href="{{ route('employee.transaction.edit', $trx->id) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition shadow-sm" title="Edit Transaksi">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <button @click="openDelete({{ $trx->id }})" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 text-red-600 rounded-lg hover:bg-red-50 transition shadow-sm" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                    <a href="{{ route('print.struk', $trx->code) }}" target="_blank" class="inline-flex items-center justify-center bg-gray-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold uppercase hover:bg-[#E31E24] transition shadow-sm">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400">
                                <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                                <p class="font-medium text-lg text-gray-500">Tidak ada transaksi ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $transactions->withQueryString()->links() }}
            </div>
            @endif
        </div>

        <div x-show="detailModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm px-4">
            <div @click.away="detailModalOpen = false" class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" x-transition.scale.origin.top>
                <div class="bg-gray-900 p-4 flex justify-between items-center text-white shrink-0">
                    <h3 class="font-bold uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-receipt text-[#E31E24]"></i> Detail Transaksi
                    </h3>
                    <button @click="detailModalOpen = false" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
                </div>

                <div class="p-6 overflow-y-auto flex-grow" x-show="activeTrx">
                    <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-1">Kode / Pelanggan</p>
                            <p class="text-lg font-black text-[#E31E24]" x-text="activeTrx?.code"></p>
                            <p class="text-sm font-semibold text-gray-800 mt-1"><i class="far fa-user mr-1 text-gray-400"></i> <span x-text="activeTrx?.customer_name || 'Pelanggan Umum'"></span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-1">Metode</p>
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-md text-xs font-black uppercase border border-gray-200" x-text="activeTrx?.payment_method"></span>
                        </div>
                    </div>

                    <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Daftar Barang Belanjaan</h4>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6 space-y-3">
                        <template x-for="item in activeTrx?.items" :key="item.id">
                            <div class="flex justify-between items-center text-sm border-b border-gray-200 pb-2 last:border-0 last:pb-0">
                                <div>
                                    <p class="font-bold text-gray-800" x-text="item.product_name"></p>
                                    <p class="text-xs text-gray-500"><span x-text="item.quantity"></span> x Rp <span x-text="formatRp(item.price)"></span></p>
                                </div>
                                <div class="font-black text-[#0D0D0D]" x-text="'Rp ' + formatRp(item.subtotal)"></div>
                            </div>
                        </template>
                    </div>

                    <div class="bg-[#0D0D0D] text-white p-4 rounded-xl flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Pembayaran</span>
                        <span class="text-xl font-black text-[#E31E24]" x-text="'Rp ' + formatRp(activeTrx?.total_amount)"></span>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm px-4">
            <div @click.away="deleteModalOpen = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center" x-transition.scale.origin.bottom>
                <div class="w-16 h-16 bg-red-100 text-[#E31E24] rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Hapus Transaksi?</h3>
                <p class="text-sm text-gray-500 mb-6">Anda yakin ingin menghapus transaksi <strong x-text="activeTrx?.code" class="text-gray-800"></strong> secara permanen?</p>

                <form :action="`{{ url('/employee/transaction') }}/${activeTrx?.id}`" method="POST" class="flex gap-3 justify-center">
                    @csrf @method('DELETE')
                    <button type="button" @click="deleteModalOpen = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold uppercase hover:bg-gray-200 transition">Kembali</button>
                    <button type="submit" class="flex-1 py-2.5 bg-[#E31E24] text-white rounded-xl text-sm font-bold uppercase hover:bg-red-700 transition">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </section>

    @include('components.footer-compact')

    @if(session('print_code'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let printUrl = "{{ route('print.struk', session('print_code')) }}";
            window.open(printUrl, '_blank');
        });
    </script>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {

            // LOGIKA MODAL RIWAYAT
            Alpine.data('riwayatSystem', (transactionsData) => ({
                detailModalOpen: false,
                deleteModalOpen: false,
                activeTrx: null,
                allTrx: transactionsData,

                openDetail(id) {
                    this.activeTrx = this.allTrx.find(t => t.id === id);
                    this.detailModalOpen = true;
                },
                openDelete(id) {
                    this.activeTrx = this.allTrx.find(t => t.id === id);
                    this.deleteModalOpen = true;
                },
                formatRp(angka) {
                    if (!angka) return '0';
                    return parseInt(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }));

            // LOGIKA POS KERANJANG
            Alpine.data('posSystem', (productsData) => ({
                products: productsData,
                items: [],
                totalAmount: 0,
                paymentMethod: 'cash',
                paidAmount: 0,
                changeAmount: 0,

                formatRupiah(angka) {
                    if (!angka) return '0';
                    return parseInt(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },
                addItem() {
                    this.items.push({
                        productId: '',
                        price: 0,
                        qty: 1,
                        subtotal: 0
                    });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateTotal();
                },
                updateItem(index) {
                    let item = this.items[index];
                    let product = this.products.find(p => p.id == item.productId);

                    if (product) {
                        item.price = product.price;
                        if (item.qty < 1 || isNaN(item.qty)) item.qty = 1;
                        item.subtotal = item.price * item.qty;
                    } else {
                        item.price = 0;
                        item.subtotal = 0;
                    }
                    this.calculateTotal();
                },
                calculateTotal() {
                    this.totalAmount = this.items.reduce((sum, item) => sum + item.subtotal, 0);
                    this.updatePayment();
                },
                updatePayment() {
                    if (this.paymentMethod === 'qris' || this.paymentMethod === 'transfer') {
                        this.paidAmount = this.totalAmount;
                        this.changeAmount = 0;
                    } else {
                        this.changeAmount = this.paidAmount - this.totalAmount;
                    }
                }
            }));
        });
    </script>
</body>

</html>