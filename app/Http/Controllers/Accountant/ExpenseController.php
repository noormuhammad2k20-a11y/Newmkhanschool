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
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Paid',
        ]);

        $expense = Expense::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'expense_category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'status' => $request->status,
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
