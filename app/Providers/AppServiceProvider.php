<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PemainBola;
use App\Observers\PemainBolaObserver;
use App\Services\WablasService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WablasService::class, function ($app) {
            return new WablasService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register PemainBola Observer untuk auto-update jumlah pemain
        PemainBola::observe(PemainBolaObserver::class);
    }
}