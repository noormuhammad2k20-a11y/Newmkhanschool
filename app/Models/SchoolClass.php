<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolClass extends Model
{
    use HasFactory, HasBranchScope;

    const UPDATED_AT = null;
    protected $table = 'classes';

    protected $fillable = ['name', 'numeric_value', 'status'];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }
}
