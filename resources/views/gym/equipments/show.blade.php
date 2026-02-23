@extends('layouts.main')

@section('content')

{{--
    =============================================
    DYNAMIC BRANDING LOGIC
    =============================================
--}}
@php
$gymName = $equipment->gymkos->name ?? '';
$isKing = \Illuminate\Support\Str::contains(strtoupper($gymName), 'K1NG GYM');

// =====================
// BRAND ROUTE TARGET
// =====================
$gymRoute = $isKing ? route('gym.king') : route('gym.biin');
$membershipUrl = $gymRoute . '#membership';

// Warna Utama
$brandColor = $isKing ? 'text-yellow-600' : 'text-red-600';
$brandBg = $isKing ? 'bg-yellow-500' : 'bg-red-600'; // Untuk tombol/badge solid
$brandBorder = $isKing ? 'border-yellow-500' : 'border-red-600';

// Warna Hover
$hoverText = $isKing ? 'hover:text-yellow-600' : 'hover:text-red-600';
$groupHoverText = $isKing ? 'group-hover:text-yellow-600' : 'group-hover:text-red-600';

// ==========================================
// LOGIKA PILIH FOTO DENGAN ORDER_INDEX = 0
// ==========================================

// OPSI A (STRICT): Hanya cari yang angkanya 0 persis.
// $firstPhoto = $equipment->gallery->where('order_index', 0)->first();

// OPSI B (REKOMENDASI): Urutkan dari angka terkecil (0, 1, 2...), lalu ambil yang paling atas.
$firstPhoto = $equipment->gallery->sortBy('order_index')->first();

// Generate URL Gambar
$shareImage = $firstPhoto
? asset('storage/' . $firstPhoto->file_path)
: asset('assets/Logo/biin.png'); // Fallback ke logo jika tidak ada foto sama sekali
@endphp

@section('title')
{{ $equipment->name }} - Panduan Latihan | {{ $isKing ? 'K1NG GYM' : 'B11N GYM' }}
@endsection

@section('meta_description')
Panduan lengkap menggunakan {{ $equipment->name }}. {{ \Illuminate\Support\Str::limit($equipment->description, 120) }}.
@endsection

@push('json-ld')
{{-- Schema Markup tetap sama --}}
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": ["Product", "VideoObject"],
        "name": "{{ $equipment->name }}",
        "description": "{{ $equipment->description }}",
        "thumbnailUrl": ["{{ $equipment->gallery->first() ? asset('storage/' . $equipment->gallery->first()->file_path) : asset('assets/Logo/biin.png') }}"],
        "uploadDate": "{{ $equipment->created_at->toIso8601String() }}",
        "contentUrl": "{{ $equipment->video_url ?? url()->current() }}",
        "embedUrl": "{{ $equipment->video_url }}"
    }
</script>
@endpush

@include('components.global-loader')

@include('components.navbar-cta')

{{-- BACKGROUND UTAMA: ABU-ABU TERANG (Clean Look) --}}
<div class="bg-gray-50 min-h-screen font-sans pb-20 pt-20">

    {{-- HERO HEADER: Tetap Gelap agar kontras dengan logo & judul --}}
    <div class="bg-black text-white py-16 relative overflow-hidden">
        {{-- Aksen Background Samar --}}
        <div class="absolute top-0 right-0 w-64 h-64 {{ $brandBg }} opacity-10 rounded-full blur-3xl -translate-y-10 translate-x-10"></div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-3">
                {{ $equipment->name }}
            </h1>

            {{-- Breadcrumb --}}
            <div class="flex justify-center items-center gap-2 text-sm text-gray-400 uppercase tracking-widest font-bold">
                <a href="/" class="hover:text-white transition">Home</a>
                <span>/</span>
                <a href="{{ route('gym.equipments.index') }}" class="hover:text-white transition">Equipments</a>
                <span>/</span>
                <span class="{{ $brandColor }}">{{ $equipment->name }}</span>
            </div>
        </div>
    </div>

    {{-- CONTENT CONTAINER --}}
    <div class="container mx-auto px-4 -mt-10 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT COLUMN: VIDEO & DESCRIPTION --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- VIDEO PLAYER: Frame Hitam agar fokus, tapi shadow lembut --}}
                <div class="bg-black rounded-xl overflow-hidden shadow-2xl border-4 border-white">
                    <div class="aspect-video relative">
                        @if($equipment->video_url)
                        <iframe class="w-full h-full"
                            src="{{ $equipment->video_url }}"
                            title="Tutorial Video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                        @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-500 bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="uppercase font-bold tracking-wider text-sm">Video Belum Tersedia</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- DESCRIPTION CARD: Putih Bersih (Sesuai screenshot 'Tentang Kami') --}}
                <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="h-6 w-1 {{ $brandBg }} rounded-full"></div>
                        <h3 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Fungsi & Cara Pakai</h3>
                    </div>

                    <p class="text-gray-600 leading-relaxed text-lg">
                        {{ $equipment->description }}
                    </p>

                    {{-- METADATA GRID: Style 'Fasilitas' (Icon Bulat / Box Rapi) --}}
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">

                        {{-- Meta 1 --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-center hover:shadow-md transition">
                            <div class="inline-flex items-center justify-center w-10 h-10 mb-2 rounded-full bg-white shadow-sm text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest mb-1">Kategori</span>
                            <span class="font-bold text-gray-800">{{ $equipment->category }}</span>
                        </div>

                        {{-- Meta 2 --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-center hover:shadow-md transition">
                            <div class="inline-flex items-center justify-center w-10 h-10 mb-2 rounded-full bg-white shadow-sm text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest mb-1">Status</span>
                            <span class="font-bold uppercase {{ $equipment->status == 'active' ? 'text-green-600' : 'text-red-500' }}">
                                {{ ucfirst($equipment->status) }}
                            </span>
                        </div>

                        {{-- Meta 3 --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-center hover:shadow-md transition">
                            <div class="inline-flex items-center justify-center w-10 h-10 mb-2 rounded-full bg-white shadow-sm text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest mb-1">Lokasi</span>
                            <span class="font-bold text-gray-800">{{ $equipment->gymkos->name ?? '-' }}</span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: SIDEBAR --}}
            <div class="space-y-8">

                {{-- GALLERY WIDGET --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 uppercase tracking-tight">
                        Galeri Foto
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        @forelse($equipment->gallery as $photo)
                        <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 group cursor-pointer relative" onclick="window.open('{{ asset('storage/'.$photo->file_path) }}', '_blank')">
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition z-10"></div>
                            <img src="{{ asset('storage/' . $photo->file_path) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        @empty
                        <div class="col-span-2 py-6 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-xs text-gray-400 italic">Tidak ada foto tambahan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- RELATED EQUIPMENT WIDGET --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 uppercase tracking-tight">
                        Alat Terkait
                    </h3>
                    <div class="space-y-4">
                        @foreach($relatedEquipments as $related)
                        <a href="{{ route('gym.equipments.show', $related->slug) }}" class="flex items-center gap-4 group p-2 hover:bg-gray-50 transition rounded-lg border border-transparent hover:border-gray-100">
                            {{-- Thumbnail --}}
                            <div class="w-16 h-16 bg-gray-200 rounded-md flex-shrink-0 overflow-hidden shadow-sm">
                                @php $img = $related->gallery->first() ? asset('storage/'.$related->gallery->first()->file_path) : 'https://placehold.co/100?text=No+Img'; @endphp
                                <img src="{{ $img }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-300">
                            </div>

                            {{-- Info --}}
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm {{ $groupHoverText }} transition uppercase tracking-wide">
                                    {{ $related->name }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $related->category }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- CTA: Gabung Member --}}
                <div class="{{ $brandBg }} rounded-xl p-6 text-center shadow-lg text-white">
                    <h4 class="font-black uppercase text-xl mb-1">Mulai Sekarang</h4>
                    <p class="text-white/80 text-sm mb-4">Dapatkan akses ke alat ini dan instruktur profesional.</p>
                    <a href="{{ $membershipUrl }}" class="scroll-smooth inline-block bg-white {{ $brandColor }} px-6 py-2 rounded-full text-sm font-bold uppercase tracking-wider hover:bg-gray-100 transition shadow-md">
                        Daftar Member
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@include('components.footer-bing-empire')

@endsection