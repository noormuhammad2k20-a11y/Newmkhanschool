@extends('layouts.app')

@section('title', 'Timetable')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Weekly Timetable</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Your complete teaching schedule.</p>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold border-r border-outline-variant">Time</th>
                            <th class="py-3 px-4 font-semibold border-r border-outline-variant">Monday</th>
                            <th class="py-3 px-4 font-semibold border-r border-outline-variant">Tuesday</th>
                            <th class="py-3 px-4 font-semibold border-r border-outline-variant">Wednesday</th>
                            <th class="py-3 px-4 font-semibold border-r border-outline-variant">Thursday</th>
                            <th class="py-3 px-4 font-semibold border-r border-outline-variant">Friday</th>
                            <th class="py-3 px-4 font-semibold">Saturday</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @php
                            // Organize timetable by time slots for easier rendering
                            // In a real app, this logic would ideally be in the controller
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            $timeSlots = $timetable->pluck('start_time')->unique()->sort();
                        @endphp
                        
                        @forelse($timeSlots as $time)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-medium text-secondary border-r border-outline-variant whitespace-nowrap">{{ \Carbon\Carbon::parse($time)->format('h:i A') }}</td>
                            @foreach($days as $day)
                                @php
                                    $slot = $timetable->where('day_of_week', $day)->where('start_time', $time)->first();
                                @endphp
                                <td class="py-3 px-4 border-r border-outline-variant text-center">
                                    @if($slot)
                                        <div class="bg-primary-fixed text-on-primary-fixed rounded p-2 shadow-sm border border-primary-fixed-dim text-sm">
                                            <p class="font-bold">{{ $slot->subject }}</p>
                                            <p>{{ $slot->class_name }}</p>
                                            <p class="text-xs mt-1">Room: {{ $slot->room }}</p>
                                        </div>
                                    @else
                                        <span class="text-secondary text-sm">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-secondary">
                                No classes scheduled in your timetable.
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
