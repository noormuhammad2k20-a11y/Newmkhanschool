<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLeave extends Model
{
    protected $table = 'teacher_leaves';
    
    // Disable updated_at if it's not in the schema
    const UPDATED_AT = null;

    protected $fillable = [
        'teacher_id',
        'leave_type',
        'start_date',
        'end_date',
        'status',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
