<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = ['school_id', 'account_name', 'account_number', 'bank_name', 'branch', 'initial_balance', 'current_balance'];

    public function school() {
        return $this->belongsTo(School::class);
    }
}
