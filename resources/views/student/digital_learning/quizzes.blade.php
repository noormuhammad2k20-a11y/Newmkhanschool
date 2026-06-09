@extends('layouts.app')
@section('title', 'Quizzes & Assessments')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Quizzes & Assessments</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Test your knowledge and track your progress.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">search</span>
                    <input type="text" placeholder="Search quizzes..." class="pl-10 pr-4 py-2 border border-outline-variant rounded-xl bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all w-64">
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-100 text-emerald-800 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-100 text-red-800 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            {{-- Available Quizzes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Available</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">quiz</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $availableQuizzes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Completed Quizzes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Completed</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">task_alt</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $completedQuizzes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Average Score Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Avg Score</h3>
                    <div class="w-8 h-8 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-[18px]">monitoring</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $averageScore }}%</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-tertiary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Upcoming Quizzes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending</h3>
                    <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                        <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $upcomingQuizzes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-error-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="flex items-center gap-4 border-b border-outline-variant pb-4 overflow-x-auto hide-scrollbar">
            <button class="px-4 py-1.5 rounded-full bg-primary text-on-primary text-label-md font-bold whitespace-nowrap">All Quizzes</button>
            <button class="px-4 py-1.5 rounded-full border border-outline-variant bg-surface-container-lowest text-secondary hover:bg-surface-container hover:text-on-surface transition-colors text-label-md font-bold whitespace-nowrap">Pending</button>
            <button class="px-4 py-1.5 rounded-full border border-outline-variant bg-surface-container-lowest text-secondary hover:bg-surface-container hover:text-on-surface transition-colors text-label-md font-bold whitespace-nowrap">Completed</button>
            <div class="w-px h-6 bg-outline-variant mx-2"></div>
            @php $uniqueSubjects = $quizzes->pluck('subject.name')->unique()->filter(); @endphp
            @foreach($uniqueSubjects as $subjName)
                <button class="px-4 py-1.5 rounded-full border border-outline-variant bg-surface-container-lowest text-secondary hover:bg-surface-container hover:text-on-surface transition-colors text-label-md font-bold whitespace-nowrap">{{ $subjName }}</button>
            @endforeach
        </div>

        <!-- Quizzes Grid -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-headline-md font-headline-md text-on-surface">Your Quizzes</h3>
                <div class="flex items-center gap-2">
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-primary-fixed text-primary"><span class="material-symbols-outlined text-[20px]">grid_view</span></button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-surface-container hover:bg-surface-container-high text-secondary transition-colors"><span class="material-symbols-outlined text-[20px]">view_list</span></button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
                @forelse($quizzes as $quiz)
                    @php
                        $attempt = $attempts->get($quiz->id);
                        $isPassed = $attempt ? ($attempt->score >= $quiz->passing_marks) : false;
                    @endphp
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-primary transition-all duration-300 flex flex-col group overflow-hidden shadow-sm hover:shadow-md">
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-primary bg-primary-fixed px-2 py-0.5 rounded-full">{{ $quiz->subject->name ?? 'General' }}</span>
                                @if($attempt)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $isPassed ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $isPassed ? 'Passed' : 'Failed' }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-surface-variant text-on-surface-variant">Pending</span>
                                @endif
                            </div>
                            
                            <h4 class="font-bold text-headline-sm text-on-surface line-clamp-2 mb-4 group-hover:text-primary transition-colors" title="{{ $quiz->title }}">{{ $quiz->title }}</h4>
                            
                            <div class="grid grid-cols-2 gap-4 mb-5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] text-secondary uppercase tracking-wider">Duration</span>
                                    <span class="font-bold text-body-lg text-on-surface flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-primary">timer</span>
                                        {{ $quiz->duration_minutes }}m
                                    </span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] text-secondary uppercase tracking-wider">Passing</span>
                                    <span class="font-bold text-body-lg text-on-surface flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-secondary-container-on">rule</span>
                                        {{ $quiz->passing_marks }}/{{ $quiz->total_marks }}
                                    </span>
                                </div>
                            </div>

                            @if($attempt)
                                <div class="mt-auto bg-surface-container-low p-3 rounded-lg border border-outline-variant border-dashed">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-secondary">Your Score</span>
                                        <span class="text-label-md font-bold {{ $isPassed ? 'text-emerald-600' : 'text-error' }}">{{ number_format($attempt->percentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-surface-variant rounded-full overflow-hidden">
                                        <div class="h-full {{ $isPassed ? 'bg-emerald-500' : 'bg-error' }}" style="width: {{ $attempt->percentage }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-secondary mt-2 text-center">Attempted on {{ $attempt->submitted_at ? \Carbon\Carbon::parse($attempt->submitted_at)->format('M d, Y') : 'Unknown' }}</p>
                                </div>
                            @else
                                <div class="mt-auto"></div>
                            @endif
                        </div>
                        
                        <div class="bg-surface-bright border-t border-outline-variant p-3 flex gap-2">
                            @if($attempt)
                                <button class="flex-1 py-2 bg-surface-container border border-outline-variant text-on-surface rounded-lg font-bold text-label-md hover:bg-surface-container-high transition-colors flex items-center justify-center gap-2" disabled>
                                    <span class="material-symbols-outlined text-[18px]">done_all</span> Completed
                                </button>
                                <button class="flex-1 py-2 bg-primary-fixed text-primary rounded-lg font-bold text-label-md hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-2">
                                    View Result
                                </button>
                            @else
                                <a href="{{ route('student.digital_learning.quizzes.take', $quiz->id) }}" class="flex-1 py-2 bg-primary text-on-primary rounded-lg font-bold text-label-md hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">play_arrow</span> Start Quiz
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center bg-surface-container-lowest border border-outline-variant border-dashed rounded-xl">
                        <div class="w-16 h-16 bg-surface-variant rounded-full flex items-center justify-center text-secondary mb-4">
                            <span class="material-symbols-outlined text-[32px]">quiz</span>
                        </div>
                        <h4 class="text-headline-md font-headline-md text-on-surface mb-1">No Quizzes Available</h4>
                        <p class="text-body-md text-secondary text-center max-w-md">There are currently no active quizzes assigned to your class.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
