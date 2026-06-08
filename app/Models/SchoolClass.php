<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    const UPDATED_AT = null;
    protected $table = 'classes';

    protected $fillable = ['name', 'numeric_value', 'status'];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }
}
