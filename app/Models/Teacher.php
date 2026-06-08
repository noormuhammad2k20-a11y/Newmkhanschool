<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    const UPDATED_AT = null;

    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class, 'teacher_id');
    }

    public function leaves()
    {
        return $this->hasMany(TeacherLeave::class, 'teacher_id');
    }
}


