<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeStructure;
use App\Models\FeeCategory;
use App\Models\SchoolClass;

class FeeStructureController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $structures = FeeStructure::with(['category', 'class'])
            ->where(function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId)
                      ->orWhereNull('school_id');
            })
            ->get();
            
        return view('accountant.fee-structure.index', compact('structures'));
    }
}
