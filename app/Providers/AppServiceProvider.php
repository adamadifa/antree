<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Share institution globally across all views
        if (!app()->runningInConsole()) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('institutions')) {
                    view()->share('institution', \App\Models\Institution::first());
                }
            } catch (\Exception $e) {
                // Prevent crash during migrations or DB setups
            }
        }
    }
}
