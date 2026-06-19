@extends('layouts.app')

@section('title', 'My Achievements & Badges')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Achievements & Badges</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">View your earned badges, certificates, and academic excellence awards.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-xl">
            <!-- Badges Section -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl">
                <h3 class="text-headline-md font-headline-md text-on-surface mb-lg flex items-center gap-2">
                    <span class="material-symbols-rounded text-primary text-[28px]">military_tech</span>
                    My Badges
                </h3>
                
                @if($badges->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <span class="material-symbols-rounded text-[48px] text-outline">shield</span>
                        <p class="mt-3">No badges earned yet. Keep up the good work!</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-md">
                        @foreach($badges as $badge)
                        <div class="bg-surface-container rounded-xl p-md text-center border border-outline-variant hover:shadow-md transition-shadow">
                            <span class="material-symbols-rounded text-[48px] text-tertiary mb-sm">{{ $badge->icon ?? 'stars' }}</span>
                            <h4 class="font-label-lg text-on-surface font-bold">{{ $badge->title }}</h4>
                            <p class="text-body-sm text-secondary mt-1">{{ $badge->badge_type }}</p>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Certificates Section -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl">
                <h3 class="text-headline-md font-headline-md text-on-surface mb-lg flex items-center gap-2">
                    <span class="material-symbols-rounded text-primary text-[28px]">workspace_premium</span>
                    My Certificates
                </h3>
                
                @if($certificates->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <span class="material-symbols-rounded text-[48px] text-outline">description</span>
                        <p class="mt-3">No certificates issued yet.</p>
                    </div>
                @else
                    <div class="space-y-md">
                        @foreach($certificates as $cert)
                        <div class="flex items-center justify-between p-md border border-outline-variant rounded-xl bg-surface hover:bg-surface-container-low transition-colors">
                            <div class="flex items-center gap-md">
                                <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                                    <span class="material-symbols-rounded">workspace_premium</span>
                                </div>
                                <div>
                                    <h4 class="font-title-md text-on-surface font-bold">{{ $cert->documentTemplate->name ?? 'Certificate' }}</h4>
                                    <p class="text-body-sm text-secondary">Issued on {{ $cert->issue_date ? $cert->issue_date->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-sm">
                                <a href="{{ route('verify.certificate', $cert->document_number) }}" target="_blank" class="p-2 rounded-full hover:bg-surface-container-highest text-secondary transition-colors" title="Verify">
                                    <span class="material-symbols-rounded">verified</span>
                                </a>
                                <button class="p-2 rounded-full hover:bg-surface-container-highest text-primary transition-colors" title="Download">
                                    <span class="material-symbols-rounded">download</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
