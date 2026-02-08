<x-filament-panels::page>
    <script src="{{ asset('js/face-api.min.js') }}"></script>

    <div
        class="grid grid-cols-1 md:grid-cols-2 gap-6"
        {{-- Event Listener --}}
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

        {{-- KOLOM KIRI --}}
        <div class="space-y-6">
            {{-- 1. SCANNER --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col items-center relative">
                <h2 class="text-xl font-bold mb-4">Scan Wajah</h2>

                {{-- [HAPUS] Overlay "Klik Disini" SUDAH DIHILANGKAN --}}
                {{-- Gantinya ada di logic Javascript di bawah --}}

                {{-- Area Kamera --}}
                <div class="relative w-full overflow-hidden rounded-lg mb-4 bg-black"
                    style="height: 400px; min-height: 400px; background-color: #000; position: relative;">

                    <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center z-30 flex-col bg-black/80 text-white">
                        <span x-text="statusMessage">Memuat...</span>
                    </div>

                    <video x-ref="video" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover; position: absolute; z-index: 10;"></video>
                    <canvas x-ref="canvas" style="width: 100%; height: 100%; position: absolute; z-index: 20; pointer-events: none;"></canvas>
                </div>

                {{-- Debug Text --}}
                <div class="w-full bg-gray-50 p-3 rounded-lg border border-gray-200 text-center">
                    <p x-text="debugMessage" class="font-mono text-sm text-blue-700 font-bold animate-pulse"></p>
                </div>
            </div>

            {{-- 2. HASIL MEMBER (CARD) --}}
            <div x-show="lastMember" x-transition.duration.500ms class="bg-white p-6 rounded-xl shadow-lg border border-green-200 bg-green-50">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                    {{-- Foto --}}
                    <div class="flex-shrink-0">
                        <template x-if="lastMember && lastMember.photo_url">
                            <img :src="lastMember.photo_url" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                        </template>
                        <template x-if="lastMember && !lastMember.photo_url">
                            <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center text-4xl shadow-md">👤</div>
                        </template>
                    </div>

                    {{-- Info Detail --}}
                    <div class="flex-grow">
                        <h3 class="text-xl font-bold text-gray-900" x-text="lastMember?.member_name"></h3>

                        {{-- Badge Gender & Mood --}}
                        <div x-show="scanExtras.gender" class="flex flex-wrap gap-2 mt-1 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="scanExtras.gender === 'Pria' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'">
                                <span x-text="scanExtras.gender === 'Pria' ? '♂️' : '♀️'" class="mr-1"></span>
                                <span x-text="scanExtras.gender"></span>
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <span x-text="scanExtras.emoji" class="mr-1"></span>
                                <span x-text="scanExtras.expression"></span>
                            </span>
                        </div>

                        <div class="text-sm text-gray-700 mt-2 space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="w-4">📞</span><span x-text="lastMember?.phone"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4">📧</span><span x-text="lastMember?.email"></span>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-green-200 flex justify-between items-center">
                            <span class="text-xs font-semibold text-green-700 uppercase tracking-wider">Status Membership</span>
                            <span class="text-sm font-bold text-gray-800">S/d: <span x-text="lastMember?.expired_date"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. ERROR ALERT --}}
            <div x-show="errorMember" x-transition.duration.300ms class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md" role="alert">
                <p class="font-bold">Akses Ditolak!</p>
                <p>Halo <span x-text="errorMember?.member_name"></span>, Membership kamu sudah <b>TIDAK AKTIF</b>.</p>
            </div>
        </div>

        {{-- KOLOM KANAN: MANUAL INPUT --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
            <h2 class="text-xl font-bold mb-4">Input Manual</h2>
            <p class="text-sm text-gray-600 mb-6">Jika scan wajah gagal, cari nama kamu di bawah ini.</p>

            <form wire:submit.prevent="submitManualAttendance" class="space-y-4">
                {{ $this->form }}
                <button type="submit"
                    class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all mt-4">
                    Absen Sekarang
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('faceRecognition', ({
                members,
                wire,
                audioUrls
            }) => ({
                // --- STATE VARIABLES ---
                isLoading: true,
                statusMessage: 'Memuat...',
                debugMessage: 'Menunggu kamera...',

                stream: null,
                faceMatcher: null,
                isProcessing: false,
                lastMember: null,
                errorMember: null,
                audioUnlocked: false, // Variable baru buat tracking status audio

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

                // --- 1. INISIALISASI ---
                async initFaceApi() {
                    // SETUP GLOBAL LISTENER (TRIK NINJA)
                    // Begitu user klik mouse atau tekan tombol APAPUN di halaman ini, audio aktif.
                    ['click', 'touchstart', 'keydown'].forEach(evt =>
                        document.addEventListener(evt, () => this.unlockAudio(), {
                            once: true
                        })
                    );

                    this.audioPlayer.success = new Audio(audioUrls.success);
                    this.audioPlayer.error = new Audio(audioUrls.error);
                    this.audioPlayer.warning = new Audio(audioUrls.warning);

                    // Set Volume
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

                // --- 2. UNLOCK AUDIO (DIJALANKAN DI BACKGROUND) ---
                unlockAudio() {
                    if (this.audioUnlocked) return; // Kalau udah aktif, stop.

                    const sounds = [this.audioPlayer.success, this.audioPlayer.error, this.audioPlayer.warning];
                    sounds.forEach(sound => {
                        if (sound) {
                            sound.volume = 0; // Silent play
                            sound.play().then(() => {
                                sound.pause();
                                sound.currentTime = 0;
                                sound.volume = 0.8; // Balikin volume
                            }).catch(e => {
                                // Kalau error (karena belum klik), biarin aja silent error
                                // Nanti akan coba lagi pas user klik sesuatu
                            });
                        }
                    });
                    this.audioUnlocked = true;
                },

                // --- 3. HELPER PLAY AUDIO ---
                playAudio(type) {
                    // Coba unlock lagi jaga-jaga kalau user belum pernah klik sama sekali
                    if (!this.audioUnlocked) this.unlockAudio();

                    if (this.audioPlayer[type]) {
                        this.audioPlayer[type].currentTime = 0;
                        this.audioPlayer[type].play().catch(e => {
                            console.error("Gagal play:", e);
                            // Kalau masih diblokir browser, ya sudah nasib. 
                            // Tapi visual tetap jalan normal.
                        });
                    }
                },

                // --- 4. HANDLE HASIL ---
                handleResult(response) {
                    console.log('Result:', response);

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

                // ... FUNGSI LAIN (startVideo, loadLabeledImages, detectFaces) ...
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
                                        boxColor: match.label == 'unknown' ? 'red' : 'lime'
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
                        default:
                            text = 'Netral';
                            emo = '😐';
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