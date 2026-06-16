<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountantDashboardController extends Controller
{
    public function index()
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $thisMonth = \Carbon\Carbon::now()->format('Y-m');

        $totalCollectionToday = DB::table('fee_payment_transactions')
            ->whereDate('paid_at', $today)
            ->where('status', 'Success')
            ->sum('amount');

        $pendingFees = \App\Models\Fee::whereIn('status', ['Pending', 'Partial'])
            ->selectRaw('SUM(amount - paid_amount - discount + fine) as total_pending')
            ->value('total_pending') ?? 0;

        $expensesThisMonth = \App\Models\Expense::where('date', 'like', $thisMonth . '%')
            ->sum('amount');

        $totalCredit = \App\Models\LedgerEntry::where('type', 'Credit')->sum('amount');
        $totalDebit = \App\Models\LedgerEntry::where('type', 'Debit')->sum('amount');
        $cashInHand = $totalCredit - $totalDebit;

        $stats = [
            'total_collection_today' => $totalCollectionToday,
            'pending_fees' => $pendingFees,
            'expenses_this_month' => $expensesThisMonth,
            'cash_in_hand' => $cashInHand,
        ];

        // Chart Data: Last 6 months
        $months = [];
        $incomeData = [];
        $expenseData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = \Carbon\Carbon::now()->subMonths($i);
            $monthStr = $monthDate->format('Y-m');
            $months[] = $monthDate->format('M');
            
            $income = DB::table('fee_payment_transactions')
                ->where('status', 'Success')
                ->where('paid_at', 'like', $monthStr . '%')
                ->sum('amount');
                
            $expense = \App\Models\Expense::where('date', 'like', $monthStr . '%')->sum('amount');
            
            $incomeData[] = $income;
            $expenseData[] = $expense;
        }

        $chartData = [
            'labels' => $months,
            'income' => $incomeData,
            'expenses' => $expenseData
        ];

        $recentTransactions = \App\Models\LedgerEntry::orderByDesc('date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('accountant.dashboard', compact('stats', 'recentTransactions', 'chartData'));
    }
}
