<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/kost.png'))">

    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    @vite('resources/css/home.css')
    <title>Multicore Website</title>
</head>

<body>
    <nav>
        <div class="nav__bar">
            <div class="nav__header">
                <div class="logo nav__logo">
                    <div class="w-14 h-14 sm:w-16 mt-4 sm:h-16">
                        <a href="#"><img src="assets/Logo/kost.png" alt="logo" /></a>
                    </div>
                    <span>KOST ISTANA<br />MERDEKA 3</span>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links" id="nav-links">
                <li class="nav-item"><a href="#header" class="nav-link">HOME</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">TENTANG KAMI</a></li>
                <li class="nav-item"><a href="#website" class="nav-link">FASILITAS</a></li>
                <li class="nav-item"><a href="#store" class="nav-link">KAMAR</a></li>
                <li class="nav-item"><a href="#blog" class="nav-link">BLOG</a></li>
                <li class="nav-item"><a href="#membership" class="nav-link">HUBUNGI KAMI</a></li>
            </ul>
        </div>
    </nav>

    <menu class="z-50">
        <a href="{{ route('home') }}" class="action"><i class="fas fa-home"></i></a>
        <a href="{{ route('kost') }}" class="action"><i class="fas fa-bed"></i></a>
        <a href="{{ route('index') }}" class="action"><img src="assets/Logo/biin.png" alt="B11N Gym" /></a>
        <a href="{{ route('kinggym') }}" class="action bg-cover object-cover"><img src="assets/Logo/last.png" alt="K1NG Gym" /></a>
        <a href="#" class="trigger"><i class="fas fa-plus"></i></a>
    </menu>

    <header class="header" id="home" style="
         background-image: linear-gradient(
             to left,
             rgba(0, 0, 0, 0.2),
             rgba(0, 0, 0, 0.9)
         ),
         url('assets/Home/kost.png');
         background-size: cover;
		background-position: center center;
         background-repeat: no-repeat;
     ">
        <div class="section__container header__container">
            <p class="section__subheader">WEBSITE KOST ISTANA MERDEKA 3</p>
            <h1>Coming<br />Soon</h1>
            <button class="btn">Halo, senang melihat anda 👋🏻</button>
        </div>
    </header>


    <footer class="footer">
        <div class="footer__bar">
            Copyright © 2024 Mullticore. All rights reserved.
        </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="assets/js/home.js"></script>
</body>

</html>