<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'asset_code',
        'name',
        'description',
        'category',
        'quantity',
        'condition_status',
        'school_id',
        'unit',
        'min_stock_alert',
        'purchase_price',
        'supplier',
        'location',
    ];

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
