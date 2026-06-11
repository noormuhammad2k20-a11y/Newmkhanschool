<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatingAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'seating_plan_id',
        'student_id',
        'row_num',
        'col_num',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
