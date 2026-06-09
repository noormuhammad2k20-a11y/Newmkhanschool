@extends('layouts.app')

@section('content')
<main class="p-lg md:p-xl w-full max-w-7xl mx-auto">
    <div class="mb-lg flex flex-col md:flex-row md:items-end justify-between gap-sm">
        <div>
            <h2 class="font-display-sm text-display-sm font-bold text-on-surface">Leave Applications - {{ $student->first_name }} {{ $student->last_name }}</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Class {{ $student->currentClass->name ?? '' }} | Section {{ $student->currentSection->name ?? '' }}</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('parent.children') }}" class="btn-outline">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Children
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-md p-md bg-primary-container text-on-primary-container rounded-lg border border-primary/20">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        <div class="lg:col-span-1">
            <div class="card p-lg">
                <h3 class="font-title-md text-title-md font-semibold text-on-surface mb-md">Apply for Leave</h3>
                <form action="{{ route('parent.child.leave.store', $student->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-1">Leave Type *</label>
                        <select name="leave_type" required class="input-field w-full">
                            <option value="">Select Type</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Casual Leave">Casual Leave</option>
                            <option value="Emergency">Emergency</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('leave_type') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-1">Start Date *</label>
                            <input type="date" name="start_date" required min="{{ date('Y-m-d') }}" class="input-field w-full">
                            @error('start_date') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-1">End Date *</label>
                            <input type="date" name="end_date" required min="{{ date('Y-m-d') }}" class="input-field w-full">
                            @error('end_date') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-1">Reason *</label>
                        <textarea name="reason" required rows="4" class="input-field w-full resize-none"></textarea>
                        @error('reason') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <button type="submit" class="btn-primary w-full">Submit Application</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="card p-0 overflow-hidden">
                <div class="p-md border-b border-outline-variant">
                    <h3 class="font-title-md text-title-md font-semibold text-on-surface">Leave History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-high border-b border-outline-variant">
                                <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Leave Type</th>
                                <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Dates</th>
                                <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Reason</th>
                                <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse($leaves as $leave)
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="p-md font-label-md text-label-md font-medium text-on-surface">{{ $leave->leave_type }}</td>
                                    <td class="p-md font-body-md text-body-md text-on-surface-variant">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - 
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                    </td>
                                    <td class="p-md font-body-md text-body-md text-on-surface-variant max-w-[200px] truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                                    <td class="p-md">
                                        @if($leave->status === 'Pending')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-tertiary-container text-on-tertiary-container font-label-sm text-label-sm">
                                                Pending
                                            </span>
                                        @elseif($leave->status === 'Approved')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-primary-container text-on-primary-container font-label-sm text-label-sm">
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-error-container text-on-error-container font-label-sm text-label-sm">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-xl text-center">
                                        <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                            <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">event_available</span>
                                            <p class="font-body-lg text-body-lg">No leave applications found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
