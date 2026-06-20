<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranchScope;

class AcademicCycleRule extends Model
{
    use HasBranchScope;

    protected $fillable = [
        'school_id',
        'exam_month',
        'result_processing_month',
        'promotion_window_start_month',
        'promotion_window_end_month',
        'next_session_start_month',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }
}
