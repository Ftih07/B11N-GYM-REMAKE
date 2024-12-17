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
	@vite('resources/css/index.css')
	<title>Web Design Mastery | FitPhysique</title>
</head>

<body>
	<nav>
		<div class="nav__bar">
			<div class="nav__header">
				<div class="nav__logo">
					<a href="#"><img src="assets/logo.png" alt="logo" /></a>
				</div>
				<div class="nav__menu__btn" id="menu-btn">
					<i class="ri-menu-line"></i>
				</div>
			</div>
			<ul class="nav__links" id="nav-links">
				<li><a href="#home">HOME</a></li>
				<li><a href="#about">ABOUT</a></li>
				<li><a href="#trainer">TRAINER</a></li>
				<li><a href="#client">CLIENT</a></li>
				<li><a href="#blog">BLOG</a></li>
				<li><a href="#contact">CONTACT US</a></li>
			</ul>
		</div>
	</nav>

	<header class="header" id="header">
		<div class="header__container max-w-[1200px] mx-auto px-4 py-20">
			<div class="header__content">
				<h1>HARD WORK</h1>
				<h2>ISS FOR EVERY SUCCESS</h2>
				<p>Start by taking inspirations, continue it to give inspirations</p>
				<div class="header__btn">
					<button class="btn btn__primary">GET STARTED</button>
				</div>
			</div>
		</div>
	</header>

	<section class="max-w-[1200px] mx-auto px-4 py-20" id="about">
		<div class="about__header text-center grid gap-4">
			<h2 class="section__header text-5xl font-semibold font-header text-text-dark text-center">ABOUT US</h2>
			<p class="section__description max-w-[350px] mx-auto">
				Our mission is to inspire and support individuals in achieving their
				health and wellness goals, regardless of their fitness level or
				background.
			</p>
		</div>
		<div class="about__grid">
			<div class="about__card">
				<h4>WINNER COACHES</h4>
				<p>
					We pride ourselves on having a team of dedicated and experienced
					coaches who are committed to helping you succeed.
				</p>
			</div>
			<div class="about__card">
				<h4>AFFORDABLE PRICE</h4>
				<p>
					We believe that everyone should have access to high-quality fitness
					facilities without breaking the bank.
				</p>
			</div>
			<div class="about__card">
				<h4>MODERN EQUIPMENTS</h4>
				<p>
					Stay ahead of the curve with our state-of-the-art equipment designed
					to elevate your workout experience.
				</p>
			</div>
		</div>
	</section>

	<section class="max-w-[1200px] mx-auto px-4 py-20" id="about">
		<div class="about__header text-center grid gap-4">
			<h2 class="section__header text-5xl font-semibold font-header text-text-dark text-center">
				Fasilitas Kami
			</h2>
			<p class="section__description max-w-[350px] mx-auto">
				B11N Gym menawarkan fasilitas lengkap dan modern untuk memenuhi kebutuhan fitnessmu.
				Mulai dari area kardio, angkat beban, hingga berbagai kelas fitness yang menarik, semuanya tersedia.
			</p>
		</div>
		<div class="about__grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
			@foreach ($facilities as $facility)
			<div class="about__card border border-gray-200 rounded-lg p-6 shadow-md">
				<img
					src="{{ asset('storage/' . $facility->image) }}"
					alt="{{ $facility->title }}"
					class="w-40 h-10 object-cover mb-4 rounded" />
				<h4 class="text-xl font-semibold mb-2">{{ $facility->title }}</h4>
				<p class="text-gray-600">
					{{ $facility->description }}
				</p>
			</div>
			@endforeach
		</div>
	</section>


	<section class="session">
		<div class="session__card">
			<h4>BODY BUILDING</h4>
			<p>
				Sculpt your physique and build muscle mass with our specialized
				bodybuilding programs at FitPhysique.
			</p>
			<button class="btn btn__secondary">
				READ MORE <i class="ri-arrow-right-line"></i>
			</button>
		</div>
		<div class="session__card">
			<h4>CARDIO</h4>
			<p>
				Elevate your heart rate and boost your endurance with our dynamic
				cardio workouts at FitPhysique.
			</p>
			<button class="btn btn__secondary">
				READ MORE <i class="ri-arrow-right-line"></i>
			</button>
		</div>
		<div class="session__card">
			<h4>FITNESS</h4>
			<p>
				Embrace a holistic approach to fitness with our comprehensive fitness
				programs at FitPhysique.
			</p>
			<button class="btn btn__secondary">
				READ MORE <i class="ri-arrow-right-line"></i>
			</button>
		</div>
		<div class="session__card">
			<h4>CROSSFIT</h4>
			<p>
				Experience the ultimate full-body workout with our intense CrossFit
				classes at FitPhysique.
			</p>
			<button class="btn btn__secondary">
				READ MORE <i class="ri-arrow-right-line"></i>
			</button>
		</div>
	</section>

	<section class="trainer__container max-w-[1200px] mx-auto px-4 py-20" id="trainer">
		<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">MEET OUR TRAINERS</h2>
		<div class="trainer__grid">
			@foreach ($trainer as $trainer)
			<div class="trainer__card">
				<img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" />
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

	<section class="blog" id="blog">
		<div class="blog__container max-w-[1200px] mx-auto px-4 py-20">
			<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">BLOGS</h2>
			<div class="blog__grid">
				<div class="blog__card">
					<img src="assets/blog-1.jpg" alt="blog" />
					<h4>Fueling Your Body for Optimal Performance</h4>
				</div>
				<div class="blog__card">
					<img src="assets/blog-2.jpg" alt="blog" />
					<h4>A Guide to Setting and Achieving Fitness Goals</h4>
				</div>
				<div class="blog__card">
					<img src="assets/blog-3.jpg" alt="blog" />
					<h4>Tips and Techniques for Efficient Exercise</h4>
				</div>
				<div class="blog__card">
					<img src="assets/blog-4.jpg" alt="blog" />
					<h4>A Beginner's Guide to Starting Your Running Journey</h4>
				</div>
			</div>
			<div class="blog__btn">
				<button class="btn btn__primary">VIEW ALL</button>
			</div>
		</div>
	</section>

	<section class="logo__banner max-w-[1200px] mx-auto px-4 py-20">
		<img src="assets/banner-1.png" alt="banner" />
		<img src="assets/banner-2.png" alt="banner" />
		<img src="assets/banner-3.png" alt="banner" />
		<img src="assets/banner-4.png" alt="banner" />
	</section>

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
</body>

</html>