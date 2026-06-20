@extends('layouts.app')

@section('title', 'Review Promotion Batch')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.promotions.batches.index') }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:text-primary transition-colors">
                    <span class="material-symbols-rounded">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Review Batch #{{ $batch->id }}</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Review validation results and approve promotion</p>
                </div>
            </div>

            @if($batch->status === 'pending_approval')
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.promotions.batches.reject', $batch->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-100 text-red-800 rounded-lg text-label-md font-label-md hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure you want to reject this batch?')">
                        Reject Batch
                    </button>
                </form>
                <form action="{{ route('admin.promotions.batches.approve', $batch->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-6 py-2 bg-emerald-600 text-white rounded-lg text-label-md font-label-md hover:bg-emerald-700 shadow-sm transition-colors" onclick="return confirm('Are you sure you want to approve and execute this batch? This action cannot be easily undone.')">
                        <span class="material-symbols-rounded text-[18px]">check_circle</span>
                        Approve & Execute
                    </button>
                </form>
            </div>
            @endif
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-start gap-3">
            <span class="material-symbols-rounded mt-0.5 text-[20px]">check_circle</span>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg flex items-start gap-3">
            <span class="material-symbols-rounded mt-0.5 text-[20px]">error</span>
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 col-span-1 lg:col-span-2">
                <h3 class="text-title-md font-title-md text-on-surface mb-4">Batch Details</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-surface-variant rounded-lg p-3">
                        <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">Total Students</span>
                        <span class="text-headline-sm font-headline-sm text-on-surface">{{ $batch->total_students }}</span>
                    </div>
                    <div class="bg-surface-variant rounded-lg p-3">
                        <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">Eligible</span>
                        <span class="text-headline-sm font-headline-sm text-emerald-600">{{ $eligibleCount }}</span>
                    </div>
                    <div class="bg-surface-variant rounded-lg p-3">
                        <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">Failed Validation</span>
                        <span class="text-headline-sm font-headline-sm text-red-600">{{ $failedCount }}</span>
                    </div>
                    <div class="bg-surface-variant rounded-lg p-3">
                        <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">Status</span>
                        @if($batch->status === 'pending_approval')
                            <span class="text-body-md font-medium text-orange-600">Pending Approval</span>
                        @elseif($batch->status === 'approved')
                            <span class="text-body-md font-medium text-blue-600">Approved</span>
                        @elseif($batch->status === 'executed')
                            <span class="text-body-md font-medium text-emerald-600">Executed</span>
                        @elseif($batch->status === 'rejected')
                            <span class="text-body-md font-medium text-red-600">Rejected</span>
                        @else
                            <span class="text-body-md font-medium text-secondary">{{ ucfirst($batch->status) }}</span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-8 relative">
                    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center justify-center bg-surface-container-lowest z-10 w-8 h-8 rounded-full border border-outline-variant">
                        <span class="material-symbols-rounded text-secondary text-[20px]">arrow_forward</span>
                    </div>
                    <div class="border border-outline-variant rounded-lg p-4 bg-surface-container-lowest">
                        <h4 class="text-label-md font-label-md text-secondary mb-3 uppercase">From</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-secondary">Session</span>
                                <span class="font-medium text-on-surface">{{ $batch->fromSession->year ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-secondary">Class</span>
                                <span class="font-medium text-on-surface">{{ $batch->fromClass->name ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-secondary">Section</span>
                                <span class="font-medium text-on-surface">{{ $batch->fromSection->name ?? 'All Sections' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="border border-outline-variant rounded-lg p-4 bg-surface-container-lowest">
                        <h4 class="text-label-md font-label-md text-secondary mb-3 uppercase">To</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-secondary">Session</span>
                                <span class="font-medium text-on-surface">{{ $batch->toSession->year ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-secondary">Class</span>
                                <span class="font-medium text-on-surface">{{ $batch->toClass->name ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-secondary">Section</span>
                                <span class="font-medium text-on-surface">{{ $batch->toSection->name ?? 'Auto Mapping' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                <h3 class="text-title-md font-title-md text-on-surface mb-4">Metadata</h3>
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs text-secondary uppercase mb-1">Created By</span>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">
                                {{ strtoupper(substr($batch->creator->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-body-md text-on-surface">{{ $batch->creator->name ?? 'System' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs text-secondary uppercase mb-1">Created At</span>
                        <span class="text-body-md text-on-surface">{{ $batch->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($batch->approved_by)
                    <div class="pt-4 border-t border-outline-variant">
                        <span class="block text-xs text-secondary uppercase mb-1">Approved By</span>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 text-xs font-bold">
                                {{ strtoupper(substr($batch->approver->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-body-md text-on-surface">{{ $batch->approver->name ?? 'System' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="p-4 border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <h3 class="text-headline-md font-headline-md text-on-surface">Student Validation List</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Student Name</th>
                            <th class="py-3 px-4 font-semibold">Admission No</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold">Remarks / Errors</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @foreach($batch->students as $bs)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest">
                            <td class="py-3 px-4 text-on-surface font-medium">
                                {{ $bs->student->first_name ?? 'Unknown' }} {{ $bs->student->last_name ?? '' }}
                            </td>
                            <td class="py-3 px-4 text-secondary">{{ $bs->student->admission_no ?? '—' }}</td>
                            <td class="py-3 px-4">
                                @if($bs->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Pending</span>
                                @elseif($bs->status === 'success')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Promoted</span>
                                @elseif($bs->status === 'failed')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Failed Validation</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($bs->error_message)
                                    <span class="text-red-600 text-sm flex items-center gap-1">
                                        <span class="material-symbols-rounded text-[14px]">warning</span>
                                        {{ $bs->error_message }}
                                    </span>
                                @else
                                    <span class="text-emerald-600 text-sm flex items-center gap-1">
                                        <span class="material-symbols-rounded text-[14px]">check_circle</span>
                                        Ready
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if($batch->students->isEmpty())
                        <tr>
                            <td colspan="4" class="py-8 text-center text-secondary">No students loaded in this batch.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>
@endsection
