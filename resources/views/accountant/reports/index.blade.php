@extends('layouts.app')

@section('title', 'Financial Reports Hub')

@section('content')
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
                            <span class="text-on-surface">Reports</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Financial Reports Hub</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Generate and export critical financial data</p>
                </div>
                <button class="btn-primary shadow-sm flex items-center gap-2" onclick="alert('Export All feature coming soon')">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                    Export Master Report
                </button>
            </div>
        </div>

        <!-- Master Container -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm">
            <div class="mb-8 pb-4 border-b border-outline-variant flex justify-between items-center">
                <div>
                    <h3 class="text-title-lg font-bold text-on-surface">Available Reports</h3>
                    <p class="text-body-md text-secondary mt-1">Select a module to view detailed analytics and printable summaries.</p>
                </div>
            </div>

            <!-- Reports Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Fee Collection Report -->
                <div class="bg-surface border border-outline-variant rounded-2xl p-6 flex flex-col hover:border-primary/50 hover:bg-primary/5 hover:shadow-md transition-all cursor-pointer group" onclick="alert('Detailed report coming soon')">
                    <div class="w-14 h-14 bg-surface-container-low border border-outline-variant text-primary rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-on-primary transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
                    </div>
                    <h4 class="text-title-md font-bold text-on-surface mb-2">Fee Collection</h4>
                    <p class="text-body-md text-secondary mb-8 flex-1 leading-relaxed">View detailed breakdown of collected fees across all classes, categories, and payment gateways.</p>
                    <div class="text-primary font-semibold flex items-center text-label-lg mt-auto">
                        Generate Report <span class="material-symbols-outlined text-[18px] ml-1 transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </div>
                </div>

                <!-- Income Statement -->
                <div class="bg-surface border border-outline-variant rounded-2xl p-6 flex flex-col hover:border-emerald-500/50 hover:bg-emerald-50 hover:shadow-md transition-all cursor-pointer group" onclick="alert('Detailed report coming soon')">
                    <div class="w-14 h-14 bg-surface-container-low border border-outline-variant text-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-3xl">trending_up</span>
                    </div>
                    <h4 class="text-title-md font-bold text-on-surface mb-2">Income Statement</h4>
                    <p class="text-body-md text-secondary mb-8 flex-1 leading-relaxed">Analyze net profit & loss by comparing all revenue streams against total recorded expenses.</p>
                    <div class="text-emerald-600 font-semibold flex items-center text-label-lg mt-auto">
                        Generate Report <span class="material-symbols-outlined text-[18px] ml-1 transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </div>
                </div>

                <!-- Expense Breakdown -->
                <div class="bg-surface border border-outline-variant rounded-2xl p-6 flex flex-col hover:border-error/50 hover:bg-red-50 hover:shadow-md transition-all cursor-pointer group" onclick="alert('Detailed report coming soon')">
                    <div class="w-14 h-14 bg-surface-container-low border border-outline-variant text-error rounded-xl flex items-center justify-center mb-6 group-hover:bg-error group-hover:text-white transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-3xl">receipt_long</span>
                    </div>
                    <h4 class="text-title-md font-bold text-on-surface mb-2">Expense Breakdown</h4>
                    <p class="text-body-md text-secondary mb-8 flex-1 leading-relaxed">Track operational expenditures categorized by type (utilities, supplies, maintenance, etc).</p>
                    <div class="text-error font-semibold flex items-center text-label-lg mt-auto">
                        Generate Report <span class="material-symbols-outlined text-[18px] ml-1 transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </div>
                </div>

                <!-- Payroll Summary -->
                <div class="bg-surface border border-outline-variant rounded-2xl p-6 flex flex-col hover:border-blue-500/50 hover:bg-blue-50 hover:shadow-md transition-all cursor-pointer group" onclick="alert('Detailed report coming soon')">
                    <div class="w-14 h-14 bg-surface-container-low border border-outline-variant text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-3xl">payments</span>
                    </div>
                    <h4 class="text-title-md font-bold text-on-surface mb-2">Payroll Summary</h4>
                    <p class="text-body-md text-secondary mb-8 flex-1 leading-relaxed">Comprehensive list of staff salaries, deductions, taxes withheld, and net payouts for any given month.</p>
                    <div class="text-blue-600 font-semibold flex items-center text-label-lg mt-auto">
                        Generate Report <span class="material-symbols-outlined text-[18px] ml-1 transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </div>
                </div>

                <!-- Bank Statement / Cash Book -->
                <div class="bg-surface border border-outline-variant rounded-2xl p-6 flex flex-col hover:border-purple-500/50 hover:bg-purple-50 hover:shadow-md transition-all cursor-pointer group" onclick="alert('Detailed report coming soon')">
                    <div class="w-14 h-14 bg-surface-container-low border border-outline-variant text-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-3xl">account_balance</span>
                    </div>
                    <h4 class="text-title-md font-bold text-on-surface mb-2">Bank & Cash Book</h4>
                    <p class="text-body-md text-secondary mb-8 flex-1 leading-relaxed">Complete ledger tracing every incoming and outgoing transaction for complete audit compliance.</p>
                    <div class="text-purple-600 font-semibold flex items-center text-label-lg mt-auto">
                        Generate Report <span class="material-symbols-outlined text-[18px] ml-1 transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection
