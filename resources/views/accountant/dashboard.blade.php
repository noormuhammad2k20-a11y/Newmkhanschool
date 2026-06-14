@extends('layouts.app')

@section('title', 'Accountant Dashboard')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Breadcrumb & Page Header -->
        <div class="flex flex-col gap-2">
            <nav class="flex text-label-md text-secondary" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('accountant.dashboard') }}" class="inline-flex items-center hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[16px] mr-1">home</span>
                            Accountant Portal
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Dashboard</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Financial Overview</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">School Financial Operations Summary</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('accountant.fees.index') }}" class="btn-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        Collect Fee
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Stat Card 1 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Today's Collection</h3>
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">0.00</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-emerald-600">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    <span>Collected via fees today</span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending Dues</h3>
                    <div class="w-10 h-10 rounded-lg bg-red-50 text-error border border-red-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">warning</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">0.00</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-error">
                    <span class="material-symbols-outlined text-[14px]">priority_high</span>
                    <span>Unpaid student fees</span>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Expenses (This Month)</h3>
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">0.00</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                    <span>Includes payroll & operations</span>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Cash in Hand</h3>
                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">0.00</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                    <span>Available liquidity</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-md">
            <!-- Chart 1 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col shadow-sm">
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-outline-variant">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Income vs Expenses</h3>
                    <button class="text-secondary hover:text-primary"><span class="material-symbols-outlined">more_horiz</span></button>
                </div>
                <div class="relative h-64 w-full flex items-center justify-center text-secondary">
                    <p class="flex flex-col items-center gap-2">
                        <span class="material-symbols-outlined text-4xl text-outline">bar_chart</span>
                        Chart Data Unavailable
                    </p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col shadow-sm">
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-outline-variant">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Quick Actions</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('accountant.fees.index') }}" class="group flex flex-col items-center justify-center p-6 rounded-xl border border-outline-variant bg-surface hover:bg-emerald-50 hover:border-emerald-200 transition-all">
                        <span class="material-symbols-outlined text-3xl text-secondary group-hover:text-emerald-600 mb-2 transition-colors">payments</span>
                        <span class="text-label-md font-semibold text-on-surface group-hover:text-emerald-800">Collect Fee</span>
                    </a>
                    <a href="{{ route('accountant.expenses.index') }}" class="group flex flex-col items-center justify-center p-6 rounded-xl border border-outline-variant bg-surface hover:bg-amber-50 hover:border-amber-200 transition-all">
                        <span class="material-symbols-outlined text-3xl text-secondary group-hover:text-amber-600 mb-2 transition-colors">receipt_long</span>
                        <span class="text-label-md font-semibold text-on-surface group-hover:text-amber-800">Record Expense</span>
                    </a>
                    <a href="{{ route('accountant.payroll.index') }}" class="group flex flex-col items-center justify-center p-6 rounded-xl border border-outline-variant bg-surface hover:bg-blue-50 hover:border-blue-200 transition-all">
                        <span class="material-symbols-outlined text-3xl text-secondary group-hover:text-blue-600 mb-2 transition-colors">account_balance_wallet</span>
                        <span class="text-label-md font-semibold text-on-surface group-hover:text-blue-800">Process Payroll</span>
                    </a>
                    <a href="{{ route('accountant.reports.index') }}" class="group flex flex-col items-center justify-center p-6 rounded-xl border border-outline-variant bg-surface hover:bg-primary/10 hover:border-primary/30 transition-all">
                        <span class="material-symbols-outlined text-3xl text-secondary group-hover:text-primary mb-2 transition-colors">analytics</span>
                        <span class="text-label-md font-semibold text-on-surface group-hover:text-primary-container">Financial Reports</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-sm">
            <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                <h3 class="text-headline-md font-headline-md text-on-surface">Recent Transactions (Ledger)</h3>
                <a href="{{ route('accountant.bank-accounts.index') }}" class="text-label-md font-semibold text-primary hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-xs font-semibold text-secondary uppercase tracking-wider border-b border-outline-variant">
                            <th class="py-4 px-6">Date</th>
                            <th class="py-4 px-6">Description</th>
                            <th class="py-4 px-6">Type</th>
                            <th class="py-4 px-6 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                        <tr><td colspan="4" class="py-8 text-center text-secondary">
                            <span class="material-symbols-outlined text-3xl mb-2 text-outline">history</span>
                            <p>No recent transactions</p>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
