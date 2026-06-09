<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceAnomaly extends Model
{
    public $timestamps = false;
    protected $fillable = ['student_id', 'teacher_id', 'anomaly_type', 'description', 'severity', 'school_id', 'resolved', 'resolved_at'];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
