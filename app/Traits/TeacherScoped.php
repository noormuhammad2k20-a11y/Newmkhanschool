<?php
namespace App\Traits;
use App\Models\TeacherAssignment;

trait TeacherScoped
{
    protected function getTeacher()
    {
        $teacher = auth()->user()->teacher;
        abort_if(!$teacher, 403, 'Teacher record not found.');
        return $teacher;
    }

    protected function getAssignedClassIds($teacher): \Illuminate\Support\Collection
    {
        return TeacherAssignment::where('teacher_id', $teacher->id)->pluck('class_id');
    }

    protected function getAssignedSubjectIds($teacher): \Illuminate\Support\Collection
    {
        return TeacherAssignment::where('teacher_id', $teacher->id)->pluck('subject_id');
    }
}
