<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        // Always join with teachers table — never use hardcoded names
        $payrolls = \App\Models\Payroll::with('teacher.user')
            ->orderByDesc('created_at')
            ->paginate(15);

        $teachers = \App\Models\Teacher::with('user')->get();

        return view('admin.payroll.index', compact('payrolls','teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id'  => 'required|exists:teachers,id',
            'basic_pay'   => 'required|numeric|min:0',
            'allowances'  => 'required|numeric|min:0',
            'deductions'  => 'required|numeric|min:0',
            'month_year'  => 'required|string',
        ]);

        $teacher = \App\Models\Teacher::with('user')->findOrFail($request->teacher_id);

        \App\Models\Payroll::create([
            'teacher_id' => $teacher->id,
            'emp_id'     => $teacher->employee_number,
            'name'       => $teacher->user->name,
            'role'       => 'Teacher',
            'basic_pay'  => $request->basic_pay,
            'allowances' => $request->allowances,
            'deductions' => $request->deductions,
            'net_salary' => $request->basic_pay + $request->allowances - $request->deductions,
            'status'     => 'Pending',
            'month_year' => $request->month_year,
        ]);

        return redirect()->route('admin.payroll')->with('success', 'Payroll entry created.');
    }
}
