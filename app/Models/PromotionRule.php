<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionRule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'from_class_id',
        'to_class_id',
        'min_percentage',
        'min_attendance_pct',
        'academic_year_id',
        'created_at'
    ];

    public function fromClass()
    {
        return $this->belongsTo(SchoolClass::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(SchoolClass::class, 'to_class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
