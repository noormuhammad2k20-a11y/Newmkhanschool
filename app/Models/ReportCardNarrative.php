<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCardNarrative extends Model
{
    protected $table = 'report_card_narratives';

    protected $fillable = [
        'report_card_id',
        'strengths',
        'improvements',
        'attendance_summary',
        'teacher_comments',
        'parent_guidance',
        'next_term_goals',
        'generated_by_ai',
        'generated_at',
        'is_locked',
        'version',
        'ai_confidence_score',
        'narrative_history'
    ];

    protected $casts = [
        'generated_by_ai' => 'boolean',
        'generated_at' => 'datetime',
        'is_locked' => 'boolean',
        'narrative_history' => 'array',
        'ai_confidence_score' => 'decimal:2'
    ];

    public function reportCard()
    {
        return $this->belongsTo(ReportCard::class, 'report_card_id');
    }
}
