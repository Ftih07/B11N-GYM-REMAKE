<div>
    <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
        Preview QR Code
    </label>
    <div class="mt-2 p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 flex justify-center">
        @if($getRecord()->qr_path)
            <img src="{{ asset('storage/' . $getRecord()->qr_path) }}" alt="QR Preview" class="w-48 h-48 object-contain border bg-white">
        @else
            <span class="text-gray-500">Gambar belum tersedia</span>
        @endif
    </div>
</div>