@extends('layouts.app')

@section('title', 'My Classes')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">My Classes</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Overview of classes you are assigned to teach.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
            @forelse($classes as $class)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">{{ $class->name }}</h3>
                        <p class="text-label-md text-secondary mt-1">Strength: {{ $class->student_count }} Students</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">class</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-label-sm text-secondary uppercase tracking-wider mb-1">Assigned Subjects</p>
                    <p class="text-body-md text-on-surface font-medium">{{ $class->subjects ?: 'None specified' }}</p>
                </div>

                <div class="mt-auto pt-4 border-t border-outline-variant grid grid-cols-3 gap-2 text-center">
                    <a href="{{ route('teacher.students', ['class_id' => $class->id]) }}" class="text-primary hover:bg-primary-fixed rounded py-1 text-label-sm font-medium transition-colors">Students</a>
                    <a href="{{ route('teacher.attendance', ['class_id' => $class->id]) }}" class="text-primary hover:bg-primary-fixed rounded py-1 text-label-sm font-medium transition-colors">Attendance</a>
                    <a href="{{ route('teacher.marks', ['class_id' => $class->id]) }}" class="text-primary hover:bg-primary-fixed rounded py-1 text-label-sm font-medium transition-colors">Marks</a>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center">
                <span class="material-symbols-outlined text-4xl text-secondary mb-2">assignment_late</span>
                <p class="text-body-lg text-secondary">No classes assigned yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
