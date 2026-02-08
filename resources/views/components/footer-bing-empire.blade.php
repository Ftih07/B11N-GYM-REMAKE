<!-- FOOTER SECTION -->
<footer id="contact" class="relative bg-neutral-900/95 text-white border-t border-gray-800 font-sans">
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-red-600 to-transparent opacity-50"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12 mb-12 reveal-stagger">

            <div class="lg:col-span-5 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 relative flex-shrink-0">
                        <a href="{{ route('home') }}" class="block">
                            <img src="{{ asset('assets/Logo/empire.png') }}" alt="B1NG Empire Logo" class="w-full h-full object-contain" />
                        </a>
                    </div>
                    <div>
                        <h3 class="font-black text-2xl leading-none tracking-wide text-white">
                            B1NG <span class="text-red-600">EMPIRE</span>
                        </h3>
                        <span class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">Premium Gym & Residence</span>
                    </div>
                </div>

                <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                    Konsep "One-Stop Solution" yang menggabungkan kebugaran, gaya hidup sehat, dan hunian nyaman dalam satu ekosistem terpadu di Purwokerto. Tingkatkan kualitas hidup Anda bersama kami.
                </p>

                <div class="flex items-center gap-3 pt-2">
                    @php
                    $socials = [
                    ['icon' => 'ri-whatsapp-line', 'url' => 'https://wa.me/6283194288423'],
                    ['icon' => 'ri-instagram-fill', 'url' => 'https://www.instagram.com/biin_gym/'],
                    ['icon' => 'ri-threads-fill', 'url' => 'https://www.threads.net/@biin_gym'],
                    ['icon' => 'fas fa-envelope', 'url' => 'mailto:sobiin77@gmail.com'],
                    ];
                    @endphp

                    @foreach($socials as $social)
                    <a href="{{ $social['url'] }}" target="_blank" class="w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-300 transform hover:-translate-y-1">
                        <i class="{{ $social['icon'] }} text-lg"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-3">
                <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-6 border-l-4 border-red-600 pl-3">
                    Navigasi
                </h4>
                <ul class="space-y-3">
                    @foreach($navMenus as $menu)
                    <li>
                        <a href="{{ route($menu['route']) }}" class="group flex items-center gap-3 text-sm text-gray-400 hover:text-white transition-colors duration-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600 opacity-0 group-hover:opacity-100 transition-all duration-300 transform -translate-x-2 group-hover:translate-x-0"></span>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ $menu['label'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-4">
                <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-6 border-l-4 border-red-600 pl-3">
                    Hubungi Kami
                </h4>
                <ul class="space-y-4">
                    <li>
                        <a href="https://wa.me/6283194288423" target="_blank" class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-red-600 hover:bg-red-600/10 transition-all duration-300 group">

                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-whatsapp-fill text-lg"></i>
                            </div>

                            <div>
                                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-1 opacity-70">WhatsApp</h5>
                                <p class="text-gray-300 font-medium text-sm group-hover:text-red-600 transition-colors">+62 831 9428 8423</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="https://maps.app.goo.gl/ZEHmkWKQV7JmNZnG9" target="_blank" class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-red-600/50 hover:bg-red-600/10 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="ri-map-pin-2-fill text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-1 opacity-70">Lokasi Utama</h5>
                                <p class="text-gray-300 text-sm leading-snug group-hover:text-red-600 transition-colors">
                                    Jl. Masjid Baru, Arcawinangun,<br>Purwokerto Timur, Banyumas
                                </p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-gray-500 text-xs text-center md:text-left">
                Copyright &copy; {{ date('Y') }} <span class="text-red-600 font-bold">B1NG EMPIRE</span>. All rights reserved.
            </p>

            <div class="flex items-center gap-6 text-xs text-gray-500">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>

    </div>
</footer> 