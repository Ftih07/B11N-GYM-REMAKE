<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use App\Models\QrCode;
use App\Observers\QrCodeObserver;

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
    }
}
