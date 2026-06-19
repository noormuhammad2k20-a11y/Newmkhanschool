@extends('layouts.app')

@section('title', 'My Assignments')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Assignments</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage your homework and assignments</p>
            </div>
            <div class="flex gap-4 items-center bg-surface-container border border-outline-variant rounded-xl p-2 px-4">
                <div class="text-center px-4 border-r border-outline-variant">
                    <span class="block text-headline-sm font-bold text-primary">{{ $pendingCount ?? 0 }}</span>
                    <span class="text-[10px] uppercase font-bold text-secondary tracking-wider">Pending</span>
                </div>
                <div class="text-center px-4 border-r border-outline-variant">
                    <span class="block text-headline-sm font-bold text-success">{{ $submittedCount ?? 0 }}</span>
                    <span class="text-[10px] uppercase font-bold text-secondary tracking-wider">Submitted</span>
                </div>
                <div class="text-center px-4">
                    <span class="block text-headline-sm font-bold text-error">{{ $lateCount ?? 0 }}</span>
                    <span class="text-[10px] uppercase font-bold text-secondary tracking-wider">Late</span>
                </div>
            </div>
        </div>

        <!-- Filter/Tabs Placeholder -->
        <div class="flex gap-2 border-b border-outline-variant pb-2">
            <button class="px-4 py-2 text-primary border-b-2 border-primary font-bold text-sm">All Assignments</button>
            <button class="px-4 py-2 text-secondary hover:text-on-surface hover:bg-surface-container rounded-t-lg transition-colors text-sm font-medium">Pending</button>
            <button class="px-4 py-2 text-secondary hover:text-on-surface hover:bg-surface-container rounded-t-lg transition-colors text-sm font-medium">Completed</button>
        </div>

@if($errors->any())
            <div class="bg-error text-white p-4 rounded-xl font-bold mb-4">
                <ul class="list-disc ml-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-md">
                @forelse($assignments ?? [] as $assignment)
                    @php
                    $dueDate = \Carbon\Carbon::parse($assignment->due_date)->endOfDay();
                    $isOverdue = $dueDate->isPast();
                    $isSubmitted = in_array($assignment->id, $submittedIds ?? []);
                        // For performance, we'll fetch the submission if $isSubmitted is true.
                        $submission = $isSubmitted ? \App\Models\AssignmentSubmission::where('assignment_id', $assignment->id)->where('student_id', auth()->user()->student->id)->first() : null;
                    @endphp
                    <div class="bg-surface-container-lowest rounded-xl border {{ $isSubmitted ? 'border-[#10b981]' : ($isOverdue ? 'border-error' : 'border-outline-variant') }} overflow-hidden flex flex-col hover:shadow-md transition-shadow relative">
                        
                        @if($submission)
                            <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
                                <div class="bg-[#10b981] text-white text-[10px] font-bold uppercase py-1 shadow-sm transform rotate-45 text-center w-24 absolute top-3 -right-6">Done</div>
                            </div>
                        @elseif($isOverdue)
                            <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
                                <div class="bg-error text-white text-[10px] font-bold uppercase py-1 shadow-sm transform rotate-45 text-center w-24 absolute top-3 -right-6">Late</div>
                            </div>
                        @else
                            <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
                                <div class="bg-warning text-warning-on-container text-[10px] font-bold uppercase py-1 shadow-sm transform rotate-45 text-center w-24 absolute top-3 -right-6">Pending</div>
                            </div>
                        @endif

                        <div class="p-5 border-b border-outline-variant z-10">
                            <div class="flex justify-between items-start mb-3 pr-8">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-primary-fixed text-primary border border-primary-fixed-dim">
                                    {{ $assignment->subject->name ?? 'N/A' }}
                                </span>
                                <span class="text-[12px] font-bold flex items-center gap-1 {{ $isOverdue && !$isSubmitted ? 'text-error' : 'text-secondary' }}">
                                    <span class="material-symbols-rounded text-[14px]">calendar_today</span>
                                    Due {{ $dueDate->format('M d, Y') }}
                                </span>
                            </div>
                            <h3 class="font-bold text-headline-md text-on-surface mb-2 line-clamp-1" title="{{ $assignment->title }}">{{ $assignment->title }}</h3>
                            <p class="text-body-md text-secondary line-clamp-2">{{ $assignment->description }}</p>
                        </div>
                        
                        <div class="p-5 bg-surface-bright mt-auto">
                            @if($isSubmitted && $submission)
                                <div class="flex flex-col gap-3">
                                    <div class="flex items-center justify-between bg-[#ecfdf5] border border-[#a7f3d0] rounded-lg p-3">
                                        <div class="flex items-center gap-2 text-[#059669]">
                                            <span class="material-symbols-rounded text-[20px]">check_circle</span>
                                            <div>
                                                <span class="block text-sm font-bold">Submitted</span>
                                                <span class="block text-[10px] opacity-80">{{ \Carbon\Carbon::parse($submission->created_at)->format('M d, g:i A') }}</span>
                                            </div>
                                        </div>
                                        @if($submission->marks_obtained !== null)
                                            <div class="text-right">
                                                <span class="block text-lg font-black text-on-surface leading-none">{{ $submission->marks_obtained }}</span>
                                                <span class="text-[10px] text-secondary font-bold uppercase">Marks</span>
                                            </div>
                                        @else
                                            <span class="text-[11px] font-bold text-secondary uppercase bg-white px-2 py-1 rounded-md">Pending Review</span>
                                        @endif
                                    </div>
                                </div>
                            @elseif(\Carbon\Carbon::now()->gt($dueDate))
                                <div class="flex items-center justify-between bg-error/10 border border-error/20 rounded-lg p-3">
                                    <div class="flex items-center gap-2 text-error">
                                        <span class="material-symbols-rounded text-[20px]">cancel</span>
                                        <div>
                                            <span class="block text-sm font-bold">Past Due</span>
                                            <span class="block text-[10px] opacity-80">Submissions are no longer accepted</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div>
                                        <input type="file" name="file" class="block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-surface-container file:text-on-surface hover:file:bg-surface-container-high transition-colors cursor-pointer border border-outline-variant border-dashed rounded-lg p-2 bg-surface-container-lowest" required>
                                    </div>
                                    <button type="submit" class="w-full bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container font-label-md text-label-md py-2 px-4 rounded-lg transition-colors flex justify-center items-center gap-2 shadow-sm">
                                        <span class="material-symbols-rounded text-[18px]">upload</span>
                                        Upload & Submit
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-surface-container-lowest rounded-xl p-12 text-center border border-outline-variant border-dashed m-4">
                        <span class="material-symbols-rounded text-[48px] mb-2 text-secondary opacity-50">task</span>
                        <h3 class="text-headline-md font-headline-md text-on-surface mb-1">No Assignments</h3>
                        <p class="text-body-lg font-body-lg text-secondary">You don't have any assignments due at the moment.</p>
                    </div>
                @endforelse
            </div>
            
            @if(method_exists($assignments, 'links') && $assignments->hasPages())
                <div class="mt-6 border-t border-outline-variant pt-6">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
