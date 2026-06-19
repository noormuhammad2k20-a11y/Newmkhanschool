<?php

use App\Services\SettingService;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key.
     *
     * @param  string  $key      Dot-notation key (e.g. 'general.app_name')
     * @param  mixed   $default  Fallback value if setting not found
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            return app(SettingService::class)->get($key, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}
