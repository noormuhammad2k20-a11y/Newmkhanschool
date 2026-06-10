<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'challan_no','student_id','fee_category_id','fee_category','amount','discount',
        'fine','paid_amount','due_date','status',
    ];

    public function student()       { return $this->belongsTo(Student::class); }
    public function payments()      { return $this->hasMany(FeePaymentTransaction::class,'fee_id'); }
    public function category()      { return $this->belongsTo(FeeCategory::class, 'fee_category_id'); }
}
