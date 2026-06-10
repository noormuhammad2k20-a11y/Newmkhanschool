@extends('layouts.app')

@section('title', 'Report Card')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-label-md font-label-md text-secondary mb-2">
                    <a href="{{ route('parent.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <a href="{{ route('parent.children') }}" class="hover:text-primary transition-colors">My Children</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="text-on-surface">Report Card</span>
                </nav>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Report Card</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">{{ $student->first_name }} {{ $student->last_name }} (Class {{ $student->currentClass->name ?? '' }} {{ $student->currentSection->name ?? '' }})</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('parent.children') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Children
                </a>
                @if($reportCard)
                    <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface rounded-lg font-label-md hover:bg-surface-container-highest transition-colors shadow-sm hidden sm:flex">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                        Print Report
                    </button>
                    <a href="{{ route('parent.child.report-card.download', $student->id ?? 0) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Download PDF
                    </a>
                @endif
            </div>
        </div>

        @if(!$reportCard)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center text-center py-20 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center text-secondary mb-4">
                    <span class="material-symbols-outlined text-[40px]">description</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface mb-2">No Report Card Available</h3>
                <p class="text-body-lg font-body-lg text-secondary max-w-md">The finalized report card for this student has not been generated or published yet.</p>
            </div>
        @else
            <div class="print-container space-y-xl">
                <!-- Print Header -->
                <div class="hidden print-show text-center border-b-2 border-primary pb-6 mb-8">
                    <h1 class="text-3xl font-bold text-on-surface uppercase tracking-widest mb-2">Official Report Card</h1>
                    <h2 class="text-xl text-secondary">{{ $student->first_name }} {{ $student->last_name }}</h2>
                    <p class="text-secondary mt-1">Class {{ $student->currentClass->name ?? '' }} {{ $student->currentSection->name ?? '' }} | Reg: {{ $student->admission_no }}</p>
                </div>

                <!-- Academic Summary Card -->
                <div class="bg-primary border border-primary rounded-xl p-lg flex flex-col text-on-primary shadow-md print:bg-white print:border-outline-variant print:text-on-surface print:shadow-none relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full print:hidden"></div>
                    <div class="absolute right-20 -bottom-10 w-24 h-24 bg-white opacity-10 rounded-full print:hidden"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between gap-8">
                        <div class="flex-1">
                            <h3 class="text-title-lg font-title-lg mb-6 opacity-90 print:text-secondary">Academic Summary</h3>
                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <p class="font-label-sm text-label-sm opacity-80 uppercase tracking-wider mb-1 print:text-secondary">Overall Grade</p>
                                    <p class="font-headline-lg text-headline-lg font-bold">{{ $reportCard->overall_grade ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="font-label-sm text-label-sm opacity-80 uppercase tracking-wider mb-1 print:text-secondary">Total Percentage</p>
                                    <p class="font-headline-lg text-headline-lg font-bold">{{ $reportCard->total_percentage ?? 'N/A' }}%</p>
                                </div>
                                <div>
                                    <p class="font-label-sm text-label-sm opacity-80 uppercase tracking-wider mb-1 print:text-secondary">Class Rank</p>
                                    <p class="font-headline-lg text-headline-lg font-bold">{{ $reportCard->rank ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($reportCard->remarks)
                            <div class="flex-1 md:border-l md:border-primary-fixed/30 md:pl-8 pt-6 md:pt-0 border-t md:border-t-0 border-primary-fixed/30 print:border-outline-variant">
                                <p class="font-label-sm text-label-sm opacity-80 uppercase tracking-wider mb-3 flex items-center gap-2 print:text-secondary">
                                    <span class="material-symbols-outlined text-[18px]">format_quote</span>
                                    Teacher Remarks
                                </p>
                                <p class="font-body-lg text-body-lg leading-relaxed italic">{{ $reportCard->remarks }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Detailed Marks Table -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                    <div class="p-md border-b border-outline-variant bg-surface-bright">
                        <h3 class="text-headline-md font-headline-md text-on-surface">Subject-wise Performance</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                    <th class="py-4 px-6 font-semibold">Subject</th>
                                    <th class="py-4 px-6 font-semibold text-center">Marks Obtained</th>
                                    <th class="py-4 px-6 font-semibold text-center">Total Marks</th>
                                    <th class="py-4 px-6 font-semibold text-center">Grade</th>
                                    <th class="py-4 px-6 font-semibold">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @forelse($marks as $mark)
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="py-4 px-6 text-title-md font-title-md text-on-surface font-medium">{{ $mark->subject_name }}</td>
                                        <td class="py-4 px-6 text-center text-body-lg font-body-lg text-on-surface font-semibold">{{ $mark->marks_obtained }}</td>
                                        <td class="py-4 px-6 text-center text-body-md font-body-md text-secondary">{{ $mark->max_marks ?? 100 }}</td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-flex items-center justify-center min-w-[40px] px-2 py-1 rounded-md bg-surface-container-highest text-on-surface font-title-md text-title-md font-bold border border-outline-variant">
                                                {{ $mark->grade ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-body-md font-body-md text-secondary italic">{{ $mark->remarks ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-secondary">
                                            <span class="material-symbols-outlined text-[32px] mb-2 opacity-50">hourglass_empty</span>
                                            <p class="font-body-md text-body-md">No subject marks have been recorded for this report card.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Print Footer -->
                <div class="hidden print-show mt-16 pt-8 border-t border-outline-variant flex justify-between px-8">
                    <div class="text-center w-48">
                        <div class="border-b border-outline-variant h-10 mb-2"></div>
                        <p class="text-label-md font-label-md text-secondary">Class Teacher Signature</p>
                    </div>
                    <div class="text-center w-48">
                        <div class="border-b border-outline-variant h-10 mb-2"></div>
                        <p class="text-label-md font-label-md text-secondary">Principal Signature</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>

<style>
    @media print {
        body { background: white !important; }
        .print-container { width: 100%; }
        .print-show { display: flex !important; }
        nav, .material-symbols-outlined:not(.print-show .material-symbols-outlined), button, a { display: none !important; }
        .shadow-sm, .shadow-md { box-shadow: none !important; }
    }
    .print-show { display: none; }
</style>
@endsection
