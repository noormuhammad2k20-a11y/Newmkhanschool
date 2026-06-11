@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface p-lg">
    <div class="max-w-max-width mx-auto">
        <div class="mb-lg">
            <h2 class="font-headline-xl text-headline-xl font-bold text-on-surface">Student Leave Approvals</h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant mt-sm">Review and manage leave requests from your students.</p>
        </div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-secondary font-label-md uppercase tracking-wider border-b border-outline-variant">
                            <th class="p-md font-semibold">Student</th>
                            <th class="p-md font-semibold">Leave Type</th>
                            <th class="p-md font-semibold">Duration</th>
                            <th class="p-md font-semibold">Reason</th>
                            <th class="p-md font-semibold text-center">Status</th>
                            <th class="p-md font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant font-body-md text-on-surface">
                        @forelse($leaves as $leave)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="p-md font-semibold">
                                {{ $leave->first_name }} {{ $leave->last_name }}
                                <div class="text-secondary font-body-sm text-sm">Roll No: {{ $leave->admission_no }}</div>
                            </td>
                            <td class="p-md">{{ $leave->leave_type ?? 'Sick Leave' }}</td>
                            <td class="p-md">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                            </td>
                            <td class="p-md max-w-xs truncate" title="{{ $leave->reason ?? '' }}">{{ $leave->reason ?? 'No reason provided' }}</td>
                            <td class="p-md text-center">
                                @if($leave->status === 'Pending')
                                    <span class="bg-warning-container text-warning-on-container px-3 py-1 rounded-full text-label-sm font-semibold">Pending</span>
                                @elseif($leave->status === 'Approved')
                                    <span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-label-sm font-semibold">Approved</span>
                                @else
                                    <span class="bg-error-container text-error px-3 py-1 rounded-full text-label-sm font-semibold">Rejected</span>
                                @endif
                            </td>
                            <td class="p-md text-right">
                                @if($leave->status === 'Pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('teacher.student-leaves.approve', $leave->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary font-label-sm py-1 px-3 rounded shadow transition-colors" title="Approve">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('teacher.student-leaves.reject', $leave->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-error hover:bg-error-container text-on-error font-label-sm py-1 px-3 rounded shadow transition-colors" title="Reject">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-secondary font-body-sm italic">Resolved</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-xl text-center text-secondary">
                                <span class="material-symbols-outlined text-[48px] mb-sm opacity-50">event_available</span>
                                <p class="font-body-lg">No student leave requests pending.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
