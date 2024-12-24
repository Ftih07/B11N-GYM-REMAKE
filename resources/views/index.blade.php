<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link
		href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
		rel="stylesheet" />
	<link
		rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

	@vite('resources/css/index.css')
	<title>Web Design Mastery | FitPhysique</title>
</head>

<body>
	<!--Navigasi-->
	<nav class="fixed text-white">
		<div class="nav__bar">
			<div class="nav__header">
				@foreach ($logo as $logo)
				<div class="w-12 h-12 object-cover object-center">
					<a href="#"><img src="{{ asset('storage/' . $logo->image) }}" alt="logo" /></a>
				</div>
				@endforeach
				<div class="nav__menu__btn" id="menu-btn">
					<i class="ri-menu-line"></i>
				</div>
			</div>
			<ul class="nav__links" id="nav-links">
				<li class="nav-item"><a href="#header" class="nav-link">HOME</a></li>
				<li class="nav-item"><a href="#about" class="nav-link">ABOUT</a></li>
				<li class="nav-item"><a href="#trainer" class="nav-link">TRAINER</a></li>
				<li class="nav-item"><a href="#client" class="nav-link">CLIENT</a></li>
				<li class="nav-item"><a href="#blog" class="nav-link">BLOG</a></li>
				<li class="nav-item"><a href="#contact" class="nav-link">CONTACT US</a></li>
			</ul>
		</div>
	</nav>

	<!--Hero-->
	<header class="header" id="header">
		<div class="relative flex items-center justify-center h-screen bg-black text-white"
			style="
         background-image: linear-gradient(
             to right,
             rgba(0, 0, 0, 0.2),
             rgba(0, 0, 0, 0.9)
         ),
         url('assets/Hero/b11ngym.jpg');
         background-size: cover;
		background-position: center center;
         background-repeat: no-repeat;
     ">
			<div class="header__content relative text-center">
				<h1 class="text-4xl md:text-6xl font-extrabold">
					<span class="text-red-600">B11N GYM</span><br>
					PURWOKERTO
				</h1>
				<p class="mt-2 text-lg md:text-xl font-base">Premium Fitness Studio | GIVE UP OR GET UP</p>
				<div class="header__btn">
					<button class="btn btn__primary">GET STARTED</button>
				</div>
			</div>
		</div>
	</header>

	<!--Tentang Kami-->
	<section class="max-w-[1200px] mx-auto px-4 py-20" id="about">
		<div class="about__header text-center">
			<h2 class="section__header text-2xl font-header text-text-dark font-semibold mb-8">Tentang Kami</h2>
			<p class="section__description mx-auto text-center text-base">
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

	<!--Fasilitas-->
	<section class="max-w-[1200px] mx-auto px-4 py-20" id="about">
		<div class="about__header text-center">
			<h2 class="section__header text-2xl font-header text-text-dark font-semibold mb-8">
				Fasilitas Kami
			</h2>
		</div>
		<div class="container mx-auto px-4 py-12">
			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
				@foreach ($facilities as $facility)
				<div class="about__card bg-white border border-gray-200 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
					<div class="flex items-center justify-center text-white rounded-full mx-auto -mb-16">
						<!-- Replace this icon with the appropriate one for each facility -->
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

	<!--Training Program-->
	<section class="py-8">
		@foreach ($groupedTrainingPrograms as $categoryId => $trainingPrograms)
		@php
		$categoryTitle = $categories[$categoryId]->title ?? 'Unknown Category'; // Judul kategori
		@endphp
		<div class="category-group">
			<div class="text-center">
				<h3 class="text-2xl font-semibold text-white bg-black pt-8 pb-8">{{ $categoryTitle }}</h3>
			</div>
			<div class="grid grid-cols-2 gap-0">
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

				<!-- Modal -->
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






	<!--Trainer-->
	<section class="trainer__container max-w-[1200px] mx-auto px-4 py-20" id="trainer">
		<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">MEET OUR TRAINERS</h2>
		<div class="trainer__grid">
			@foreach ($trainer as $trainer)
			<div class="trainer__card">
				<img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" class="h-64 w-auto" />
				<h4>{{ $trainer->name }}</h4>
				<p>{{ $trainer->description }}</p>
				<div class="trainer__socials">
					<a href="{{ $trainer->urls['facebook'] ?? '#' }}"><i class="ri-facebook-fill"></i></a>
					<a href="{{ $trainer->urls['whatsapp'] ?? '#' }}"><i class="ri-whatsapp-fill"></i></a>
					<a href="{{ $trainer->urls['instagram'] ?? '#' }}"><i class="ri-instagram-fill"></i></a>
				</div>
			</div>
			@endforeach
		</div>
	</section>

	<!--Membership-->
	<section class="membership">
		<div class="membership__container max-w-[1200px] mx-auto px-4 py-20">
			<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">MEMBERSHIP</h2>
			<div class="membership__grid">
				<div class="membership__card">
					<h4>STANDARD</h4>
					<ul>
						<li>
							<span><i class="ri-check-line"></i></span>
							Gym floor access and standard equipment.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Group fitness classes: yoga, Zumba, Pilates.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Complimentary fitness consultations.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Locker room and showers.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Nutritional guidance and snacks.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Member discounts on merchandise.
						</li>
					</ul>
					<h3><sup>$</sup>30<span>/month</span></h3>
					<button class="btn btn__primary">BUY NOW</button>
				</div>
				<div class="membership__card">
					<h4>PROFESSIONAL</h4>
					<ul>
						<li>
							<span><i class="ri-check-line"></i></span>
							Standard Membership facilities included.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Priority booking for personal training.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Access to advanced equipment.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Complimentary fitness consultations.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Exclusive member events and workshops.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Discounts on additional services.
						</li>
					</ul>
					<h3><sup>$</sup>45<span>/month</span></h3>
					<button class="btn btn__primary">BUY NOW</button>
				</div>
				<div class="membership__card">
					<h4>ULTIMATE</h4>
					<ul>
						<li>
							<span><i class="ri-check-line"></i></span>
							Standard and Professional facilities included.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Unlimited access to premium amenities.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Reserved parking or valet service.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Complimentary premium fitness classes.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Customized workout plans.
						</li>
						<li>
							<span><i class="ri-check-line"></i></span>
							Priority access to guest passes and events.
						</li>
					</ul>
					<h3><sup>$</sup>60<span>/month</span></h3>
					<button class="btn btn__primary">BUY NOW</button>
				</div>
			</div>
		</div>
	</section>

	<!--Testimoni-->
	<section class="client__container max-w-[1200px] mx-auto px-4 py-20" id="client">
		<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">OUR TESTIMONIALS</h2>
		<!-- Slider main container -->
		<div class="swiper">
			<!-- Additional required wrapper -->
			<div class="swiper-wrapper">
				<!-- Slides -->
				<div class="swiper-slide">
					<div class="client__card">
						<img src="assets/client-1.jpg" alt="client" />
						<div><i class="ri-double-quotes-r"></i></div>
						<p>
							I've been a member at FitPhysique for over a year now, and I
							couldn't be happier with my experience. The range of classes
							offered here is impressive - from high-energy cardio sessions to
							relaxing yoga classes, there's something for everyone.
						</p>
						<h4>Sarah Johnson</h4>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="client__card">
						<img src="assets/client-2.jpg" alt="client" />
						<div><i class="ri-double-quotes-r"></i></div>
						<p>
							The classes are always well-planned and engaging, and the
							instructors do an excellent job of keeping us motivated
							throughout. I'm so grateful to have found such a supportive and
							inclusive gym.
						</p>
						<h4>Michael Wong</h4>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="client__card">
						<img src="assets/client-3.jpg" alt="client" />
						<div><i class="ri-double-quotes-r"></i></div>
						<p>
							I've tried many gyms in the past, but none of them compare to
							FitPhysique. From the moment I walked through the doors, I felt
							welcomed and supported by the staff and fellow members alike.
						</p>
						<h4>Emily Davis</h4>
					</div>
				</div>
			</div>
			<!-- If we need pagination -->
			<div class="swiper-pagination"></div>
		</div>
	</section>

	<!--Store-->
	@foreach ($banner as $banner)
	<section
		style="background-image: url('{{ asset('storage/' . $banner->image) }}'); background-size: cover; background-position: center;"
		class="header mx-auto px-4 py-15 bg-black"
		id="store">
		<div class="header__container max-w-[1200px] mx-auto px-4 py-20">
			<div class="header__content">
				<h1 class="text-[#dc030a]">{{ $banner->title }}</h1>
				<h2 class="text-white">{{ $banner->subheading }}</h2>
				<p class="text-white">{{ $banner->description }}</p>
				<div class="header__btn">
					<a href="{{ route('product.index') }}" class="btn btn__primary">VISIT STORE</a>
				</div>
			</div>
		</div>
	</section>
	@endforeach

	<!--Blog-->
	<section class="blog" id="blog">
		<div class="blog__container max-w-[1200px] mx-auto px-4 py-20">
			<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">BLOGS</h2>
			<div class="blog__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
				@foreach ($blog as $blog)
				<div class="blog__card">
					<img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" />
					<h4 class="text-lg font-medium mt-4">{{ $blog->title }}</h4>
				</div>
				@endforeach
			</div>
			@if($blog->count() > 3)
			<div class="blog__btn mt-8 text-center">
				<a href="/blogs" class="btn btn__primary">VIEW ALL</a>
			</div>
			@endif
		</div>
	</section>

	<!--Gallery and contact us embed link maps-->
	<section class="logo__banner max-w-[1200px] mx-auto px-4 py-20">
		<img src="assets/banner-1.png" alt="banner" />
		<img src="assets/banner-2.png" alt="banner" />
		<img src="assets/banner-3.png" alt="banner" />
		<img src="assets/banner-4.png" alt="banner" />
	</section>

	<!--Footer-->
	<footer class="footer" id="contact">
		<div class="footer__container max-w-[1200px] mx-auto px-4 py-20">
			<div class="footer__col">
				<div class="footer__logo">
					<a href="#"><img src="assets/logo.png" alt="logo" /></a>
				</div>
				<p>
					Welcome to FitPhysique, where we believe that every journey to
					fitness is unique and empowering.
				</p>
				<ul class="footer__links">
					<li>
						<a href="#">
							<span><i class="ri-map-pin-2-fill"></i></span>
							123 Main Street<br />Sunrise Valley, Evergreen Heights
						</a>
					</li>
					<li>
						<a href="#">
							<span><i class="ri-phone-fill"></i></span>
							+91 9876543210
						</a>
					</li>
					<li>
						<a href="#">
							<span><i class="ri-mail-fill"></i></span>
							info@fitphysique.com
						</a>
					</li>
				</ul>
			</div>
			<div class="footer__col">
				<h4>GALLERY</h4>
				<div class="gallery__grid">
					<img src="assets/gallery-1.jpg" alt="gallery" />
					<img src="assets/gallery-2.jpg" alt="gallery" />
					<img src="assets/gallery-3.jpg" alt="gallery" />
					<img src="assets/gallery-4.jpg" alt="gallery" />
					<img src="assets/gallery-5.jpg" alt="gallery" />
					<img src="assets/gallery-6.jpg" alt="gallery" />
					<img src="assets/gallery-7.jpg" alt="gallery" />
					<img src="assets/gallery-8.jpg" alt="gallery" />
					<img src="assets/gallery-9.jpg" alt="gallery" />
				</div>
			</div>
			<div class="footer__col">
				<h4>NEWSLETTER</h4>
				<p>
					Don't miss out on the latest news and offers - sign up today and
					join our thriving fitness community!
				</p>
				<form action="/">
					<input type="text" placeholder="Enter Email" />
					<button class="btn btn__primary">SEND</button>
				</form>
				<div class="footer__socials">
					<a href="#"><i class="ri-facebook-fill"></i></a>
					<a href="#"><i class="ri-twitter-fill"></i></a>
					<a href="#"><i class="ri-youtube-fill"></i></a>
				</div>
			</div>
		</div>
		<div class="footer__bar">
			Copyright © 2024 Web Design Mastery. All rights reserved.
		</div>
	</footer>

	<script src="https://unpkg.com/scrollreveal"></script>
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<script src="assets/js/script.js"></script>
	<script>
		document.querySelectorAll('a[href^="#"]').forEach(anchor => {
			anchor.addEventListener('click', function(e) {
				e.preventDefault();
				const targetId = this.getAttribute('href');
				const targetElement = document.querySelector(targetId);

				if (targetElement) {
					window.scrollTo({
						top: targetElement.offsetTop - 90, // Ganti 100 dengan jarak yang diinginkan
						behavior: 'smooth',
					});
				}
			});
		});

		const navItems = document.querySelectorAll('.nav-item');

		function handleScroll() {
			const scrollPosition = window.scrollY;

			// Define section heights (replace with actual section heights)
			const sectionHeights = {
				header: 0,
				about: 500,
				trainer: 1000,
				client: 1500,
				blog: 2000,
				contact: 2500,
			};

			navItems.forEach(item => {
				const sectionId = item.querySelector('a').getAttribute('href').substring(1);
				const sectionHeight = sectionHeights[sectionId];
				const nextSectionId = Object.keys(sectionHeights)[Object.keys(sectionHeights).indexOf(sectionId) + 1];
				const nextSectionHeight = sectionHeights[nextSectionId];

				if (scrollPosition >= sectionHeight && (nextSectionHeight === undefined || scrollPosition < nextSectionHeight)) {
					item.classList.add('active');
				} else {
					item.classList.remove('active');
				}
			});
		}

		window.addEventListener('scroll', handleScroll);
		handleScroll();
	</script>

</body>

</html>