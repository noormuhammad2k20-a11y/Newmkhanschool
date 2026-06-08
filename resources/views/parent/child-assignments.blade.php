@extends('layouts.app')

@section('title', 'Child Assignments')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Assignments</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Viewing assignments for {{ $student->first_name }} {{ $student->last_name }}</p>
    </div>
    <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
        <span class="material-symbols-rounded text-[18px] mr-1">arrow_back</span>
        Back to Dashboard
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($assignments ?? [] as $assignment)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col hover:border-blue-300 transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-start mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ $assignment->subject->name ?? 'N/A' }}
                    </span>
                    @php
                        $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                        $isOverdue = $dueDate->isPast() && !$dueDate->isToday();
                    @endphp
                    <span class="text-xs font-medium flex items-center gap-1 {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                        <span class="material-symbols-rounded text-[14px]">calendar_today</span>
                        Due {{ $dueDate->format('M d') }}
                    </span>
                </div>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-1 line-clamp-1">{{ $assignment->title }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $assignment->description }}</p>
            </div>
            <div class="p-5 bg-gray-50 dark:bg-gray-900/50 mt-auto">
                @php
                    $submission = $assignment->submissions->where('student_id', $student->id)->first();
                @endphp
                
                @if($submission)
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-sm font-medium text-green-600 dark:text-green-400">
                            <span class="material-symbols-rounded text-base">check_circle</span>
                            Submitted
                        </span>
                        @if($submission->marks_obtained !== null)
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $submission->marks_obtained }} Marks</span>
                        @else
                            <span class="text-sm text-gray-500">Pending Review</span>
                        @endif
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-sm font-medium text-orange-600 dark:text-orange-400">
                            <span class="material-symbols-rounded text-base">pending_actions</span>
                            Not Submitted
                        </span>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">task</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Assignments</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">There are no assignments due at the moment.</p>
        </div>
    @endforelse
</div>
@endsection
