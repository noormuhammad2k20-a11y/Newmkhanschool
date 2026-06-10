@extends('layouts.app')

@section('title', 'Exam Schedule')

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
                    <span class="text-on-surface">Exam Schedule</span>
                </nav>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Exam Schedule</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">{{ $student->first_name }} {{ $student->last_name }} (Class {{ $student->currentClass->name ?? '' }} {{ $student->currentSection->name ?? '' }})</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('parent.children') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Children
                </a>
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface rounded-lg font-label-md hover:bg-surface-container-highest transition-colors shadow-sm hidden sm:flex">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    Print Schedule
                </button>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col print-container">
            <div class="p-xl border-b border-outline-variant bg-surface-bright flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hidden print-show">
                <div>
                    <h3 class="text-headline-md font-headline-md text-on-surface">Official Exam Schedule</h3>
                    <p class="text-body-md text-secondary">Student: {{ $student->first_name }} {{ $student->last_name }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-fixed rounded flex items-center justify-center text-primary font-bold">
                    LOGO
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-4 px-6 font-semibold">Subject</th>
                            <th class="py-4 px-6 font-semibold">Date</th>
                            <th class="py-4 px-6 font-semibold">Time</th>
                            <th class="py-4 px-6 font-semibold">Max Marks</th>
                            <th class="py-4 px-6 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($schedules as $schedule)
                            @php
                                $examDate = \Carbon\Carbon::parse($schedule->exam_date);
                                $isUpcoming = $examDate->isFuture() && $examDate->diffInDays(now()) <= 3;
                            @endphp
                            <tr class="hover:bg-surface-container-low transition-colors {{ $isUpcoming ? 'bg-error-container/10 border-l-4 border-l-error' : 'border-l-4 border-l-transparent' }}">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined text-[20px]">menu_book</span>
                                        </div>
                                        <span class="text-title-md font-title-md text-on-surface">{{ $schedule->subjectRelation->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-body-lg font-body-lg text-on-surface {{ $isUpcoming ? 'text-error font-semibold' : '' }}">{{ $examDate->format('M d, Y') }}</span>
                                        <span class="text-label-sm font-label-sm text-secondary">{{ $examDate->format('l') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2 text-body-md font-body-md text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[18px] text-secondary">schedule</span>
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-title-md font-title-md text-on-surface">{{ $schedule->max_marks }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($examDate->isPast())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-label-sm font-label-sm bg-surface-container-highest text-secondary border border-outline-variant">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-label-sm font-label-sm bg-primary-container text-on-primary-container border border-primary-container">
                                            Upcoming
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-secondary">
                                        <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-[40px] opacity-50">event_busy</span>
                                        </div>
                                        <h3 class="text-headline-md font-headline-md text-on-surface mb-2">No Exam Schedule</h3>
                                        <p class="text-body-lg font-body-lg text-secondary">There are no upcoming exams scheduled for this student.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<style>
    @media print {
        .print-container {
            border: none;
            box-shadow: none;
            width: 100%;
        }
        .print-show {
            display: flex !important;
        }
        nav, .material-symbols-outlined:not(.print-show .material-symbols-outlined), button, a {
            display: none !important;
        }
    }
    .print-show {
        display: none;
    }
</style>
@endsection
