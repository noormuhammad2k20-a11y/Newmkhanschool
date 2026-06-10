@extends('layouts.app')

@section('title', 'Child Assignments')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Assignments</h2>
                <p class="text-body-md font-body-md text-secondary mt-1">Viewing assignments for {{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
        <a href="{{ route('parent.dashboard') }}" class="bg-surface border border-outline-variant text-on-surface px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-md mb-lg">
        @forelse($assignments ?? [] as $assignment)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden flex flex-col hover:border-primary transition-colors group shadow-sm">
                <div class="p-md border-b border-outline-variant bg-surface-container-lowest">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-primary-container text-on-primary-container">
                            {{ $assignment->subject->name ?? 'N/A' }}
                        </span>
                        @php
                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                            $isOverdue = $dueDate->isPast() && !$dueDate->isToday();
                        @endphp
                        <span class="font-label-sm text-label-sm flex items-center gap-1 {{ $isOverdue ? 'text-red-600' : 'text-secondary' }}">
                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                            Due {{ $dueDate->format('M d') }}
                        </span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-1 line-clamp-1 group-hover:text-primary transition-colors">{{ $assignment->title }}</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ $assignment->description }}</p>
                </div>
                <div class="p-md bg-surface-container-low mt-auto border-t border-outline-variant">
                    @php
                        $submission = $assignment->submission;
                    @endphp
                    
                    @if($submission)
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 font-label-md text-label-md text-emerald-600">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                Submitted ({{ \Carbon\Carbon::parse($submission->submitted_at ?? $submission->created_at)->format('d M') }})
                            </span>
                            @if($submission->marks_obtained !== null)
                                <span class="font-label-md text-label-md font-bold text-on-surface">{{ $submission->marks_obtained }} Marks</span>
                            @else
                                <span class="font-label-md text-label-md text-secondary">Pending Review</span>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 font-label-md text-label-md {{ $isOverdue ? 'text-red-600' : 'text-orange-600' }}">
                                <span class="material-symbols-outlined text-[18px]">{{ $isOverdue ? 'cancel' : 'pending_actions' }}</span>
                                Not Submitted
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-surface-container-lowest border border-outline-variant rounded-lg p-xl text-center shadow-sm">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-low mb-4 text-secondary">
                    <span class="material-symbols-outlined text-3xl">task</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">No Assignments</h3>
                <p class="text-body-md font-body-md text-secondary mt-1">There are no assignments due at the moment.</p>
            </div>
        @endforelse
    </div>
    </div>
</main>
@endsection
