<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $fillable = ['school_id', 'date', 'description', 'type', 'amount', 'reference_id', 'reference_type', 'bank_account_id'];

    public function school() { return $this->belongsTo(School::class); }
    public function reference() { return $this->morphTo(); }
    public function bankAccount() { return $this->belongsTo(BankAccount::class); }
}
