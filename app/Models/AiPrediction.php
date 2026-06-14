<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPrediction extends Model
{
    protected $table = 'ai_predictions';

    protected $fillable = [
        'student_id',
        'risk_type',
        'probability',
        'factors',
        'recommendations',
        'predicted_at'
    ];

    protected $casts = [
        'probability' => 'decimal:2',
        'factors' => 'array',
        'predicted_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
