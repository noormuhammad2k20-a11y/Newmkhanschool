<?php

namespace App\Observers;

use App\Models\InventoryPurchase;
use App\Models\LedgerEntry;

class InventoryPurchaseObserver
{
    public function created(InventoryPurchase $purchase): void
    {
        LedgerEntry::create([
            'school_id' => $purchase->school_id,
            'date' => $purchase->purchase_date ?? now(),
            'description' => 'Inventory Purchase: ' . $purchase->item_name . ' (' . $purchase->quantity . ' units)',
            'type' => 'Expense',
            'amount' => $purchase->total_cost,
            'reference_id' => $purchase->id,
            'reference_type' => InventoryPurchase::class,
        ]);
    }
}
