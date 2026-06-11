<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'branch_code',
        'parent_school_id',
        'is_main_branch',
        'is_active',
        'city',
        'principal_name',
        'logo'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'school_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'school_id');
    }

    public function branches()
    {
        return $this->hasMany(School::class, 'parent_school_id');
    }

    public function parentSchool()
    {
        return $this->belongsTo(School::class, 'parent_school_id');
    }
}
