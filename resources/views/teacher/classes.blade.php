@extends('layouts.app')

@section('title', 'My Classes')

@section('content')
<main class="flex-1 overflow-y-auto bg-background p-4 sm:p-6 lg:p-8">
    <div class="w-full mx-auto">
        <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <!-- Header Section -->
            <div class="p-4 sm:p-6 lg:p-8 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">My Classes</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Overview of classes you are assigned to teach.</p>
                </div>
            </div>

            <!-- Classes Grid -->
            <div class="p-4 sm:p-6 lg:p-8 bg-surface-container-lowest">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
            @forelse($classes as $class)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col hover:border-primary/50 hover:shadow-sm transition-all duration-300 h-full">
                
                <!-- Card Header -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1 min-w-0 pr-3">
                        <h3 class="text-title-lg font-title-lg text-on-surface truncate">{{ $class->name }}</h3>
                        <p class="text-body-sm font-medium text-secondary mt-1 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">groups</span>
                            {{ $class->student_count }} Students Enrolled
                        </p>
                    </div>
                    <div class="shrink-0 w-10 h-10 bg-primary-fixed rounded-lg flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">local_library</span>
                    </div>
                </div>
                
                <!-- Subjects Section -->
                <div class="mb-4 flex-1 content-start">
                    <div class="flex flex-wrap gap-1.5">
                        @if($class->subjects)
                            @foreach(explode(', ', $class->subjects) as $subject)
                                <span class="px-2 py-0.5 bg-surface-container text-on-surface-variant text-[11px] font-medium tracking-wide rounded border border-outline-variant whitespace-nowrap">
                                    {{ $subject }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-[12px] text-secondary italic">No subjects specified</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-auto pt-4 border-t border-outline-variant grid grid-cols-3 gap-2">
                    <!-- Students Button - Light Blue -->
                    <a href="{{ route('teacher.students', ['class_id' => $class->id]) }}" class="flex flex-col xl:flex-row items-center justify-center gap-1 xl:gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-100 hover:border-blue-200 rounded-lg py-1.5 xl:py-2 px-1 text-[11px] xl:text-label-sm font-medium transition-colors text-center shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">person_search</span>
                        <span>Students</span>
                    </a>
                    <!-- Attendance Button - Light Emerald -->
                    <a href="{{ route('teacher.attendance', ['class_id' => $class->id]) }}" class="flex flex-col xl:flex-row items-center justify-center gap-1 xl:gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100 hover:border-emerald-200 rounded-lg py-1.5 xl:py-2 px-1 text-[11px] xl:text-label-sm font-medium transition-colors text-center shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">fact_check</span>
                        <span>Attendance</span>
                    </a>
                    <!-- Marks Button - Light Purple -->
                    <a href="{{ route('teacher.marks', ['class_id' => $class->id]) }}" class="flex flex-col xl:flex-row items-center justify-center gap-1 xl:gap-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-100 hover:border-purple-200 rounded-lg py-1.5 xl:py-2 px-1 text-[11px] xl:text-label-sm font-medium transition-colors text-center shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">draw</span>
                        <span>Marks</span>
                    </a>
                </div>

            </div>
            @empty
            <div class="col-span-full bg-surface-container-lowest border border-outline-variant rounded-xl p-10 text-center flex flex-col items-center justify-center min-h-[200px]">
                <div class="w-16 h-16 bg-surface-variant rounded-full flex items-center justify-center text-on-surface-variant mb-4">
                    <span class="material-symbols-outlined text-3xl">assignment_late</span>
                </div>
                <h3 class="text-title-lg font-title-lg text-on-surface mb-2">No Classes Assigned</h3>
                <p class="text-body-md text-secondary max-w-md mx-auto">You have not been assigned to any classes yet. Please contact the administrator.</p>
            </div>
            @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
