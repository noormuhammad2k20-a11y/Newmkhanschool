<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'setting_group_id', 'key', 'value', 'type',
        'label', 'description', 'options', 'is_public', 'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_public' => 'boolean',
    ];

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
