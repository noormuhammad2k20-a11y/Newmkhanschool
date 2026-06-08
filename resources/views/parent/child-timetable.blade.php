@extends('layouts.app')

@section('title', 'Child Timetable')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Class Timetable</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Viewing schedule for {{ $student->first_name }} {{ $student->last_name }}</p>
    </div>
    <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
        <span class="material-symbols-rounded text-[18px] mr-1">arrow_back</span>
        Back to Dashboard
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden p-6">
    @if(isset($timetables) && count($timetables) > 0)
        @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        @endphp
        
        <div class="space-y-8">
            @foreach($days as $day)
                @php
                    $dayRoutines = collect($timetables)->filter(function($t) use ($day) {
                        return strcasecmp($t->day_of_week, $day) === 0;
                    })->sortBy('start_time');
                @endphp
                
                @if($dayRoutines->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">{{ $day }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($dayRoutines as $routine)
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700/50 hover:shadow-md transition-shadow">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="material-symbols-rounded text-blue-500 text-sm">schedule</span>
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-base">{{ $routine->subject->name ?? 'N/A' }}</h4>
                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-1.5 text-gray-600 dark:text-gray-300">
                                            <span class="material-symbols-rounded text-[16px]">person</span>
                                            <span>{{ $routine->teacher->first_name ?? '' }} {{ $routine->teacher->last_name ?? '' }}</span>
                                        </div>
                                        <div class="text-gray-500 font-medium">Room {{ $routine->room_no }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">calendar_month</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Timetable Set</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">The class timetable has not been published yet.</p>
        </div>
    @endif
</div>
@endsection
