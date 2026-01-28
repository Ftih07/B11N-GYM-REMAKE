<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO & Meta Tags --}}
    <title>@yield('title', 'B1NG Empire')</title>
    <meta name="description" content="@yield('meta_description', 'Pusat kebugaran B1NG Empire Purwokerto')">
    <meta name="keywords" content="@yield('meta_keywords', 'gym, fitness, purwokerto')">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/empire.png') }}">

    {{-- 1. TAILWIND CSS (Versi Script - Support Aspect Ratio & JIT) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- 2. REMIX ICON (Wajib untuk Icon) --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    {{-- 3. GOOGLE FONTS (Opsional - Agar font lebih Industrial/Keren) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700;900&family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    {{-- Konfigurasi Font Tailwind agar pakai font di atas --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        condensed: ['Roboto Condensed', 'sans-serif'], // Untuk Judul Besar
                    }
                }
            }
        }
    </script>

    {{-- Stack untuk CSS tambahan jika ada --}}
    @stack('styles')

    {{-- Stack untuk JSON-LD Schema (SEO) --}}
    @stack('json-ld')
</head>

<body class="bg-gray-50 antialiased">

    {{-- Navbar bisa ditaruh di sini (include) --}}
    {{-- @include('partials.navbar') --}}

    {{-- Content Utama --}}
    @yield('content')

    {{-- Footer bisa ditaruh di sini (include) --}}
    {{-- @include('partials.footer') --}}

    {{-- Stack untuk Script JS tambahan --}}
    @stack('scripts')
</body>

</html>