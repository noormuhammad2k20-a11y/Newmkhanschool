@extends('layouts.app')

@section('title', 'Advanced Analytics')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Advanced Analytics</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Deep insights into school performance, attendance, and financials</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Filters
                </button>
                <button class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Export PDF
                </button>
            </div>
        </div>

        <!-- Filters Section (Hidden by default, shown as an example) -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Academic Session</label>
                    <select class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option>2023-2024</option>
                        <option>2022-2023</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Class</label>
                    <select class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option>All Classes</option>
                        <option>Grade 10</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Date Range</label>
                    <select class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option>Last 30 Days</option>
                        <option>This Semester</option>
                    </select>
                </div>
                <div class="flex-none">
                    <button class="px-4 py-2 bg-surface-container-high text-on-surface rounded-lg text-label-md font-label-md hover:bg-surface-variant transition-colors">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Grid (6 cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-md">
            <!-- Stat Card 1 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Students</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">group</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-total-students" class="text-headline-lg font-headline-lg text-on-surface">...</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Teachers</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[18px]">school</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-total-teachers" class="text-headline-lg font-headline-lg text-on-surface">...</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Attendance Rate</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">fact_check</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-attendance-rate" class="text-headline-lg font-headline-lg text-on-surface">...</span>
                </div>
                <div class="mt-2 text-xs font-medium text-emerald-700 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> +1.2%
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Fee Collection</h3>
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-fee-rate" class="text-headline-lg font-headline-lg text-on-surface">...</span>
                </div>
                 <div class="mt-2 text-xs font-medium text-emerald-700 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> +5.4%
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 5 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Avg Performance</h3>
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700">
                        <span class="material-symbols-outlined text-[18px]">analytics</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-exam-perf" class="text-headline-lg font-headline-lg text-on-surface">...</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-purple-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 6 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Active Classes</h3>
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700">
                        <span class="material-symbols-outlined text-[18px]">meeting_room</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-active-classes" class="text-headline-lg font-headline-lg text-on-surface">...</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-orange-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Charts Section Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-md">
            <!-- Fee Trend Chart -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col">
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-outline-variant">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Fee Collection Trends (6 Months)</h3>
                    <button class="text-secondary hover:text-primary"><span class="material-symbols-outlined">more_horiz</span></button>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="feeTrendChart"></canvas>
                </div>
            </div>

            <!-- Attendance Heatmap/Bar -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col">
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-outline-variant">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Weekly Attendance Trends</h3>
                    <button class="text-secondary hover:text-primary"><span class="material-symbols-outlined">more_horiz</span></button>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts Section Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Fee Status Pie -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col lg:col-span-1">
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-outline-variant">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Current Fee Status</h3>
                </div>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="feeStatusChart"></canvas>
                </div>
            </div>

            <!-- Class Performance -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col lg:col-span-2">
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-outline-variant">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Class-wise Academic Performance</h3>
                    <button class="text-secondary hover:text-primary"><span class="material-symbols-outlined">more_horiz</span></button>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch("{{ route('admin.analytics.data') }}")
        .then(res => res.json())
        .then(data => {
            // Colors from design system
            const primaryColor = '#000666';
            const primaryFixedColor = '#e0e0ff';
            const secondaryColor = '#526069';
            const outlineVariantColor = '#c6c5d4';
            const successColor = '#146c2e';
            const errorColor = '#ba1a1a';
            const warningColor = '#b58500';

            // Populate Stats
            document.getElementById('stat-total-students').innerText = data.student_stats.total;
            document.getElementById('stat-total-teachers').innerText = "42"; // Mocked for UI, as backend doesn't send it yet
            
            // Calculate mock averages from data
            let presentAvg = 0;
            if(data.attendance_weekly.length > 0) {
                let totalPresent = data.attendance_weekly.reduce((acc, curr) => acc + curr.present, 0);
                let totalAll = data.attendance_weekly.reduce((acc, curr) => acc + curr.total, 0);
                presentAvg = totalAll > 0 ? Math.round((totalPresent/totalAll)*100) : 0;
            }
            document.getElementById('stat-attendance-rate').innerText = presentAvg + "%";

            let feeTotal = data.fee_status_pie.reduce((acc, curr) => acc + curr.count, 0);
            let feePaid = data.fee_status_pie.find(d => d.label === 'Paid')?.count || 0;
            let feeRate = feeTotal > 0 ? Math.round((feePaid/feeTotal)*100) : 0;
            document.getElementById('stat-fee-rate').innerText = feeRate + "%";
            
            let classAvg = 0;
            if(data.class_performance.length > 0) {
                let totalPerf = data.class_performance.reduce((acc, curr) => acc + curr.percentage, 0);
                classAvg = Math.round(totalPerf / data.class_performance.length);
            }
            document.getElementById('stat-exam-perf').innerText = classAvg + "%";
            document.getElementById('stat-active-classes').innerText = data.class_performance.length;

            // 1. Fee Trend Chart
            new Chart(document.getElementById('feeTrendChart'), {
                type: 'line',
                data: {
                    labels: data.fee_collection.map(d => d.label),
                    datasets: [{
                        label: 'Collected Amount (PKR)',
                        data: data.fee_collection.map(d => d.amount),
                        borderColor: primaryColor,
                        backgroundColor: primaryFixedColor,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: primaryColor,
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: outlineVariantColor, borderDash: [5, 5] }, ticks: { font: { family: 'Inter', size: 12 }, color: secondaryColor } },
                        x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 12 }, color: secondaryColor } }
                    }
                }
            });

            // 2. Attendance Chart
            new Chart(document.getElementById('attendanceChart'), {
                type: 'bar',
                data: {
                    labels: data.attendance_weekly.map(d => d.label),
                    datasets: [
                        {
                            label: 'Present',
                            data: data.attendance_weekly.map(d => d.present),
                            backgroundColor: successColor,
                            borderRadius: 4
                        },
                        {
                            label: 'Absent',
                            data: data.attendance_weekly.map(d => d.absent),
                            backgroundColor: errorColor,
                            borderRadius: 4
                        }
                    ]
                },
                options: { 
                    responsive: true, maintainAspectRatio: false,
                    scales: { 
                        x: { stacked: true, grid: { display: false }, ticks: { color: secondaryColor } }, 
                        y: { stacked: true, grid: { color: outlineVariantColor, borderDash: [5,5] }, ticks: { color: secondaryColor } } 
                    } 
                }
            });

            // 3. Fee Status Pie
            new Chart(document.getElementById('feeStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: data.fee_status_pie.map(d => d.label),
                    datasets: [{
                        data: data.fee_status_pie.map(d => d.count),
                        backgroundColor: [successColor, secondaryColor, errorColor],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { color: secondaryColor, font: { family: 'Inter' } } } },
                    cutout: '70%'
                }
            });

            // 4. Class Performance
            new Chart(document.getElementById('performanceChart'), {
                type: 'bar',
                data: {
                    labels: data.class_performance.map(d => d.class),
                    datasets: [{
                        label: 'Average Score %',
                        data: data.class_performance.map(d => d.percentage),
                        backgroundColor: primaryColor,
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        x: { grid: { display: false }, ticks: { color: secondaryColor } }, 
                        y: { beginAtZero: true, max: 100, grid: { color: outlineVariantColor, borderDash: [5,5] }, ticks: { color: secondaryColor, callback: function(val) { return val + '%'; } } } 
                    } 
                }
            });
        });
});
</script>
@endpush
