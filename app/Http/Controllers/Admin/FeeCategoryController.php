<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;
use App\Models\FeeCategory;

class FeeCategoryController extends Controller
{
    use AjaxResponseTrait;

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        FeeCategory::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return $this->ajaxSuccess($request, 'Fee Category created successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $category = FeeCategory::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $category->delete();
        return $this->ajaxSuccess($request, 'Fee Category deleted successfully.');
    }
}
