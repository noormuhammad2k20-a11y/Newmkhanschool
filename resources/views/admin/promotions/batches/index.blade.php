@extends('layouts.app')

@section('title', 'Auto Promotion Batches')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Auto Promotion Batches</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage and approve batch promotions</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.promotions.index') }}" class="px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    Back to Promotions
                </a>
                <a href="{{ route('admin.promotions.batches.create') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors">
                    <span class="material-symbols-rounded text-[18px]">add</span>
                    Create Batch
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Batches</h3>
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                        <span class="material-symbols-rounded text-[18px]">layers</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $metrics['total'] }}</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending Approval</h3>
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700">
                        <span class="material-symbols-rounded text-[18px]">pending_actions</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $metrics['pending'] }}</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Executed Batches</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-rounded text-[18px]">task_alt</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $metrics['executed'] }}</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Failed Promotions</h3>
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-700">
                        <span class="material-symbols-rounded text-[18px]">error</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $metrics['failed_promotions'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Batch ID</th>
                            <th class="py-3 px-4 font-semibold">From / To Session</th>
                            <th class="py-3 px-4 font-semibold">From Class</th>
                            <th class="py-3 px-4 font-semibold">To Class</th>
                            <th class="py-3 px-4 font-semibold">Total Students</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold">Created</th>
                            <th class="py-3 px-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($batches as $batch)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 text-on-surface font-medium">#{{ $batch->id }}</td>
                            <td class="py-3 px-4 text-secondary">
                                {{ $batch->fromSession->year ?? '—' }} &rarr; {{ $batch->toSession->year ?? '—' }}
                            </td>
                            <td class="py-3 px-4 text-secondary">{{ $batch->fromClass->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $batch->toClass->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $batch->total_students }}</td>
                            <td class="py-3 px-4">
                                @if($batch->status === 'pending_approval')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Pending Approval</span>
                                @elseif($batch->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Approved</span>
                                @elseif($batch->status === 'executed')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Executed</span>
                                @elseif($batch->status === 'rejected')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-secondary">{{ $batch->created_at->format('M d, Y H:i') }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.promotions.batches.show', $batch->id) }}" class="text-primary hover:underline text-label-md font-label-md">Review</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-secondary">No promotion batches found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-outline-variant">
                {{ $batches->links() }}
            </div>
        </div>
    </div>
</main>
@endsection
