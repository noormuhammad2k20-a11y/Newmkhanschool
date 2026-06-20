<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionBatchStudent extends Model
{
    protected $fillable = [
        'batch_id',
        'student_id',
        'status',
        'error_message',
        'eligibility_score',
        'category',
        'risk_flags',
    ];

    protected $casts = [
        'risk_flags' => 'array',
    ];

    public function batch() { return $this->belongsTo(PromotionBatch::class, 'batch_id'); }
    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
}
