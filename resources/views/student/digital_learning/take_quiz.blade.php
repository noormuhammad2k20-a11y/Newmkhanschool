@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center gap-4 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
        <a href="{{ route('student.digital_learning.quizzes') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-low hover:bg-surface-container transition-colors text-on-surface">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div class="flex-1">
            <h1 class="font-headline-lg text-headline-lg text-on-surface">{{ $quiz->title }}</h1>
            <p class="font-body-md text-on-surface-variant">Subject: {{ $quiz->subject->name ?? 'N/A' }}</p>
        </div>
        <div class="text-right">
            <div class="font-headline-sm text-primary" id="timer">{{ $quiz->duration_minutes }}:00</div>
            <div class="font-label-sm text-on-surface-variant">Time Remaining</div>
        </div>
    </div>

    @if($quiz->description)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-blue-800">
            <strong>Instructions:</strong> {{ $quiz->description }}
        </div>
    @endif

    <form id="quizForm" action="{{ route('student.digital_learning.quizzes.submit', $quiz->id) }}" method="POST" class="space-y-6">
        @csrf
        @forelse($quiz->questions->sortBy('order') as $index => $q)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-headline-md text-on-surface"><span class="text-primary font-bold">Q{{ $index + 1 }}.</span> {{ $q->question_text }}</h3>
                    <span class="font-label-sm text-on-surface-variant bg-surface-container-low px-2 py-1 rounded-full">{{ $q->marks }} Marks</span>
                </div>
                
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-outline-variant hover:bg-surface-container-low cursor-pointer transition-colors has-[:checked]:bg-primary/5 has-[:checked]:border-primary">
                        <input type="radio" name="answers[{{ $q->id }}]" value="a" class="w-4 h-4 text-primary focus:ring-primary border-outline-variant">
                        <span class="font-bold text-on-surface-variant">A.</span>
                        <span class="text-on-surface">{{ $q->option_a }}</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-outline-variant hover:bg-surface-container-low cursor-pointer transition-colors has-[:checked]:bg-primary/5 has-[:checked]:border-primary">
                        <input type="radio" name="answers[{{ $q->id }}]" value="b" class="w-4 h-4 text-primary focus:ring-primary border-outline-variant">
                        <span class="font-bold text-on-surface-variant">B.</span>
                        <span class="text-on-surface">{{ $q->option_b }}</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-outline-variant hover:bg-surface-container-low cursor-pointer transition-colors has-[:checked]:bg-primary/5 has-[:checked]:border-primary">
                        <input type="radio" name="answers[{{ $q->id }}]" value="c" class="w-4 h-4 text-primary focus:ring-primary border-outline-variant">
                        <span class="font-bold text-on-surface-variant">C.</span>
                        <span class="text-on-surface">{{ $q->option_c }}</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-outline-variant hover:bg-surface-container-low cursor-pointer transition-colors has-[:checked]:bg-primary/5 has-[:checked]:border-primary">
                        <input type="radio" name="answers[{{ $q->id }}]" value="d" class="w-4 h-4 text-primary focus:ring-primary border-outline-variant">
                        <span class="font-bold text-on-surface-variant">D.</span>
                        <span class="text-on-surface">{{ $q->option_d }}</span>
                    </label>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-surface-container-lowest rounded-xl border border-outline-variant border-dashed">
                <span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-4">quiz</span>
                <p class="font-body-lg text-on-surface-variant">No questions found for this quiz.</p>
            </div>
        @endforelse

        @if($quiz->questions->count() > 0)
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3 bg-primary text-on-primary rounded-lg font-label-lg hover:bg-primary/90 transition-colors shadow-sm" onclick="return confirm('Are you sure you want to submit your quiz? You cannot change your answers after submission.');">
                    Submit Quiz
                </button>
            </div>
        @endif
    </form>
</div>

<script>
    // Timer Logic
    let timeRemaining = {{ $quiz->duration_minutes * 60 }};
    const timerElement = document.getElementById('timer');
    const formElement = document.getElementById('quizForm');

    const interval = setInterval(() => {
        timeRemaining--;
        let minutes = Math.floor(timeRemaining / 60);
        let seconds = timeRemaining % 60;
        
        timerElement.innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        if (timeRemaining <= 60) {
            timerElement.classList.remove('text-primary');
            timerElement.classList.add('text-error', 'animate-pulse');
        }

        if (timeRemaining <= 0) {
            clearInterval(interval);
            alert('Time is up! Submitting your quiz automatically.');
            formElement.submit();
        }
    }, 1000);
</script>
@endsection
