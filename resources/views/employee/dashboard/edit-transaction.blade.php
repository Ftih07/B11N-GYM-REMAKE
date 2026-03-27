<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - B1NG Empire</title>
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

<body class="text-gray-800 flex flex-col min-h-screen pb-20">

    <nav class="bg-[#0D0D0D] p-4 shadow-lg border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
            <div class="font-extrabold text-xl tracking-tighter uppercase">Edit Transaksi <span class="text-[#E31E24]">{{ $transaction->code }}</span></div>
            <a href="{{ route('employee.dashboard') }}" class="text-sm font-bold uppercase hover:text-[#E31E24] transition">&larr; Batal</a>
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

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 mb-10 w-full"
        x-data="editSystem(
            {{ json_encode($products) }}, 
            {{ json_encode($transaction->items) }},
            {{ round($transaction->total_amount ?? 0) }},
            '{{ $transaction->payment_method ?? 'cash' }}',
            {{ round($transaction->paid_amount ?? 0) }},
            {{ round($transaction->change_amount ?? 0) }}
        )">

        <form action="{{ route('employee.transaction.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8 flex flex-col h-full">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex-grow flex flex-col overflow-hidden">
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h2 class="text-lg font-black text-[#0D0D0D] tracking-tight uppercase"><i class="fas fa-shopping-cart text-[#E31E24]"></i> Revisi Keranjang</h2>
                            <button type="button" @click="addItem()" class="bg-[#0D0D0D] text-white px-4 py-2 rounded-lg font-bold text-sm uppercase hover:bg-gray-800 transition shadow-sm"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>

                        <div class="p-5 space-y-4 overflow-y-auto flex-grow" style="max-height: 60vh;">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center bg-white p-4 rounded-xl border border-gray-200 shadow-sm relative hover:border-[#E31E24] transition">
                                    <button type="button" @click="removeItem(index)" class="absolute -top-3 -right-3 bg-white text-gray-400 border border-gray-200 rounded-full w-8 h-8 flex items-center justify-center hover:text-red-500 hover:border-red-200 transition shadow-sm z-10"><i class="fas fa-times"></i></button>

                                    <div class="flex-1 w-full">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Produk</label>
                                        <select x-model="item.productId" @change="updateItem(index)" :name="'items['+index+'][product_id]'" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50 font-medium">
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
                            <div x-show="items.length === 0" class="text-center py-12 text-gray-400 font-medium">Keranjang Kosong. Silakan tambah produk.</div>
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
                                    @foreach($gymkosList as $cabang)
                                    <option value="{{ $cabang->id }}" {{ $transaction->gymkos_id == $cabang->id ? 'selected' : '' }}>{{ $cabang->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Nama Pelanggan</label>
                                <input type="text" name="customer_name" value="{{ old('customer_name', $transaction->customer_name) }}" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] bg-gray-50">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex-grow flex flex-col">
                        <h2 class="text-sm font-black text-gray-400 tracking-widest uppercase mb-4 border-b border-gray-100 pb-2">Pembayaran</h2>
                        <div class="bg-gray-900 rounded-xl p-4 mb-5 text-center shadow-inner relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-1 bg-[#E31E24]"></div>
                            <span class="block text-xs text-gray-400 font-bold mb-1 tracking-wider">TOTAL TAGIHAN</span>
                            <span class="text-3xl font-black text-white" x-text="'Rp ' + formatRupiah(totalAmount)"></span>
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
                                    <input type="number" x-model.number="paidAmount" @input="updatePayment()" name="paid_amount" :readonly="paymentMethod !== 'cash'" required class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#E31E24] font-black text-center" :class="{'bg-gray-100 text-gray-400 cursor-not-allowed': paymentMethod !== 'cash'}">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-gray-600 uppercase mb-1.5 block">Kembali</label>
                                    <div class="w-full p-2.5 border border-gray-200 bg-gray-50 rounded-lg text-center font-black text-sm" :class="{'text-[#E31E24]': changeAmount < 0, 'text-green-600': changeAmount >= 0}" x-text="'Rp ' + formatRupiah(changeAmount)"></div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" :disabled="items.length === 0 || changeAmount < 0" class="w-full mt-6 bg-[#E31E24] text-white py-4 rounded-xl font-black text-lg uppercase tracking-wider hover:bg-red-700 transition shadow-lg shadow-red-500/30 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Simpan Revisi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    @include('components.footer-compact')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editSystem', (productsData, existingItems, initTotal, initMethod, initPaid, initChange) => ({
                products: productsData,
                items: [],
                totalAmount: initTotal,
                paymentMethod: initMethod,
                paidAmount: initPaid,
                changeAmount: initChange,

                init() {
                    this.items = existingItems.map(item => ({
                        productId: item.product_id,
                        price: Math.round(item.price),
                        qty: parseInt(item.quantity),
                        subtotal: Math.round(item.subtotal)
                    }));
                    this.calculateTotal();
                },
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
            }))
        })
    </script>
</body>

</html>