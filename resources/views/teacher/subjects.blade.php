@extends('layouts.app')

@section('title', 'My Subjects')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">My Subjects</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Subjects assigned to you across different classes.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            @forelse($subjects as $subject)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">{{ $subject->subject }}</h3>
                        <p class="text-label-md text-secondary mt-1">Class: {{ $subject->class_name }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined">menu_book</span>
                    </div>
                </div>
                
                <div class="mt-auto pt-4 border-t border-outline-variant grid grid-cols-2 gap-2 text-center">
                    <a href="{{ route('teacher.assignments') }}" class="text-primary hover:bg-primary-fixed rounded py-1 text-label-sm font-medium transition-colors">Assignments</a>
                    <a href="{{ route('teacher.marks', ['class_id' => $subject->class_id, 'subject' => $subject->subject]) }}" class="text-primary hover:bg-primary-fixed rounded py-1 text-label-sm font-medium transition-colors">Enter Marks</a>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center">
                <span class="material-symbols-outlined text-4xl text-secondary mb-2">library_books</span>
                <p class="text-body-lg text-secondary">No subjects assigned yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
