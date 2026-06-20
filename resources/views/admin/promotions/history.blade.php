@extends('layouts.app')

@section('title', 'Promotion History')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Promotion History</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Complete log of all student promotions</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.promotions.export-history', request()->query()) }}" class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-rounded text-[18px]">download</span>
                    Export CSV
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors">
                    <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                    Back to Promotions
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
            <form method="GET" action="{{ route('admin.promotions.history') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Search</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name or Admission No..." class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Academic Session</label>
                    <select name="academic_year_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="">All Sessions</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ ($filters['academic_year_id'] ?? '') == $year->id ? 'selected' : '' }}>
                                {{ $year->year ?? ($year->start_date . ' – ' . $year->end_date) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Class</label>
                    <select name="class_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ ($filters['class_id'] ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Status</label>
                    <select name="status" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="">All Statuses</option>
                        <option value="success" {{ ($filters['status'] ?? '') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ ($filters['status'] ?? '') === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="skipped" {{ ($filters['status'] ?? '') === 'skipped' ? 'selected' : '' }}>Skipped</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded text-[18px]">filter_list</span>
                        Filter
                    </button>
                    <a href="{{ route('admin.promotions.history') }}" class="py-2 px-3 bg-surface-container border border-outline-variant rounded-lg text-label-md text-on-surface hover:bg-surface-container-high transition-colors" title="Reset filters">
                        <span class="material-symbols-rounded text-[18px]">restart_alt</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- History Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Student</th>
                            <th class="py-3 px-4 font-semibold">Admission No</th>
                            <th class="py-3 px-4 font-semibold">From</th>
                            <th class="py-3 px-4 font-semibold">To</th>
                            <th class="py-3 px-4 font-semibold">Promoted By</th>
                            <th class="py-3 px-4 font-semibold">Date</th>
                            <th class="py-3 px-4 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($history as $record)
                            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr($record->student->first_name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-on-surface font-medium">{{ ($record->student->first_name ?? '') . ' ' . ($record->student->last_name ?? '') }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-secondary">{{ $record->student->admission_no ?? '—' }}</td>
                                <td class="py-3 px-4">
                                    <div class="text-on-surface text-sm">
                                        <span class="font-medium">{{ $record->fromClass->name ?? '—' }}</span>
                                        @if($record->fromSection)
                                            <span class="text-secondary"> ({{ $record->fromSection->name }})</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-secondary">
                                        {{ $record->academicYear->year ?? ($record->academicYear->start_date ?? '—') }}
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-on-surface text-sm">
                                        <span class="font-medium">{{ $record->toClass->name ?? '—' }}</span>
                                        @if($record->toSection)
                                            <span class="text-secondary"> ({{ $record->toSection->name }})</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-secondary">
                                        {{ $record->toAcademicYear->year ?? ($record->toAcademicYear->start_date ?? '—') }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-secondary text-sm">
                                    {{ $record->promotedByUser->name ?? 'System' }}
                                </td>
                                <td class="py-3 px-4 text-secondary text-sm">
                                    {{ $record->promoted_at?->format('M d, Y') }}
                                    <div class="text-xs opacity-70">{{ $record->promoted_at?->format('h:i A') }}</div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($record->status === 'success')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                            <span class="material-symbols-rounded text-[12px] mr-1">check_circle</span>
                                            Success
                                        </span>
                                    @elseif($record->status === 'failed')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800" title="{{ $record->error_message }}">
                                            <span class="material-symbols-rounded text-[12px] mr-1">cancel</span>
                                            Failed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800" title="{{ $record->error_message }}">
                                            <span class="material-symbols-rounded text-[12px] mr-1">skip_next</span>
                                            Skipped
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-secondary">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-rounded text-5xl mb-3 opacity-30">history</span>
                                        <p class="text-lg font-medium text-on-surface mb-1">No promotion history found</p>
                                        <p class="text-sm">Promotions will appear here once students are promoted.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($history->hasPages())
                <div class="p-md border-t border-outline-variant">
                    {{ $history->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
@endsection
