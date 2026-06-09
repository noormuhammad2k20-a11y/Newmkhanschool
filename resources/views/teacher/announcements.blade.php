@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-surface-container-lowest">
    <div class="max-w-[1440px] mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <h1 class="text-title-lg font-bold text-primary">Announcements</h1>
                <p class="text-body-md text-secondary mt-1">Official updates and notices from the administration.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 px-4 py-2 bg-surface-container-low border border-outline-variant hover:bg-surface-container rounded-lg text-label-md font-medium text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Filter
                </button>
            </div>
        </div>

        <!-- Announcements Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($announcements as $announcement)
                <div class="group bg-surface-container-lowest border border-outline-variant hover:border-outline hover:shadow-md transition-all duration-200 rounded-xl overflow-hidden flex flex-col sm:flex-row relative">
                    
                    <!-- Subtle Left Accent -->
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $announcement->type == 'Event' ? 'bg-tertiary' : 'bg-primary' }}"></div>

                    @if($announcement->image_url)
                        <div class="sm:w-48 h-48 sm:h-auto bg-surface-container-low shrink-0 relative border-r border-outline-variant overflow-hidden">
                            <img src="{{ $announcement->image_url }}" alt="Announcement" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-6 flex-1 flex flex-col min-w-0 pl-7">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wide {{ $announcement->type == 'Event' ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-primary-container text-on-primary-container' }}">
                                <span class="material-symbols-outlined text-[14px]">
                                    {{ $announcement->type == 'Event' ? 'event' : 'campaign' }}
                                </span>
                                {{ $announcement->type }}
                            </span>
                            
                            @if($announcement->priority == 'high')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wide bg-error-container text-on-error-container">
                                    <span class="material-symbols-outlined text-[14px]">priority_high</span>
                                    Urgent
                                </span>
                            @endif
                            
                            <span class="text-label-sm text-secondary ml-auto flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                {{ \Carbon\Carbon::parse($announcement->created_at ?? $announcement->start_date)->format('M d, Y') }}
                            </span>
                        </div>
                        
                        <h3 class="text-title-lg font-bold text-primary mb-2">
                            {{ $announcement->title }}
                        </h3>

                        <p class="text-body-md text-on-surface-variant leading-relaxed line-clamp-3 mb-5">
                            {{ $announcement->content }}
                        </p>

                        <div class="mt-auto pt-4 border-t border-outline-variant flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center text-[10px] font-bold">
                                    AD
                                </div>
                                <span class="text-label-md font-medium text-on-surface">Administration</span>
                            </div>
                            
                            @if($announcement->type == 'Event' && $announcement->location)
                                <div class="flex items-center gap-1.5 text-label-sm text-secondary bg-surface-container-low px-2 py-1 rounded-md border border-outline-variant">
                                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                                    {{ $announcement->location }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-16 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-surface-variant rounded-full flex items-center justify-center text-secondary mb-4">
                        <span class="material-symbols-outlined text-3xl">inbox</span>
                    </div>
                    <h3 class="text-title-lg font-bold text-primary mb-2">No Announcements</h3>
                    <p class="text-body-md text-secondary">There are currently no official notices to display.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
