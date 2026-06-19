@extends('layouts.app')
@section('title', 'Take Quiz - ' . $quiz->title)

@section('content')
<!-- Quiz Background Header -->
<div class="bg-surface-container-low border-b border-outline-variant pt-8 pb-12 px-6">
    <div class="max-w-[1440px] mx-auto flex items-center gap-4">
        <a href="{{ route('student.digital_learning.quizzes') }}" class="w-12 h-12 flex items-center justify-center rounded-full bg-surface-container-highest hover:bg-outline-variant transition-colors text-on-surface" title="Leave Quiz">
            <span class="material-symbols-rounded text-[24px]">arrow_back</span>
        </a>
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-primary bg-primary-fixed px-2 py-0.5 rounded-full">{{ $quiz->subject->name ?? 'General' }}</span>
            </div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">{{ $quiz->title }}</h1>
        </div>
    </div>
</div>

<main class="flex-1 p-margin-desktop bg-background -mt-6">
    <div class="max-w-[1440px] mx-auto">
        
        @if($quiz->description)
            <div class="bg-secondary-container border border-outline-variant rounded-xl p-4 text-on-secondary-container mb-6 shadow-sm">
                <div class="flex gap-2">
                    <span class="material-symbols-rounded shrink-0">info</span>
                    <div>
                        <strong class="font-label-lg block mb-1">Instructions:</strong>
                        <p class="font-body-md">{{ $quiz->description }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form id="quizForm" action="{{ route('student.digital_learning.quizzes.submit', $quiz->id) }}" method="POST">
            @csrf
            
            <div class="flex flex-col lg:flex-row gap-8 relative items-start">
                
                <!-- Left Column: Questions -->
                <div class="flex-1 w-full space-y-6">
                    @forelse($quiz->questions->sortBy('order') as $index =>q)
                        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-6 md:p-8 shadow-sm transition-shadow hover:shadow-md question-card" data-question-id="{{ $q->id }}">
                            <div class="flex justify-between items-start mb-6 gap-4 border-b border-outline-variant pb-4">
                                <h3 class="font-headline-md text-on-surface leading-snug">
                                    <span class="text-primary font-black mr-2">Q{{ $index + 1 }}.</span> 
                                    {{ $q->question_text }}
                                </h3>
                                <div class="shrink-0 font-label-md text-secondary bg-surface-container px-3 py-1.5 rounded-lg border border-outline-variant">
                                    <span class="font-bold text-on-surface">{{ $q->marks }}</span> Marks
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                @php 
                                    $inputType = ($q->question_type === 'multiple') ? 'checkbox' : 'radio';
                                    $inputName = ($q->question_type === 'multiple') ? "answers[{$q->id}][]" : "answers[{$q->id}]";
                                @endphp

                                <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-outline-variant hover:bg-surface-container-low cursor-pointer transition-all has-[:checked]:bg-primary-fixed/30 has-[:checked]:border-primary group">
                                    <div class="relative flex items-center justify-center">
                                        <input type="{{ $inputType }}" name="{{ $inputName }}" value="a" class="peer w-5 h-5 opacity-0 absolute answer-input" onchange="updateProgress()">
                                        <div class="w-6 h-6 rounded-{{ $inputType === 'checkbox' ? 'md' : 'full' }} border-2 border-outline-variant flex items-center justify-center peer-checked:border-primary peer-checked:bg-primary transition-colors">
                                            <span class="material-symbols-rounded text-[16px] text-on-primary opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex gap-3 items-center">
                                        <span class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center font-bold text-secondary group-hover:bg-outline-variant transition-colors group-has-[:checked]:bg-primary group-has-[:checked]:text-on-primary">A</span>
                                        <span class="text-body-lg text-on-surface font-medium">{{ $q->option_a }}</span>
                                    </div>
                                </label>

                                <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-outline-variant hover:bg-surface-container-low cursor-pointer transition-all has-[:checked]:bg-primary-fixed/30 has-[:checked]:border-primary group">
                                    <div class="relative flex items-center justify-center">
                                        <input type="{{ $inputType }}" name="{{ $inputName }}" value="b" class="peer w-5 h-5 opacity-0 absolute answer-input" onchange="updateProgress()">
                                        <div class="w-6 h-6 rounded-{{ $inputType === 'checkbox' ? 'md' : 'full' }} border-2 border-outline-variant flex items-center justify-center peer-checked:border-primary peer-checked:bg-primary transition-colors">
                                            <span class="material-symbols-rounded text-[16px] text-on-primary opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex gap-3 items-center">
                                        <span class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center font-bold text-secondary group-hover:bg-outline-variant transition-colors group-has-[:checked]:bg-primary group-has-[:checked]:text-on-primary">B</span>
                                        <span class="text-body-lg text-on-surface font-medium">{{ $q->option_b }}</span>
                                    </div>
                                </label>
                                
                                @if($q->question_type !== 'true_false')
                                <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-outline-variant hover:bg-surface-container-low cursor-pointer transition-all has-[:checked]:bg-primary-fixed/30 has-[:checked]:border-primary group">
                                    <div class="relative flex items-center justify-center">
                                        <input type="{{ $inputType }}" name="{{ $inputName }}" value="c" class="peer w-5 h-5 opacity-0 absolute answer-input" onchange="updateProgress()">
                                        <div class="w-6 h-6 rounded-{{ $inputType === 'checkbox' ? 'md' : 'full' }} border-2 border-outline-variant flex items-center justify-center peer-checked:border-primary peer-checked:bg-primary transition-colors">
                                            <span class="material-symbols-rounded text-[16px] text-on-primary opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex gap-3 items-center">
                                        <span class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center font-bold text-secondary group-hover:bg-outline-variant transition-colors group-has-[:checked]:bg-primary group-has-[:checked]:text-on-primary">C</span>
                                        <span class="text-body-lg text-on-surface font-medium">{{ $q->option_c }}</span>
                                    </div>
                                </label>

                                <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-outline-variant hover:bg-surface-container-low cursor-pointer transition-all has-[:checked]:bg-primary-fixed/30 has-[:checked]:border-primary group">
                                    <div class="relative flex items-center justify-center">
                                        <input type="{{ $inputType }}" name="{{ $inputName }}" value="d" class="peer w-5 h-5 opacity-0 absolute answer-input" onchange="updateProgress()">
                                        <div class="w-6 h-6 rounded-{{ $inputType === 'checkbox' ? 'md' : 'full' }} border-2 border-outline-variant flex items-center justify-center peer-checked:border-primary peer-checked:bg-primary transition-colors">
                                            <span class="material-symbols-rounded text-[16px] text-on-primary opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex gap-3 items-center">
                                        <span class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center font-bold text-secondary group-hover:bg-outline-variant transition-colors group-has-[:checked]:bg-primary group-has-[:checked]:text-on-primary">D</span>
                                        <span class="text-body-lg text-on-surface font-medium">{{ $q->option_d }}</span>
                                    </div>
                                </label>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-surface-container-lowest rounded-2xl border border-outline-variant border-dashed">
                            <span class="material-symbols-rounded text-[64px] text-outline mb-4">quiz</span>
                            <p class="font-headline-md text-on-surface">No questions found for this quiz.</p>
                            <p class="font-body-md text-secondary mt-2">Please contact your teacher.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Right Column: Sticky Sidebar -->
                @if($quiz->questions->count() > 0)
                <div class="w-full lg:w-80 shrink-0 sticky top-6 z-10 space-y-4">
                    
                    <!-- Timer Card -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden shadow-md relative">
                        <div class="absolute top-0 left-0 w-full h-1 bg-surface-variant">
                            <div id="timeProgressBar" class="h-full bg-primary" style="width: 100%;"></div>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-label-md uppercase tracking-widest text-secondary font-bold mb-2">Time Remaining</h3>
                            <div class="text-display-md font-black text-on-surface flex items-center justify-center gap-2 tracking-tight tabular-nums" id="timer">
                                {{ $quiz->duration_minutes }}:00
                            </div>
                            <p class="text-label-sm text-secondary mt-2">Auto-submits when time is up</p>
                        </div>
                    </div>

                    <!-- Progress Card -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm">
                        <h3 class="font-headline-sm text-on-surface mb-4 flex items-center gap-2">
                            <span class="material-symbols-rounded text-primary">analytics</span> Progress
                        </h3>
                        
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-title-lg font-bold text-on-surface" id="answeredCount">0</span>
                            <span class="text-label-md text-secondary mb-1">/ {{ count($quiz->questions) }} Answered</span>
                        </div>
                        
                        <div class="w-full h-2.5 bg-surface-container-high rounded-full overflow-hidden mb-6">
                            <div id="quizProgressBar" class="h-full bg-emerald-500 transition-all duration-300" style="width: 0%;"></div>
                        </div>

                        <!-- Mini Map of Questions -->
                        <div class="grid grid-cols-5 gap-2 mb-6">
                            @foreach($quiz->questions->sortBy('order') as $index =>q)
                                <a href="javascript:void(0)" onclick="document.querySelector('.question-card[data-question-id=\'{{ $q->id }}\']').scrollIntoView({behavior: 'smooth', block: 'center'})" 
                                   id="nav-q-{{ $q->id }}"
                                   class="w-full aspect-square flex items-center justify-center rounded-lg border border-outline-variant text-label-sm font-bold text-secondary hover:border-primary hover:text-primary transition-colors">
                                    {{ $index + 1 }}
                                </a>
                            @endforeach
                        </div>

                        <div class="pt-4 border-t border-outline-variant">
                            <div class="flex justify-between items-center text-label-md">
                                <span class="text-secondary">Total Marks:</span>
                                <span class="font-bold text-on-surface">{{ $quiz->total_marks }}</span>
                            </div>
                            <div class="flex justify-between items-center text-label-md mt-2">
                                <span class="text-secondary">Passing Marks:</span>
                                <span class="font-bold text-on-surface">{{ $quiz->passing_marks }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="button" onclick="confirmSubmission()" class="w-full py-4 bg-primary text-on-primary rounded-2xl font-title-md font-bold hover:bg-primary/90 hover:-translate-y-0.5 transition-all shadow-md flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded">send</span> Submit Quiz
                    </button>
                    <p class="text-center text-xs text-secondary px-4 mt-2">You cannot change your answers after submission.</p>

                </div>
                @endif
                
            </div>
        </form>
    </div>
</main>

<script>
    // Timer Logic
    const totalTime = {{ $quiz->duration_minutes * 60 }};
    let timeRemaining = totalTime;
    
    const timerElement = document.getElementById('timer');
    const formElement = document.getElementById('quizForm');
    const timeProgressBar = document.getElementById('timeProgressBar');

    const interval = setInterval(() => {
        timeRemaining--;
        let minutes = Math.floor(timeRemaining / 60);
        let seconds = timeRemaining % 60;
        
        timerElement.innerText = `${minutes}: {seconds < 10 ? '0' : ''}${seconds}`;
        
        // Update Time Progress Bar
        const percentage = (timeRemaining / totalTime) * 100;
        timeProgressBar.style.width = percentage + '%';

        if (timeRemaining <= 60) {
            timerElement.classList.remove('text-on-surface');
            timerElement.classList.add('text-error', 'animate-pulse');
            timeProgressBar.classList.remove('bg-primary');
            timeProgressBar.classList.add('bg-error');
        }

        if (timeRemaining <= 0) {
            clearInterval(interval);
            window.UI.alert('Time Up', 'Time is up! Submitting your quiz automatically.').then(() => {
                formElement.requestSubmit ? formElement.requestSubmit() : formElement.submit();
            });
        }
    }, 1000);

    // Progress Logic
    const totalQuestions = {{ count($quiz->questions) }};
    const questionCards = document.querySelectorAll('.question-card');

    function updateProgress() {
        let answered = 0;
        
        questionCards.forEach(card => {
            const inputs = card.querySelectorAll('.answer-input');
            let isAnswered = false;
            
            inputs.forEach(input => {
                if (input.checked) isAnswered = true;
            });

            const qId = card.getAttribute('data-question-id');
            const navIndicator = document.getElementById('nav-q-' + qId);

            if (isAnswered) {
                answered++;
                navIndicator.classList.remove('border-outline-variant', 'text-secondary', 'bg-transparent');
                navIndicator.classList.add('bg-primary-fixed', 'border-primary', 'text-primary');
            } else {
                navIndicator.classList.add('border-outline-variant', 'text-secondary', 'bg-transparent');
                navIndicator.classList.remove('bg-primary-fixed', 'border-primary', 'text-primary');
            }
        });

        document.getElementById('answeredCount').innerText = answered;
        const progressPct = totalQuestions > 0 ? (answered / totalQuestions) * 100 : 0;
        document.getElementById('quizProgressBar').style.width = progressPct + '%';
    }

    async function confirmSubmission() {
        const answered = parseInt(document.getElementById('answeredCount').innerText);
        let message = `Are you sure you want to submit?`;
        
        if (answered < totalQuestions) {
            message = `You have only answered ${answered} out of ${totalQuestions} questions.\n\n` + message;
        }

        const isConfirmed = await window.UI.confirm('Confirm Submission', message, 'Submit Quiz', 'primary');
        if (isConfirmed) {
            formElement.requestSubmit ? formElement.requestSubmit() : formElement.submit();
        }
    }

    // Initialize progress
    updateProgress();
</script>
@endsection
