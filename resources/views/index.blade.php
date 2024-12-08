<!DOCTYPE html>
	<html>
	<head>
		<!--  *****   Link To Custom CSS Style Sheet   *****  -->
        @vite('resources/css/app.css')

		<!--  *****   Link To Font Awsome Icons   *****  -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>

		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>FitLife</title>
	</head>
	<body>
	<!--   *** Website Wrapper Starts ***   -->
	<div class="website-wrapper">
		
	<!--   *** Home Section Starts ***   -->
	<section class="home" id="home">
		
		<div class="home-overlay"></div>
		<!--   === Main Navbar Starts ===   -->
		<nav class="main-navbar">
			<div class="logo">
				<img src="assets/images/home/logo.png">
			</div>
			<ul class="nav-list">
				<li><a href="#home">Home</a></li>
				<li><a href="#about">About</a></li>
				<li><a href="#services">Services</a></li>
				<li><a href="#our_team">Trainers</a></li>
				<li><a href="#pricing">Prices</a></li>
				<li><a href="{{ route ('product') }}">Blog</a></li>
			</ul>
			<a href="#" class="join-us-btn-wrapper">
				<button class="btn join-us-btn">Join Us</button>
			</a>
			<div class="hamburger-btn">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</nav>
		<!--   === Main Navbar Ends ===   -->
		<!--   === Banner Starts ===   -->
		<div class="banner">
			<div class="banner-contents">
				<h2>Start your training at FitLife</h2>
				<h1>Fit body needs more training</h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
				<button class="btn read-more-btn">Read More</button>
			</div>
		</div>
		<!--   === Banner Ends ===   -->

	</section>
	<!--   *** Home Section Ends ***   -->

	<!--   *** Facilities Section Starts ***   -->
	<section class="facilities">
		<div class="facilities-contents">
			
			<div class="facility-item">
				<div class="facility-icon">
					<i class="fa-solid fa-dumbbell"></i>
				</div>
				<div class="facility-desc">
					<h2>Quality Equipment</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</div>
			</div>

			<div class="facility-item">
				<div class="facility-icon">
					<i class="fa-solid fa-wifi"></i>
				</div>
				<div class="facility-desc">
					<h2>Free Wifi</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</div>
			</div>

			<div class="facility-item">
				<div class="facility-icon">
					<i class="fa-solid fa-person-swimming"></i>
				</div>
				<div class="facility-desc">
					<h2>Swimming Pool</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</div>
			</div>

		</div>
	</section>
	<!--   *** Facilities Section Ends ***   -->

	<!--   *** About Section Starts ***   -->
	<section class="about" id="about">
		<div class="about-contents">
			
			<div class="about-left-col">
				<img src="assets/images/about/about-img.jpg">
			</div>

			<div class="about-right-col">
				<h4>About Us</h4>
				<h1>Best Facilities and Experienced Trainers</h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
				<div class="about-states">
					<div class="about-state about-state-1">
						<i class="fa-solid fa-person"></i>
						<h2>Best Trainers</h2>
					</div>
					<div class="about-state about-state-2">
						<i class="fa-solid fa-medal"></i>
						<h2>Award Winning</h2>
					</div>
				</div>
			</div>

		</div>
	</section>
	<!--   *** About Section Ends ***   -->

	<!--   *** Services Section Starts ***   -->
	<section class="services" id="services">
		
		<!--   === Services Header Starts ===   -->
		<header class="section-header">
			<h3>Services</h3>
			<h1>Services Which We Offer</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
		</header>
		<!--   === Services Header Ends ===   -->

		<!--   === Services Contents Starts ===   -->
		<div class="services-contents">
			
			<div class="service-box">
				<div class="service-icon-box">
					<i class="fa-solid fa-dumbbell"></i>
				</div>
				<div class="service-desc">
					<h2>Body Building</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
				</div>
			</div>

			<div class="service-box">
				<div class="service-icon-box">
					<i class="fa-solid fa-person-walking"></i>
				</div>
				<div class="service-desc">
					<h2>Fitness</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
				</div>
			</div>

			<div class="service-box">
				<div class="service-icon-box">
					<i class="fa-solid fa-weight-hanging"></i>
				</div>
				<div class="service-desc">
					<h2>Boxing</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
				</div>
			</div>

			<div class="service-box">
				<div class="service-icon-box">
					<i class="fa-solid fa-dumbbell"></i>
				</div>
				<div class="service-desc">
					<h2>Crossfit</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
				</div>
			</div>

		</div>
		<!--   === Services Contents Ends ===   -->

	</section>
	<!--   *** Services Section Ends ***   -->

	<!--   *** Offer Section Starts ***   -->
	<section class="offer">
		<div class="offer-overlay"></div>
		<div class="offer-contents">
			<h1>Start Your Training Today</h1>
			<span>&</span>
			<h3>Get 30% Discount</h3>
			<button class="btn start-training-btn">Join Now</button>
		</div>
	</section>
	<!--   *** Offer Section Ends ***   -->

	<!--   *** Team Section Starts ***   -->
	<section class="our-team" id="our_team">
		<!--   === Team Header Starts ===   -->
		<header class="section-header">
			<h3>Our Trainers</h3>
			<h1>Meet Our Experienced Trainers</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
		</header>
		<!--   === Team Header Ends ===   -->
		<!--   === Team Contents Starts ===   -->
		<div class="team-contents">
			
			<div class="trainer-card">
				<div class="trainer-image">
					<img src="assets/images/trainers/trainer-1.jpg">
				</div>
				<div class="trainer-desc">
					<h2>John Doe</h2>
					<p>Muscles Trainer</p>
				</div>
				<div class="trainer-contact">
					<a href="#"><i class="fa-brands fa-facebook-f"></i></a>
					<a href="#"><i class="fa-brands fa-twitter"></i></a>
					<a href="#"><i class="fa-brands fa-instagram"></i></a>
				</div>
			</div>

			<div class="trainer-card">
				<div class="trainer-image">
					<img src="assets/images/trainers/trainer-2.jpg">
				</div>
				<div class="trainer-desc">
					<h2>Jane Doe</h2>
					<p>Boxing Trainer</p>
				</div>
				<div class="trainer-contact">
					<a href="#"><i class="fa-brands fa-facebook-f"></i></a>
					<a href="#"><i class="fa-brands fa-twitter"></i></a>
					<a href="#"><i class="fa-brands fa-instagram"></i></a>
				</div>
			</div>

			<div class="trainer-card">
				<div class="trainer-image">
					<img src="assets/images/trainers/trainer-3.jpg">
				</div>
				<div class="trainer-desc">
					<h2>Tom Anderson</h2>
					<p>Fitness Trainer</p>
				</div>
				<div class="trainer-contact">
					<a href="#"><i class="fa-brands fa-facebook-f"></i></a>
					<a href="#"><i class="fa-brands fa-twitter"></i></a>
					<a href="#"><i class="fa-brands fa-instagram"></i></a>
				</div>
			</div>

		</div>
		<!--   === Team Contents Ends ===   -->
	</section>
	<!--   *** Team Section Ends ***   -->

	<!--   *** Pricing Section Starts ***   -->
	<section class="pricing" id="pricing">

		<!--   === Pricing Header Starts ===   -->
		<header class="section-header">
			<h3>Pricing</h3>
			<h1>Join Suitable Plan</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
		</header>
		<!--   === Pricing Header Ends ===   -->
		<!--   === Pricing Contents Starts ===   -->
		<div class="pricing-contents">
			
			<!--   === Pricing Card 1 Starts ===   -->
			<div class="pricing-card">
				<div class="pricing-card-header">
					<span class="pricing-card-title">Silver</span>
					<div class="price-circle">
						<span class="price"><i>$</i>12.99</span>
						<span class="desc">/ Month</span>
					</div>
				</div>

				<div class="pricing-card-body">
					<ul>
						<li><i class="fa-solid fa-check"></i>15 body Building</li>
						<li><i class="fa-solid fa-check"></i>10 Boxing Classes</li>
						<li><i class="fa-solid fa-check"></i>5 Massage</li>
						<li><i class="fa-solid fa-check"></i>12 Swimming Sessions</li>
					</ul>
					<button class="btn price-plan-btn">Select Plan</button>
				</div>
			</div>
			<!--   === Pricing Card 1 Ends ===   -->

			<!--   === Pricing Card 2 Starts ===   -->
			<div class="pricing-card">
				<div class="pricing-card-header">
					<div class="tag-box">
						<span class="tag">Recommend</span>
					</div>
					<span class="pricing-card-title">Gold</span>
					<div class="price-circle">
						<span class="price"><i>$</i>36.99</span>
						<span class="desc">/ Month</span>
					</div>
				</div>

				<div class="pricing-card-body">
					<ul>
						<li><i class="fa-solid fa-check"></i>15 body Building</li>
						<li><i class="fa-solid fa-check"></i>10 Boxing Classes</li>
						<li><i class="fa-solid fa-check"></i>5 Massage</li>
						<li><i class="fa-solid fa-check"></i>12 Swimming Sessions</li>
					</ul>
					<button class="btn price-plan-btn">Select Plan</button>
				</div>
			</div>
			<!--   === Pricing Card 2 Ends ===   -->

			<!--   === Pricing Card 3 Starts ===   -->
			<div class="pricing-card">
				<div class="pricing-card-header">
					<span class="pricing-card-title">Platinum</span>
					<div class="price-circle">
						<span class="price"><i>$</i>74.99</span>
						<span class="desc">/ Month</span>
					</div>
				</div>

				<div class="pricing-card-body">
					<ul>
						<li><i class="fa-solid fa-check"></i>15 body Building</li>
						<li><i class="fa-solid fa-check"></i>10 Boxing Classes</li>
						<li><i class="fa-solid fa-check"></i>5 Massage</li>
						<li><i class="fa-solid fa-check"></i>12 Swimming Sessions</li>
					</ul>
					<button class="btn price-plan-btn">Select Plan</button>
				</div>
			</div>
			<!--   === Pricing Card 3 Ends ===   -->

		</div>
		<!--   === Pricing Contents Ends ===   -->
	</section>
	<!--   *** Pricing Section Ends ***   -->

	<!--   *** Blog Section Starts ***   -->
	<section class="blog" id="blog">
		<!--   === Blog Header Starts ===   -->
		<header class="section-header">
			<h3>Our Blog</h3>
			<h1>Latest From Our Blog</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
		</header>
		<!--   === Blog Header Ends ===   -->

		<!--   === Blog Contents Starts ===   -->
		<div class="blog-contents">
			
			<div class="article-card">
				<img src="assets/images/posts/post-1.jpg">
				<div class="category">
					<div class="subject"><h3>muscles</h3></div>
					<span>21/06/2023</span>
				</div>
				<h2 class="article-title">Strong Muscle: Lorem ipsum dolor sit amet, consectetur.</h2>
				<p class="article-desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p>
				<div class="article-views">
					<span>2.5k <i class="fa-solid fa-eye"></i></span>
					<span>352 <i class="fa-solid fa-comment"></i></span>
				</div>
			</div>

			<div class="article-card">
				<img src="assets/images/posts/post-2.jpg">
				<div class="category">
					<div class="subject"><h3>muscles</h3></div>
					<span>21/06/2023</span>
				</div>
				<h2 class="article-title">Strong Muscle: Lorem ipsum dolor sit amet, consectetur.</h2>
				<p class="article-desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p>
				<div class="article-views">
					<span>2.5k <i class="fa-solid fa-eye"></i></span>
					<span>352 <i class="fa-solid fa-comment"></i></span>
				</div>
			</div>

			<div class="article-card">
				<img src="assets/images/posts/post-3.jpg">
				<div class="category">
					<div class="subject"><h3>muscles</h3></div>
					<span>21/06/2023</span>
				</div>
				<h2 class="article-title">Strong Muscle: Lorem ipsum dolor sit amet, consectetur.</h2>
				<p class="article-desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p>
				<div class="article-views">
					<span>2.5k <i class="fa-solid fa-eye"></i></span>
					<span>352 <i class="fa-solid fa-comment"></i></span>
				</div>
			</div>

		</div>
		<!--   === Blog Contents Ends ===   -->
		<div class="view-more-btn-container">
			<button class="btn articles-view-btn">View More</button>
		</div>
	</section>
	<!--   *** Blog Section Ends ***   -->

	<!--   *** Footer Section Starts ***   -->
	<section class="page-footer">
		
		<!--   === Footer Contents Starts ===   -->
		<div class="footer-contents">
			
			<div class="footer-col footer-col-1">
				<div class="footer-col-title">
					<h3>About</h3>
				</div>
				<div class="footer-col-desc">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p>
					<span>321 Street, California, USA</span>
					<span>+012 123 45678</span>
					<span>info@sample.com</span>
					<div class="footer-social-media">
						<a href="#"><i class="fa-brands fa-facebook-f"></i></a>
						<a href="#"><i class="fa-brands fa-twitter"></i></a>
						<a href="#"><i class="fa-brands fa-instagram"></i></a>
						<a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
					</div>
				</div>
			</div>

			<div class="footer-col footer-col-2">
				<div class="footer-col-title">
					<h3>Quick Links</h3>
				</div>
				<div class="footer-col-desc">
					<a href="#">Home</a>
					<a href="#">About</a>
					<a href="#">Services</a>
					<a href="#">Trainers</a>
					<a href="#">Pricing</a>
					<a href="#">Blog</a>
				</div>
			</div>

			<div class="footer-col footer-col-3">
				<div class="footer-col-title">
					<h3>Newsletter</h3>
				</div>
				<div class="footer-col-desc">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					<form class="newsletter">
						<input type="email" placeholder="Your Email">
						<button class="btn newsletter-btn" type="submit">Subscribe</button>
					</form>
				</div>
			</div>

		</div>
		<!--   === Footer Contents Ends ===   -->

	</section>
	<!--   *** Footer Section Ends ***   -->

	<!--   *** Copy Rights Starts ***   -->
	<div class="copy-rights">
		<p>Created By <b>Five Star Tutorials</b> All Rights Reserved</p>
	</div>
	<!--   *** Copy Rights Ends ***   -->

	</div>
	<!--   *** Website Wrapper Ends ***   -->




	<!--   *** Link To Custom Script File ***   -->
	<script type="text/javascript" src="assets/js/script.js"></script>
	</body>
	</html>