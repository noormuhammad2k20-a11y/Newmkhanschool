@extends('layouts.app')

@section('title', 'Child Attendance')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Attendance Record</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Viewing attendance for {{ $student->first_name }} {{ $student->last_name }}</p>
    </div>
    <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
        <span class="material-symbols-rounded text-[18px] mr-1">arrow_back</span>
        Back to Dashboard
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if(isset($attendances) && count($attendances) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 font-medium border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($attendances as $attendance)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($attendance->date)->format('l, M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @if($attendance->status === 'Present')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Present</span>
                            @elseif($attendance->status === 'Absent')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Absent</span>
                            @elseif($attendance->status === 'Late')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Late</span>
                            @elseif($attendance->status === 'Half Day')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">Half Day</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ $attendance->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(method_exists($attendances, 'links'))
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $attendances->links() }}
        </div>
        @endif
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">event_busy</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Attendance Records</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">There are no attendance records for this student yet.</p>
        </div>
    @endif
</div>
@endsection
