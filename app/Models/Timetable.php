<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    public $timestamps = false;
    const UPDATED_AT = null;

    protected $fillable = [
        'timetable_version_id',
        'class_id',
        'section_id',
        'section_id_ref',
        'subject',
        'subject_id_ref',
        'teacher',
        'teacher_id',
        'room',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function version()       { return $this->belongsTo(TimetableVersion::class, 'timetable_version_id'); }

    public function teacher()       { return $this->belongsTo(Teacher::class,'teacher_id'); }
    public function subjectRef()    { return $this->belongsTo(Subject::class,'subject_id_ref'); }
    public function sectionRef()    { return $this->belongsTo(Section::class,'section_id_ref'); }
    public function class_()        { return $this->belongsTo(SchoolClass::class,'class_id'); }
}
