@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('student.dashboard') }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">My Quiz Results</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">View your quiz attempts and scores.</p>
            </div>
        </div>

        <!-- Quiz Results Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]">quiz</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">Quiz Attempts</h3>
            </div>
            <div class="overflow-x-auto">
                @if($attempts->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">#</th>
                            <th class="py-3 px-4 font-semibold">Quiz Title</th>
                            <th class="py-3 px-4 font-semibold">Subject</th>
                            <th class="py-3 px-4 font-semibold">Date</th>
                            <th class="py-3 px-4 font-semibold">Score</th>
                            <th class="py-3 px-4 font-semibold">Percentage</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @foreach($attempts as $i => $attempt)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 text-secondary">{{ $i + 1 }}</td>
                            <td class="py-3 px-4 font-medium text-on-surface">{{ $attempt->quiz->title ?? '—' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $attempt->quiz->subject->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $attempt->submitted_at ? $attempt->submitted_at->format('d M Y') : '—' }}</td>
                            <td class="py-3 px-4 text-on-surface font-medium">{{ $attempt->score }}/{{ $attempt->total_marks }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-20 h-2 bg-surface-variant rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $attempt->percentage >= 50 ? 'bg-emerald-500' : 'bg-error' }}" style="width: {{ min($attempt->percentage, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold {{ $attempt->percentage >= 50 ? 'text-emerald-700' : 'text-error' }}">{{ $attempt->percentage }}%</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $attempt->status === 'submitted' ? 'bg-emerald-100 text-emerald-700' : 'bg-surface-variant text-secondary' }}">
                                    {{ ucfirst($attempt->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-16">
                    <span class="material-symbols-outlined text-[56px] text-secondary opacity-40">quiz</span>
                    <p class="text-body-lg font-body-lg text-secondary mt-4">No quiz attempts found yet.</p>
                    <p class="text-body-md font-body-md text-secondary mt-1">Complete quizzes to see your results here.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
