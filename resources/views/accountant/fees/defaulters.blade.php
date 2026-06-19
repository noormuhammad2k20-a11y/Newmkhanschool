@extends('layouts.app')

@section('title', 'Fee Defaulters')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Breadcrumb & Page Header -->
        <div class="flex flex-col gap-2">
            <nav class="flex text-label-md text-secondary" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('accountant.dashboard') }}" class="inline-flex items-center hover:text-primary transition-colors">
                            <span class="material-symbols-rounded text-[16px] mr-1">home</span>
                            Accountant Portal
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-rounded text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Fee Management</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-rounded text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Fee Defaulters</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Fee Defaulters</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Students with overdue payments</p>
                </div>
                <form method="POST" action="{{ route('accountant.defaulters.remind-all') }}">
                    @csrf
                    <button type="submit" class="btn-primary bg-error hover:bg-error-container hover:text-error transition-colors shadow-sm flex items-center gap-2">
                        <span class="material-symbols-rounded text-[20px]">notifications_active</span>
                        Send Reminders to All
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 relative flex items-center gap-3" role="alert">
            <span class="material-symbols-rounded text-emerald-600">check_circle</span>
            <div><span class="font-semibold">Success!</span> {{ session('success') }}</div>
        </div>
        @endif

        <!-- Table Section -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-xs font-semibold text-secondary uppercase tracking-wider border-b border-outline-variant">
                            <th class="py-4 px-6">Student</th>
                            <th class="py-4 px-6">Challan No</th>
                            <th class="py-4 px-6">Due Date</th>
                            <th class="py-4 px-6">Total Amount</th>
                            <th class="py-4 px-6">Paid Amount</th>
                            <th class="py-4 px-6 text-error">Balance Due</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                        @forelse($defaulters as $fee)
                        @php
                            $balance = $fee->amount - $fee->discount + $fee->fine - $fee->paid_amount;
                        @endphp
                        <tr class="hover:bg-red-50/30 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant text-on-surface flex items-center justify-center font-bold text-sm uppercase">{{ substr($fee->student->first_name, 0, 1) }}{{ substr($fee->student->last_name ?? '', 0, 1) }}</div>
                                    <div>
                                        <div class="font-medium text-on-surface">{{ $fee->student->first_name }} {{ $fee->student->last_name }}</div>
                                        <div class="text-xs text-secondary mt-0.5">{{ $fee->student->currentClass->name ?? '' }} - {{ $fee->student->currentSection->name ?? '' }}</div>
                                        <div class="text-xs text-secondary flex items-center gap-1 mt-1">
                                            <span class="material-symbols-rounded text-[14px]">call</span>
                                            {{ $fee->student->phone ?? $fee->student->parent->phone ?? 'No Phone' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-secondary font-medium">{{ $fee->challan_no }}</td>
                            <td class="py-4 px-6">
                                <div class="text-error font-medium flex items-center gap-1.5 bg-red-50 border border-red-100 px-2.5 py-1 rounded-md inline-flex">
                                    <span class="material-symbols-rounded text-[16px]">warning</span>
                                    {{ \Carbon\Carbon::parse($fee->due_date)->format('d M, Y') }}
                                </div>
                            </td>
                            <td class="py-4 px-6 font-medium text-on-surface">{{ number_format($fee->amount - $fee->discount + $fee->fine, 2) }}</td>
                            <td class="py-4 px-6 text-emerald-600 font-medium">{{ number_format($fee->paid_amount, 2) }}</td>
                            <td class="py-4 px-6">
                                <div class="text-error font-bold text-body-lg flex items-center gap-1">
                                    {{ number_format(max(0, $balance), 2) }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form method="POST" action="{{ route('accountant.defaulters.remind', $fee->student_id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="fee_id" value="{{ $fee->id }}">
                                    <button class="btn-outline text-sm py-2 px-5 text-error border-error/50 hover:border-error hover:bg-error hover:text-on-error transition-all shadow-sm flex items-center gap-2 ml-auto">
                                        <span class="material-symbols-rounded text-[18px]">notifications</span>
                                        Remind
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-secondary">
                                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                                    <span class="material-symbols-rounded text-4xl">check_circle</span>
                                </div>
                                <p class="text-body-lg font-medium text-on-surface mt-2">No defaulters found</p>
                                <p class="text-body-md mt-1">All student fee collections are up to date.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($defaulters->hasPages())
            <div class="p-4 bg-surface-bright border-t border-outline-variant">
                {{ $defaulters->links() }}
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
