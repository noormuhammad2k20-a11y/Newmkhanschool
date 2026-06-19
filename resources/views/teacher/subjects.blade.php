@extends('layouts.app')

@section('title', 'My Subjects')

@section('content')
<main class="flex-1 overflow-y-auto bg-background p-4 sm:p-6 lg:p-8">
    <div class="w-full mx-auto">
        <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <!-- Header Section -->
            <div class="p-4 sm:p-6 lg:p-8 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">My Subjects</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Subjects assigned to you across different classes.</p>
                </div>
            </div>

            <!-- Subjects Grid -->
            <div class="p-4 sm:p-6 lg:p-8 bg-surface-container-lowest">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
            @forelse($subjects as $subject)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col hover:border-primary/50 hover:shadow-sm transition-all duration-300 h-full">
                
                <!-- Card Header -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1 min-w-0 pr-3">
                        <h3 class="text-title-lg font-title-lg text-on-surface truncate">{{ $subject->subject }}</h3>
                        <p class="text-body-sm font-medium text-secondary mt-1 flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-[16px]">groups</span>
                            Class: {{ $subject->class_name }}
                        </p>
                    </div>
                    <div class="shrink-0 w-10 h-10 bg-primary-fixed rounded-lg flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-rounded text-[20px]">menu_book</span>
                    </div>
                </div>

                <!-- Spacer -->
                <div class="mb-4 flex-1 content-start"></div>

                <!-- Action Buttons -->
                <div class="mt-auto pt-4 border-t border-outline-variant grid grid-cols-2 gap-2">
                    <!-- Assignments Button - Light Blue -->
                    <a href="{{ route('teacher.assignments') }}" class="flex flex-col xl:flex-row items-center justify-center gap-1 xl:gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-100 hover:border-blue-200 rounded-lg py-1.5 xl:py-2 px-1 text-[11px] xl:text-label-sm font-medium transition-colors text-center shadow-sm">
                        <span class="material-symbols-rounded text-[18px]">assignment</span>
                        <span>Assignments</span>
                    </a>
                    <!-- Marks Button - Light Purple -->
                    <a href="{{ route('teacher.marks', ['class_id' =>$subject->class_id, 'subject' =>$subject->subject]) }}" class="flex flex-col xl:flex-row items-center justify-center gap-1 xl:gap-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-100 hover:border-purple-200 rounded-lg py-1.5 xl:py-2 px-1 text-[11px] xl:text-label-sm font-medium transition-colors text-center shadow-sm">
                        <span class="material-symbols-rounded text-[18px]">draw</span>
                        <span>Marks</span>
                    </a>
                </div>

            </div>
            @empty
            <div class="col-span-full bg-surface-container-lowest border border-outline-variant rounded-xl p-10 text-center flex flex-col items-center justify-center min-h-[200px]">
                <div class="w-16 h-16 bg-surface-variant rounded-full flex items-center justify-center text-on-surface-variant mb-4">
                    <span class="material-symbols-rounded text-3xl">library_books</span>
                </div>
                <h3 class="text-title-lg font-title-lg text-on-surface mb-2">No Subjects Assigned</h3>
                <p class="text-body-md text-secondary max-w-md mx-auto">You have not been assigned to any subjects yet. Please contact the administrator.</p>
            </div>
            @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
