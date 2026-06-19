<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsAuditLog extends Model
{
    public $timestamps = false;

    protected $table = 'settings_audit_log';

    protected $fillable = [
        'setting_id',
        'setting_key',
        'old_value',
        'new_value',
        'changed_by',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'setting_id');
    }
}
