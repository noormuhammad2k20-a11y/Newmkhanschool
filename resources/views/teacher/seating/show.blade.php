@extends('layouts.app')

@section('content')
<div class="px-md py-lg max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">{{ $plan->name }}</h2>
            <p class="text-body-md text-secondary">Class: {{ $plan->class->name }} - {{ $plan->section->name }}</p>
        </div>
        <div class="flex gap-sm items-center">
            <button onclick="window.print()" class="px-md py-sm bg-surface-container-high text-on-surface rounded-lg font-label-md hover:bg-surface-container-highest transition-colors flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">print</span> Print
            </button>
            <a href="{{ route('teacher.seating.edit', $plan->id) }}" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">edit</span> Edit Plan
            </a>
            <a href="{{ route('teacher.seating.index') }}" class="px-md py-sm text-secondary hover:underline">Back</a>
        </div>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm overflow-x-auto print:shadow-none print:border-none print:p-0">
        <div class="flex justify-center mb-xl">
            <div class="w-2/3 h-12 bg-surface-container-highest rounded-lg border-2 border-outline flex items-center justify-center font-bold text-secondary uppercase tracking-widest print:border-black print:bg-white print:text-black">
                Teacher's Desk / Whiteboard
            </div>
        </div>

        <div class="grid-container mx-auto" style="display: grid; grid-template-columns: repeat({{ $plan->cols }}, minmax(120px, 1fr)); gap: 1rem; width: max-content;">
            @for($r = 1; $r <= $plan->rows; $r++)
                @for($c = 1; $c <= $plan->cols; $c++)
                    @php
                        $student = $grid[$r][$c] ?? null;
                    @endphp
                    <div class="seat-zone border-2 border-outline-variant rounded-xl p-sm min-h-[100px] flex flex-col items-center justify-center bg-surface-container-lowest print:border-black print:bg-white relative">
                        <span class="absolute top-1 left-2 text-[10px] text-outline font-mono">R{{ $r }}C{{ $c }}</span>
                        
                        @if($student)
                        <div class="student-card w-full text-center mt-2">
                            <div class="w-10 h-10 mx-auto rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm mb-xs print:border print:border-black print:bg-white print:text-black">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                            <p class="text-sm font-semibold leading-tight">{{ $student->first_name }}</p>
                            <p class="text-xs text-secondary">{{ $student->last_name }}</p>
                        </div>
                        @else
                        <div class="w-full h-full flex items-center justify-center text-outline-variant">
                            <span class="material-symbols-outlined text-[32px]">event_seat</span>
                        </div>
                        @endif
                    </div>
                @endfor
            @endfor
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #content, #content * { visibility: visible; }
    #content { position: absolute; left: 0; top: 0; width: 100%; }
    .print\:hidden { display: none !important; }
}
</style>
@endsection
