<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/empire.png'))">

    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    @vite('resources/css/home.css')
    <title>B1NG EMPIRE</title>
</head>
    <nav>
        <div class="nav__bar">
            <div class="nav__header">
                <div class="logo nav__logo">
                    <div class="w-14 h-14 sm:w-16 mt-4 sm:h-16">
                        <a href="#"><img src="assets/Logo/empire.png" alt="logo" /></a>
                    </div>
                    <span>B1NG<br />EMPIRE</span>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links" id="nav-links">
                <li class="nav-item"><a href="#header" class="nav-link">HOME</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">TENTANG KAMI</a></li>
                <li class="nav-item"><a href="#website" class="nav-link">WEBSITE KAMI</a></li>
                <li class="nav-item"><a href="#store" class="nav-link">STORE</a></li>
                <li class="nav-item"><a href="#blog" class="nav-link">BLOG</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">HUBUNGI KAMI</a></li>
            </ul>
        </div>
    </nav>

    <menu class="z-50">
        <a href="{{ route('home') }}" class="action"><img src="assets/Logo/empire.png" alt="B1NG Empire" /></a>
        <a href="{{ route('kost') }}" class="action"><img src="assets/Logo/kost.png" alt="Istana Merdeka 03" /></a>
        <a href="{{ route('gym.biin') }}" class="action"><img src="assets/Logo/biin.png" alt="B11N Gym" /></a>
        <a href="{{ route('gym.king') }}" class="action bg-cover object-cover"><img src="assets/Logo/last.png" alt="K1NG Gym" /></a>
        <a href="#" class="trigger"><i class="fas fa-plus"></i></a>
    </menu>

    <header class="header" id="header" style="
         background-image: linear-gradient(
             to left,
             rgba(0, 0, 0, 0.2),
             rgba(0, 0, 0, 0.9)
         ),
         url('assets/Hero/b11ngym.jpg');
         background-size: cover;
		background-position: center center;
         background-repeat: no-repeat;
     ">
        <div class="section__container header__container">
            <p class="section__subheader">WELCOME TO</p>
            <h1>B1NG<br />EMPIRE</h1>
            <button class="btn">Halo, senang melihat anda 👋🏻</button>
        </div>
    </header>

    <section class="about" id="about">
        <div class="section__container about__container">
            <div class="about__grid">
                <div class="about__image">
                    <img src="assets/home/biin-gym.jpg" alt="about" />
                </div>
                <div class="about__card">
                    <span><i class="fas fa-dumbbell"></i></span>
                    <h4>B11N & K1NG GYM</h4>
                    <p>
                        Dua tempat fitness & gym kami yang berada di Purwokerto
                    </p>
                </div>
                <div class="about__image">
                    <img src="assets/home/kost.jpg" alt="about" />
                </div>
                <div class="about__card">
                    <span><i class="fas fa-bed"></i></span>
                    <h4>Kost Istana Merdeka 3</h4>
                    <p> Tempat Kost Putra yang terletak diatas B11N Gym Purwokerto</p>
                </div>
            </div>
            <div class="about__content">
                <p class="section__subheader">TENTANG KAMI</p>
                <h2 class="section__header">Apa itu B1NG EMPIRE?</h2>
                <p class="section__description">
                    B1NG EMPIRE adalah sebuah konsep di mana beberapa bisnis atau layanan yang berbeda-beda, namun memiliki kesamaan dalam hal kepemilikan atau target audiens, digabungkan ke dalam satu website. Tujuannya adalah untuk memberikan pengalaman pengguna yang lebih baik, meningkatkan efisiensi, dan memperkuat branding. </p>
                <button class="btn"><a href="#website">Get Started</a></button>
            </div>
        </div>
    </section>

    <section class="room__container" id="website">
        <p class="section__subheader">Beberapa Ekosistem Kami Yang Menunjukkan Tempat Usaha Kami</p>
        <h2 class="section__header">Ekosistem Kami</h2>
        <div class="room__grid">
            <div class="room__card">
                <img src="assets/home/biin-gym.jpg" alt="b11n gym" />
                <div class="room__card__details">
                    <div>
                        <h4>B11N Gym Purwokerto</h4>
                        <p>Tempat gym yang saat ini menyandang status sebagai tempat gym termurah di Purwokerto</p>

                        <a href="{{ route('gym.biin') }}" target="_blank">
                            <button class="px-4 py-2 mt-3 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-md cursor-pointer outline-none border-none">Kunjungi Ekosistem</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="room__card">
                <img src="assets/home/king-gym.jpg" alt="b1ng gym" />
                <div class="room__card__details">
                    <div>
                        <h4>K1NG Gym Purwokerto</h4>
                        <p>Cabang dari B11N Gym yang baru buka beberapa bulan yang juga menyandang status sebagai tempat gym termurah di Purwokerto</p>
                        <a href="{{ route('gym.king') }}" target="_blank">
                            <button class="px-4 py-2 mt-3 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-md cursor-pointer outline-none border-none">Kunjungi Ekosistem</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="room__card">
                <img src="assets/home/istana-merdeka.jpg" alt="istana merdeka 03" />
                <div class="room__card__details">
                    <div>
                        <h4>Kost Istana Merdeka 3</h4>
                        <p>Kost khusus putra yang letaknya berada di lantai 2 B11N Gym Purwokerto </p>
                        <a href="{{ route('kost') }}" target="_blank">
                            <button class="px-4 py-2 mt-3 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-md cursor-pointer outline-none border-none">Kunjungi Ekosistem</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="intro" id="store">
        <div class="section__container intro__container">
            <div class="intro__cotent">
                <p class="section__subheader">STORE</p>
                <h2 class="section__header">B11N & K1NG Gym Store</h2>
                <p class="section__description">
                    B11N & K1NG Gym Store adalah toko yang menjual berbagai minuman protein yang dijual di B11N Gym & K1NG Gym Purwokerto, disini ada banyak jenis minuman baik itu susu protein, air mineral, suplement untuk gym, dan lainnya
                </p>
                <a href="{{ route('store.biin-king') }}" target="_blank">
                    <button class="btn">Kunjungi Website</button>
                </a>
            </div>
            <div class="intro__video">
                <img src="assets/home/store.png"></img>
            </div>
        </div>
    </section>

    <section class="section__container news__container" id="blog">
        <div class="news__header">
            <div>
                <p class="section__subheader">BLOG</p>
                <h2 class="section__header">B1NG EMPIRE Blog</h2>
                <p class="section__description">
                    B1NG EMPIRE Blog adalah Website Blog pribadi kami yang didalamnya berisi informasi, tips & trick, dan berita - berita terbaru yang berkaitan dengan B11N Gym, K1NG Gym, dan Kost Istana Merdeka 3.
                </p>
            </div>
        </div>
        <div class="news__grid">
            @foreach ($blog as $blog)
            <div class="news__card rounded-lg shadow-md p-5 bg-white dark:bg-slate-900">
                <a href="{{ route('blogs.show', $blog->id) }}" target="_blank">
                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="h-150 sm:h-[200px] object-cover" />
                    <div class="news__card__title">
                        <p>{{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</p>
                    </div>
                    <h4 class="text-black dark:text-white">{{ $blog->title }}</h4>
                    <p>{!! Str::limit($blog->content, 100) !!}</p>
                </a>
            </div>
            @endforeach
        </div>
        @if($blog->count() > 3)
        <div class=" mt-8 text-center">
            <a href="{{ route('blogs.index') }}" target="_blank">
                <button class="btn">
                    View All
                </button>
            </a>
        </div>
        @endif
    </section>


    <footer class="footer" id="contact">
        <div class="section__container footer__container">
            <div class="footer__col">
                <div class="logo footer__logo">
                    <div class="w-14 h-14 sm:w-16 mt-0 sm:h-16">
                        <a href="#"><img src="assets/Logo/empire.png" alt="logo" class="mt-3" /></a>
                    </div> <span>B1NG<br />EMPIRE</span>
                </div>
                <p class="section__description">
                    B1NG EMPIRE adalah sebuah konsep di mana beberapa bisnis atau layanan yang berbeda-beda, namun memiliki kesamaan dalam hal kepemilikan atau target audiens, digabungkan ke dalam satu website. Tujuannya adalah untuk memberikan pengalaman pengguna yang lebih baik, meningkatkan efisiensi, dan memperkuat branding. </p>
                </p>
                <ul class="footer__socials">
                    <li>
                        <a href="mailto:sobiin77@gmail.com"><i class="fas fa-envelope"></i></a>
                    </li>
                    <li>
                        <a href="https://wa.me/6281226110988"><i class="ri-whatsapp-line"></i></a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/biin_gym/"><i class="ri-instagram-fill"></i></a>
                    </li>
                    <li>
                        <a href="https://www.threads.net/@biin_gym?xmt=AQGzKh5EYkbE4G7JIjSwlirbjIADsXrxWWU6UuUKi1XKhFU"><i class="ri-threads-fill"></i></a>
                    </li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>Quick Link</h4>
                <div class="footer__links">
                    <li><a href="{{ route('home') }}" target="_blank">B1NG EMPIRE</a></li>
                    <li><a href="{{ route('gym.biin') }}" target="_blank">B11N Gym Website</a></li>
                    <li><a href="{{ route('gym.king') }}" target="_blank">K1NG Gym Website</a></li>
                    <li><a href="{{ route('kost') }}" target="_blank">Kost Istana Merdeka 03 Website</a></li>
                    <li><a href="{{ route('store.biin-king') }}" target="_blank">B11N & K1NG Gym Store</a></li>
                    <li><a href="{{ route('blogs.index') }}" target="_blank">B1NG EMPIRE Blog</a></li>
                </div>
            </div>
            <div class="footer__col">
                <h4>Hubungi Kami</h4>
                <div class="footer__links">
                    <a href="tel:+6289653847651">

                        <li>
                            <span><i class="ri-phone-fill"></i></span>
                            <div>
                                <h5>No. Telephone</h5>
                                <p>+62 896 5384 7651</p>
                            </div>
                        </li>
                    </a>
                    <a href="mailto:sobiin77@gmail.com">

                        <li>
                            <span><i class="ri-record-mail-line"></i></span>
                            <div>
                                <h5>Email</h5>
                                <p>sobiin77@gmail.com</p>
                            </div>
                        </li>
                    </a>
                    <a href="https://www.google.com/maps?q=Jl.+Masjid+Baru,+Arcawinangun,Kec.+Purwokerto+Timur,+Kab.+Banyumas" target="_blank">

                        <li>
                            <span><i class="ri-map-pin-2-fill"></i></span>
                            <div>
                                <h5>Alamat</h5>
                                <p>Jl. Masjid Baru, Arcawinangun, Kec. Purwokerto Timur, Kab. Banyumas</p>
                            </div>
                        </li>
                    </a>
                </div>
            </div>

        </div>
        <div class="footer__bar">
            Copyright © 2025 B1NG EMPIRE. All rights reserved.
        </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="assets/js/home.js"></script>

</body>

</html>