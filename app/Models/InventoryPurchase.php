<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'item_name',
        'supplier_name',
        'quantity',
        'unit_price',
        'total_cost',
        'purchase_date',
        'recorded_by',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function ledgerEntries()
    {
        return $this->morphMany(LedgerEntry::class, 'reference');
    }
}
