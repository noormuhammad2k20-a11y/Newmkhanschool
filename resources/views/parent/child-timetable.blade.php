@extends('layouts.app')

@section('title', 'Child Timetable')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Class Timetable</h2>
                <p class="text-body-md font-body-md text-secondary mt-1">Viewing schedule for {{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
        <a href="{{ route('parent.dashboard') }}" class="bg-surface border border-outline-variant text-on-surface px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span>
            Back to Dashboard
        </a>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-lg mb-lg shadow-sm">


    @if(isset($timetables) && count($timetables) > 0)
        @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        @endphp
        
        <div class="space-y-xl">
            @foreach($days as $day)
                @php
                    $dayRoutines = collect($timetables)->filter(function($t) use ($day) {
                        return strcasecmp($t->day_of_week, $day) === 0;
                    })->sortBy('start_time');
                @endphp
                
                @if($dayRoutines->count() > 0)
                    <div>
                        <h3 class="font-headline-md text-headline-md font-semibold text-on-surface mb-md border-b border-outline-variant pb-2">{{ $day }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-md">
                            @foreach($dayRoutines as $routine)
                                <div class="bg-surface border border-outline-variant hover:border-primary rounded-lg p-md transition-colors group shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
                                        <span class="font-label-md text-label-md text-on-surface-variant">
                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                        </span>
                                    </div>
                                    <h4 class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary transition-colors">{{ $routine->subject->name ?? 'N/A' }}</h4>
                                    <div class="mt-md pt-md border-t border-outline-variant flex items-center justify-between">
                                        <div class="flex items-center gap-1.5 text-secondary font-body-md text-body-md">
                                            <span class="material-symbols-outlined text-[16px]">person</span>
                                            <span>{{ $routine->teacher->first_name ?? '' }} {{ $routine->teacher->last_name ?? '' }}</span>
                                        </div>
                                        <div class="font-label-md text-label-md text-on-surface-variant">Room {{ $routine->room_no }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-xl text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-low mb-4 text-secondary">
                <span class="material-symbols-outlined text-3xl">calendar_month</span>
            </div>
            <h3 class="text-headline-md font-headline-md text-on-surface">No Timetable Set</h3>
            <p class="text-body-md font-body-md text-secondary mt-1">The class timetable has not been published yet.</p>
        </div>
    @endif
    </div>
    </div>
</main>
@endsection
