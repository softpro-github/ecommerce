<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
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
        try {
            if (Schema::hasTable('settings')) {
                Setting::applyMailConfig();
            }
        } catch (\Throwable $e) {
            // Settings table not migrated yet, or DB unavailable (e.g. during initial setup) — fall back to .env.
        }
    }
}
