@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <a href="{{ route('teacher.digital_learning.quizzes') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-low hover:bg-surface-container transition-colors text-on-surface">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-semibold tracking-wide uppercase">Quiz Results</span>
                <span class="px-3 py-1 bg-surface-container-low text-on-surface-variant rounded-full text-xs font-medium">
                    <span class="material-symbols-rounded text-[14px] align-middle mr-1">class</span>
                    {{ $quiz->class->name ?? 'N/A' }}
                </span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface mb-1">{{ $quiz->title }}</h1>
            <p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-2">
                <span class="material-symbols-rounded text-[18px]">verified</span>
                Passing Marks: <strong class="text-on-surface">{{ $quiz->passing_marks }}</strong> out of {{ $quiz->total_marks }}
            </p>
        </div>
    </div>

    @php
        $passed = $attempts->where('score', '>=', $quiz->passing_marks)->count();
        $total = $attempts->count();
        $failed = $total - $passed;
        $passRate = $total > 0 ? round(($passed / $total) * 100) : 0;
    @endphp

    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Total Attempts -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 flex flex-col hover:shadow-sm transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-rounded text-[24px]">groups</span>
                </div>
            </div>
            <span class="font-headline-xl text-3xl font-bold text-on-surface">{{ $total }}</span>
            <span class="font-label-md text-on-surface-variant font-medium mt-1">Total Submissions</span>
        </div>
        
        <!-- Pass Rate -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 flex flex-col hover:shadow-sm transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-rounded text-[24px]">monitoring</span>
                </div>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="font-headline-xl text-3xl font-bold text-on-surface">{{ $passRate }}</span>
                <span class="text-on-surface-variant font-bold">%</span>
            </div>
            <span class="font-label-md text-on-surface-variant font-medium mt-1">Average Pass Rate</span>
        </div>

        <!-- Passed -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 flex flex-col hover:shadow-sm transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[24px]">workspace_premium</span>
                </div>
            </div>
            <span class="font-headline-xl text-3xl font-bold text-green-700">{{ $passed }}</span>
            <span class="font-label-md text-on-surface-variant font-medium mt-1">Students Passed</span>
        </div>

        <!-- Failed -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 flex flex-col hover:shadow-sm transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[24px]">warning</span>
                </div>
            </div>
            <span class="font-headline-xl text-3xl font-bold text-red-700">{{ $failed }}</span>
            <span class="font-label-md text-on-surface-variant font-medium mt-1">Students Failed</span>
        </div>
    </div>

    <!-- Results Data Grid -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
        <div class="p-6 border-b border-outline-variant flex items-center justify-between bg-surface-container-lowest">
            <h2 class="text-xl font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-rounded text-primary">format_list_bulleted</span>
                Detailed Submissions
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50 border-b border-outline-variant">
                        <th class="py-4 px-6 font-label-md font-semibold text-on-surface-variant uppercase tracking-wider text-xs">Student Profile</th>
                        <th class="py-4 px-6 font-label-md font-semibold text-on-surface-variant uppercase tracking-wider text-xs">Submitted At</th>
                        <th class="py-4 px-6 font-label-md font-semibold text-on-surface-variant uppercase tracking-wider text-xs">Performance</th>
                        <th class="py-4 px-6 font-label-md font-semibold text-on-surface-variant uppercase tracking-wider text-xs w-48">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant bg-surface-container-lowest">
                    @forelse($attempts as $attempt)
                        @php
                            $studentName = optional(optional($attempt->student)->user)->name ?? 'Unknown Student';
                            $initials = collect(explode(' ', $studentName))->map(function($word) { return strtoupper(substr($word, 0, 1)); })->take(2)->implode('');
                            $isPass = $attempt->score >= $quiz->passing_marks;
                        @endphp
                        <tr class="hover:bg-primary/5 transition-colors duration-200 group">
                            <!-- Student Profile -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm shadow-inner shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="font-body-md font-bold text-on-surface group-hover:text-primary transition-colors">{{ $studentName }}</div>
                                        <div class="text-xs text-on-surface-variant mt-0.5">Attempt ID: #{{ str_pad($attempt->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Submitted At -->
                            <td class="py-4 px-6 font-body-sm text-on-surface-variant">
                                @if($attempt->submitted_at)
                                    <div class="flex flex-col">
                                        <span class="font-medium text-on-surface">{{ $attempt->submitted_at->format('M d, Y') }}</span>
                                        <span class="text-xs mt-0.5">{{ $attempt->submitted_at->format('h:i A') }}</span>
                                    </div>
                                @else
                                    <span class="italic opacity-70">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Performance -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-2 w-full max-w-[200px]">
                                    <div class="flex justify-between items-end">
                                        <span class="font-bold text-on-surface">{{ number_format($attempt->score, 1) }} <span class="text-xs text-on-surface-variant font-normal">/ {{ $quiz->total_marks }} pts</span></span>
                                        <span class="text-xs font-bold {{ $isPass ? 'text-green-600' : 'text-red-600' }}">{{ number_format($attempt->percentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-surface-variant h-1.5 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $isPass ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ min(100, max(0, $attempt->percentage)) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="py-4 px-6">
                                @if($isPass)
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full shadow-sm">
                                        <span class="material-symbols-rounded text-[16px]">verified</span>
                                        <span class="text-xs font-bold uppercase tracking-wide">Passed</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full shadow-sm">
                                        <span class="material-symbols-rounded text-[16px]">cancel</span>
                                        <span class="text-xs font-bold uppercase tracking-wide">Failed</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 px-6 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant mb-4">
                                        <span class="material-symbols-rounded text-[40px]">inbox</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-on-surface mb-1">No Submissions Yet</h3>
                                    <p class="text-sm text-on-surface-variant max-w-sm">When students complete this quiz, their results and performance metrics will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
