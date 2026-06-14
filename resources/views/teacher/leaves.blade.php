@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Leave Requests</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Apply for leave and track your previous requests.</p>
            </div>
            <button onclick="document.getElementById('createModal').classList.remove('hidden'); document.body.style.overflow = 'hidden';" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-dark flex items-center gap-2">
                <span class="material-symbols-outlined">add</span> Apply Leave
            </button>
        </div>



<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Leave Type</th>
                            <th class="py-3 px-4 font-semibold">Start Date</th>
                            <th class="py-3 px-4 font-semibold">End Date</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold text-right">Applied On</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($leaves as $leave)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-medium text-on-surface">{{ $leave->leave_type }}</td>
                            <td class="py-3 px-4 text-secondary">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M, Y') }}</td>
                            <td class="py-3 px-4 text-secondary">{{ \Carbon\Carbon::parse($leave->end_date)->format('d M, Y') }}</td>
                            <td class="py-3 px-4">
                                @if($leave->status == 'Approved')
                                    <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded text-xs font-medium">Approved</span>
                                @elseif($leave->status == 'Rejected')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">Rejected</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">Pending</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-secondary text-right">{{ \Carbon\Carbon::parse($leave->created_at)->format('d M, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-secondary">
                                No leave requests found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-xl max-w-md w-full">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">Apply for Leave</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="text-secondary hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('teacher.leaves.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-label-md text-on-surface mb-1">Leave Type</label>
                    <select name="leave_type" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        <option value="Sick">Sick Leave</option>
                        <option value="Casual">Casual Leave</option>
                        <option value="Emergency">Emergency Leave</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Start Date</label>
                        <input type="date" name="start_date" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">End Date</label>
                        <input type="date" name="end_date" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="px-4 py-2 border border-outline-variant rounded text-on-surface hover:bg-surface-container-low transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded hover:bg-primary-dark transition-colors">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
