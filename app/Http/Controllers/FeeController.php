<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $categories = \App\Models\FeeCategory::all();
            $classes = \App\Models\SchoolClass::all();
            $structures = \App\Models\FeeStructure::with(['category', 'class'])->get();
        } else {
            $schoolId = auth()->user()->school_id;
            $categories = \App\Models\FeeCategory::where('school_id', $schoolId)->get();
            $classes = \App\Models\SchoolClass::where('school_id', $schoolId)->get();
            $structures = \App\Models\FeeStructure::with(['category', 'class'])
                            ->where('school_id', $schoolId)->get();
        }

        return view('fees.index', compact('categories', 'classes', 'structures'));
    }
}
