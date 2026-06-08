<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    const UPDATED_AT = null;

    public function teacher()       { return $this->belongsTo(Teacher::class,'teacher_id'); }
    public function subjectRef()    { return $this->belongsTo(Subject::class,'subject_id_ref'); }
    public function sectionRef()    { return $this->belongsTo(Section::class,'section_id_ref'); }
    public function class_()        { return $this->belongsTo(SchoolClass::class,'class_id'); }
}
