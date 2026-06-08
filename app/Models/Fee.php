<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
        'challan_no','student_id','fee_category','amount','discount',
        'fine','paid_amount','due_date','status',
    ];

    public function student()       { return $this->belongsTo(Student::class); }
    public function payments()      { return $this->hasMany(FeePayment::class,'fee_id'); }
}
