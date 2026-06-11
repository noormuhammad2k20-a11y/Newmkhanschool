@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col h-full">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-lg print:mb-sm">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">{{ $plan->name }}</h2>
            <p class="text-body-md text-secondary mt-1">Class: <strong class="text-on-surface">{{ $plan->class->name }} - {{ $plan->section->name }}</strong></p>
        </div>
        <div class="flex flex-wrap gap-sm items-center w-full sm:w-auto">
            <button onclick="window.print()" class="flex-1 sm:flex-none justify-center px-md py-sm bg-surface-container-high text-on-surface rounded-lg font-label-md hover:bg-surface-container-highest transition-colors flex items-center gap-xs print:hidden shadow-sm">
                <span class="material-symbols-outlined text-[18px]">print</span> Print
            </button>
            <a href="{{ route('teacher.seating.edit', $plan->id) }}" class="flex-1 sm:flex-none justify-center px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant flex items-center gap-xs print:hidden shadow-sm transition-colors">
                <span class="material-symbols-outlined text-[18px]">edit</span> Edit Plan
            </a>
            <a href="{{ route('teacher.seating.index') }}" class="flex-1 sm:flex-none text-center px-md py-sm border border-outline text-secondary hover:text-on-surface rounded-lg font-label-md hover:bg-surface-container-highest transition-colors print:hidden">Back</a>
        </div>
    </div>

    <!-- Seating Grid Wrapper -->
    <div class="flex-1 min-h-0 bg-surface border border-outline-variant rounded-2xl p-md lg:p-xl shadow-sm flex flex-col relative print:shadow-none print:border-none print:p-0 print:overflow-visible">
        <div class="flex justify-center mb-xl shrink-0 print:mb-md">
            <div class="w-full max-w-lg h-12 bg-surface-container-highest rounded-xl border border-outline-variant flex items-center justify-center font-bold text-secondary uppercase tracking-widest text-sm print:border-black print:bg-white print:text-black">
                <span class="material-symbols-outlined mr-2">cast_for_education</span>
                Teacher's Desk / Whiteboard
            </div>
        </div>

        <!-- Scrollable Grid Area -->
        <div class="flex-1 overflow-auto custom-scrollbar w-full rounded-lg border border-outline-variant bg-surface-container-lowest p-md lg:p-lg relative print:border-none print:bg-white print:overflow-visible">
            <div class="grid-container mx-auto" style="display: grid; grid-template-columns: repeat({{ $plan->cols }}, minmax(100px, 1fr)); gap: 1rem; min-width: max-content;">
                @for($r = 1; $r <= $plan->rows; $r++)
                    @for($c = 1; $c <= $plan->cols; $c++)
                        @php
                            $student = $grid[$r][$c] ?? null;
                        @endphp
                        <div class="seat-zone border-2 border-outline-variant rounded-xl p-xs min-h-[120px] flex flex-col items-center justify-center bg-surface print:border-black print:bg-white relative">
                            <span class="absolute top-1 left-2 text-[10px] text-outline font-mono font-medium">R{{ $r }}C{{ $c }}</span>
                            
                            @if($student)
                            <div class="student-card w-full h-full flex flex-col justify-center items-center bg-primary-container text-on-primary-container rounded-lg p-xs text-center mt-4 print:border print:border-black print:bg-white print:text-black">
                                <div class="w-10 h-10 mx-auto rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm mb-1 print:border print:border-black print:bg-white print:text-black">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                                <p class="text-xs font-semibold leading-tight line-clamp-2 px-1 w-full" title="{{ $student->first_name }} {{ $student->last_name }}">{{ $student->first_name }}</p>
                                <p class="text-[10px] text-secondary mt-1">{{ $student->last_name }}</p>
                            </div>
                            @else
                            <div class="w-full h-full flex items-center justify-center text-outline-variant mt-2">
                                <span class="material-symbols-outlined text-[28px] opacity-40">event_seat</span>
                            </div>
                            @endif
                        </div>
                    @endfor
                @endfor
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(107, 114, 128, 0.8);
}

@media print {
    body * { visibility: hidden; }
    #content, #content * { visibility: visible; }
    #content { position: absolute; left: 0; top: 0; width: 100%; }
    .print\:hidden { display: none !important; }
    
    .grid-container {
        gap: 4px !important;
    }
    .seat-zone {
        min-height: 90px !important;
        padding: 2px !important;
    }
    .student-card {
        margin-top: 12px !important;
        padding: 2px !important;
    }
}
</style>
@endsection
