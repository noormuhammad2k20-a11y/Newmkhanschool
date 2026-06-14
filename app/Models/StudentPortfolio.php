<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPortfolio extends Model
{
    protected $table = 'student_portfolios';

    protected $fillable = [
        'student_id',
        'title',
        'description',
        'visibility',
        'skills_json',
        'completion_score'
    ];

    protected $casts = [
        'skills_json' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function items()
    {
        return $this->hasMany(PortfolioItem::class, 'portfolio_id');
    }
}
