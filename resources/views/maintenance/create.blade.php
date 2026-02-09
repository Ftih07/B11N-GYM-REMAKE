<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- CSRF Token (Wajib untuk form Laravel yang menggunakan AJAX/Post) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- 1. TITLE: Jelas & To-The-Point --}}
    <title>Lapor Kerusakan & Maintenance - B11N Gym % K1NG Gym</title>

    {{-- 2. META DESCRIPTION: Instruksi singkat --}}
    <meta name="description" content="Formulir resmi pelaporan kerusakan alat Gym dan fasilitas Kost (Istana Merdeka 03). Bantu kami menjaga kenyamanan dengan melaporkan kendala di sini.">

    {{-- 3. KEYWORDS: Fokus ke member internal --}}
    <meta name="keywords" content="lapor kerusakan gym purwokerto, maintenance b11n, komplain fasilitas kost, service alat gym purwokerto, form maintenance b1ng empire">

    <meta name="author" content="B1NG EMPIRE IT Support">

    {{-- Robots: Index biar member bisa cari di Google, tapi prioritas rendah --}}
    <meta name="robots" content="index, follow">

    {{-- 4. OPEN GRAPH (Penting untuk Share di Grup WhatsApp Member) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="🛠 Lapor Kerusakan Alat - B1NG EMPIRE">
    <meta property="og:description" content="Temukan alat rusak atau fasilitas bermasalah? Laporkan segera di sini agar tim maintenance kami bisa memperbaikinya.">
    <meta property="og:url" content="{{ url()->current() }}">
    {{-- Gunakan Logo B11N atau icon maintenance sebagai thumbnail --}}
    <meta property="og:image" content="{{ asset('assets/Logo/empire.png') }}">
    <meta property="og:site_name" content="Maintenance System">

    {{-- 5. THEME COLOR (Sesuai B11N Gym) --}}
    <meta name="theme-color" content="#dc030a">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/empire.png') }}">

    {{-- Google Fonts (sama dengan landing page) --}}
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Scripts & Styles --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#dc030a',
                        'primary-dark': 'rgb(135, 6, 12)',
                        secondary: '#0a0a0a',
                        textDark: '#0a0a0a',
                        textLight: '#737373',
                        extraLight: '#f5f5f5',
                    },
                    fontFamily: {
                        heading: ['Oswald', 'sans-serif'],
                        body: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Efek saat kartu dipilih */
        .equipment-card.selected {
            border-color: #dc030a !important;
            background-color: rgba(220, 3, 10, 0.08);
            box-shadow: 0 0 0 3px rgba(220, 3, 10, 0.3);
        }

        .equipment-card {
            transition: all 0.2s ease;
        }

        /* Smooth scroll & font */
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Custom radio style */
        .severity-option input[type="radio"] {
            accent-color: #dc030a;
        }

        /* Scrollbar styling */
        #equipmentGrid::-webkit-scrollbar {
            width: 6px;
        }
        #equipmentGrid::-webkit-scrollbar-thumb {
            background-color: #dc030a;
            border-radius: 3px;
        }
        #equipmentGrid::-webkit-scrollbar-track {
            background-color: #e5e5e5;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex items-center justify-center p-4">

    <div class="bg-white shadow-2xl w-full max-w-5xl overflow-hidden rounded-lg border border-gray-200">
        {{-- Header sesuai B11N Gym --}}
        <div class="bg-primary px-6 py-5">
            <h1 class="text-white text-2xl font-heading font-bold tracking-wider uppercase">Maintenance Report</h1>
            <p class="text-white/80 text-sm mt-1 font-body">Laporkan kerusakan alat Gym/Kost di sini</p>
        </div>

        <div class="p-6">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('maintenance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="equipment_id" id="selectedEquipmentId" required>

                {{-- Row 1: Nama & Lokasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Nama anda</label>
                        <input type="text" name="reporter_name" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                            placeholder="Contoh: Alex">
                    </div>
                    <div>
                        <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Lokasi Gym / Kost</label>
                        <select id="gymSelect"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body">
                            <option value="" disabled selected>Pilih Lokasi</option>
                            @foreach($gyms as $gym)
                            <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 2: Equipment Grid (Kiri) & Severity (Kanan) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Kolom Kiri: Pilih Alat --}}
                    <div>
                        <label class="block text-primary text-sm font-semibold mb-3 font-heading uppercase tracking-wide">Pilih alat yang bermasalah</label>

                        <div id="loadingMessage" class="hidden text-center text-textLight py-4 font-body">
                            Memuat data alat...
                        </div>

                        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 overflow-visible">
                            <div id="equipmentGrid" class="grid grid-cols-3 gap-4 max-h-[360px] overflow-y-auto overflow-x-visible p-1">
                                <div class="col-span-full text-textLight text-sm italic text-center py-6 bg-extraLight rounded-lg border-2 border-dashed border-gray-300 font-body">
                                    Silahkan pilih lokasi gym terlebih dahulu.
                                </div>
                            </div>
                        </div>

                        <p id="equipmentError" class="text-primary text-xs mt-2 hidden font-body">Mohon pilih salah satu alat diatas.</p>
                    </div>

                    {{-- Kolom Kanan: Tingkat Keparahan --}}
                    <div>
                        <label class="block text-primary text-sm font-semibold mb-3 font-heading uppercase tracking-wide">Tingkat Keparahan</label>
                        <div class="flex flex-col gap-2">
                            <label class="severity-option border-2 border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-extraLight hover:border-primary transition">
                                <input type="radio" name="severity" value="low" class="w-4 h-4">
                                <span class="text-sm text-textDark font-body">Low (Ringan)</span>
                            </label>
                            <label class="severity-option border-2 border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-extraLight hover:border-primary transition">
                                <input type="radio" name="severity" value="high" class="w-4 h-4">
                                <span class="text-sm text-textDark font-body">High (Berat)</span>
                            </label>
                            <label class="severity-option border-2 border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-extraLight hover:border-primary transition">
                                <input type="radio" name="severity" value="medium" class="w-4 h-4">
                                <span class="text-sm text-textDark font-body">Medium</span>
                            </label>
                            <label class="severity-option border-2 border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-extraLight hover:border-primary transition">
                                <input type="radio" name="severity" value="critical" class="w-4 h-4">
                                <span class="text-sm text-textDark font-body">Critical (Bahaya)</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-6">
                    <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Deskripsi</label>
                    <textarea name="description" rows="4" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                        placeholder="Contoh: Kabel putus, baut kendor ..."></textarea>
                </div>

                {{-- Foto Bukti --}}
                <div class="mb-8">
                    <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Foto Bukti (Optional)</label>
                    <input type="file" name="evidence_photo" accept="image/*"
                        class="text-sm text-textDark font-body file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-2 file:border-primary file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-white hover:file:text-primary file:transition file:cursor-pointer cursor-pointer">
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full bg-primary text-white font-heading font-bold py-4 rounded-lg hover:bg-white hover:text-primary border-2 border-primary transition duration-300 shadow-lg text-lg tracking-wider uppercase">
                    Kirim Laporan
                </button>
            </form>
        </div>
    </div>

    <script>
        const gymSelect = document.getElementById('gymSelect');
        const equipmentGrid = document.getElementById('equipmentGrid');
        const loadingMessage = document.getElementById('loadingMessage');
        const hiddenInput = document.getElementById('selectedEquipmentId');

        gymSelect.addEventListener('change', function() {
            const gymId = this.value;

            // Reset UI
            equipmentGrid.innerHTML = '';
            loadingMessage.classList.remove('hidden');
            equipmentGrid.classList.add('hidden');
            hiddenInput.value = '';

            // Fetch API
            fetch(`/api/equipments/${gymId}`)
                .then(response => response.json())
                .then(data => {
                    loadingMessage.classList.add('hidden');
                    equipmentGrid.classList.remove('hidden');

                    if (data.length === 0) {
                        equipmentGrid.innerHTML = '<div class="col-span-full text-center text-textLight py-4 font-body">Tidak ada alat terdaftar di lokasi ini.</div>';
                    } else {
                        data.forEach(item => {
                            const card = document.createElement('div');
                            card.className = 'equipment-card border-2 border-gray-200 rounded-xl p-3 cursor-pointer hover:shadow-md hover:border-primary bg-white flex flex-col items-center text-center';
                            card.onclick = () => selectEquipment(item.id, card);

                            card.innerHTML = `
                                <div class="w-full h-20 bg-extraLight rounded-lg mb-2 overflow-hidden flex items-center justify-center p-2">
                                    <img src="${item.image_url}" alt="${item.name}" class="max-w-full max-h-full object-contain">
                                </div>
                                <h3 class="font-semibold text-xs text-textDark leading-tight font-heading">${item.name}</h3>
                                <p class="text-[10px] text-textLight mt-1 line-clamp-2 leading-relaxed font-body">${item.description || ''}</p>
                            `;

                            equipmentGrid.appendChild(card);
                        });
                    }
                })
                .catch(error => {
                    loadingMessage.classList.add('hidden');
                    equipmentGrid.classList.remove('hidden');
                    console.error('Error:', error);
                    equipmentGrid.innerHTML = '<div class="col-span-full text-red-500 text-center font-body">Gagal memuat data.</div>';
                });
        });

        // Fungsi saat kartu diklik
        function selectEquipment(id, element) {
            hiddenInput.value = id;

            document.querySelectorAll('.equipment-card').forEach(el => {
                el.classList.remove('selected');
                el.classList.add('border-gray-200');
            });

            element.classList.remove('border-gray-200');
            element.classList.add('selected');
        }
    </script>
</body>

</html>