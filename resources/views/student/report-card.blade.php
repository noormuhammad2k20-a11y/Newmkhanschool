@extends('layouts.app')

@section('title', 'Academic Report Card')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Academic Report Card</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">View your term results, performance analytics, and grade distribution.</p>
            </div>
            
            <form method="GET" action="{{ route('student.report-card') }}" class="flex items-end gap-sm" id="examFilterForm">
                <div class="flex flex-col gap-1 w-full md:w-64">
                    <label class="text-label-md font-label-md text-secondary">Select Examination</label>
                    <div class="relative">
                        <select name="exam_type_id" class="w-full py-2 px-3 border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none pr-10" onchange="document.getElementById('examFilterForm').submit()">
                            <option value="">-- Choose Term / Exam --</option>
                            @foreach($examTypes as $type)
                                <option value="{{ $type->id }}" {{ $examTypeId == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">expand_more</span>
                    </div>
                </div>
            </form>
        </div>

        @if(!$examTypeId)
            {{-- Empty State - No Exam Selected --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                <div class="p-md border-b border-outline-variant bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">school</span>
                        Examination Selection
                    </h3>
                </div>
                <div class="p-2xl flex flex-col items-center justify-center text-center min-h-[350px]">
                    <div class="w-20 h-20 bg-primary-fixed rounded-full flex items-center justify-center mb-lg">
                        <span class="material-symbols-outlined text-[40px] text-primary">assignment</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md font-bold text-on-surface mb-sm">Select an Examination</h3>
                    <p class="text-body-lg font-body-lg text-secondary max-w-lg">Please select an exam type from the dropdown above to view your detailed academic performance, marks, and teacher remarks.</p>
                    <div class="flex flex-wrap items-center justify-center gap-md mt-xl">
                        <div class="flex items-center gap-2 bg-surface border border-outline-variant rounded-xl px-4 py-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">bar_chart</span>
                            <span class="text-label-md font-label-md text-on-surface font-bold">Grade Analytics</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface border border-outline-variant rounded-xl px-4 py-3">
                            <span class="material-symbols-outlined text-secondary text-[20px]">emoji_events</span>
                            <span class="text-label-md font-label-md text-on-surface font-bold">Class Rank</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface border border-outline-variant rounded-xl px-4 py-3">
                            <span class="material-symbols-outlined text-tertiary text-[20px]">download</span>
                            <span class="text-label-md font-label-md text-on-surface font-bold">PDF Export</span>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($marks->isEmpty())
            {{-- Empty State - No Marks Found --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                <div class="p-md border-b border-outline-variant bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-error">analytics</span>
                        Examination Results
                    </h3>
                </div>
                <div class="p-2xl flex flex-col items-center justify-center text-center min-h-[350px]">
                    <div class="w-20 h-20 bg-error-container rounded-full flex items-center justify-center mb-lg">
                        <span class="material-symbols-outlined text-[40px] text-error">hourglass_empty</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md font-bold text-on-surface mb-sm">Results Not Published</h3>
                    <p class="text-body-lg font-body-lg text-secondary max-w-lg">Your results for this examination have not been published yet. Please check back later or contact your class teacher.</p>
                </div>
            </div>
        @else
            {{-- Report Card Actions --}}
            <div class="flex justify-end items-center gap-sm">
                <button onclick="window.print()" class="py-2 px-4 border border-outline-variant rounded-lg text-label-md font-label-md text-secondary hover:bg-surface-container-low transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    Print Report
                </button>
                <a href="{{ route('student.report-card.download', ['exam_type_id' =>examTypeId]) }}" class="py-2 px-4 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:opacity-90 transition-opacity flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Download PDF
                </a>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden print-container">
                
                {{-- School Header --}}
                <div class="bg-surface-bright p-xl border-b border-outline-variant text-center">
                    <div class="w-16 h-16 mx-auto bg-primary text-on-primary rounded-xl flex items-center justify-center mb-md shadow-sm">
                        <span class="material-symbols-outlined text-[32px]">account_balance</span>
                    </div>
                    <h2 class="text-headline-lg font-headline-lg font-black text-on-surface uppercase tracking-wide">{{ $student->school->name ?? 'EduGov Management System' }}</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Academic Performance Report</p>
                    <div class="mt-md inline-block bg-surface-container-lowest px-md py-sm rounded-full border border-outline-variant">
                        <span class="text-label-md font-label-md font-bold text-primary">{{ $academicYear->name ?? 'Current Academic Year' }}</span>
                    </div>
                </div>

                {{-- Student Info Card --}}
                <div class="p-xl border-b border-outline-variant bg-surface-container-lowest">
                    <div class="flex flex-col md:flex-row items-center gap-xl">
                        <div class="w-24 h-24 rounded-full bg-surface-container-high border-4 border-surface shadow-sm overflow-hidden flex-shrink-0 flex items-center justify-center">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-[40px] text-secondary">person</span>
                            @endif
                        </div>
                        
                        <div class="flex-1 w-full grid grid-cols-2 md:grid-cols-4 gap-y-md gap-x-xl">
                            <div>
                                <span class="block text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-1">Student Name</span>
                                <span class="block text-title-md font-title-md font-bold text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</span>
                            </div>
                            <div>
                                <span class="block text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-1">Admission No</span>
                                <span class="block text-title-md font-title-md font-bold text-on-surface">{{ $student->admission_no }}</span>
                            </div>
                            <div>
                                <span class="block text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-1">Class & Section</span>
                                <span class="block text-title-md font-title-md font-bold text-on-surface">{{ $student->currentClass->name ?? 'N/A' }} - {{ $student->currentSection->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-1">Roll Number</span>
                                <span class="block text-title-md font-title-md font-bold text-on-surface">{{ $student->roll_number ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-xl grid grid-cols-1 lg:grid-cols-3 gap-xl">
                    
                    {{-- Left Column: Marks Table --}}
                    <div class="lg:col-span-2 space-y-xl">
                        <div>
                            <h3 class="text-headline-md font-headline-md text-on-surface mb-md flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">menu_book</span>
                                Subject-wise Results
                            </h3>
                            <div class="overflow-x-auto rounded-xl border border-outline-variant">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-surface-container-low border-b border-outline-variant">
                                        <tr>
                                            <th class="p-md text-label-lg font-label-lg font-semibold text-on-surface">Subject</th>
                                            <th class="p-md text-label-lg font-label-lg font-semibold text-on-surface text-center">Max Marks</th>
                                            <th class="p-md text-label-lg font-label-lg font-semibold text-on-surface text-center">Obtained</th>
                                            <th class="p-md text-label-lg font-label-lg font-semibold text-on-surface text-center">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-outline-variant">
                                        @foreach($marks as $mark)
                                        @php
                                            $subPct = $mark->total_marks > 0 ? round(($mark->marks_obtained / $mark->total_marks) * 100, 1) : 0;
                                            $subGrade = match(true) {
                                                $subPct >= 90 => 'A+', $subPct >= 80 => 'A',
                                                $subPct >= 70 => 'B+', $subPct >= 60 => 'B',
                                                $subPct >= 50 => 'C',  $subPct >= 40 => 'D',
                                                default => 'F',
                                            };
                                            $isFail = $subPct < 40;
                                        @endphp
                                        <tr class="hover:bg-surface-container-lowest transition-colors">
                                            <td class="p-md text-body-lg font-body-lg text-on-surface font-semibold">{{ $mark->subject->name ?? 'Unknown' }}</td>
                                            <td class="p-md text-body-md font-body-md text-secondary text-center">{{ $mark->total_marks }}</td>
                                            <td class="p-md text-title-md font-title-md text-center font-bold {{ $isFail ? 'text-error' : 'text-on-surface' }}">
                                                {{ $mark->marks_obtained }}
                                            </td>
                                            <td class="p-md text-center">
                                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-label-md font-label-md font-bold {{ $isFail ? 'bg-error-container text-error' : 'bg-primary-fixed text-primary' }}">
                                                    {{ $subGrade }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Summary & Analytics --}}
                    <div class="space-y-md">
                        <h3 class="text-headline-md font-headline-md text-on-surface mb-md flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">donut_large</span>
                            Academic Summary
                        </h3>

                        <div class="grid grid-cols-2 gap-sm">
                            <div class="bg-surface-container-lowest rounded-xl p-md flex flex-col items-center text-center justify-center border border-outline-variant">
                                <span class="text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-1">Percentage</span>
                                <span class="text-headline-lg font-headline-lg font-bold text-on-surface">{{ $summary['percentage'] }}%</span>
                            </div>
                            <div class="bg-surface-container-lowest rounded-xl p-md flex flex-col items-center text-center justify-center border border-outline-variant">
                                <span class="text-label-sm font-label-sm text-secondary uppercase tracking-wider mb-1">Total Marks</span>
                                <span class="text-headline-lg font-headline-lg font-bold text-on-surface">{{ $summary['obtained'] }}/{{ $summary['total'] }}</span>
                            </div>
                            <div class="bg-primary-fixed rounded-xl p-md flex flex-col items-center text-center justify-center border border-primary/20 col-span-2">
                                <span class="text-label-sm font-label-sm text-primary uppercase tracking-wider mb-1 opacity-80">Overall Grade</span>
                                <span class="text-display-sm font-display-sm font-black text-primary">{{ $summary['grade'] }}</span>
                            </div>
                        </div>

                        {{-- Performance Bar --}}
                        <div class="bg-surface-container-lowest rounded-xl p-md border border-outline-variant mt-md">
                            <div class="flex justify-between items-center mb-sm">
                                <span class="text-label-md font-label-md font-bold text-on-surface">Performance Level</span>
                                <span class="text-label-sm font-label-sm text-secondary">Max 100%</span>
                            </div>
                            <div class="w-full bg-surface-container-highest rounded-full h-4 overflow-hidden shadow-inner">
                                @php
                                    $barColor = match(true) {
                                        $summary['percentage'] >= 80 => 'bg-emerald-600',
                                        $summary['percentage'] >= 60 => 'bg-primary',
                                        $summary['percentage'] >= 40 => 'bg-secondary',
                                        default => 'bg-error',
                                    };
                                @endphp
                                <div class="{{ $barColor }} h-4 rounded-full transition-all duration-1000" style="width: {{ $summary['percentage'] }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-2 text-label-sm font-label-sm text-secondary text-[10px] uppercase">
                                <span>Poor</span>
                                <span>Avg</span>
                                <span>Good</span>
                                <span>Excellent</span>
                            </div>
                        </div>

                        {{-- Class Rank --}}
                        <div class="bg-secondary-container rounded-xl p-md flex items-center justify-between border border-secondary/20">
                            <div>
                                <span class="block text-label-sm font-label-sm text-on-secondary-container uppercase tracking-wider opacity-80 mb-1">Class Rank</span>
                                <span class="block text-headline-sm font-headline-sm font-bold text-on-secondary-container">
                                    {{ $summary['rank'] ? '#' . $summary['rank'] : 'Not Ranked' }}
                                </span>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-surface-container-lowest flex items-center justify-center shadow-sm border border-outline-variant/30">
                                <span class="material-symbols-outlined text-[28px] text-on-secondary-container">emoji_events</span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Signatures Area for Printing --}}
                <div class="p-xl bg-surface-container-lowest mt-xl pt-2xl border-t border-outline-variant hidden print:flex justify-between items-end">
                    <div class="text-center w-48">
                        <div class="border-b border-on-surface mb-2 h-12"></div>
                        <span class="text-label-md font-label-md font-bold uppercase text-on-surface">Class Teacher</span>
                    </div>
                    <div class="text-center w-48">
                        <div class="border-b border-on-surface mb-2 h-12"></div>
                        <span class="text-label-md font-label-md font-bold uppercase text-on-surface">Parent / Guardian</span>
                    </div>
                    <div class="text-center w-48">
                        <div class="border-b border-on-surface mb-2 h-12"></div>
                        <span class="text-label-md font-label-md font-bold uppercase text-on-surface">Principal</span>
                    </div>
                </div>

            </div>
        @endif
    </div>
</main>

<style>
    @media print {
        body { background-color: white !important; }
        nav, header, .sidebar, #examFilterForm, button, a[href*="download"] { display: none !important; }
        main { padding: 0 !important; max-width: 100% !important; margin: 0 !important; background: transparent !important; }
        .print-container { border: none !important; box-shadow: none !important; margin-top: 0 !important; }
        .print\:flex { display: flex !important; }
        .bg-surface-container-lowest { background-color: #fff !important; }
    }
</style>
@endsection
