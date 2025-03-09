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

    <!-- Tambahkan CSS Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Tambahkan JS Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    @vite('resources/css/kost.css')
    <title>Kost Istana Merdeka 03</title>
</head>

<body>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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
                <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">HOME</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">TENTANG KAMI</a></li>
                <li class="nav-item"><a href="#website" class="nav-link">FASILITAS</a></li>
                <li class="nav-item"><a href="#room" class="nav-link">KAMAR</a></li>
                <li class="nav-item"><a href="#feature" class="nav-link">FASILITAS</a></li>
                <li class="nav-item"><a href="#booking" class="nav-link">PEMESANAN</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">HUBUNGI KAMI</a></li>
            </ul>
        </div>
    </nav>

    <menu class="z-50">
        <a href="{{ route('home') }}" class="action"><img src="assets/Logo/empire.png" alt="B1NG Empire" /></a>
        <a href="{{ route('kost') }}" class="action"><img src="assets/Logo/kost.png" alt="Istana Merdeka 03" /></a>
        <a href="{{ route('index') }}" class="action"><img src="assets/Logo/biin.png" alt="B11N Gym" /></a>
        <a href="{{ route('kinggym') }}" class="action bg-cover object-cover"><img src="assets/Logo/last.png" alt="K1NG Gym" /></a>
        <a href="#" class="trigger"><i class="fas fa-plus"></i></a>
    </menu>

    <!-- Header -->
    <header class="header" id="home" style="
         background-image: linear-gradient(
             to left,
             rgba(0, 0, 0, 0.2),
             rgba(0, 0, 0, 0.9)
         ),
         url('assets/facilities/ber-ac.png');
         background-size: cover;
		background-position: center center;
         background-repeat: no-repeat;
     ">
        <div class="section__container header__container">
            <p class="section__subheader">WELCOME TO</p>
            <h1>Kost Putra<br />Istana Merdeka 03</h1>
            <button class="btn">Halo, senang melihat anda 👋🏻</button>
        </div>
    </header>

    <!-- About -->
    <section class="about" id="about">
        <div class="section__container about__container">
            <div class="about__grid">
                <div class="about__image">
                    <img src="assets/facilities/ac.png" alt="about" />
                </div>
                <div class="about__card">
                    <span><i class="ri-user-line"></i></span>
                    <h4>Fasilitas Premium</h4>
                    <p>
                        Kamar ber-AC, WiFi cepat, kasur empuk, dan lemari luas untuk kenyamanan maksimal.
                    </p>
                </div>
                <div class="about__image">
                    <img src="assets/home/biin-gym.jpg" alt="about" />
                </div>
                <div class="about__card">
                    <span><i class="ri-calendar-check-line"></i></span>
                    <h4>Lokasi Strategis</h4>
                    <p>
                        Dekat UNSOED dan UMP, memudahkan akses ke kampus dan fasilitas umum.
                    </p>
                </div>
            </div>
            <div class="about__content">
                <p class="section__subheader">ABOUT US</p>
                <h2 class="section__header">Kost Istana Merdeka 03</h2>
                <p class="section__description">
                    Selamat datang di Kost Istana Merdeka 3, pilihan terbaik bagi Anda yang mencari hunian premium dengan kenyamanan maksimal. Terletak strategis di atas B11N Gym Purwokerto, kost khusus putra ini menawarkan suasana eksklusif dan tenang, jauh dari kebisingan meskipun berada di area gym.

                    Nikmati fasilitas terbaik seperti AC di setiap kamar dan WiFi berkecepatan tinggi, memastikan Anda tetap nyaman saat beristirahat, belajar, atau bekerja. </p>
                <button class="btn"><a href="#booking">Pesan Sekarang</a></button>
            </div>
        </div>
    </section>

    <!-- Room -->
    <section class="room__container" id="room">
        <p class="section__subheader">Kamar</p>
        <h2 class="section__header">Tersedia 2 Jenis Kamar</h2>
        <div class="room__grid">
            <div class="room__card">
                <img src="assets/home/istana-merdeka.jpg" alt="room"/>
                <div class="room__card__details">
                    <div>
                        <h4>Kamar Non AC</h4>
                        <p>Nyaman, terjangkau, dan cocok untuk hunian yang hemat.</p>
                    </div>
                    <h3>Rp350.000<span>/bulan</span></h3>
                </div>
            </div>
            <div class="room__card">
                <img src="assets/facilities/ber-ac.png" alt="room" />
                <div class="room__card__details">
                    <div>
                        <h4>Kamar Ber-AC</h4>
                        <p>Dilengkapi AC untuk kenyamanan ekstra di setiap waktu.</p>
                    </div>
                    <h3>Rp750.000<span>/bulan</span></h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Room Tour -->
    <section class="intro">
        <div class="section__container intro__container">
            <div class="intro__cotent">
                <p class="section__subheader">ROOM TOUR</p>
                <h2 class="section__header">Temukan Kenyamanan di Kost Istana Merdeka 03</h2>
                <p class="section__description">
                    Nikmati pengalaman tinggal di Kost Istana Merdeka 3 yang nyaman dan eksklusif. Dengan kamar ber-AC yang bersih dan tertata rapi, kami menghadirkan suasana yang tenang dan kondusif untuk beristirahat maupun beraktivitas. Saksikan video cinematic kami untuk melihat langsung fasilitas yang tersedia dan rasakan kenyamanan yang kami tawarkan.
                </p>
                <button class="btn"><a href="#booking">Pesan Sekarang</a></button>
            </div>
            <div class="intro__video">
                <video src="assets/0305.mp4" autoplay muted loop></video>
            </div>
        </div>
    </section>

    <!-- Feature -->
    <section class="section__container feature__container" id="feature">
        <p class="section__subheader">FASILITAS</p>
        <h2 class="section__header">Fasilitas Kami</h2>
        <div class="feature__grid">
            <div class="feature__card">
                <span><i class="ri-price-tag-3-line"></i></span>
                <h4>Harga Terjangkau</h4>
                <p>
                    Nikmati hunian premium dengan fasilitas lengkap tanpa menguras kantong!
                </p>
            </div>

            <div class="feature__card">
                <span><i class="ri-snowy-line"></i></span>
                <h4>Kamar Ber-AC</h4>
                <p>
                    Udara tetap sejuk sepanjang hari untuk tidur lebih nyaman dan berkualitas.
                </p>
            </div>

            <div class="feature__card">
                <span><i class="ri-wifi-line"></i></span>
                <h4>WiFi Cepat</h4>
                <p>
                    Internet stabil dan kencang untuk kerja, kuliah, atau sekadar streaming santai.
                </p>
            </div>

            <div class="feature__card">
                <span><i class="ri-hotel-bed-line"></i></span>
                <h4>Kasur Nyaman</h4>
                <p>
                    Kasur empuk dan bersih untuk istirahat yang lebih nyenyak setiap malam.
                </p>
            </div>

            <div class="feature__card">
                <span><i class="ri-store-line"></i></span>
                <h4>Lemari Luas</h4>
                <p>
                    Ruang penyimpanan yang cukup untuk menjaga barang tetap rapi dan aman.
                </p>
            </div>

            <div class="feature__card">
                <span><i class="ri-shower-line"></i></span>
                <h4>Kamar Mandi Bersih</h4>
                <p>
                    Kamar mandi luar yang selalu terjaga kebersihannya untuk kenyamanan bersama.
                </p>
            </div>

            <div class="feature__card">
                <span><i class="ri-community-line"></i></span>
                <h4>Lingkungan Kondusif</h4>
                <p>
                    Suasana tenang dan nyaman, cocok untuk belajar maupun beristirahat.
                </p>
            </div>

            <div class="feature__card">
                <span><i class="ri-map-pin-line"></i></span>
                <h4>Lokasi Strategis</h4>
                <p>
                    Dekat kampus UNSOED dan UMP, memudahkan mobilitas ke berbagai tempat.
                </p>
            </div>

            <div class="feature__card">
                <span><i class="ri-shield-check-line"></i></span>
                <h4>Keamanan Terjamin</h4>
                <p>
                    Akses terbatas dan sistem keamanan yang menjaga kenyamanan penghuni 24/7.
                </p>
            </div>

        </div>
    </section>

    <!--Testimoni-->
    <section class="client__container max-w-[1200px] mx-auto px-4 py-20" id="client">
        <h2 class="section__header text-sm font-semibold font-header text-text-dark text-center">OUR TESTIMONIALS</h2>
        <!-- Slider main container -->
        <div class="swiper">
            <!-- Additional required wrapper -->
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
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Gallery -->
    <section class="gallery" id="gallery">
        <div class="section__container menu__container">
            <div class="menu__header">
                <div>
                    <p class="section__subheader">GALLERY</p>
                    <h2 class="section__header">Gallery Kami</h2>
                    <br>
                </div>
                <div class="section__nav">
                    <span><i class="ri-arrow-left-line"></i></span>
                    <span><i class="ri-arrow-right-line"></i></span>
                </div>
            </div>
            <div class="menu__images">
                @foreach ($gallery as $item)
                <img
                    src="{{ asset('storage/' . $item->image) }}"
                    alt="{{ $item->title }}"
                    class="gallery-thumbnail" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- Booking -->
    <div class="booking-section" id="booking">
        <section class="booking-container">
            <form class="booking-form" action="{{ route('kost.book') }}" method="POST" enctype="multipart/form-data" id="bookingForm">
                <h2 class="section__header-booking">Form Pemesanan Kamar</h2>
                <p class="booking-subtext">Kost Istana Merdeka 03</p>
                <hr>
                @csrf

                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Nomor WhatsApp</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="date">Pilih Tanggal</label>
                <input type="date" id="date" name="date" required>
                <span id="tooltip" class="hidden absolute bg-red-500 text-white text-xs rounded p-1"></span>

                <label for="room_number">Pilih Nomor Kamar</label>
                <div class="layout">
                    <div class="stairs">Tangga</div>
                    <div class="bathrooms">
                        <div class="bathroom">Kamar mandi 01</div>
                        <div class="bathroom">Kamar mandi 02</div>
                    </div>
                    <div class="container-room">
                        @foreach(array_chunk(range(1, 10), 2) as $row)
                        <div class="room-row">
                            @foreach($row as $room)
                            @php
                            $isBooked = in_array($room, $bookedRooms ?? []);
                            @endphp
                            <div class="room {{ $isBooked ? 'booked' : '' }}" data-room="{{ $room }}">
                                {{ str_pad($room, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    <div class="balcony">Balkon</div>
                </div>

                <input type="hidden" id="room_number" name="room_number" required>

                <label for="room_type">Pilih Jenis Kamar</label>
                <select id="room_type" name="room_type">
                    <option value="750rb - AC">750rb - AC</option>
                    <option value="350rb - Non AC">350rb - Non AC</option>
                </select>

                <label for="paymentMethod">Metode Pembayaran</label>
                <select id="paymentMethod" name="paymentMethod" required>
                    <option value="" selected disabled>Pilih Metode</option>
                    <option value="qris">QRIS</option>
                    <option value="transfer">Transfer Bank</option>
                </select>

                <!-- Bagian QRIS -->
                <div id="qrisSection" class="hidden">
                    <p>Silakan scan QRIS di bawah ini:</p>
                    <div class="place-items-center">
                        <img
                            src="/assets/img/pembayaran/qris-barcode.png"
                            alt="QRIS Barcode"
                            class="img-fluid w-full md:w-1/2" />
                    </div>
                </div>

                <!-- Bagian Transfer -->
                <div id="transferSection" class="hidden">
                    <p class="text-center">Nomor Rekening: <strong>0461701506</strong></p>
                    <p class="text-center">An: <strong>Sobiin</strong></p>
                    <div class="place-items-center">
                        <img
                            src="/assets/img/pembayaran/bca.png"
                            alt="Bank BCA"
                            class="img-fluid w-1/2" />
                    </div>
                </div>

                <label for="paymentProof">Upload Bukti Pembayaran</label>
                <input type="file" id="paymentProof" name="paymentProof" accept="image/*" required>

                <button type="submit" class="booking-btn" id="bookingNow" disabled>Booking Sekarang</button>
            </form>
        </section>
    </div>

    <!-- Booking -->
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
                <a href="{{ route('blog.show', $blog->id) }}" target="_blank">
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
                        <a href="#"><img src="assets/Logo/kost.png" alt="logo" class="mt-3" /></a>
                    </div> <span>KOST ISTANA<br />MERDEKA 03</span>
                </div>
                <p class="section__description">
                    Selamat datang di Kost Istana Merdeka 3, pilihan terbaik bagi Anda yang mencari hunian premium dengan kenyamanan maksimal. Terletak strategis di atas B11N Gym Purwokerto, kost khusus putra ini menawarkan suasana eksklusif dan tenang, jauh dari kebisingan meskipun berada di area gym.

                    Nikmati fasilitas terbaik seperti AC di setiap kamar dan WiFi berkecepatan tinggi, memastikan Anda tetap nyaman saat beristirahat, belajar, atau bekerja. </p>
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
                    <li><a href="{{ route('index') }}" target="_blank">B11N Gym Website</a></li>
                    <li><a href="{{ route('kinggym') }}" target="_blank">K1NG Gym Website</a></li>
                    <li><a href="{{ route('kost') }}" target="_blank">Kost Istana Merdeka 03 Website</a></li>
                    <li><a href="{{ route('product.index') }}" target="_blank">B11N & K1NG Gym Store</a></li>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper(".swiper", {
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    768: {
                        slidesPerView: 1,
                    },
                    1024: {
                        slidesPerView: 1,
                    },
                },
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paymentMethod = document.getElementById("paymentMethod");
            const qrisSection = document.getElementById("qrisSection");
            const transferSection = document.getElementById("transferSection");
            const paymentProof = document.getElementById("paymentProof");
            const bookingNow = document.getElementById("bookingNow");

            function checkForm() {
                if (paymentMethod.value && paymentProof.files.length > 0) {
                    bookingNow.removeAttribute("disabled");
                } else {
                    bookingNow.setAttribute("disabled", "true");
                }
            }

            paymentMethod.addEventListener("change", function() {
                qrisSection.classList.add("hidden");
                transferSection.classList.add("hidden");

                if (paymentMethod.value === "qris") {
                    qrisSection.classList.remove("hidden");
                } else if (paymentMethod.value === "transfer") {
                    transferSection.classList.remove("hidden");
                }

                checkForm();
            });

            paymentProof.addEventListener("change", checkForm);
        });

        document.getElementById("bookingForm").addEventListener("submit", function(event) {
            console.log("Form sedang dikirim...");
        });

        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000); // 5 detik



        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#date", {
                dateFormat: "Y-m-d", // Format tanggal (YYYY-MM-DD)
            });
        });

        document.querySelectorAll('.room:not(.booked)').forEach(room => {
            room.addEventListener('click', function() {
                document.querySelectorAll('.room').forEach(r => r.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('room_number').value = this.dataset.room;
            });
        });
    </script>



</body>

</html>