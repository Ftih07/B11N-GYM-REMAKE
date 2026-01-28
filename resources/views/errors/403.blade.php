@extends('layouts.main')

@section('title', '403 - Restricted Area | B1NG Empire')

@section('content')
<div class="relative min-h-screen bg-neutral-900 flex items-center justify-center overflow-hidden">
    
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0">
        <h1 class="text-[20rem] font-black text-neutral-800 opacity-30 tracking-tighter">
            403
        </h1>
    </div>

    <div class="relative z-10 max-w-lg w-full px-4 text-center">
        {{-- Icon Kuning/Emas (Warning) --}}
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-yellow-500/10 border-2 border-yellow-500 mb-8 shadow-[0_0_30px_rgba(234,179,8,0.4)]">
            <i class="ri-lock-2-fill text-5xl text-yellow-500"></i>
        </div>

        <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight mb-4">
            Members Only
        </h2>
        
        <p class="text-gray-400 text-lg mb-8 leading-relaxed">
            Stop! Kamu tidak memiliki izin (Membership) untuk mengakses area ini. Silakan hubungi admin atau login dengan akun yang sesuai.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="px-8 py-3 bg-yellow-500 text-black font-black uppercase tracking-wider hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/20">
                <i class="ri-shield-user-line mr-1"></i> Login Page
            </a>
        </div>
    </div>
</div>
@endsection