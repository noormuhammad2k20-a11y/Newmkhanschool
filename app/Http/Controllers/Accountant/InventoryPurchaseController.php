<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryPurchase;

class InventoryPurchaseController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        $purchases = InventoryPurchase::with('recorder')
            ->where('school_id', $schoolId)
            ->latest('purchase_date')
            ->paginate(15);
            
        return view('accountant.inventory-purchases.index', compact('purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'supplier_name' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        $totalCost = $request->quantity * $request->unit_price;

        InventoryPurchase::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'item_name' => $request->item_name,
            'supplier_name' => $request->supplier_name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_cost' => $totalCost,
            'purchase_date' => $request->purchase_date,
            'recorded_by' => auth()->id() ?? 1,
        ]);

        return back()->with('success', 'Inventory purchase recorded and posted to ledger successfully.');
    }
}
