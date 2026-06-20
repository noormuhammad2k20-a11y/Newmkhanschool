<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranchScope;

class PromotionBatch extends Model
{
    use HasBranchScope;

    protected $fillable = [
        'school_id',
        'from_session_id',
        'to_session_id',
        'from_class_id',
        'to_class_id',
        'from_section_id',
        'to_section_id',
        'total_students',
        'status',
        'created_by',
        'approved_by',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Scopes\SchoolScope());
    }

    public function fromSession() { return $this->belongsTo(AcademicYear::class, 'from_session_id'); }
    public function toSession() { return $this->belongsTo(AcademicYear::class, 'to_session_id'); }
    public function fromClass() { return $this->belongsTo(SchoolClass::class, 'from_class_id'); }
    public function toClass() { return $this->belongsTo(SchoolClass::class, 'to_class_id'); }
    public function fromSection() { return $this->belongsTo(Section::class, 'from_section_id'); }
    public function toSection() { return $this->belongsTo(Section::class, 'to_section_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
    public function students() { return $this->hasMany(PromotionBatchStudent::class, 'batch_id'); }
}
