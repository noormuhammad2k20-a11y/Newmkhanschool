@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Requests</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and apply for leaves</p>
    </div>
    <button type="button" onclick="document.getElementById('leaveModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <span class="material-symbols-rounded text-[20px]">add</span>
        Apply Leave
    </button>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200">
    {{ session('success') }}
</div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if(isset($leaves) && count($leaves) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 font-medium border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Leave Type</th>
                        <th class="px-6 py-4">From</th>
                        <th class="px-6 py-4">To</th>
                        <th class="px-6 py-4">Reason</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($leaves as $leave)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $leave->leave_type }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 max-w-xs truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                        <td class="px-6 py-4">
                            @if($leave->status === 'Approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Approved</span>
                            @elseif($leave->status === 'Rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Rejected</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">event_busy</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Leaves Found</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">You haven't applied for any leaves yet.</p>
        </div>
    @endif
</div>

<!-- Leave Application Modal -->
<div id="leaveModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Apply for Leave</h3>
            <button type="button" onclick="document.getElementById('leaveModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('student.leave.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type</label>
                    <select name="leave_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Casual Leave">Casual Leave</option>
                        <option value="Emergency">Emergency</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                        <input type="date" name="start_date" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                        <input type="date" name="end_date" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason</label>
                    <textarea name="reason" rows="3" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Briefly explain the reason..."></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('leaveModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Submit Request</button>
            </div>
        </form>
    </div>
</div>
@endsection
