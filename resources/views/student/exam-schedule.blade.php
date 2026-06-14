@extends('layouts.app')

@section('title', 'Exam Schedule')

@section('content')
<main class="p-lg md:p-xl w-full max-w-7xl mx-auto space-y-xl">
    
    {{-- Header with Action --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
        <div>
            <h1 class="font-display-md text-display-md font-bold text-on-surface">Exam Schedule</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant mt-1">Track your upcoming examinations, timings, and subjects.</p>
        </div>
        <button onclick="window.print()" class="btn-outline flex items-center gap-2 hidden md:flex">
            <span class="material-symbols-outlined text-[20px]">print</span>
            Print Schedule
        </button>
    </div>

    @if(isset($schedules) && count($schedules) > 0)
        
        @php
            // Find the most immediate upcoming exam
            $now = \Carbon\Carbon::now()->startOfDay();
            $upcomingExams = $schedules->filter(fn($s) => \Carbon\Carbon::parse($s->exam_date)->startOfDay()->gte($now))->sortBy('exam_date');
            $nextExam = $upcomingExams->first();
        @endphp

        @if($nextExam)
            {{-- Upcoming Exam Hero Card --}}
            <div class="card p-0 overflow-hidden bg-primary-container text-on-primary-container border-0 shadow-md">
                <div class="flex flex-col md:flex-row">
                    <div class="p-xl flex-1 flex flex-col justify-center">
                        <div class="flex items-center gap-2 font-label-md uppercase tracking-wider mb-sm opacity-80 font-bold">
                            <span class="material-symbols-outlined text-[20px] animate-pulse">campaign</span>
                            Next Upcoming Exam
                        </div>
                        <h2 class="font-display-md text-display-md font-black mb-1">{{ $nextExam->subjectRelation->name ?? $nextExam->subject }}</h2>
                        <p class="font-title-md text-title-md opacity-90 mb-md">{{ $nextExam->exam_type }}</p>
                        
                        <div class="flex flex-wrap gap-md mt-auto">
                            <div class="flex items-center gap-2 bg-on-primary-container/10 px-md py-sm rounded-lg">
                                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                                <span class="font-label-lg font-bold">{{ \Carbon\Carbon::parse($nextExam->exam_date)->format('l, d F Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 bg-on-primary-container/10 px-md py-sm rounded-lg">
                                <span class="material-symbols-outlined text-[20px]">schedule</span>
                                <span class="font-label-lg font-bold">{{ $nextExam->exam_time }}</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Countdown Badge --}}
                    @php
                        $daysDiff = $now->diffInDays(\Carbon\Carbon::parse($nextExam->exam_date)->startOfDay(), false);
                    @endphp
                    <div class="bg-on-primary-container text-primary-container p-xl flex flex-col items-center justify-center md:w-64">
                        <span class="font-display-lg text-display-lg font-black leading-none">{{ $daysDiff == 0 ? 'Today' : daysDiff }}</span>
                        <span class="font-label-md text-label-md uppercase tracking-widest mt-2 font-bold">{{ $daysDiff == 0 ? '' : ($daysDiff == 1 ? 'Day Left' : 'Days Left') }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Schedule Grid / Timeline --}}
        <div>
            <h3 class="font-title-lg text-title-lg font-bold text-on-surface mb-md flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">view_timeline</span>
                Full Examination Schedule
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
                @foreach($schedules as $schedule)
                @php
                    $examDate = \Carbon\Carbon::parse($schedule->exam_date)->startOfDay();
                    $status = $schedule->getStudentStatus(auth()->user()->student);
                @endphp
                <div id="exam-card-{{ $schedule->id }}" class="card p-lg bg-surface-container-lowest border border-outline-variant flex flex-col hover:shadow-md transition-shadow {{ in_array($status, ['Completed', 'Absent / Missed']) ? 'opacity-60 grayscale' : '' }}">
                    <div class="flex justify-between items-start mb-md">
                        <div class="bg-surface-container-high rounded-lg text-center px-4 py-2 border border-outline-variant/50 shadow-sm">
                            <span class="block font-label-md text-error font-bold uppercase tracking-wide">{{ $examDate->format('M') }}</span>
                            <span class="block font-display-sm font-black text-on-surface">{{ $examDate->format('d') }}</span>
                        </div>
                        
                        <div class="exam-status-container" data-exam-id="{{ $schedule->id }}">
                            @if($status === 'Scheduled')
                                <span class="bg-surface-variant text-on-surface-variant text-[10px] uppercase font-bold px-2 py-1 rounded-full">Scheduled</span>
                            @elseif($status === 'In Progress')
                                <span class="bg-primary/10 text-primary border border-primary/20 text-[10px] uppercase font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span> In Progress
                                </span>
                            @elseif($status === 'Pending Results')
                                <span class="bg-orange-100 text-orange-700 border border-orange-200 text-[10px] uppercase font-bold px-2 py-1 rounded-full">Pending Results</span>
                            @elseif($status === 'Completed')
                                <span class="bg-secondary/10 text-secondary border border-secondary/20 text-[10px] uppercase font-bold px-2 py-1 rounded-full">Completed</span>
                            @elseif($status === 'Absent / Missed')
                                <span class="bg-error/10 text-error border border-error/20 text-[10px] uppercase font-bold px-2 py-1 rounded-full">Absent / Missed</span>
                            @endif
                        </div>
                    </div>
                    
                    <h4 class="font-title-lg text-title-lg font-bold text-on-surface mb-xs truncate">{{ $schedule->subjectRelation->name ?? $schedule->subject }}</h4>
                    <p class="font-label-md text-label-md text-primary font-semibold mb-md">{{ $schedule->exam_type }}</p>
                    
                    <div class="mt-auto pt-md border-t border-outline-variant flex items-center justify-between text-on-surface-variant font-label-md">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            {{ $schedule->exam_time }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">meeting_room</span>
                            {{ $schedule->room_no ?? 'TBA' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    @else
        <div class="card p-2xl text-center border-dashed border-outline-variant bg-surface-container-lowest">
            <div class="flex flex-col items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined text-[64px] mb-md opacity-50">event_busy</span>
                <h3 class="font-headline-md text-headline-md text-on-surface font-bold">No Exams Scheduled</h3>
                <p class="font-body-lg text-body-lg mt-sm max-w-md">There are no upcoming exams scheduled for your class at the moment. Take this time to prepare!</p>
            </div>
        </div>
    @endif

    {{-- Instructions Block --}}
    <div class="bg-tertiary-container text-on-tertiary-container rounded-xl p-lg border border-tertiary/20 shadow-sm print:hidden">
        <h3 class="font-title-md text-title-md font-bold mb-md flex items-center gap-2">
            <span class="material-symbols-outlined">info</span>
            Examination Instructions
        </h3>
        <ul class="list-disc list-inside space-y-sm font-body-md opacity-90">
            <li>Students must arrive at the examination hall at least 15 minutes before the scheduled start time.</li>
            <li>Valid Student ID card is mandatory for entry into the examination hall.</li>
            <li>Electronic devices (mobile phones, smartwatches) are strictly prohibited inside the hall.</li>
            <li>Students arriving more than 30 minutes late will not be permitted to take the exam.</li>
        </ul>
    </div>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fetchInterval = 10000; // 10 seconds

        function fetchExamStatuses() {
            fetch("{{ route('student.api.exam-statuses') }}")
                .then(response => response.json())
                .then(statuses => {
                    Object.keys(statuses).forEach(id => {
                        const status = statuses[id];
                        const container = document.querySelector(`.exam-status-container[data-exam-id="${id}"]`);
                        const card = document.getElementById(`exam-card-${id}`);
                        
                        if (container) {
                            let badgeHtml = '';
                            if (status === 'Scheduled') {
                                badgeHtml = `<span class="bg-surface-variant text-on-surface-variant text-[10px] uppercase font-bold px-2 py-1 rounded-full">Scheduled</span>`;
                            } else if (status === 'In Progress') {
                                badgeHtml = `<span class="bg-primary/10 text-primary border border-primary/20 text-[10px] uppercase font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span> In Progress
                                </span>`;
                            } else if (status === 'Pending Results') {
                                badgeHtml = `<span class="bg-orange-100 text-orange-700 border border-orange-200 text-[10px] uppercase font-bold px-2 py-1 rounded-full">Pending Results</span>`;
                            } else if (status === 'Completed') {
                                badgeHtml = `<span class="bg-secondary/10 text-secondary border border-secondary/20 text-[10px] uppercase font-bold px-2 py-1 rounded-full">Completed</span>`;
                            } else if (status === 'Absent / Missed') {
                                badgeHtml = `<span class="bg-error/10 text-error border border-error/20 text-[10px] uppercase font-bold px-2 py-1 rounded-full">Absent / Missed</span>`;
                            }
                            
                            // Only update DOM if the HTML actually changed
                            if (container.innerHTML.trim() !== badgeHtml.trim() && badgeHtml !== '') {
                                container.innerHTML = badgeHtml;
                            }
                        }

                        if (card) {
                            if (status === 'Completed' || status === 'Absent / Missed') {
                                card.classList.add('opacity-60', 'grayscale');
                            } else {
                                card.classList.remove('opacity-60', 'grayscale');
                            }
                        }
                    });
                })
                .catch(err => console.error("Error fetching exam statuses:", err));
        }

        setInterval(fetchExamStatuses, fetchInterval);
    });
</script>

<style>
    @media print {
        body { background-color: white !important; }
        nav, header, .sidebar, button, .print\:hidden { display: none !important; }
        main { padding: 0 !important; max-width: 100% !important; margin: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #e5e7eb !important; page-break-inside: avoid; }
    }
</style>
@endsection
