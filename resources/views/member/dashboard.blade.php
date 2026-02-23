<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - B1NG Empire</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/empire.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        header: ['Oswald', 'sans-serif'],
                        body: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        dark: '#0a0a0a',
                        card: '#171717',
                        primary: '#DC2626',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-dark text-gray-200 font-body min-h-screen">

    @include('components.global-loader')

    @include('components.navbar')

    <div class="container mx-auto p-6 max-w-7xl">

        @if(session('success'))
        <div class="bg-green-900/50 border border-green-600 text-green-200 p-4 rounded mb-6 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- LOGIC CEK MEMBER --}}
        @if($member)

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-card border border-neutral-800 p-6 rounded-lg shadow-xl relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
                    <h3 class="font-header text-2xl font-bold mb-6 text-white uppercase tracking-wide">
                        Catat <span class="text-primary">Progress</span>
                    </h3>

                    <form action="{{ route('measurements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Berat Badan (kg)</label>
                            <input type="number" step="0.01" name="weight" class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition placeholder-gray-600" placeholder="0.0">
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Pinggang</label>
                                <input type="number" step="0.01" name="waist_size" class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-white focus:border-primary transition text-sm" placeholder="cm">
                            </div>
                            <div>
                                <label class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Lengan</label>
                                <input type="number" step="0.01" name="arm_size" class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-white focus:border-primary transition text-sm" placeholder="cm">
                            </div>
                            <div>
                                <label class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Paha</label>
                                <input type="number" step="0.01" name="thigh_size" class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-white focus:border-primary transition text-sm" placeholder="cm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Foto Body Check</label>
                            <input type="file" name="progress_photo" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-neutral-800 file:text-primary hover:file:bg-neutral-700 cursor-pointer">
                        </div>

                        <div>
                            <label class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Catatan</label>
                            <textarea name="notes" rows="2" class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-white focus:border-primary transition text-sm" placeholder="Target hari ini..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-red-700 text-white font-header font-bold uppercase tracking-wider py-3 px-4 rounded transition shadow-lg shadow-red-900/20">
                            Simpan Data
                        </button>
                    </form>
                </div>

                <div class="bg-card border border-neutral-800 p-6 rounded-lg shadow-xl mt-6 lg:hidden">
                    <h3 class="font-header text-xl font-bold text-white mb-2">Status Member</h3>
                    <p class="text-sm text-gray-400">Expired: <span class="text-white font-bold">{{ \Carbon\Carbon::parse($member->membership_end_date)->format('d M Y') }}</span></p>
                    <button onclick="toggleModal('renewModal')" class="mt-4 w-full bg-green-700 hover:bg-green-800 text-white py-2 rounded text-sm font-bold uppercase">Perpanjang</button>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">

                <div class="hidden lg:flex bg-gradient-to-r from-neutral-800 to-neutral-900 border border-neutral-800 p-6 rounded-lg shadow-xl justify-between items-center relative overflow-hidden">
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-primary/5 -skew-x-12 transform translate-x-10"></div>

                    <div>
                        <h3 class="font-header text-2xl font-bold text-white uppercase">Membership Status</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <p class="text-gray-400 text-sm">Berlaku sampai:</p>
                            <span class="text-primary font-header text-xl font-bold">
                                {{ \Carbon\Carbon::parse($member->membership_end_date)->format('d F Y') }}
                            </span>
                        </div>

                        @php
                        $statusClass = $member->status === 'active' ? 'bg-green-900/50 text-green-400 border-green-800' : 'bg-red-900/50 text-red-400 border-red-800';
                        $statusLabel = $member->status === 'active' ? 'ACTIVE MEMBER' : 'INACTIVE / EXPIRED';
                        @endphp
                        <span class="inline-block mt-3 border {{ $statusClass }} text-[10px] font-bold px-3 py-1 rounded uppercase tracking-widest">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="relative z-10">
                        <button type="button" onclick="toggleModal('renewModal')" class="bg-green-600 hover:bg-green-700 text-white font-header font-bold py-3 px-8 rounded shadow-lg transition transform hover:-translate-y-0.5">
                            PERPANJANG SEKARANG
                        </button>
                    </div>
                </div>

                <div class="bg-card border border-neutral-800 rounded-lg shadow-xl overflow-hidden">
                    <div class="p-4 border-b border-neutral-800 flex justify-between items-center">
                        <h3 class="font-header text-xl font-bold text-white uppercase tracking-wide">Riwayat Latihan</h3>
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Last 30 Days</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-400">
                            <thead class="bg-neutral-900 text-gray-500 uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="p-4 font-bold font-header">Tanggal</th>
                                    <th class="p-4 font-bold font-header">Berat</th>
                                    <th class="p-4 font-bold font-header hidden md:table-cell">Detail (cm)</th>
                                    <th class="p-4 font-bold font-header text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-800">
                                @forelse($history as $item)
                                <tr class="hover:bg-neutral-800/50 transition duration-150">
                                    <td class="p-4">
                                        <div class="text-white font-medium">{{ \Carbon\Carbon::parse($item->measured_at)->format('d/m/y') }}</div>
                                        <div class="md:hidden text-[10px] text-gray-500 mt-1 uppercase">
                                            P:{{ $item->waist_size ?? '-' }} | L:{{ $item->arm_size ?? '-' }} | Ph:{{ $item->thigh_size ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="text-primary font-bold text-base">{{ $item->weight ?? '-' }}</span><span class="text-[10px] ml-1">kg</span>
                                    </td>
                                    <td class="p-4 hidden md:table-cell">
                                        <div class="grid grid-cols-3 gap-2 text-[11px]">
                                            <div><span class="block text-gray-600 uppercase">Pinggang</span><span class="text-white">{{ $item->waist_size ?? '-' }}</span></div>
                                            <div><span class="block text-gray-600 uppercase">Lengan</span><span class="text-white">{{ $item->arm_size ?? '-' }}</span></div>
                                            <div><span class="block text-gray-600 uppercase">Paha</span><span class="text-white">{{ $item->thigh_size ?? '-' }}</span></div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                            @if($item->progress_photo)
                                            <a href="{{ Storage::url($item->progress_photo) }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-[10px] font-bold border border-blue-900 bg-blue-900/20 px-2 py-1.5 rounded uppercase text-center">Foto</a>
                                            @endif

                                            <button
                                                onclick="showNotes('{{ \Carbon\Carbon::parse($item->measured_at)->format('d M Y') }}', {{ json_encode($item->notes) }})"
                                                class="text-gray-400 hover:text-white text-[10px] font-bold border border-neutral-700 bg-neutral-800 px-2 py-1.5 rounded uppercase">
                                                Notes
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-600 italic">Belum ada data progress.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="notesModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                    <div class="bg-neutral-900 border border-neutral-800 p-6 rounded-lg max-w-sm w-full shadow-2xl">
                        <h4 id="notesDate" class="font-header text-primary font-bold uppercase tracking-widest text-sm mb-2"></h4>
                        <p id="notesContent" class="text-gray-300 text-sm leading-relaxed mb-6 italic"></p>
                        <button onclick="closeNotes()" class="w-full bg-neutral-800 hover:bg-neutral-700 text-white font-bold py-2 rounded uppercase text-xs">Tutup</button>
                    </div>
                </div>

                <script>
                    function showNotes(date, text) {
                        document.getElementById('notesDate').innerText = 'Catatan - ' + date;
                        document.getElementById('notesContent').innerText = text || 'Tidak ada catatan.';
                        document.getElementById('notesModal').classList.remove('hidden');
                    }

                    function closeNotes() {
                        document.getElementById('notesModal').classList.add('hidden');
                    }
                </script>

            </div>
        </div>

        @else
        <div class="text-center bg-yellow-900/20 border border-yellow-700/50 p-6 rounded-lg mt-6">
            <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-yellow-500 font-bold mb-1">DATA MEMBER TIDAK DITEMUKAN</p>
            <p class="text-gray-400 text-sm">Email ini (<span class="text-white">{{ $user->email ?? 'Akun Anda' }}</span>) belum terdaftar di sistem gym kami. Hubungi resepsionis untuk aktivasi.</p>
        </div>
        @endif

        <div class="mt-12 mb-8">
            <div class="flex justify-between items-end mb-6 border-b border-neutral-800 pb-2">
                <h3 class="font-header text-3xl font-bold text-white uppercase italic tracking-wide">
                    Tips & <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-orange-500">Artikel</span>
                </h3>
                <a href="{{ route('blogs.index') }}" class="text-sm font-bold text-gray-500 hover:text-white uppercase tracking-wider flex items-center gap-1 transition">
                    Lihat Semua <span class="text-primary">→</span>
                </a>
            </div>

            @if($blogs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($blogs as $blog)
                <a href="{{ route('blogs.show', $blog->slug) }}" class="group block bg-card rounded overflow-hidden shadow-lg hover:shadow-red-900/20 border border-neutral-800 hover:border-primary/50 transition duration-300">
                    <div class="relative h-48 overflow-hidden">
                        @if($blog->image)
                        <img src="{{ Storage::url($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-110 grayscale group-hover:grayscale-0 transition duration-500">
                        @else
                        <div class="w-full h-full bg-neutral-800 flex items-center justify-center">
                            <span class="font-header text-4xl text-neutral-700 font-bold">B1NG</span>
                        </div>
                        @endif
                        <div class="absolute top-0 right-0 bg-primary text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider">New</div>
                    </div>
                    <div class="p-5">
                        <span class="text-xs text-primary font-bold uppercase tracking-wider mb-2 block">
                            {{ $blog->created_at->format('d M Y') }}
                        </span>
                        <h4 class="font-header text-xl font-bold text-gray-100 mb-2 leading-tight group-hover:text-primary transition">
                            {{ $blog->title }}
                        </h4>
                        <p class="text-gray-400 text-sm line-clamp-2 font-light">
                            {{ Str::limit(strip_tags($blog->content), 80) }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-10 bg-neutral-900 border border-neutral-800 border-dashed rounded text-gray-600">
                <p>Belum ada artikel terbaru.</p>
            </div>
            @endif
        </div>
    </div>

    @if($member)
    <div id="renewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/90 backdrop-blur-sm transition-opacity" onclick="toggleModal('renewModal')"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative bg-card border border-neutral-700 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full rounded-lg">

                <div class="bg-neutral-800 px-6 py-4 border-b border-neutral-700 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-header font-bold text-white uppercase tracking-wide" id="modal-title">
                        Perpanjang Membership
                    </h3>
                    <button type="button" onclick="toggleModal('renewModal')" class="text-gray-400 hover:text-white focus:outline-none">
                        ✕
                    </button>
                </div>

                <div class="px-6 py-6">
                    <form action="/payment/upload" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="member_id" value="{{ $member->id }}">
                        <input type="hidden" name="gym_id" value="{{ $member->gymkos_id }}">

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs text-gray-500 uppercase font-bold mb-1">Nama</label>
                                <input type="text" value="{{ $member->name }}" class="w-full bg-neutral-900 border border-neutral-700 p-2 rounded text-gray-400 text-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 uppercase font-bold mb-1">Paket</label>
                                <input type="text" value="Bulanan (85k)" class="w-full bg-neutral-900 border border-neutral-700 p-2 rounded text-primary font-bold text-sm" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs text-gray-500 uppercase font-bold mb-1">Metode Pembayaran</label>
                            <select id="paymentMethodSelect" name="payment" onchange="togglePaymentInfo()" class="w-full bg-neutral-900 border border-neutral-600 text-white p-2 rounded focus:ring-primary focus:border-primary">
                                <option value="qris">QRIS (Scan Barcode)</option>
                                <option value="transfer">Transfer Bank (BCA)</option>
                            </select>
                        </div>

                        <div id="paymentInfoArea" class="mb-6">
                            <div id="info-qris" class="text-center p-4 border border-neutral-700 rounded bg-white">
                                <img src="/assets/img/pembayaran/qris-barcode.png" alt="QRIS Code" class="mx-auto w-40 h-auto">
                                <p class="text-black font-bold mt-2">Scan via GoPay/OVO/BCA</p>
                            </div>
                            <div id="info-transfer" class="hidden bg-neutral-800 border-l-4 border-blue-500 text-gray-300 p-4 text-sm">
                                <p class="font-bold text-white mb-2">Transfer Manual:</p>
                                <p>Bank BCA: <strong class="text-white text-lg select-all">0461701506</strong></p>
                                <p>A.n: <strong class="text-white">Sobiin</strong></p>
                                <p class="mt-2 text-xs text-gray-500">Total: Rp 85.000</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs text-gray-500 uppercase font-bold mb-1">Upload Bukti</label>
                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-neutral-800 file:text-white hover:file:bg-neutral-700 border border-neutral-700 rounded" required>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-neutral-700">
                            <button type="button" onclick="toggleModal('renewModal')" class="bg-transparent border border-gray-600 hover:border-gray-400 text-gray-400 hover:text-white font-bold py-2 px-4 rounded transition">
                                Batal
                            </button>
                            <button type="submit" class="bg-primary hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition shadow-lg shadow-red-900/30">
                                KIRIM BUKTI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) {
                document.body.style.overflow = 'hidden';
                document.getElementById('paymentMethodSelect').value = 'qris';
                togglePaymentInfo();
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        function togglePaymentInfo() {
            const select = document.getElementById('paymentMethodSelect');
            const qrisDiv = document.getElementById('info-qris');
            const transferDiv = document.getElementById('info-transfer');

            if (select.value === 'qris') {
                qrisDiv.classList.remove('hidden');
                transferDiv.classList.add('hidden');
            } else {
                qrisDiv.classList.add('hidden');
                transferDiv.classList.remove('hidden');
            }
        }
    </script>

    @include('components.footer-compact')

</body>

</html>