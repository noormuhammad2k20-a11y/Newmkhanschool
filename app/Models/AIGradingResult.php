<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIGradingResult extends Model
{
    use HasFactory;

    protected $table = 'ai_grading_results';

    protected $fillable = [
        'submission_id',
        'suggested_score',
        'feedback',
        'rubric_breakdown',
        'model_used',
        'tokens_used',
    ];

    protected $casts = [
        'rubric_breakdown' => 'array',
    ];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }
}
