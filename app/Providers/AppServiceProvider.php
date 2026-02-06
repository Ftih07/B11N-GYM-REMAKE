<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use App\Models\QrCode;
use App\Observers\QrCodeObserver;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Transaction::observe(TransactionObserver::class);
        QrCode::observe(QrCodeObserver::class);

        // --- SOLUSI: BUNGKUS DALAM VIEW COMPOSER ---
        // Tanda '*' artinya data ini dikirim ke SEMUA view, sama seperti View::share
        View::composer('*', function ($view) {

            // Masukkan array menu kamu DI DALAM sini
            $navMenus = [
                [
                    'label' => 'Home',
                    'route' => 'home',
                    'submenu' => [
                        ['label' => 'Tentang Kami', 'url' => route('home') . '#about'],
                        ['label' => 'Ekosistem Kami', 'url' => route('home') . '#ecosystem'],
                        ['label' => 'Store', 'url' => route('home') . '#store'],
                        ['label' => 'Blog', 'url' => route('home') . '#blog'],
                        ['label' => 'Hubungi Kami', 'url' => route('home') . '#contact'],
                    ]
                ],
                [
                    'label' => 'B11N Gym',
                    'route' => 'gym.biin',
                    'submenu' => [
                        ['label' => 'Tentang Kami', 'url' => route('gym.biin') . '#about'],
                        ['label' => 'Fasilitas', 'url' => route('gym.biin') . '#facility'],
                        ['label' => 'Training Program', 'url' => route('gym.biin') . '#training'],
                        ['label' => 'Equipments & Tutorials', 'url' => route('gym.biin') . '#equipments'],
                        ['label' => 'Trainer', 'url' => route('gym.biin') . '#trainer'],
                        ['label' => 'Membership', 'url' => route('gym.biin') . '#membership'],
                        ['label' => 'Store', 'url' => route('gym.biin') . '#store'],
                        ['label' => 'Testimonial', 'url' => route('gym.biin') . '#testimonial'],
                        ['label' => 'Blog', 'url' => route('gym.biin') . '#blog'],
                        ['label' => 'Contact Us', 'url' => route('gym.biin') . '#contact'],
                    ]
                ],
                [
                    'label' => 'K1NG Gym',
                    'route' => 'gym.king',
                    'submenu' => [
                        ['label' => 'Tentang Kami', 'url' => route('gym.king') . '#about'],
                        ['label' => 'Fasilitas', 'url' => route('gym.king') . '#facilities'],
                        ['label' => 'Training Program', 'url' => route('gym.king') . '#training'],
                        ['label' => 'Equipments & Tutorials', 'url' => route('gym.king') . '#equipments'],
                        ['label' => 'Trainer', 'url' => route('gym.king') . '#trainer'],
                        ['label' => 'Membership', 'url' => route('gym.king') . '#membership'],
                        ['label' => 'Store', 'url' => route('gym.king') . '#store'],
                        ['label' => 'Testimonial', 'url' => route('gym.king') . '#testimonials'],
                        ['label' => 'Blog', 'url' => route('gym.king') . '#blog'],
                        ['label' => 'Contact Us', 'url' => route('gym.king') . '#contact'],
                    ]
                ],
                [
                    'label' => 'Kost Istana 03',
                    'route' => 'kost',
                    'submenu' => [
                        ['label' => 'Tentang Kami', 'url' => route('kost') . '#about'],
                        ['label' => 'Tipe Kamar', 'url' => route('kost') . '#room'],
                        ['label' => 'Fasilitas', 'url' => route('kost') . '#feature'],
                        ['label' => 'Testimonials', 'url' => route('kost') . '#testimonials'],
                        ['label' => 'Gallery', 'url' => route('kost') . '#gallery'],
                        ['label' => 'Pemesanan', 'url' => route('kost') . '#booking'],
                        ['label' => 'Blog', 'url' => route('kost') . '#blog'],
                        ['label' => 'Hubungi Kami', 'url' => route('kost') . '#contact'],
                    ]
                ],
                [
                    'label' => 'Store',
                    'route' => 'store.biin-king',
                    'submenu' => [] // Kosong karena direct link
                ],
                [
                    'label' => 'Blog',
                    'route' => 'blogs.index',
                    'submenu' => [] // Kosong karena direct link
                ],
                [
                    'label' => 'Support',
                    'route' => 'maintenance.create',
                    'submenu' => [
                        ['label' => 'Laporan Kerusakan', 'url' => route('maintenance.create')],
                        ['label' => 'Survey Pengunjung', 'url' => route('survey.index')],
                    ]
                ],
            ];

            // Kirim variabel ke view
            $view->with('navMenus', $navMenus);
        });
    }
}
