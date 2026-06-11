@extends('layouts.app')

@section('title', 'Leave Application')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-label-md font-label-md text-secondary mb-2">
                    <a href="{{ route('parent.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <a href="{{ route('parent.children') }}" class="hover:text-primary transition-colors">My Children</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="text-on-surface">Leave Application</span>
                </nav>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Leave Application</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">{{ $student->first_name }} {{ $student->last_name }} (Class {{ $student->currentClass->name ?? '' }} {{ $student->currentSection->name ?? '' }})</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('parent.children') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Children
                </a>
            </div>
        </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-xl">
            <!-- Apply for Leave Form -->
            <div class="lg:col-span-1">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                    <div class="p-md border-b border-outline-variant bg-surface-bright">
                        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">edit_document</span>
                            New Application
                        </h3>
                    </div>
                    <form action="{{ route('parent.child.leave.store', $student->id) }}" method="POST" class="p-md space-y-5">
                        @csrf
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-2">Leave Type <span class="text-error">*</span></label>
                            <select name="leave_type" required class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary text-on-surface transition-colors">
                                <option value="">Select leave type...</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Emergency">Emergency Leave</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('leave_type') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface mb-2">Start Date <span class="text-error">*</span></label>
                                <input type="date" name="start_date" required min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary text-on-surface transition-colors">
                                @error('start_date') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface mb-2">End Date <span class="text-error">*</span></label>
                                <input type="date" name="end_date" required min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary text-on-surface transition-colors">
                                @error('end_date') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-2">Reason <span class="text-error">*</span></label>
                            <textarea name="reason" required rows="4" placeholder="Briefly explain the reason for leave..." class="w-full bg-surface border border-outline-variant rounded-lg py-2.5 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary text-on-surface resize-y transition-colors"></textarea>
                            @error('reason') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <button type="submit" class="w-full bg-primary text-on-primary py-2.5 rounded-lg font-label-lg hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">send</span>
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>

            <!-- Leave History Table -->
            <div class="lg:col-span-2">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm flex flex-col h-full">
                    <div class="p-md border-b border-outline-variant bg-surface-bright">
                        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">history</span>
                            Leave History
                        </h3>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                    <th class="py-3 px-4 font-semibold">Leave Type</th>
                                    <th class="py-3 px-4 font-semibold">Duration</th>
                                    <th class="py-3 px-4 font-semibold">Reason</th>
                                    <th class="py-3 px-4 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @forelse($leaves as $leave)
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface font-medium">{{ $leave->leave_type }}</td>
                                        <td class="py-3 px-4 text-body-md font-body-md text-secondary">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - 
                                            {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                        </td>
                                        <td class="py-3 px-4 text-body-md font-body-md text-secondary max-w-[200px] truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                                        <td class="py-3 px-4">
                                            @if($leave->status === 'Pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-orange-100 text-orange-800 font-label-sm text-label-sm border border-orange-200">
                                                    Pending
                                                </span>
                                            @elseif($leave->status === 'Approved')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-label-sm text-label-sm border border-emerald-200">
                                                    Approved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-100 text-red-800 font-label-sm text-label-sm border border-red-200">
                                                    Rejected
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-16 text-center">
                                            <div class="flex flex-col items-center justify-center text-secondary">
                                                <div class="w-16 h-16 rounded-full bg-surface-container-low flex items-center justify-center mb-3">
                                                    <span class="material-symbols-outlined text-[32px] opacity-50">event_available</span>
                                                </div>
                                                <p class="text-body-lg font-body-lg text-on-surface mb-1">No Leave History</p>
                                                <p class="text-body-md font-body-md">You haven't submitted any leave applications for this student yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($leaves->hasPages())
                        <div class="p-4 border-t border-outline-variant bg-surface-lowest">
                            {{ $leaves->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection


