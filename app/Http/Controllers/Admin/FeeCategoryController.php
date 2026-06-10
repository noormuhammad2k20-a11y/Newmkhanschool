<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeCategory;

class FeeCategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        FeeCategory::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return redirect()->back()->with('success', 'Fee Category created successfully.');
    }

    public function destroy($id)
    {
        $category = FeeCategory::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Fee Category deleted successfully.');
    }
}
