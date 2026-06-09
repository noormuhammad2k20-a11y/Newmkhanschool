<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalNote extends Model
{
    protected $table = 'digital_notes';
    protected $fillable = ['title','description','file_path','file_type','external_url','subject_id','class_id','section_id','academic_year_id','uploaded_by','is_public','download_count','school_id'];
    
    public function subject()  { return $this->belongsTo(Subject::class); }
    public function class()    { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function section()  { return $this->belongsTo(Section::class, 'section_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class, 'academic_year_id'); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}
