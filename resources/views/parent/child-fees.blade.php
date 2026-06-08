@extends('layouts.app')

@section('title', 'Child Fees')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fee Status</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Viewing fees for {{ $student->first_name }} {{ $student->last_name }}</p>
    </div>
    <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
        <span class="material-symbols-rounded text-[18px] mr-1">arrow_back</span>
        Back to Dashboard
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
            <span class="material-symbols-rounded">receipt_long</span>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Billed</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rs {{ isset($fees) ? $fees->sum('amount') : 0 }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
            <span class="material-symbols-rounded">payments</span>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Paid</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rs {{ isset($fees) ? $fees->sum('paid_amount') : 0 }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
            <span class="material-symbols-rounded">money_off</span>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Due</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rs {{ isset($fees) ? $fees->sum(function($f) { return max(0, $f->amount - $f->paid_amount + $f->fine - $f->discount); }) : 0 }}</p>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if(isset($fees) && count($fees) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 font-medium border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Challan No</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($fees as $fee)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $fee->challan_no }}</td>
                        <td class="px-6 py-4">{{ $fee->fee_category }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">Rs {{ number_format($fee->amount, 2) }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @if($fee->status === 'Paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Paid</span>
                            @elseif($fee->status === 'Overdue')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Overdue</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">receipt_long</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Fee Records</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">There are no fee records available for this student.</p>
        </div>
    @endif
</div>
@endsection
