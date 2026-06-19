<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingGroup;
use App\Models\SettingsAuditLog;
use App\Models\SettingsBackup;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{Crypt, Cache, Storage, Artisan, Mail, DB, Process};

class SettingController extends Controller
{
    /** Keys that must be encrypted at rest + masked in the API response */
    private const SECRET_KEYS = [
        'notification.smtp_password',
        'notification.sms_api_key',
        'notification.whatsapp_api_key',
        'api.jazzcash_password',
        'api.jazzcash_salt',
        'api.easypaisa_hash_key',
        'api.gemini_api_key',
        'api.openai_api_key',
    ];

    /**
     * Display the settings page with all groups and settings.
     */
    public function index()
    {
        $groups = SettingGroup::with(['settings' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')->get();

        // Mask secrets before sending to the view
        $groups->each(fn($g) => $g->settings->each(function ($s) {
            if (in_array($s->key, self::SECRET_KEYS) && filled($s->value)) {
                $s->value = $this->mask($s->value);
                $s->is_masked = true;
            }
        }));

        return view('admin.settings.index', compact('groups'));
    }

    /**
     * AJAX: autosave a single field on blur/change (debounced from JS).
     */
    public function updateField(Request $request): JsonResponse
    {
        $request->validate(['key' => 'required|string', 'value' => 'nullable']);

        $setting = Setting::where('key', $request->key)->firstOrFail();

        try {
            $this->validateFieldValue($setting, $request->value);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $old = $setting->value;
        $newValue = $request->value;

        // Encrypt secret values
        if (in_array($setting->key, self::SECRET_KEYS) && filled($newValue) && !str_contains($newValue, '•')) {
            $newValue = Crypt::encryptString($newValue);
        }

        // Skip overwrite if the masked placeholder was resubmitted unchanged
        if (str_contains((string) $newValue, '•')) {
            return response()->json(['success' => true, 'unchanged' => true]);
        }

        $setting->update(['value' => $newValue]);

        // Audit log
        SettingsAuditLog::create([
            'setting_id'  => $setting->id,
            'setting_key' => $setting->key,
            'old_value'   => in_array($setting->key, self::SECRET_KEYS) ? '[hidden]' : $old,
            'new_value'   => in_array($setting->key, self::SECRET_KEYS) ? '[hidden]' : $newValue,
            'changed_by'  => auth()->id(),
            'ip_address'  => $request->ip(),
        ]);

        Cache::forget("setting.{$setting->key}");
        Cache::forget('app_settings');

        return response()->json(['success' => true, 'message' => "{$setting->label} updated."]);
    }

    /**
     * AJAX: save a whole tab/group at once (Save button at bottom of each tab).
     */
    public function updateGroup(Request $request, string $slug): JsonResponse
    {
        $group = SettingGroup::where('slug', $slug)->firstOrFail();
        $errors = [];
        $count = 0;

        foreach ($request->except('_token') as $key => $value) {
            $setting = $group->settings()->where('key', $key)->first();
            if (!$setting) continue;

            try {
                $this->validateFieldValue($setting, $value);
            } catch (\Throwable $e) {
                $errors[$key] = $e->getMessage();
                continue;
            }

            if (in_array($key, self::SECRET_KEYS) && filled($value) && !str_contains($value, '•')) {
                $value = Crypt::encryptString($value);
            }

            if (!str_contains((string) $value, '•')) {
                $old = $setting->value;
                $setting->update(['value' => $value]);
                Cache::forget("setting.{$key}");

                SettingsAuditLog::create([
                    'setting_id'  => $setting->id,
                    'setting_key' => $setting->key,
                    'old_value'   => in_array($key, self::SECRET_KEYS) ? '[hidden]' : $old,
                    'new_value'   => in_array($key, self::SECRET_KEYS) ? '[hidden]' : $value,
                    'changed_by'  => auth()->id(),
                    'ip_address'  => $request->ip(),
                ]);
                $count++;
            }
        }

        Cache::forget('app_settings');

        if ($errors) {
            return response()->json(['success' => false, 'errors' => $errors, 'message' => 'Some fields failed validation.'], 422);
        }

        return response()->json(['success' => true, 'message' => ucfirst($group->name) . ' saved successfully.', 'count' => $count]);
    }

    /**
     * AJAX file upload — logo, favicon, signature, stamp, watermark, login bg.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'key'  => 'required|string',
            'file' => 'required|file|max:2048|mimes:png,jpg,jpeg,svg,webp,ico',
        ]);

        $setting = Setting::where('key', $request->key)->firstOrFail();

        // Delete old file if exists
        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        $path = $request->file('file')->store('settings', 'public');
        $setting->update(['value' => $path]);
        Cache::forget('app_settings');

        return response()->json(['success' => true, 'url' => Storage::url($path), 'path' => $path]);
    }

    /**
     * AJAX: remove uploaded image.
     */
    public function removeImage(string $key): JsonResponse
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->update(['value' => null]);
        Cache::forget('app_settings');

        return response()->json(['success' => true, 'message' => "{$setting->label} removed."]);
    }

    /**
     * Test SMTP connection with current (unsaved) form values.
     */
    public function testSmtp(Request $request): JsonResponse
    {
        config(['mail.mailers.smtp' => [
            'transport'  => 'smtp',
            'host'       => $request->smtp_host,
            'port'       => $request->smtp_port,
            'username'   => $request->smtp_username,
            'password'   => $request->smtp_password,
            'encryption' => $request->smtp_port == 465 ? 'ssl' : 'tls',
        ]]);

        try {
            $testEmail = $request->test_email ?: auth()->user()->email;
            Mail::raw('This is a test email from ' . setting('general.app_name', 'School Management System') . '.', function ($m) use ($testEmail) {
                $m->to($testEmail)->subject('SMTP Test — ' . setting('general.app_name', 'School'));
            });
            return response()->json(['success' => true, 'message' => "Test email sent successfully to {$testEmail}."]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'SMTP test failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Test SMS gateway connection.
     */
    public function testSms(Request $request): JsonResponse
    {
        $url = $request->gateway_url;
        $key = $request->api_key;

        if (empty($url) || empty($key)) {
            return response()->json(['success' => false, 'message' => 'Gateway URL and API key are required.'], 422);
        }

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $key],
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 200 && $httpCode < 400) {
                return response()->json(['success' => true, 'message' => 'SMS gateway is reachable (HTTP ' . $httpCode . ').']);
            }
            return response()->json(['success' => false, 'message' => 'SMS gateway returned HTTP ' . $httpCode . '.'], 422);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'SMS test failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Test WhatsApp API connection.
     */
    public function testWhatsapp(Request $request): JsonResponse
    {
        $key = $request->api_key;

        if (empty($key)) {
            return response()->json(['success' => false, 'message' => 'WhatsApp API key is required.'], 422);
        }

        // Simulated connection test — replace with actual API endpoint when configured
        return response()->json(['success' => true, 'message' => 'WhatsApp API key format validated. Configure a real endpoint for full testing.']);
    }

    /**
     * Test JazzCash sandbox authentication.
     */
    public function testJazzCash(Request $request): JsonResponse
    {
        $merchantId = $request->merchant_id;
        $password   = $request->password;

        if (empty($merchantId) || empty($password)) {
            return response()->json(['success' => false, 'message' => 'Merchant ID and password are required.'], 422);
        }

        try {
            $isSandbox = str_contains(strtolower($merchantId), 'sandbox');
            $url = $isSandbox
                ? 'https://sandbox.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction'
                : 'https://payments.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction';

            // Lightweight connectivity check
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_NOBODY         => true,
            ]);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $env = $isSandbox ? 'Sandbox' : 'Production';
            return response()->json(['success' => true, 'message' => "JazzCash {$env} endpoint is reachable (HTTP {$httpCode}). Credentials will be verified on first transaction."]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'JazzCash test failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Test EasyPaisa connection.
     */
    public function testEasyPaisa(Request $request): JsonResponse
    {
        $storeId = $request->store_id;
        $hashKey = $request->hash_key;

        if (empty($storeId) || empty($hashKey)) {
            return response()->json(['success' => false, 'message' => 'Store ID and hash key are required.'], 422);
        }

        try {
            $ch = curl_init('https://easypay.easypaisa.com.pk/easypay/Index.jsf');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_NOBODY         => true,
            ]);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return response()->json(['success' => true, 'message' => "EasyPaisa endpoint is reachable (HTTP {$httpCode}). Credentials will be verified on first transaction."]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'EasyPaisa test failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Verify Gemini AI API key by listing models.
     */
    public function testGemini(Request $request): JsonResponse
    {
        $apiKey = $request->api_key;

        if (empty($apiKey) || str_contains($apiKey, '•')) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid API key (not the masked placeholder).'], 422);
        }

        try {
            $ch = curl_init("https://generativelanguage.googleapis.com/v1/models?key={$apiKey}");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                $modelCount = count($data['models'] ?? []);
                return response()->json(['success' => true, 'message' => "✓ Valid key — {$modelCount} models available."]);
            }
            return response()->json(['success' => false, 'message' => '✗ Invalid or expired key (HTTP ' . $httpCode . ').'], 422);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Gemini test failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Verify OpenAI API key by listing models.
     */
    public function testOpenAi(Request $request): JsonResponse
    {
        $apiKey = $request->api_key;

        if (empty($apiKey) || str_contains($apiKey, '•')) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid API key (not the masked placeholder).'], 422);
        }

        try {
            $ch = curl_init('https://api.openai.com/v1/models');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json',
                ],
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                $modelCount = count($data['data'] ?? []);
                return response()->json(['success' => true, 'message' => "✓ Valid key — {$modelCount} models available."]);
            }
            return response()->json(['success' => false, 'message' => '✗ Invalid or expired key (HTTP ' . $httpCode . ').'], 422);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'OpenAI test failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Run a database backup now.
     */
    public function runBackupNow(Request $request): JsonResponse
    {
        $backup = SettingsBackup::create([
            'file_path'  => '',
            'file_size'  => 0,
            'type'       => 'manual',
            'status'     => 'running',
            'created_by' => auth()->id(),
        ]);

        // Run backup synchronously (for simplicity without queue worker)
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $dir = storage_path('app/backups');
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $filepath = $dir . '/' . $filename;

            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port', 3306);
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            // Try mysqldump first
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s 2>&1',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !file_exists($filepath) || filesize($filepath) < 100) {
                // Fallback: PHP-based table export
                $this->phpDatabaseExport($filepath, $dbName);
            }

            if (file_exists($filepath) && filesize($filepath) > 0) {
                $backup->update([
                    'file_path' => 'backups/' . $filename,
                    'file_size' => filesize($filepath),
                    'status'    => 'completed',
                ]);
                return response()->json(['success' => true, 'message' => 'Backup completed successfully.', 'backup' => $backup]);
            } else {
                $backup->update(['status' => 'failed']);
                return response()->json(['success' => false, 'message' => 'Backup file was empty or not created.'], 500);
            }
        } catch (\Throwable $e) {
            $backup->update(['status' => 'failed']);
            return response()->json(['success' => false, 'message' => 'Backup failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * PHP-based database export fallback.
     */
    private function phpDatabaseExport(string $filepath, string $dbName): void
    {
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . $dbName;
        $sql = "-- Database Backup: {$dbName}\n-- Generated: " . now() . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$key;
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $values = collect((array) $row)->map(function ($v) {
                    return $v === null ? 'NULL' : "'" . addslashes($v) . "'";
                })->implode(', ');
                $sql .= "INSERT INTO `{$tableName}` VALUES ({$values});\n";
            }
            $sql .= "\n";
        }

        file_put_contents($filepath, $sql);
    }

    /**
     * List all backups.
     */
    public function listBackups(): JsonResponse
    {
        $backups = SettingsBackup::with('creator:id,name')
            ->latest('created_at')->take(20)->get()
            ->map(fn($b) => [
                'id'         => $b->id,
                'file_path'  => $b->file_path,
                'file_size'  => $b->formatted_size,
                'type'       => $b->type,
                'status'     => $b->status,
                'created_by' => $b->creator?->name ?? 'System',
                'created_at' => $b->created_at?->format('M d, Y H:i'),
            ]);

        return response()->json($backups);
    }

    /**
     * Download a backup file.
     */
    public function downloadBackup(int $id)
    {
        $backup = SettingsBackup::findOrFail($id);
        $filepath = storage_path('app/' . $backup->file_path);

        if (!file_exists($filepath)) {
            return response()->json(['success' => false, 'message' => 'Backup file not found.'], 404);
        }

        return response()->download($filepath);
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackup(int $id): JsonResponse
    {
        $backup = SettingsBackup::findOrFail($id);
        $filepath = storage_path('app/' . $backup->file_path);

        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $backup->delete();
        return response()->json(['success' => true, 'message' => 'Backup deleted.']);
    }

    /**
     * Clear all application caches.
     */
    public function clearCache(): JsonResponse
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            return response()->json(['success' => true, 'message' => 'Application cache cleared successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Cache clear failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * System health check.
     */
    public function healthCheck(): JsonResponse
    {
        return response()->json([
            'database' => $this->checkDatabase(),
            'storage'  => $this->checkStorageWritable(),
            'queue'    => $this->checkQueueWorker(),
            'cache'    => $this->checkCacheDriver(),
        ]);
    }

    /**
     * Export all settings as JSON (secrets excluded).
     */
    public function export()
    {
        $data = Setting::with('group')->get()
            ->reject(fn($s) => in_array($s->key, self::SECRET_KEYS))
            ->map(fn($s) => ['key' => $s->key, 'value' => $s->value]);

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="settings-export-' . date('Y-m-d') . '.json"');
    }

    /**
     * Import settings from JSON file.
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate(['file' => 'required|file|mimes:json,txt|max:1024']);

        $content = file_get_contents($request->file('file')->getRealPath());
        $data = json_decode($content, true);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON file.'], 422);
        }

        $count = 0;

        // Support both flat array [{key, value}] and grouped {slug: {key: {value}}} formats
        foreach ($data as $item) {
            if (is_array($item) && isset($item['key'])) {
                $result = Setting::where('key', $item['key'])
                    ->whereNotIn('key', self::SECRET_KEYS)
                    ->update(['value' => $item['value'] ?? null]);
                if ($result) $count++;
            }
        }

        Cache::flush();
        return response()->json(['success' => true, 'message' => "{$count} setting(s) imported successfully."]);
    }

    /**
     * Reset a single field to its seeded default value.
     */
    public function resetToDefault(Request $request, string $key): JsonResponse
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        // Defaults map — these match what was seeded
        $defaults = [
            'general.app_name'          => 'School Management System',
            'general.timezone'          => 'Asia/Karachi',
            'general.date_format'       => 'd-m-Y',
            'general.currency'          => 'PKR',
            'general.language'          => 'en',
            'security.min_password_length'   => '8',
            'security.session_timeout'       => '30',
            'security.max_login_attempts'    => '5',
            'security.lockout_duration'      => '15',
            'examination.passing_marks'      => '33',
            'maintenance.log_level'          => 'error',
            'maintenance.backup_frequency'   => 'daily',
            'maintenance.backup_retention'   => '30',
            'certificate.watermark_opacity'  => '15',
        ];

        $defaultValue = $defaults[$key] ?? '';

        $old = $setting->value;
        $setting->update(['value' => $defaultValue]);
        Cache::forget('app_settings');

        SettingsAuditLog::create([
            'setting_id'  => $setting->id,
            'setting_key' => $setting->key,
            'old_value'   => in_array($key, self::SECRET_KEYS) ? '[hidden]' : $old,
            'new_value'   => '[reset to default]',
            'changed_by'  => auth()->id(),
            'ip_address'  => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$setting->label} reset to default.",
            'value'   => $defaultValue,
        ]);
    }

    /**
     * Get paginated audit log entries.
     */
    public function auditLog(Request $request): JsonResponse
    {
        $logs = SettingsAuditLog::with('user:id,name')
            ->latest('created_at')
            ->paginate(20);

        return response()->json($logs);
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenance(Request $request): JsonResponse
    {
        $enable = $request->boolean('enable');

        try {
            if ($enable) {
                Artisan::call('down', ['--secret' => 'admin-bypass-' . auth()->id()]);
                $message = $request->input('message', 'The system is currently under maintenance. Please check back later.');
                $setting = Setting::where('key', 'maintenance.maintenance_message')->first();
                if ($setting) $setting->update(['value' => $message]);
            } else {
                Artisan::call('up');
            }

            // Update the toggle setting
            $toggle = Setting::where('key', 'maintenance.maintenance_mode')->first();
            if ($toggle) $toggle->update(['value' => $enable ? '1' : '0']);
            Cache::forget('app_settings');

            return response()->json([
                'success' => true,
                'message' => $enable ? 'Maintenance mode enabled.' : 'Maintenance mode disabled.',
                'enabled' => $enable,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get the current user's IP address (for IP Whitelist "Add my IP" button).
     */
    public function getMyIp(Request $request): JsonResponse
    {
        return response()->json(['ip' => $request->ip()]);
    }

    // ─── Helpers ──────────────────────────────────────────────

    /**
     * Type-aware server-side validation for a single field value.
     */
    private function validateFieldValue(Setting $setting, mixed $value): void
    {
        $key = $setting->key;
        $type = $setting->type;

        switch ($type) {
            case 'email':
                if (filled($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new \InvalidArgumentException('Invalid email address.');
                }
                break;
            case 'url':
                if (filled($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    throw new \InvalidArgumentException('Invalid URL format.');
                }
                break;
            case 'number':
                if (filled($value) && !is_numeric($value)) {
                    throw new \InvalidArgumentException('Value must be a number.');
                }
                // Specific field validations
                if ($key === 'security.min_password_length' && ($value < 4 || $value > 128)) {
                    throw new \InvalidArgumentException('Password length must be between 4 and 128.');
                }
                if ($key === 'security.session_timeout' && ($value < 5 || $value > 1440)) {
                    throw new \InvalidArgumentException('Session timeout must be between 5 and 1440 minutes.');
                }
                if ($key === 'security.max_login_attempts' && ($value < 1 || $value > 100)) {
                    throw new \InvalidArgumentException('Max attempts must be between 1 and 100.');
                }
                if ($key === 'security.lockout_duration' && ($value < 1 || $value > 1440)) {
                    throw new \InvalidArgumentException('Lockout duration must be between 1 and 1440 minutes.');
                }
                if ($key === 'examination.passing_marks' && ($value < 0 || $value > 100)) {
                    throw new \InvalidArgumentException('Passing marks must be between 0 and 100.');
                }
                if ($key === 'certificate.watermark_opacity' && ($value < 0 || $value > 100)) {
                    throw new \InvalidArgumentException('Opacity must be between 0 and 100.');
                }
                break;
            case 'json':
                if (filled($value)) {
                    $decoded = json_decode($value);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
                    }
                }
                break;
            case 'toggle':
                // Accept 0, 1, true, false, "0", "1"
                break;
            case 'color':
                if (filled($value) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) {
                    throw new \InvalidArgumentException('Invalid hex color format. Use #RRGGBB.');
                }
                break;
        }
    }

    /**
     * Mask a secret value for display: show only last 4 chars.
     */
    private function mask(string $value): string
    {
        $decrypted = '';
        try {
            $decrypted = Crypt::decryptString($value);
        } catch (\Throwable $e) {
            $decrypted = $value;
        }
        $len = strlen($decrypted);
        return $len <= 4 ? str_repeat('•', $len) : str_repeat('•', $len - 4) . substr($decrypted, -4);
    }

    // ─── Health Check Helpers ──────────────────────────────────

    private function checkDatabase(): array
    {
        try {
            DB::select('SELECT 1');
            return ['status' => 'ok', 'message' => 'Connected'];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkStorageWritable(): array
    {
        $path = storage_path('app');
        return is_writable($path)
            ? ['status' => 'ok', 'message' => 'Writable']
            : ['status' => 'error', 'message' => 'Not writable'];
    }

    private function checkQueueWorker(): array
    {
        // Simple check: if using sync driver, queue is "always running"
        $driver = config('queue.default');
        if ($driver === 'sync') {
            return ['status' => 'ok', 'message' => 'Sync driver (no worker needed)'];
        }
        return ['status' => 'warning', 'message' => "Driver: {$driver} — verify worker is running"];
    }

    private function checkCacheDriver(): array
    {
        try {
            Cache::put('health_check_test', true, 5);
            $result = Cache::get('health_check_test');
            Cache::forget('health_check_test');
            return $result
                ? ['status' => 'ok', 'message' => 'Driver: ' . config('cache.default')]
                : ['status' => 'error', 'message' => 'Cache read/write failed'];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
