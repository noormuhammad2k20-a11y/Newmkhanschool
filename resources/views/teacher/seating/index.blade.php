@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="p-lg border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-headline-sm font-semibold text-on-surface">Seating Plans</h2>
                <p class="text-body-md text-secondary mt-1">Manage seating arrangements and exam grids for your classes.</p>
            </div>
            <div>
                <a href="{{ route('teacher.seating.create') }}" class="inline-flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    New Seating Plan
                </a>
            </div>
        </div>

        <div class="p-lg bg-surface-container-lowest">

<!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
                @forelse($plans as $plan)
                <div class="bg-surface border border-outline-variant rounded-xl flex flex-col h-full shadow-sm hover:border-outline transition-colors">
                    <div class="p-md flex flex-col h-full">
                        
                        <!-- Card Header -->
                        <div class="flex justify-between items-start mb-md">
                            <div>
                                <h3 class="text-title-lg font-bold text-on-surface line-clamp-1" title="{{ $plan->name }}">{{ $plan->name }}</h3>
                                <p class="text-label-md text-primary mt-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">grid_on</span>
                                    {{ $plan->rows }} x {{ $plan->cols }} Grid
                                </p>
                            </div>
                            <form method="POST" action="{{ route('teacher.seating.destroy', $plan->id) }}" data-confirm="Are you sure you want to delete this seating plan?" class="ml-2 shrink-0">
                                @csrf
                                @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded-full text-secondary hover:bg-error-container hover:text-error transition-colors" title="Delete Plan">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Card Details -->
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md mb-lg space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-body-sm text-secondary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">class</span> Class
                                </span>
                                <strong class="text-label-md text-on-surface">{{ $plan->class->name }} - {{ $plan->section->name }}</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-body-sm text-secondary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">group</span> Seats
                                </span>
                                <strong class="text-label-md text-on-surface">{{ $plan->rows * $plan->cols }} Total</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-body-sm text-secondary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">event_seat</span> Mode
                                </span>
                                <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 bg-primary-container text-on-primary-container rounded-full">
                                    {{ $plan->mode ?? 'Regular' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Card Actions -->
                        <div class="flex gap-sm mt-auto pt-xs">
                            <a href="{{ route('teacher.seating.show', $plan->id) }}" class="flex-1 flex justify-center items-center gap-xs px-md py-sm bg-surface text-on-surface border border-outline-variant rounded-lg font-label-md hover:bg-surface-container-high transition-colors">
                                <span class="material-symbols-outlined text-[18px]">visibility</span> View
                            </a>
                            <a href="{{ route('teacher.seating.edit', $plan->id) }}" class="flex-1 flex justify-center items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span> Edit
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-xl px-lg text-center bg-surface border border-dashed border-outline-variant rounded-lg">
                    <span class="material-symbols-outlined text-[48px] text-outline-variant mb-sm">event_seat</span>
                    <h3 class="text-title-md font-semibold text-on-surface mb-xs">No Seating Plans Found</h3>
                    <p class="text-body-md text-secondary mb-md">You haven't created any seating arrangements yet.</p>
                    <a href="{{ route('teacher.seating.create') }}" class="inline-flex items-center gap-xs px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        Create Seating Plan
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
