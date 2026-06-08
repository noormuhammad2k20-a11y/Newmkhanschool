<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'exam_type',
        'class_name',
        'subject',
        'exam_date',
        'exam_time',
        'status',
        'class_id',
        'subject_id',
        'academic_year_id',
        'school_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope);
    }

    public function class_()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subjectRelation()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
