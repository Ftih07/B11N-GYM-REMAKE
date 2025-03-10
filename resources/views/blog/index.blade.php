<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/empire.png'))">
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=GFS+Didot&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <title>B1NG EMPIRE Blog</title>
    <link rel="stylesheet" href="/assets/css/blog.css?v=1.1">
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="bg-white dark:bg-black transition-colors duration-300">
    <nav class="fixed">
        <div class="nav__bar bg-white dark:bg-gray-600">
            <div class="nav__header">
                <div class="logo nav__logo">
                    <div class="w-14 h-14 sm:w-16 mt-4 sm:h-16">
                        <a href="#"><img src="assets/Logo/empire.png" alt="logo" /></a>
                    </div>
                    <span>B1NG EMPIRE<br />BLOG</span>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links dark:text-white text-black" id="nav-links">
                <li>
                    <a href="{{ route('home') }}"
                        class="{{ Route::currentRouteName() === 'home' ? 'active' : '' }}">HOME</a>
                </li>
                <li>
                    <a href="{{ route('index') }}"
                        class="{{ Route::currentRouteName() === 'index' ? 'active' : '' }}">B11N GYM</a>
                </li>
                <li>
                    <a href="{{ route('kinggym') }}"
                        class="{{ Route::currentRouteName() === 'kinggym' ? 'active' : '' }}">K1NG GYM</a>
                </li>
                <li>
                    <a href="{{ route('kost') }}"
                        class="{{ Route::currentRouteName() === 'kinggym' ? 'active' : '' }}">ISTANA MERDEKA 03</a>
                </li>
                <li>
                    <a href="{{ route('product.index') }}"
                        class="{{ Route::currentRouteName() === 'product.index' ? 'active' : '' }}">STORE</a>
                </li>
                <div class="mode rounded-full" id="switch-mode">
                    <div class="btn__indicator">
                        <div class="btn__icon-container">
                            <i class="btn__icon fa-solid"></i>
                        </div>
                    </div>
                </div>
            </ul>
        </div>
    </nav>
    @foreach ($logo as $logo)
    <menu class="z-50">
        <a href="{{ route('home') }}" class="action"><img src="assets/Logo/empire.png" alt="B1NG Empire" /></a>
        <a href="{{ route('kost') }}" class="action"><img src="assets/Logo/kost.png" alt="Istana Merdeka 03" /></a>
        <a href="{{ route('index') }}" class="action"><img src="assets/Logo/biin.png" alt="B11N Gym" /></a>
        <a href="{{ route('kinggym') }}" class="action bg-cover object-cover"><img src="assets/Logo/last.png" alt="K1NG Gym" /></a>
        <a href="#" class="trigger"><i class="fas fa-plus"></i></a>
    </menu>
    @endforeach

    <header class="header" id="home" style="
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
            <p class="section__subheader sm:mt-28">B1NG EMPIRE BLOG</p>
            <h1>Blog<br />Seputar</h1>
            <div class="mt-6">
                <a
                    href="#"
                    class="inline-block py-3 px-8 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-xl sm:rounded-full shadow-lg transition duration-300">
                    B11N Gym | K1NG Gym | Kost Istana Merdeka 03
                </a>
            </div>
        </div>
    </header>

    <section class="booking">
        <div class="section__container booking__container">
        </div>
    </section>



    <!-- Sesuaikan dengan layout Anda -->

    @section('content')
    <section class="section__container news__container" id="b11n-news">
        <div class="news__header">
            <div>
                <p class="section__subheader dark:text-gray-300">B11N Gym</p>
                <h2 class="section__header dark:text-white">B11N Gym Blogs</h2>
            </div>
        </div>
        <div class="news__grid">

            @foreach ($b11nBlogs as $blog)
            <div class="news__card rounded-lg shadow-md p-5 bg-white dark:bg-slate-900">
                <a href="{{ route('blog.show', $blog->id) }}">
                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="h-150 sm:h-[300px] object-cover" />
                    <div class="news__card__title">
                        <p>{{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</p>
                    </div>
                    <h4 class="text-black dark:text-white">{{ $blog->title }}</h4>
                    <p>{!! Str::limit($blog->content, 100) !!}</p>
                </a>
            </div>
            @endforeach

        </div>
    </section>

    <section class="section__container news__container" id="k1ng-news">
        <div class="news__header">
            <div>
                <p class="section__subheader dark:text-gray-300">K1NG Gym</p>
                <h2 class="section__header dark:text-white">K1NG Gym Blogs</h2>
            </div>
        </div>
        <div class="news__grid">

            @foreach ($k1ngBlogs as $blog)
            <div class="news__card rounded-lg shadow-md p-5 bg-white dark:bg-slate-900">
                <a href="{{ route('blog.show', $blog->id) }}">

                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="h-150 sm:h-[300px] object-cover" />
                    <div class="news__card__title">
                        <p>{{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</p>
                    </div>
                    <h4 class="text-black dark:text-white">{{ $blog->title }}</h4>
                    <p>{!! Str::limit($blog->content, 100) !!}</p>
                </a>
            </div>
            @endforeach

        </div>
    </section>




    <footer class="footer mt-10">
        <div class="footer__bar">
            Copyright © 2024 B1NG EMPIRE. All rights reserved.
        </div>
    </footer>



    <script src="https://unpkg.com/scrollreveal"></script>
    <script>
        // Automatically remove notification after 3 seconds
        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.transition = 'opacity 0.5s';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500); // Remove after transition
            }
        }, 3000);

        document.addEventListener('DOMContentLoaded', function() {
            const toggleDarkMode = document.getElementById('switch-mode');
            const htmlElement = document.documentElement;
            const icon = document.querySelector('.btn__icon');

            // Jika localStorage belum ada, set default ke light mode
            if (!localStorage.theme) {
                localStorage.theme = 'light';
            }

            // Terapkan mode yang tersimpan di localStorage
            if (localStorage.theme === 'dark') {
                htmlElement.classList.add('dark');
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                htmlElement.classList.remove('dark');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }

            // Event toggle dark mode
            toggleDarkMode.addEventListener('click', function() {
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.theme = 'light';
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.theme = 'dark';
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
            });
        });

        const body = document.querySelector('body');
        const btn = document.querySelector('.mode');
        const icon = document.querySelector('.btn__icon');

        //to save the dark mode use the object "local storage".

        //function that stores the value true if the dark mode is activated or false if it's not.
        function store(value) {
            localStorage.setItem('darkmode', value);
        }

        //function that indicates if the "darkmode" property exists. It loads the page as we had left it.
        function load() {
            const darkmode = localStorage.getItem('darkmode');

            //if the dark mode was never activated
            if (!darkmode) {
                store(false);
                icon.classList.add('fa-sun');
            } else if (darkmode == 'true') { //if the dark mode is activated
                body.classList.add('darkmode');
                icon.classList.add('fa-moon');
            } else if (darkmode == 'false') { //if the dark mode exists but is disabled
                icon.classList.add('fa-sun');
            }
        }


        load();

        btn.addEventListener('click', () => {

            body.classList.toggle('darkmode');
            icon.classList.add('animated');

            //save true or false
            store(body.classList.contains('darkmode'));

            if (body.classList.contains('darkmode')) {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }

            setTimeout(() => {
                icon.classList.remove('animated');
            }, 500)
        })


        const menuBtn = document.getElementById("menu-btn");
        const navLinks = document.getElementById("nav-links");
        const menuBtnIcon = menuBtn.querySelector("i");

        menuBtn.addEventListener("click", (e) => {
            navLinks.classList.toggle("open");

            const isOpen = navLinks.classList.contains("open");
            menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
        });

        navLinks.addEventListener("click", (e) => {
            navLinks.classList.remove("open");
            menuBtnIcon.setAttribute("class", "ri-menu-line");
        });

        const scrollRevealOption = {
            distance: "50px",
            origin: "bottom",
            duration: 1000,
        };

        // header container
        ScrollReveal().reveal(".header__container .section__subheader", {
            ...scrollRevealOption,
        });

        ScrollReveal().reveal(".header__container h1", {
            ...scrollRevealOption,
            delay: 500,
        });

        ScrollReveal().reveal(".header__container .btn", {
            ...scrollRevealOption,
            delay: 1000,
        });

        // room container
        ScrollReveal().reveal(".room__card", {
            ...scrollRevealOption,
            interval: 500,
        });

        // feature container
        ScrollReveal().reveal(".feature__card", {
            ...scrollRevealOption,
            interval: 500,
        });

        // news container
        ScrollReveal().reveal(".news__card", {
            ...scrollRevealOption,
            interval: 500,
        });

        const trigger = document.querySelector("menu > .trigger");
        trigger.addEventListener('click', (e) => {
            e.currentTarget.parentElement.classList.toggle("open");
        });
    </script>
</body>

</html>