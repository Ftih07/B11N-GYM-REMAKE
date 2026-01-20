@extends('layouts.main')

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
    
    <div class="flex flex-wrap justify-center gap-4 mb-12">
        <button class="px-6 py-2 bg-red-600 text-white rounded-full font-bold shadow-lg">All</button>
        <button class="px-6 py-2 bg-white text-gray-600 border hover:bg-gray-100 rounded-full font-semibold transition">Cardio</button>
        <button class="px-6 py-2 bg-white text-gray-600 border hover:bg-gray-100 rounded-full font-semibold transition">Strength</button>
        <button class="px-6 py-2 bg-white text-gray-600 border hover:bg-gray-100 rounded-full font-semibold transition">Machine</button>
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
                    <a href="{{ route('gym.equipments.show', $item->id) }}" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300">
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
                        <a href="{{ route('gym.equipments.show', $item->id) }}" class="text-red-600 font-bold text-sm hover:text-red-800 flex items-center gap-1">
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