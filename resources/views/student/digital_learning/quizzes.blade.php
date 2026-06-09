@extends('layouts.app')

@section('title', 'Interactive Quizzes')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Assessment Center</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Test your knowledge, take active quizzes, and review your past performance.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-md bg-emerald-100 text-emerald-800 rounded-xl border border-emerald-200 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span class="text-body-md font-body-md font-semibold">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-error-container text-error p-md rounded-xl flex items-center gap-2 border border-error/20 shadow-sm">
                <span class="material-symbols-outlined">error</span>
                <span class="text-body-md font-body-md">{{ session('error') }}</span>
            </div>
        @endif

        @php
            $activeQuizzes = $quizzes->filter(fn($q) => !isset($attempts[$q->id]));
            $completedQuizzes = $quizzes->filter(fn($q) => isset($attempts[$q->id]));
            $avgScore = $completedQuizzes->count() > 0
                ? round($completedQuizzes->map(fn($q) => $attempts[$q->id]->percentage)->avg(), 1)
                : 0;
        @endphp

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            {{-- Total Quizzes --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Quizzes</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">quiz</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $quizzes->count() }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                    <span>Available assessments</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Pending --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-secondary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $activeQuizzes->count() }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                    <span>Awaiting attempt</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Completed --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-emerald-600 transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Completed</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">task_alt</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $completedQuizzes->count() }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-emerald-700">
                    <span class="material-symbols-outlined text-[14px]">check</span>
                    <span>Finished quizzes</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Average Score --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-tertiary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Avg Score</h3>
                    <div class="w-8 h-8 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-[18px]">analytics</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $avgScore }}%</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-tertiary font-bold">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    <span>Overall average</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-tertiary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        {{-- Pending Assessments Section --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">play_circle</span>
                    Pending Assessments
                </h3>
                @if($activeQuizzes->count() > 0)
                    <span class="text-label-md font-label-md text-secondary">{{ $activeQuizzes->count() }} quiz{{ $activeQuizzes->count() > 1 ? 'zes' : '' }}</span>
                @endif
            </div>
            <div class="p-xl">
                @if($activeQuizzes->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
                        @foreach($activeQuizzes as $quiz)
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col hover:-translate-y-1 hover:shadow-md transition-all duration-300 group overflow-hidden">
                            {{-- Top accent bar --}}
                            <div class="h-1 w-full bg-primary"></div>

                            <div class="p-xl flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-sm">
                                    <div class="flex items-center gap-2 text-primary text-label-md font-label-md font-bold">
                                        <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                        {{ $quiz->subject->name ?? 'Subject' }}
                                    </div>
                                    <span class="bg-primary text-on-primary text-[10px] uppercase font-bold px-2.5 py-1 rounded-md animate-pulse">Active</span>
                                </div>

                                <h3 class="text-title-lg font-title-lg font-bold text-on-surface mb-sm group-hover:text-primary transition-colors">{{ $quiz->title }}</h3>
                                <p class="text-body-md font-body-md text-secondary line-clamp-2 mb-lg flex-1">{{ $quiz->description }}</p>

                                <div class="grid grid-cols-2 gap-sm mb-lg">
                                    <div class="bg-surface border border-outline-variant/50 rounded-xl p-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-secondary text-[20px]">timer</span>
                                        <div>
                                            <span class="block text-label-sm font-label-sm text-secondary">Duration</span>
                                            <span class="block text-title-sm font-title-sm font-bold text-on-surface">{{ $quiz->duration_minutes }} mins</span>
                                        </div>
                                    </div>
                                    <div class="bg-surface border border-outline-variant/50 rounded-xl p-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-tertiary text-[20px]">military_tech</span>
                                        <div>
                                            <span class="block text-label-sm font-label-sm text-secondary">Total Marks</span>
                                            <span class="block text-title-sm font-title-sm font-bold text-on-surface">{{ $quiz->total_marks }} pts</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-md border-t border-outline-variant bg-surface-bright">
                                <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="w-full py-2.5 px-4 bg-primary text-on-primary rounded-lg text-label-md font-label-md font-bold text-center hover:opacity-90 transition-opacity flex items-center justify-center gap-2 shadow-sm">
                                    Start Assessment
                                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-center py-xl">
                        <div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mb-md">
                            <span class="material-symbols-outlined text-[40px] text-secondary opacity-50">task_alt</span>
                        </div>
                        <h3 class="text-title-lg font-title-lg font-bold text-on-surface">You're all caught up!</h3>
                        <p class="text-body-md font-body-md text-secondary mt-1">There are no pending quizzes for you to take right now.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Completed Assessments Section --}}
        @if($completedQuizzes->count() > 0)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-700">history</span>
                    Completed Assessments
                </h3>
                <span class="text-label-md font-label-md text-secondary">{{ $completedQuizzes->count() }} result{{ $completedQuizzes->count() > 1 ? 's' : '' }}</span>
            </div>
            <div class="p-xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    @foreach($completedQuizzes as $quiz)
                    @php 
                        $attempt = $attempts[$quiz->id]; 
                        $isPass = $attempt->percentage >= 40;
                    @endphp
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col sm:flex-row overflow-hidden hover:shadow-md transition-shadow">
                        
                        {{-- Score Visual --}}
                        <div class="{{ $isPass ? 'bg-emerald-50 text-emerald-700' : 'bg-error-container text-error' }} p-xl flex flex-col items-center justify-center sm:w-48 border-b sm:border-b-0 sm:border-r border-outline-variant/30">
                            <div class="relative w-24 h-24 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" class="opacity-20" />
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" stroke-dasharray="{{ 2 * pi() * 45 }}" stroke-dashoffset="{{ 2 * pi() * 45 * (1 - $attempt->percentage/100) }}" class="transition-all duration-1000 ease-out" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-display-sm font-display-sm font-black leading-none">{{ round($attempt->percentage) }}%</span>
                                </div>
                            </div>
                            <span class="text-label-md font-label-md font-bold uppercase tracking-widest mt-3">
                                {{ $isPass ? 'Passed' : 'Failed' }}
                            </span>
                        </div>

                        {{-- Quiz Info --}}
                        <div class="p-xl flex-1 flex flex-col justify-center">
                            <div class="flex items-center gap-2 text-primary text-label-sm font-label-sm uppercase font-bold tracking-wider mb-1">
                                <span class="material-symbols-outlined text-[16px]">menu_book</span>
                                {{ $quiz->subject->name ?? 'Subject' }}
                            </div>
                            <h3 class="text-headline-sm font-headline-sm font-bold text-on-surface mb-md">{{ $quiz->title }}</h3>
                            
                            <div class="grid grid-cols-2 gap-md">
                                <div>
                                    <span class="block text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-1">Score Achieved</span>
                                    <span class="block text-title-md font-title-md font-bold text-on-surface">{{ $attempt->score }} <span class="text-secondary font-normal text-sm">/ {{ $quiz->total_marks }}</span></span>
                                </div>
                                <div>
                                    <span class="block text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-1">Completed On</span>
                                    <span class="block text-title-md font-title-md font-bold text-on-surface">{{ \Carbon\Carbon::parse($attempt->submitted_at)->format('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
</main>
@endsection
