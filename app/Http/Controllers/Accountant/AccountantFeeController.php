<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\FeePaymentTransaction;

class AccountantFeeController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        $query = Fee::with(['student.currentClass', 'student.currentSection', 'category'])
            ->whereHas('student', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('current_class_id', $request->class_id);
            });
        }

        if ($request->filled('fee_category_id')) {
            $query->where('fee_category_id', $request->fee_category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('challan_no', 'like', "%{$search}%")
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $fees = $query->latest()->paginate(15);
            
        $classes = \App\Models\SchoolClass::where('school_id', $schoolId)->get();
        $categories = \App\Models\FeeCategory::all();

        return view('accountant.fees.index', compact('fees', 'classes', 'categories'));
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

        \App\Models\LedgerEntry::create([
            'school_id' => $fee->student->school_id ?? 1,
            'date' => now()->toDateString(),
            'description' => 'Fee Collection: ' . $fee->fee_category . ' from ' . $fee->student->full_name,
            'type' => 'Income',
            'amount' => $request->amount,
            'reference_id' => $transaction->id,
            'reference_type' => FeePaymentTransaction::class,
        ]);

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
