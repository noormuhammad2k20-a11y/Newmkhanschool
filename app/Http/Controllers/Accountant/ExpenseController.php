<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseCategory;

class ExpenseController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        $expenses = Expense::with(['category', 'recorder'])
            ->where('school_id', $schoolId)
            ->latest()
            ->paginate(15);
            
        $categories = ExpenseCategory::where('school_id', $schoolId)->get();
            
        return view('accountant.expenses.index', compact('expenses', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'status' => 'required|in:Pending,Paid',
            'payment_mode' => 'required|string|in:Cash,Bank Transfer,Cheque,Card,Online',
            'paid_to' => 'required|string|max:255',
            'voucher_no' => 'nullable|string|max:50',
            'receipt' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('expenses/receipts', 'public');
        }

        $expense = Expense::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'expense_category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'status' => $request->status,
            'payment_mode' => $request->payment_mode,
            'paid_to' => $request->paid_to,
            'voucher_no' => $request->voucher_no,
            'receipt_path' => $receiptPath,
            'recorded_by' => auth()->id() ?? 1,
        ]);

        if ($expense->status === 'Paid') {
            \App\Models\LedgerEntry::create([
                'school_id' => $expense->school_id,
                'date' => $expense->date,
                'description' => 'Expense: ' . ($expense->category->name ?? 'General') . ($expense->description ? ' - ' . $expense->description : ''),
                'type' => 'Debit',
                'amount' => $expense->amount,
                'reference_id' => $expense->id,
                'reference_type' => Expense::class,
            ]);
        }

        return back()->with('success', 'Expense recorded successfully.');
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'status' => 'required|in:Pending,Paid',
            'payment_mode' => 'required|string|in:Cash,Bank Transfer,Cheque,Card,Online',
            'paid_to' => 'required|string|max:255',
            'voucher_no' => 'nullable|string|max:50',
            'receipt' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            $expense->receipt_path = $request->file('receipt')->store('expenses/receipts', 'public');
        }

        $oldStatus = $expense->status;

        $expense->update([
            'expense_category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'status' => $request->status,
            'payment_mode' => $request->payment_mode,
            'paid_to' => $request->paid_to,
            'voucher_no' => $request->voucher_no,
        ]);

        if ($oldStatus !== 'Paid' && $expense->status === 'Paid') {
            \App\Models\LedgerEntry::create([
                'school_id' => $expense->school_id,
                'date' => $expense->date,
                'description' => 'Expense Paid: ' . ($expense->category->name ?? 'General') . ($expense->description ? ' - ' . $expense->description : ''),
                'type' => 'Debit',
                'amount' => $expense->amount,
                'reference_id' => $expense->id,
                'reference_type' => Expense::class,
            ]);
        }

        return back()->with('success', 'Expense updated successfully.');
    }

    public function updateStatus(Request $request, Expense $expense)
    {
        $request->validate([
            'status' => 'required|in:Pending,Paid',
        ]);

        if ($expense->status !== 'Paid' && $request->status === 'Paid') {
            \App\Models\LedgerEntry::create([
                'school_id' => $expense->school_id,
                'date' => now()->toDateString(),
                'description' => 'Expense Paid: ' . ($expense->category->name ?? 'General') . ($expense->description ? ' - ' . $expense->description : ''),
                'type' => 'Debit',
                'amount' => $expense->amount,
                'reference_id' => $expense->id,
                'reference_type' => Expense::class,
            ]);
        }

        $expense->update(['status' => $request->status]);

        return back()->with('success', 'Expense status updated successfully.');
    }
}
