<?php
namespace App\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SchoolScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Super Admin sees everything
        if (auth()->check() && auth()->user()->hasRole('Super Admin')) return;

        $schoolId = auth()->user()?->school_id;
        if ($schoolId) {
            $builder->where($model->getTable() . '.school_id', $schoolId);
        }
    }
}
