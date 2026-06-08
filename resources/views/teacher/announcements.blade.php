@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Announcements</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Important notices and updates from administration.</p>
        </div>

        <div class="space-y-4">
            @forelse($announcements as $announcement)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 hover:shadow-sm transition-shadow">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined">campaign</span>
                        </div>
                        <div>
                            <h3 class="text-headline-sm font-headline-sm text-on-surface">{{ $announcement->title }}</h3>
                            <p class="text-label-sm text-secondary">{{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($announcement->priority == 'high')
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold uppercase">High Priority</span>
                    @endif
                </div>
                <div class="mt-4 text-body-md text-on-surface leading-relaxed">
                    {{ $announcement->content }}
                </div>
            </div>
            @empty
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center">
                <span class="material-symbols-outlined text-4xl text-secondary mb-2">notifications_off</span>
                <p class="text-body-lg text-secondary">No recent announcements.</p>
            </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
