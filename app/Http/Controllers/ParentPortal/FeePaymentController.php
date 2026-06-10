<?php

namespace App\Http\Controllers\ParentPortal;

use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;

class FeePaymentController extends BaseParentController
{
    public function showPaymentForm($student_id, $fee_id)
    {
        abort_unless($this->parentOwnsStudent($student_id), 403);
        $student = Student::findOrFail($student_id);
        $fee = Fee::where('student_id', $student_id)->findOrFail($fee_id);
        
        if ($fee->status === 'Paid') {
            return redirect()->route('parent.child.fees', $student_id)->with('error', 'This fee is already paid.');
        }
        
        return view('parent.fees.payment', compact('student', 'fee'));
    }

    public function processPayment(Request $request, $student_id, $fee_id)
    {
        abort_unless($this->parentOwnsStudent($student_id), 403);
        $fee = Fee::where('student_id', $student_id)->findOrFail($fee_id);
        
        if ($fee->status === 'Paid') {
            return redirect()->route('parent.child.fees', $student_id)->with('error', 'This fee is already paid.');
        }
        
        // Mock payment processing with JazzCash/EasyPaisa
        $fee->update([
            'status' => 'Paid',
            'payment_method' => $request->payment_method ?? 'Online',
            'payment_date' => now(),
            'transaction_id' => 'TXN' . strtoupper(uniqid())
        ]);
        
        return redirect()->route('parent.child.fees.receipt', ['student_id' => $student_id, 'fee_id' => $fee_id])
            ->with('success', 'Payment successful!');
    }

    public function receipt($student_id, $fee_id)
    {
        abort_unless($this->parentOwnsStudent($student_id), 403);
        $student = Student::with(['currentClass', 'currentSection'])->findOrFail($student_id);
        $fee = Fee::where('student_id', $student_id)->findOrFail($fee_id);
        
        return view('parent.fees.receipt', compact('student', 'fee'));
    }
}
