@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-headline-xl font-headline-xl text-on-surface">Announcements</h1>
                <p class="text-body-lg font-body-lg text-secondary mt-1">School news and updates</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-3 overflow-x-auto pb-2 border-b border-outline-variant">
            <button class="px-4 py-1.5 rounded-full bg-primary-container text-on-primary-container font-label-md text-label-md transition-colors whitespace-nowrap">All</button>
            <button class="px-4 py-1.5 rounded-full border border-outline-variant text-secondary hover:bg-surface-container-low font-label-md text-label-md transition-colors whitespace-nowrap">Important</button>
            <button class="px-4 py-1.5 rounded-full border border-outline-variant text-secondary hover:bg-surface-container-low font-label-md text-label-md transition-colors whitespace-nowrap">Academic</button>
            <button class="px-4 py-1.5 rounded-full border border-outline-variant text-secondary hover:bg-surface-container-low font-label-md text-label-md transition-colors whitespace-nowrap">Events</button>
        </div>

        <div class="space-y-4">
            @forelse($announcements as $announcement)
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 relative overflow-hidden group hover:border-primary transition-colors flex flex-col shadow-sm">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                            <h3 class="text-headline-md font-headline-md text-on-surface group-hover:text-primary transition-colors">
                                {{ $announcement->title }}
                            </h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-secondary-container text-on-secondary-container">General</span>
                        </div>
                        <span class="text-label-md font-label-md text-secondary flex items-center gap-1 shrink-0">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            {{ $announcement->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-body-md font-body-md text-on-surface-variant whitespace-pre-line mt-2">{{ $announcement->content }}</p>
                </div>
            @empty
                <div class="bg-surface-container-lowest rounded-xl p-12 text-center border border-outline-variant shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-high mb-4 text-secondary">
                        <span class="material-symbols-outlined text-3xl">campaign</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md text-on-surface">No Announcements</h3>
                    <p class="text-body-md font-body-md text-secondary mt-1">Check back later for school news and updates.</p>
                </div>
            @endforelse

            @if(isset($announcements) && method_exists($announcements, 'links'))
                <div class="mt-6">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
