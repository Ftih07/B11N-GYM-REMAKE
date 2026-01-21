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

    {{-- Logic tombol filter --}}
    <div class="flex flex-wrap justify-center gap-4 mb-12">
        {{-- Tombol ALL: Aktif kalau TIDAK ADA request category --}}
        <a href="{{ route('gym.equipments.index') }}"
            class="px-6 py-2 rounded-full font-bold shadow-lg transition
           {{ !request('category') ? 'bg-red-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-100' }}">
            All
        </a>

        {{-- Tombol CARDIO: Aktif kalau category == Cardio --}}
        <a href="{{ route('gym.equipments.index', ['category' => 'Cardio']) }}"
            class="px-6 py-2 rounded-full font-bold shadow-lg transition
           {{ request('category') == 'Cardio' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-100' }}">
            Cardio
        </a>

        {{-- Tombol STRENGTH --}}
        <a href="{{ route('gym.equipments.index', ['category' => 'Strength']) }}"
            class="px-6 py-2 rounded-full font-bold shadow-lg transition
           {{ request('category') == 'Strength' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-100' }}">
            Strength
        </a>

        {{-- Tombol MACHINE --}}
        <a href="{{ route('gym.equipments.index', ['category' => 'Machine']) }}"
            class="px-6 py-2 rounded-full font-bold shadow-lg transition
           {{ request('category') == 'Machine' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 border hover:bg-gray-100' }}">
            Machine
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($equipments as $item)
        @php
        $thumbnail = $item->gallery->first()
        ? asset('storage/' . $item->gallery->first()->file_path)
        : 'https://placehold.co/600x400?text=No+Image';
        @endphp
        <div class="group bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-xl transition duration-300 flex flex-col h-full">
            <div class="relative h-60 overflow-hidden">
                <img src="{{ $thumbnail }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                <a href="{{ route('gym.equipments.show', $item->slug) }}" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300">
                    <i class="ri-play-circle-fill text-6xl text-white drop-shadow-lg"></i>
                </a>
            </div>
            <div class="p-6 flex-grow flex flex-col">
                <div class="mb-auto">
                    <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded mb-2 font-semibold uppercase">{{ $item->category }}</span>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->name }}</h3>
                    <p class="text-gray-500 text-sm line-clamp-3">{{ $item->description }}</p>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('gym.equipments.show', $item->slug) }}" class="text-red-600 font-bold text-sm hover:text-red-800 flex items-center gap-1">
                        LEARN MORE <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-12">
        {{ $equipments->links() }}
    </div>
</div>
@endsection