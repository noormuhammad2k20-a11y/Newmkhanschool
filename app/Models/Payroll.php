<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'teacher_id',
        'emp_id',
        'name',
        'role',
        'basic_pay',
        'allowances',
        'deductions',
        'net_salary',
        'status',
        'month_year'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
