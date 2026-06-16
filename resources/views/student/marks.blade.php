@extends('layouts.app')
@section('title', 'My Marks')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header & Actions -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Academic Performance</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">View your marks and academic progress</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('student.report-card.download') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-fixed text-primary rounded-xl font-bold hover:bg-primary-fixed-dim transition-colors">
                    <span class="material-symbols-outlined text-[18px]">download</span> Download Report Card
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-high border border-outline-variant text-on-surface rounded-xl font-bold hover:bg-surface-container-highest transition-colors">
                    <span class="material-symbols-outlined text-[18px]">print</span> Print Result
                </button>
            </div>
        </div>

        <!-- Summary & Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            {{-- Grade & GPA Summary --}}
            <div class="bg-primary text-on-primary rounded-xl p-6 flex flex-col justify-between overflow-hidden relative group">
                <div class="relative z-10">
                    <h3 class="text-title-md font-bold uppercase tracking-wider mb-6 opacity-90">Overall Performance</h3>
                    <div class="flex items-baseline gap-3 mb-2">
                        <span class="text-[64px] font-black leading-none">A+</span>
                        <span class="text-headline-md opacity-80">Grade</span>
                    </div>
                    <div class="flex items-center gap-4 text-body-lg mt-4">
                        <div class="bg-on-primary/20 px-3 py-1.5 rounded-lg">
                            <span class="opacity-80 text-sm">CGPA</span>
                            <span class="font-bold ml-1">3.8 / 4.0</span>
                        </div>
                        <div class="bg-on-primary/20 px-3 py-1.5 rounded-lg">
                            <span class="opacity-80 text-sm">Rank</span>
                            <span class="font-bold ml-1">5th</span>
                        </div>
                    </div>
                </div>
                <span class="material-symbols-outlined absolute -bottom-10 -right-10 text-[180px] opacity-10 group-hover:scale-110 transition-transform duration-500 z-0">school</span>
            </div>

            {{-- Performance Analytics --}}
            <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                <h3 class="text-headline-sm font-bold text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">insights</span> Performance Trend
                </h3>
                <div class="flex h-[140px] items-end gap-4 relative mt-4">
                    <!-- Y Axis -->
                    <div class="flex flex-col justify-between h-full text-[10px] text-secondary absolute -left-2 top-0 py-1">
                        <span>100%</span>
                        <span>50%</span>
                        <span>0%</span>
                    </div>
                    <div class="w-full h-full border-b border-outline-variant ml-6 flex items-end justify-between px-2 gap-4 pb-0 relative">
                        <div class="absolute w-full h-[1px] bg-outline-variant/30 top-1/2 left-0"></div>
                        
                        <!-- Bars -->
                        <div class="w-full bg-primary/20 hover:bg-primary transition-colors rounded-t-md relative group cursor-pointer" style="height: 65%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Monthly Test 1 - 65%</div>
                        </div>
                        <div class="w-full bg-primary/40 hover:bg-primary transition-colors rounded-t-md relative group cursor-pointer" style="height: 80%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Term 1 - 80%</div>
                        </div>
                        <div class="w-full bg-primary/60 hover:bg-primary transition-colors rounded-t-md relative group cursor-pointer" style="height: 72%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Monthly Test 2 - 72%</div>
                        </div>
                        <div class="w-full bg-primary hover:bg-primary transition-colors rounded-t-md relative group cursor-pointer" style="height: 92%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Term 2 - 92%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marks Details Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <!-- Table Header & Filters -->
            <div class="p-4 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-bright">
                <h3 class="text-headline-sm font-bold text-on-surface">Subject-wise Marks</h3>
                <div class="flex gap-2">
                    <select class="bg-surface-container border border-outline-variant text-on-surface text-sm rounded-lg focus:ring-primary focus:border-primary block p-2">
                        <option>All Exam Types</option>
                        @foreach($examTypes ?? [] as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(isset($marks) && count($marks) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-body-md text-on-surface">
                        <thead class="bg-surface-bright text-on-surface-variant font-label-md text-label-md border-b border-outline-variant">
                            <tr>
                                <th class="px-6 py-4 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Exam Type</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Marks Obtained</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-4 uppercase tracking-wider">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach($marks as $examGroupMarks)
                                @foreach($examGroupMarks as $mark)
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="px-6 py-4 font-bold text-on-surface">{{ $mark->subject->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-primary-fixed text-primary border border-primary-fixed-dim">
                                            {{ $mark->examType->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-baseline gap-1">
                                            <span class="font-black text-on-surface text-body-lg">{{ $mark->marks_obtained }}</span>
                                            <span class="text-secondary text-label-sm">/ {{ $mark->total_marks }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 w-[200px]">
                                        @php
                                            $percent = $mark->total_marks > 0 ? round(($mark->marks_obtained / $mark->total_marks) * 100, 2) : 0;
                                            $isPass = $percent >= 50;
                                            $isExcellent = $percent >= 85;
                                            
                                            $colorClass = $isExcellent ? 'bg-primary' : ($isPass ? 'bg-[#10b981]' : 'bg-error');
                                            $textColor = $isExcellent ? 'text-primary' : ($isPass ? 'text-[#059669]' : 'text-error');
                                        @endphp
                                        <div class="flex items-center gap-3">
                                            <div class="w-full h-2 bg-surface-container-highest rounded-full overflow-hidden flex-1">
                                                <div class="h-full {{ $colorClass }} rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="font-bold text-[12px] {{ $textColor }} w-9 text-right">{{ $percent }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-secondary text-sm">
                                        @if($isExcellent)
                                            <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-tertiary">star</span> Excellent</span>
                                        @elseif($isPass)
                                            <span class="inline-flex items-center gap-1 text-success"><span class="material-symbols-outlined text-[14px]">check_circle</span> Pass</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-error"><span class="material-symbols-outlined text-[14px]">warning</span> Needs Work</span>
                                        @endif
                                        <div class="mt-0.5">{{ $mark->remarks ?? '' }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-secondary m-4">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">assignment</span>
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-1">No Marks Found</h3>
                    <p class="text-body-lg font-body-lg">Your academic marks will appear here once published.</p>
                </div>
            @endif
        </div>
        
        <!-- Print Signatory Boxes -->
        <div class="hidden print:flex justify-between items-end mt-24 pt-8 px-8 w-full border-t border-outline-variant">
            <div class="text-center w-48">
                <div class="border-b-2 border-black mb-2 h-16"></div>
                <span class="text-label-md font-label-md text-on-surface font-bold uppercase">Class Teacher</span>
            </div>
            <div class="text-center w-48">
                <div class="border-b-2 border-black mb-2 h-16"></div>
                <span class="text-label-md font-label-md text-on-surface font-bold uppercase">Parent / Guardian</span>
            </div>
            <div class="text-center w-48">
                <div class="border-b-2 border-black mb-2 h-16"></div>
                <span class="text-label-md font-label-md text-on-surface font-bold uppercase">Headmaster</span>
            </div>
        </div>
    </div>
</main>
@endsection
