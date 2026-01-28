@extends('layouts.main')

@section('title', 'Maintenance - Rest Day | B1NG Empire')

@section('content')
<div class="relative min-h-screen bg-neutral-900 flex items-center justify-center overflow-hidden">
    
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0">
        <i class="ri-tools-fill text-[20rem] text-neutral-800 opacity-20 transform rotate-12"></i>
    </div>

    <div class="relative z-10 max-w-xl w-full px-4 text-center">
        
        <div class="mb-6">
            <span class="inline-block px-4 py-1 bg-yellow-500 text-black font-bold uppercase tracking-widest text-xs rounded mb-4">
                Under Maintenance
            </span>
        </div>

        <h2 class="text-5xl md:text-6xl font-black text-white uppercase tracking-tight mb-6 leading-none">
            WE ARE ON<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-red-600">REST DAY</span>
        </h2>
        
        <p class="text-gray-400 text-lg mb-8 leading-relaxed">
            Sistem kami sedang melakukan *upgrade* otot agar performa lebih maksimal. Kami akan segera kembali lebih kuat dari sebelumnya.
        </p>

        <div class="flex justify-center gap-6 text-gray-500">
            <a href="#" class="hover:text-white transition"><i class="ri-instagram-line text-2xl"></i></a>
            <a href="#" class="hover:text-white transition"><i class="ri-whatsapp-line text-2xl"></i></a>
            <a href="#" class="hover:text-white transition"><i class="ri-mail-line text-2xl"></i></a>
        </div>
    </div>
</div>
@endsection