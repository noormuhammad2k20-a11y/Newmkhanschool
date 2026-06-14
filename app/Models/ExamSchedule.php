<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'exam_type',
        'exam_date',
        'exam_time',
        'end_time',
        'class_id',
        'subject_id',
        'academic_year_id',
        'school_id',
        'max_marks',
        'passing_marks'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope);
    }

    public function class_()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subjectRelation()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function getGeneralStatusAttribute()
    {
        if (!$this->exam_date || !$this->exam_time || !$this->end_time) {
            return 'Scheduled';
        }

        try {
            $now = \Carbon\Carbon::now();
            $startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->exam_date . ' ' . (strlen($this->exam_time) == 5 ? $this->exam_time . ':00' : $this->exam_time));
            $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->exam_date . ' ' . (strlen($this->end_time) == 5 ? $this->end_time . ':00' : $this->end_time));

            if ($now->lt($startDateTime)) {
                return 'Scheduled';
            } elseif ($now->between($startDateTime, $endDateTime)) {
                return 'In Progress';
            } else {
                return 'Completed';
            }
        } catch (\Exception $e) {
            return 'Scheduled';
        }
    }

    public function getStudentStatus(\App\Models\Student $student)
    {
        if (!$this->exam_date || !$this->exam_time || !$this->end_time) {
            return 'Scheduled';
        }

        try {
            $now = \Carbon\Carbon::now();
            $startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->exam_date . ' ' . (strlen($this->exam_time) == 5 ? $this->exam_time . ':00' : $this->exam_time));
            $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->exam_date . ' ' . (strlen($this->end_time) == 5 ? $this->end_time . ':00' : $this->end_time));

            if ($now->lt($startDateTime)) {
                return 'Scheduled';
            } elseif ($now->between($startDateTime, $endDateTime)) {
                return 'In Progress';
            } else {
                // Exam time has ended. Check Attendance & Marks.
                $attendance = \App\Models\StudentAttendance::where('student_id', $student->id)
                                ->whereDate('date', $this->exam_date)
                                ->first();

                if (!$attendance || $attendance->status !== 'P') {
                    return 'Absent / Missed';
                }

                // Attendance is 'P'. Check if marks are entered.
                $marksEntered = \App\Models\Mark::where('student_id', $student->id)
                                    ->where('exam_schedule_id', $this->id)
                                    ->exists();

                if ($marksEntered) {
                    return 'Completed';
                } else {
                    return 'Pending Results';
                }
            }
        } catch (\Exception $e) {
            return 'Scheduled';
        }
    }
}
