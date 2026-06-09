<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_school_id',
        'name',
        'code',
        'address',
        'city',
        'phone',
        'email',
        'principal_name',
        'logo',
        'status'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'school_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'school_id');
    }
}
