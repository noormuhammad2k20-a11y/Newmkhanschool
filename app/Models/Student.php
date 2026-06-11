<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranchScope;

class Student extends Model
{
    const UPDATED_AT = null;

    use \Illuminate\Database\Eloquent\SoftDeletes;
    use HasBranchScope;

    protected $fillable = [
        'admission_no','user_id','first_name','last_name','gender','dob',
        'b_form_number','father_name','father_cnic','mobile_number',
        'current_class_id','current_section_id','status','exam_roll',
        'class_admitted','admission_date','previous_school','placeofbirth',
        'address','religion','caste','photo','current_school',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }

    public function currentClass()  { return $this->belongsTo(SchoolClass::class,'current_class_id'); }
    public function currentSection(){ return $this->belongsTo(Section::class,'current_section_id'); }
    public function attendances()   { return $this->hasMany(StudentAttendance::class); }
    public function marks()         { return $this->hasMany(Mark::class); }
    public function fees()          { return $this->hasMany(Fee::class); }
    public function submissions()   { return $this->hasMany(AssignmentSubmission::class); }
    public function leaveRequests() { return $this->hasMany(StudentLeaveRequest::class); }
    public function reportCards()   { return $this->hasMany(ReportCard::class); }
    public function user()          { return $this->belongsTo(User::class); }
}
