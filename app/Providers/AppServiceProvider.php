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
        // Share institution and display settings globally across all views
        if (!app()->runningInConsole()) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('institutions')) {
                    $institution = \App\Models\Institution::first();
                    view()->share('institution', $institution);

                    if (\Illuminate\Support\Facades\Schema::hasTable('display_settings')) {
                        $displaySettings = \App\Models\DisplaySetting::pluck('value', 'key')->toArray();
                        view()->share('displaySettings', $displaySettings);
                    }
                }
            } catch (\Exception $e) {
                // Prevent crash during migrations or DB setups
            }
        }
    }
}
