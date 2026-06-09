<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubstituteAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_request_id',
        'original_teacher_id',
        'substitute_teacher_id',
        'class_id',
        'subject_id',
        'date',
        'period_time',
        'status',
        'notes',
        'assigned_by'
    ];

    public function leaveRequest()
    {
        return $this->belongsTo(TeacherLeaveRequest::class);
    }

    public function originalTeacher()
    {
        return $this->belongsTo(Teacher::class, 'original_teacher_id');
    }

    public function substituteTeacher()
    {
        return $this->belongsTo(Teacher::class, 'substitute_teacher_id');
    }

    public function class_()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
