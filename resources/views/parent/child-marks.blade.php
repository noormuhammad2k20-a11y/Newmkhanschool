@extends('layouts.app')

@section('title', 'Child Marks')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Academic Marks</h2>
                <p class="text-body-md font-body-md text-secondary mt-1">Viewing marks for {{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
        <a href="{{ route('parent.dashboard') }}" class="bg-surface border border-outline-variant text-on-surface px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center justify-center">
            <span class="material-symbols-rounded text-[18px] mr-1">arrow_back</span>
            Back to Dashboard
        </a>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm flex-1 flex flex-col mb-lg">
    @if(isset($marks) && count($marks) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface border-b border-outline-variant">
                    <tr>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Subject</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Exam Type</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Marks Obtained</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Total Marks</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Percentage</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($marks as $mark)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface">{{ $mark->subject->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-primary-container text-on-primary-container">
                                {{ $mark->examType->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface font-semibold">{{ $mark->marks_obtained }}</td>
                        <td class="py-3 px-4 text-body-md font-body-md text-secondary">{{ $mark->total_marks }}</td>
                        <td class="py-3 px-4">
                            @php
                                $percent = $mark->total_marks > 0 ? round(($mark->marks_obtained / $mark->total_marks) * 100, 2) : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="font-body-md text-body-md text-on-surface-variant">{{ $percent }}%</span>
                                <div class="w-16 h-2 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full {{ $percent >= 50 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface-variant">{{ $mark->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-xl text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-low mb-4 text-secondary">
                <span class="material-symbols-rounded text-3xl">assignment</span>
            </div>
            <h3 class="text-headline-md font-headline-md text-on-surface">No Marks Found</h3>
            <p class="text-body-md font-body-md text-secondary mt-1">There are no academic marks published for this student yet.</p>
        </div>
    @endif
    </div>
    </div>
</main>
@endsection
