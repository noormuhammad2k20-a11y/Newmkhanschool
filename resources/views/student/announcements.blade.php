@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 border-b border-outline-variant pb-6">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface flex items-center gap-3">
                    <span class="material-symbols-outlined text-[32px] text-tertiary">campaign</span>
                    Announcements
                </h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Stay updated with the latest school news and notices</p>
            </div>
            
            <div class="flex gap-2">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[18px]">search</span>
                    <input type="text" placeholder="Search announcements..." class="pl-10 pr-4 py-2 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-primary focus:border-primary text-on-surface w-[250px]">
                </div>
                <button class="bg-surface-container border border-outline-variant text-on-surface p-2 rounded-xl hover:bg-surface-container-high transition-colors" title="Filter Categories">
                    <span class="material-symbols-outlined">filter_list</span>
                </button>
            </div>
        </div>

        <!-- Filter/Categories -->
        <div class="flex gap-2 pb-2 overflow-x-auto hide-scrollbar">
            <button class="px-4 py-1.5 bg-tertiary text-on-tertiary rounded-full text-sm font-bold whitespace-nowrap shadow-sm">All News</button>
            <button class="px-4 py-1.5 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface rounded-full text-sm font-medium whitespace-nowrap transition-colors">Academics</button>
            <button class="px-4 py-1.5 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface rounded-full text-sm font-medium whitespace-nowrap transition-colors">Events</button>
            <button class="px-4 py-1.5 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface rounded-full text-sm font-medium whitespace-nowrap transition-colors">Exams</button>
            <button class="px-4 py-1.5 bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface rounded-full text-sm font-medium whitespace-nowrap transition-colors">General</button>
        </div>

        <div class="space-y-4">
            @forelse($announcements as $index =>announcement)
                @php
                    $isNew = $index < 2; // Mocking "new" status for the first 2
                    $isPinned = $index === 0; // Mocking "pinned" status for the first one
                @endphp
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border {{ $isPinned ? 'border-tertiary' : 'border-outline-variant' }} relative overflow-hidden group hover:shadow-md transition-shadow cursor-pointer">
                    
                    @if($isPinned)
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-tertiary"></div>
                        <div class="absolute top-0 right-0 bg-tertiary text-on-tertiary px-3 py-1 rounded-bl-xl flex items-center gap-1 shadow-sm z-10">
                            <span class="material-symbols-outlined text-[14px]">push_pin</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Pinned</span>
                        </div>
                    @else
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isNew ? 'bg-primary' : 'bg-outline-variant group-hover:bg-primary transition-colors' }}"></div>
                    @endif

                    <div class="p-6 pl-8">
                        <div class="flex items-start justify-between gap-4 mb-3 pr-16">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $isPinned ? 'bg-tertiary-fixed text-tertiary' : 'bg-surface-variant text-on-surface-variant' }}">
                                        {{ $isPinned ? 'Important' : 'General' }}
                                    </span>
                                    @if($isNew)
                                        <span class="w-2 h-2 rounded-full bg-error animate-pulse" title="Unread"></span>
                                    @endif
                                </div>
                                <h3 class="text-headline-sm font-bold text-on-surface group-hover:text-tertiary transition-colors">
                                    {{ $announcement->title }}
                                </h3>
                            </div>
                        </div>
                        
                        <p class="text-body-md font-body-md text-secondary whitespace-pre-line mb-4">{{ $announcement->content }}</p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-outline-variant/50">
                            <div class="flex items-center gap-4 text-label-sm font-label-sm text-secondary">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                                    {{ $announcement->created_at->diffForHumans() }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">account_circle</span>
                                    School Administration
                                </span>
                            </div>
                            
                            @if(strlen($announcement->content) > 100)
                                <button class="text-primary text-sm font-bold hover:underline flex items-center gap-1">
                                    Read More <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-surface-container-lowest rounded-xl p-16 text-center border border-outline-variant border-dashed shadow-sm m-4">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-surface-container mb-4 text-secondary">
                        <span class="material-symbols-outlined text-[40px] opacity-70">campaign</span>
                    </div>
                    <h3 class="text-headline-md font-bold text-on-surface">No Announcements</h3>
                    <p class="text-body-lg text-secondary mt-2 max-w-md mx-auto">You're all caught up! Check back later for school news and updates.</p>
                </div>
            @endforelse

            @if(isset($announcements) && method_exists($announcements, 'links'))
                <div class="mt-8">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</main>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
