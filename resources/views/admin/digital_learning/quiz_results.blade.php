@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.digital_learning.quizzes') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-low hover:bg-surface-container transition-colors text-on-surface">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Quiz Results: {{ $quiz->title }}</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Class: {{ $quiz->class->name ?? 'N/A' }} | Passing Marks: {{ $quiz->passing_marks }} / {{ $quiz->total_marks }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 flex flex-col items-center justify-center">
            <span class="material-symbols-outlined text-[48px] text-primary mb-2">groups</span>
            <span class="font-headline-xl text-primary">{{ $attempts->count() }}</span>
            <span class="font-label-md text-on-surface-variant">Total Attempts</span>
        </div>
        
        @php
            $passed = $attempts->where('score', '>=', $quiz->passing_marks)->count();
            $failed = $attempts->count() - $passed;
        @endphp

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 flex flex-col items-center justify-center">
            <span class="material-symbols-outlined text-[48px] text-green-600 mb-2">check_circle</span>
            <span class="font-headline-xl text-green-600">{{ $passed }}</span>
            <span class="font-label-md text-on-surface-variant">Passed</span>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 flex flex-col items-center justify-center">
            <span class="material-symbols-outlined text-[48px] text-error mb-2">cancel</span>
            <span class="font-headline-xl text-error">{{ $failed }}</span>
            <span class="font-label-md text-on-surface-variant">Failed</span>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-md font-label-md text-on-surface-variant">Student</th>
                        <th class="p-md font-label-md text-on-surface-variant">Submitted At</th>
                        <th class="p-md font-label-md text-on-surface-variant">Score</th>
                        <th class="p-md font-label-md text-on-surface-variant">Percentage</th>
                        <th class="p-md font-label-md text-on-surface-variant">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($attempts as $attempt)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-md font-body-md text-on-surface font-semibold">
                                {{ $attempt->student->user->name ?? 'Unknown' }}
                            </td>
                            <td class="p-md font-body-md text-on-surface">
                                {{ $attempt->submitted_at ? $attempt->submitted_at->format('M d, Y h:i A') : 'N/A' }}
                            </td>
                            <td class="p-md font-body-md text-on-surface font-semibold">
                                {{ $attempt->score }} / {{ $attempt->total_marks }}
                            </td>
                            <td class="p-md font-body-md text-on-surface">
                                {{ number_format($attempt->percentage, 2) }}%
                            </td>
                            <td class="p-md font-body-md">
                                @if($attempt->score >= $quiz->passing_marks)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Passed</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Failed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-md text-center text-on-surface-variant py-8">
                                No attempts recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
