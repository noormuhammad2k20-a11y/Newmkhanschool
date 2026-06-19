<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsBackup extends Model
{
    public $timestamps = false;

    protected $table = 'settings_backups';

    protected $fillable = [
        'file_path',
        'file_size',
        'type',
        'status',
        'created_by',
    ];

    protected $casts = [
        'file_size'  => 'integer',
        'created_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
