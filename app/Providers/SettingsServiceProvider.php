<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingService::class, function () {
            return new SettingService();
        });
    }

    public function boot(): void
    {
        // Only share settings with views if the settings table exists
        // This prevents errors during migrations
        try {
            if (Schema::hasTable('settings')) {
                $settingService = app(SettingService::class);
                $allSettings = $settingService->getAllCached();

                View::share('appSettings', $allSettings);
            }
        } catch (\Throwable $e) {
            // Silently fail during migrations or when DB is unavailable
        }
    }
}
