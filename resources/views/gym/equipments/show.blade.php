@extends('layouts.main') @section('content')

<div class="bg-gray-900 py-12 text-white">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold uppercase">{{ $equipment->name }}</h1>
        <p class="text-gray-400 mt-2">Home > Equipments > {{ $equipment->name }}</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-black rounded-xl overflow-hidden shadow-2xl aspect-video relative">
                @if($equipment->video_url)
                    <iframe class="w-full h-full" 
                        src="{{ $equipment->video_url }}" 
                        title="Tutorial Video" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                @else
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <p>Video tutorial belum tersedia.</p>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="text-2xl font-bold mb-4 text-gray-800">Description</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ $equipment->description }}
                </p>
                
                <div class="mt-6 pt-6 border-t flex gap-4">
                    <div class="bg-gray-100 px-4 py-2 rounded-lg">
                        <span class="block text-xs text-gray-500 uppercase">Category</span>
                        <span class="font-bold text-gray-800">{{ $equipment->category }}</span>
                    </div>
                    <div class="bg-gray-100 px-4 py-2 rounded-lg">
                        <span class="block text-xs text-gray-500 uppercase">Status</span>
                        <span class="font-bold {{ $equipment->status == 'active' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($equipment->status) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="space-y-8">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Gallery</h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($equipment->gallery as $photo)
                    <div class="aspect-square rounded-lg overflow-hidden cursor-pointer hover:opacity-80 transition" onclick="window.open('{{ asset('storage/'.$photo->file_path) }}', '_blank')">
                        <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @if($equipment->gallery->isEmpty())
                    <p class="text-sm text-gray-400 italic">Tidak ada foto tambahan.</p>
                @endif
            </div>

            <div class="bg-gray-50 p-6 rounded-xl border">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Alat Lainnya</h3>
                <div class="space-y-4">
                    @foreach($relatedEquipments as $related)
                    <a href="{{ route('gym.equipments.show', $related->slug) }}" class="flex items-center gap-3 group">
                        <div class="w-16 h-16 rounded-md overflow-hidden flex-shrink-0">
                            @php $img = $related->gallery->first() ? asset('storage/'.$related->gallery->first()->file_path) : 'https://placehold.co/100'; @endphp
                            <img src="{{ $img }}" class="w-full h-full object-cover group-hover:scale-110 transition">
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm group-hover:text-red-600 transition">{{ $related->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $related->category }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection