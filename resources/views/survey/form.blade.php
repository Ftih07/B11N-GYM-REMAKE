<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey B11N Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 py-10 px-4">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Survey Kepuasan Pelanggan</h2>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('survey.store') }}" method="POST" x-data="{ isMember: false }">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full border p-2 rounded">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">No. WhatsApp</label>
                    <input type="text" name="phone" required class="w-full border p-2 rounded">
                </div>
            </div>

            <div class="mb-6 bg-blue-50 p-4 rounded border border-blue-200">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_membership" value="1" x-model="isMember" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700 font-semibold">Saya adalah Member Aktif</span>
                </label>

                <div x-show="isMember" x-transition class="mt-4 pt-4 border-t border-blue-200">
                    <div class="mb-3">
                        <label class="block text-gray-700 text-sm mb-1">Sudah berapa lama jadi member?</label>
                        <input type="text" name="member_duration" placeholder="Contoh: 3 Bulan" class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm mb-1">Kemungkinan perpanjang (1-5)?</label>
                        <select name="renewal_chance" class="w-full border p-2 rounded">
                            <option value="">Pilih...</option>
                            <option value="1">1 - Sangat Kecil</option>
                            <option value="3">3 - Ragu-ragu</option>
                            <option value="5">5 - Pasti Perpanjang</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Tujuan Utama Fitness</label>
                <input type="text" name="fitness_goal" required class="w-full border p-2 rounded" placeholder="Tulis tujuan Anda (misal: Menurunkan berat badan, dll)">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Rating Alat (1-5)</label>
                    <select name="rating_equipment" required class="w-full border p-2 rounded">
                        <option value="">Pilih Rating...</option>
                        <option value="1">1 - Buruk</option>
                        <option value="2">2 - Kurang</option>
                        <option value="3">3 - Cukup</option>
                        <option value="4">4 - Bagus</option>
                        <option value="5">5 - Sangat Bagus</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kebersihan (1-5)</label>
                    <select name="rating_cleanliness" required class="w-full border p-2 rounded">
                        <option value="">Pilih Rating...</option>
                        <option value="1">1 - Buruk</option>
                        <option value="2">2 - Kurang</option>
                        <option value="3">3 - Cukup</option>
                        <option value="4">4 - Bagus</option>
                        <option value="5">5 - Sangat Bagus</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Seberapa mungkin merekomendasikan kami? (NPS 1-10)</label>
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Gak banget</span>
                    <span>Pasti Rekomen</span>
                </div>
                <input type="range" name="nps_score" min="1" max="10" value="5" oninput="this.nextElementSibling.value = this.value" class="w-full">
                <output class="text-center block font-bold text-lg text-blue-600">5</output>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Kritik & Saran</label>
                <textarea name="feedback" rows="3" class="w-full border p-2 rounded"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Tertarik Promo?</label>
                <select name="promo_interest" required class="w-full border p-2 rounded bg-yellow-50">
                    <option value="none">Belum tertarik saat ini</option>
                    <option value="paket_a">Paket A (Bayar 6 Free 2)</option>
                    <option value="paket_b">Paket B (Bayar 9 Free 3)</option>
                    <option value="paket_c">Paket C (Bayar 12 Free 4 - Best Value!)</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700 transition">
                Kirim Survey
            </button>
        </form>
    </div>
</body>

</html>