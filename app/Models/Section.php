<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['name', 'capacity', 'status', 'class_id'];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }
}
