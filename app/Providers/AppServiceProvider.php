<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrapFive();

        \App\Models\ProductionTracking::observe(\App\Observers\ProductionTrackingObserver::class);
        \App\Models\OrderRevision::observe(\App\Observers\OrderRevisionObserver::class);

        try {
            $setting = Setting::first();
            View::share('setting', $setting);
        } catch (\Exception $e) {
            // database tidak ditemukan
        }
    }
}
