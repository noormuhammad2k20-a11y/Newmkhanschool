<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\FeePaymentTransaction;

class AccountantFeeController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        $fees = Fee::with(['student.currentClass', 'student.currentSection', 'category'])
            ->whereHas('student', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->latest()
            ->paginate(15);
            
        return view('accountant.fees.index', compact('fees'));
    }

    public function collectPayment(Request $request, Fee $fee)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'gateway' => 'required|string',
        ]);

        $transaction = FeePaymentTransaction::create([
            'fee_id' => $fee->id,
            'student_id' => $fee->student_id,
            'gateway' => $request->gateway,
            'transaction_ref' => 'REF-' . strtoupper(uniqid()),
            'amount' => $request->amount,
            'status' => 'Success',
            'paid_at' => now()
        ]);

        $fee->paid_amount += $request->amount;
        if ($fee->paid_amount >= $fee->amount) {
            $fee->status = 'Paid';
        } else {
            $fee->status = 'Partial';
        }
        $fee->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment collected successfully. Ref: ' . $transaction->transaction_ref
            ]);
        }

        return back()->with('success', 'Payment collected successfully. Ref: ' . $transaction->transaction_ref);
    }

    public function printReceipt(Fee $fee)
    {
        $fee->load(['student.currentClass', 'student.currentSection', 'payments']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('accountant.fees.receipt_pdf', compact('fee'));
        return $pdf->stream('Receipt-' . $fee->challan_no . '.pdf');
    }
    public function generateChallans(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        // Get all active students for this school
        $students = \App\Models\Student::where('school_id', $schoolId)
            ->where('status', 'Active')
            ->get();
            
        $count = 0;
        
        foreach ($students as $student) {
            // Get all fee structures assigned to this student's class
            $structures = \App\Models\FeeStructure::with('category')
                ->where(function($q) use ($schoolId) {
                    $q->where('school_id', $schoolId)->orWhereNull('school_id');
                })
                ->where('class_id', $student->current_class_id)
                ->get();
                
            foreach ($structures as $structure) {
                // Prevent duplicate generation for the same category in the same month
                $exists = Fee::where('student_id', $student->id)
                    ->where('fee_category_id', $structure->fee_category_id)
                    ->whereMonth('due_date', now()->month)
                    ->whereYear('due_date', now()->year)
                    ->exists();
                    
                if (!$exists) {
                    Fee::create([
                        'student_id' => $student->id,
                        'fee_category_id' => $structure->fee_category_id,
                        'fee_category' => $structure->category->name ?? 'General',
                        'amount' => $structure->amount,
                        'discount' => 0,
                        'fine' => 0,
                        'paid_amount' => 0,
                        'due_date' => now()->addDays(10), // Default due date
                        'status' => 'Unpaid',
                        'challan_no' => 'CHN-' . strtoupper(uniqid())
                    ]);
                    $count++;
                }
            }
        }
        
        if ($count > 0) {
            return back()->with('success', "Successfully generated $count new fee challans for this month.");
        }
        
        return back()->with('success', 'All fee challans for this month have already been generated.');
    }
}
