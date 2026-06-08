@extends('layouts.app')

@section('title', 'Child Marks')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Academic Marks</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Viewing marks for {{ $student->first_name }} {{ $student->last_name }}</p>
    </div>
    <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
        <span class="material-symbols-rounded text-[18px] mr-1">arrow_back</span>
        Back to Dashboard
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if(isset($marks) && count($marks) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 font-medium border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Exam Type</th>
                        <th class="px-6 py-4">Marks Obtained</th>
                        <th class="px-6 py-4">Total Marks</th>
                        <th class="px-6 py-4">Percentage</th>
                        <th class="px-6 py-4">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($marks as $mark)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $mark->subject->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $mark->examType->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $mark->marks_obtained }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $mark->total_marks }}</td>
                        <td class="px-6 py-4">
                            @php
                                $percent = $mark->total_marks > 0 ? round(($mark->marks_obtained / $mark->total_marks) * 100, 2) : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <span>{{ $percent }}%</span>
                                <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700">
                                    <div class="h-full {{ $percent >= 50 ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $mark->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">assignment</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Marks Found</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">There are no academic marks published for this student yet.</p>
        </div>
    @endif
</div>
@endsection
