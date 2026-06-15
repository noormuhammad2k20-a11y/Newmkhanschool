<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $month = $request->input('month', now()->format('Y-m'));
        
        $payrolls = Payroll::with('teacher')
            ->where('month_year', $month)
            ->latest()
            ->paginate(15);
            
        return view('accountant.payroll.index', compact('payrolls', 'month'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month_year' => 'required|date_format:Y-m',
        ]);

        $monthYear = $request->month_year;
        $schoolId = auth()->user()->school_id ?? 1;

        $teachers = \App\Models\Teacher::where('school_id', $schoolId)->where('status', 'Active')->get();
        $count = 0;

        foreach ($teachers as $teacher) {
            $exists = Payroll::where('teacher_id', $teacher->id)->where('month_year', $monthYear)->exists();
            if (!$exists) {
                // Assuming basic_salary column exists on Teacher, else default to 0
                $basicPay = $teacher->basic_salary ?? 0;
                Payroll::create([
                    'teacher_id' => $teacher->id,
                    'emp_id' => $teacher->employee_id ?? 'EMP-' . $teacher->id,
                    'name' => $teacher->full_name,
                    'role' => 'Teacher',
                    'basic_pay' => $basicPay,
                    'allowances' => 0,
                    'deductions' => 0,
                    'net_salary' => $basicPay,
                    'status' => 'Pending',
                    'month_year' => $monthYear,
                ]);
                $count++;
            }
        }

        return back()->with('success', "Successfully generated $count payroll records for $monthYear.");
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'basic_pay' => 'required|numeric',
            'allowances' => 'required|numeric',
            'deductions' => 'required|numeric',
        ]);

        $net = $request->basic_pay + $request->allowances - $request->deductions;
        $payroll->update([
            'basic_pay' => $request->basic_pay,
            'allowances' => $request->allowances,
            'deductions' => $request->deductions,
            'net_salary' => $net,
        ]);

        return back()->with('success', 'Payroll updated successfully.');
    }

    public function markPaid(Request $request, Payroll $payroll)
    {
        $payroll->update(['status' => 'Paid']);
        return back()->with('success', 'Payroll marked as paid successfully.');
    }

    public function slip(Payroll $payroll)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('accountant.payroll.slip_pdf', compact('payroll'));
        return $pdf->stream('SalarySlip-' . $payroll->emp_id . '-' . $payroll->month_year . '.pdf');
    }
}
