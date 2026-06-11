<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranchScope;

class Section extends Model
{
    use HasFactory, HasBranchScope;

    const UPDATED_AT = null;

    protected $fillable = ['name', 'capacity', 'status', 'class_id'];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }
}
