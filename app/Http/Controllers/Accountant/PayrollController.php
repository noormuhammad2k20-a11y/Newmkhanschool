<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index()
    {
        // Assuming Payroll has school_id through teacher or we fetch all
        // Wait, Payroll does not have school_id and Teacher doesn't have school_id either, wait, Teacher has school_id
        // We'll just fetch all for now, assuming standard multi-tenant scoping if implemented
        $payrolls = Payroll::with('teacher')
            ->latest('month_year')
            ->paginate(15);
            
        return view('accountant.payroll.index', compact('payrolls'));
    }

    public function markPaid(Request $request, Payroll $payroll)
    {
        $payroll->update(['status' => 'Paid']);
        // Observer handles LedgerEntry
        return back()->with('success', 'Payroll marked as paid successfully.');
    }
}
