<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingGroup extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'slug', 'icon', 'description', 'order',
    ];

    public function settings()
    {
        return $this->hasMany(Setting::class)->orderBy('order');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
