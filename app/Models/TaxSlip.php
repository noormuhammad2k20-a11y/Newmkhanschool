<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSlip extends Model
{
    protected $fillable = ['school_id', 'teacher_id', 'tax_year', 'total_income', 'tax_deducted', 'generated_at'];
    protected $casts = ['generated_at' => 'datetime'];

    public function school() { return $this->belongsTo(School::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
}
