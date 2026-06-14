<?php

namespace App\Observers;

use App\Models\Payroll;
use App\Models\LedgerEntry;

class PayrollObserver
{
    public function created(Payroll $payroll): void
    {
        if ($payroll->status === 'Paid') {
            LedgerEntry::create([
                'school_id' => auth()->user()->school_id ?? 1,
                'date' => now(),
                'description' => 'Payroll: ' . $payroll->name . ' (' . $payroll->month_year . ')',
                'type' => 'Expense',
                'amount' => $payroll->net_salary,
                'reference_id' => $payroll->id,
                'reference_type' => Payroll::class,
            ]);
        }
    }

    public function updated(Payroll $payroll): void
    {
        if ($payroll->wasChanged('status') && $payroll->status === 'Paid') {
            $exists = LedgerEntry::where('reference_id', $payroll->id)
                ->where('reference_type', Payroll::class)
                ->exists();
                
            if (!$exists) {
                LedgerEntry::create([
                    'school_id' => auth()->user()->school_id ?? 1,
                    'date' => now(),
                    'description' => 'Payroll: ' . $payroll->name . ' (' . $payroll->month_year . ')',
                    'type' => 'Expense',
                    'amount' => $payroll->net_salary,
                    'reference_id' => $payroll->id,
                    'reference_type' => Payroll::class,
                ]);
            }
        }
    }

    public function deleted(Payroll $payroll): void
    {
        //
    }

    public function restored(Payroll $payroll): void
    {
        //
    }

    public function forceDeleted(Payroll $payroll): void
    {
        //
    }
}
