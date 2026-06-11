<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AjaxResponseTrait;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Http\Requests\Admin\InventoryRequest;
use App\Http\Requests\Admin\StockTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    use AjaxResponseTrait;
    public function index(Request $request)
    {
        $query = Inventory::query();
        
        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('asset_code', 'like', '%' . $request->search . '%');
        }
        
        $items = $query->latest()->paginate(20)->withQueryString();
        
        // Stats
        $lowStockCount = Inventory::whereColumn('quantity', '<=', 'min_stock_alert')->count();
        
        return view('admin.inventory.index', compact('items', 'lowStockCount'));
    }

    public function create()
    {
        return view('admin.inventory.create');
    }

    public function store(InventoryRequest $request)
    {
        $data = $request->validated();
        $data['school_id'] = auth()->user()->school_id ?? 1;
        
        DB::transaction(function () use ($data) {
            $item = Inventory::create($data);
            
            // Initial stock in transaction
            if ($item->quantity > 0) {
                InventoryTransaction::create([
                    'inventory_id' => $item->id,
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'reason' => 'Initial Stock',
                    'performed_by' => auth()->id(),
                    'school_id' => $item->school_id
                ]);
            }
        });

        return $this->ajaxSuccess($request, 'Item added to inventory.', null, route('admin.inventory'));
    }

    public function show($id)
    {
        $item = Inventory::findOrFail($id);
        $transactions = $item->transactions()->with('performer')->latest()->paginate(20);
        return view('admin.inventory.show', compact('item', 'transactions'));
    }

    public function edit($id)
    {
        $item = Inventory::findOrFail($id);
        return view('admin.inventory.edit', compact('item'));
    }

    public function update(InventoryRequest $request, $id)
    {
        $item = Inventory::findOrFail($id);
        $item->update($request->validated());
        return $this->ajaxSuccess($request, 'Item updated successfully.', null, route('admin.inventory'));
    }

    public function destroy(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);
        $item->delete();
        return $this->ajaxSuccess($request, 'Item deleted successfully.');
    }

    public function stockInForm($id)
    {
        $item = Inventory::findOrFail($id);
        return view('admin.inventory.stock-in', compact('item'));
    }

    public function stockIn(StockTransactionRequest $request, $id)
    {
        $item = Inventory::findOrFail($id);
        
        DB::transaction(function () use ($item, $request) {
            $item->increment('quantity', $request->quantity);
            
            InventoryTransaction::create([
                'inventory_id' => $item->id,
                'type' => 'in',
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'reference_no' => $request->reference_no,
                'performed_by' => auth()->id(),
                'school_id' => $item->school_id
            ]);
        });

        return $this->ajaxSuccess($request, 'Stock added successfully.', null, route('admin.inventory.show', $item->id));
    }

    public function stockOutForm($id)
    {
        $item = Inventory::findOrFail($id);
        return view('admin.inventory.stock-out', compact('item'));
    }

    public function stockOut(StockTransactionRequest $request, $id)
    {
        $item = Inventory::findOrFail($id);
        
        if ($item->quantity < $request->quantity) {
            return $this->ajaxError($request, 'Insufficient stock.');
        }

        DB::transaction(function () use ($item, $request) {
            $item->decrement('quantity', $request->quantity);
            
            InventoryTransaction::create([
                'inventory_id' => $item->id,
                'type' => 'out',
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'reference_no' => $request->reference_no,
                'performed_by' => auth()->id(),
                'school_id' => $item->school_id
            ]);
        });

        return $this->ajaxSuccess($request, 'Stock issued successfully.', null, route('admin.inventory.show', $item->id));
    }

    public function lowStock()
    {
        $items = Inventory::whereColumn('quantity', '<=', 'min_stock_alert')
            ->latest()->paginate(20);
        $lowStockCount = $items->total();
        
        return view('admin.inventory.index', compact('items', 'lowStockCount'));
    }
}
