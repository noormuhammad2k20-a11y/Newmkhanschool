@extends('layouts.app')

@section('content')
<main class="p-lg md:p-xl w-full max-w-7xl mx-auto">
    <div class="mb-lg flex flex-col md:flex-row md:items-end justify-between gap-sm">
        <div>
            <h2 class="font-display-sm text-display-sm font-bold text-on-surface">Report Card - {{ $student->first_name }} {{ $student->last_name }}</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Class {{ $student->currentClass->name ?? '' }} | Section {{ $student->currentSection->name ?? '' }}</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('parent.children') }}" class="btn-outline">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Children
            </a>
        </div>
    </div>

    @if(!$reportCard)
        <div class="card p-xl text-center">
            <div class="flex flex-col items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">description</span>
                <p class="font-body-lg text-body-lg">No finalized report card available for this student.</p>
            </div>
        </div>
    @else
        <div class="card mb-lg p-lg bg-primary-container text-on-primary-container">
            <div class="flex flex-wrap gap-xl">
                <div>
                    <p class="font-label-sm text-label-sm opacity-80 uppercase tracking-wider mb-1">Overall Grade</p>
                    <p class="font-headline-lg text-headline-lg font-bold">{{ $reportCard->overall_grade ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm opacity-80 uppercase tracking-wider mb-1">Total Percentage</p>
                    <p class="font-headline-lg text-headline-lg font-bold">{{ $reportCard->total_percentage ?? 'N/A' }}%</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm opacity-80 uppercase tracking-wider mb-1">Rank</p>
                    <p class="font-headline-lg text-headline-lg font-bold">{{ $reportCard->rank ?? 'N/A' }}</p>
                </div>
            </div>
            @if($reportCard->remarks)
                <div class="mt-md pt-md border-t border-primary/20">
                    <p class="font-label-md text-label-md font-medium mb-1">Teacher Remarks:</p>
                    <p class="font-body-md text-body-md">{{ $reportCard->remarks }}</p>
                </div>
            @endif
        </div>
        
        <div class="card p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-high border-b border-outline-variant">
                            <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Subject</th>
                            <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Marks Obtained</th>
                            <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Total Marks</th>
                            <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Grade</th>
                            <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($marks as $mark)
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="p-md font-label-md text-label-md font-medium text-on-surface">{{ $mark->subject_name }}</td>
                                <td class="p-md font-body-md text-body-md text-on-surface-variant">{{ $mark->marks_obtained }}</td>
                                <td class="p-md font-body-md text-body-md text-on-surface-variant">{{ $mark->max_marks ?? 100 }}</td>
                                <td class="p-md font-body-md text-body-md text-on-surface-variant">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm">
                                        {{ $mark->grade ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="p-md font-body-md text-body-md text-on-surface-variant">{{ $mark->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-md text-center text-on-surface-variant">No marks recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</main>
@endsection
