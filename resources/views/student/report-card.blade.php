@extends('layouts.app')

@section('title', 'Report Card')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Report Card</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your term results and academic standing</p>
    </div>
    <a href="{{ route('student.report-card.download') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
        <span class="material-symbols-rounded text-[20px]">download</span>
        Download PDF
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
    @if(isset($reportCards) && count($reportCards) > 0)
        @foreach($reportCards as $report)
            <div class="mb-8 last:mb-0">
                <div class="flex justify-between items-end border-b-2 border-gray-900 dark:border-white pb-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider">{{ $report->examType->name ?? 'Term Examination' }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 font-medium">Academic Session: {{ $report->academicYear->year ?? 'Current' }}</p>
                    </div>
                    <div class="text-right">
                        <div class="inline-block bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-lg text-center">
                            <span class="block text-xs uppercase font-bold tracking-wider opacity-70">Grade</span>
                            <span class="block text-2xl font-black">{{ $report->grade ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg text-center">
                        <span class="block text-sm text-gray-500 mb-1">Total Marks</span>
                        <span class="block font-bold text-xl text-gray-900 dark:text-white">{{ $report->total_max }}</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg text-center">
                        <span class="block text-sm text-gray-500 mb-1">Marks Obtained</span>
                        <span class="block font-bold text-xl text-gray-900 dark:text-white">{{ $report->total_obtained }}</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg text-center">
                        <span class="block text-sm text-gray-500 mb-1">Percentage</span>
                        <span class="block font-bold text-xl text-gray-900 dark:text-white">{{ $report->percentage }}%</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg text-center">
                        <span class="block text-sm text-gray-500 mb-1">Class Rank</span>
                        <span class="block font-bold text-xl text-gray-900 dark:text-white">{{ $report->rank ?? '-' }}</span>
                    </div>
                </div>

                @if($report->remarks)
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6 border border-blue-100 dark:border-blue-800">
                    <h4 class="text-sm font-bold text-blue-900 dark:text-blue-300 mb-1 flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-[18px]">comment</span>
                        Teacher's Remarks
                    </h4>
                    <p class="text-blue-800 dark:text-blue-200 text-sm italic">"{{ $report->remarks }}"</p>
                </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="py-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">school</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Report Card Available</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Your term report card has not been generated yet.</p>
        </div>
    @endif
</div>
@endsection
