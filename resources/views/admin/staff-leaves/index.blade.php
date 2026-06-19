@extends('layouts.app')

@section('title', 'Staff Leave Management')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Staff Leave Management</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Review leave requests, assign substitutes, and track balances</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.staff-leaves.balances') }}" class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-rounded text-[18px]">account_balance_wallet</span>
                    Leave Balances
                </a>
                <a href="{{ route('admin.staff-leaves.substitutes') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors">
                    <span class="material-symbols-rounded text-[18px]">event_available</span>
                    Substitute Schedule
                </a>
            </div>
        </div>

        <!-- Stats Grid (4 cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Stat Card 1 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Requests</h3>
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                        <span class="material-symbols-rounded text-[18px]">all_inbox</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $leaves->total() }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-secondary flex items-center gap-1">
                    All time requests
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending</h3>
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700">
                        <span class="material-symbols-rounded text-[18px]">pending_actions</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $pendingCount }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-orange-700 flex items-center gap-1">
                    Requires your action
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-orange-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Approved</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-rounded text-[18px]">check_circle</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $leaves->where('status', 'Approved')->count() }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-emerald-700 flex items-center gap-1">
                    Successfully granted
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Rejected</h3>
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-700">
                        <span class="material-symbols-rounded text-[18px]">cancel</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $leaves->where('status', 'Rejected')->count() }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-red-700 flex items-center gap-1">
                    Declined requests
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-red-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Leaves Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                <h3 class="text-headline-md font-headline-md text-on-surface">Recent Leave Requests</h3>
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 bg-surface-container border border-outline-variant rounded-lg text-label-sm font-label-sm hover:bg-surface-variant transition-colors flex items-center gap-1">
                        <span class="material-symbols-rounded text-[16px]">filter_list</span> Filter
                    </button>
                    <button class="px-3 py-1.5 bg-surface-container border border-outline-variant rounded-lg text-label-sm font-label-sm hover:bg-surface-variant transition-colors flex items-center gap-1">
                        <span class="material-symbols-rounded text-[16px]">download</span> Export
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Teacher</th>
                            <th class="py-3 px-4 font-semibold">Leave Type</th>
                            <th class="py-3 px-4 font-semibold">Duration</th>
                            <th class="py-3 px-4 font-semibold">Reason</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($leaves as $leave)
                            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr($leave->teacher->user->name ?? 'T', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-on-surface font-medium">{{ $leave->teacher->user->name ?? 'Unknown Teacher' }}</span>
                                            <span class="text-secondary text-xs">{{ $leave->teacher->department ?? 'General' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-surface-variant text-on-surface-variant">
                                        @if(str_contains(strtolower($leave->leave_type), 'sick'))
                                            <span class="material-symbols-rounded text-[14px]">local_hospital</span>
                                        @elseif(str_contains(strtolower($leave->leave_type), 'casual'))
                                            <span class="material-symbols-rounded text-[14px]">beach_access</span>
                                        @elseif(str_contains(strtolower($leave->leave_type), 'emergency'))
                                            <span class="material-symbols-rounded text-[14px]">warning</span>
                                        @else
                                            <span class="material-symbols-rounded text-[14px]">event</span>
                                        @endif
                                        {{ $leave->leave_type }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-on-surface">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M, Y') }}</span>
                                        <span class="text-secondary text-xs font-medium">{{ $leave->total_days }} Day(s)</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="text-secondary truncate max-w-[200px]" title="{{ $leave->reason }}">{{ $leave->reason }}</p>
                                </td>
                                <td class="py-3 px-4">
                                    @if($leave->status === 'Pending')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Pending</span>
                                    @elseif($leave->status === 'Approved')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Approved</span>
                                    @elseif($leave->status === 'Rejected')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rejected</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-surface-variant text-on-surface-variant">{{ $leave->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if($leave->status === 'Pending')
                                            <!-- Approve Form -->
                                            <form action="{{ route('admin.staff-leaves.approve', $leave->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Approve">
                                                    <span class="material-symbols-rounded text-[18px]">check_circle</span>
                                                </button>
                                            </form>
                                            <!-- Reject Button (Triggers Modal in real app, here direct action for UI) -->
                                            <form action="{{ route('admin.staff-leaves.reject', $leave->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="rejection_reason" value="Declined by administrator">
                                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Reject">
                                                    <span class="material-symbols-rounded text-[18px]">cancel</span>
                                                </button>
                                            </form>
                                        @endif
                                        <button class="p-1.5 text-secondary hover:text-primary hover:bg-primary-fixed rounded-lg transition-colors" title="View Details">
                                            <span class="material-symbols-rounded text-[18px]">visibility</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-secondary">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-rounded text-4xl mb-3 opacity-50">event_busy</span>
                                        <p class="text-lg font-medium text-on-surface mb-1">No leave requests found</p>
                                        <p class="text-sm">You're all caught up!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($leaves->hasPages())
            <div class="p-4 border-t border-outline-variant bg-surface-bright">
                {{ $leaves->links() }}
            </div>
            @endif
        </div>

    </div>
</main>
@endsection
