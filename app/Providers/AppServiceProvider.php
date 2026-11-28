<?php

namespace App\Providers;

use App\Models\QrCode;
use App\Models\Transaction;
use App\Observers\QrCodeObserver;
use App\Observers\TransactionObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Register Observers
        Transaction::observe(TransactionObserver::class);
        QrCode::observe(QrCodeObserver::class);

        // 1b. Rate limit form publik (SEC-04) dan API OCR (SEC-07)
        $this->configureRateLimiting();

        // 2. Global View Data (Navigation Menu)
        // Using View Composer ensures '$navMenus' is available in EVERY blade file
        View::composer('*', function ($view) {

            // Define Global Menu Structure
            $navMenus = [
                [
                    'label' => 'Home',
                    'route' => 'home',
                ],
                [
                    'label' => 'Unit Usaha',
                    'route' => 'gym.biin',
                    'submenu' => [
                        ['label' => 'B11N Gym', 'url' => route('gym.biin')],
                        ['label' => 'K1NG Gym', 'url' => route('gym.king')],
                        ['label' => 'Kost Istana Merdeka 03', 'url' => route('kost')],
                        ['label' => 'Digitify', 'url' => 'https://digitify.my.id/'],

                    ],
                ],
                [
                    'label' => 'Store',
                    'route' => 'store.biin-king',
                    'submenu' => [],
                ],
                [
                    'label' => 'Blog',
                    'route' => 'blogs.index',
                    'submenu' => [],
                ],
                [
                    'label' => 'Support',
                    'route' => 'maintenance.create',
                    'submenu' => [
                        ['label' => 'Laporan Kerusakan', 'url' => route('maintenance.create')],
                        ['label' => 'Survey Pengunjung', 'url' => route('survey.index')],
                    ],
                ],
            ];

            // Inject variable to views
            $view->with('navMenus', $navMenus);
        });
    }

    private function configureRateLimiting(): void
    {
        $tooMany = function (Request $request, array $headers) {
            $message = 'Terlalu banyak percobaan. Silakan coba lagi beberapa menit lagi.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 429, $headers)
                : back()->with('error', $message)->withHeaders($headers);
        };

        // Form publik (survei, laporan kerusakan). Batas per menit
        // dibuat longgar karena banyak pengunjung gym bisa memakai Wi-Fi (IP) yang sama.
        RateLimiter::for('public-form', fn (Request $request) => [
            Limit::perMinute(10)->by('form-min:'.$request->ip())->response($tooMany),
            Limit::perDay(150)->by('form-day:'.$request->ip())->response($tooMany),
        ]);

        // Sinkronisasi rekap harian dari skrip OCR.
        RateLimiter::for('ocr-sync', fn (Request $request) => Limit::perMinute(30)->by('ocr:'.$request->ip()));
    }
}
