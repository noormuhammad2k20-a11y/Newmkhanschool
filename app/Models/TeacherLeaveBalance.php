<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLeaveBalance extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'teacher_id',
        'academic_year_id',
        'casual_total',
        'casual_used',
        'sick_total',
        'sick_used',
        'annual_total',
        'annual_used'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
