@extends('layouts.app')

@section('title', 'Exam Schedule')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Exam Schedule</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Upcoming examinations and timings</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if(isset($schedules) && count($schedules) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 font-medium border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Exam Type</th>
                        <th class="px-6 py-4">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($schedules as $schedule)
                    @php
                        $isUpcoming = \Carbon\Carbon::parse($schedule->exam_date)->isFuture();
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors {{ $isUpcoming ? 'bg-blue-50/30 dark:bg-blue-900/10' : '' }}">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900 dark:text-white block">{{ \Carbon\Carbon::parse($schedule->exam_date)->format('D, M d, Y') }}</span>
                            @if($isUpcoming)
                                <span class="text-xs text-blue-600 dark:text-blue-400">Upcoming</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $schedule->subjectRelation->name ?? $schedule->subject }}</td>
                        <td class="px-6 py-4">{{ $schedule->exam_type }}</td>
                        <td class="px-6 py-4 flex items-center gap-1 text-gray-500">
                            <span class="material-symbols-rounded text-[16px]">schedule</span>
                            {{ $schedule->exam_time }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">event_note</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Exams Scheduled</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">There are no upcoming exams scheduled for your class.</p>
        </div>
    @endif
</div>
@endsection
