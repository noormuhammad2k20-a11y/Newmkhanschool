<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBadge extends Model
{
    protected $table = 'student_badges';

    protected $fillable = [
        'student_id',
        'badge_type',
        'title',
        'description',
        'icon',
        'awarded_by',
        'awarded_at'
    ];

    protected $casts = [
        'awarded_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function awardedBy()
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }
}
