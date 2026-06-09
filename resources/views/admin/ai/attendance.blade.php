@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface p-lg">
    <div class="max-w-max-width mx-auto">
        
        <div class="flex justify-between items-end mb-lg">
            <div>
                <h2 class="font-headline-xl text-headline-xl font-bold text-on-surface">Smart Attendance Analytics</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-sm">AI-driven insights and anomaly detection for student and teacher attendance.</p>
            </div>
            <div>
                <form action="{{ route('admin.ai.attendance.predict') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary font-label-md py-2 px-4 rounded-full shadow transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined">auto_awesome</span>
                        Run AI Analysis
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-[#d3e2ed] border border-[#bac9d3] text-[#0f1d25] px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                <span class="block sm:inline font-body-md">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Patterns Section -->
        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-lg mb-lg">
            <h3 class="font-headline-md text-headline-md text-on-surface mb-md">Attendance Patterns</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                @forelse($patterns as $pattern)
                <div class="bg-surface rounded-lg p-md border border-outline-variant">
                    <div class="flex items-center gap-sm mb-sm">
                        <span class="material-symbols-outlined text-primary">insights</span>
                        <h4 class="font-label-md text-label-md text-on-surface">{{ ucfirst($pattern->entity_type) }} Pattern</h4>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-sm">{{ $pattern->pattern_key }} ({{ str_replace('_', ' ', $pattern->pattern_type) }})</p>
                    <div class="flex justify-between items-end">
                        <div>
                            <span class="font-headline-lg text-error">{{ $pattern->absence_percentage }}%</span>
                            <span class="font-label-md text-secondary">Absence Rate</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-xl">
                    <p class="text-secondary font-body-md">No significant patterns detected yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Anomalies List -->
        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                <h3 class="font-headline-md text-headline-md font-bold text-on-surface">Detected Anomalies</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-secondary font-label-md uppercase tracking-wider border-b border-outline-variant">
                            <th class="p-md font-semibold">Entity</th>
                            <th class="p-md font-semibold">Anomaly Type</th>
                            <th class="p-md font-semibold">Description</th>
                            <th class="p-md font-semibold">Severity</th>
                            <th class="p-md font-semibold">Detected At</th>
                            <th class="p-md font-semibold">Status</th>
                            <th class="p-md font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant font-body-md">
                        @forelse($anomalies as $anomaly)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="p-md">
                                @if($anomaly->student)
                                    Student: {{ $anomaly->student->first_name }} {{ $anomaly->student->last_name }}
                                @elseif($anomaly->teacher)
                                    Teacher: {{ $anomaly->teacher->first_name }} {{ $anomaly->teacher->last_name }}
                                @else
                                    Unknown
                                @endif
                            </td>
                            <td class="p-md">
                                <span class="bg-surface-container px-2 py-1 rounded text-label-md">{{ str_replace('_', ' ', $anomaly->anomaly_type) }}</span>
                            </td>
                            <td class="p-md text-on-surface-variant max-w-xs truncate" title="{{ $anomaly->description }}">
                                {{ $anomaly->description }}
                            </td>
                            <td class="p-md">
                                @if($anomaly->severity === 'high')
                                    <span class="text-error font-semibold flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">warning</span> High</span>
                                @elseif($anomaly->severity === 'medium')
                                    <span class="text-[#b26b00] font-semibold flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">error</span> Medium</span>
                                @else
                                    <span class="text-primary font-semibold flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">info</span> Low</span>
                                @endif
                            </td>
                            <td class="p-md text-on-surface-variant">
                                {{ $anomaly->detected_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="p-md">
                                @if($anomaly->resolved)
                                    <span class="text-[#006e1c] font-semibold flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">check_circle</span> Resolved</span>
                                @else
                                    <span class="text-secondary font-semibold flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">pending</span> Pending</span>
                                @endif
                            </td>
                            <td class="p-md text-right">
                                @if(!$anomaly->resolved)
                                <form action="{{ route('admin.ai.attendance.resolve', $anomaly->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-primary hover:text-primary-container font-label-md flex items-center gap-1 ml-auto">
                                        <span class="material-symbols-outlined text-[18px]">done_all</span> Mark Resolved
                                    </button>
                                </form>
                                @else
                                    <span class="text-outline text-label-md">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-xl text-center text-secondary">
                                No attendance anomalies detected.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($anomalies->hasPages())
            <div class="p-md border-t border-outline-variant bg-surface-container-lowest">
                {{ $anomalies->links('pagination::tailwind') }}
            </div>
            @endif
        </div>
        
    </div>
</main>
@endsection
