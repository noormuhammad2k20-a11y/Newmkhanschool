<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    public $timestamps = false;
    protected $fillable = ['attempt_id','question_id','selected_option','is_correct'];
    public function question() { return $this->belongsTo(QuizQuestion::class); }
}
