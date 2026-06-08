@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Welcome back, {{ auth()->user()->student->first_name ?? auth()->user()->name }}!</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Here is what's happening with your academics today.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            {{-- Attendance Card --}}
            <a href="{{ route('student.timetable') }}" class="block bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Attendance</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">co_present</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $attendancePct ?? 0 }}%</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                    <span>{{ $presentDays ?? 0 }}/{{ $totalDays ?? 0 }} days</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </a>

            {{-- Pending Fees Card --}}
            <a href="{{ route('student.fees') }}" class="block bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending Fees</h3>
                    <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                        <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">Rs {{ number_format($pendingFees ?? 0, 2) }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-error-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </a>
            
            {{-- Assignments Card --}}
            <a href="{{ route('student.assignments') }}" class="block bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Assignments</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[18px]">assignment</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">Active</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </a>

            {{-- Report Card --}}
            <a href="{{ route('student.report-card') }}" class="block bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Report Card</h3>
                    <div class="w-8 h-8 rounded-lg bg-surface-variant flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[18px]">grade</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">View</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-surface-variant rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </a>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            {{-- Today's Classes --}}
            <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">calendar_today</span>
                        Today's Timetable
                    </h3>
                    <a href="{{ route('student.timetable') }}" class="text-primary text-label-md font-label-md hover:underline">Full Schedule</a>
                </div>
                <div class="p-0">
                    @if(isset($todayClasses) && count($todayClasses) > 0)
                        <ul class="divide-y divide-outline-variant">
                            @foreach($todayClasses as $period)
                            <li class="p-md hover:bg-surface-container-lowest transition-colors flex items-center gap-4">
                                <div class="w-24 text-center shrink-0">
                                    <span class="block text-body-md font-bold text-on-surface">{{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }}</span>
                                    <span class="block text-label-md text-secondary">{{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}</span>
                                </div>
                                <div class="w-1 bg-primary h-10 rounded-full shrink-0"></div>
                                <div>
                                    <h4 class="font-bold text-on-surface text-body-lg">{{ $period->subjectRef->name ?? $period->subject }}</h4>
                                    <div class="flex items-center gap-3 text-label-md text-secondary mt-1">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">person</span> {{ $period->teacher->full_name ?? $period->teacher }}</span>
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">room</span> Room {{ $period->room_no ?? $period->room }}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-12 text-center text-secondary">
                            <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">weekend</span>
                            <p class="text-body-lg font-body-lg">No classes scheduled for today.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Announcements & Exams --}}
            <div class="space-y-md flex flex-col">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                    <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">campaign</span>
                            Announcements
                        </h3>
                        <a href="{{ route('student.announcements') }}" class="text-primary text-label-md font-label-md hover:underline">View All</a>
                    </div>
                    <div class="p-md space-y-4">
                        @forelse($announcements ?? [] as $ann)
                            <div class="border-l-2 border-secondary-container pl-3">
                                <h4 class="font-medium text-on-surface text-body-md mb-1 line-clamp-1">{{ $ann->title }}</h4>
                                <p class="text-label-md text-secondary line-clamp-2">{{ Str::limit($ann->content, 120) }}</p>
                                <span class="text-[10px] text-outline mt-1 block">{{ $ann->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <p class="text-body-md text-secondary text-center py-4">No recent announcements.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden flex-1">
                    <div class="p-md border-b border-outline-variant bg-surface-bright">
                        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-error">edit_document</span>
                            Upcoming Exams
                        </h3>
                    </div>
                    <div class="p-md space-y-4">
                        @forelse($upcomingExams ?? [] as $exam)
                            <div class="flex items-start gap-3">
                                <div class="bg-error-container text-error rounded-lg p-2 text-center min-w-[50px] shrink-0 border border-error-container">
                                    <span class="block text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($exam->exam_date)->format('M') }}</span>
                                    <span class="block text-lg font-black leading-none">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-on-surface text-body-md">{{ $exam->subjectRef->name ?? $exam->subject }}</h4>
                                    <p class="text-label-md text-secondary mt-0.5">{{ $exam->exam_time ?? 'TBA' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-body-md text-secondary text-center py-4">No upcoming exams.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
