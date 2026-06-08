<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'student_leave_requests';

    protected $fillable = [
        'student_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
