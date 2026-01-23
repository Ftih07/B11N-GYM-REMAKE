<style>
    /* Styling for the header logo */
    .header-logo a {
        text-decoration: none;
        color: #fff;
        font-weight: 900;
        font-size: 23px;
        display: flex;
    }

    /* Orange-colored text for emphasis */
    .header-logo a span {
        color: #ff8243;
    }

    /* Class for additional orange-colored text */
    .rasa-text {
        color: #ff8243;
        font-weight: bold;
    }

    .logo-img {
        height: 30px;
        /* Sesuaikan ukuran logo */
        vertical-align: middle;
        /* Biar sejajar dengan teks */
        margin-right: 8px;
        /* Beri jarak antara logo dan teks */
    }
</style>

<!-- Header Logo Section -->
<div class="flex items-center gap-x-2">
    {{-- LOGO GAMBAR --}}
    {{-- Gunakan asset() biar muncul di semua halaman --}}
    <img
        src="{{ asset('assets/Logo/empire.png') }}"
        alt="Logo"
        class="h-10" />

    {{-- TEKS --}}
    {{-- text-gray-950 (hitam utk light mode) --}}
    {{-- dark:text-white (putih utk dark mode) --}}
    <div class="font-bold text-xl text-gray-950 dark:text-white">
        B1NG EMPIRE
    </div>
</div>