<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = ['school_id', 'name', 'description'];

    public function school() {
        return $this->belongsTo(School::class);
    }
}
