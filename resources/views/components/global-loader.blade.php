<div id="global-page-loader" class="fixed inset-0 z-[9999] bg-gray-100 flex flex-col transition-opacity duration-500 overflow-hidden">

    <div class="absolute top-0 left-0 h-1 bg-red-600 w-full z-[10000]">
        <div class="h-full bg-white opacity-50 animate-[shimmer_1.5s_infinite]"></div>
    </div>

    <div class="absolute inset-0 opacity-40 blur-[2px] pointer-events-none flex flex-col">
        <div class="w-full h-20 bg-[#161616] flex items-center justify-between px-6 lg:px-12 animate-pulse">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-700"></div>
                <div class="w-32 h-5 bg-gray-700 rounded-sm"></div>
            </div>
            <div class="hidden lg:flex gap-8">
                <div class="w-12 h-3 bg-gray-700 rounded-sm"></div>
                <div class="w-20 h-3 bg-gray-700 rounded-sm"></div>
                <div class="w-14 h-3 bg-gray-700 rounded-sm"></div>
                <div class="w-12 h-3 bg-gray-700 rounded-sm"></div>
            </div>
        </div>

        <div class="flex-grow flex flex-col items-center justify-center p-4">
            <div class="h-4 w-48 bg-gray-300 rounded-sm mb-8 animate-pulse"></div>
            <div class="h-24 w-72 bg-gray-300 rounded-sm mb-4 animate-pulse"></div>
            <div class="h-24 w-80 bg-gray-300 rounded-sm mb-12 animate-pulse"></div>
            <div class="h-5 w-full max-w-lg bg-gray-300 rounded-sm mb-3 animate-pulse"></div>
        </div>
    </div>

    <div class="relative z-50 flex-grow flex flex-col items-center justify-center pointer-events-none">

        <div class="absolute w-48 h-48 bg-red-600/20 rounded-full blur-2xl animate-pulse"></div>

        <div class="relative flex flex-col items-center">
            <h1 class="font-heading text-textDark font-bold text-4xl sm:text-5xl tracking-tight uppercase flex items-center gap-2">
                B1NG <span class="text-red-600 opacity-90 animate-pulse">EMPIRE</span>
            </h1>

            <div class="mt-6 w-32 h-1 bg-gray-300 rounded-full overflow-hidden">
                <div class="h-full bg-red-600 rounded-full animate-[progress_1.5s_ease-in-out_infinite]"></div>
            </div>

            <p class="mt-4 font-heading text-xs text-textLight tracking-[0.3em] uppercase font-medium">
                Mempersiapkan Ruangan...
            </p>
        </div>
    </div>

</div>

<style>
    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    @keyframes progress {
        0% {
            transform: translateX(-100%);
            width: 50%;
        }

        50% {
            transform: translateX(50%);
            width: 100%;
        }

        100% {
            transform: translateX(200%);
            width: 50%;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loader = document.getElementById('global-page-loader');

        // Fungsi untuk menyembunyikan loader
        function hideLoader() {
            if (!loader) return;
            loader.classList.add('opacity-0');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }

        // 1. Matikan loader saat halaman pertama kali selesai dimuat
        window.addEventListener('load', hideLoader);

        // 2. Trik BFCache: Matikan loader saat user pakai tombol "Back" atau "Forward"
        window.addEventListener('pageshow', function(event) {
            // event.persisted bernilai true jika halaman diload dari cache browser
            if (event.persisted) {
                hideLoader();
            }
        });

        // 3. Nyalakan loader saat pindah halaman
        document.addEventListener('click', function(e) {
            const target = e.target.closest('a');

            if (target && target.href) {
                const href = target.href;

                // Jangan munculkan loader untuk link tertentu
                if (
                    target.target !== '_blank' &&
                    !href.includes('#') &&
                    !href.startsWith('javascript:') &&
                    !href.startsWith('mailto:') &&
                    !href.startsWith('tel:')
                ) {
                    loader.style.display = 'flex';
                    setTimeout(() => {
                        loader.classList.remove('opacity-0');
                    }, 10);
                }
            }
        });
    });
</script>