<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    const UPDATED_AT = null;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'user_id', 'employee_number', 'full_name', 'gender', 'dob', 'mobile_number',
        'joining_date', 'qualification', 'experience', 'address', 'status', 'photo',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }
    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class, 'teacher_id');
    }

    public function leaves()
    {
        return $this->hasMany(TeacherLeave::class, 'teacher_id');
    }

    public function user()          { return $this->belongsTo(User::class); }
    public function assignments()   { return $this->hasMany(TeacherAssignment::class); }
    public function classes()       { return $this->hasManyThrough(SchoolClass::class, TeacherAssignment::class,'teacher_id','id','id','class_id'); }
}


