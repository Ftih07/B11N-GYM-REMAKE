@php
// LOGIC PERBAIKAN PATH
// 1. Ambil path gambar saat ini (contoh: "data.picture" atau "picture")
$currentPath = $getStatePath();

// 2. Pecah berdasarkan titik
$pathParts = explode('.', $currentPath);

// 3. Buang bagian terakhir (buang "picture")
array_pop($pathParts);

// 4. Masukkan nama field descriptor (masukkan "face_descriptor")
$pathParts[] = $descriptorField;

// 5. Gabung ulang (jadi "data.face_descriptor")
$descriptorPath = implode('.', $pathParts);
@endphp

<x-filament-forms::field-wrapper :id="$getId()" :label="$getLabel()" :helper-text="$getHelperText()" :state-path="$getStatePath()">

    <script src="{{ asset('js/face-api.min.js') }}"></script>

    <div
        x-data="{
            stream: null,
            image: @entangle($getStatePath()), 
            descriptorState: @entangle($descriptorPath), // <--- INI YG KITA PERBAIKI
            cameraActive: false,
            isModelLoaded: false,
            status: 'Memuat AI...',

            async init() {
                try {
                    await Promise.all([
                        faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                        faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                    ]);
                    this.isModelLoaded = true;
                    this.status = 'Siap Scan';
                } catch (e) {
                    this.status = 'Gagal memuat AI';
                    console.error(e);
                }
            },

            async startCamera() {
                this.cameraActive = true;
                this.stream = await navigator.mediaDevices.getUserMedia({ video: true });
                $refs.video.srcObject = this.stream;
            },

            async snap() {
                if (!this.isModelLoaded) {
                    alert('Tunggu sebentar, AI sedang dimuat...');
                    return;
                }

                this.status = 'Mendeteksi Wajah...';

                const video = $refs.video;
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0);

                // Deteksi Wajah
                const detection = await faceapi.detectSingleFace(canvas).withFaceLandmarks().withFaceDescriptor();

                if (detection) {
                    // a. Simpan gambar
                    this.image = canvas.toDataURL('image/jpeg');
                    
                    // b. Simpan descriptor
                    const descriptorArray = Object.values(detection.descriptor);
                    
                    // Update state Livewire (ini akan masuk ke data.face_descriptor)
                    this.descriptorState = JSON.stringify(descriptorArray);

                    this.stopCamera();
                    this.status = 'Wajah Terdeteksi ✅';
                } else {
                    alert('Wajah tidak terdeteksi!');
                    this.status = 'Coba Lagi';
                }
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                }
                this.cameraActive = false;
            },

            removePhoto() {
                this.image = null;
                this.descriptorState = null;
                this.status = 'Siap Scan';
            }
        }"
        x-init="init()">
        <div x-show="!isModelLoaded" class="text-xs text-orange-600 mb-2 font-bold animate-pulse" x-text="status"></div>
        <div x-show="isModelLoaded" class="text-xs text-green-600 mb-2 font-bold" x-text="status"></div>

        <div x-show="!image">
            <div x-show="cameraActive" class="relative">
                <video x-ref="video" autoplay playsinline class="w-full rounded-lg border border-gray-300"></video>

                <button type="button" @click="snap()" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-2 rounded-full shadow-lg hover:bg-red-700 flex items-center gap-2">
                    <span>📸</span> Jepret & Scan AI
                </button>
            </div>

            <div x-show="!cameraActive" class="text-center p-6 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                <button type="button" @click="startCamera()" :disabled="!isModelLoaded" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 disabled:opacity-50">
                    Buka Kamera
                </button>
            </div>
        </div>

        <div x-show="image" class="text-center">
            <img :src="image" class="w-full max-w-md mx-auto rounded-lg border border-gray-300 mb-2">
            <div class="text-xs text-green-600 font-mono mb-2">Data Biometrik Tersimpan ✅</div>
            <button type="button" @click="removePhoto()" class="text-red-600 text-sm hover:underline">
                Hapus & Foto Ulang
            </button>
        </div>
    </div>
</x-filament-forms::field-wrapper>