<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\FeePayment;

class FeeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $fees = Fee::with('payments')
            ->where('student_id', $student->id)
            ->orderByDesc('due_date')
            ->paginate(15);

        $totals = [
            'total'   => Fee::where('student_id', $student->id)->sum('amount'),
            'paid'    => Fee::where('student_id', $student->id)->sum('paid_amount'),
            'pending' => Fee::where('student_id', $student->id)->whereIn('status',['Pending','Overdue','Partial'])->get()->sum(function($fee) {
                return $fee->amount - $fee->paid_amount - $fee->discount + $fee->fine;
            }),
        ];

        return view('student.fees', compact('fees','totals','student'));
    }
}
