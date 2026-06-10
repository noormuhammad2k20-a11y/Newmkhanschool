<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeStructure;

class FeeStructureController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fee_category_id' => 'required|exists:fee_categories,id',
            'class_id' => 'required|exists:classes,id',
            'amount' => 'required|numeric|min:0'
        ]);
        
        FeeStructure::updateOrCreate(
            ['school_id' => auth()->user()->school_id, 'fee_category_id' => $request->fee_category_id, 'class_id' => $request->class_id],
            ['amount' => $request->amount]
        );
        return redirect()->back()->with('success', 'Fee Structure saved successfully.');
    }

    public function destroy($id)
    {
        $structure = FeeStructure::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $structure->delete();
        return redirect()->back()->with('success', 'Fee Structure deleted successfully.');
    }
}
