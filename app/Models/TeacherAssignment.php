<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    protected $table = 'teacher_assignments';
    protected $fillable = ['teacher_id', 'class_id', 'subject_id'];

    public function teacher()  { return $this->belongsTo(Teacher::class); }
    public function class_()   { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function subject()  { return $this->belongsTo(Subject::class); }
}
