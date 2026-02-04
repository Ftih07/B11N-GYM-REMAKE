<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - B1NG Empire</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/empire.png') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        header: ['Oswald', 'sans-serif'],
                        body: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        dark: '#0a0a0a',
                        card: '#171717',
                        primary: '#DC2626',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-dark text-gray-200 font-body min-h-screen">

    @include('components.navbar')

    <div class="container mx-auto p-6 max-w-2xl">

        @if(session('success'))
        <div class="bg-green-900/50 border border-green-600 text-green-200 p-4 rounded mb-6 text-center font-bold tracking-wide">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-card border border-neutral-800 p-8 rounded-lg shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-orange-600"></div>

            <div class="flex flex-col items-center mb-8 relative z-10">
                <div class="relative">
                    <img src="{{ $user->profile_picture ?? 'https://via.placeholder.com/150' }}"
                        class="w-32 h-32 rounded-full border-4 border-neutral-800 shadow-xl object-cover">
                    <div class="absolute bottom-1 right-1 bg-white p-1 rounded-full shadow-md" title="Terhubung dengan Google">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">
                    </div>
                </div>

                <h2 class="mt-4 text-3xl font-header font-bold text-white uppercase tracking-wide">{{ $user->name }}</h2>
                <p class="text-gray-500 font-medium">{{ $user->email }}</p>

                @php
                $badgeClass = ($member && $member->status === 'active')
                ? 'bg-green-900/50 text-green-400 border-green-800'
                : 'bg-red-900/50 text-red-400 border-red-800';
                $badgeText = ($member && $member->status === 'active') ? 'ACTIVE MEMBER' : 'INACTIVE / GUEST';
                @endphp

                <span class="mt-3 {{ $badgeClass }} border text-[10px] font-bold px-4 py-1 rounded uppercase tracking-widest">
                    {{ $badgeText }}
                </span>
            </div>

            @if($member)
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </span>
                        <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
                            class="w-full bg-neutral-900 border border-neutral-700 rounded pl-10 p-3 text-white focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition placeholder-gray-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Alamat Domisili</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </span>
                        <textarea name="address" rows="3"
                            class="w-full bg-neutral-900 border border-neutral-700 rounded pl-10 p-3 text-white focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition placeholder-gray-600">{{ old('address', $member->address) }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-800 flex flex-col md:flex-row justify-between items-center gap-4">
                    <button type="submit" class="w-full md:w-auto bg-primary hover:bg-red-700 text-white font-header font-bold uppercase tracking-wider py-3 px-8 rounded shadow-lg shadow-red-900/20 transition transform hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>

                    <button form="logout-form" type="button" onclick="document.getElementById('logout-form').submit();"
                        class="text-gray-500 hover:text-white text-sm font-medium transition flex items-center gap-2 px-4 py-2 hover:bg-neutral-800 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout Akun
                    </button>
                </div>
            </form>
            @else
            <div class="text-center bg-yellow-900/20 border border-yellow-700/50 p-6 rounded-lg mt-6">
                <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-yellow-500 font-bold mb-1">DATA MEMBER TIDAK DITEMUKAN</p>
                <p class="text-gray-400 text-sm">Email ini belum terdaftar di sistem gym kami. Hubungi resepsionis untuk aktivasi.</p>
            </div>
            <div class="mt-8 text-center">
                <button form="logout-form" onclick="document.getElementById('logout-form').submit();" class="text-gray-500 hover:text-white text-sm font-bold uppercase tracking-wider transition">← Logout</button>
            </div>
            @endif

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>

        <div class="mt-8 text-center text-xs text-gray-600 font-header tracking-widest uppercase">
            B1NG Empire Member Area &copy; {{ date('Y') }}
        </div>
    </div>
</body>

</html>