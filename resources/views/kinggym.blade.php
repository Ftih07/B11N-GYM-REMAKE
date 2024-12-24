@foreach ($groupedTrainingPrograms as $categoryId => $trainingPrograms)
@php
$categoryTitle = $categories[$categoryId]->title ?? 'Unknown Category'; // Judul kategori
@endphp
<div class="category-group mb-8">
    <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ $categoryTitle }}</h3>
    <div class="sessions grid grid-cols-1 sm:grid-cols-2 gap-0">
        @foreach ($trainingPrograms as $trainingprogram)
        <div class="session__card bg-cover bg-center p-6 h-[300px] relative w-full"
            style="background-image: url('{{ asset('storage/' . $trainingprogram->image) }}');">
            <div class="bg-black bg-opacity-50 w-full h-full flex flex-col justify-center items-start p-6">
                <h4 class="text-xl font-bold text-white">{{ $trainingprogram->title }}</h4>
                <p class="text-white mt-4">
                    {{ \Illuminate\Support\Str::words(strip_tags($trainingprogram->description), 50, '...') }}
                </p>
                <button class="btn btn__secondary bg-white text-black mt-4 px-4 py-2 rounded shadow"
                    data-bs-toggle="modal" data-bs-target="#modal-{{ $trainingprogram->id }}">
                    READ MORE <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
