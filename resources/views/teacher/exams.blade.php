@extends('layouts.app')

@section('title', 'Exams')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Exams & Results</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">View upcoming exams and manage results for your classes.</p>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright">
                <h3 class="text-headline-md font-headline-md text-on-surface">Upcoming Exam Schedule</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Exam Type</th>
                            <th class="py-3 px-4 font-semibold">Class</th>
                            <th class="py-3 px-4 font-semibold">Subject</th>
                            <th class="py-3 px-4 font-semibold">Date</th>
                            <th class="py-3 px-4 font-semibold">Time</th>
                            <th class="py-3 px-4 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($exams as $exam)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-medium text-on-surface">{{ $exam->exam_type }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $exam->class_->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $exam->subjectRelation->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M, Y') }}</td>
                            <td class="py-3 px-4">{{ $exam->exam_time }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">{{ $exam->general_status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-secondary">
                                No upcoming exams scheduled for your classes.
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
