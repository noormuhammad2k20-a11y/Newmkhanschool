@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-label-md font-label-md text-secondary mb-2">
                    <a href="{{ route('parent.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="text-on-surface">Announcements</span>
                </nav>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Announcements</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Stay updated with the latest school news and notices.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="flex items-center gap-3 overflow-x-auto pb-2 border-b border-outline-variant mb-lg hide-scrollbar">
            <button class="px-5 py-2 rounded-full bg-primary text-on-primary font-label-md text-label-md transition-colors whitespace-nowrap shadow-sm">All</button>
            <button class="px-5 py-2 rounded-full border border-outline-variant text-secondary hover:bg-surface-container-low hover:text-on-surface font-label-md text-label-md transition-colors whitespace-nowrap">Important</button>
            <button class="px-5 py-2 rounded-full border border-outline-variant text-secondary hover:bg-surface-container-low hover:text-on-surface font-label-md text-label-md transition-colors whitespace-nowrap">Academic</button>
            <button class="px-5 py-2 rounded-full border border-outline-variant text-secondary hover:bg-surface-container-low hover:text-on-surface font-label-md text-label-md transition-colors whitespace-nowrap">Events</button>
        </div>

        <div class="space-y-6">
            @forelse($announcements as $announcement)
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-0 relative overflow-hidden group hover:border-primary transition-all hover:shadow-md flex flex-col md:flex-row">
                    <div class="bg-surface-bright p-6 md:w-48 border-b md:border-b-0 md:border-r border-outline-variant flex flex-col items-center justify-center text-center shrink-0">
                        <span class="text-headline-lg font-headline-lg text-primary">{{ $announcement->created_at->format('d') }}</span>
                        <span class="text-title-md font-title-md text-secondary uppercase tracking-widest">{{ $announcement->created_at->format('M Y') }}</span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-center">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <h3 class="text-headline-md font-headline-md text-on-surface group-hover:text-primary transition-colors">
                                {{ $announcement->title }}
                            </h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-label-sm font-label-sm bg-secondary-container text-on-secondary-container shrink-0">
                                {{ $announcement->type ?? 'General' }}
                            </span>
                        </div>
                        <p class="text-body-lg font-body-lg text-secondary whitespace-pre-line">{{ $announcement->content }}</p>
                        <div class="mt-4 flex items-center gap-2 text-label-sm font-label-sm text-outline">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            Posted {{ $announcement->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center text-center py-20 shadow-sm">
                    <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center text-secondary mb-4">
                        <span class="material-symbols-outlined text-[40px]">campaign</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-2">No Announcements</h3>
                    <p class="text-body-lg font-body-lg text-secondary max-w-md">There are no school announcements or notices at this time.</p>
                </div>
            @endforelse

            @if(isset($announcements) && method_exists($announcements, 'links') && $announcements->hasPages())
                <div class="mt-8">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
