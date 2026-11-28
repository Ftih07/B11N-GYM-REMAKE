{{-- resources/views/legal.blade.php --}}
@extends('layouts.main') {{-- Sesuaikan dengan layout utamamu --}}

@section('title', 'Legal & Trust - B1NG Empire')

@section('content')
<x-cdn name="alpine" defer />

@include('components.global-loader')

@include('components.navbar-cta')

<div class="min-h-screen bg-neutral-950 text-neutral-300 font-poppins py-12 px-4 sm:px-6 lg:px-8 mt-20">
    <div class="max-w-4xl mx-auto" x-data="{ activeTab: 'privacy' }">

        {{-- Header Section --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold uppercase font-oswald text-white tracking-wider mb-2">
                Legal & <span class="text-orange-500">Trust</span>
            </h1>
            <p class="text-neutral-500">
                Transparansi untuk member B1NG Empire (B11N & K1NG Gym).
            </p>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex justify-center mb-10 space-x-4">
            <button
                @click="activeTab = 'privacy'"
                :class="activeTab === 'privacy' ? 'border-orange-500 text-white' : 'border-transparent text-neutral-600 hover:text-neutral-400'"
                class="px-6 py-2 rounded-sm font-oswald uppercase tracking-wide border-b-2 transition-all duration-300 focus:outline-none">
                Privacy Policy
            </button>
            <button
                @click="activeTab = 'terms'"
                :class="activeTab === 'terms' ? 'border-orange-500 text-white' : 'border-transparent text-neutral-600 hover:text-neutral-400'"
                class="px-6 py-2 rounded-sm font-oswald uppercase tracking-wide border-b-2 transition-all duration-300 focus:outline-none">
                Terms of Service
            </button>
        </div>

        {{-- Content Container --}}
        <div class="bg-neutral-900/50 border border-neutral-800 p-8 rounded-sm shadow-2xl backdrop-blur-sm relative min-h-[400px]">

            {{-- Privacy Policy Content --}}
            <div x-show="activeTab === 'privacy'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="space-y-6">

                <div class="border-b border-neutral-800 pb-4 last:border-0 last:pb-0">
                    <h3 class="text-xl text-white font-oswald uppercase mb-2 tracking-wide">1. Data Collection</h3>
                    <div class="text-sm leading-relaxed text-neutral-400">
                        <p>Kami mengumpulkan informasi untuk keperluan manajemen gym dan fitur <span class="text-white font-medium">Auto-Attendance</span>, meliputi:</p>
                        <ul class="list-disc pl-5 mt-2 space-y-1">
                            <li>Identitas Personal (Nama, Email, No. Telepon, Alamat).</li>
                            <li>Data Keanggotaan (Jenis Paket, Tanggal Mulai/Berakhir, riwayat perpanjangan).</li>
                            <li>Log Aktivitas (Waktu check-in di B11N & K1NG Gym).</li>
                            <li><span class="text-white font-medium">Foto wajah dan data pengenal wajah</span> (deretan angka hasil pemindaian wajah) bila kamu didaftarkan untuk absensi scan wajah.</li>
                            <li>Data progres tubuh (berat, lingkar tubuh, foto progres) yang kamu isi sendiri di dashboard member.</li>
                            <li>Data kunjungan situs: alamat IP yang disimpan dalam bentuk acak (hash), jenis browser, dan tanggal kunjungan, serta cookie sesi untuk login dan absen mandiri.</li>
                        </ul>
                    </div>
                </div>
                <div class="border-b border-neutral-800 pb-4 last:border-0 last:pb-0">
                    <h3 class="text-xl text-white font-oswald uppercase mb-2 tracking-wide">2. How We Use Your Data</h3>
                    <div class="text-sm leading-relaxed text-neutral-400">
                        <p>Data kamu digunakan murni untuk operasional sistem <strong>B1NG Empire</strong>:</p>
                        <ul class="list-disc pl-5 mt-2 space-y-1">
                            <li>Memvalidasi akses dan mencatat kehadiran member.</li>
                            <li>Mencocokkan wajah saat absensi scan wajah di perangkat admin gym. Data wajah <span class="text-white font-medium">tidak</span> dipakai untuk menebak usia, jenis kelamin, atau emosi.</li>
                            <li>Memantau progress latihan dan riwayat kehadiran member.</li>
                            <li>Menghitung jumlah pengunjung situs secara agregat.</li>
                        </ul>
                    </div>
                </div>
                <div class="border-b border-neutral-800 pb-4 last:border-0 last:pb-0">
                    <h3 class="text-xl text-white font-oswald uppercase mb-2 tracking-wide">3. Data Protection &amp; Retention</h3>
                    <div class="text-sm leading-relaxed text-neutral-400">
                        <p>Foto wajah, foto progres, dan bukti pembayaran disimpan di penyimpanan privat yang hanya dapat dibuka oleh admin B1NG Empire (foto progres juga oleh member pemiliknya). Data kunjungan situs dihapus otomatis setelah 13 bulan. Data pribadimu tidak dijual atau dibagikan ke pihak ketiga di luar ekosistem B1NG Empire tanpa persetujuanmu.</p>
                    </div>
                </div>
                <div class="border-b border-neutral-800 pb-4 last:border-0 last:pb-0">
                    <h3 class="text-xl text-white font-oswald uppercase mb-2 tracking-wide">4. Your Rights</h3>
                    <div class="text-sm leading-relaxed text-neutral-400">
                        <p>Kamu dapat meminta untuk melihat, memperbaiki, atau menghapus data pribadimu — termasuk foto dan data wajah (kamu tetap bisa absen secara manual) — dengan menghubungi admin melalui WhatsApp <a href="{{ config('b1ng.contact.whatsapp_url') }}" target="_blank" rel="noopener noreferrer" class="text-white underline">{{ config('b1ng.contact.phone') }}</a>.</p>
                    </div>
                </div>
            </div>

            {{-- Terms of Service Content --}}
            <div x-show="activeTab === 'terms'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                style="display: none;" {{-- Mencegah flicker saat load --}}
                class="space-y-6">

                <div class="border-b border-neutral-800 pb-4 last:border-0 last:pb-0">
                    <h3 class="text-xl text-white font-oswald uppercase mb-2 tracking-wide">1. Membership Agreement</h3>
                    <div class="text-sm leading-relaxed text-neutral-400">
                        <p>Dengan mendaftar di B11N atau K1NG Gym, member menyetujui untuk mematuhi aturan fasilitas. Keanggotaan bersifat pribadi dan tidak dapat dipindahtangankan (non-transferable).</p>
                    </div>
                </div>

                <div class="border-b border-neutral-800 pb-4 last:border-0 last:pb-0">
                    <h3 class="text-xl text-white font-oswald uppercase mb-2 tracking-wide">2. Facility Usage & Safety</h3>
                    <div class="text-sm leading-relaxed text-neutral-400">
                        <p>Member wajib menjaga peralatan gym. Segala bentuk kerusakan akibat kelalaian member dapat dikenakan sanksi atau penggantian. Gunakan peralatan sesuai instruksi untuk menghindari cedera.</p>
                    </div>
                </div>

                <div class="border-b border-neutral-800 pb-4 last:border-0 last:pb-0">
                    <h3 class="text-xl text-white font-oswald uppercase mb-2 tracking-wide">3. Account Security</h3>
                    <div class="text-sm leading-relaxed text-neutral-400">
                        <p>Akun member B1NG Empire kamu adalah kunci akses fasilitas. Dilarang keras meminjamkan akses akun (QR Code/ID) kepada orang lain untuk masuk ke area gym. Pelanggaran ini akan berakibat pada pemblokiran akun permanen (Banned).</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer Note --}}
        <div class="mt-8 text-center text-xs text-neutral-600">
            Last updated: {{ date('Y') }} &copy; B1NG Empire Technology Dept.
        </div>
    </div>
</div>

@include('components.footer-compact')

@endsection