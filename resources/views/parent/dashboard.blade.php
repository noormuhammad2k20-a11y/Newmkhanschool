@extends('layouts.app')

@section('title', 'Parent Dashboard')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Dashboard</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Welcome back, {{ auth()->user()->name }}! Here is an overview of your children's academics.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('parent.messages') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    Contact School
                </a>
            </div>
        </div>

        @if(isset($children) && count($children) > 0)
            <!-- Stats Overview (Aggregated for all children) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                <!-- Total Children -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Children</h3>
                        <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[18px]">family_restroom</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-headline-xl font-headline-xl text-on-surface">{{ count($children) }}</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                        <span>Enrolled in school</span>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                </div>

                <!-- Total Pending Fees -->
                @php
                    $totalPendingFees = 0;
                    foreach($childSummaries as $summary) {
                        $totalPendingFees += $summary['pending_fees'];
                    }
                @endphp
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending Fees</h3>
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                            <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-headline-xl font-headline-xl text-on-surface">Rs {{ number_format($totalPendingFees, 2) }}</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-xs font-medium {{ $totalPendingFees > 0 ? 'text-error' : 'text-emerald-700' }}">
                        <span>{{ $totalPendingFees > 0 ? 'Action Required' : 'All clear' }}</span>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-red-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                </div>

                <!-- Average Attendance -->
                @php
                    $avgAttendance = 0;
                    if(count($children) > 0) {
                        $sum = 0;
                        foreach($childSummaries as $summary) {
                            $sum += $summary['attendance_pct'];
                        }
                        $avgAttendance = round($sum / count($children), 1);
                    }
                @endphp
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Avg Attendance</h3>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                            <span class="material-symbols-outlined text-[18px]">rule</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-headline-xl font-headline-xl text-on-surface">{{ $avgAttendance }}%</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                        <span>Current Academic Year</span>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                </div>

                <!-- Recent Messages -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Messages</h3>
                        <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                            <span class="material-symbols-outlined text-[18px]">mail</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-headline-xl font-headline-xl text-on-surface">Inbox</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                        <span><a href="{{ route('parent.messages') }}" class="hover:underline">View messages</a></span>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                </div>
            </div>

            <!-- Children List Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Children Overview</h3>
                    <a href="{{ route('parent.children') }}" class="text-primary text-label-md font-label-md hover:underline">View All Details</a>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-md">
                    @foreach($children as $student)
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col group hover:border-primary transition-colors cursor-default">
                            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-lg border-2 border-surface-container-lowest shadow-sm">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/'.$student->photo) }}" alt="{{ $student->first_name }}" class="w-full h-full rounded-full object-cover">
                                    @else
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name ?? '', 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-title-lg font-title-lg text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</h3>
                                    <p class="text-body-md font-body-md text-secondary">Class: {{ $student->currentClass->name ?? 'N/A' }} {{ $student->currentSection->name ?? '' }}</p>
                                </div>
                            </div>
                            
                            <!-- Quick Stats -->
                            <div class="grid grid-cols-2 divide-x divide-outline-variant border-b border-outline-variant bg-surface-container-low">
                                <div class="p-3 text-center">
                                    <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wide">Attendance</p>
                                    <p class="text-title-md font-title-md text-on-surface mt-1">{{ $childSummaries[$student->id]['attendance_pct'] }}%</p>
                                </div>
                                <div class="p-3 text-center">
                                    <p class="text-label-sm font-label-sm text-secondary uppercase tracking-wide">Pending Fee</p>
                                    <p class="text-title-md font-title-md text-on-surface mt-1 {{ $childSummaries[$student->id]['pending_fees'] > 0 ? 'text-error' : '' }}">
                                        Rs {{ number_format($childSummaries[$student->id]['pending_fees']) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Monthly Attendance Chart -->
                            <div class="p-3 border-b border-outline-variant bg-surface-container-lowest">
                                <canvas id="chart-{{ $student->id }}" height="80"></canvas>
                            </div>

                            <!-- Actions -->
                            <div class="p-md grid grid-cols-2 gap-3">
                                <a href="{{ route('parent.child.attendance', $student->id) }}" class="flex flex-col items-center justify-center gap-1 p-3 rounded-lg border border-outline-variant hover:border-primary hover:bg-surface-container-low transition-colors">
                                    <span class="material-symbols-outlined text-primary">co_present</span>
                                    <span class="text-label-sm font-label-sm text-on-surface">Attendance</span>
                                </a>
                                <a href="{{ route('parent.child.marks', $student->id) }}" class="flex flex-col items-center justify-center gap-1 p-3 rounded-lg border border-outline-variant hover:border-primary hover:bg-surface-container-low transition-colors">
                                    <span class="material-symbols-outlined text-emerald-600">grade</span>
                                    <span class="text-label-sm font-label-sm text-on-surface">Marks</span>
                                </a>
                                <a href="{{ route('parent.child.fees', $student->id) }}" class="flex flex-col items-center justify-center gap-1 p-3 rounded-lg border border-outline-variant hover:border-primary hover:bg-surface-container-low transition-colors">
                                    <span class="material-symbols-outlined text-red-600">account_balance_wallet</span>
                                    <span class="text-label-sm font-label-sm text-on-surface">Fees</span>
                                </a>
                                <a href="{{ route('parent.child.assignments', $student->id) }}" class="flex flex-col items-center justify-center gap-1 p-3 rounded-lg border border-outline-variant hover:border-primary hover:bg-surface-container-low transition-colors">
                                    <span class="material-symbols-outlined text-orange-600">assignment</span>
                                    <span class="text-label-sm font-label-sm text-on-surface">Assignments</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center text-center py-20">
                <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center text-secondary mb-4">
                    <span class="material-symbols-outlined text-[40px]">family_restroom</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface mb-2">No Children Linked</h3>
                <p class="text-body-lg font-body-lg text-secondary max-w-md">You don't have any students linked to your parent account at the moment.</p>
                <p class="text-body-md font-body-md text-secondary max-w-md mt-2">Please contact the school administration to link your children to your profile.</p>
                <a href="{{ route('parent.messages') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary rounded-full font-label-lg hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined">support_agent</span>
                    Contact Administration
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">

            
            {{-- Contact School --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden h-fit">
                <div class="p-md border-b border-outline-variant bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">contact_support</span>
                        School Contact
                    </h3>
                </div>
                <div class="p-md">
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">phone</span>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-secondary">Admin Office</p>
                                <p class="text-body-lg font-body-lg text-on-surface">+1 234 567 8900</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">email</span>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-secondary">Support Email</p>
                                <p class="text-body-lg font-body-lg text-on-surface">support@school.edu</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-secondary">Address</p>
                                <p class="text-body-lg font-body-lg text-on-surface">123 Education Street<br>City, State 12345</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const childSummaries = @json($childSummaries ?? []);
    for (const [studentId, data] of Object.entries(childSummaries)) {
        const ctx = document.getElementById('chart-' + studentId);
        if (ctx && data.monthly_chart && data.monthly_chart.length > 0) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.monthly_chart.map(c => c.label),
                    datasets: [
                        { label: 'Present', data: data.monthly_chart.map(c => c.present), backgroundColor: '#10b981' },
                        { label: 'Absent', data: data.monthly_chart.map(c => c.absent), backgroundColor: '#ef4444' }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    }
                }
            });
        }
    }
});
</script>
@endsection
