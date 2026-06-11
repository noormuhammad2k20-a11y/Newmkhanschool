<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['title','description','subject_id','class_id','section_id','academic_year_id','created_by','total_marks','passing_marks','duration_minutes','start_at','end_at','is_active','school_id'];
    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime'];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }

    
    public function questions() { return $this->hasMany(QuizQuestion::class); }
    public function attempts()  { return $this->hasMany(QuizAttempt::class); }
    public function subject()   { return $this->belongsTo(Subject::class); }
    public function class()     { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function section()   { return $this->belongsTo(Section::class, 'section_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class, 'academic_year_id'); }
    public function creator()   { return $this->belongsTo(User::class, 'created_by'); }
}
