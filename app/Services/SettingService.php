<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SettingGroup;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    protected const CACHE_KEY = 'app_settings';
    protected const CACHE_TTL = 86400; // 24 hours

    /**
     * Get a single setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->getAllCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a single setting value.
     */
    public function set(string $key, mixed $value): void
    {
        $setting = Setting::byKey($key)->first();

        // Encrypt if it's a secret key
        if (in_array($key, Setting::SECRET_KEYS) && filled($value)) {
            $value = \Illuminate\Support\Facades\Crypt::encryptString($value);
        }

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            Setting::create([
                'setting_group_id' => 1, // Fallback, assuming group 1 exists
                'key'   => $key,
                'value' => $value,
                'type'  => 'text',
                'label' => ucwords(str_replace(['.', '_'], ' ', $key)),
            ]);
        }

        $this->clearCache();
    }

    /**
     * Get all settings for a specific group by slug.
     */
    public function getGroup(string $slug): array
    {
        $group = SettingGroup::where('slug', $slug)->with('settings')->first();

        if (!$group) {
            return [];
        }

        $result = [];
        foreach ($group->settings as $setting) {
            $result[$setting->key] = $setting->getDecryptedValueAttribute();
        }

        return $result;
    }

    /**
     * Get all setting groups with their settings (for the UI).
     */
    public function getAllGrouped(): \Illuminate\Database\Eloquent\Collection
    {
        return SettingGroup::ordered()->with('settings')->get();
    }

    /**
     * Get all settings as a flat key => value array (cached).
     * IMPORTANT: This returns DECRYPTED values so the setting() helper works server-side.
     */
    public function getAllCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            // We need decrypted values for the app to function
            return Setting::all()->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->getDecryptedValueAttribute()];
            })->toArray();
        });
    }

    /**
     * Update multiple settings at once (for group save).
     */
    public function updateMany(array $data): int
    {
        $count = 0;

        foreach ($data as $key => $value) {
            $setting = Setting::byKey($key)->first();
            if ($setting) {
                // For toggle type, convert checkbox to boolean string
                if ($setting->type === 'toggle') {
                    $value = $value ? '1' : '0';
                }
                // For json type, encode arrays
                if ($setting->type === 'json' && is_array($value)) {
                    $value = json_encode($value);
                }

                // Encrypt if secret
                if (in_array($key, Setting::SECRET_KEYS) && filled($value) && !str_contains($value, '•')) {
                    $value = \Illuminate\Support\Facades\Crypt::encryptString($value);
                }

                // Skip overwrite if it's the masked placeholder
                if (str_contains((string)$value, '•')) {
                    continue;
                }

                $setting->update(['value' => $value]);
                $count++;
            }
        }

        $this->clearCache();
        return $count;
    }

    /**
     * Handle file upload for a setting.
     */
    public function uploadFile(string $key, $file): string
    {
        $setting = Setting::byKey($key)->first();

        if (!$setting) {
            throw new \Exception("Setting key '{$key}' not found.");
        }

        // Delete old file if exists
        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        // Store new file
        $path = $file->store('settings', 'public');
        $setting->update(['value' => $path]);

        $this->clearCache();

        return $path;
    }

    /**
     * Clear the settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Export all settings as JSON (excluding secrets).
     */
    public function export(): array
    {
        $groups = $this->getAllGrouped();
        $export = [];

        foreach ($groups as $group) {
            $export[$group->slug] = [];
            foreach ($group->settings as $setting) {
                if (in_array($setting->key, Setting::SECRET_KEYS)) {
                    continue; // Skip secrets
                }
                $export[$group->slug][$setting->key] = [
                    'value' => $setting->value,
                    'type'  => $setting->type,
                    'label' => $setting->label,
                ];
            }
        }

        return $export;
    }

    /**
     * Import settings from JSON data.
     */
    public function import(array $data): int
    {
        $count = 0;

        foreach ($data as $groupSlug => $settings) {
            foreach ($settings as $key => $settingData) {
                if (in_array($key, Setting::SECRET_KEYS)) {
                    continue; // Skip secrets
                }

                $value = is_array($settingData) ? ($settingData['value'] ?? null) : $settingData;
                $setting = Setting::byKey($key)->first();

                if ($setting) {
                    $setting->update(['value' => $value]);
                    $count++;
                }
            }
        }

        $this->clearCache();
        return $count;
    }
}
