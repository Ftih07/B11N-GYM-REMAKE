<!DOCTYPE html>
<html lang="id">

@php
    // Tema per cabang (warna & gambar mengikuti website B11N / K1NG). Kalau tidak dikirim, pakai B11N.
    $theme = $theme ?? [
        'accent' => '#dc030a',
        'dark' => '#87060c',
        'rgb' => '220, 3, 10',
        'hero' => 'assets/home/biin-gym.jpg',
        'logo' => 'assets/Logo/biin.png',
        'brand' => 'B11N GYM',
        'tagline' => 'GIVE UP OR GET UP',
    ];
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0a0a0a">
    <title>{{ $title ?? 'Absen Mandiri' }}</title>
    <link rel="icon" type="image/png" href="{{ asset($theme['logo']) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- CSS biasa (bukan utility Tailwind) supaya tidak bergantung pada hasil build Tailwind.
         Ukuran besar & kontras tinggi karena dipakai orang dari semua usia. --}}
    <style>
        :root {
            --accent: {{ $theme['accent'] }};
            --accent-dark: {{ $theme['dark'] }};
            --accent-soft: rgba({{ $theme['rgb'] }}, .18);
            --bg: #0a0a0a;
            --card: #171717;
            --field: #0a0a0a;
            --text: #fafafa;
            --muted: #a3a3a3;
            --line: #2e2e2e;
            --ok: #4ade80;
            --ok-bg: rgba(34, 197, 94, .18);
            --warn: #fde047;
            --warn-bg: rgba(234, 179, 8, .18);
            --err: #fca5a5;
            --err-bg: rgba(239, 68, 68, .2);
            --head-font: "Oswald", "Arial Narrow", sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 17px;
            line-height: 1.5;
            -webkit-text-size-adjust: 100%;
        }

        /* Latar: foto cabang yang digelapkan, seperti hero di website */
        .wrap {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 16px;
            background-image:
                linear-gradient(rgba(0, 0, 0, .55), rgba(0, 0, 0, .55)),
                linear-gradient(to left, rgba(0, 0, 0, .85), rgba(0, 0, 0, .25), rgba(0, 0, 0, .85)),
                url("{{ asset($theme['hero']) }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .card {
            width: 100%;
            max-width: 480px;
            background: rgba(23, 23, 23, .96);
            border: 1px solid var(--line);
            border-top: 4px solid var(--accent);
            border-radius: 8px;
            padding: 20px;
            margin-top: 8px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .5);
        }

        .head {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 16px;
            margin-bottom: 8px;
            border-bottom: 1px solid var(--line);
        }

        .logo {
            flex: none;
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .eyebrow {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        h1 {
            margin: 0;
            font-family: var(--head-font);
            font-size: 28px;
            font-weight: 700;
            line-height: 1.15;
            text-transform: uppercase;
            color: var(--accent);
        }

        .tagline {
            margin: 2px 0 0;
            font-size: 12px;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--muted);
        }

        h2 {
            margin: 14px 0 4px;
            font-family: var(--head-font);
            font-size: 24px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .lead {
            margin: 16px 0 0;
            color: var(--muted);
        }

        label.field {
            display: block;
            margin-top: 18px;
            font-weight: 600;
        }

        input[type="text"] {
            width: 100%;
            margin-top: 6px;
            padding: 14px 16px;
            font-family: inherit;
            font-size: 19px;
            color: var(--text);
            background: var(--field);
            border: 2px solid var(--line);
            border-radius: 4px;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .hint {
            margin: 6px 0 0;
            font-size: 14px;
            color: var(--muted);
        }

        .err {
            margin: 8px 0 0;
            padding: 10px 12px;
            border-radius: 4px;
            background: var(--err-bg);
            color: var(--err);
            font-size: 15px;
            font-weight: 600;
        }

        .big {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 66px;
            margin-top: 14px;
            padding: 14px 18px;
            border: 2px solid var(--line);
            border-radius: 4px;
            background: transparent;
            color: var(--text);
            font-family: var(--head-font);
            font-size: 21px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: .2s;
        }

        .big:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .big small {
            font-family: "Poppins", sans-serif;
            font-size: 13px;
            font-weight: 400;
            letter-spacing: .04em;
            text-transform: none;
            color: var(--muted);
        }

        .big-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .big-primary:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
        }

        .big[disabled] {
            opacity: .6;
            cursor: wait;
        }

        .big:focus-visible,
        .link:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 2px;
        }

        .link {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            background: transparent;
            border: 0;
            color: var(--muted);
            font-family: inherit;
            font-size: 16px;
            text-decoration: underline;
            cursor: pointer;
        }

        .link:hover {
            color: var(--text);
        }

        .group-title {
            margin: 20px 0 0;
            font-weight: 600;
        }

        .choices {
            display: grid;
            gap: 10px;
            margin-top: 8px;
        }

        .choice {
            position: relative;
            display: block;
        }

        .choice input {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            opacity: 0;
            cursor: pointer;
        }

        .choice span {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 54px;
            padding: 10px 14px;
            border: 2px solid var(--line);
            border-radius: 4px;
            font-weight: 500;
        }

        .choice span::before {
            content: "";
            flex: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid var(--muted);
        }

        .choice input:checked+span {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .choice input:checked+span::before {
            border-color: var(--accent);
            background: radial-gradient(circle, var(--accent) 42%, transparent 46%);
        }

        .choice input:focus-visible+span {
            outline: 3px solid var(--accent);
            outline-offset: 2px;
        }

        .result {
            text-align: center;
            padding: 12px 0 4px;
        }

        .result .icon {
            width: 96px;
            height: 96px;
            margin: 0 auto 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 54px;
            font-weight: 800;
        }

        .result.success .icon {
            background: var(--ok-bg);
            color: var(--ok);
        }

        .result.warning .icon {
            background: var(--warn-bg);
            color: var(--warn);
        }

        .result.error .icon {
            background: var(--err-bg);
            color: var(--err);
        }

        .result h2 {
            font-size: 28px;
        }

        .result p {
            margin: 6px 0 0;
            font-size: 19px;
        }

        .foot {
            margin: 18px 0 0;
            font-size: 13px;
            text-align: center;
            color: var(--muted);
        }
    </style>

    @livewireStyles
</head>

<body>
    <main class="wrap">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
