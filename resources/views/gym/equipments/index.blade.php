@extends('layouts.main')

@section('title')
{{ request('category') ? 'Kategori: ' . request('category') : 'Daftar Peralatan Gym Lengkap' }} - B1NG Empire
@endsection

@section('meta_description')
Katalog lengkap peralatan fitness B1NG Empire. Temukan alat Cardio, Strength, dan Machine terbaik untuk latihanmu di Purwokerto.
@endsection

@section('meta_keywords', 'alat gym purwokerto, treadmill, smith machine, leg press, b1ng gym equipment')
@section('meta_image', asset('assets/compressed-1771109800.webp'))

{{-- Schema Markup CollectionPage --}}
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

@include('components.global-loader')

@include('components.navbar-cta')

@section('content')

{{--
    =============================================
    1. HERO SECTION (DARK MODE)
    ============================================= 
--}}
<div class="relative bg-neutral-900 pt-36 pb-24 border-b-4 border-red-600">
    {{-- Background Image with Overlay --}}
    <div class="absolute inset-0 overflow-hidden">
        <img src="/assets/compressed-1771109800.webp"
            class="w-full h-full object-cover opacity-20 grayscale">
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-transparent to-neutral-900"></div>
    </div>

    <div class="relative container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tight mb-4">
            Gym <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-yellow-500">Arsenal</span>
        </h1>
        <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto font-light">
            Eksplorasi koleksi mesin tempur standar internasional kami. Pilih senjatamu, mulai transformasi.
        </p>
    </div>
</div>

{{--
    =============================================
    2. MAIN CONTENT (LIGHT MODE)
    ============================================= 
--}}
<div class="bg-gray-50 min-h-screen pb-20">
    <div class="container mx-auto px-4 relative -mt-8 z-10">

        {{-- FILTER BAR (Floating Card) --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 mb-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">

                {{-- Left: Category Pills --}}
                <div class="flex flex-wrap justify-center md:justify-start gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
                        class="px-5 py-2 rounded-full font-bold text-xs uppercase tracking-wider transition border
                       {{ !request('category') 
                          ? 'bg-black text-white border-black shadow-md transform scale-105' 
                          : 'bg-gray-100 text-gray-500 border-transparent hover:bg-gray-200' }}">
                        All Units
                    </a>

                    @foreach(['Cardio', 'Strength', 'Machine'] as $cat)
                    <a href="{{ request()->fullUrlWithQuery(['category' => $cat]) }}"
                        class="px-5 py-2 rounded-full font-bold text-xs uppercase tracking-wider transition border
                       {{ request('category') == $cat 
                          ? 'bg-red-600 text-white border-red-600 shadow-md transform scale-105' 
                          : 'bg-gray-100 text-gray-500 border-transparent hover:bg-gray-200' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                </div>

                {{-- Right: Location Dropdown --}}
                <div class="relative w-full md:w-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ri-map-pin-line text-gray-400"></i>
                    </div>
                    <select onchange="window.location.href = this.value"
                        class="appearance-none w-full md:w-64 bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold py-2.5 pl-10 pr-8 rounded-lg focus:outline-none focus:bg-white focus:border-red-500 transition cursor-pointer">

                        <option value="{{ request()->fullUrlWithQuery(['gym' => null]) }}" {{ !request('gym') ? 'selected' : '' }}>
                            Semua Lokasi
                        </option>
                        @foreach($gyms as $gym)
                        <option value="{{ request()->fullUrlWithQuery(['gym' => $gym->id]) }}" {{ request('gym') == $gym->id ? 'selected' : '' }}>
                            {{ $gym->name }}
                        </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="ri-arrow-down-s-fill text-xs"></i>
                    </div>
                </div>

            </div>
        </div>

        {{-- GRID SYSTEM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($equipments as $item)

            {{-- Logic Warna Label per Kartu --}}
            @php
            $gymName = strtoupper($item->gymkos->name ?? '');
            $isKing = \Illuminate\Support\Str::contains($gymName, 'KING');

            // Set warna badge lokasi berdasarkan brand
            $badgeBg = $isKing ? 'bg-yellow-500 text-black' : 'bg-red-600 text-white';
            $hoverBorder = $isKing ? 'group-hover:border-yellow-500' : 'group-hover:border-red-600';

            $thumbnail = $item->gallery->first()
            ? asset('storage/' . $item->gallery->first()->file_path)
            : 'https://placehold.co/600x400/eee/999?text=No+Image';
            @endphp

            {{-- CARD ITEM --}}
            <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 {{ $hoverBorder }}">

                {{-- Image Area --}}
                <div class="relative h-56 overflow-hidden bg-gray-100">
                    <img src="{{ $thumbnail }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">

                    {{-- Overlay Gradient saat Hover --}}
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-40 transition duration-300"></div>

                    {{-- Badge Kategori (Pojok Kiri Atas) --}}
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur text-gray-800 text-[10px] font-black px-3 py-1 rounded uppercase tracking-widest shadow-sm">
                            {{ $item->category }}
                        </span>
                    </div>

                    {{-- Play Icon (Center) --}}
                    <a href="{{ route('gym.equipments.show', $item->slug) }}" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 transform scale-50 group-hover:scale-100">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <i class="ri-play-fill text-xl text-black"></i>
                        </div>
                    </a>
                </div>

                {{-- Content Area --}}
                <div class="p-5">
                    {{-- Badge Lokasi (Dynamic Color) --}}
                    <div class="flex justify-between items-start mb-2">
                        <span class="{{ $badgeBg }} text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                            {{ $item->gymkos->name ?? 'Gym N/A' }}
                        </span>

                        {{-- Status Dot --}}
                        @if($item->status == 'active')
                        <span class="flex h-2 w-2 relative" title="Active">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-black text-gray-800 uppercase leading-tight mb-2 group-hover:text-gray-600 transition">
                        <a href="{{ route('gym.equipments.show', $item->slug) }}">
                            {{ $item->name }}
                        </a>
                    </h3>

                    <p class="text-gray-500 text-sm line-clamp-2 mb-4">
                        {{ $item->description }}
                    </p>

                    <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                        <span class="text-xs text-gray-400 font-medium">Lihat Detail</span>
                        <a href="{{ route('gym.equipments.show', $item->slug) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 hover:bg-black hover:text-white transition text-gray-600">
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty

            {{-- Empty State --}}
            <div class="col-span-full py-16 text-center">
                <div class="inline-block p-6 rounded-full bg-gray-100 mb-4">
                    <i class="ri-database-2-line text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 uppercase">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 mt-2">Tidak ada alat yang sesuai dengan filter pencarianmu.</p>
                <a href="{{ route('gym.equipments.index') }}" class="inline-block mt-6 px-6 py-2 bg-black text-white text-sm font-bold uppercase tracking-wider rounded hover:bg-gray-800 transition">
                    Reset Filter
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-12 flex justify-center">
            {{ $equipments->links() }}
        </div>
    </div>
</div>

@include('components.footer-bing-empire')

@endsection