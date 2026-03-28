<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan - B1NG Empire</title>

    {{-- Stylesheets --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen text-gray-800">

    @include('components.global-loader')
    @include('components.navbar-cta')

    <main class="flex-grow flex items-center justify-center py-12 md:py-24 px-4 sm:px-6">

        <div class="bg-white p-6 sm:p-10 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100 relative overflow-hidden">

            <div class="absolute top-0 left-0 w-full h-1.5 bg-[#E31E24]"></div>

            <div class="text-center mb-8">
                <img src="/assets/Logo/empire.png" alt="B1NG Empire Logo" class="h-20 w-20 mx-auto mb-5 p-2 bg-[#0D0D0D] rounded-full shadow-lg border border-gray-200">
                <h1 class="text-3xl sm:text-4xl font-black text-[#0D0D0D] tracking-tighter uppercase mb-2">
                    LOGIN <span class="text-[#E31E24]">KARYAWAN</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-500 font-medium">
                    Akses dashboard internal B1NG Empire.<br class="hidden sm:block">
                    Silakan masuk menggunakan kredensial Anda.
                </p>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-[#E31E24] text-[#E31E24] p-4 rounded-xl mb-6 shadow-sm" role="alert">
                <p class="font-black text-sm uppercase tracking-wide"><i class="ri-error-warning-fill mr-1"></i> Gagal Login!</p>
                <ul class="text-xs mt-1.5 list-disc list-inside font-medium">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('employee.login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label for="email" class="text-[11px] font-bold text-gray-500 uppercase tracking-widest block">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="ri-mail-line text-lg"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-xl text-[#0D0D0D] bg-gray-50 focus:ring-2 focus:ring-[#E31E24] focus:border-[#E31E24] focus:bg-white transition-colors"
                            placeholder="karyawan@bingempire.com">
                    </div>
                </div>

                <div class="space-y-1.5" x-data="{ show: false }">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Password</label>
                        <a href="https://wa.me/6283194288423" class="text-[11px] font-bold text-[#E31E24] hover:text-red-700 transition">Lupa password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="ri-lock-password-line text-lg"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" id="password" required
                            class="w-full pl-10 pr-12 py-3 text-sm border border-gray-300 rounded-xl text-[#0D0D0D] bg-gray-50 focus:ring-2 focus:ring-[#E31E24] focus:border-[#E31E24] focus:bg-white transition-colors"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#E31E24] focus:outline-none transition">
                            <i :class="show ? 'ri-eye-line text-lg' : 'ri-eye-off-line text-lg'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center pt-1">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-[#E31E24] focus:ring-[#E31E24] border-gray-300 rounded cursor-pointer">
                    <label for="remember" class="ml-2 text-sm font-semibold text-gray-600 hover:text-[#0D0D0D] cursor-pointer transition">Ingat saya di perangkat ini</label>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full bg-[#E31E24] text-white py-3.5 rounded-xl font-black text-base uppercase tracking-wider hover:bg-red-700 transition duration-300 shadow-lg shadow-red-500/30 flex items-center justify-center gap-2">
                        Masuk Sekarang <i class="ri-login-circle-line"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('components.footer-compact')

</body>

</html>