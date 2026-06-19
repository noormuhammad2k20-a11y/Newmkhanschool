@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Welcome back, {{ auth()->user()->student->first_name ?? auth()->user()->name }}!</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Here is what's happening with your academics today.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('student.timetable') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-fixed text-primary rounded-xl font-bold hover:bg-primary-fixed-dim transition-colors">
                    <span class="material-symbols-rounded text-[18px]">calendar_month</span> View Timetable
                </a>
                <a href="{{ route('student.leave.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-high border border-outline-variant text-on-surface rounded-xl font-bold hover:bg-surface-container-highest transition-colors">
                    <span class="material-symbols-rounded text-[18px]">event_busy</span> Leave Request
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            {{-- Attendance Card --}}
            <a href="{{ route('student.attendance') }}" class="block bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Attendance</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-rounded text-[18px]">co_present</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ $attendancePct ?? 0 }}%</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-label-md font-label-md text-secondary">
                    <span class="w-1.5 h-1.5 rounded-full {{ ($attendancePct ?? 0) >= 75 ? 'bg-success' : 'bg-error' }}"></span>
                    <span>{{ $presentDays ?? 0 }}/{{ $totalDays ?? 0 }} days present</span>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </a>

            {{-- Pending Fees Card --}}
            <a href="{{ route('student.fees') }}" class="block bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Pending Fees</h3>
                    <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                        <span class="material-symbols-rounded text-[18px]">account_balance_wallet</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-display-sm font-display-sm text-on-surface">Rs {{ number_format($pendingFees ?? 0, 0) }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-label-md font-label-md text-error font-bold">
                    <span class="material-symbols-rounded text-[14px]">warning</span>
                    <span>Action Required</span>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-error-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </a>
            
            {{-- Academic Performance --}}
            <a href="{{ route('student.marks') }}" class="block bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Overall GPA</h3>
                    <div class="w-8 h-8 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary">
                        <span class="material-symbols-rounded text-[18px]">school</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-display-sm font-display-sm text-on-surface">3.8</span>
                    <span class="text-label-md text-secondary">/ 4.0</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-label-md font-label-md text-tertiary font-bold">
                    <span class="material-symbols-rounded text-[14px]">trending_up</span>
                    <span>Top 10% of class</span>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-tertiary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </a>

            {{-- Assignments Pending --}}
            <a href="{{ route('student.assignments') }}" class="block bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Pending Tasks</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-rounded text-[18px]">assignment_late</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-display-sm font-display-sm text-on-surface">3</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-label-md font-label-md text-secondary">
                    <span class="material-symbols-rounded text-[14px]">schedule</span>
                    <span>Due this week</span>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </a>
        </div>

        <!-- Middle Section: Performance & Today's Timetable -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            {{-- Performance Trends --}}
            <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                <div class="p-4 border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-rounded text-primary">monitoring</span>
                        Performance Overview
                    </h3>
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex gap-4 mb-4">
                        <div class="bg-surface-container p-3 rounded-lg flex-1 border border-outline-variant text-center">
                            <span class="block text-secondary text-label-sm uppercase tracking-wider mb-1">Mathematics</span>
                            <span class="block text-title-lg font-bold text-primary">92%</span>
                        </div>
                        <div class="bg-surface-container p-3 rounded-lg flex-1 border border-outline-variant text-center">
                            <span class="block text-secondary text-label-sm uppercase tracking-wider mb-1">Science</span>
                            <span class="block text-title-lg font-bold text-success">88%</span>
                        </div>
                        <div class="bg-surface-container p-3 rounded-lg flex-1 border border-outline-variant text-center">
                            <span class="block text-secondary text-label-sm uppercase tracking-wider mb-1">English</span>
                            <span class="block text-title-lg font-bold text-tertiary">95%</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-highest rounded-lg flex-1 relative flex items-end p-4 border border-outline-variant gap-2 min-h-[150px]">
                        <!-- Mock Chart Bars -->
                        <div class="w-full flex justify-between items-end h-full gap-2 px-2">
                            <div class="w-full bg-primary/20 hover:bg-primary/40 transition-colors rounded-t-md relative group" style="height: 60%">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">Term 1</div>
                            </div>
                            <div class="w-full bg-primary/40 hover:bg-primary/60 transition-colors rounded-t-md relative group" style="height: 75%">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">Term 2</div>
                            </div>
                            <div class="w-full bg-primary hover:bg-primary/80 transition-colors rounded-t-md relative group" style="height: 90%">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">Mid Term</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Timetable --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                <div class="p-4 border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-rounded text-secondary">calendar_today</span>
                        Today's Classes
                    </h3>
                    <a href="{{ route('student.timetable') }}" class="text-primary text-label-md font-label-md hover:underline">View All</a>
                </div>
                <div class="p-0 overflow-y-auto max-h-[300px]">
                    @if(isset($todayClasses) && count($todayClasses) > 0)
                        <div class="divide-y divide-outline-variant">
                            @foreach($todayClasses as $period)
                                @php
                                    $isCurrent = \Carbon\Carbon::now()->between(\Carbon\Carbon::parse($period->start_time), \Carbon\Carbon::parse($period->end_time));
                                @endphp
                                <div class="p-4 flex gap-4 {{ $isCurrent ? 'bg-primary-fixed/20 border-l-4 border-l-primary' : 'hover:bg-surface-container transition-colors border-l-4 border-l-transparent' }}">
                                    <div class="flex flex-col items-center justify-center shrink-0 w-16 text-center">
                                        <span class="text-label-md font-bold text-on-surface">{{ \Carbon\Carbon::parse($period->start_time)->format('h:i') }}</span>
                                        <span class="text-[10px] text-secondary uppercase">{{ \Carbon\Carbon::parse($period->start_time)->format('A') }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-body-lg text-on-surface truncate">{{ $period->subjectRef->name ?? $period->subject }}</h4>
                                        <p class="text-label-md text-secondary truncate">{{ $period->teacher->full_name ?? $period->teacher }} • Room {{ $period->room_no ?? $period->room }}</p>
                                    </div>
                                    @if($isCurrent)
                                        <div class="shrink-0 flex items-center">
                                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-10 text-center text-secondary">
                            <span class="material-symbols-rounded text-[40px] mb-2 opacity-50">weekend</span>
                            <p class="text-body-md">No classes scheduled for today.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Learning Hub Section -->
        <div class="mt-xl">
            <div class="flex items-center gap-2 mb-md border-b border-outline-variant pb-2">
                <span class="material-symbols-rounded text-primary text-[28px]">local_library</span>
                <h3 class="text-headline-lg font-headline-lg text-on-surface">Learning Hub</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                {{-- Recent Digital Notes --}}
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                        <h4 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                            <span class="material-symbols-rounded text-primary">menu_book</span>
                            Recent Digital Notes
                        </h4>
                        <a href="{{ route('student.digital_learning.notes') }}" class="text-primary text-label-md hover:underline">View All</a>
                    </div>
                    <div class="p-4 space-y-3">
                        @forelse($recentNotes ?? [] as $note)
                            <div class="flex items-center gap-3 border border-outline-variant rounded-lg p-3 bg-surface-container hover:border-primary transition-colors">
                                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <span class="material-symbols-rounded text-[20px]">
                                        @if($note->file_type == 'pdf') picture_as_pdf
                                        @elseif(in_array($note->file_type, ['doc', 'text'])) description
                                        @elseif($note->file_type == 'ppt') slides
                                        @else link @endif
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="font-bold text-body-md text-on-surface truncate">{{ $note->title }}</h5>
                                    <p class="text-label-sm text-secondary truncate">{{ $note->subject->name ?? 'General' }} • By {{ $note->uploader->name ?? 'Teacher' }}</p>
                                </div>
                                @if($note->file_path)
                                    <a href="{{ Storage::url($note->file_path) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container-high text-primary hover:bg-primary hover:text-on-primary transition-colors shrink-0">
                                        <span class="material-symbols-rounded text-[16px]">download</span>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <p class="text-body-md text-secondary text-center py-4">No recent notes available.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Upcoming Quizzes --}}
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                        <h4 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                            <span class="material-symbols-rounded text-secondary-container-on">quiz</span>
                            Pending Quizzes
                        </h4>
                        <a href="{{ route('student.digital_learning.quizzes') }}" class="text-primary text-label-md hover:underline">View All</a>
                    </div>
                    <div class="p-4 space-y-3">
                        @forelse($upcomingQuizzes ?? [] as $quiz)
                            <div class="flex items-center gap-3 border border-outline-variant rounded-lg p-3 bg-surface-container hover:border-primary transition-colors">
                                <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center shrink-0">
                                    <span class="material-symbols-rounded text-[20px]">timer</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="font-bold text-body-md text-on-surface truncate">{{ $quiz->title }}</h5>
                                    <p class="text-label-sm text-secondary truncate">{{ $quiz->subject->name ?? 'General' }} • {{ $quiz->duration_minutes }} mins</p>
                                </div>
                                <a href="{{ route('student.digital_learning.quizzes.take', $quiz->id) }}" class="px-3 py-1 bg-primary text-on-primary text-label-sm font-bold rounded-lg hover:bg-primary/90 transition-colors shrink-0 whitespace-nowrap">
                                    Start
                                </a>
                            </div>
                        @empty
                            <p class="text-body-md text-secondary text-center py-4">No pending quizzes right now.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Assignments, Exams -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            {{-- Pending Assignments List --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                <div class="p-4 border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-rounded text-secondary-container-on">assignment</span>
                        Assignments
                    </h3>
                    <a href="{{ route('student.assignments') }}" class="text-primary text-label-md hover:underline">View All</a>
                </div>
                <div class="p-4 space-y-3">
                    <div class="border border-outline-variant rounded-lg p-3 bg-surface-container hover:border-primary transition-colors cursor-pointer">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-error-container text-error">Due Tomorrow</span>
                        </div>
                        <h4 class="font-bold text-body-md text-on-surface line-clamp-1">Physics Lab Report</h4>
                        <p class="text-label-sm text-secondary mt-1">Submit PDF document online</p>
                    </div>
                    <div class="border border-outline-variant rounded-lg p-3 bg-surface-container hover:border-primary transition-colors cursor-pointer">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-warning-container text-warning-on-container">Due in 3 Days</span>
                        </div>
                        <h4 class="font-bold text-body-md text-on-surface line-clamp-1">Math Assignment 4</h4>
                        <p class="text-label-sm text-secondary mt-1">Chapters 5 and 6</p>
                    </div>
                </div>
            </div>



            {{-- Upcoming Exams --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                <div class="p-4 border-b border-outline-variant bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-rounded text-error">edit_document</span>
                        Upcoming Exams
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($upcomingExams ?? [] as $exam)
                        <div class="flex items-center gap-3 border border-outline-variant rounded-lg p-3 bg-surface-container">
                            <div class="bg-error-container text-error rounded-lg p-2 text-center min-w-[50px] shrink-0">
                                <span class="block text-[10px] font-bold uppercase">{{ \Carbon\Carbon::parse($exam->exam_date)->format('M') }}</span>
                                <span class="block text-lg font-black leading-none">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-on-surface text-body-md truncate">{{ $exam->subjectRef->name ?? $exam->subject }}</h4>
                                <p class="text-label-md text-secondary mt-0.5 flex items-center gap-1">
                                    <span class="material-symbols-rounded text-[12px]">schedule</span> 
                                    {{ $exam->exam_time ?? 'TBA' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-body-md text-secondary text-center py-4">No upcoming exams.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
