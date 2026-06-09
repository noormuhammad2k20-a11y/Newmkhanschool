@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface p-lg">
    <div class="max-w-max-width mx-auto">
        <div class="mb-lg">
            <h2 class="font-headline-xl text-headline-xl font-bold text-on-surface">Exam Schedule</h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant mt-sm">View scheduled exams for your classes.</p>
        </div>

        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-secondary font-label-md uppercase tracking-wider border-b border-outline-variant">
                            <th class="p-md font-semibold">Exam Type</th>
                            <th class="p-md font-semibold">Class</th>
                            <th class="p-md font-semibold">Subject</th>
                            <th class="p-md font-semibold">Date</th>
                            <th class="p-md font-semibold">Time</th>
                            <th class="p-md font-semibold text-center">Marks (Max / Pass)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant font-body-md text-on-surface">
                        @forelse($schedules as $schedule)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="p-md">{{ $schedule->exam_type_id ? ($schedule->examType->name ?? 'Exam') : 'Exam' }}</td>
                            <td class="p-md font-semibold">{{ $schedule->class->name ?? 'N/A' }}</td>
                            <td class="p-md">{{ $schedule->subjectRel->name ?? $schedule->subject }}</td>
                            <td class="p-md">{{ \Carbon\Carbon::parse($schedule->exam_date)->format('M d, Y') }}</td>
                            <td class="p-md">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                            <td class="p-md text-center">{{ $schedule->max_marks }} / {{ $schedule->passing_marks }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-xl text-center text-secondary">
                                <span class="material-symbols-outlined text-[48px] mb-sm opacity-50">event_note</span>
                                <p class="font-body-lg">No exams scheduled for your classes.</p>
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
