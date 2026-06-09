<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    public $timestamps = false; // Note: has started_at, submitted_at, no created_at
    protected $fillable = ['quiz_id','student_id','started_at','submitted_at','score','total_marks','percentage','status'];
    protected $casts = ['started_at' => 'datetime', 'submitted_at' => 'datetime'];
    
    public function quiz()    { return $this->belongsTo(Quiz::class); }
    public function student() { return $this->belongsTo(Student::class); }
    public function answers() { return $this->hasMany(QuizAnswer::class, 'attempt_id'); }
}
