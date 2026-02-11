<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Survei Pelanggan - B11N Gym & K1NG Gym</title>
    <meta name="description" content="Formulir survei kepuasan pelanggan B11N Gym. Bantu kami meningkatkan layanan dengan memberikan penilaian Anda.">
    <meta name="author" content="B1NG EMPIRE IT Support">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#dc030a">

    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/empire.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#dc030a',
                        'primary-dark': 'rgb(135, 6, 12)',
                        secondary: '#0a0a0a',
                        textDark: '#0a0a0a',
                        textLight: '#737373',
                        extraLight: '#f5f5f5',
                    },
                    fontFamily: {
                        heading: ['Oswald', 'sans-serif'],
                        body: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        input[type="radio"] {
            accent-color: #dc030a;
        }

        input[type="checkbox"] {
            accent-color: #dc030a;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col">

    <div class="w-full z-50">
        @include('components.navbar-cta')
    </div>

    <main class="flex-grow flex items-center justify-center p-4 py-8 mt-20">

        <div class="bg-white shadow-2xl w-full max-w-4xl overflow-hidden rounded-lg border border-gray-200">
            {{-- Header --}}
            <div class="bg-primary px-6 py-5">
                <h1 class="text-white text-2xl font-heading font-bold tracking-wider uppercase">Survei Pelanggan</h1>
                <p class="text-white/80 text-sm mt-1 font-body">Nilai fasilitas, kebersihan, dan layanan kami</p>
            </div>

            <div class="p-6">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('survey.store') }}" method="POST" x-data="{ isMember: false }">
                    @csrf

                    {{-- Row 1: Nama, Email, Nomor Whatsapp --}}
                    <div class="border-2 border-gray-200 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Nama anda</label>
                                <input type="text" name="name" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                                    placeholder="Contoh: Alex">
                            </div>
                            <div>
                                <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Email</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                                    placeholder="Contoh: Alex@example.com">
                            </div>
                            <div>
                                <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Nomor Whatsapp</label>
                                <input type="text" name="phone" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                                    placeholder="Contoh: 081234567890">
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Member Checkbox + Tujuan Utama Fitness --}}
                    <div class="border-2 border-gray-200 rounded-lg p-4 mb-6">
                        <div class="text-center mb-3">
                            <label class="block text-primary text-sm font-semibold font-heading uppercase tracking-wide">Tujuan Utama Fitness</label>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <label class="inline-flex items-center cursor-pointer whitespace-nowrap">
                                <input type="checkbox" name="is_membership" value="1" x-model="isMember" class="form-checkbox h-5 w-5">
                                <span class="ml-2 text-textDark font-semibold font-body">Saya adalah member aktif</span>
                            </label>
                            <div class="flex-1">
                                <input type="text" name="fitness_goal" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                                    placeholder="Contoh: Alex">
                            </div>
                        </div>

                        {{-- Member Detail Popup --}}
                        <div x-show="isMember" x-transition class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Sudah berapa lama jadi member?</label>
                                    <input type="text" name="member_duration" placeholder="Contoh: 3 Bulan"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body">
                                </div>
                                <div>
                                    <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Kemungkinan perpanjang (1-5)?</label>
                                    <select name="renewal_chance"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body">
                                        <option value="">Pilih...</option>
                                        <option value="1">1 - Sangat Kecil</option>
                                        <option value="3">3 - Ragu-ragu</option>
                                        <option value="5">5 - Pasti Perpanjang</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 3: Kebersihan Rating 1-10 --}}
                    <div class="border-2 border-gray-200 rounded-lg p-4 mb-6">
                        <label class="block text-primary text-sm font-semibold mb-3 font-heading uppercase tracking-wide">Kebersihan</label>
                        <div class="flex items-center justify-between gap-1">
                            <span class="text-xs text-primary font-semibold font-body whitespace-nowrap">Sangat Buruk</span>
                            <div class="flex gap-3 md:gap-6 flex-1 justify-center">
                                @for($i = 1; $i <= 10; $i++)
                                <label class="flex flex-col items-center cursor-pointer">
                                    <input type="radio" name="rating_cleanliness" value="{{ $i }}" class="mb-1" required>
                                    <span class="text-xs text-textDark font-body">{{ $i }}</span>
                                </label>
                                @endfor
                            </div>
                            <span class="text-xs text-primary font-semibold font-body whitespace-nowrap">Sangat Baik</span>
                        </div>
                    </div>

                    {{-- Row 4: Rating Alat 1-10 --}}
                    <div class="border-2 border-gray-200 rounded-lg p-4 mb-6">
                        <label class="block text-primary text-sm font-semibold mb-3 font-heading uppercase tracking-wide">Rating Alat</label>
                        <div class="flex items-center justify-between gap-1">
                            <span class="text-xs text-primary font-semibold font-body whitespace-nowrap">Sangat Buruk</span>
                            <div class="flex gap-3 md:gap-6 flex-1 justify-center">
                                @for($i = 1; $i <= 10; $i++)
                                <label class="flex flex-col items-center cursor-pointer">
                                    <input type="radio" name="rating_equipment" value="{{ $i }}" class="mb-1" required>
                                    <span class="text-xs text-textDark font-body">{{ $i }}</span>
                                </label>
                                @endfor
                            </div>
                            <span class="text-xs text-primary font-semibold font-body whitespace-nowrap">Sangat Baik</span>
                        </div>
                    </div>

                    {{-- Row 5: NPS Score 1-10 --}}
                    <div class="border-2 border-gray-200 rounded-lg p-4 mb-6">
                        <label class="block text-primary text-sm font-semibold mb-3 font-heading uppercase tracking-wide">Seberapa mungkin anda merekomendasikan kami?</label>
                        <div class="flex items-center justify-between gap-1">
                            <span class="text-xs text-primary font-semibold font-body whitespace-nowrap">Sangat Buruk</span>
                            <div class="flex gap-3 md:gap-6 flex-1 justify-center">
                                @for($i = 1; $i <= 10; $i++)
                                <label class="flex flex-col items-center cursor-pointer">
                                    <input type="radio" name="nps_score" value="{{ $i }}" class="mb-1" required>
                                    <span class="text-xs text-textDark font-body">{{ $i }}</span>
                                </label>
                                @endfor
                            </div>
                            <span class="text-xs text-primary font-semibold font-body whitespace-nowrap">Sangat Baik</span>
                        </div>
                    </div>

                    {{-- Row 6: Kritik dan Saran --}}
                    <div class="border-2 border-gray-200 rounded-lg p-4 mb-6">
                        <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Kritik dan Saran</label>
                        <textarea name="feedback" rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body"
                            placeholder="Contoh: Kabel putus, baut kendor ..."></textarea>
                    </div>

                    {{-- Row 7: Tertarik Promo --}}
                    <div class="border-2 border-gray-200 rounded-lg p-4 mb-8">
                        <label class="block text-primary text-sm font-semibold mb-2 font-heading uppercase tracking-wide">Tertarik Promo</label>
                        <select name="promo_interest" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-primary bg-extraLight font-body">
                            <option value="none">Belum tertarik saat ini</option>
                            <option value="paket_a">Paket A (Bayar 6 Free 2)</option>
                            <option value="paket_b">Paket B (Bayar 9 Free 3)</option>
                            <option value="paket_c">Paket C (Bayar 12 Free 4 - Best Value!)</option>
                        </select>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full bg-primary text-white font-heading font-bold py-4 rounded-lg hover:bg-white hover:text-primary border-2 border-primary transition duration-300 shadow-lg text-lg tracking-wider uppercase">
                        Kirim Survey
                    </button>
                </form>
            </div>
        </div>
    </main>

    <div class="w-full mt-auto">
        @include('components.footer-bing-empire')
    </div>

</body>

</html>