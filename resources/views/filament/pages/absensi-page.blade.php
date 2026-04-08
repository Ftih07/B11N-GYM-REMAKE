<x-filament-panels::page>

    {{-- Script Face API --}}
    <script src="{{ asset('js/face-api.min.js') }}"></script>

    {{-- ========================================== --}}
    {{-- KONDISI 1: JIKA LOKASI BELUM DIPILIH --}}
    {{-- ========================================== --}}
    @if(!$selectedGymId)
    <div class="bg-white dark:bg-gray-900 p-8 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 text-center max-w-3xl mx-auto mt-10 transition-colors duration-300">

        <div class="mb-8"> {{-- Margin bawah diperbesar sedikit biar ada ruang nafas --}}
            <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-600 dark:text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pilih Lokasi Absensi</h2>
            {{-- Ditambahkan max-w-lg dan mx-auto agar teks tidak menabrak batas pinggir container --}}
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-3 max-w-lg mx-auto leading-relaxed">
                Pilih lokasi operasional Anda saat ini. Sistem akan otomatis mencatat data absensi pada lokasi yang Anda pilih.
            </p>
        </div>

        {{-- Mengubah sm:grid-cols-2 menjadi md:grid-cols-2 agar transisi ke 2 kolom lebih pas dengan sidebar Filament --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($gyms as $gym)
            {{-- Ditambahkan w-full, h-full, dan justify-center agar box tombol ukurannya selalu konsisten dan konten di tengah --}}
            <button wire:click="setGym({{ $gym->id }})"
                class="w-full h-full justify-center p-6 border-2 border-gray-200 dark:border-gray-800 bg-black dark:bg-gray-800/50 rounded-xl hover:border-yellow-500 dark:hover:border-yellow-500 transition-all duration-200 group flex flex-col items-center gap-3">

                {{-- Ditambahkan text-center memastikan tulisan panjang tetap rapi di tengah --}}
                <span class="text-xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-yellow-600 dark:group-hover:text-yellow-500 text-center">
                    {{ $gym->name }}
                </span>

                <span class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-yellow-600 dark:group-hover:text-yellow-500 text-center">
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
    @if($selectedGymId)

    {{-- Baris Status Lokasi --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-4 flex justify-between items-center shadow-sm mb-6 border border-gray-200 dark:border-gray-800 transition-colors duration-300">
        <div class="flex items-center gap-3">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
            </span>
            <span class="font-medium text-sm text-gray-700 dark:text-white">Mode Absensi Aktif:
                <strong class="text-yellow-600 dark:text-yellow-500 ml-1 uppercase">{{ collect($gyms)->firstWhere('id', $selectedGymId)->name ?? 'Unknown' }}</strong>
            </span>
        </div>
        <button wire:click="resetGym" class="text-xs font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 px-3 py-1.5 rounded transition">
            Ganti Lokasi
        </button>
    </div>

    <div
        class="grid grid-cols-1 md:grid-cols-2 gap-6"
        x-on:attendance-processed.window="handleResult($event.detail.result)"
        x-data="faceRecognition({ 
                members: @js($members_data),
                wire: $wire,
                audioUrls: {
                    success: 'https://cdn.jsdelivr.net/gh/Ftih07/B11N-GYM-REMAKE@main/public/sounds/success.mp3',
                    error:   'https://cdn.jsdelivr.net/gh/Ftih07/B11N-GYM-REMAKE@main/public/sounds/error.mp3',
                    warning: 'https://cdn.jsdelivr.net/gh/Ftih07/B11N-GYM-REMAKE@main/public/sounds/warning.mp3'
                }
            })"
        x-init="initFaceApi()">

        {{-- KOLOM KIRI (Kamera & Info) --}}
        <div class="space-y-6">
            {{-- 1. SCANNER --}}
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col items-center relative transition-colors duration-300">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Scan Wajah</h2>

                {{-- Area Kamera --}}
                <div class="relative w-full overflow-hidden rounded-lg mb-4 bg-gray-100 dark:bg-black border border-gray-300 dark:border-gray-700"
                    style="height: 400px; min-height: 400px; position: relative;">

                    <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center z-30 flex-col bg-white/80 dark:bg-black/80 text-gray-900 dark:text-white backdrop-blur-sm">
                        <span x-text="statusMessage" class="font-medium">Memuat...</span>
                    </div>

                    <video x-ref="video" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover; position: absolute; z-index: 10;"></video>
                    <canvas x-ref="canvas" style="width: 100%; height: 100%; position: absolute; z-index: 20; pointer-events: none;"></canvas>
                </div>

                {{-- Debug Text --}}
                <div class="w-full bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-700 text-center">
                    <p x-text="debugMessage" class="font-mono text-sm text-yellow-600 dark:text-yellow-500 font-bold animate-pulse"></p>
                </div>
            </div>

            {{-- 2. HASIL MEMBER (CARD) --}}
            <div x-show="lastMember" x-transition.duration.500ms class="bg-yellow-50 dark:bg-yellow-500/10 p-6 rounded-xl shadow-sm border border-yellow-200 dark:border-yellow-500/20 transition-colors duration-300">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                    {{-- Foto --}}
                    <div class="flex-shrink-0">
                        <template x-if="lastMember && lastMember.photo_url">
                            <img :src="lastMember.photo_url" class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md">
                        </template>
                        <template x-if="lastMember && !lastMember.photo_url">
                            <div class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-4xl shadow-md">👤</div>
                        </template>
                    </div>

                    {{-- Info Detail --}}
                    <div class="flex-grow w-full">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white" x-text="lastMember?.member_name"></h3>

                        {{-- Badge Gender & Mood --}}
                        <div x-show="scanExtras.gender" class="flex flex-wrap gap-2 mt-1 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700">
                                <span x-text="scanExtras.gender === 'Pria' ? '♂️' : '♀️'" class="mr-1"></span>
                                <span x-text="scanExtras.gender"></span>
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-500/20 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-500/30">
                                <span x-text="scanExtras.emoji" class="mr-1"></span>
                                <span x-text="scanExtras.expression"></span>
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
                                <span class="w-4">📦</span><span class="font-semibold text-yellow-600 dark:text-yellow-400" x-text="lastMember?.message"></span>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-yellow-200 dark:border-yellow-500/20 flex justify-between items-center">
                            <span class="text-xs font-semibold text-yellow-700 dark:text-yellow-500 uppercase tracking-wider">Status Membership</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">S/d: <span x-text="lastMember?.expired_date || '-'"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. ERROR ALERT --}}
            <div x-show="errorMember" x-transition.duration.300ms class="bg-red-50 dark:bg-red-500/10 border-l-4 border-red-500 p-4 rounded shadow-sm transition-colors duration-300" role="alert">
                <p class="font-bold text-red-800 dark:text-red-400">Akses Ditolak!</p>
                <p class="text-red-700 dark:text-red-300">Halo <span x-text="errorMember?.member_name"></span>, Membership kamu sudah <b>TIDAK AKTIF</b>.</p>
            </div>
        </div>

        {{-- KOLOM KANAN: MANUAL INPUT --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 h-fit transition-colors duration-300">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Input Manual</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Jika scan wajah gagal, cari nama kamu di bawah ini.</p>

            <form wire:submit.prevent="submitManualAttendance" class="space-y-6">

                {{-- Form Bawaan Filament dibiarkan polos tanpa wrapper aneh-aneh agar ngikut tema otomatis --}}
                <div>
                    {{ $this->form }}
                </div>

                <button type="submit"
                    style="background-color: #facc15;"
                    class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg shadow-sm text-sm font-bold text-gray-900 hover:opacity-90 transition-all mt-4">
                    Absen Sekarang
                </button>
            </form>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('faceRecognition', ({
                members,
                wire,
                audioUrls
            }) => ({
                isLoading: true,
                statusMessage: 'Memuat...',
                debugMessage: 'Menunggu kamera...',
                stream: null,
                faceMatcher: null,
                isProcessing: false,
                lastMember: null,
                errorMember: null,
                audioUnlocked: false,
                scanExtras: {
                    gender: null,
                    expression: null,
                    emoji: ''
                },
                audioPlayer: {
                    success: null,
                    error: null,
                    warning: null
                },

                async initFaceApi() {
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

                    try {
                        if (typeof faceapi === 'undefined') throw new Error('Face-API not loaded');

                        await Promise.all([
                            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                            faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                            faceapi.nets.faceExpressionNet.loadFromUri('/models'),
                            faceapi.nets.ageGenderNet.loadFromUri('/models')
                        ]);

                        this.faceMatcher = this.loadLabeledImages(members);
                        await this.startVideo();

                        this.isLoading = false;
                        this.debugMessage = 'Mencari Wajah...';
                        this.detectFaces();
                    } catch (e) {
                        console.error("Error Init:", e);
                        this.statusMessage = 'Gagal memuat AI. Refresh halaman.';
                    }
                },

                unlockAudio() {
                    if (this.audioUnlocked) return;
                    const sounds = [this.audioPlayer.success, this.audioPlayer.error, this.audioPlayer.warning];
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
                },

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
                        this.scanExtras = {
                            gender: null,
                            expression: null,
                            emoji: ''
                        };
                    } else {
                        this.debugMessage = response.message;
                    }

                    setTimeout(() => {
                        this.isProcessing = false;
                        this.debugMessage = 'Siap Scan...';
                    }, 5000);
                },

                async startVideo() {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: {}
                    });
                    this.$refs.video.srcObject = this.stream;
                },

                loadLabeledImages(members) {
                    if (!members) return null;
                    return new faceapi.FaceMatcher(
                        members.map(m => {
                            try {
                                return new faceapi.LabeledFaceDescriptors(m.id.toString(), [new Float32Array(typeof m.descriptor == 'string' ? JSON.parse(m.descriptor) : m.descriptor)])
                            } catch (e) {
                                return null
                            }
                        }).filter(d => d), 0.6
                    );
                },

                async detectFaces() {
                    const video = this.$refs.video;
                    const canvas = this.$refs.canvas;
                    video.addEventListener('play', () => {
                        const displaySize = {
                            width: video.clientWidth,
                            height: video.clientHeight
                        };
                        faceapi.matchDimensions(canvas, displaySize);
                        setInterval(async () => {
                            if (this.isProcessing) return;
                            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors().withFaceExpressions().withAgeAndGender();
                            const resized = faceapi.resizeResults(detections, displaySize);
                            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

                            if (this.faceMatcher) {
                                resized.forEach(d => {
                                    const match = this.faceMatcher.findBestMatch(d.descriptor);
                                    new faceapi.draw.DrawBox(d.detection.box, {
                                        label: match.toString(),
                                        boxColor: match.label == 'unknown' ? 'red' : '#eab308'
                                    }).draw(canvas);

                                    if (match.label !== 'unknown') {
                                        this.processFaceAttributes(d);
                                        this.triggerAttendance(match.label);
                                    }
                                });
                            }
                        }, 500);
                    });
                },

                processFaceAttributes(detection) {
                    const genderRaw = detection.gender;
                    const genderIndo = genderRaw === 'male' ? 'Pria' : 'Wanita';
                    const expressions = detection.expressions;
                    const sorted = Object.keys(expressions).sort((a, b) => expressions[b] - expressions[a]);
                    const dominant = sorted[0];

                    let text = 'Biasa',
                        emo = '😐';
                    switch (dominant) {
                        case 'happy':
                            text = 'Bahagia';
                            emo = '😄';
                            break;
                        case 'sad':
                            text = 'Sedih';
                            emo = '😢';
                            break;
                        case 'angry':
                            text = 'Marah';
                            emo = '😡';
                            break;
                        case 'surprised':
                            text = 'Kaget';
                            emo = '😲';
                            break;
                        case 'disgusted':
                            text = 'Jijik';
                            emo = '🤢';
                            break;
                        case 'fearful':
                            text = 'Takut';
                            emo = '😨';
                            break;
                    }
                    this.scanExtras = {
                        gender: genderIndo,
                        expression: text,
                        emoji: emo
                    };
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
                }
            }));
        });
    </script>
</x-filament-panels::page>