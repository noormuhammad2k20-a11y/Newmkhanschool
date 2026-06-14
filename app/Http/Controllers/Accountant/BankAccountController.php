<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;

class BankAccountController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $accounts = BankAccount::where('school_id', $schoolId)->get();
        return view('accountant.bank-accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        BankAccount::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'branch' => $request->branch,
            'initial_balance' => $request->initial_balance,
            'current_balance' => $request->initial_balance,
        ]);

        return back()->with('success', 'Bank account added successfully.');
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
        ]);

        $bankAccount->update($request->only('account_name', 'account_number', 'bank_name', 'branch'));

        return back()->with('success', 'Bank account updated successfully.');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return back()->with('success', 'Bank account removed successfully.');
    }
}
