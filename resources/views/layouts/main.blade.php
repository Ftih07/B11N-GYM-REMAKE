<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO & Meta Tags Dinamis --}}
    <title>@yield('title', 'B1NG Empire - Premium Gym di Purwokerto')</title>
    <meta name="description" content="@yield('meta_description', 'Pusat kebugaran terlengkap B1NG Empire Purwokerto. Fasilitas premium dengan harga bersahabat.')">
    <meta name="keywords" content="@yield('meta_keywords', 'gym purwokerto, fitness purwokerto, b11n gym, k1ng gym')">
    <meta name="author" content="B1NG EMPIRE">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph / Social Media (Biar preview link cakep) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'B1NG Empire')">
    <meta property="og:description" content="@yield('meta_description', 'Pusat kebugaran terlengkap B1NG Empire Purwokerto.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="B1NG EMPIRE">
    <meta property="og:image" content="@yield('meta_image', asset('assets/default-image-og.webp'))">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'B1NG Empire')">
    <meta name="twitter:description" content="@yield('meta_description', 'Pusat kebugaran terlengkap B1NG Empire Purwokerto.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('assets/default-image-og.webp'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/empire.png') }}">

    {{-- Tailwind & Fonts (Tetap Biarkan Utuh Seperti Punya Abang) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700;900&family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        condensed: ['Roboto Condensed', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    @stack('styles')

    {{-- Stack untuk JSON-LD Schema --}}
    @stack('json-ld')
</head>

<body class="bg-gray-50 antialiased">
    @yield('content')
    @stack('scripts')
</body>

</html>