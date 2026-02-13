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
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Register Observers
        Transaction::observe(TransactionObserver::class);
        QrCode::observe(QrCodeObserver::class);

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
                    ]
                ],
                [
                    'label' => 'Store',
                    'route' => 'store.biin-king',
                    'submenu' => []
                ],
                [
                    'label' => 'Blog',
                    'route' => 'blogs.index',
                    'submenu' => []
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

            // Inject variable to views
            $view->with('navMenus', $navMenus);
        });
    }
}
