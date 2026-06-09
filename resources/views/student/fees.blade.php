@extends('layouts.app')

@section('title', 'My Fees')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header & Action -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Fee Status</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Track your fee payments and due amounts</p>
            </div>
            <div>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-high border border-outline-variant text-on-surface rounded-xl font-bold hover:bg-surface-container-highest transition-colors">
                    <span class="material-symbols-outlined text-[18px]">print</span> Print Statement
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex items-center gap-4 hover:border-primary transition-colors">
                <div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                </div>
                <div>
                    <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Total Billed</p>
                    <p class="text-headline-md font-headline-md text-on-surface mt-1">Rs {{ isset($fees) ? number_format($fees->sum('amount'), 2) : 0 }}</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex items-center gap-4 hover:border-[#10b981] transition-colors">
                <div class="w-12 h-12 rounded-xl bg-[#ecfdf5] flex items-center justify-center text-[#10b981]">
                    <span class="material-symbols-outlined text-[24px]">payments</span>
                </div>
                <div>
                    <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Total Paid</p>
                    <p class="text-headline-md font-headline-md text-on-surface mt-1">Rs {{ isset($fees) ? number_format($fees->sum('paid_amount'), 2) : 0 }}</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest border-2 border-error-container rounded-xl p-6 flex items-center gap-4 relative overflow-hidden group">
                <div class="w-12 h-12 rounded-xl bg-error-container flex items-center justify-center text-error relative z-10">
                    <span class="material-symbols-outlined text-[24px]">money_off</span>
                </div>
                <div class="relative z-10">
                    <p class="text-label-sm font-label-sm text-error uppercase tracking-wider font-bold">Outstanding Balance</p>
                    <p class="text-headline-md font-headline-md text-on-surface mt-1 font-black">Rs {{ isset($fees) ? number_format($fees->sum(function($f) { return max(0, $f->amount - $f->paid_amount + $f->fine - $f->discount); }), 2) : 0 }}</p>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-error-container rounded-full opacity-30 group-hover:scale-150 transition-transform duration-500 z-0"></div>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="p-4 border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <h3 class="text-headline-sm font-bold text-on-surface">Payment History & Breakdowns</h3>
            </div>
            @if(isset($fees) && count($fees) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-body-md text-on-surface">
                        <thead class="bg-surface-bright text-on-surface-variant font-label-md text-label-md border-b border-outline-variant">
                            <tr>
                                <th class="px-6 py-4 uppercase tracking-wider">Challan No</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Fine / Discount</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach($fees as $fee)
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-6 py-4 font-bold text-on-surface">
                                    #{{ $fee->challan_no }}
                                    <span class="block text-[11px] text-secondary font-normal mt-0.5">ID: {{ $fee->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-on-surface">{{ $fee->fee_category }}</span>
                                    @if($fee->remarks)
                                        <span class="block text-[11px] text-secondary mt-0.5">{{ Str::limit($fee->remarks, 30) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-black text-on-surface">Rs {{ number_format($fee->amount, 2) }}</td>
                                <td class="px-6 py-4 text-secondary text-sm">
                                    @if($fee->fine > 0) <span class="text-error block">+ Rs {{ number_format($fee->fine, 2) }}</span> @endif
                                    @if($fee->discount > 0) <span class="text-[#10b981] block">- Rs {{ number_format($fee->discount, 2) }}</span> @endif
                                    @if($fee->fine == 0 && $fee->discount == 0) - @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-on-surface">{{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}</span>
                                    @if($fee->status !== 'Paid' && \Carbon\Carbon::parse($fee->due_date)->isPast())
                                        <span class="block text-[11px] text-error font-bold mt-0.5">Past Due</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($fee->status === 'Paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-[#ecfdf5] text-[#10b981] border border-[#a7f3d0]">Paid</span>
                                        <span class="block text-[10px] text-secondary mt-1">on {{ $fee->paid_date ? \Carbon\Carbon::parse($fee->paid_date)->format('M d') : 'N/A' }}</span>
                                    @elseif($fee->status === 'Overdue' || (\Carbon\Carbon::parse($fee->due_date)->isPast() && $fee->status !== 'Paid'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-error-container text-error border border-error-container">Overdue</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-[#fffbeb] text-[#d97706] border border-[#fde68a]">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($fee->status === 'Paid')
                                        <a href="#" onclick="alert('Receipt PDF Generation coming soon!')" class="inline-flex items-center gap-1.5 text-primary hover:bg-primary-fixed p-2 rounded-lg transition-colors text-sm font-bold">
                                            <span class="material-symbols-outlined text-[18px]">download</span> Receipt
                                        </a>
                                    @else
                                        <a href="#" class="inline-flex items-center gap-1.5 bg-primary text-on-primary px-4 py-1.5 rounded-lg transition-colors text-sm font-bold shadow-sm hover:shadow">
                                            Pay Now
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-secondary border border-outline-variant border-dashed rounded-xl m-4">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">receipt_long</span>
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-1">No Fee Records</h3>
                    <p class="text-body-lg font-body-lg">There are no fee records available for your account.</p>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
