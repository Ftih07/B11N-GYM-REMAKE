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
    <meta property="og:image" content="{{ asset('assets/default-image-og.webp') }}">
    <meta property="og:site_name" content="Maintenance System">

    {{-- 5. THEME COLOR (Sesuai B11N Gym) --}}
    <meta name="theme-color" content="#dc030a">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/empire.png') }}">

    {{-- Google Fonts (sama dengan landing page) --}}
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Scripts & Styles --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    {{-- LINK SWEETALERT DIHAPUS DARI SINI --}}

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

<body class="bg-white min-h-screen flex flex-col">

    @include('components.global-loader')

    <div class="w-full z-50">
        @include('components.navbar-cta')
    </div>

    <main class="flex-grow flex items-center justify-center p-4 py-8 mt-20">

        <div class="bg-white shadow-2xl w-full max-w-5xl overflow-hidden rounded-lg border border-gray-200">
            {{-- Header (Desain Asli) --}}
            <div class="bg-primary px-6 py-5">
                <h1 class="text-white text-2xl font-heading font-bold tracking-wider uppercase">Maintenance Report</h1>
                <p class="text-white/80 text-sm mt-1 font-body">Laporkan kerusakan alat Gym/Kost di sini</p>
            </div>

            <div class="p-6">
                {{-- KOTAK NOTIFIKASI HIJAU LAMA DIHAPUS DARI SINI --}}

                <form id="maintenanceForm" action="{{ route('maintenance.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="equipment_id" id="selectedEquipmentId" required>

                    {{-- Row 1: Nama & Lokasi (Desain Asli) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Nama anda <span class="text-red-500">*</span></label>
                            <input type="text" name="reporter_name" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                                placeholder="Contoh: Alex">
                        </div>
                        <div>
                            <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Lokasi Gym / Kost <span class="text-red-500">*</span></label>
                            <select id="gymSelect" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body">
                                <option value="" disabled selected>Pilih Lokasi</option>
                                @foreach($gyms as $gym)
                                <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: Equipment Grid & Severity --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                        {{-- Kolom Kiri: Pilih Alat --}}
                        <div>
                            <label class="block text-primary text-sm font-semibold mb-3 font-heading uppercase tracking-wide">Pilih alat yang bermasalah <span class="text-red-500">*</span></label>

                            <div id="loadingMessage" class="hidden text-center text-textLight py-4 font-body">
                                Memuat data alat...
                            </div>

                            <div class="border border-gray-200 rounded-xl p-2 bg-gray-50 overflow-visible">

                                <div id="equipmentGrid" class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-[450px] overflow-y-auto overflow-x-visible p-1">
                                    <div class="col-span-full text-textLight text-sm italic text-center py-6 bg-white rounded-lg border border-dashed border-gray-300 font-body shadow-sm">
                                        Silahkan pilih lokasi gym terlebih dahulu.
                                    </div>
                                </div>
                            </div>
                            <p id="equipmentError" class="text-primary text-xs mt-2 hidden font-body">Mohon pilih salah satu alat diatas.</p>
                        </div>

                        {{-- Kolom Kanan: Tingkat Keparahan (Desain Asli) --}}
                        <div>
                            <label class="block text-primary text-sm font-semibold mb-3 font-heading uppercase tracking-wide">Tingkat Keparahan <span class="text-red-500">*</span></label>
                            <div class="flex flex-col gap-2">
                                <label class="severity-option border-2 border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-extraLight hover:border-primary transition">
                                    <input type="radio" name="severity" value="low" required class="w-4 h-4">
                                    <span class="text-sm text-textDark font-body">Low (Ringan)</span>
                                </label>
                                <label class="severity-option border-2 border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-extraLight hover:border-primary transition">
                                    <input type="radio" name="severity" value="high" required class="w-4 h-4">
                                    <span class="text-sm text-textDark font-body">High (Berat)</span>
                                </label>
                                <label class="severity-option border-2 border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-extraLight hover:border-primary transition">
                                    <input type="radio" name="severity" value="medium" required class="w-4 h-4">
                                    <span class="text-sm text-textDark font-body">Medium</span>
                                </label>
                                <label class="severity-option border-2 border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-extraLight hover:border-primary transition">
                                    <input type="radio" name="severity" value="critical" required class="w-4 h-4">
                                    <span class="text-sm text-textDark font-body">Critical (Bahaya)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi (Desain Asli) --}}
                    <div class="mb-6">
                        <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                            placeholder="Contoh: Kabel putus, baut kendor ..."></textarea>
                    </div>

                    {{-- Foto Bukti (Desain Asli) --}}
                    <div class="mb-8">
                        <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Foto Bukti (Optional)</label>
                        <input type="file" name="evidence_photo" accept="image/*"
                            class="text-sm text-textDark font-body file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-2 file:border-primary file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-white hover:file:text-primary file:transition file:cursor-pointer cursor-pointer">
                    </div>

                    {{-- Submit Button (Desain Asli) --}}
                    <button type="submit"
                        class="w-full bg-primary text-white font-heading font-bold py-4 rounded-lg hover:bg-white hover:text-primary border-2 border-primary transition duration-300 shadow-lg text-lg tracking-wider uppercase">
                        Kirim Laporan
                    </button>
                </form>
            </div>
        </div>
    </main>

    <div class="w-full mt-auto">
        @include('components.footer-bing-empire')
    </div>

    <script>
        // 1. FUNGSI UNTUK MEMUNCULKAN TOAST NOTIFICATION MODERN (Durasi 10 Detik)
        function showModernToast(message, type = 'success') {
            const existingToast = document.getElementById('modern-toast');
            if (existingToast) existingToast.remove();

            const isSuccess = type === 'success';
            const borderColor = isSuccess ? '#16a34a' : '#dc030a';
            const iconColor = isSuccess ? '#22c55e' : '#dc030a';
            const iconBg = isSuccess ? 'rgba(34, 197, 94, 0.1)' : 'rgba(220, 3, 10, 0.1)';
            const progressBg = isSuccess ? '#16a34a' : '#dc030a';

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

        // --- CHECK SESSION & TRIGGER TOAST KETIKA HALAMAN DIMUAT ---
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            showModernToast('{{ session("success") }}', 'success');
        });
        @endif

        @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            showModernToast('{{ session("error") }}', 'error');
        });
        @endif


        // 2. LOGIKA PILIH ALAT DAN AJAX FETCH
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

            fetch(`/api/equipments/${gymId}`)
                .then(response => response.json())
                .then(data => {
                    loadingMessage.classList.add('hidden');
                    equipmentGrid.classList.remove('hidden');
                    if (data.length === 0) {
                        equipmentGrid.innerHTML = '<div class="col-span-full text-center text-textLight py-4 font-body">Tidak ada alat terdaftar.</div>';
                    } else {
                        data.forEach(item => {
                            const card = document.createElement('div');
                            card.className = 'equipment-card border-2 border-gray-200 rounded-xl p-2 cursor-pointer hover:shadow-lg hover:border-primary bg-white flex flex-col items-center text-center transition-all duration-200 relative group';
                            card.onclick = () => selectEquipment(item.id, card);

                            card.innerHTML = `
                                <div class="w-full h-32 bg-gray-50 rounded-lg mb-3 overflow-hidden flex items-center justify-center p-2 group-hover:bg-white transition-colors">
                                    <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-contain drop-shadow-sm">
                                </div>
                                
                                <div class="w-full px-1">
                                    <h3 class="font-bold text-sm text-textDark leading-tight font-heading uppercase tracking-wide">${item.name}</h3>
                                    <p class="text-[11px] text-textLight mt-1 leading-tight font-body">${item.description || 'Alat Gym'}</p>
                                </div>
                                
                                <div class="absolute top-2 right-2 text-primary opacity-0 check-icon transition-opacity">
                                    <svg class="w-6 h-6 bg-white rounded-full p-1 shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </div>
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

        function selectEquipment(id, element) {
            hiddenInput.value = id;
            document.querySelectorAll('.equipment-card').forEach(el => {
                el.classList.remove('selected');
                el.classList.add('border-gray-200');
            });
            element.classList.remove('border-gray-200');
            element.classList.add('selected');
        }

        // 3. VALIDASI FORM SUBMIT (Cegah form terkirim jika alat belum dipilih)
        document.getElementById('maintenanceForm').addEventListener('submit', function(e) {
            if (!hiddenInput.value) {
                e.preventDefault(); // Hentikan form agar tidak me-reload page

                // Munculkan Modern Toast Error
                showModernToast('Mohon pilih salah satu alat yang bermasalah pada grid yang tersedia.', 'error');

                // Animasi kecil di teks "Mohon pilih salah satu alat diatas"
                const errorText = document.getElementById('equipmentError');
                errorText.classList.remove('hidden');
                setTimeout(() => {
                    errorText.classList.add('hidden');
                }, 4000);
            }
        });
    </script>
</body>

</html>