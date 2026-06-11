@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Leave Requests</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage and apply for leaves</p>
            </div>
            <button type="button" onclick="document.getElementById('leaveModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container text-label-md font-label-md rounded-lg transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Apply Leave
            </button>
        </div>

<!-- Leave Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[24px]">event_available</span>
                </div>
                <div>
                    <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Total Allowed</p>
                    <p class="text-headline-sm font-bold text-on-surface">15 Days</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[#ecfdf5] flex items-center justify-center text-[#10b981]">
                    <span class="material-symbols-outlined text-[24px]">event_busy</span>
                </div>
                <div>
                    <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Leaves Taken</p>
                    <p class="text-headline-sm font-bold text-on-surface">{{ isset($leaves) ? $leaves->where('status', 'Approved')->sum(function($l) { return \Carbon\Carbon::parse($l->start_date)->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1; }) : 0 }} Days</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[#fffbeb] flex items-center justify-center text-[#d97706]">
                    <span class="material-symbols-outlined text-[24px]">pending_actions</span>
                </div>
                <div>
                    <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Pending</p>
                    <p class="text-headline-sm font-bold text-on-surface">{{ isset($leaves) ? $leaves->where('status', 'Pending')->count() : 0 }} Requests</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-[24px]">balance</span>
                </div>
                <div>
                    <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Remaining</p>
                    <p class="text-headline-sm font-bold text-on-surface">{{ 15 - (isset($leaves) ? $leaves->where('status', 'Approved')->sum(function($l) { return \Carbon\Carbon::parse($l->start_date)->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1; }) : 0) }} Days</p>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="p-4 border-b border-outline-variant bg-surface-bright">
                <h3 class="text-headline-sm font-bold text-on-surface">Leave History</h3>
            </div>
            @if(isset($leaves) && count($leaves) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-body-md text-on-surface">
                        <thead class="bg-surface-bright text-on-surface-variant font-label-md text-label-md border-b border-outline-variant">
                            <tr>
                                <th class="px-6 py-4 uppercase tracking-wider">Leave Type</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach($leaves as $leave)
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-on-surface block">{{ $leave->leave_type }}</span>
                                    <span class="text-[11px] text-secondary">Applied on {{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y') }}</span>
                                </td>
                                <td class="px-6 py-4 text-secondary">
                                    <span class="block text-on-surface font-medium">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</span>
                                    <span class="text-[11px]">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} Days</span>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate text-secondary" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                                <td class="px-6 py-4">
                                    @if($leave->status === 'Approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-[#ecfdf5] text-[#10b981] border border-[#a7f3d0]">Approved</span>
                                    @elseif($leave->status === 'Rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-error-container text-error border border-error-container">Rejected</span>
                                        @if($leave->remarks)
                                            <span class="block text-[10px] text-error mt-1 truncate max-w-[100px]" title="{{ $leave->remarks }}">{{ $leave->remarks }}</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-[#fffbeb] text-[#d97706] border border-[#fde68a]">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($leave->status === 'Pending')
                                        <button onclick="window.UI.confirm('Confirm Action', 'Are you sure you want to cancel this leave request?', 'Cancel Leave', 'error').then(c => { if(c) window.UI.alert('Notice', 'Cancel function not yet implemented.'); })" class="text-error hover:bg-error-container p-2 rounded-lg transition-colors text-sm font-bold flex items-center gap-1 ml-auto">
                                            <span class="material-symbols-outlined text-[16px]">cancel</span> Cancel
                                        </button>
                                    @else
                                        <span class="text-secondary text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-secondary border border-outline-variant border-dashed rounded-xl m-4">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">event_busy</span>
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-1">No Leaves Found</h3>
                    <p class="text-body-lg font-body-lg">You haven't applied for any leaves yet.</p>
                </div>
            @endif
        </div>
    </div>
</main>

<!-- Leave Application Modal -->
<div id="leaveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="document.getElementById('leaveModal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="bg-surface-container-lowest rounded-xl max-w-md w-full shadow-lg border border-outline-variant relative z-10 transform scale-100 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <h3 class="text-headline-md font-headline-md font-bold text-on-surface">Apply for Leave</h3>
            <button type="button" onclick="document.getElementById('leaveModal').classList.add('hidden')" class="text-secondary hover:bg-surface-container w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('student.leave.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Leave Type</label>
                    <select name="leave_type" required class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-primary shadow-sm h-10 px-3">
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Casual Leave">Casual Leave</option>
                        <option value="Emergency">Emergency</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Start Date</label>
                        <input type="date" name="start_date" required class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-primary shadow-sm h-10 px-3">
                    </div>
                    <div>
                        <label class="block text-label-md font-label-md text-on-surface-variant mb-1">End Date</label>
                        <input type="date" name="end_date" required class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-primary shadow-sm h-10 px-3">
                    </div>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Reason</label>
                    <textarea name="reason" rows="3" required class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-primary shadow-sm p-3" placeholder="Briefly explain the reason..."></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('leaveModal').classList.add('hidden')" class="px-4 py-2.5 rounded-lg text-label-md font-label-md text-secondary hover:bg-surface-container transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container text-label-md font-label-md rounded-lg transition-colors shadow-sm">Submit Request</button>
            </div>
        </form>
    </div>
</div>
@endsection
