<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['school_id', 'expense_category_id', 'amount', 'date', 'description', 'status', 'recorded_by', 'receipt_path', 'payment_mode', 'paid_to', 'voucher_no'];

    public function school() { return $this->belongsTo(School::class); }
    public function category() { return $this->belongsTo(ExpenseCategory::class, 'expense_category_id'); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
    
    // For LedgerEntry morph
    public function ledgerEntries() { return $this->morphMany(LedgerEntry::class, 'reference'); }
}
