<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\LedgerEntry;

class ExpenseObserver
{
    public function created(Expense $expense): void
    {
        if ($expense->status === 'Paid') {
            LedgerEntry::create([
                'school_id' => $expense->school_id,
                'date' => $expense->date ?? now(),
                'description' => 'Expense: ' . ($expense->category->name ?? 'General') . ' - ' . $expense->description,
                'type' => 'Expense',
                'amount' => $expense->amount,
                'reference_id' => $expense->id,
                'reference_type' => Expense::class,
            ]);
        }
    }

    public function updated(Expense $expense): void
    {
        if ($expense->wasChanged('status') && $expense->status === 'Paid') {
            $exists = LedgerEntry::where('reference_id', $expense->id)
                ->where('reference_type', Expense::class)
                ->exists();
                
            if (!$exists) {
                LedgerEntry::create([
                    'school_id' => $expense->school_id,
                    'date' => $expense->date ?? now(),
                    'description' => 'Expense: ' . ($expense->category->name ?? 'General') . ' - ' . $expense->description,
                    'type' => 'Expense',
                    'amount' => $expense->amount,
                    'reference_id' => $expense->id,
                    'reference_type' => Expense::class,
                ]);
            }
        }
    }

    public function deleted(Expense $expense): void
    {
        //
    }

    public function restored(Expense $expense): void
    {
        //
    }

    public function forceDeleted(Expense $expense): void
    {
        //
    }
}
