<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'submitted_at',
        'total_marks',
        'obtained_marks',
        'percentage',
        'status',
        'ip_address'
    ];

    public function exam()
    {
        return $this->belongsTo(OnlineExam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
