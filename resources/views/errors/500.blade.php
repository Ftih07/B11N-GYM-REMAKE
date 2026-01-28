@extends('layouts.main')

@section('title', '500 - System Failure | B1NG Empire')

@section('content')
<div class="relative min-h-screen bg-red-950 flex items-center justify-center overflow-hidden">
    
    {{-- Background merah gelap untuk kesan urgent --}}
    <div class="absolute inset-0 bg-neutral-900 opacity-90"></div>

    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0">
        <h1 class="text-[20rem] font-black text-red-900 opacity-20 tracking-tighter animate-pulse">
            500
        </h1>
    </div>

    <div class="relative z-10 max-w-lg w-full px-4 text-center">
        
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-600 mb-8 shadow-xl animate-bounce">
            <i class="ri-alert-fill text-5xl text-white"></i>
        </div>

        <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight mb-4">
            System Failure
        </h2>
        
        <p class="text-red-200 text-lg mb-8 leading-relaxed">
            Ada beban yang terlalu berat untuk server kami. Tim teknis sedang melakukan "spotting" untuk memperbaiki masalah ini secepatnya.
        </p>

        <a href="{{ url('/') }}" class="inline-block px-8 py-3 bg-transparent border-2 border-white text-white font-black uppercase tracking-wider hover:bg-white hover:text-red-900 transition">
            Reload Page
        </a>
    </div>
</div>
@endsection