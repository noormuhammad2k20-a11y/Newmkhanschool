<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    const CREATED_AT = 'promoted_at';

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'to_academic_year_id',
        'from_class_id',
        'from_section_id',
        'to_class_id',
        'to_section_id',
        'promotion_type',
        'status',
        'promoted_by',
        'remarks',
        'error_message',
        'batch_id',
        'school_id',
        'promoted_at',
    ];

    protected $casts = [
        'promoted_at' => 'datetime',
    ];

    /* ── Relationships ─────────────────────────────── */

    public function student()
    {
        return $this->belongsTo(Student::class)->withTrashed();
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function toAcademicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }

    public function fromClass()
    {
        return $this->belongsTo(SchoolClass::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(SchoolClass::class, 'to_class_id');
    }

    public function fromSection()
    {
        return $this->belongsTo(Section::class, 'from_section_id');
    }

    public function toSection()
    {
        return $this->belongsTo(Section::class, 'to_section_id');
    }

    public function promotedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'promoted_by');
    }

    /* ── Scopes ────────────────────────────────────── */

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}
