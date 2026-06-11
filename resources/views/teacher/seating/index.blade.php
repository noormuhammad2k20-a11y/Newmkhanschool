@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">Seating Plans</h2>
            <p class="text-body-md text-secondary">Manage seating arrangements for your classes.</p>
        </div>
        <div>
            <a href="{{ route('teacher.seating.create') }}" class="inline-flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors">
                <span class="material-symbols-outlined" data-icon="add">add</span>
                New Seating Plan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-md p-md bg-surface-container-high border-l-4 border-primary text-on-surface rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
        @forelse($plans as $plan)
        <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
            <div class="flex justify-between items-start mb-sm">
                <h3 class="text-headline-sm font-semibold text-on-surface">{{ $plan->name }}</h3>
                <form method="POST" action="{{ route('teacher.seating.destroy', $plan->id) }}" onsubmit="return confirm('Delete this plan?');">
                    @csrf
                    @method('DELETE')
                    <button class="text-error hover:text-error-container" title="Delete Plan"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                </form>
            </div>
            <p class="text-body-md text-secondary mb-xs">Class: {{ $plan->class->name }} - {{ $plan->section->name }}</p>
            <p class="text-body-md text-secondary mb-md">Grid: {{ $plan->rows }} x {{ $plan->cols }}</p>
            
            <div class="flex gap-sm">
                <a href="{{ route('teacher.seating.show', $plan->id) }}" class="flex-1 text-center px-sm py-xs bg-surface-container-high text-on-surface rounded font-label-md hover:bg-surface-container-highest transition-colors">View</a>
                <a href="{{ route('teacher.seating.edit', $plan->id) }}" class="flex-1 text-center px-sm py-xs bg-primary-container text-on-primary-container rounded font-label-md hover:bg-primary hover:text-white transition-colors">Edit Plan</a>
            </div>
        </div>
        @empty
        <div class="col-span-full p-xl text-center bg-surface border border-outline-variant rounded-xl">
            <p class="text-secondary mb-sm">No seating plans found.</p>
            <a href="{{ route('teacher.seating.create') }}" class="text-primary font-medium hover:underline">Create your first seating plan</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
