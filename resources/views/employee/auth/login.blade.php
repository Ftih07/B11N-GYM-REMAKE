<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan - B1NG Empire - Premium Gym & Residence Purwokerto</title>

    {{-- Stylesheets --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen text-gray-200">

    @include('components.global-loader')

    <!-- NAVIGATION BAR -->
    @include('components.navbar-cta')

    <main class="flex-grow flex items-center justify-center py-40 px-4">

        <div class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-lg border border-gray-200">

            <div class="text-center mb-10">
                <img src="/assets/Logo/empire.png" alt="B1NG Empire Logo" class="h-20 w-20 mx-auto mb-5 p-2 bg-black rounded-full shadow-md">
                <h1 class="text-4xl font-extrabold text-[#0D0D0D] tracking-tighter uppercase mb-2">
                    LOGIN <span class="text-[#E31E24]">KARYAWAN</span>
                </h1>
                <p class="text-lg text-gray-600 font-medium">
                    Akses dashboard internal B1NG Empire.<br>
                    Silakan masuk menggunakan kredensial Anda.
                </p>
            </div>

            @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-[#E31E24] text-[#E31E24] p-4 rounded-lg mb-8" role="alert">
                <p class="font-bold">Opps, sepertinya ada yang salah!</p>
                <ul class="text-sm mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('employee.login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="text-sm font-semibold text-[#0D0D0D] uppercase tracking-wide">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 focus:ring-2 focus:ring-[#E31E24] focus:border-[#E31E24] placeholder-gray-400 shadow-inner"
                            placeholder="karyawan@bingempire.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-sm font-semibold text-[#0D0D0D] uppercase tracking-wide">Password</label>
                        <a href="https://wa.me/6283194288423" class="text-sm font-semibold text-[#E31E24] hover:text-[#0D0D0D]">Lupa password?</a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" id="password" required
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 focus:ring-2 focus:ring-[#E31E24] focus:border-[#E31E24] placeholder-gray-400 shadow-inner"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="h-6 w-6" x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="h-6 w-6" x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L5.636 5.636m10.122 10.122l4.242 4.242M14.122 14.122L18.364 18.364" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember"
                        class="h-5 w-5 text-[#E31E24] focus:ring-[#E31E24] border-gray-300 rounded">
                    <label for="remember" class="ml-3 text-sm font-medium text-gray-600 hover:text-[#0D0D0D]">Ingat saya di perangkat ini</label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full bg-[#E31E24] text-white py-4 rounded-xl font-extrabold text-xl uppercase tracking-wider hover:bg-[#0D0D0D] transition duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-red-300">
                        Masuk Sekarang
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- FOOTER -->
    @include('components.footer-bing-empire')

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>