@extends('layouts.app')

@section('title', 'Child Attendance')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Attendance Record</h2>
                <p class="text-body-md font-body-md text-secondary mt-1">Viewing attendance for {{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
        <a href="{{ route('parent.dashboard') }}" class="bg-surface border border-outline-variant text-on-surface px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span>
            Back to Dashboard
        </a>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm flex-1 flex flex-col mb-lg">
    @if(isset($attendances) && count($attendances) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface border-b border-outline-variant">
                    <tr>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Date</th>
                        <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($attendances as $attendance)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="py-3 px-4 text-body-md font-body-md text-on-surface">{{ \Carbon\Carbon::parse($attendance->date)->format('l, M d, Y') }}</td>
                        <td class="py-3 px-4">
                            @if($attendance->status === 'Present' || $attendance->status === 'P')
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">Present</span>
                            @elseif($attendance->status === 'Absent' || $attendance->status === 'A')
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">Absent</span>
                            @elseif($attendance->status === 'Late' || $attendance->status === 'L')
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">Late</span>
                            @elseif($attendance->status === 'Half Day' || $attendance->status === 'H')
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-orange-100 text-orange-800">Half Day</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-surface-container-high text-on-surface-variant">{{ $attendance->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(method_exists($attendances, 'links'))
        <div class="p-md border-t border-outline-variant bg-surface-container-lowest">
            {{ $attendances->links() }}
        </div>
        @endif
    @else
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-xl text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-low mb-4 text-secondary">
                <span class="material-symbols-outlined text-3xl">event_busy</span>
            </div>
            <h3 class="text-headline-md font-headline-md text-on-surface">No Attendance Records</h3>
            <p class="text-body-md font-body-md text-secondary mt-1">There are no attendance records for this student yet.</p>
        </div>
    @endif
    </div>
    </div>
</main>
@endsection
