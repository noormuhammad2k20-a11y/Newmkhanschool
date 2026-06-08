@extends('layouts.app')

@section('title', 'Staff Payroll Management')

@section('content')
        <main class="flex-1 p-margin-mobile md:p-margin-desktop max-w-[1440px] w-full mx-auto">
            <!-- Page Header & Controls -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-xl gap-md">
                <div>
                    <h2 class="font-headline-xl text-headline-xl text-on-background">Staff Payroll Management</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Overview and processing for October 2024</p>
                </div>
                <div class="flex gap-sm">
                    <button class="px-md py-sm border border-outline-variant rounded-lg font-label-md text-label-md text-on-surface bg-surface hover:bg-surface-container-highest transition-colors flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">download</span> Export Report
                    </button>
                    <button class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:bg-on-primary-fixed-variant transition-colors shadow-sm flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">bolt</span> Generate Payroll
                    </button>
                </div>
            </div>
            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg mb-xl">
                <!-- Monthly Summary Cards (Span 8 cols, inner grid) -->
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-lg">
                    <!-- Total Salaries -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-md">
                            <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Gross Salaries</span>
                            <span class="material-symbols-outlined text-primary bg-primary-fixed-dim bg-opacity-20 p-xs rounded-md">account_balance</span>
                        </div>
                        <div>
                            <div class="font-headline-xl text-headline-xl text-on-background">$1,245,000</div>
                            <div class="font-label-md text-label-md text-secondary mt-xs flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[14px] text-green-600">trending_up</span>
                                +2.4% from last month
                            </div>
                        </div>
                    </div>
                    <!-- Total Deductions -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-md">
                            <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Taxes &amp; Deductions</span>
                            <span class="material-symbols-outlined text-error bg-error-container p-xs rounded-md">receipt_long</span>
                        </div>
                        <div>
                            <div class="font-headline-xl text-headline-xl text-on-background">$210,500</div>
                            <div class="font-label-md text-label-md text-secondary mt-xs flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[14px] text-error">trending_down</span>
                                -0.5% from last month
                            </div>
                        </div>
                    </div>
                    <!-- Net Paid -->
                    <div class="bg-primary text-on-primary rounded-xl p-md flex flex-col justify-between shadow-sm relative overflow-hidden">
                        <!-- Decorative gradient/pattern -->
                        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                        <div class="flex justify-between items-start mb-md relative z-10">
                            <span class="font-label-md text-label-md text-primary-fixed-dim uppercase tracking-wider">Total Net Paid</span>
                            <span class="material-symbols-outlined text-on-primary">price_check</span>
                        </div>
                        <div class="relative z-10">
                            <div class="font-headline-xl text-headline-xl">$1,034,500</div>
                            <div class="font-label-md text-label-md text-primary-fixed-dim mt-xs flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span> 245 Staff Processed
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Payment Status Tracker (Span 4 cols) -->
                <div class="lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
                    <div class="flex justify-between items-center mb-md border-b border-surface-variant pb-xs">
                        <h3 class="font-headline-md text-headline-md text-on-background">Payment Status</h3>
                        <button class="text-primary hover:bg-surface-container-highest p-xs rounded-full transition-colors">
                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                        </button>
                    </div>
                    <div class="space-y-md mt-md">
                        <!-- Status Item: Paid -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-sm">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="font-body-md text-on-surface">Paid (Teachers)</span>
                            </div>
                            <div class="flex items-center gap-md">
                                <span class="font-label-md text-label-md text-on-surface-variant">180</span>
                                <div class="w-16 h-1 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 w-[73%]"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Status Item: Processing -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-sm">
                                <div class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></div>
                                <span class="font-body-md text-on-surface">Processing (Admin)</span>
                            </div>
                            <div class="flex items-center gap-md">
                                <span class="font-label-md text-label-md text-on-surface-variant">45</span>
                                <div class="w-16 h-1 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-500 w-[18%]"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Status Item: Pending -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-sm">
                                <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                                <span class="font-body-md text-on-surface">Pending Approval</span>
                            </div>
                            <div class="flex items-center gap-md">
                                <span class="font-label-md text-label-md text-on-surface-variant">20</span>
                                <div class="w-16 h-1 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-outline-variant w-[9%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Staff Salary Breakdown Table -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                <div class="p-md border-b border-surface-variant bg-surface-bright flex justify-between items-center">
                    <h3 class="font-headline-md text-headline-md text-on-background">Staff Salary Breakdown</h3>
                    <!-- Table Filters/Search -->
                    <div class="flex gap-sm">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-[16px]">search</span>
                            <input class="pl-8 pr-sm py-xs text-body-md border border-outline-variant rounded-md bg-surface w-48 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="Search staff ID..." type="text" />
                        </div>
                        <button class="px-sm py-xs border border-outline-variant rounded-md font-label-md text-label-md bg-surface flex items-center gap-xs hover:bg-surface-container-high">
                            <span class="material-symbols-outlined text-[16px]">filter_list</span> Filter
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant">
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Staff Member</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Role / Dept</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Basic Pay</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Allowances</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Deductions</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Net Salary</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-center">Status</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-on-surface">
                            <!-- Row 1 -->
                            <tr class="border-b border-surface-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-sm px-md flex items-center gap-sm">
                                    <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-xs">ER</div>
                                    <div>
                                        <div class="font-medium text-on-background">Eleanor Rigby</div>
                                        <div class="text-xs text-on-surface-variant">ID: EMP-0014</div>
                                    </div>
                                </td>
                                <td class="py-sm px-md">Senior Math Teacher</td>
                                <td class="py-sm px-md text-right">$4,200</td>
                                <td class="py-sm px-md text-right text-secondary text-sm">
                                    <div title="HRA: $400, TA: $150">$550</div>
                                </td>
                                <td class="py-sm px-md text-right text-error text-sm">
                                    <div title="PF: $210, Tax: $450">-$660</div>
                                </td>
                                <td class="py-sm px-md text-right font-medium">$4,090</td>
                                <td class="py-sm px-md text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Paid</span>
                                </td>
                                <td class="py-sm px-md text-center">
                                    <button class="text-primary hover:bg-primary-fixed-dim hover:bg-opacity-20 p-xs rounded-full transition-colors" title="Download Slip">
                                        <span class="material-symbols-outlined text-[18px]">receipt</span>
                                    </button>
                                </td>
                            </tr>
                            <!-- Row 2 (Zebra) -->
                            <tr class="border-b border-surface-variant bg-surface hover:bg-surface-container-lowest transition-colors">
                                <td class="py-sm px-md flex items-center gap-sm">
                                    <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-xs">JP</div>
                                    <div>
                                        <div class="font-medium text-on-background">Jude Peterson</div>
                                        <div class="text-xs text-on-surface-variant">ID: EMP-0082</div>
                                    </div>
                                </td>
                                <td class="py-sm px-md">Science Dept Head</td>
                                <td class="py-sm px-md text-right">$5,100</td>
                                <td class="py-sm px-md text-right text-secondary text-sm">
                                    <div title="HRA: $500, TA: $200">$700</div>
                                </td>
                                <td class="py-sm px-md text-right text-error text-sm">
                                    <div title="PF: $255, Tax: $600">-$855</div>
                                </td>
                                <td class="py-sm px-md text-right font-medium">$4,945</td>
                                <td class="py-sm px-md text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Processing</span>
                                </td>
                                <td class="py-sm px-md text-center">
                                    <button class="text-primary hover:bg-primary-fixed-dim hover:bg-opacity-20 p-xs rounded-full transition-colors" title="Preview Slip">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                </td>
                            </tr>
                            <!-- Row 3 -->
                            <tr class="border-b border-surface-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-sm px-md flex items-center gap-sm">
                                    <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-xs">MM</div>
                                    <div>
                                        <div class="font-medium text-on-background">Martha Maxwell</div>
                                        <div class="text-xs text-on-surface-variant">ID: EMP-0105</div>
                                    </div>
                                </td>
                                <td class="py-sm px-md">Admin Staff</td>
                                <td class="py-sm px-md text-right">$3,500</td>
                                <td class="py-sm px-md text-right text-secondary text-sm">
                                    <div title="HRA: $300, TA: $100">$400</div>
                                </td>
                                <td class="py-sm px-md text-right text-error text-sm">
                                    <div title="PF: $175, Tax: $250">-$425</div>
                                </td>
                                <td class="py-sm px-md text-right font-medium">$3,475</td>
                                <td class="py-sm px-md text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-surface-variant text-on-surface-variant border border-outline-variant">Pending</span>
                                </td>
                                <td class="py-sm px-md text-center">
                                    <button class="text-primary hover:bg-primary-fixed-dim hover:bg-opacity-20 p-xs rounded-full transition-colors" title="Review">
                                        <span class="material-symbols-outlined text-[18px]">edit_document</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Footer -->
                <div class="p-sm border-t border-surface-variant bg-surface flex justify-between items-center text-sm text-on-surface-variant">
                    <span>Showing 1 to 3 of 245 entries</span>
                    <div class="flex gap-xs">
                        <button class="px-2 py-1 border border-outline-variant rounded hover:bg-surface-container-highest disabled:opacity-50" disabled="">Prev</button>
                        <button class="px-2 py-1 bg-primary text-on-primary rounded">1</button>
                        <button class="px-2 py-1 border border-outline-variant rounded hover:bg-surface-container-highest">2</button>
                        <button class="px-2 py-1 border border-outline-variant rounded hover:bg-surface-container-highest">3</button>
                        <span class="px-1">...</span>
                        <button class="px-2 py-1 border border-outline-variant rounded hover:bg-surface-container-highest">Next</button>
                    </div>
                </div>
            </div>
        </main>
@endsection
