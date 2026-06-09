@extends('layouts.app')
@section('title', 'Online Exams')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Online Exams</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage and attempt your scheduled online examinations.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">search</span>
                    <input type="text" placeholder="Search exams..." class="pl-10 pr-4 py-2 border border-outline-variant rounded-xl bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all w-64">
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-100 text-emerald-800 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 bg-blue-100 text-blue-800 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined">info</span>
                {{ session('info') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            {{-- Upcoming Exams Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Upcoming</h3>
                    <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                        <span class="material-symbols-outlined text-[18px]">event_upcoming</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $upcomingExams }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-error-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Active Exams Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Active</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">play_circle</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $activeExams }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Completed Exams Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Completed</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $completedExams }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Average Performance Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Avg Performance</h3>
                    <div class="w-8 h-8 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-[18px]">monitoring</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $averagePerformance }}%</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-tertiary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="flex items-center gap-4 border-b border-outline-variant pb-4 overflow-x-auto hide-scrollbar">
            <button class="px-4 py-1.5 rounded-full bg-primary text-on-primary text-label-md font-bold whitespace-nowrap">All Exams</button>
            <button class="px-4 py-1.5 rounded-full border border-outline-variant bg-surface-container-lowest text-secondary hover:bg-surface-container hover:text-on-surface transition-colors text-label-md font-bold whitespace-nowrap">Upcoming</button>
            <button class="px-4 py-1.5 rounded-full border border-outline-variant bg-surface-container-lowest text-secondary hover:bg-surface-container hover:text-on-surface transition-colors text-label-md font-bold whitespace-nowrap">Active</button>
            <button class="px-4 py-1.5 rounded-full border border-outline-variant bg-surface-container-lowest text-secondary hover:bg-surface-container hover:text-on-surface transition-colors text-label-md font-bold whitespace-nowrap">Completed</button>
        </div>

        <!-- Exams Grid -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-headline-md font-headline-md text-on-surface">Scheduled Exams</h3>
                <div class="flex items-center gap-2">
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-primary-fixed text-primary"><span class="material-symbols-outlined text-[20px]">grid_view</span></button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-surface-container hover:bg-surface-container-high text-secondary transition-colors"><span class="material-symbols-outlined text-[20px]">view_list</span></button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-md">
                @forelse($exams as $exam)
                    @php
                        $isAttempted = in_array($exam->id, $attemptedIds);
                        $attemptRecord = $isAttempted ? collect($allAttempts)->where('exam_id', $exam->id)->first() : null;
                    @endphp
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-primary transition-all duration-300 flex flex-col group overflow-hidden shadow-sm hover:shadow-md">
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-primary bg-primary-fixed px-2 py-0.5 rounded-full">{{ $exam->subject->name ?? 'General' }}</span>
                                @if($isAttempted)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                        Completed
                                    </span>
                                @elseif($exam->status === 'Active')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-surface-variant text-on-surface-variant">
                                        Upcoming
                                    </span>
                                @endif
                            </div>
                            
                            <h4 class="font-bold text-headline-sm text-on-surface line-clamp-2 mb-2 group-hover:text-primary transition-colors" title="{{ $exam->title }}">{{ $exam->title }}</h4>
                            
                            <div class="bg-surface-container p-3 rounded-lg border border-outline-variant mb-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">calendar_today</span>
                                    <span class="font-bold text-label-md text-on-surface">{{ \Carbon\Carbon::parse($exam->exam_date)->format('M d, Y') }} at {{ $exam->start_time }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-label-md text-secondary">
                                    <span class="material-symbols-outlined text-[16px]">timer</span>
                                    <span>{{ $exam->duration_minutes }} mins</span>
                                    <span class="mx-1">•</span>
                                    <span class="material-symbols-outlined text-[16px]">task</span>
                                    <span>{{ $exam->total_marks }} Marks</span>
                                </div>
                            </div>
                            
                            @if($isAttempted && $attemptRecord)
                                <div class="mt-auto bg-surface-container-low p-3 rounded-lg border border-outline-variant border-dashed text-center">
                                    <span class="block text-xs font-bold text-secondary mb-1">Your Score</span>
                                    <span class="text-title-md font-bold text-primary">{{ number_format($attemptRecord->percentage, 1) }}%</span>
                                    <span class="text-xs text-secondary ml-1">({{ $attemptRecord->obtained_marks }}/{{ $attemptRecord->total_marks }})</span>
                                </div>
                            @elseif($exam->status === 'Active' && !$isAttempted)
                                <div class="mt-auto bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded-lg text-center">
                                    <p class="text-label-md font-bold flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">play_circle</span> Exam is currently active
                                    </p>
                                </div>
                            @else
                                <div class="mt-auto text-center py-2 text-label-md text-secondary italic">
                                    Available soon
                                </div>
                            @endif
                        </div>
                        
                        <div class="bg-surface-bright border-t border-outline-variant p-3 flex gap-2">
                            @if($isAttempted)
                                <a href="{{ route('student.online-exams.result', $exam->id) }}" class="flex-1 py-2 bg-surface-container border border-outline-variant text-on-surface rounded-lg font-bold text-label-md hover:bg-surface-container-high transition-colors flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">assessment</span> View Result
                                </a>
                            @elseif($exam->status === 'Active')
                                <a href="{{ route('student.online-exams.take', $exam->id) }}" class="flex-1 py-2 bg-primary text-on-primary rounded-lg font-bold text-label-md hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">play_arrow</span> Start Exam
                                </a>
                            @else
                                <button class="flex-1 py-2 bg-surface-container border border-outline-variant text-on-surface-variant rounded-lg font-bold text-label-md cursor-not-allowed flex items-center justify-center gap-2" disabled>
                                    <span class="material-symbols-outlined text-[18px]">lock</span> Start Exam
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center bg-surface-container-lowest border border-outline-variant border-dashed rounded-xl">
                        <div class="w-16 h-16 bg-surface-variant rounded-full flex items-center justify-center text-secondary mb-4">
                            <span class="material-symbols-outlined text-[32px]">desktop_windows</span>
                        </div>
                        <h4 class="text-headline-md font-headline-md text-on-surface mb-1">No Exams Scheduled</h4>
                        <p class="text-body-md text-secondary text-center max-w-md">There are currently no online exams scheduled for your class.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
