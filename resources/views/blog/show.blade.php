<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/mc.png'))">
    <title>{{ $blog->title }}</title>
    @vite('resources/css/app.css')
    @vite('resources/css/index.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
        rel="stylesheet" />
</head>

<body class="m-0 p-0 bg-white text-white font-poppins dark:bg-black transition-colors duration-300">

    @if (session('success'))
    <div
        id="notification"
        class="flex items-center bg-green-500 text-white text-center py-2 px-4 rounded-lg shadow-lg fixed top-4 right-4 z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <nav class="fixed bg-gray-100 dark:bg-black">
        <div class="nav__bar">
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="#"><img src="{{ asset('assets/Logo/colab.png') }}" alt="logo" /></a>
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
                    <a href="{{ route('blogs.index') }}"
                        class="{{ Route::currentRouteName() === 'blogs.index' ? 'active' : '' }}">BLOG</a>
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
                        class="{{ Route::currentRouteName() === 'kost' ? 'active' : '' }}">KOST</a>
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
        <a href="{{ route('home') }}" class="action"><i class="fas fa-home"></i></a>
        <a href="{{ route('kost') }}" class="action"><i class="fas fa-bed"></i></a>
        <a href="{{ route('index') }}" class="action"><img src="{{ asset('assets/Logo/biin.png') }}" alt="B11N Gym" /></a>
        <a href="{{ route('kinggym') }}" class="action bg-cover object-cover"><img src="{{ asset('assets/Logo/last.png') }}" alt="K1NG Gym" /></a>
        <a href="#" class="trigger"><i class="fas fa-plus"></i></a>
    </menu>
    @endforeach


    <section class="blog-detail max-w-[800px] mx-auto px-4 py-24">
        <article class="blog__content">
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full rounded-lg mb-6">
            <p class="text-sm text-gray-500">{{ $blog->created_at->format('F d, Y') }}</p>
            <h1 class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-white my-6">{{ $blog->title }}</h1>
            <div class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                {!! $blog->content !!} <!-- Rich Editor content -->
            </div>
        </article>

        {{-- Artikel Lainnya --}}
        <section class="related-articles mt-12">
            <h2 class="text-2xl font-semibold mb-6 text-black dark:text-white">Artikel Lainnya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($relatedBlogs as $relatedBlog)
                <div class="blog__card">
                    <a href="{{ route('blog.show', $relatedBlog->id) }}">
                        <img src="{{ asset('storage/' . $relatedBlog->image) }}" alt="{{ $relatedBlog->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $relatedBlog->title }}</h4>
                        <p class="text-sm text-gray-500">{{ $relatedBlog->created_at->format('F d, Y') }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </section>
    </section>

    <script src="{{ asset('assets/js/script.js') }}"></script>



    <footer class="footer mt-10" id="contact">
        <div class="footer__bar">
            Copyright © 2024 Multicore. All rights reserved.
        </div>
    </footer>

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

            // Cek mode dari LocalStorage
            if (localStorage.theme === 'dark') {
                htmlElement.classList.add('dark');
            } else if (localStorage.theme === 'light') {
                htmlElement.classList.remove('dark');
            }

            // Event toggle
            toggleDarkMode.addEventListener('click', function() {
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.theme = 'light'; // Simpan ke LocalStorage
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.theme = 'dark'; // Simpan ke LocalStorage
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

        // Nav Utama
        const trigger = document.querySelector("menu > .trigger");
        trigger.addEventListener('click', (e) => {
            e.currentTarget.parentElement.classList.toggle("open");
        });
    </script>
</body>

</html>