@extends('layouts.app')

@section('content')
<main class="p-lg md:p-xl w-full max-w-7xl mx-auto">
    <div class="mb-lg flex flex-col md:flex-row md:items-end justify-between gap-sm">
        <div>
            <h2 class="font-display-sm text-display-sm font-bold text-on-surface">Exam Schedule - {{ $student->first_name }} {{ $student->last_name }}</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Class {{ $student->currentClass->name ?? '' }} | Section {{ $student->currentSection->name ?? '' }}</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('parent.children') }}" class="btn-outline">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Children
            </a>
        </div>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-high border-b border-outline-variant">
                        <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Subject</th>
                        <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Date</th>
                        <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Start Time</th>
                        <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">End Time</th>
                        <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Max Marks</th>
                        <th class="p-md font-label-lg text-label-lg font-semibold text-on-surface">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($schedules as $schedule)
                        @php
                            $isUpcoming = \Carbon\Carbon::parse($schedule->exam_date)->isFuture() && \Carbon\Carbon::parse($schedule->exam_date)->diffInDays(now()) <= 3;
                        @endphp
                        <tr class="hover:bg-surface-container transition-colors {{ $isUpcoming ? 'bg-error-container/20' : '' }}">
                            <td class="p-md">
                                <div class="font-label-md text-label-md font-medium text-on-surface">{{ $schedule->subjectRel->name ?? 'N/A' }}</div>
                            </td>
                            <td class="p-md">
                                <div class="font-body-md text-body-md text-on-surface-variant {{ $isUpcoming ? 'text-error font-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($schedule->exam_date)->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="p-md font-body-md text-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</td>
                            <td class="p-md font-body-md text-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                            <td class="p-md font-body-md text-body-md text-on-surface-variant">{{ $schedule->max_marks }}</td>
                            <td class="p-md">
                                @if(\Carbon\Carbon::parse($schedule->exam_date)->isPast())
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-primary-container text-on-primary-container font-label-sm text-label-sm">
                                        Upcoming
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-xl text-center">
                                <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">event_busy</span>
                                    <p class="font-body-lg text-body-lg">No exam schedule found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
