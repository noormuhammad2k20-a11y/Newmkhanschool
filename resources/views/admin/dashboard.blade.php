@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
        <!-- Main Canvas -->
        <main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
            <div class="max-w-[1440px] mx-auto space-y-xl">
                <!-- Page Header -->
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Overview</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">State Education Department Activity Summary
                    </p>
                </div>
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                    <!-- Stat Card 1 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Students</h3>
                            <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[18px]">group</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="stat-total-students" class="text-headline-xl font-headline-xl text-on-surface">...</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-emerald-700">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span>
                            <span>+2.4% from last month</span>
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
                            <span id="stat-total-teachers" class="text-headline-xl font-headline-xl text-on-surface">...</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-emerald-700">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span>
                            <span>+4 new hires</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                    <!-- Stat Card 3 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Classes</h3>
                            <div class="w-8 h-8 rounded-lg bg-surface-variant flex items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">meeting_room</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="stat-total-classes" class="text-headline-xl font-headline-xl text-on-surface">...</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                            <span>Across all grade levels</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-surface-variant rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                    <!-- Stat Card 4 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Today's Attendance</h3>
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                                <span class="material-symbols-outlined text-[18px]">rule</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="stat-attendance-percent" class="text-headline-xl font-headline-xl text-on-surface">...</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-medium">
                            <span id="stat-attendance-present" class="text-emerald-700">... P</span>
                            <span class="text-outline">|</span>
                            <span id="stat-attendance-absent" class="text-error">... A</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                </div>
                <!-- Extra Stats Grid for Modules -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                    <!-- Stat Card 5 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Docs Generated</h3>
                            <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[18px]">description</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="stat-documents-generated" class="text-headline-xl font-headline-xl text-on-surface">...</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                            <span>Total Issued</span>
                        </div>
                    </div>
                    <!-- Stat Card 6 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Inventory Items</h3>
                            <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                                <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="stat-inventory-items" class="text-headline-xl font-headline-xl text-on-surface">...</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                            <span>Tracked Assets</span>
                        </div>
                    </div>
                    <!-- Stat Card 7 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Low Stock Alerts</h3>
                            <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                                <span class="material-symbols-outlined text-[18px]">warning</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="stat-low-stock" class="text-headline-xl font-headline-xl text-error">...</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-error">
                            <span>Needs Attention</span>
                        </div>
                    </div>
                    <!-- Stat Card 8 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Branches</h3>
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                                <span class="material-symbols-outlined text-[18px]">account_tree</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="stat-total-branches" class="text-headline-xl font-headline-xl text-on-surface">...</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-medium text-secondary">
                            <span>Active Campuses</span>
                        </div>
                    </div>
                </div>
                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-md">
                    <!-- Chart 1 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col">
                        <div class="flex justify-between items-center mb-6 pb-2 border-b border-outline-variant">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Enrollment by Class</h3>
                            <button class="text-secondary hover:text-primary"><span class="material-symbols-outlined">more_horiz</span></button>
                        </div>
                        <div class="relative h-64 w-full">
                            <canvas id="enrollmentChart"></canvas>
                        </div>
                    </div>
                    <!-- Chart 2 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col">
                        <div class="flex justify-between items-center mb-6 pb-2 border-b border-outline-variant">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Attendance Trends (30 Days)</h3>
                            <button class="text-secondary hover:text-primary"><span class="material-symbols-outlined">more_horiz</span></button>
                        </div>
                        <div class="relative h-64 w-full">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Tables Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
                    <!-- Table 1: Recent Admissions -->
                    <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                        <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Recent Admissions</h3>
                            <button class="text-primary text-label-md font-label-md hover:underline">View All</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                        <th class="py-3 px-4 font-semibold">Student Name</th>
                                        <th class="py-3 px-4 font-semibold">Registration ID</th>
                                        <th class="py-3 px-4 font-semibold">Date</th>
                                        <th class="py-3 px-4 font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-admissions-tbody" class="text-body-md font-body-md">
                                    <tr><td colspan="4" class="py-4 text-center">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table 2: Attendance Snapshot -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                        <div class="p-md border-b border-outline-variant bg-surface-bright">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Today's Snapshot</h3>
                            <p class="text-xs text-secondary mt-1">Section-wise lowest attendance</p>
                        </div>
                        <div class="p-md flex-1">
                            <ul class="space-y-4">
                                <li class="flex justify-between items-center pb-3 border-b border-outline-variant border-opacity-50">
                                    <div>
                                        <p class="font-medium text-body-md text-on-surface">Grade 10 - Sec B</p>
                                        <p class="text-xs text-error mt-0.5">Alert: Below 85%</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-body-lg text-on-surface">82%</p>
                                        <p class="text-xs text-secondary">32/39 Present</p>
                                    </div>
                                </li>
                                <!-- Static placeholder matching the original -->
                            </ul>
                            <button class="w-full mt-4 py-2 border border-outline-variant rounded-lg text-label-md font-label-md text-secondary hover:bg-surface-container-low transition-colors">Generate Alert Report</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch dashboard data
        fetch('/api/dashboard')
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    const data = response.data;
                    document.getElementById('stat-total-students').textContent = data.totalStudents.toLocaleString();
                    document.getElementById('stat-total-teachers').textContent = data.totalTeachers.toLocaleString();
                    document.getElementById('stat-total-classes').textContent = data.totalClasses.toLocaleString();
                    document.getElementById('stat-attendance-percent').textContent = data.attendancePercent + '%';
                    document.getElementById('stat-attendance-present').textContent = data.presentCount.toLocaleString() + ' P';
                    document.getElementById('stat-attendance-absent').textContent = data.absentCount.toLocaleString() + ' A';
                    
                    document.getElementById('stat-documents-generated').textContent = data.documentsGenerated.toLocaleString();
                    document.getElementById('stat-inventory-items').textContent = data.inventoryItems.toLocaleString();
                    document.getElementById('stat-low-stock').textContent = data.lowStockAlerts.toLocaleString();
                    document.getElementById('stat-total-branches').textContent = data.totalBranches.toLocaleString();

                    // Populate recent admissions
                    const tbody = document.getElementById('recent-admissions-tbody');
                    if (data.recentAdmissions.length === 0) {
                        tbody.innerHTML = `
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-xs">AK</div>
                                <span class="font-medium text-on-surface">Aarav Kumar</span>
                            </td>
                            <td class="py-3 px-4 text-secondary">REG-2023-0891</td>
                            <td class="py-3 px-4 text-secondary">Oct 24, 2023</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Approved</span>
                            </td>
                        </tr>`;
                    } else {
                        tbody.innerHTML = '';
                        data.recentAdmissions.forEach(student => {
                            const initials = student.first_name[0] + (student.last_name[0] || '');
                            tbody.innerHTML += `
                            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-xs uppercase">${initials}</div>
                                    <span class="font-medium text-on-surface">${student.first_name} ${student.last_name}</span>
                                </td>
                                <td class="py-3 px-4 text-secondary">${student.admission_no}</td>
                                <td class="py-3 px-4 text-secondary">${new Date(student.created_at).toLocaleDateString()}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Approved</span>
                                </td>
                            </tr>`;
                        });
                    }

                    // Render Charts
                    renderCharts(data.enrollmentChart);
                }
            });

        function renderCharts(enrollmentData) {
            const primaryColor = '#000666';
            const primaryFixedColor = '#e0e0ff';
            const secondaryColor = '#526069';
            const outlineVariantColor = '#c6c5d4';

            // Enrollment Bar Chart
            const ctxEnrollment = document.getElementById('enrollmentChart').getContext('2d');
            new Chart(ctxEnrollment, {
                type: 'bar',
                data: {
                    labels: enrollmentData.labels,
                    datasets: [{
                        label: 'Students Enrolled',
                        data: enrollmentData.data,
                        backgroundColor: primaryColor,
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: outlineVariantColor, borderDash: [5, 5] }, ticks: { font: { family: 'Inter', size: 12 }, color: secondaryColor } },
                        x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 12 }, color: secondaryColor } }
                    }
                }
            });

            // Attendance Line Chart (Dummy data for now)
            const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
            new Chart(ctxAttendance, {
                type: 'line',
                data: {
                    labels: ['1', '5', '10', '15', '20', '25', '30'],
                    datasets: [{
                        label: 'Attendance %',
                        data: [95, 94.5, 93, 96, 92, 94, 94.2],
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
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { min: 80, max: 100, grid: { color: outlineVariantColor, borderDash: [5, 5] }, ticks: { font: { family: 'Inter', size: 12 }, color: secondaryColor, callback: function (value) { return value + '%' } } },
                        x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 12 }, color: secondaryColor } }
                    }
                }
            });
        }
    });
</script>
@endsection
