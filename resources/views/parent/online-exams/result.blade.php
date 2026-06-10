@extends('layouts.app')

@section('title', 'Exam Result - ' . $attempt->onlineExam->title)

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('parent.child.online-exams.index', $attempt->student_id) }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-headline-lg font-headline-lg text-on-surface">{{ $attempt->onlineExam->title }}</h2>
                    <p class="text-body-md font-body-md text-secondary mt-1">Exam Result Details</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-lg">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex items-center gap-4 border-l-4 border-l-primary/60">
                <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                    <span class="material-symbols-outlined text-[24px]">workspace_premium</span>
                </div>
                <div>
                    <p class="text-label-md font-label-md text-on-surface-variant mb-1 uppercase tracking-wider">Score Obtained</p>
                    <p class="text-headline-md font-headline-md font-bold text-on-surface">{{ $attempt->score }} / {{ $attempt->total_marks }}</p>
                </div>
            </div>
            
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex items-center gap-4 border-l-4 border-l-emerald-500/60">
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700">
                    <span class="material-symbols-outlined text-[24px]">percent</span>
                </div>
                <div>
                    <p class="text-label-md font-label-md text-on-surface-variant mb-1 uppercase tracking-wider">Percentage</p>
                    <p class="text-headline-md font-headline-md font-bold text-on-surface">
                        @if($attempt->total_marks > 0)
                            {{ round(($attempt->score / $attempt->total_marks) * 100) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex items-center gap-4 border-l-4 border-l-blue-500/60">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">
                    <span class="material-symbols-outlined text-[24px]">event_available</span>
                </div>
                <div>
                    <p class="text-label-md font-label-md text-on-surface-variant mb-1 uppercase tracking-wider">Submitted On</p>
                    <p class="text-title-lg font-title-lg font-bold text-on-surface">{{ \Carbon\Carbon::parse($attempt->submitted_at)->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col mb-lg">
            <div class="p-6 border-b border-outline-variant bg-surface-bright">
                <h3 class="text-title-lg font-title-lg text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">fact_check</span>
                    Question Breakdown
                </h3>
            </div>
            <div class="p-6 space-y-6">
                @forelse($attempt->answers as $index => $answer)
                    <div class="p-6 rounded-xl border {{ $answer->is_correct ? 'border-emerald-200 bg-emerald-50/30' : 'border-red-200 bg-red-50/30' }} transition-colors">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <h4 class="text-title-md font-title-md text-on-surface flex-1">
                                <span class="text-secondary mr-2">Q{{ $index + 1 }}.</span>
                                {{ $answer->question->question_text ?? 'Unknown Question' }}
                            </h4>
                            <div class="shrink-0 flex items-center gap-2">
                                <span class="px-3 py-1 rounded-md text-label-md font-label-md font-bold {{ $answer->is_correct ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $answer->marks_awarded }} Marks
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-lg bg-surface-container-lowest border border-outline-variant">
                                <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-2">Child's Answer</p>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined {{ $answer->is_correct ? 'text-emerald-500' : 'text-red-500' }}">
                                        {{ $answer->is_correct ? 'check_circle' : 'cancel' }}
                                    </span>
                                    <p class="text-body-md font-body-md font-medium text-on-surface">{{ $answer->selected_option }}</p>
                                </div>
                            </div>
                            <div class="p-4 rounded-lg bg-surface-container-lowest border border-outline-variant">
                                <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-2">Correct Answer</p>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                                    <p class="text-body-md font-body-md font-medium text-on-surface">{{ $answer->question->correct_option ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-[48px] text-secondary mb-4">do_not_disturb_off</span>
                        <p class="text-body-lg font-body-lg text-secondary">No answers found for this attempt.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
