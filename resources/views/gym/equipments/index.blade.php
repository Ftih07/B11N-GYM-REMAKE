@extends('layouts.main')

@section('title')
{{ request('category') ? 'Alat Gym Kategori ' . request('category') : 'Daftar Peralatan Gym Lengkap' }} - B1NG Empire
@endsection

@section('meta_description')
Lihat koleksi lengkap peralatan fitness standar internasional di B1NG Empire. Tersedia alat Cardio, Strength, dan Machine modern untuk menunjang latihanmu.
@endsection

@section('meta_keywords', 'alat gym purwokerto, treadmill purwokerto, alat fitness lengkap, smith machine, lat pulldown, B1NG Empire equipment')

{{-- Schema Markup untuk Halaman Koleksi --}}
@push('json-ld')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "Koleksi Peralatan B1NG Empire",
        "description": "Daftar peralatan gym lengkap mulai dari Cardio, Strength, hingga Machine.",
        "url": "{{ url()->current() }}"
    }
</script>
@endpush

@section('content')
<div class="relative bg-black py-20">
    <div class="absolute inset-0 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop" class="w-full h-full object-cover opacity-30">
    </div>
    <div class="relative container mx-auto px-4 text-center text-white">
        <h1 class="text-5xl font-bold uppercase tracking-wider">Gym Equipments</h1>
        <p class="text-xl text-gray-300 mt-4 max-w-2xl mx-auto">Koleksi lengkap peralatan standar internasional kami.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">

    {{-- WRAPPER FILTER --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-12">

        {{-- 1. FILTER CATEGORY (Kiri) --}}
        <div class="flex flex-wrap justify-center gap-2">
            {{-- Helper function: fullUrlWithQuery(['category' => ...]) menjaga filter gym tetap ada --}}

            {{-- Tombol ALL --}}
            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
                class="px-5 py-2 rounded-full font-bold text-sm shadow-sm transition border
               {{ !request('category') ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                All
            </a>

            {{-- Tombol Categories --}}
            @foreach(['Cardio', 'Strength', 'Machine'] as $cat)
            <a href="{{ request()->fullUrlWithQuery(['category' => $cat]) }}"
                class="px-5 py-2 rounded-full font-bold text-sm shadow-sm transition border
               {{ request('category') == $cat ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>

        {{-- 2. FILTER GYM LOCATION (Kanan) --}}
        <div class="relative">
            <select onchange="window.location.href = this.value"
                class="appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-full leading-tight focus:outline-none focus:bg-white focus:border-red-500 shadow-sm cursor-pointer font-medium w-full md:w-auto">

                {{-- Opsi Semua Lokasi --}}
                <option value="{{ request()->fullUrlWithQuery(['gym' => null]) }}"
                    {{ !request('gym') ? 'selected' : '' }}>
                    📍 Semua Lokasi
                </option>

                {{-- Loop Data Gyms --}}
                @foreach($gyms as $gym)
                <option value="{{ request()->fullUrlWithQuery(['gym' => $gym->id]) }}"
                    {{ request('gym') == $gym->id ? 'selected' : '' }}>
                    📍 {{ $gym->name }}
                </option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <i class="ri-arrow-down-s-line"></i>
            </div>
        </div>
    </div>

    {{-- GRID EQUIPMENTS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($equipments as $item)
        @php
        $thumbnail = $item->gallery->first()
        ? asset('storage/' . $item->gallery->first()->file_path)
        : 'https://placehold.co/600x400?text=No+Image';
        @endphp
        <div class="group bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-xl transition duration-300 flex flex-col h-full">
            <div class="relative h-60 overflow-hidden">
                <img src="{{ $thumbnail }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">

                {{-- Badge Lokasi di atas Gambar (Opsional, agar terlihat jelas) --}}
                <div class="absolute top-3 right-3 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded backdrop-blur-sm flex items-center gap-1">
                    <i class="ri-map-pin-line text-red-500"></i> {{ $item->gymkos->name ?? 'Unknown' }}
                </div>

                <a href="{{ route('gym.equipments.show', $item->slug) }}" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300">
                    <i class="ri-play-circle-fill text-6xl text-white drop-shadow-lg"></i>
                </a>
            </div>

            <div class="p-6 flex-grow flex flex-col">
                <div class="mb-auto">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-semibold uppercase tracking-wide">
                            {{ $item->category }}
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-red-600 transition">{{ $item->name }}</h3>
                    <p class="text-gray-500 text-sm line-clamp-2 mb-3">{{ $item->description }}</p>
                </div>

                <div class="mt-4 pt-4 border-t flex justify-between items-center">
                    <a href="{{ route('gym.equipments.show', $item->slug) }}" class="text-red-600 font-bold text-sm hover:text-red-800 flex items-center gap-1">
                        LEARN MORE <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                <i class="ri-search-line text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-600">Alat tidak ditemukan</h3>
            <p class="text-gray-500">Coba atur ulang filter pencarian Anda.</p>
            <a href="{{ route('gym.equipments.index') }}" class="inline-block mt-4 text-red-600 font-bold hover:underline">Reset Filter</a>
        </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $equipments->links() }}
    </div>
</div>
@endsection