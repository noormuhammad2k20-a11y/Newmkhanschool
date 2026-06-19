<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    /** Keys that must be encrypted at rest + masked in API responses */
    public const SECRET_KEYS = [
        'notification.smtp_password',
        'notification.sms_api_key',
        'notification.whatsapp_api_key',
        'api.jazzcash_password',
        'api.jazzcash_salt',
        'api.easypaisa_hash_key',
        'api.gemini_api_key',
        'api.openai_api_key',
    ];

    protected $fillable = [
        'setting_group_id', 'key', 'value', 'type',
        'label', 'description', 'options', 'is_public', 'order',
    ];

    protected $casts = [
        'options'   => 'array',
        'is_public' => 'boolean',
    ];

    /** Dynamic attribute — set by controller when masking for display */
    public $is_masked = false;

    public function group()
    {
        return $this->belongsTo(SettingGroup::class, 'setting_group_id');
    }

    /**
     * Get the typed value based on the setting type.
     */
    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'toggle' => (bool) $this->value,
            'number' => is_numeric($this->value) ? (float) $this->value : null,
            'json'   => $this->isValidJson($this->value) ? json_decode($this->value, true) : $this->value,
            default  => $this->value,
        };
    }

    /**
     * Decrypt a secret value for server-side use only.
     * Returns raw plaintext — NEVER send this to the browser.
     */
    public function getDecryptedValueAttribute(): ?string
    {
        if (!in_array($this->key, self::SECRET_KEYS) || empty($this->value)) {
            return $this->value;
        }

        try {
            return Crypt::decryptString($this->value);
        } catch (\Throwable $e) {
            // Value might not be encrypted yet (legacy data)
            return $this->value;
        }
    }

    /**
     * Get masked value safe for sending to the browser.
     */
    public function getMaskedDisplayValueAttribute(): string
    {
        if (!in_array($this->key, self::SECRET_KEYS) || empty($this->value)) {
            return $this->value ?? '';
        }

        $decrypted = $this->decrypted_value;
        $len = strlen($decrypted);
        if ($len <= 4) {
            return str_repeat('•', $len);
        }
        return str_repeat('•', $len - 4) . substr($decrypted, -4);
    }

    /**
     * Scope to find by key.
     */
    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Scope to find by group slug.
     */
    public function scopeByGroup($query, string $slug)
    {
        return $query->whereHas('group', fn ($q) => $q->where('slug', $slug));
    }

    /**
     * Check if a string is valid JSON.
     */
    private function isValidJson($string): bool
    {
        if (!is_string($string) || empty($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
