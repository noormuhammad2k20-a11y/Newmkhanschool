<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'teacher_id',
        'date',
        'status',
        'remarks',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
