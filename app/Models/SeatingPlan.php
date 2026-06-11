<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranchScope;

class SeatingPlan extends Model
{
    use HasFactory, HasBranchScope;

    protected $fillable = [
        'name',
        'class_id',
        'section_id',
        'teacher_id',
        'rows',
        'cols',
        'school_id',
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function assignments()
    {
        return $this->hasMany(SeatingAssignment::class);
    }
}
