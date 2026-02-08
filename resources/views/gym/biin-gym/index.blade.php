<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	{{-- 1. TITLE --}}
	<title>B11N Gym Purwokerto - Pusat Fitness & Gym Terlengkap & Murah</title>

	{{-- 2. META DESCRIPTION --}}
	<meta name="description" content="Cari gym di Purwokerto? B11N Gym Arcawinangun menawarkan alat fitness lengkap, AC, Karaoke, dan Free Personal Trainer. Harian mulai Rp 10rb! Gabung sekarang.">

	{{-- 3. KEYWORDS --}}
	<meta name="keywords" content="gym purwokerto, fitness center purwokerto, tempat gym murah purwokerto, gym arcawinangun, fitness muslimah purwokerto, member gym harian, B11N Gym">

	<meta name="author" content="B11N Gym Management">
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="{{ url()->current() }}">

	{{-- 4. GEO TAGS --}}
	<meta name="geo.region" content="ID-JT" />
	<meta name="geo.placename" content="Purwokerto" />
	<meta name="geo.position" content="-7.4243;109.2391" />
	<meta name="ICBM" content="-7.4243, 109.2391" />

	{{-- 5. OPEN GRAPH --}}
	<meta property="og:type" content="business.business">
	<meta property="og:title" content="B11N Gym Purwokerto - Give Up or Get Up">
	<meta property="og:description" content="Gym premium harga rakyat. Fasilitas lengkap, nyaman, dan strategis di Arcawinangun.">
	<meta property="og:url" content="{{ url()->current() }}">
	<meta property="og:image" content="{{ asset('assets/home/biin-gym.jpg') }}">
	<meta property="og:site_name" content="B11N Gym">

	{{-- 6. SCHEMA MARKUP --}}
	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "ExerciseGym",
			"name": "B11N Gym Purwokerto",
			"image": "{{ asset('assets/home/biin-gym.jpg') }}",
			"telephone": "+6283194288423",
			"url": "{{ url('/') }}",
			"address": {
				"@type": "PostalAddress",
				"streetAddress": "Jl. Masjid Baru, Arcawinangun",
				"addressLocality": "Purwokerto Timur",
				"addressRegion": "Jawa Tengah",
				"postalCode": "53113",
				"addressCountry": "ID"
			},
			"geo": {
				"@type": "GeoCoordinates",
				"latitude": -7.4243,
				"longitude": 109.2391
			},
			"openingHoursSpecification": [{
				"@type": "OpeningHoursSpecification",
				"dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
				"opens": "07:00",
				"closes": "21:00"
			}],
			"priceRange": "Rp 10.000 - Rp 80.000"
		}
	</script>

	{{-- Favicon --}}
	<link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/biin.png'))">

	{{-- CSS Libraries (Bootstrap First to avoid override issues) --}}
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

	{{-- TAILWIND CDN & CONFIGURATION --}}
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		tailwind.config = {
			theme: {
				extend: {
					fontFamily: {
						header: ['Oswald', 'sans-serif'],
						body: ['Poppins', 'sans-serif'],
						sans: ['Poppins', 'sans-serif'],
						heading: ['Oswald', 'sans-serif'],
					},
					colors: {
						primary: '#dc030a',
						'primary-dark': 'rgb(135, 6, 12)',
						'text-dark': '#0a0a0a',
						'text-light': '#737373',
						'brand-red': '#dc030a',
						'brand-orange': '#f97316',
						'brand-black': '#0a0a0a',
						'brand-gray': '#171717',
					}
				}
			},
			// Penting agar tidak merusak komponen Bootstrap tertentu (opsional, bisa dihapus jika tampilan aman)
			corePlugins: {
				// preflight: false, // Uncomment jika tombol/input Bootstrap jadi aneh
			}
		}
	</script>

	{{-- CUSTOM CSS (Converted for CDN) --}}
	<style type="text/tailwindcss">
		/* Global & Variables */
        :root {
            --primary-color: #dc030a;
            --primary-color-dark: rgb(135, 6, 12);
            --text-dark: #0a0a0a;
            --text-light: #737373;
            --extra-light: #e5e5e5;
            --white: #ffffff;
            --max-width: 1200px;
            --header-font: "Oswald", sans-serif;

            --color: #9176ff;
            --dark-color: #2b2b2b;
            --dark-icon-color: #fff;
            --light-color: #f7f7f7;
            --light-icon-color: #ffde59;
        }
		
        /* Gallery */
        .gallery-thumbnail {
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .gallery-thumbnail:hover {
            transform: scale(1.05);
        }

        /* Modals */
        .modal-body img {
            max-height: 70vh;
            object-fit: contain;
        }
        .modal-body {
            font-size: 1rem;
            line-height: 1.5;
        }
        .modal-title {
            font-weight: bold;
            font-size: 1.25rem;
        }

        /* Typography & Components */
        .category-group h3 {
            font-family: var(--header-font);
            font-size: 1.8rem;
            text-transform: uppercase;
            color: #333;
        }

        .sessions {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        /* Toggle Mode (Dark/Light) */
        .mode {
            background-color: #fff;
            width: 5em;
            height: 2.5em;
            border-radius: 20em;
            box-shadow: inset 0 8px 60px rgba(0, 0, 0, 0.1), inset 0 8px 8px rgba(0, 0, 0, 0.1), inset 0 -4px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
            left: 7em;
        }

        .btn__indicator {
            background-color: #000;
            width: 2em;
            height: 2em;
            border-radius: 50%;
            position: absolute;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            right: 2.5em;
        }

        .btn__icon-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn__icon {
            color: var(--dark-icon-color);
            font-size: 1rem;
        }

        .btn__icon.animated {
            animation: spin 0.5s;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Darkmode Overrides */
        .darkmode .btn {
            box-shadow: inset 0 8px 60px rgba(0, 0, 0, 0.3), inset 8px 0 8px rgba(0, 0, 0, 0.3), inset 0 -4px 4px rgba(0, 0, 0, 0.3);
        }
        .darkmode .btn__indicator {
            transform: translateX(2em);
            background-color: var(--white);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
        }
        .darkmode .btn__icon {
            color: var(--light-icon-color);
        }

        /* Sections */
        .section__container {
            max-width: var(--max-width);
            margin: auto;
            padding: 5rem 1rem;
        }
        .section__header {
            font-family: var(--header-font);
            color: var(--text-dark);
        }
        .section__description {
            color: var(--text-light);
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            outline: none;
            border: none;
            color: var(--white);
            border-radius: 2px;
            transition: 0.3s;
            cursor: pointer;
        }
        .btn__primary {
            background-color: var(--primary-color);
        }
        .btn__primary:hover {
            background-color: var(--primary-color-dark);
        }
        .btn__secondary {
            background-color: transparent;
            border: 1px solid var(--white);
        }
        .btn__secondary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        /* General */
        img {
            display: flex;
            width: 100%;
        }
        a {
            text-decoration: none;
            transition: 0.3s;
        }
        body {
            font-family: "Poppins", sans-serif;
        }

        /* Nav Layout */
        nav {
            background-color: var(--text-dark);
            position: fixed;
            width: 100%;
            z-index: 9;
        }
        .nav__bar {
            width: 100%;
            max-width: var(--max-width);
            margin-inline: auto;
        }
        .nav__header {
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--text-dark);
        }
        .nav__logo img {
            max-width: 90px;
        }
        .nav__menu__btn {
            font-size: 1.5rem;
            color: gray;
            cursor: pointer;
        }
        .darkmode.nav__menu__btn {
            color: var(--white);
        }
        .nav__links {
            position: absolute;
            top: 68px;
            left: 0;
            width: 100%;
            padding: 2rem;
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 2rem;
            background-color: var(--text-dark);
            transition: 0.5s;
            z-index: -1;
            transform: translateY(-100%);
        }
        .nav__links.open {
            transform: translateY(0);
        }
        .nav__links a {
            font-size: 1.1rem;
            font-family: var(--header-font);
            white-space: nowrap;
            color: rgb(131, 131, 131);
            transition: 0.3s;
        }
        .darkmode.nav__links a {
            color: rgb(188, 188, 188);
        }
        .nav__links a.active {
            color: red;
        }
        .nav__links a:hover {
            color: var(--primary-color);
        }

        /* Header Hero */
        .header {
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.8)), url("assets/header.jpg");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .header__container {
            padding-block: 10rem;
            display: grid;
        }
        .header__content {
            margin-top: 5%;
        }
        .header__content_hero h1 {
            font-weight: 700;
            font-family: var(--header-font);
            line-height: 5rem;
        }
        .header__content h1 {
            font-size: 4.5rem;
            font-weight: 700;
            font-family: var(--header-font);
            line-height: 5rem;
            color: var(--primary-color);
        }
        .header__content h2 {
            margin-bottom: 1rem;
            font-size: 2.5rem;
            font-weight: 400;
            font-family: var(--header-font);
            line-height: 3rem;
        }
        .header__content p {
            margin-bottom: 2rem;
        }

        /* About Section */
        .about__header { text-align: center; }
        .about__header .section__description { max-width: 650px; }
        .about__grid { margin-top: 4rem; display: grid; gap: 4rem; }
        .about__card h4 {
            position: relative; isolation: isolate; margin-bottom: 1rem; padding-top: 4rem;
            font-size: 1.5rem; font-weight: 600; font-family: var(--header-font);
        }
        .about__card h4::before {
            position: absolute; bottom: 0; left: 0; font-size: 6rem; line-height: 6rem;
            color: var(--text-dark); opacity: 0.1; z-index: -1;
        }
        .about__card:nth-child(1) h4::before { content: "01"; }
        .about__card:nth-child(2) h4::before { content: "02"; }
        .about__card:nth-child(3) h4::before { content: "03"; }
        .about__card:nth-child(4) h4::before { content: "04"; }
        .about__card:nth-child(5) h4::before { content: "05"; }
        .about__card:nth-child(6) h4::before { content: "06"; }
        .about__card:nth-child(7) h4::before { content: "07"; }
        .about__card:nth-child(8) h4::before { content: "08"; }
        .about__card:nth-child(9) h4::before { content: "09"; }
        .about__card p { color: var(--text-light); }

        /* Session */
        .session { display: grid; }
        .session__card {
            padding: 5rem 2rem; text-align: center; background-size: cover; background-position: center center; background-repeat: no-repeat;
        }
        .session__card:nth-child(1) { background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("assets/session-1.jpg"); }
        .session__card:nth-child(2) { background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("assets/session-2.jpg"); }
        .session__card:nth-child(3) { background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("assets/session-3.jpg"); }
        .session__card:nth-child(4) { background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("assets/session-4.jpg"); }
        .session__card h4 {
            position: relative; isolation: isolate; max-width: fit-content; margin-inline: auto; margin-bottom: 1rem;
            font-size: 2rem; font-weight: 500; font-family: var(--header-font); color: var(--white);
        }
        .session__card h4::before {
            position: absolute; content: ""; top: 0; left: -5px; height: 25px; aspect-ratio: 1; background-color: var(--primary-color); z-index: -1;
        }
        .session__card p { max-width: 350px; margin-inline: auto; margin-bottom: 2rem; color: var(--extra-light); }

        /* Trainer */
        .trainer__grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; justify-content: center;
        }
        .trainer__card img { margin-bottom: 1rem; }
        .trainer__card h4 {
            font-size: 1.5rem; font-weight: 500; font-family: var(--header-font); color: var(--text-dark); text-align: center;
        }
        .trainer__card p { margin-bottom: 1rem; color: var(--text-light); text-align: center; }
        .trainer__socials { display: flex; align-items: center; justify-content: center; gap: 1rem; }
        .trainer__socials a { font-size: 1.25rem; color: var(--text-light); }
        .trainer__socials a:hover { color: var(--primary-color); }

        /* Membership */
        .membership {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("public/assets/membership.jpg");
            background-size: cover; background-position: center center; background-repeat: no-repeat;
        }
        .membership__container .section__header { color: var(--white); }
        .membership__grid { margin-top: 4rem; display: grid; gap: 1rem; }
        .membership__card { padding: 1rem; background-color: rgba(0, 0, 0, 0.6); transition: 0.3s; }
        .membership__card:hover { background-color: #dc030a; }
        .membership__card h4 {
            margin-bottom: 2rem; font-size: 1.75rem; font-weight: 500; font-family: var(--header-font); color: var(--white);
        }
        .membership__card ul { margin-bottom: 1rem; list-style: none; display: grid; gap: 1rem; }
        .membership__card li { display: flex; gap: 10px; color: var(--white); }
        .membership__card li span { font-size: 1.2rem; font-weight: 600; color: var(--primary-color); transition: 0.3s; }
        .membership__card:hover li span { color: var(--white); }
        .membership__card h3 {
            margin-bottom: 1rem; font-size: 3rem; font-weight: 400; font-family: var(--header-font); color: var(--white);
        }
        .membership__card h3 :is(sup, span) { font-size: 1.5rem; font-weight: 400; }
        .membership__card:hover .btn { background-color: var(--text-dark); color: white; }

        /* Swiper/Testimonial */
        .swiper { margin-top: 2rem; padding-bottom: 3rem; width: 100%; }
        .client__card { max-width: 600px; margin-inline: auto; text-align: center; }
        .client__card img {
            max-width: 100px; margin-inline: auto; margin-bottom: 1rem; border: 2px solid var(--primary-color); border-radius: 100%;
        }
        .client__card > div { font-size: 2rem; line-height: 2rem; color: var(--text-light); opacity: 0.5; }
        .client__card p { margin-bottom: 1rem; color: var(--text-light); }
        .client__card h4 { font-size: 1.2rem; font-weight: 600; color: var(--text-dark); }
        .swiper-pagination-bullet { height: 12px; width: 12px; }
        .swiper-pagination-bullet-active { background-color: var(--primary-color); }

        /* Blog */
        .blog { background-color: var(--text-dark); }
        .blog__container .section__header { color: var(--white); }
        .blog__grid { margin-top: 4rem; display: grid; gap: 1rem; }
        .blog__card img { margin-bottom: 10px; }
        .blog__card h4 {
            max-width: calc(100% - 1rem); font-size: 1.2rem; font-weight: 400; font-family: var(--header-font); color: var(--dark-color); transition: 0.3s;
        }
        .darkmode.blog__card h4 { color: var(--white); }
        .blog__card:hover h4 { color: var(--primary-color); }
        .blog__card h5 {
            max-width: calc(100% - 1rem); font-size: 1.2rem; font-weight: 400; font-family: var(--header-font); color: var(--white); transition: 0.3s;
        }
        .darkmode.blog__card h5 { color: var(--white); }
        .blog__card:hover h5 { color: var(--primary-color); }
        .blog__btn { margin-top: 4rem; text-align: center; }

        /* Footer */
        .footer { background-color: var(--text-dark); }
        .darkmode .footer { background-color: var(--text-dark); }
        .footer__container { display: grid; gap: 4rem 2rem; }
        .footer__logo img { margin-bottom: 1rem; max-width: 120px; }
        .footer_contact { color: white; }
        .footer_contact h4 {
            margin-bottom: 1rem; font-size: 1.5rem; font-weight: 500; font-family: var(--header-font); color: var(--white);
        }
        .footer_contact .border-b { border-bottom: 1px dotted #6b7280; padding-bottom: 12px; margin-bottom: 16px; }
        .footer_contact p { color: #9ca3af; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .footer_contact i { font-size: 1.25rem; }
        .footer__col p { margin-bottom: 2rem; color: var(--text-light); }
        .footer__links { list-style: none; display: grid; gap: 1rem; }
        .footer__links a { color: var(--text-light); }
        .footer__links a:hover { color: var(--white); }
        .footer__links a span { margin-right: 10px; font-size: 1.2rem; color: var(--primary-color); }
        .footer__col h4 {
            margin-bottom: 1rem; font-size: 1.5rem; font-weight: 500; font-family: var(--header-font); color: var(--white);
        }
        .gallery__grid { max-width: 350px; gap: 10px; }
        .footer__col form {
            margin-bottom: 2rem; width: 100%; max-width: 400px; display: flex; align-items: center; justify-content: center; background-color: var(--white); border-radius: 2px;
        }
        .footer__col input { width: 100%; padding-inline: 1rem; outline: none; border: none; color: var(--text-dark); }
        .footer__socials { display: flex; align-items: center; gap: 1rem; }
        .footer__socials a {
            padding: 5px 10px; font-size: 1.25rem; color: var(--primary-color); background-color: var(--white); border-radius: 100%;
        }
        .footer__socials a:hover { color: var(--white); background-color: var(--primary-color); }
        .footer__bar { padding: 2rem; font-size: 0.9rem; color: var(--text-light); text-align: center; }

        /* Media Queries */
        @media (max-width: 768px) {
            .header { background-position: right center; }
            .header__content h1 { font-size: 2.5rem; line-height: 3.5rem; }
        }

        @media (width > 540px) {
            .about__grid { grid-template-columns: repeat(2, 1fr); }
            .session__card { padding: 7rem 2rem; }
            .trainer__grid { grid-template-columns: repeat(2, 1fr); }
            .membership__grid { grid-template-columns: repeat(2, 1fr); }
            .blog__grid { grid-template-columns: repeat(2, 1fr); }
            .footer__container { grid-template-columns: repeat(1, 1fr); }
        }

        @media (width > 768px) {
            .header__container { padding-block: 12rem; grid-template-columns: repeat(2, 1fr); }
            .header__content { grid-column: 2/3; }
            .about__header { grid-template-columns: repeat(2, 1fr); gap: 2rem; align-items: center; }
            .about__grid { grid-template-columns: repeat(3, 1fr); }
            .session { grid-template-columns: repeat(2, 1fr); }
            .session__card { padding: 10rem 2rem; }
            .trainer__grid { grid-template-columns: repeat(4, 1fr); }
            .membership__grid { grid-template-columns: repeat(3, 1fr); }
            .blog__grid { grid-template-columns: repeat(4, 1fr); }
            .logo__banner { grid-template-columns: repeat(4, 1fr); }
            .footer__container { grid-template-columns: repeat(3, 1fr); }
        }

        @media (width > 1024px) {
            nav { position: static; padding: 1.5rem 1rem; }
            .nav__bar { display: flex; align-items: center; justify-content: space-between; gap: 2rem; }
            .nav__header { padding: 0; background-color: transparent; }
            .nav__logo img { max-width: 120px; }
            .nav__menu__btn { display: none; }
            .nav__links {
                position: static; padding: 0; flex-direction: row; justify-content: flex-end; background-color: transparent; transform: none; z-index: 1;
            }
            .trainer__grid, .membership__grid { gap: 2rem; }
            .membership__card { padding: 2rem; }
        }
    </style>

	<style>
		/* 1. Paksa Muncul Pas Hover (Desktop) */
		@media (min-width: 768px) {
			.dropdown:hover .dropdown-menu {
				display: block;
				animation: slideUp 0.2s ease-out forwards;
			}
		}

		@keyframes slideUp {
			from {
				opacity: 0;
				transform: translateY(10px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		/* 2. Styling Container Dropdown (BIAR DARK & RAPI) */
		.custom-dropdown-menu {
			border: none;
			border-top: 4px solid #dc030a;
			/* Merah di atas */
			background-color: #0a0a0a;
			/* HITAM (Brand Black) biar nyatu sama navbar */
			border-radius: 0 0 8px 8px;
			box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
			/* Shadow lebih gelap */
			padding: 8px 0;
			min-width: 220px;
			/* Sedikit lebih lebar */

			display: none;
			position: absolute;
			/* KUNCI POSISI: */
			top: 100%;
			/* Muncul pas di bawah container parent */
			left: 0;
			margin-top: 0;
			z-index: 9999;
		}

		/* 3. Styling Item (Link) */
		.custom-dropdown-item {
			display: block;
			padding: 12px 20px;
			font-family: "Poppins", sans-serif;
			font-size: 0.9rem;
			color: #d1d5db;
			/* Abu-abu terang (Gray-300) */
			border-bottom: 1px solid #1f2937;
			/* Garis pemisah tipis gelap */
			transition: all 0.2s;
			background-color: transparent;
			/* Pastikan transparan */
		}

		/* Hover Effect per Item */
		.custom-dropdown-item:hover {
			background-color: #171717;
			/* Sedikit lebih terang pas dihover */
			color: #dc030a;
			/* Teks jadi merah */
			text-decoration: none;
			padding-left: 24px;
			/* Geser dikit biar dinamis */
		}

		.custom-dropdown-item:last-child {
			border-bottom: none;
		}

		/* Hilangkan panah bawaan Bootstrap */
		.dropdown-toggle::after {
			display: none !important;
		}
	</style>
</head>

<body>
	{{-- NAVIGASI UTAMA --}}
	<nav class="fixed top-0 left-0 w-full z-50 bg-white/95 text-gray-800 dark:text-white dark:bg-brand-black/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 shadow-sm transition-all duration-300 h-20">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
			<div class="flex items-center justify-between h-full">

				{{-- LOGO --}}
				<div class="flex-shrink-0 flex items-center">
					<a href="{{ route('home') }}" class="flex items-center gap-3">
						<img class="h-10 w-auto object-contain" src="{{ asset('assets/Logo/empire.png') }}" alt="B1NG Empire Logo">
						<span class="font-heading font-bold text-xl uppercase tracking-wider sm:block text-gray-900 dark:text-white">B1NG Empire</span>
					</a>
				</div>

				{{-- DESKTOP MENU (TENGAH) --}}
				<div class="hidden md:flex flex-1 justify-center ml-10">
					<div class="flex items-center space-x-6">
						@foreach($navMenus as $menu)
						{{-- WRAPPER DROPDOWN ASLI KAMU --}}
						<div class="dropdown relative h-20 flex items-center group">
							<a href="{{ route($menu['route']) }}"
								class="dropdown-toggle font-heading uppercase text-sm tracking-widest hover:text-brand-red transition-colors py-2 flex items-center gap-1
                            {{ Route::currentRouteName() === $menu['route'] ? 'text-brand-red font-bold' : 'text-gray-800 dark:text-white' }}"
								@if(!empty($menu['submenu'])) role="button" @endif>
								{{ $menu['label'] }}
								@if(!empty($menu['submenu']))
								<i class="fas fa-chevron-down text-[10px] opacity-50 group-hover:opacity-100 transition-opacity ml-1"></i>
								@endif
							</a>

							@if(!empty($menu['submenu']))
							{{-- CLASS ASLI KAMU: custom-dropdown-menu --}}
							<ul class="dropdown-menu custom-dropdown-menu">
								@foreach($menu['submenu'] as $sub)
								<li>
									<a class="custom-dropdown-item" href="{{ $sub['url'] }}">
										{{ $sub['label'] }}
									</a>
								</li>
								@endforeach
							</ul>
							@endif
						</div>
						@endforeach
					</div>
				</div>

				{{-- BAGIAN KANAN: LOGIN / PROFILE (PAKAI STRUKTUR DROPDOWN YG SAMA) --}}
				<div class="hidden md:flex items-center gap-4 ml-4">
					@guest
					{{-- Tombol Login Biasa --}}
					<a href="{{ route('login') }}" class="px-5 py-2 border border-brand-red text-brand-red hover:bg-brand-red hover:text-white rounded font-heading font-bold uppercase tracking-widest text-xs transition">
						Login
					</a>
					@else
					{{-- DROPDOWN PROFILE (Di-copy dari struktur menu kamu biar jalannya sama) --}}
					<div class="dropdown relative h-20 flex items-center group">

						{{-- Trigger: Foto & Nama --}}
						<a href="#" class="dropdown-toggle flex items-center gap-3 focus:outline-none py-2" role="button">
							<div class="text-right hidden xl:block">
								<p class="text-sm font-bold font-heading uppercase text-gray-800 dark:text-white">{{ Auth::user()->name }}</p>
							</div>
							<img class="h-9 w-9 rounded border border-gray-300 dark:border-gray-600 object-cover"
								src="{{ Auth::user()->profile_picture ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=DC2626&color=fff' }}">
							<i class="fas fa-chevron-down text-[10px] opacity-50 group-hover:opacity-100 transition-opacity ml-1 text-gray-500"></i>
						</a>

						{{-- Menu: Pakai class 'dropdown-menu custom-dropdown-menu' punya kamu --}}
						<ul class="dropdown-menu custom-dropdown-menu custom-dropdown-menu-right"> {{-- Tambahkan class 'custom-dropdown-menu-right' di CSS kalau mau rata kanan --}}
							<li>
								<a class="custom-dropdown-item" href="{{ route('dashboard') }}">
									<i class="fas fa-chart-line mr-2"></i> Dashboard
								</a>
							</li>
							<li>
								<a class="custom-dropdown-item" href="{{ route('attendance') }}">
									<i class="fas fa-qrcode mr-2"></i> Riwayat Absensi
								</a>
							</li>
							<li>
								<a class="custom-dropdown-item" href="{{ route('profile') }}">
									<i class="fas fa-user-cog mr-2"></i> Edit Profil
								</a>
							</li>
							<li>
								<hr class="dropdown-divider border-t border-gray-200 dark:border-gray-700 my-1">
							</li>
							<li>
								<form method="POST" action="{{ route('logout') }}">
									@csrf
									<button type="submit" class="custom-dropdown-item w-full text-left text-red-500 font-bold">
										<i class="fas fa-sign-out-alt mr-2"></i> Logout
									</button>
								</form>
							</li>
						</ul>
					</div>
					@endguest
				</div>

				{{-- MOBILE MENU BUTTON (ASLI) --}}
				<div class="md:hidden flex items-center">
					<button id="mobile-menu-btn" class="relative w-10 h-10 flex items-center justify-center text-gray-800 dark:text-white hover:text-brand-red focus:outline-none">
						<i id="nav-icon-bars" class="fas fa-bars text-2xl transition-all duration-300 transform scale-100 opacity-100 absolute"></i>
						<i id="nav-icon-times" class="fas fa-times text-2xl transition-all duration-300 transform scale-0 opacity-0 absolute"></i>
					</button>
				</div>
			</div>
		</div>

		{{-- MOBILE MENU (ASLI + LOGIC MEMBER) --}}
		<div id="mobile-menu" class="hidden md:hidden bg-black dark:bg-brand-gray border-t border-gray-200 dark:border-gray-700 absolute w-full left-0 shadow-lg h-[calc(100vh-80px)] overflow-y-auto pb-20">

			{{-- SHORTCUT MEMBER (PALING ATAS) --}}
			<div class="px-4 pt-6 pb-2">
				@guest
				<a href="{{ route('login') }}" class="block w-full text-center bg-brand-red text-white font-heading font-bold py-3 rounded mb-4 uppercase tracking-widest shadow-md">
					Login Member
				</a>
				@else
				<div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-black/30 rounded-lg mb-4 border border-gray-200 dark:border-gray-600">
					<img class="h-12 w-12 rounded object-cover" src="{{ Auth::user()->profile_picture ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=DC2626&color=fff' }}">
					<div>
						<div class="text-sm font-bold font-heading uppercase text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
						<div class="text-xs text-brand-red font-bold uppercase tracking-wide">Member Area</div>
					</div>
				</div>
				<div class="grid grid-cols-2 gap-3 mb-6">
					<a href="{{ route('dashboard') }}" class="text-center py-3 bg-black dark:bg-brand-black text-gray-600 dark:text-gray-300 rounded border border-gray-200 dark:border-gray-600 text-xs font-bold uppercase shadow-sm hover:border-brand-red hover:text-brand-red transition">
						Dashboard
					</a>
					<a href="{{ route('attendance') }}" class="text-center py-3 bg-black dark:bg-brand-black text-gray-600 dark:text-gray-300 rounded border border-gray-200 dark:border-gray-600 text-xs font-bold uppercase shadow-sm hover:border-brand-red hover:text-brand-red transition">
						Riwayat Absensi
					</a>
				</div>
				<div class="border-b border-gray-100 dark:border-gray-700 mb-2"></div>
				@endguest
			</div>

			{{-- LOOPING MENU BAWAAN --}}
			<div class="px-4 space-y-1">
				@foreach($navMenus as $index => $menu)
				<div class="border-b border-gray-100 dark:border-gray-700 last:border-0">
					@if(empty($menu['submenu']))
					<a href="{{ route($menu['route']) }}" class="block px-3 py-4 text-base font-heading uppercase tracking-widest {{ Route::currentRouteName() === $menu['route'] ? 'text-brand-red font-bold' : 'text-gray-800 dark:text-white' }}">
						{{ $menu['label'] }}
					</a>
					@else
					<button onclick="toggleMobileSubmenu('submenu-{{ $index }}')" class="w-full flex justify-between items-center px-3 py-4 text-base font-heading uppercase tracking-widest text-gray-800 dark:text-white hover:text-brand-red focus:outline-none">
						<span>{{ $menu['label'] }}</span>
						<i id="icon-submenu-{{ $index }}" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
					</button>
					<div id="submenu-{{ $index }}" class="hidden bg-gray-50 dark:bg-black/20 pl-6 pr-3 py-2 space-y-2 mb-2">
						@foreach($menu['submenu'] as $sub)
						<a href="{{ $sub['url'] }}" class="block py-2 text-sm font-sans text-gray-600 dark:text-gray-300 hover:text-brand-red">
							<i class="fas fa-angle-right mr-2 text-brand-red text-xs"></i> {{ $sub['label'] }}
						</a>
						@endforeach
					</div>
					@endif
				</div>
				@endforeach

				@auth
				<form method="POST" action="{{ route('logout') }}" class="pt-6 pb-4">
					@csrf
					<button type="submit" class="block w-full text-center px-3 py-3 rounded border border-red-200 dark:border-red-900/50 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 font-bold uppercase text-sm transition">
						Logout
					</button>
				</form>
				@endauth
			</div>
		</div>
	</nav>

	{{-- JAVASCRIPT --}}
	<script>
		// --- Navbar Mobile Toggle (UPDATED) ---
		const btn = document.getElementById('mobile-menu-btn');
		const menu = document.getElementById('mobile-menu');
		const iconBars = document.getElementById('nav-icon-bars');
		const iconTimes = document.getElementById('nav-icon-times');

		if (btn && menu) {
			btn.addEventListener('click', () => {
				const isHidden = menu.classList.toggle('hidden');

				if (isHidden) {
					// MENU TUTUP → tampilkan BARS
					iconBars.classList.remove('scale-0', 'opacity-0');
					iconBars.classList.add('scale-100', 'opacity-100');

					iconTimes.classList.remove('scale-100', 'opacity-100');
					iconTimes.classList.add('scale-0', 'opacity-0');
				} else {
					// MENU BUKA → tampilkan TIMES
					iconBars.classList.remove('scale-100', 'opacity-100');
					iconBars.classList.add('scale-0', 'opacity-0');

					iconTimes.classList.remove('scale-0', 'opacity-0');
					iconTimes.classList.add('scale-100', 'opacity-100');
				}
			});
		}

		// --- Mobile Submenu Accordion ---
		function toggleMobileSubmenu(id) {
			const submenu = document.getElementById(id);
			const icon = document.getElementById('icon-' + id);

			if (submenu.classList.contains('hidden')) {
				submenu.classList.remove('hidden');
				icon.classList.add('rotate-180');
			} else {
				submenu.classList.add('hidden');
				icon.classList.remove('rotate-180');
			}
		}

		// --- CTA Floating ---
		let fabOpen = false;

		function toggleFab() {
			const fabMenu = document.getElementById('fab-menu');
			const iconOpen = document.getElementById('fab-icon-open');
			const iconClose = document.getElementById('fab-icon-close');
			const fabBtn = document.getElementById('fab-btn');

			fabOpen = !fabOpen;

			if (fabOpen) {
				fabMenu.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
				iconOpen.classList.add('opacity-0', 'scale-0');
				iconClose.classList.remove('opacity-0', 'scale-0');
				fabBtn.classList.add('ring-4', 'ring-red-300');
			} else {
				fabMenu.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
				iconOpen.classList.remove('opacity-0', 'scale-0');
				iconClose.classList.add('opacity-0', 'scale-0');
				fabBtn.classList.remove('ring-4', 'ring-red-300');
			}
		}

		document.addEventListener('click', function(event) {
			const container = document.getElementById('fab-btn').parentElement;
			if (!container.contains(event.target) && fabOpen) {
				toggleFab();
			}
		});
	</script>

	<section id="header">
		<header class="header">
			<div class="relative flex items-center justify-center h-screen bg-black text-white"
				style="
        background-image: linear-gradient(
            rgba(0, 0, 0, 0.5),  /* Lapisan gelap di atas */
            rgba(0, 0, 0, 0.5)
        ),
        linear-gradient(
            to left, 
            rgba(0, 0, 0, 0.8), 
            rgba(0, 0, 0, 0.2),
            rgba(0, 0, 0, 0.8)
        ),
        url('assets/home/biin-gym.jpg');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        ">

				<div class="header__content_hero relative text-center px-4 sm:px-8 md:px-12 lg:px-16">
					<h1 class="text-4xl sm:text-6xl md:text-6xl lg:text-7xl font-extrabold">
						<span class="text-red-600">B11N GYM</span><br>
						PURWOKERTO
					</h1>
					<p class="mt-2 text-lg md:text-xl font-base">Premium Fitness Studio | GIVE UP OR GET UP</p>
					<div class="header__btn">
						<a href="#membership">
							<button class="btn btn__primary mt-3">GET STARTED</button>
						</a>
					</div>
				</div>
			</div>
		</header>
	</section>

	<section class="max-w-[1200px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-20" id="about">
		<div class="about__header text-center">
			<h2 class="section__header text-4xl sm:text-4xl md:text-4xl lg:text-5xl font-header text-text-dark font-semibold mb-8">Tentang Kami</h2>
			<p class="section__description mx-auto text-center text-base sm:text-base md:text-base">
				Selamat datang di B11N Gym Purwokerto, pusat kebugaran populer di Arcawinangun, Purwokerto Timur. Kami menawarkan keanggotaan harian, mingguan, dan bulanan dengan harga terjangkau. Fasilitas kami meliputi peralatan gym lengkap, ruang istirahat ber-AC, ruang karaoke, dan loker yang aman. B11N Gym bukan hanya tempat berolahraga, tapi juga ruang untuk membangun komunitas hidup sehat yang menyenangkan.
			</p>
		</div>
		<div class="about__grid">
			@foreach ($about as $about)
			<div class="about__card">
				<h4 class="uppercase">{{ $about->title }}</h4>
				<p>
					{{ $about->description }}
				</p>
			</div>
			@endforeach
		</div>
	</section>

	<section class="max-w-[1200px] mx-auto px-4 py-20" id="facility">
		<div class="about__header text-center">
			<h2 class="section__header text-4xl sm:text-4xl md:text-4xl lg:text-5xl font-header text-text-dark font-semibold mb-8">
				Fasilitas Kami
			</h2>
		</div>
		<div class="container mx-auto px-0 md:px-4 py-12">
			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
				@foreach ($facilities as $facility)
				<div class="about__card bg-white border border-gray-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
					<div class="flex items-center justify-center text-white rounded-full mx-auto -mb-16">
						<img
							src="{{ asset('storage/' . $facility->image) }}"
							alt="{{ $facility->title }}"
							class="object-cover w-28 h-28" />
					</div>
					<h4 class="text-center text-red-600 mb-2 uppercase">{{ $facility->title }}</h4>
					<p class="text-gray-600 text-sm text-center">
						{{ $facility->description }}
					</p>
				</div>
				@endforeach
			</div>
		</div>
	</section>

	<section class="py-8" id="training">
		@foreach ($groupedTrainingPrograms as $categoryId => $trainingPrograms)
		@php
		$categoryTitle = $categories[$categoryId]->title ?? 'Unknown Category'; // Judul kategori
		@endphp
		<div class="category-group">
			<div class="text-center">
				<h3 class="text-2xl font-semibold text-white bg-black pt-8 pb-8">{{ $categoryTitle }}</h3>
			</div>
			<div class="grid grid-cols-1 sm:grid-cols-2 gap-0">
				@foreach ($trainingPrograms as $trainingprogram)
				<div class="session__card bg-cover bg-center p-6 relative h-[30rem]"
					style="background-image:  linear-gradient(to top, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.8)),url('{{ asset('storage/' . $trainingprogram->image) }}');">
					<h4 class="text-xl font-bold text-white uppercase">{{ $trainingprogram->title }}</h4>
					<p class="text-white mt-4">
						{{ \Illuminate\Support\Str::words(strip_tags($trainingprogram->description), 15, '...') }}
					</p>
					<button class="btn btn__secondary" data-bs-toggle="modal" data-bs-target="#modal-{{ $trainingprogram->id }}">
						READ MORE <i class="ri-arrow-right-line"></i>
					</button>
				</div>

				<div class="modal fade" id="modal-{{ $trainingprogram->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $trainingprogram->id }}" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content rounded-lg shadow-lg">
							<div class="modal-header bg-gray-100 p-4 rounded-t-lg">
								<h5 class="modal-title text-xl font-semibold" id="modalLabel-{{ $trainingprogram->id }}">{{ $trainingprogram->title }}</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body p-6">
								{!! $trainingprogram->description !!}
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		@endforeach
	</section>

	<section class="py-20 bg-white text-gray-900" id="equipments">
		<div class="container mx-auto px-4">

			{{-- HEADER --}}
			<div class="text-center mb-16">
				<h2 class="text-4xl md:text-5xl font-bold uppercase tracking-tighter mb-2 font-['Oswald']">
					Gym Equipments
					<span class="text-red-600">& Tutorials</span>
				</h2>

				<div class="w-24 h-1.5 bg-red-600 mx-auto mt-4"></div>

				<p class="text-gray-600 mt-6 max-w-2xl mx-auto font-light text-lg">
					Kenali alat-alat kami dan pelajari cara penggunaannya yang benar demi hasil maksimal.
				</p>
			</div>

			{{-- GRID --}}
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
				@foreach($featuredEquipments as $item)
				@php
				$thumbnail = $item->gallery->first()
				? asset('storage/' . $item->gallery->first()->file_path)
				: 'https://placehold.co/600x400?text=No+Image';
				@endphp

				<div class="group bg-white border border-gray-200 hover:border-gray-400 transition-all duration-300 relative shadow-sm hover:shadow-xl">

					<div class="relative h-64 overflow-hidden bg-gray-100">
						<img src="{{ $thumbnail }}" alt="{{ $item->name }}"
							class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 grayscale group-hover:grayscale-0">

						<div class="absolute top-0 left-0 bg-red-600 text-white text-xs font-bold px-3 py-1 uppercase tracking-widest font-['Oswald']">
							{{ $item->category }}
						</div>

						<div class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center backdrop-blur-[2px]">
							<a href="{{ route('gym.equipments.show', $item->slug) }}"
								class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 font-bold uppercase tracking-wide
									  transform translate-y-4 group-hover:translate-y-0 transition duration-300 shadow-lg
									  flex items-center gap-2 rounded-sm">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
									stroke="currentColor" stroke-width="2">
									<path stroke-linecap="round" stroke-linejoin="round"
										d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
								</svg>
								Watch Tutorial
							</a>
						</div>
					</div>

					<div class="p-6">
						<h3 class="text-2xl font-bold text-gray-900 mb-2 uppercase font-['Oswald']
								   group-hover:text-red-600 transition-colors">
							{{ $item->name }}
						</h3>

						<div class="w-10 h-1 bg-gray-200 mb-3 group-hover:bg-red-600 transition-colors"></div>

						<p class="text-gray-500 text-sm line-clamp-2 leading-relaxed">
							{{ $item->description }}
						</p>
					</div>

					<div class="h-1 w-0 group-hover:w-full bg-red-600 transition-all duration-500 absolute bottom-0 left-0"></div>
				</div>
				@endforeach
			</div>

			{{-- CTA --}}
			<div class="text-center">
				<a href="{{ route('gym.equipments.index') }}"
					class="inline-block border-2 border-black text-black px-10 py-3 font-bold uppercase tracking-widest font-['Oswald'] text-lg hover:bg-black hover:!text-white transition duration-300">
					View All Equipments
				</a>
			</div>

		</div>
	</section>


	<section class="trainer__container max-w-[1200px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-20" id="trainer">
		<h2 class="section__header text-2xl sm:text-3xl md:text-3xl lg:text-3xl font-semibold font-header text-text-dark text-center">MEET OUR TRAINERS</h2>
		<div class="trainer__grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-8">
			@foreach ($trainer as $trainer)
			<div class="trainer__card text-center border border-gray-200 rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300">
				<img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" class="h-50 w-full object-cover mb-4 rounded-md" />
				<h4 class="text-lg sm:text-xl md:text-2xl font-semibold">{{ $trainer->name }}</h4>
				<p class="text-sm sm:text-base md:text-lg text-gray-600">{{ $trainer->description }}</p>
				<div class="trainer__socials flex justify-center mt-4 gap-4">
					<a href="{{ $trainer->urls['facebook'] ?? '#' }}" target="_blank"><i class="ri-facebook-fill"></i></a>
					<a href="{{ $trainer->urls['whatsapp'] ?? '#' }}"><i class="ri-whatsapp-fill"></i></a>
					<a href="{{ $trainer->urls['instagram'] ?? '#' }}"><i class="ri-instagram-fill"></i></a>
				</div>
			</div>
			@endforeach
		</div>
	</section>

	<section class="membership"
		style="
        background-image: linear-gradient(
             to top,
             rgba(0, 0, 0, 0.2),
             rgba(0, 0, 0, 0.9)
         ),
         url('assets/Hero/b11ngym.jpg');
         background-size: cover;
        background-position: center center;
         background-repeat: no-repeat;
     " id="membership">
		<div class="membership__container max-w-[1200px] mx-auto px-4 py-20">
			<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">MEMBERSHIP</h2>
			<div class="membership__grid grid grid-cols-1 md:grid-cols-3 gap-6">
				<div class="membership__card">
					<h4>Harian</h4>
					<ul>
						<li>
							<span><i class="ri-check-line"></i></span>
							Satu hari akses penuh di B11N & K1NG Gym Purwokerto
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Akses bebas untuk semua peralatan gym.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Free personal trainer.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Akses bebas untuk semua peralatan
						</li>
						<li>
							<span><i class="ri-close-line"></i></span>
							Free biaya pendaftaran
						</li>
						<li>
							<span><i class="ri-close-line"></i></span>
							Masuk sebagai member dari B11N & K1NG Gym
						</li>
					</ul>
					<h3><sup>Rp</sup>10.000<span>/hari</span></h3>
					<button class="btn btn__primary" class="btn btn__primary" onclick="openPaymentModal('Harian')">
						Beli Sekarang</button>
				</div>
				<div class="membership__card">
					<h4>Mingguan</h4>
					<ul>
						<li>
							<span><i class="ri-check-line"></i></span>
							Satu hari akses penuh di B11N & K1NG Gym Purwokerto
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Akses bebas untuk semua peralatan gym.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Free personal trainer.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Akses bebas untuk semua peralatan
						</li>
						<li>
							<span><i class="ri-close-line"></i></span>
							Free biaya pendaftaran
						</li>
						<li>
							<span><i class="ri-close-line"></i></span>
							Masuk sebagai member dari B11N & K1NG Gym
						</li>
					</ul>
					<h3><sup>Rp</sup>30.000<span>/minggu</span></h3>
					<button class="btn btn__primary" class="btn btn__primary" onclick="openPaymentModal('Mingguan')">
						Beli Sekarang</button>
				</div>
				<div class="membership__card">
					<h4>Bulanan</h4>
					<ul>
						<li>
							<span><i class="ri-check-line"></i></span>
							Satu hari akses penuh di B11N & K1NG Gym Purwokerto
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Akses bebas untuk semua peralatan gym.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Free personal trainer.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Akses bebas untuk semua peralatan
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Free biaya pendaftaran
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Masuk sebagai member dari B11N & K1NG Gym
						</li>
					</ul>
					<h3><sup>Rp</sup>80.000<span>/Bulan</span></h3>
					<button
						class="btn btn__primary" onclick="openPaymentModal('Bulanan')">
						Beli Sekarang
					</button>
				</div>

				<div
					class="modal fade"
					id="paymentModal"
					tabindex="-1"
					aria-labelledby="paymentModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="paymentModalLabel">
									Pilih Metode Pembayaran untuk Member <span class="text-danger" id="membershipTitle"></span>
								</h5>
								<button
									type="button"
									class="btn-close"
									data-bs-dismiss="modal"
									aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div id="paymentOptions">
									<div class="row">
										<div class="col-md-6 text-center">
											<img
												src="/assets/img/pembayaran/qris.png"
												alt="QRIS"
												class="img-fluid mb-3"
												style="max-height: 200px;" />
											<button
												class="btn btn-primary w-100"
												onclick="selectPaymentMethod('qris')">
												Bayar via QRIS
											</button>
										</div>
										<div class="col-md-6 text-center">
											<img
												src="/assets/img/pembayaran/bca.png"
												alt="Transfer Bank"
												class="img-fluid mb-3"
												style="max-height: 200px;" />
											<button
												class="btn btn-primary w-100"
												onclick="selectPaymentMethod('transfer')">
												Bayar via Transfer
											</button>
										</div>
									</div>
								</div>

								<div id="qrisPayment" class="d-none">
									<h5 class="text-center mt-4">
										Pembayaran via QRIS untuk Member <span class="text-danger" id="qrisMembershipTitle"></span>
									</h5>
									<div class="text-center my-3">
										<div class="place-items-center">
											<img
												src="/assets/img/pembayaran/qris-barcode.png"
												alt="QRIS Barcode"
												class="img-fluid w-full md:w-1/2" />
										</div>
									</div>
									<p class="text-center">
										Scan kode QR di atas untuk melakukan pembayaran.
									</p>
									<div class="flex flex-col sm:flex-row gap-4 justify-center">
										<button class="btn btn-primary" onclick="showPaymentConfirmationModal()">
											Upload Payment Confirmation
										</button>
									</div>
									<p class="text-danger text-center">
										Catatan Penting: Setelah pembayaran, wajib kirim bukti pembayaran
										dengan klik tombol di atas.
									</p>
									<p class="text-gray-800 text-center rounded-md bg-gray-200 text-sm border-t-[15px] border-b-[15px] mb-3 mt-2">
										Jangan lupa absen saat berkunjung ke B11N Gym. Jika kamu membeli atau memperpanjang Member Bulanan, minta kartu member baru atau serahkan kartu lamamu ke kasir.
									</p>
									<div class="d-flex justify-content-between">
										<button
											class="btn btn-secondary"
											onclick="goBackToOptions()">
											Kembali
										</button>
										<button
											class="btn btn-secondary"
											data-bs-dismiss="modal">
											Tutup
										</button>
									</div>
								</div>

								<div id="transferPayment" class="d-none">
									<h5 class="text-center mt-4">
										Pembayaran via Transfer untuk Member <span class="text-danger" id="transferMembershipTitle"></span>
									</h5>
									<div class="text-center my-3">
										<p class="text-center">Nomor Rekening: <strong>0461701506</strong></p>
										<p class="text-center">An: <strong>Sobiin</strong></p>
										<div class="place-items-center">
											<img
												src="/assets/img/pembayaran/bca.png"
												alt="Bank BCA"
												class="img-fluid w-1/2" />
										</div>
									</div>
									<p class="text-center">
										Lakukan transfer ke nomor rekening di atas.
									</p>
									<div class="flex flex-col sm:flex-row gap-4 justify-center">
										<button class="btn btn-primary" onclick="showPaymentConfirmationModal()">
											Upload Payment Confirmation
										</button>
									</div>

									<p class="text-danger text-center">
										Catatan Penting: Setelah pembayaran, wajib kirim bukti pembayaran
										dengan klik tombol di atas.
									</p>
									<p class="text-gray-800 text-center rounded-md bg-gray-200 text-sm border-t-[15px] border-b-[15px] mb-3 mt-2">
										Jangan lupa absen saat berkunjung ke B11N Gym. Jika kamu membeli atau memperpanjang Member Bulanan, minta kartu member baru atau serahkan kartu lamamu ke kasir.
									</p>
									<div class="d-flex justify-content-between">
										<button
											class="btn btn-secondary"
											onclick="goBackToOptions()">
											Kembali
										</button>
										<button
											class="btn btn-secondary"
											data-bs-dismiss="modal">
											Tutup
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="paymentConfirmationModal" tabindex="-1" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title" id="paymentConfirmationModalLabel">Upload Payment Confirmation</h2>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form id="paymentForm" enctype="multipart/form-data">

									<input type="hidden" name="gym_id" value="1">

									<div class="mb-3">
										<label class="block font-medium">Nama:</label>
										<input type="text" name="name" class="w-full border p-2 rounded" required>
									</div>

									<div class="mb-3">
										<label class="block font-medium">Email:</label>
										<input type="email" name="email" class="w-full border p-2 rounded" required>
									</div>

									<div class="mb-3">
										<label class="block font-medium">No. Telp:</label>
										<input type="tel" name="phone" class="w-full border p-2 rounded" required>
									</div>

									<div class="mb-3">
										<label class="block font-medium">Upload Bukti Pembayaran:</label>
										<input type="file" name="image" accept="image/*" class="w-full border p-2 rounded" required>
									</div>

									<div class="mb-3">
										<label class="block font-medium">Pilih Membership:</label>
										<select name="membership_type" class="w-full border p-2 rounded" required>
											<option value="Member Harian">Member Harian (Rp 10.000)</option>
											<option value="Member Mingguan">Member Mingguan (Rp 30.000)</option>
											<option value="Member Bulanan">Member Bulanan (Rp 85.000)</option>
										</select>
									</div>

									<div class="mb-3">
										<label class="block font-medium">Metode Pembayaran:</label>
										<select name="payment" class="w-full border p-2 rounded" required>
											<option value="qris">QRIS</option>
											<option value="transfer">Transfer Bank</option>
										</select>
									</div>

									<div class="flex justify-end">
										<button type="button" class="btn btn-danger me-2" data-bs-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-success">Upload</button>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>

	@if ($store)
	<section
		style="background-image: url('{{ asset('storage/' . $store->image) }}'); background-size: cover; background-position: center;"
		class="header mx-auto px-4 py-15 bg-black "
		id="store">
		<div class="header__container max-w-[1200px] mx-auto px-4 py-20">
			<div class="header__content">
				<h1 class="text-[#dc030a]">{{ $store->title }}</h1>

				<h2 class="text-white">{{ $store->subheading }}</h2>

				<p class="text-white">{{ $store->description }}</p>
				<div class="header__btn">
					<a href="{{ route('store.biin-king') }}" class="btn btn__primary">VISIT STORE</a>
				</div>
			</div>
		</div>
	</section>
	@endif

	<section class="client__container max-w-[1200px] mx-auto px-4 py-20" id="testimonial">
		<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">OUR TESTIMONIALS</h2>
		<div class="swiper">
			<div class="swiper-wrapper">
				@foreach ($testimonis as $testimoni)
				<div class="swiper-slide">
					<div class="client__card">
						<img src="{{ filter_var($testimoni->image, FILTER_VALIDATE_URL) ? $testimoni->image : asset('storage/' . $testimoni->image) }}"
							alt="{{ $testimoni->name }}" />
						<div><i class="ri-double-quotes-r"></i></div>
						<p>{{ $testimoni->description }}</p>
						<h4>{{ $testimoni->name }}</h4>
						<div class="rating">
							@php
							$rating = $testimoni->rating ?? 1; // Default minimal 1
							@endphp
							@for ($i = 1; $i <= 5; $i++)
								<i class="{{ $i <= $rating ? 'fas' : 'far' }} fa-star text-yellow-500 text-base"></i>
								@endfor
						</div>
					</div>
				</div>
				@endforeach
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</section>

	@section('content')
	<section class="blog" id="blog">
		<div class="blog__container max-w-[1200px] mx-auto px-4 py-20">
			<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">BLOGS</h2>
			<div class="blog__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
				@foreach ($blog as $blog)
				<div class="blog__card mt-4 md:mt-0">
					<a href="{{ route('blogs.show', $blog->slug) }}">
						<img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="h-[300px] object-cover" />
						<p class="uppercase text-[10px] tracking-[3px] mb-1 text-white mt-3">{{ $blog->created_at->format('F d, Y') }}</p>
						<h5 class="text-lg font-medium uppercase">{{ $blog->title }}</h5>
					</a>
				</div>
				@endforeach
			</div>

			@if($blog->count() > 3)
			<div class="blog__btn mt-8 text-center">
				<a href="{{ route('blogs.index') }}" class="btn btn__primary">VIEW ALL</a>
			</div>
			@endif
		</div>
	</section>

	<section class="w-full mx-auto" id="contact">
		<div class="flex-dir-row" style="width: 100%; height: 300px;">
			<div class="" style="width: 100%; height: 100%;">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.4855691732196!2d109.25932187454826!3d-7.411386672987716!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655f0039f00903%3A0xf9273b4e5db80ee9!2sB11N%20GYM!5e0!3m2!1sid!2sid!4v1735117847259!5m2!1sid!2sid" style="border:0; width: 100%; height: 100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</div>
	</section>

	<footer class="footer">
		<div class="footer__container max-w-[1200px] mx-auto px-4 py-20">
			<div class="footer__col">
				<div class="footer__logo">
					<a href="#">
						<img src="{{ asset('assets/Logo/biin.png') }}" alt="logo" />
					</a>
				</div>
				<p>
					Selamat datang di B11N Gym Purwokerto, pusat kebugaran populer di Arcawinangun, Purwokerto Timur.
				</p>
				<ul class="footer__links">
					<li>
						<a href="https://maps.app.goo.gl/CEQqy1nCNUYKasrU9" target="_blank">
							<span><i class="ri-map-pin-2-fill"></i></span>
							Jl. Masjid Baru, Arcawinangun,Kec. Purwokerto Timur, Kab. Banyumas </a>
					</li>
					<li>
						<a href="https://wa.me/6283194288423" target="_blank">
							<span><i class="ri-phone-fill"></i></span>
							+62 831 9428 8423
						</a>
					</li>
					<li>
						<a href="mailto:sobiin77@gmail.com" target="_blank">
							<span><i class="ri-mail-fill"></i></span>
							sobiin77@gmail.com
						</a>
					</li>
				</ul>
			</div>

			<div class="footer__col">
				<h4 class="">GALLERY</h4>
				<div class="gallery__grid mt-7 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
					@foreach ($gallery as $item)
					<img
						src="{{ asset('storage/' . $item->image) }}"
						alt="{{ $item->title }}"
						class="gallery-thumbnail"
						data-bs-toggle="modal"
						data-bs-target="#galleryModal"
						data-title="{{ $item->title }}"
						data-src="{{ asset('storage/' . $item->image) }}" />
					@endforeach
				</div>
			</div>

			<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">
								<span class="text-danger">B11N GYM </span>GALLERY
							</h5>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="Close"></button>
						</div>
						<div class="modal-body text-center">
							<img id="modalImage" src="" alt="" class="img-fluid mb-3" />
							<h5 id="modalTitle" class="text-center font-bold text-lg"></h5>
						</div>
						<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-secondary prev-image">Previous</button>
							<button type="button" class="btn btn-secondary next-image">Next</button>
						</div>
					</div>
				</div>
			</div>

			<div class="footer_contact text-white">
				<h4 class="text-red-600 text-lg font-bold mb-4">HUBUNGI KAMI</h4>

				<div class="border-b border-dotted border-gray-400 pb-3 mb-3 group">
					<a href="https://www.threads.net/@biin_gym?xmt=AQGzKh5EYkbE4G7JIjSwlirbjIADsXrxWWU6UuUKi1XKhFU" class="flex items-center gap-3 text-white group-hover:text-red-600 transition-all" target="_blank">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-threads group-hover:text-red-600" viewBox="0 0 16 16">
							<path d="M6.321 6.016c-.27-.18-1.166-.802-1.166-.802.756-1.081 1.753-1.502 3.132-1.502.975 0 1.803.327 2.394.948s.928 1.509 1.005 2.644q.492.207.905.484c1.109.745 1.719 1.86 1.719 3.137 0 2.716-2.226 5.075-6.256 5.075C4.594 16 1 13.987 1 7.994 1 2.034 4.482 0 8.044 0 9.69 0 13.55.243 15 5.036l-1.36.353C12.516 1.974 10.163 1.43 8.006 1.43c-3.565 0-5.582 2.171-5.582 6.79 0 4.143 2.254 6.343 5.63 6.343 2.777 0 4.847-1.443 4.847-3.556 0-1.438-1.208-2.127-1.27-2.127-.236 1.234-.868 3.31-3.644 3.31-1.618 0-3.013-1.118-3.013-2.582 0-2.09 1.984-2.847 3.55-2.847.586 0 1.294.04 1.663.114 0-.637-.54-1.728-1.9-1.728-1.25 0-1.566.405-1.967.868ZM8.716 8.19c-2.04 0-2.304.87-2.304 1.416 0 .878 1.043 1.168 1.6 1.168 1.02 0 2.067-.282 2.232-2.423a6.2 6.2 0 0 0-1.528-.161" />
						</svg>
						<h5 class="font-semibold text-xl group-hover:text-red-600 transition-all">Threads</h5>
					</a>
					<p class="text-gray-400 text-sm tracking-wide uppercase mt-2">B11N_GYM</p>
				</div>

				<div class="border-b border-dotted border-gray-400 pb-3 mb-3 group">
					<a href="https://www.instagram.com/biin_gym/" class="flex items-center gap-3 text-white group-hover:text-red-600 transition-all" target="_blank">
						<i class="fab fa-instagram text-xl group-hover:text-red-600"></i>
						<h5 class="font-semibold text-xl group-hover:text-red-600 transition-all">Instagram</h5>
					</a>
					<p class="text-gray-400 text-sm tracking-wide uppercase mt-2">BIIN_GYM</p>
				</div>

				<div class="border-b border-dotted border-gray-400 pb-3 mb-3 group">
					<a href="https://wa.me/6283194288423" class="flex items-center gap-3 text-white group-hover:text-red-600 transition-all" target="_blank">
						<i class="fab fa-whatsapp text-xl group-hover:text-red-600"></i>
						<h5 class="font-semibold text-xl group-hover:text-red-600 transition-all">Whatsapp</h5>
					</a>
					<p class="text-gray-400 text-sm tracking-wide mt-2">0831 9428 8423</p>
				</div>

				<div class="border-b border-dotted border-gray-400 pb-3 mb-3 group">
					<a href="mailto:sobiin77@gmail.com" class="flex items-center gap-3 text-white group-hover:text-red-600 transition-all" target="_blank">
						<i class="fas fa-envelope text-xl group-hover:text-red-600"></i>
						<h5 class="font-semibold text-xl group-hover:text-red-600 transition-all">Email</h5>
					</a>
					<p class="text-gray-400 text-sm tracking-wide mt-2">SOBIIN77@GMAIL.COM</p>
				</div>
			</div>


		</div>
		<hr class="border-t border-white">

		<div class="footer__bar">
			&copy; {{ date('Y') }} B11N Gym. All rights reserved. Part of
			<a href="{{ route('home') }}" class="text-secondary hover:text-white transition-colors duration-300 font-semibold">B1NG Empire</a>.
		</div>
	</footer>

	<script src="https://unpkg.com/scrollreveal"></script>
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/script.js"></script>
	<script>
		document.querySelectorAll('a[href^="#"]').forEach(anchor => {
			anchor.addEventListener('click', function(e) {
				e.preventDefault();
				const targetId = this.getAttribute('href');
				const targetElement = document.querySelector(targetId);

				if (targetElement) {
					window.scrollTo({
						top: targetElement.offsetTop - 70, // Offset untuk menghindari overlap dengan header
						behavior: 'smooth',
					});
				}
			});
		});

		const sections = document.querySelectorAll('section'); // Pastikan setiap bagian diberi tag <section>
		const navItems = document.querySelectorAll('.nav-item');

		function handleScroll() {
			let activeFound = false;
			sections.forEach(section => {
				const rect = section.getBoundingClientRect();
				if (rect.top <= 100 && rect.bottom >= 100 && !activeFound) {
					activeFound = true;
					const id = section.getAttribute('id');
					navItems.forEach(item => {
						item.classList.toggle(
							'active',
							item.querySelector('a').getAttribute('href') === `#${id}`
						);
					});
				}
			});

			if (!activeFound) {
				navItems.forEach(item => item.classList.remove('active'));
			}
		}

		window.addEventListener('scroll', handleScroll);
		handleScroll(); // Jalankan sekali untuk memastikan efek langsung terjadi

		let selectedMembershipTitle = "";
		let selectedPaymentMethod = ""; // Tambahkan variabel ini untuk menyimpan metode pembayaran

		function openPaymentModal(title) {
			selectedMembershipTitle = title;
			document.getElementById("membershipTitle").textContent = title;
			document.getElementById("paymentOptions").classList.remove("d-none");
			document.getElementById("qrisPayment").classList.add("d-none");
			document.getElementById("transferPayment").classList.add("d-none");
			new bootstrap.Modal(document.getElementById("paymentModal")).show();
		}

		function selectPaymentMethod(method) {
			selectedPaymentMethod = method; // Simpan metode pembayaran yang dipilih
			document.getElementById("paymentOptions").classList.add("d-none");
			if (method === "qris") {
				document.getElementById("qrisPayment").classList.remove("d-none");
				document.getElementById("qrisMembershipTitle").textContent = selectedMembershipTitle;
			} else if (method === "transfer") {
				document.getElementById("transferPayment").classList.remove("d-none");
				document.getElementById("transferMembershipTitle").textContent = selectedMembershipTitle;
			}
		}

		function sendPaymentConfirmation(method) {
			const email = "naufalfathi37@gmail.com"; // Ganti dengan email Anda
			const whatsappNumber = "6283194288423"; // Ganti dengan nomor WhatsApp Anda

			const messageBase = `Halo, saya ingin mengonfirmasi pembayaran untuk membership ${selectedMembershipTitle} via (${selectedPaymentMethod.toUpperCase()}).\n`;
			const messageFooter = `Tolong lampirkan bukti pembayaran Anda disini. \n\nTerima kasih.`;

			if (method === "email") {
				const subject = `Konfirmasi Pembayaran via ${selectedPaymentMethod.toUpperCase()} untuk member ${selectedMembershipTitle}`;
				const body = `${messageBase}${messageFooter}`;
				window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
			} else if (method === "whatsapp") {
				const message = `${messageBase}${messageFooter}`;
				window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`, "_blank");
			}
		}


		function goBackToOptions() {
			document.getElementById("paymentOptions").classList.remove("d-none");
			document.getElementById("qrisPayment").classList.add("d-none");
			document.getElementById("transferPayment").classList.add("d-none");
		}

		document.addEventListener('DOMContentLoaded', function() {
			const swiper = new Swiper('.swiper', {
				loop: true,
				autoplay: {
					delay: 3000,
					disableOnInteraction: false,
				},
				pagination: {
					el: '.swiper-pagination',
					clickable: true,
					dynamicBullets: true, // Pagination berubah saat swipe
					dynamicMainBullets: 5, // Hanya tampilkan 5 bullet, sisanya bisa digeser
				},
			});
		});


		document.addEventListener('DOMContentLoaded', function() {
			const thumbnails = document.querySelectorAll('.gallery-thumbnail');
			const modalImage = document.getElementById('modalImage');
			const modalTitle = document.getElementById('modalTitle');
			let currentIndex = 0;

			// Array untuk menyimpan data gambar
			const galleryData = Array.from(thumbnails).map((thumbnail, index) => ({
				src: thumbnail.getAttribute('data-src'),
				title: thumbnail.getAttribute('data-title'),
				index: index,
			}));

			// Klik pada gambar untuk membuka modal
			thumbnails.forEach((thumbnail, index) => {
				thumbnail.addEventListener('click', () => {
					currentIndex = index;
					updateModal();
				});
			});

			// Fungsi untuk memperbarui modal
			function updateModal() {
				const {
					src,
					title
				} = galleryData[currentIndex];
				modalImage.src = src;
				modalTitle.textContent = title;
			}

			// Navigasi gambar
			document.querySelector('.prev-image').addEventListener('click', () => {
				currentIndex = (currentIndex - 1 + galleryData.length) % galleryData.length;
				updateModal();
			});

			document.querySelector('.next-image').addEventListener('click', () => {
				currentIndex = (currentIndex + 1) % galleryData.length;
				updateModal();
			});
		});

		const trigger = document.querySelector("menu > .trigger");
		trigger.addEventListener('click', (e) => {
			e.currentTarget.parentElement.classList.toggle("open");
		});
	</script>

	<script>
		document.getElementById('paymentForm').addEventListener('submit', function(event) {
			event.preventDefault();

			// 1. Definisi Elemen
			const form = this;
			const submitBtn = form.querySelector('button[type="submit"]');
			const originalBtnText = submitBtn.innerHTML; // Simpan teks asli tombol "Upload"
			const modalElement = document.getElementById('paymentConfirmationModal');
			const modalInstance = bootstrap.Modal.getInstance(modalElement);

			// 2. Aktifkan Loading State
			submitBtn.disabled = true;
			submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';

			// 3. Siapkan Data
			let formData = new FormData(this);

			// 4. Kirim Request
			fetch('/payment/upload', {
					method: 'POST',
					body: formData,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
						// Jangan set 'Content-Type' secara manual saat pakai FormData, 
						// browser akan otomatis mengaturnya termasuk boundary-nya.
					}
				})
				.then(response => {
					// Cek jika response sukses (200 OK)
					if (response.ok) {
						return response.json();
					}
					// Jika error validasi (422) atau server error (500)
					return response.json().then(err => {
						throw err;
					});
				})
				.then(data => {
					// --- SUKSES ---
					console.log('Success:', data);

					// Tampilkan pesan sukses (Bisa ganti SweetAlert biar lebih cantik)
					alert("Berhasil! " + data.message + "\nOrder ID: " + data.order_id);

					// Reset form agar bersih jika mau upload lagi
					form.reset();

					// Tutup Modal
					if (modalInstance) {
						modalInstance.hide();
					}
				})
				.catch(error => {
					// --- ERROR ---
					console.error('Error:', error);

					let errorMessage = "Terjadi kesalahan saat mengupload.";

					// Cek apakah ini error validasi dari Laravel (Status 422)
					if (error.errors) {
						errorMessage = "Gagal Validasi:\n";
						// Loop error message dari Laravel
						for (const [key, messages] of Object.entries(error.errors)) {
							errorMessage += `- ${messages[0]}\n`;
						}
					} else if (error.message) {
						errorMessage = error.message;
					}

					alert(errorMessage);
				})
				.finally(() => {
					// --- FINALLY (Selalu dijalankan) ---
					// 5. Matikan Loading State (Kembalikan tombol seperti semula)
					submitBtn.disabled = false;
					submitBtn.innerHTML = originalBtnText;
				});
		});

		function showPaymentConfirmationModal() {
			var paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
			var paymentConfirmationModal = new bootstrap.Modal(document.getElementById('paymentConfirmationModal'));

			paymentModal.hide(); // Tutup modal pertama
			setTimeout(() => {
				paymentConfirmationModal.show(); // Buka modal kedua setelah yang pertama tertutup
			}, 500); // Delay sedikit biar animasi smooth
		}
	</script>

</body>

</html>