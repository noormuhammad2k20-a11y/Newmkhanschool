<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashBookController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $month = $request->input('month', now()->format('Y-m'));

        $entries = \App\Models\LedgerEntry::with('bankAccount')
            ->where('school_id', $schoolId)
            ->where('date', 'like', $month . '%')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);
            
        $bankAccounts = \App\Models\BankAccount::where('school_id', $schoolId)->get();

        $totalDebit = \App\Models\LedgerEntry::where('school_id', $schoolId)
            ->where('date', 'like', $month . '%')
            ->where('type', 'Debit')
            ->sum('amount');
            
        $totalCredit = \App\Models\LedgerEntry::where('school_id', $schoolId)
            ->where('date', 'like', $month . '%')
            ->where('type', 'Credit')
            ->sum('amount');

        return view('accountant.cash-book.index', compact('entries', 'month', 'bankAccounts', 'totalDebit', 'totalCredit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Credit,Debit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'bank_account_id' => 'nullable|exists:bank_accounts,id',
        ]);

        $schoolId = auth()->user()->school_id ?? 1;

        \App\Models\LedgerEntry::create([
            'school_id' => $schoolId,
            'date' => $request->date,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'bank_account_id' => $request->bank_account_id,
        ]);

        if ($request->bank_account_id) {
            $bankAccount = \App\Models\BankAccount::find($request->bank_account_id);
            if ($request->type == 'Credit') {
                $bankAccount->current_balance += $request->amount;
            } else {
                $bankAccount->current_balance -= $request->amount;
            }
            $bankAccount->save();
        }

        return back()->with('success', 'Ledger entry added successfully.');
    }
}
