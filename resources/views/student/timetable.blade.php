@extends('layouts.app')

@section('title', 'Class Timetable')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">My Timetable</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Weekly class schedule</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-high border border-outline-variant text-on-surface rounded-xl font-bold hover:bg-surface-container-highest transition-colors">
                    <span class="material-symbols-outlined text-[18px]">print</span> Print Schedule
                </button>
                <button onclick="alert('Export PDF coming soon!')" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-fixed text-primary rounded-xl font-bold hover:bg-primary-fixed-dim transition-colors">
                    <span class="material-symbols-outlined text-[18px]">download</span> Export PDF
                </button>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-6">
            @if(isset($timetables) && count($timetables) > 0)
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    $currentDay = \Carbon\Carbon::now()->format('l');
                @endphp
                
                <div class="space-y-8">
                    @foreach($days as $day)
                        @php
                            $dayRoutines = collect($timetables)->filter(function($t) use ($day) {
                                return strcasecmp($t->day_of_week, $day) === 0;
                            })->sortBy('start_time');
                            
                            $isToday = strcasecmp($day, $currentDay) === 0;
                        @endphp
                        
                        @if($dayRoutines->count() > 0 || $isToday)
                            <div class="{{ $isToday ? 'bg-primary-fixed/5 rounded-xl p-4 border border-primary/20 -mx-4 px-4' : '' }}">
                                <h3 class="text-headline-md font-headline-md text-on-surface mb-4 pb-2 flex items-center gap-2">
                                    {{ $day }} 
                                    @if($isToday) 
                                        <span class="bg-primary text-on-primary text-[10px] uppercase font-bold px-2 py-0.5 rounded-md">Today</span> 
                                    @endif
                                </h3>
                                
                                @if($dayRoutines->count() > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($dayRoutines as $routine)
                                            @php
                                                $isCurrentClass = $isToday && \Carbon\Carbon::now()->between(\Carbon\Carbon::parse($routine->start_time), \Carbon\Carbon::parse($routine->end_time));
                                            @endphp
                                            <div class="bg-surface-container border {{ $isCurrentClass ? 'border-primary shadow-md' : 'border-outline-variant' }} rounded-xl p-4 hover:border-primary transition-colors flex flex-col relative overflow-hidden group">
                                                @if($isCurrentClass)
                                                    <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
                                                    <div class="absolute top-3 right-3 flex items-center gap-1.5">
                                                        <span class="relative flex h-2.5 w-2.5">
                                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary"></span>
                                                        </span>
                                                        <span class="text-[10px] text-primary font-bold uppercase tracking-wider">Now</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center gap-1.5 bg-surface-bright px-2 py-1 rounded-md border {{ $isCurrentClass ? 'border-primary/30 text-primary' : 'border-outline-variant text-on-surface-variant' }}">
                                                        <span class="material-symbols-outlined text-[14px] {{ $isCurrentClass ? 'text-primary' : '' }}">schedule</span>
                                                        <span class="text-[11px] font-bold">
                                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <h4 class="font-bold text-body-lg text-on-surface mb-4 flex-1 pr-12">{{ $routine->subjectRef->name ?? $routine->subject ?? 'N/A' }}</h4>
                                                
                                                <div class="flex flex-col gap-1.5 text-label-md font-label-md {{ $isCurrentClass ? 'text-on-surface' : 'text-secondary' }} border-t border-outline-variant pt-3 mt-auto">
                                                    <div class="flex items-center gap-2 truncate">
                                                        <span class="material-symbols-outlined text-[16px] {{ $isCurrentClass ? 'text-primary' : '' }}">person</span>
                                                        <span class="truncate font-medium">{{ $routine->teacher->first_name ?? '' }} {{ $routine->teacher->last_name ?? '' }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 truncate">
                                                        <span class="material-symbols-outlined text-[16px] {{ $isCurrentClass ? 'text-primary' : '' }}">room</span>
                                                        <span class="font-medium">Room {{ $routine->room ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                @if($isCurrentClass)
                                                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-primary opacity-5 rounded-full z-0 group-hover:scale-150 transition-transform duration-700"></div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-surface-container border border-outline-variant border-dashed rounded-xl p-8 text-center text-secondary">
                                        <p>No classes scheduled for today.</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center text-secondary border border-outline-variant border-dashed rounded-xl m-4">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">calendar_month</span>
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-1">No Timetable Set</h3>
                    <p class="text-body-lg font-body-lg">Your class timetable has not been published yet.</p>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
