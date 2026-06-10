@extends('layouts.app')

@section('title', 'Child Fees')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Fee Status</h2>
                <p class="text-body-md font-body-md text-secondary mt-1">Viewing fees for {{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
        <a href="{{ route('parent.dashboard') }}" class="bg-surface border border-outline-variant text-on-surface px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-lg">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm flex items-center gap-4 border-l-4 border-l-primary/30">
            <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                <span class="material-symbols-outlined">receipt_long</span>
            </div>
            <div>
                <p class="font-label-md text-label-md text-on-surface-variant mb-1">Total Billed</p>
                <p class="font-headline-md text-headline-md font-bold text-on-surface">₨ {{ isset($fees) ? number_format($fees->sum('amount'), 2) : '0.00' }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm flex items-center gap-4 border-l-4 border-l-emerald-500/30">
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700">
                <span class="material-symbols-outlined">payments</span>
            </div>
            <div>
                <p class="font-label-md text-label-md text-on-surface-variant mb-1">Total Paid</p>
                <p class="font-headline-md text-headline-md font-bold text-on-surface">₨ {{ isset($fees) ? number_format($fees->sum('paid_amount'), 2) : '0.00' }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm flex items-center gap-4 border-l-4 border-l-red-500/30">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                <span class="material-symbols-outlined">money_off</span>
            </div>
            <div>
                <p class="font-label-md text-label-md text-on-surface-variant mb-1">Total Due</p>
                <p class="font-headline-md text-headline-md font-bold text-on-surface">₨ {{ isset($fees) ? number_format($fees->sum(function($f) { return max(0, $f->amount - $f->paid_amount + $f->fine - $f->discount); }), 2) : '0.00' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm flex-1 flex flex-col mb-lg">
    @if(isset($fees) && count($fees) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface border-b border-outline-variant">
                    <tr>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Challan No</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Category</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Amount</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Due Date</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($fees as $fee)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-3 px-4 text-body-md font-body-md font-medium text-on-surface">#{{ $fee->challan_no }}</td>
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface-variant">{{ $fee->fee_category }}</td>
                        <td class="py-3 px-4 text-body-md font-body-md font-semibold text-on-surface">₨ {{ number_format($fee->amount, 2) }}</td>
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}</td>
                        <td class="py-3 px-4">
                            @if($fee->status === 'Paid')
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">Paid</span>
                            @elseif($fee->status === 'Overdue')
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">Overdue</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-xl text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-low mb-4 text-secondary">
                <span class="material-symbols-outlined text-3xl">receipt_long</span>
            </div>
            <h3 class="text-headline-md font-headline-md text-on-surface">No Fee Records</h3>
            <p class="text-body-md font-body-md text-secondary mt-1">There are no fee records available for this student.</p>
        </div>
    @endif
    </div>
    </div>
</main>
@endsection
