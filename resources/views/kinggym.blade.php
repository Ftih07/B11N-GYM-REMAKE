<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" type="image/png" href="@yield('favicon', asset('assets/Logo/last.png'))">

	<link
		href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
		rel="stylesheet" />
	<link
		rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
	<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

	@vite('resources/css/kinggym.css')
	<title>K1NG Gym Purwokerto</title>
</head>

@foreach ($logo as $logo)

<body>
	<!--Navigasi-->
	<nav class="fixed text-white">
		<div class="nav__bar">
			<div class="nav__header">
				<div class="w-12 h-12 object-cover object-center">
					<a href="#"><img src="{{ asset('storage/' . $logo->image) }}" alt="logo" /></a>
				</div>
				<div class="nav__menu__btn" id="menu-btn">
					<i class="ri-menu-line"></i>
				</div>
			</div>
			<ul class="nav__links" id="nav-links">
				<li class="nav-item"><a href="#header" class="nav-link">HOME</a></li>
				<li class="nav-item"><a href="#about" class="nav-link">TENTANG KAMI</a></li>
				<li class="nav-item"><a href="#fasilitas" class="nav-link">FASILITAS</a></li>
				<li class="nav-item"><a href="#training" class="nav-link">TRAINING PROGRAM</a></li>
				<li class="nav-item"><a href="#trainer" class="nav-link">TRAINER</a></li>
				<li class="nav-item"><a href="#membership" class="nav-link">MEMBERSHIP</a></li>
				<li class="nav-item"><a href="#store" class="nav-link">STORE</a></li>
				<li class="nav-item"><a href="#client" class="nav-link">TESTIMONIAL</a></li>
				<li class="nav-item"><a href="#blog" class="nav-link">BLOG</a></li>
				<li class="nav-item"><a href="#contact" class="nav-link">CONTACT US</a></li>
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

	<!--Hero-->
	<section id="header">
		<header class="header">
			<div class="relative flex items-center justify-center h-screen bg-black text-white"
				style="
         background-image: linear-gradient(
             to right,
             rgba(0, 0, 0, 0.2),
             rgba(0, 0, 0, 0.9)
         ),
         url('assets/Hero/king.jpg');
         background-size: cover;
		background-position: center center;
         background-repeat: no-repeat;
     ">
				<div class="header__content_hero relative text-center px-4 sm:px-8 md:px-12 lg:px-16">
					<h1 class="text-4xl sm:text-6xl md:text-6xl lg:text-7xl font-extrabold">
						<span class="text-yellow-600">K1NG GYM</span><br>
						PURWOKERTO
					</h1>
					<p class="mt-2 text-lg md:text-xl font-base">Empower The King On You | GIVE UP OR GET UP</p>
					<div class="header__btn">
						<a href="#membership">
							<button class="btn btn__primary mt-3">GET STARTED</button>
						</a>
					</div>
				</div>
			</div>
		</header>
	</section>

	<!--Tentang Kami-->
	<section class="max-w-[1200px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-20" id="about">
		<div class="about__header text-center">
			<h2 class="section__header text-4xl sm:text-4xl md:text-4xl lg:text-5xl font-header text-text-dark font-semibold mb-8">Tentang Kami</h2>
			<p class="section__description mx-auto text-center text-base sm:text-base md:text-base">
				Selamat datang di K1NG Gym Purwokerto, pusat kebugaran populer di Ledug, Kec. Kembaran. Kami menawarkan keanggotaan harian, mingguan, dan bulanan dengan harga terjangkau. Fasilitas kami meliputi peralatan gym lengkap, loker yang aman, free personal trainer, dan free parkir. K1NG Gym bukan hanya tempat berolahraga, tapi juga ruang untuk membangun komunitas hidup sehat yang menyenangkan.
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
	<section class="max-w-[1200px] mx-auto px-4 py-20" id="fasilitas">
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
						<!-- Replace this icon with the appropriate one for each facility -->
						<img
							src="{{ asset('storage/' . $facility->image) }}"
							alt="{{ $facility->title }}"
							class="object-cover w-28 h-28" />
					</div>
					<h4 class="text-center text-yellow-600 mb-2 uppercase">{{ $facility->title }}</h4>
					<p class="text-gray-600 text-sm text-center">
						{{ $facility->description }}
					</p>
				</div>
				@endforeach
			</div>
		</div>
	</section>

	<!--Training Program-->
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
	<section class="trainer__container max-w-[1200px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-20" id="trainer">
		<h2 class="section__header text-2xl sm:text-3xl md:text-3xl lg:text-3xl font-semibold font-header text-text-dark text-center">MEET OUR TRAINERS</h2>
		<div class="trainer__grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-8">
			@foreach ($trainer as $trainer)
			<div class="trainer__card text-center border border-gray-200 rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300">
				<img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" class="h-50 w-full object-cover mb-4 rounded-md" />
				<h4 class="text-lg sm:text-xl md:text-2xl font-semibold">{{ $trainer->name }}</h4>
				<p class="text-sm sm:text-base md:text-lg text-gray-600">{{ $trainer->description }}</p>
				<div class="trainer__socials flex justify-center mt-4 gap-4">
					<a href="{{ $trainer->urls['facebook'] ?? '#' }}"><i class="ri-facebook-fill"></i></a>
					<a href="{{ $trainer->urls['whatsapp'] ?? '#' }}"><i class="ri-whatsapp-fill"></i></a>
					<a href="{{ $trainer->urls['instagram'] ?? '#' }}"><i class="ri-instagram-fill"></i></a>
				</div>
			</div>
			@endforeach
		</div>
	</section>

	<!--Membership-->
	<section class="membership"
		style="
         background-image: linear-gradient(
             to top,
             rgba(0, 0, 0, 0.2),
             rgba(0, 0, 0, 0.9)
         ),
         url('assets/home/king-gym.jpg');
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

				<!-- Modal -->
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
									<!-- Pilihan Metode Pembayaran -->
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

								<!-- Pembayaran QRIS -->
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
										Jangan lupa absen saat berkunjung ke K1NG Gym. Jika kamu membeli atau memperpanjang Member Bulanan, minta kartu member baru atau serahkan kartu lamamu ke kasir.
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

								<!-- Pembayaran Transfer -->
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
										Jangan lupa absen saat berkunjung ke K1NG Gym. Jika kamu membeli atau memperpanjang Member Bulanan, minta kartu member baru atau serahkan kartu lamamu ke kasir.
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

				<!-- Modal Payment Confirmation -->
				<div class="modal fade" id="paymentConfirmationModal" tabindex="-1" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title" id="paymentConfirmationModalLabel">Upload Payment Confirmation</h2>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<!-- Form -->
								<form id="paymentForm" enctype="multipart/form-data">
									<!-- Nama -->
									<div class="mb-3">
										<label class="block font-medium">Nama:</label>
										<input type="text" name="name" class="w-full border p-2 rounded" required>
									</div>

									<!-- Email -->
									<div class="mb-3">
										<label class="block font-medium">Email:</label>
										<input type="email" name="email" class="w-full border p-2 rounded" required>
									</div>

									<!-- No. Telp -->
									<div class="mb-3">
										<label class="block font-medium">No. Telp:</label>
										<input type="tel" name="phone" class="w-full border p-2 rounded" required>
									</div>

									<!-- Upload Bukti Pembayaran -->
									<div class="mb-3">
										<label class="block font-medium">Upload Bukti Pembayaran:</label>
										<input type="file" name="image" accept="image/*" class="w-full border p-2 rounded" required>
									</div>

									<!-- Pilihan Membership -->
									<div class="mb-3">
										<label class="block font-medium">Pilih Membership:</label>
										<select name="membership_type" class="w-full border p-2 rounded" required>
											<option value="Member Harian">Member Harian</option>
											<option value="Member Mingguan">Member Mingguan</option>
											<option value="Member Bulanan">Member Bulanan</option>
										</select>
									</div>

									<!-- Metode Pembayaran -->
									<div class="mb-3">
										<label class="block font-medium">Metode Pembayaran:</label>
										<select name="payment" class="w-full border p-2 rounded" required>
											<option value="qris">QRIS</option>
											<option value="transfer">Transfer Bank</option>
										</select>
									</div>

									<!-- Submit Button -->
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

	<!--Store-->
	@foreach ($banner as $banner)
	<section
		style="background-image: url('{{ asset('storage/' . $banner->image) }}'); background-size: cover; background-position: center;"
		class="header mx-auto px-4 py-15 bg-black "
		id="store">
		<div class="header__container max-w-[1200px] mx-auto px-4 py-20">
			<div class="header__content">
				<h1 class="text-[#f0761f]">{{ $banner->title }}</h1>
				<h2 class="text-white">{{ $banner->subheading }}</h2>
				<p class="text-white">{{ $banner->description }}</p>
				<div class="header__btn">
					<a href="{{ route('product.index') }}" class="btn btn__primary">VISIT STORE</a>
				</div>
			</div>
		</div>
	</section>
	@endforeach

	<!--Testimoni-->
	<section class="client__container max-w-[1200px] mx-auto px-4 py-20" id="client">
		<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">OUR TESTIMONIALS</h2>
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

	<!--Blog-->
	@section('content')
	<section class="blog" id="blog">
		<div class="blog__container max-w-[1200px] mx-auto px-4 py-20">
			<h2 class="section__header text-2xl font-semibold font-header text-text-dark text-center">BLOGS</h2>
			<div class="blog__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
				@foreach ($blog as $blog)
				<div class="blog__card mt-4 md:mt-0">
					<a href="{{ route('blog.show', $blog->id) }}">
						<img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="h-[300px] object-cover" />
						<p class="uppercase text-[10px] tracking-[3px] mb-1 text-white mt-3">{{ $blog->created_at->format('F d, Y') }}</p>
						<h4 class="text-lg font-medium uppercase">{{ $blog->title }}</h4>
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

	<!--Maps-->
	<section class="w-full mx-auto" id="contact">
		<div class="flex-dir-row" style="width: 100%; height: 300px;">
			<div class="" style="width: 100%; height: 100%;">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.3990886005636!2d109.2682165745483!3d-7.421008173094577!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655f0048b6a975%3A0x8d16ddcbd80dad18!2sK1NG%20GYM!5e0!3m2!1sid!2sid!4v1735265555471!5m2!1sid!2sid" style="border:0; width: 100%; height: 100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</div>
	</section>

	<!--Footer-->
	<footer class="footer">
		<div class="footer__container max-w-[1200px] mx-auto px-4 py-20">
			<div class="footer__col">
				<div class="footer__logo">
					<a href="#"><img src="{{ asset('storage/' . $logo->image) }}" alt="logo" /></a>
				</div>
				<p>
					Selamat datang di K1NG Gym Purwokerto, pusat kebugaran populer di Ledug, Kec. Kembaran.
				</p>
				<ul class="footer__links">
					<li>
						<a href="https://maps.app.goo.gl/a6hw3JmqBBEuBzt89" target="_blank">
							<span><i class="ri-map-pin-2-fill"></i></span>
							Jl. Masjid Baru, Arcawinangun,Kec. Purwokerto Timur, Kab. Banyumas </a>
					</li>
					<li>
						<a href="https://wa.me/6281226110988" target="_blank">
							<span><i class="ri-phone-fill"></i></span>
							+62 812 2611 0988
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

			<!---Gallery Footer-->
			<div class="footer__col">
				<h4 class="">GALLERY</h4>
				<div class="gallery__grid mt-7 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
					@foreach ($gallery as $item)
					<img
						src="{{ asset('storage/' . $item->image) }}"
						alt="{{ $item->title }}"
						class="gallery-thumbnail h-20 w-20 object-cover"
						data-bs-toggle="modal"
						data-bs-target="#galleryModal"
						data-title="{{ $item->title }}"
						data-src="{{ asset('storage/' . $item->image) }}" />
					@endforeach
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">
								<span class="text-danger">K1NG GYM </span>GALLERY
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
				<h4 class="text-yellow-600 text-lg font-bold mb-4">HUBUNGI KAMI</h4>

				<!-- Threads -->
				<div class="border-b border-dotted border-gray-400 pb-3 mb-3 group">
					<a href="https://www.threads.net/@biin_gym?xmt=AQGzKh5EYkbE4G7JIjSwlirbjIADsXrxWWU6UuUKi1XKhFU" class="flex items-center gap-3 text-white group-hover:text-yellow-600 transition-all" target="_blank">
						<!-- Threads Icon -->
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-threads group-hover:text-yellow-600" viewBox="0 0 16 16">
							<path d="M6.321 6.016c-.27-.18-1.166-.802-1.166-.802.756-1.081 1.753-1.502 3.132-1.502.975 0 1.803.327 2.394.948s.928 1.509 1.005 2.644q.492.207.905.484c1.109.745 1.719 1.86 1.719 3.137 0 2.716-2.226 5.075-6.256 5.075C4.594 16 1 13.987 1 7.994 1 2.034 4.482 0 8.044 0 9.69 0 13.55.243 15 5.036l-1.36.353C12.516 1.974 10.163 1.43 8.006 1.43c-3.565 0-5.582 2.171-5.582 6.79 0 4.143 2.254 6.343 5.63 6.343 2.777 0 4.847-1.443 4.847-3.556 0-1.438-1.208-2.127-1.27-2.127-.236 1.234-.868 3.31-3.644 3.31-1.618 0-3.013-1.118-3.013-2.582 0-2.09 1.984-2.847 3.55-2.847.586 0 1.294.04 1.663.114 0-.637-.54-1.728-1.9-1.728-1.25 0-1.566.405-1.967.868ZM8.716 8.19c-2.04 0-2.304.87-2.304 1.416 0 .878 1.043 1.168 1.6 1.168 1.02 0 2.067-.282 2.232-2.423a6.2 6.2 0 0 0-1.528-.161" />
						</svg>
						<h5 class="font-semibold text-xl group-hover:text-yellow-600 transition-all">Threads</h5>
					</a>
					<p class="text-gray-400 text-sm tracking-wide uppercase mt-2">B11N_GYM</p>
				</div>

				<!-- Instagram -->
				<div class="border-b border-dotted border-gray-400 pb-3 mb-3 group">
					<a href="https://www.instagram.com/biin_gym/" class="flex items-center gap-3 text-white group-hover:text-yellow-600 transition-all" target="_blank">
						<i class="fab fa-instagram text-xl group-hover:text-yellow-600"></i>
						<h5 class="font-semibold text-xl group-hover:text-yellow-600 transition-all">Instagram</h5>
					</a>
					<p class="text-gray-400 text-sm tracking-wide uppercase mt-2">BIIN_GYM</p>
				</div>

				<!-- WhatsApp -->
				<div class="border-b border-dotted border-gray-400 pb-3 mb-3 group">
					<a href="https://wa.me/6281226110988" class="flex items-center gap-3 text-white group-hover:text-yellow-600 transition-all" target="_blank">
						<i class="fab fa-whatsapp text-xl group-hover:text-yellow-600"></i>
						<h5 class="font-semibold text-xl group-hover:text-yellow-600 transition-all">Whatsapp</h5>
					</a>
					<p class="text-gray-400 text-sm tracking-wide mt-2">0896-5384-7651</p>
				</div>

				<!-- Email -->
				<div class="border-b border-dotted border-gray-400 pb-3 mb-3 group">
					<a href="mailto:sobiin77@gmail.com" class="flex items-center gap-3 text-white group-hover:text-yellow-600 transition-all" target="_blank">
						<i class="fas fa-envelope text-xl group-hover:text-yellow-600"></i>
						<h5 class="font-semibold text-xl group-hover:text-yellow-600 transition-all">Email</h5>
					</a>
					<p class="text-gray-400 text-sm tracking-wide mt-2">SOBIIN77@GMAIL.COM</p>
				</div>
			</div>


		</div>
		<hr class="border-t border-white">

		<div class="footer__bar">
			Copyright © 2024 Mullticore. All rights reserved.
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
			const whatsappNumber = "6281226110988"; // Ganti dengan nomor WhatsApp Anda

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


		// Nav Utama
		const trigger = document.querySelector("menu > .trigger");
		trigger.addEventListener('click', (e) => {
			e.currentTarget.parentElement.classList.toggle("open");
		});
	</script>

	<script>
		document.getElementById('paymentForm').addEventListener('submit', function(event) {
			event.preventDefault();

			let formData = new FormData(this);

			fetch('/payment/upload', {
					method: 'POST',
					body: formData,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
					}
				})
				.then(response => response.text()) // Ubah dari `.json()` ke `.text()`
				.then(data => {
					console.log('Response:', data); // Cek di console log
					try {
						let jsonData = JSON.parse(data); // Coba parse ke JSON
						alert(jsonData.message);
					} catch (e) {
						console.error('Invalid JSON response:', data);
					}
				})
				.catch(error => console.error('Error:', error));

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
@endforeach


</html>