<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Kerusakan Alat - B11N</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Efek saat kartu dipilih */
        .equipment-card.selected {
            border-color: #dc2626;
            /* Merah */
            background-color: #fef2f2;
            /* Merah muda pudar */
            ring: 2px solid #dc2626;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl overflow-hidden">
        <div class="bg-red-600 p-6">
            <h1 class="text-white text-xl font-bold">🛠 Maintenance Report</h1>
            <p class="text-red-100 text-sm mt-1">Laporkan kerusakan alat Gym/Kost disini.</p>
        </div>

        <div class="p-6">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('maintenance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <input type="hidden" name="equipment_id" id="selectedEquipmentId" required>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Anda</label>
                    <input type="text" name="reporter_name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Contoh: Naufal">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Gym / Kost</label>
                    <select id="gymSelect" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                        <option value="" disabled selected>Pilih Lokasi...</option>
                        @foreach($gyms as $gym)
                        <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Alat yang Bermasalah</label>

                    <div id="loadingMessage" class="hidden text-center text-gray-500 py-4">
                        Memuat data alat...
                    </div>

                    <div id="equipmentGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-80 overflow-y-auto p-1">
                        <div class="col-span-full text-gray-400 text-sm italic text-center py-4 bg-gray-50 rounded border border-dashed">
                            Silahkan pilih lokasi gym terlebih dahulu.
                        </div>
                    </div>

                    <p id="equipmentError" class="text-red-500 text-xs mt-1 hidden">Mohon pilih salah satu alat diatas.</p>
                </div>

                <div class="space-y-4 pt-4 border-t">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tingkat Keparahan</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="border rounded-lg p-2 flex items-center gap-2 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="severity" value="low" class="text-red-600 focus:ring-red-500">
                                <span class="text-sm">Low (Ringan)</span>
                            </label>
                            <label class="border rounded-lg p-2 flex items-center gap-2 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="severity" value="medium" class="text-red-600 focus:ring-red-500">
                                <span class="text-sm">Medium</span>
                            </label>
                            <label class="border rounded-lg p-2 flex items-center gap-2 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="severity" value="high" class="text-red-600 focus:ring-red-500">
                                <span class="text-sm">High (Berat)</span>
                            </label>
                            <label class="border rounded-lg p-2 flex items-center gap-2 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="severity" value="critical" class="text-red-600 focus:ring-red-500">
                                <span class="text-sm">Critical (Bahaya)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Masalah</label>
                        <textarea name="description" rows="3" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Contoh: Kabel putus, baut kendor..."></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Foto Bukti (Opsional)</label>
                        <input type="file" name="evidence_photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    </div>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition duration-300 shadow-md">
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
            hiddenInput.value = ''; // Kosongkan pilihan lama

            // Fetch API
            fetch(`/api/equipments/${gymId}`)
                .then(response => response.json())
                .then(data => {
                    loadingMessage.classList.add('hidden');
                    equipmentGrid.classList.remove('hidden');

                    if (data.length === 0) {
                        equipmentGrid.innerHTML = '<div class="col-span-full text-center text-gray-500 py-4">Tidak ada alat terdaftar dilokasi ini.</div>';
                    } else {
                        data.forEach(item => {
                            // Render Kartu (HTML String)
                            const card = document.createElement('div');
                            card.className = 'equipment-card border rounded-lg p-2 cursor-pointer hover:shadow-md transition bg-white flex flex-col items-center text-center h-full';
                            card.onclick = () => selectEquipment(item.id, card);

                            card.innerHTML = `
                                <div class="w-full h-24 bg-gray-100 rounded-md mb-2 overflow-hidden flex items-center justify-center">
                                    <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover">
                                </div>
                                <h3 class="font-bold text-sm text-gray-800 leading-tight">${item.name}</h3>
                                <p class="text-xs text-gray-500 mt-1">${item.category}</p>
                                <p class="text-[10px] text-gray-400 mt-1 line-clamp-2">${item.description || 'Tidak ada deskripsi'}</p>
                            `;

                            equipmentGrid.appendChild(card);
                        });
                    }
                })
                .catch(error => {
                    loadingMessage.classList.add('hidden');
                    console.error('Error:', error);
                    equipmentGrid.innerHTML = '<div class="col-span-full text-red-500 text-center">Gagal memuat data.</div>';
                });
        });

        // Fungsi saat kartu diklik
        function selectEquipment(id, element) {
            // Update Hidden Input
            hiddenInput.value = id;

            // Update Visual (Hapus kelas 'selected' dari semua kartu, tambah ke yang diklik)
            document.querySelectorAll('.equipment-card').forEach(el => {
                el.classList.remove('selected', 'border-red-500', 'bg-red-50');
                el.classList.add('border-gray-200');
            });

            element.classList.remove('border-gray-200');
            element.classList.add('selected', 'border-red-500', 'bg-red-50');
        }
    </script>
</body>

</html>