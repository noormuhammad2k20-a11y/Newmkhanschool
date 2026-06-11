<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    const UPDATED_AT = null;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
