<?php

namespace App\Observers;

use App\Models\FeePaymentTransaction;
use App\Models\LedgerEntry;

class FeePaymentTransactionObserver
{
    public function created(FeePaymentTransaction $feePaymentTransaction): void
    {
        if (in_array($feePaymentTransaction->status, ['Successful', 'Completed', 'success', 'paid', 'completed'])) {
            $student = $feePaymentTransaction->student;
            $fee = $feePaymentTransaction->fee;
            
            LedgerEntry::create([
                'school_id' => $student->school_id ?? 1,
                'date' => $feePaymentTransaction->paid_at ?? now(),
                'description' => 'Fee Collection: ' . ($fee->feeCategory->name ?? $fee->fee_category ?? 'General'),
                'type' => 'Income',
                'amount' => $feePaymentTransaction->amount,
                'reference_id' => $feePaymentTransaction->id,
                'reference_type' => FeePaymentTransaction::class,
            ]);
        }
    }

    public function updated(FeePaymentTransaction $feePaymentTransaction): void
    {
        if ($feePaymentTransaction->wasChanged('status') && in_array($feePaymentTransaction->status, ['Successful', 'Completed', 'success', 'paid', 'completed'])) {
            // Ensure we don't duplicate ledger entries if already exists
            $exists = LedgerEntry::where('reference_id', $feePaymentTransaction->id)
                ->where('reference_type', FeePaymentTransaction::class)
                ->exists();
                
            if (!$exists) {
                $student = $feePaymentTransaction->student;
                $fee = $feePaymentTransaction->fee;
                
                LedgerEntry::create([
                    'school_id' => $student->school_id ?? 1,
                    'date' => $feePaymentTransaction->paid_at ?? now(),
                    'description' => 'Fee Collection: ' . ($fee->feeCategory->name ?? $fee->fee_category ?? 'General'),
                    'type' => 'Income',
                    'amount' => $feePaymentTransaction->amount,
                    'reference_id' => $feePaymentTransaction->id,
                    'reference_type' => FeePaymentTransaction::class,
                ]);
            }
        }
    }

    public function deleted(FeePaymentTransaction $feePaymentTransaction): void
    {
        //
    }

    public function restored(FeePaymentTransaction $feePaymentTransaction): void
    {
        //
    }

    public function forceDeleted(FeePaymentTransaction $feePaymentTransaction): void
    {
        //
    }
}
