<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;
use App\Models\FeeStructure;

class FeeStructureController extends Controller
{
    use AjaxResponseTrait;
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
        return $this->ajaxSuccess($request, 'Fee Structure saved successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'fees' => 'required|array',
            'fees.*' => 'array',
            'fees.*.*' => 'nullable|numeric|min:0'
        ]);

        $schoolId = auth()->user()->school_id;

        foreach ($request->fees as $classId => $categories) {
            foreach ($categories as $categoryId => $amount) {
                if ($amount === null || $amount === '') {
                    // Delete if amount is cleared
                    FeeStructure::where([
                        'school_id' => $schoolId,
                        'class_id' => $classId,
                        'fee_category_id' => $categoryId
                    ])->delete();
                } else {
                    // Update or Create
                    FeeStructure::updateOrCreate(
                        [
                            'school_id' => $schoolId,
                            'class_id' => $classId,
                            'fee_category_id' => $categoryId
                        ],
                        ['amount' => $amount]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Fee structures updated successfully.')->with('active_tab', 'tab-structures');
    }

    public function destroy(Request $request, $id)
    {
        $structure = FeeStructure::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $structure->delete();
        return $this->ajaxSuccess($request, 'Fee Structure deleted successfully.');
    }
}
