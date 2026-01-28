@extends('layouts.main')

@section('title', '404 - Lost Your Spot? | B1NG Empire')

@section('content')
<div class="relative min-h-screen bg-neutral-900 flex items-center justify-center overflow-hidden">
    
    {{-- BACKGROUND DECORATION --}}
    {{-- Angka Raksasa di Belakang (Efek Industrial) --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0">
        <h1 class="text-[20rem] font-black text-neutral-800 opacity-30 tracking-tighter transform -rotate-6">
            404
        </h1>
    </div>

    {{-- Overlay Noise/Texture (Opsional) --}}
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 z-0"></div>

    {{-- MAIN CONTENT CARD --}}
    <div class="relative z-10 max-w-lg w-full px-4 text-center">
        
        {{-- Icon --}}
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-600/10 border-2 border-red-600 mb-8 shadow-[0_0_30px_rgba(220,38,38,0.4)]">
            <i class="ri-map-pin-time-line text-5xl text-red-600"></i>
        </div>

        {{-- Typography --}}
        <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight mb-4">
            Out of Zone
        </h2>
        
        <p class="text-gray-400 text-lg mb-8 leading-relaxed">
            Waduh, sepertinya kamu tersesat di area yang belum dibangun. Alat atau halaman yang kamu cari tidak ada di rak kami.
        </p>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="group relative px-8 py-3 bg-white text-black font-black uppercase tracking-wider hover:bg-gray-200 transition overflow-hidden">
                <span class="relative z-10 flex items-center gap-2">
                    <i class="ri-home-4-fill"></i> Back to Home
                </span>
                {{-- Hover Effect --}}
                <div class="absolute inset-0 bg-red-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                <span class="absolute inset-0 border-2 border-white pointer-events-none"></span>
            </a>

            <a href="{{ url()->previous() }}" class="px-8 py-3 border border-gray-600 text-gray-300 font-bold uppercase tracking-wider hover:border-white hover:text-white transition">
                Go Back
            </a>
        </div>
    </div>
    
    {{-- Footer Kecil --}}
    <div class="absolute bottom-8 w-full text-center text-neutral-600 text-xs uppercase tracking-widest">
        B1NG Empire System &copy; {{ date('Y') }}
    </div>
</div>
@endsection