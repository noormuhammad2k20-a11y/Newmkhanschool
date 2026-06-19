@extends('layouts.app')

@section('title', $title ?? 'Module Coming Soon')

@section('content')
        <main class="flex-1 p-lg bg-surface flex items-center justify-center">
            <div class="max-w-lg mx-auto text-center">
                <div class="w-24 h-24 bg-surface-container-high rounded-full flex items-center justify-center mx-auto mb-6 text-secondary">
                    <span class="material-symbols-rounded text-[48px]">{{ $icon ?? 'construction' }}</span>
                </div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2">{{ $title ?? 'Module Coming Soon' }}</h2>
                <p class="text-body-lg font-body-lg text-on-surface-variant">This section is currently under development. Please check back later or contact your administrator if you need immediate access to these features.</p>
                <a href="{{ route('teacher.dashboard') }}" class="inline-flex mt-8 px-lg py-sm border border-outline-variant rounded text-on-surface text-label-md font-label-md hover:bg-surface-container-high transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </main>
@endsection
