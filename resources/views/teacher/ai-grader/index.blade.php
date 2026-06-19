@extends('layouts.app')

@section('title', 'AI Auto Grader')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">AI Auto Grader</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Review assignments with pending submissions that can be graded by AI.</p>
            </div>
        </div>



<!-- List -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Assignment Title</th>
                            <th class="py-3 px-4 font-semibold">Pending Submissions</th>
                            <th class="py-3 px-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($assignments as $assignment)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-4 px-4 font-medium text-on-surface">{{ $assignment->title }}</td>
                            <td class="py-4 px-4 text-secondary">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-error-container text-on-error-container">
                                    {{ $assignment->pending_submissions_count }} Pending
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('teacher.assignments.submissions', $assignment->id) }}" class="bg-primary text-on-primary hover:bg-primary-container px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-rounded text-[18px]">auto_awesome</span>
                                    Grade Submissions
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 px-4 text-center text-secondary">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-rounded text-4xl mb-2 opacity-50">check_circle</span>
                                    <p class="text-body-lg font-medium">All caught up!</p>
                                    <p class="text-body-md mt-1">There are no pending submissions requiring AI grading.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
