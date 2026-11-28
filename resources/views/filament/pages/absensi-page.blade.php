<x-filament-panels::page>

    {{-- Styling khusus halaman ini. Sengaja memakai CSS biasa (bukan utility Tailwind) supaya tidak
         bergantung pada hasil build Tailwind. Dark mode mengikuti class .dark milik Filament. --}}
    <style>
        /* Header panel Scan Wajah (tombol buka/tutup) */
        .abs-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem;
            text-align: left;
            background: transparent;
            cursor: pointer;
            transition: background-color .15s ease;
        }

        .abs-toggle:hover {
            background-color: rgba(17, 24, 39, .04);
        }

        .dark .abs-toggle:hover {
            background-color: rgba(255, 255, 255, .06);
        }

        .abs-toggle:focus-visible {
            outline: 2px solid #eab308;
            outline-offset: -2px;
        }

        .abs-icon {
            flex-shrink: 0;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fef9c3;
            color: #ca8a04;
        }

        .dark .abs-icon {
            background: rgba(234, 179, 8, .2);
            color: #eab308;
        }

        .abs-live {
            display: inline-flex;
            align-items: center;
            gap: .375rem;
            padding: .125rem .625rem;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 500;
            background: #dcfce7;
            color: #15803d;
        }

        .abs-live::before {
            content: '';
            width: .375rem;
            height: .375rem;
            border-radius: 9999px;
            background: #22c55e;
        }

        .dark .abs-live {
            background: rgba(34, 197, 94, .2);
            color: #4ade80;
        }

        /* Area kamera */
        .abs-cam {
            position: relative;
            width: 100%;
            height: 400px;
            min-height: 400px;
            overflow: hidden;
            border-radius: .5rem;
            margin-bottom: 1rem;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
        }

        .dark .abs-cam {
            background: #000;
            border-color: #374151;
        }

        .abs-cam-overlay {
            position: absolute;
            inset: 0;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            text-align: center;
            font-weight: 500;
            background: rgba(255, 255, 255, .85);
            color: #111827;
            backdrop-filter: blur(4px);
        }

        .dark .abs-cam-overlay {
            background: rgba(0, 0, 0, .85);
            color: #fff;
        }

        .abs-debug {
            width: 100%;
            padding: .75rem;
            border-radius: .5rem;
            text-align: center;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .dark .abs-debug {
            background: #1f2937;
            border-color: #374151;
        }

        /* Tombol "Ganti Lokasi" */
        .abs-btn-ghost {
            font-size: .75rem;
            font-weight: 700;
            padding: .375rem .75rem;
            border-radius: .25rem;
            background: #f3f4f6;
            color: #374151;
            transition: background-color .15s ease;
        }

        .abs-btn-ghost:hover {
            background: #e5e7eb;
        }

        .dark .abs-btn-ghost {
            background: #1f2937;
            color: #fff;
        }

        .dark .abs-btn-ghost:hover {
            background: #374151;
            color: #fff;
        }

        /* Chip Member ID & lokasi di kartu hasil absen */
        .abs-chips {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin: .5rem 0;
        }

        .abs-chip {
            display: inline-flex;
            align-items: center;
            gap: .375rem;
            padding: .125rem .625rem;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .abs-chip-id {
            background: #fef9c3;
            color: #854d0e;
            border-color: #fde68a;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        .dark .abs-chip-id {
            background: rgba(234, 179, 8, .2);
            color: #fde047;
            border-color: rgba(234, 179, 8, .35);
        }

        .abs-chip-gym {
            background: #e0f2fe;
            color: #075985;
            border-color: #bae6fd;
        }

        .dark .abs-chip-gym {
            background: rgba(14, 165, 233, .2);
            color: #7dd3fc;
            border-color: rgba(14, 165, 233, .35);
        }

        .abs-chip-home {
            background: #f3f4f6;
            color: #374151;
            border-color: #e5e7eb;
        }

        .dark .abs-chip-home {
            background: rgba(255, 255, 255, .08);
            color: #d1d5db;
            border-color: rgba(255, 255, 255, .15);
        }
    </style>

    {{-- ========================================== --}}
    {{-- KONDISI 1: JIKA LOKASI BELUM DIPILIH --}}
    {{-- ========================================== --}}
    @if (!$selectedGymId)
        <div
            class="bg-white dark:bg-gray-900 p-8 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 text-center max-w-3xl mx-auto mt-10 transition-colors duration-300">

            <div class="mb-8">
                <div
                    class="w-16 h-16 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-600 dark:text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pilih Lokasi Absensi</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-3 max-w-lg mx-auto leading-relaxed">
                    Pilih lokasi operasional Anda saat ini. Sistem akan otomatis mencatat data absensi pada lokasi yang
                    Anda pilih.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($gyms as $gym)
                    <button wire:click="setGym({{ $gym->id }})"
                        class="w-full h-full justify-center p-6 border-2 border-gray-200 dark:border-gray-800 bg-black dark:bg-gray-800/50 rounded-xl hover:border-yellow-500 dark:hover:border-yellow-500 transition-all duration-200 group flex flex-col items-center gap-3">

                        <span
                            class="text-xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-yellow-600 dark:group-hover:text-yellow-500 text-center">
                            {{ $gym->name }}
                        </span>

                        <span
                            class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-yellow-600 dark:group-hover:text-yellow-500 text-center">
                            Klik untuk masuk sesi absen
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif


    {{-- ========================================== --}}
    {{-- KONDISI 2: JIKA LOKASI SUDAH DIPILIH --}}
    {{-- ========================================== --}}
    @if ($selectedGymId)
        {{-- Baris Status Lokasi --}}
        <div
            class="bg-white dark:bg-gray-900 rounded-xl p-4 flex justify-between items-center shadow-sm mb-6 border border-gray-200 dark:border-gray-800 transition-colors duration-300">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                </span>
                <span class="font-medium text-sm text-gray-700 dark:text-white">Mode Absensi Aktif:
                    <strong
                        class="text-yellow-600 dark:text-yellow-500 ml-1 uppercase">{{ collect($gyms)->firstWhere('id', $selectedGymId)->name ?? 'Unknown' }}</strong>
                </span>
            </div>
            <button wire:click="resetGym" class="abs-btn-ghost">
                Ganti Lokasi
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6"
            x-on:attendance-processed.window="handleResult($event.detail.result)" x-data="faceRecognition({
                wire: $wire,
                faceApiUrl: @js(asset('js/face-api.min.js')),
                modelsUrl: '/models',
                audioUrls: {
                    success: @js(asset('sounds/success.mp3')),
                    error: @js(asset('sounds/error.mp3')),
                    warning: @js(asset('sounds/warning.mp3'))
                }
            })">

            {{-- KOLOM KIRI (Scan Wajah collapsible & Hasil) --}}
            <div class="space-y-6">

                {{-- 1. SCANNER (BISA DIBUKA / DITUTUP) --}}
                <div
                    class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden transition-colors duration-300">

                    {{-- Header / Tombol Toggle --}}
                    <button type="button" @click="toggleScanner()" :aria-expanded="scannerOpen.toString()"
                        aria-controls="face-scanner-panel" class="abs-toggle">

                        <div class="flex items-center gap-4">
                            <span class="abs-icon">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Scan Wajah</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                                    x-text="scannerOpen ? 'Kamera aktif. Klik untuk menutup.' : 'Kamera nonaktif. Klik untuk membuka.'">
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span x-show="scannerOpen" x-cloak class="abs-live">Aktif</span>
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200"
                                :class="scannerOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </button>

                    {{-- Isi Panel --}}
                    <div id="face-scanner-panel" x-show="scannerOpen" x-collapse x-cloak>
                        <div
                            class="px-6 pb-6 flex flex-col items-center border-t border-gray-100 dark:border-gray-800 pt-6">

                            {{-- Area Kamera --}}
                            <div wire:ignore class="abs-cam">

                                <div x-show="isLoading" class="abs-cam-overlay">
                                    <span x-text="statusMessage" class="font-medium">Memuat...</span>
                                </div>

                                <video x-ref="video" autoplay muted playsinline
                                    style="width: 100%; height: 100%; object-fit: cover; position: absolute; z-index: 10;"></video>
                                <canvas x-ref="canvas"
                                    style="width: 100%; height: 100%; position: absolute; z-index: 20; pointer-events: none;"></canvas>
                            </div>

                            {{-- Debug Text --}}
                            <div class="abs-debug">
                                <p x-text="debugMessage"
                                    class="font-mono text-sm text-yellow-600 dark:text-yellow-500 font-bold animate-pulse">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. HASIL MEMBER (CARD) - tampil untuk hasil scan maupun input manual --}}
                <div x-show="lastMember" x-cloak x-transition.duration.500ms
                    class="bg-yellow-50 dark:bg-yellow-500/10 p-6 rounded-xl shadow-sm border border-yellow-200 dark:border-yellow-500/20 transition-colors duration-300">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        {{-- Foto --}}
                        <div class="flex-shrink-0">
                            <template x-if="lastMember && lastMember.photo_url">
                                <img :src="lastMember.photo_url"
                                    class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md">
                            </template>
                            <template x-if="lastMember && !lastMember.photo_url">
                                <div
                                    class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-4xl shadow-md">
                                    👤</div>
                            </template>
                        </div>

                        {{-- Info Detail --}}
                        <div class="flex-grow w-full">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white"
                                x-text="lastMember?.member_name"></h3>

                            {{-- Member ID & lokasi absen --}}
                            <div class="abs-chips">
                                <span class="abs-chip abs-chip-id" x-show="lastMember?.member_code" title="Member ID">
                                    🆔 <span x-text="lastMember?.member_code"></span>
                                </span>
                                <span class="abs-chip abs-chip-gym" x-show="lastMember?.gym_name" title="Lokasi absen">
                                    📍 <span x-text="lastMember?.gym_name"></span>
                                </span>
                                <span class="abs-chip abs-chip-home"
                                    x-show="lastMember?.home_gym_name && lastMember?.home_gym_name !== lastMember?.gym_name"
                                    title="Cabang asal member">
                                    Member cabang <span x-text="lastMember?.home_gym_name"></span>
                                </span>
                            </div>

                            <div class="text-sm text-gray-700 dark:text-white mt-2 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-4">📞</span><span x-text="lastMember?.phone || '-'"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-4">📧</span><span x-text="lastMember?.email || '-'"></span>
                                </div>
                                <div class="flex items-center gap-2" x-show="lastMember?.message">
                                    <span class="w-4">📦</span><span
                                        class="font-semibold text-yellow-600 dark:text-yellow-400"
                                        x-text="lastMember?.message"></span>
                                </div>
                            </div>

                            <div
                                class="mt-3 pt-3 border-t border-yellow-200 dark:border-yellow-500/20 flex justify-between items-center">
                                <span
                                    class="text-xs font-semibold text-yellow-700 dark:text-yellow-500 uppercase tracking-wider">Status
                                    Membership</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">S/d: <span
                                        x-text="lastMember?.expired_date || '-'"></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. ERROR ALERT --}}
                <div x-show="errorMember" x-cloak x-transition.duration.300ms
                    class="bg-red-50 dark:bg-red-500/10 border-l-4 border-red-500 p-4 rounded shadow-sm transition-colors duration-300"
                    role="alert">
                    <p class="font-bold text-red-800 dark:text-red-400">Akses Ditolak!</p>
                    <p class="text-red-700 dark:text-red-300">Halo <span
                            x-text="errorMember?.member_name"></span><span x-show="errorMember?.member_code"> (<span
                                x-text="errorMember?.member_code"></span>)</span>, Membership kamu sudah <b>TIDAK
                            AKTIF</b>.</p>
                </div>
            </div>

            {{-- KOLOM KANAN: MANUAL INPUT --}}
            <div
                class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 h-fit transition-colors duration-300">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Input Manual</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Cari member lewat nama, Member ID, email, atau
                    no. HP. Gunakan ini jika scan wajah tidak dipakai atau gagal.</p>

                <form wire:submit.prevent="submitManualAttendance" class="space-y-6">

                    <div>
                        {{ $this->form }}
                    </div>

                    <button type="submit" style="background-color: #facc15;"
                        class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg shadow-sm text-sm font-bold text-gray-900 hover:opacity-90 transition-all mt-4">
                        Absen Sekarang
                    </button>
                </form>
            </div>
        </div>
    @endif

    <script>
        (function() {
            // Cache di luar Alpine supaya script & model hanya dimuat sekali per halaman
            let faceApiPromise = null;
            let modelsPromise = null;

            const registerFaceRecognition = () => {
                Alpine.data('faceRecognition', ({
                    wire,
                    faceApiUrl,
                    modelsUrl,
                    audioUrls
                }) => ({
                    // --- State panel scan ---
                    scannerOpen: false, // default tertutup: kamera & AI tidak dimuat sama sekali
                    session: 0, // naik setiap buka/tutup, untuk membatalkan proses yang masih berjalan
                    isLoading: true,
                    statusMessage: 'Memuat...',
                    debugMessage: 'Kamera nonaktif.',

                    // --- State scan ---
                    stream: null,
                    faceMatcher: null,
                    detectTimer: null,
                    detecting: false,
                    isProcessing: false,
                    lastMember: null,
                    errorMember: null,
                    audioUnlocked: false,
                    audioPlayer: {
                        success: null,
                        error: null,
                        warning: null
                    },

                    // Dipanggil otomatis oleh Alpine saat komponen dibuat
                    init() {
                        ['click', 'touchstart', 'keydown'].forEach(evt =>
                            document.addEventListener(evt, () => this.unlockAudio(), {
                                once: true
                            })
                        );

                        this.audioPlayer.success = new Audio(audioUrls.success);
                        this.audioPlayer.error = new Audio(audioUrls.error);
                        this.audioPlayer.warning = new Audio(audioUrls.warning);

                        this.audioPlayer.success.volume = 0.8;
                        this.audioPlayer.error.volume = 0.8;
                        this.audioPlayer.warning.volume = 0.8;
                    },

                    // Dipanggil otomatis saat komponen dihapus (mis. klik "Ganti Lokasi"),
                    // supaya kamera tidak tetap menyala.
                    destroy() {
                        this.stopScanner();
                    },

                    // ------------------------------------------------------
                    // TOGGLE PANEL
                    // ------------------------------------------------------
                    toggleScanner() {
                        this.scannerOpen ? this.closeScanner() : this.openScanner();
                    },

                    async openScanner() {
                        if (this.scannerOpen) return;

                        this.scannerOpen = true;
                        this.isLoading = true;
                        this.statusMessage = 'Memuat AI pengenalan wajah...';
                        this.debugMessage = 'Menyiapkan kamera...';

                        const token = ++this.session;

                        try {
                            await this.ensureFaceApi();
                            await this.ensureModels();
                            if (token !== this.session) return;

                            this.statusMessage = 'Memuat data wajah member...';
                            const members = await wire.loadFaceMembers();
                            if (token !== this.session) return;

                            this.faceMatcher = this.buildMatcher(members);

                            this.statusMessage = 'Membuka kamera...';
                            await this.startVideo();
                            if (token !== this.session) {
                                this.stopVideo();
                                return;
                            }

                            this.isLoading = false;
                            this.debugMessage = this.faceMatcher ?
                                'Mencari Wajah...' :
                                'Belum ada data wajah member yang terdaftar.';

                            this.startDetection(token);
                        } catch (e) {
                            if (token !== this.session) return;
                            console.error('Error Init:', e);
                            this.isLoading = true;
                            this.statusMessage = (e && e.name === 'NotAllowedError') ?
                                'Izin kamera ditolak. Izinkan akses kamera di browser, lalu buka kembali.' :
                                'Gagal memuat AI/kamera. Tutup lalu buka panel ini lagi.';
                        }
                    },

                    closeScanner() {
                        this.stopScanner();
                        this.scannerOpen = false;
                        this.isLoading = true;
                        this.statusMessage = 'Memuat...';
                        this.debugMessage = 'Kamera nonaktif.';
                    },

                    // ------------------------------------------------------
                    // LOADER (lazy)
                    // ------------------------------------------------------
                    ensureFaceApi() {
                        if (typeof faceapi !== 'undefined') return Promise.resolve();

                        if (!faceApiPromise) {
                            faceApiPromise = new Promise((resolve, reject) => {
                                const script = document.createElement('script');
                                script.src = faceApiUrl;
                                script.onload = () => resolve();
                                script.onerror = () => {
                                    faceApiPromise = null;
                                    script.remove();
                                    reject(new Error('face-api.js gagal dimuat'));
                                };
                                document.head.appendChild(script);
                            });
                        }
                        return faceApiPromise;
                    },

                    ensureModels() {
                        if (!modelsPromise) {
                            modelsPromise = Promise.all([
                                faceapi.nets.tinyFaceDetector.loadFromUri(modelsUrl),
                                faceapi.nets.faceLandmark68Net.loadFromUri(modelsUrl),
                                faceapi.nets.faceRecognitionNet.loadFromUri(modelsUrl)
                            ]).catch((e) => {
                                modelsPromise = null;
                                throw e;
                            });
                        }
                        return modelsPromise;
                    },

                    buildMatcher(members) {
                        const labeled = (members || []).map(m => {
                            try {
                                const raw = typeof m.descriptor === 'string' ? JSON.parse(m
                                    .descriptor) : m.descriptor;
                                if (!Array.isArray(raw) || raw.length !== 128) return null;
                                return new faceapi.LabeledFaceDescriptors(m.id.toString(), [
                                    new Float32Array(raw)
                                ]);
                            } catch (e) {
                                return null;
                            }
                        }).filter(d => d);

                        // FaceMatcher melempar error jika daftar kosong
                        return labeled.length ? new faceapi.FaceMatcher(labeled, 0.6) : null;
                    },

                    // ------------------------------------------------------
                    // KAMERA & DETEKSI
                    // ------------------------------------------------------
                    async startVideo() {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: {}
                        });
                        const video = this.$refs.video;
                        video.srcObject = this.stream;
                        await video.play().catch(() => {});
                    },

                    stopVideo() {
                        if (this.stream) {
                            this.stream.getTracks().forEach(track => track.stop());
                            this.stream = null;
                        }
                        const video = this.$refs.video;
                        if (video) {
                            video.pause();
                            video.srcObject = null;
                        }
                    },

                    stopScanner() {
                        this.session++;

                        if (this.detectTimer) {
                            clearInterval(this.detectTimer);
                            this.detectTimer = null;
                        }
                        this.detecting = false;

                        this.stopVideo();

                        const canvas = this.$refs.canvas;
                        if (canvas) canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas
                        .height);
                    },

                    startDetection(token) {
                        const video = this.$refs.video;
                        const canvas = this.$refs.canvas;
                        const options = new faceapi.TinyFaceDetectorOptions();

                        this.detectTimer = setInterval(async () => {
                            if (token !== this.session || this.isProcessing || this
                                .detecting || video.readyState < 2) return;

                            this.detecting = true;
                            try {
                                const size = {
                                    width: video.clientWidth,
                                    height: video.clientHeight
                                };
                                if (!size.width || !size.height) return;

                                if (canvas.width !== size.width || canvas.height !== size
                                    .height) {
                                    faceapi.matchDimensions(canvas, size);
                                }

                                const detections = await faceapi
                                    .detectAllFaces(video, options)
                                    .withFaceLandmarks()
                                    .withFaceDescriptors();

                                if (token !== this.session) return;

                                const resized = faceapi.resizeResults(detections, size);
                                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas
                                    .height);

                                if (!this.faceMatcher) return;

                                resized.forEach(d => {
                                    const match = this.faceMatcher.findBestMatch(d
                                        .descriptor);
                                    new faceapi.draw.DrawBox(d.detection.box, {
                                        label: match.toString(),
                                        boxColor: match.label == 'unknown' ?
                                            'red' : '#eab308'
                                    }).draw(canvas);

                                    if (match.label !== 'unknown') {
                                        this.triggerAttendance(match.label);
                                    }
                                });
                            } catch (e) {
                                console.error('Error Deteksi:', e);
                            } finally {
                                this.detecting = false;
                            }
                        }, 500);
                    },

                    triggerAttendance(memberId) {
                        if (this.isProcessing) return;
                        this.isProcessing = true;
                        this.debugMessage = `✅ Memproses ID ${memberId}...`;

                        wire.submitFaceAttendance(memberId)
                            .then((response) => {
                                this.handleResult(response);
                            })
                            .catch((err) => {
                                console.error(err);
                                this.isProcessing = false;
                            });
                    },

                    // ------------------------------------------------------
                    // HASIL (dipakai scan wajah & input manual)
                    // ------------------------------------------------------
                    handleResult(response) {
                        this.lastMember = null;
                        this.errorMember = null;

                        if (response.status === 'success') {
                            this.playAudio('success');
                            this.lastMember = response;
                            this.debugMessage = `Selamat Datang, ${response.member_name}!`;
                        } else if (response.status === 'warning') {
                            this.playAudio('warning');
                            this.lastMember = response;
                            this.debugMessage = response.message;
                        } else if (response.status === 'expired') {
                            this.playAudio('error');
                            this.errorMember = response;
                            this.debugMessage = 'MEMBERSHIP EXPIRED ❌';
                        } else {
                            this.debugMessage = response.message;
                        }

                        setTimeout(() => {
                            this.isProcessing = false;
                            this.debugMessage = this.scannerOpen ? 'Siap Scan...' :
                                'Kamera nonaktif.';
                        }, 5000);
                    },

                    // ------------------------------------------------------
                    // AUDIO
                    // ------------------------------------------------------
                    unlockAudio() {
                        if (this.audioUnlocked) return;
                        const sounds = [this.audioPlayer.success, this.audioPlayer.error, this
                            .audioPlayer.warning
                        ];
                        sounds.forEach(sound => {
                            if (sound) {
                                sound.volume = 0;
                                sound.play().then(() => {
                                    sound.pause();
                                    sound.currentTime = 0;
                                    sound.volume = 0.8;
                                }).catch(e => {});
                            }
                        });
                        this.audioUnlocked = true;
                    },

                    playAudio(type) {
                        if (!this.audioUnlocked) this.unlockAudio();
                        if (this.audioPlayer[type]) {
                            this.audioPlayer[type].currentTime = 0;
                            this.audioPlayer[type].play().catch(e => {});
                        }
                    }
                }));
            };

            if (window.Alpine) {
                registerFaceRecognition();
            } else {
                document.addEventListener('alpine:init', registerFaceRecognition);
            }
        })();
    </script>
</x-filament-panels::page>
