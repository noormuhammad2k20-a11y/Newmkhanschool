<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    use HasFactory;

    protected $table = 'parent_students';

    protected $fillable = [
        'parent_user_id',
        'student_id',
    ];

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
