<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasBranchScope
{
    protected static function bootHasBranchScope()
    {
        static::addGlobalScope('branch', function (Builder $builder) {
            if (app()->has('active_branch_id') && app('active_branch_id')) {
                $builder->where('school_id', app('active_branch_id'));
            }
        });
    }
}
