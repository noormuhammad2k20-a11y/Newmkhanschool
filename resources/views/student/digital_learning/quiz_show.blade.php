@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface p-lg">
    <div class="max-w-3xl mx-auto">
        
        <div class="mb-lg flex items-center justify-between">
            <div>
                <a href="{{ route('student.quizzes') }}" class="text-primary hover:underline font-label-md flex items-center gap-1 mb-sm">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Quizzes
                </a>
                <h2 class="font-headline-xl text-headline-xl font-bold text-on-surface">{{ $quiz->title }}</h2>
                <p class="text-secondary font-body-md mt-1">{{ $quiz->subject->name ?? 'Subject' }} | Duration: {{ $quiz->duration_minutes }} mins | Total Marks: {{ $quiz->total_marks }}</p>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center gap-2 bg-error-container text-error px-4 py-2 rounded-full font-headline-sm shadow-sm" id="timer-display">
                    <span class="material-symbols-outlined">timer</span>
                    <span id="time-left">{{ sprintf('%02d:00', $quiz->duration_minutes) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-lg">
            @if($quiz->description)
            <div class="bg-surface-container rounded-lg p-md mb-lg border border-outline">
                <h4 class="font-label-md text-on-surface mb-xs font-semibold">Instructions:</h4>
                <p class="text-on-surface-variant font-body-md">{{ $quiz->description }}</p>
            </div>
            @endif

            @if($quiz->questions->isEmpty())
                <div class="text-center py-xl text-secondary">
                    <p class="font-body-lg">This quiz has no questions currently.</p>
                </div>
            @else
                <form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST" id="quiz-form">
                    @csrf
                    
                    <div class="space-y-xl">
                        @foreach($quiz->questions as $index =>question)
                        <div class="quiz-question" id="q_{{ $question->id }}">
                            <div class="flex items-start gap-md mb-md">
                                <span class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold flex-shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h3 class="font-headline-sm text-on-surface">{{ $question->question_text }}</h3>
                                    <span class="text-secondary font-label-sm">[{{ $question->marks }} marks]</span>
                                </div>
                            </div>
                            
                            <div class="pl-12 space-y-sm">
                                @if($question->question_type === 'multiple_choice')
                                    @php
                                        $options = json_decode($question->options, true) ?? [];
                                    @endphp
                                    @foreach($options as $key =>opt)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-outline hover:bg-surface-container-low cursor-pointer transition-colors w-full group">
                                        <input type="radio" name="q_{{ $question->id }}" value="{{ $key }}" class="w-5 h-5 text-primary border-outline focus:ring-primary focus:ring-2">
                                        <span class="font-body-md text-on-surface group-hover:text-primary transition-colors">{{ $opt }}</span>
                                    </label>
                                    @endforeach
                                @else
                                    <!-- Assume true/false or other if supported -->
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-outline hover:bg-surface-container-low cursor-pointer transition-colors w-full group">
                                        <input type="radio" name="q_{{ $question->id }}" value="true" class="w-5 h-5 text-primary border-outline focus:ring-primary focus:ring-2">
                                        <span class="font-body-md text-on-surface group-hover:text-primary transition-colors">True</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-outline hover:bg-surface-container-low cursor-pointer transition-colors w-full group">
                                        <input type="radio" name="q_{{ $question->id }}" value="false" class="w-5 h-5 text-primary border-outline focus:ring-primary focus:ring-2">
                                        <span class="font-body-md text-on-surface group-hover:text-primary transition-colors">False</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-2xl pt-lg border-t border-outline-variant flex justify-between items-center">
                        <p class="text-secondary font-body-sm">Ensure all questions are answered before submitting.</p>
                        <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary font-headline-sm py-3 px-8 rounded-full shadow-md transition-all transform hover:scale-105 flex items-center gap-2">
                            <span class="material-symbols-outlined">send</span> Submit Quiz
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(!$quiz->questions->isEmpty())
        let timeRemaining = {{ $quiz->duration_minutes * 60 }};
        const timerDisplay = document.getElementById('time-left');
        const form = document.getElementById('quiz-form');
        
        const timerInterval = setInterval(function() {
            timeRemaining--;
            
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            
            timerDisplay.textContent = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
                
            if (timeRemaining <= 300) { // 5 minutes warning
                document.getElementById('timer-display').classList.replace('bg-error-container', 'bg-error');
                document.getElementById('timer-display').classList.replace('text-error', 'text-on-error');
                document.getElementById('timer-display').classList.add('animate-pulse');
            }
                
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                form.submit();
            }
        }, 1000);
        @endif
    });
</script>
@endsection
