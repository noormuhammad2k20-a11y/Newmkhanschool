<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Canvas -->
        <main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-surface-dim w-full">
            <div class="max-w-max-width mx-auto">
                <div class="mb-lg">
                    <h2 class="text-headline-xl font-headline-xl text-primary mb-xs">Analytics Report</h2>
                    <p class="text-body-lg font-body-lg text-secondary">High-level institutional overview for the Principal's Office.</p>
                </div>
                <!-- Bento Grid Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-md">
                    <!-- KPI Cards (Row 1) -->
                    <div class="lg:col-span-4 bg-surface-container-lowest rounded-lg border border-outline-variant p-md flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-md">
                            <div>
                                <p class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Enrollment</p>
                                <h3 class="text-headline-xl font-headline-xl text-on-surface mt-xs">1,248</h3>
                            </div>
                            <div class="p-sm bg-primary-fixed text-primary rounded-lg">
                                <span class="material-symbols-outlined">groups</span>
                            </div>
                        </div>
                        <div class="flex items-center text-label-md font-label-md text-[#2e7d32]">
                            <span class="material-symbols-outlined text-[16px] mr-xs">trending_up</span>
                            <span>+4.2% from last year</span>
                        </div>
                    </div>
                    <div class="lg:col-span-4 bg-surface-container-lowest rounded-lg border border-outline-variant p-md flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-md">
                            <div>
                                <p class="text-label-md font-label-md text-secondary uppercase tracking-wider">Average Attendance</p>
                                <h3 class="text-headline-xl font-headline-xl text-on-surface mt-xs">92.4%</h3>
                            </div>
                            <div class="p-sm bg-secondary-fixed text-secondary rounded-lg">
                                <span class="material-symbols-outlined">fact_check</span>
                            </div>
                        </div>
                        <div class="flex items-center text-label-md font-label-md text-secondary">
                            <span class="material-symbols-outlined text-[16px] mr-xs">horizontal_rule</span>
                            <span>Stable this month</span>
                        </div>
                    </div>
                    <div class="lg:col-span-4 bg-surface-container-lowest rounded-lg border border-outline-variant p-md flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-md">
                            <div>
                                <p class="text-label-md font-label-md text-secondary uppercase tracking-wider">Overall Pass Rate</p>
                                <h3 class="text-headline-xl font-headline-xl text-on-surface mt-xs">88.1%</h3>
                            </div>
                            <div class="p-sm bg-surface-tint text-surface-container-lowest rounded-lg">
                                <span class="material-symbols-outlined">school</span>
                            </div>
                        </div>
                        <div class="flex items-center text-label-md font-label-md text-[#c62828]">
                            <span class="material-symbols-outlined text-[16px] mr-xs">trending_down</span>
                            <span>-1.5% from last semester</span>
                        </div>
                    </div>
                    <!-- Enrollment Trends (Large Chart) -->
                    <div class="lg:col-span-8 bg-surface-container-lowest rounded-lg border border-outline-variant flex flex-col h-[400px]">
                        <div class="p-md border-b border-outline-variant flex justify-between items-center">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Enrollment Trends (Last 5 Years)</h3>
                            <button class="text-secondary hover:text-primary transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                        </div>
                        <div class="p-md flex-1 relative w-full h-full">
                            <canvas id="enrollmentChart"></canvas>
                        </div>
                    </div>
                    <!-- Gender Distribution (Donut Chart) -->
                    <div class="lg:col-span-4 bg-surface-container-lowest rounded-lg border border-outline-variant flex flex-col h-[400px]">
                        <div class="p-md border-b border-outline-variant flex justify-between items-center">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Demographics</h3>
                            <button class="text-secondary hover:text-primary transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                        </div>
                        <div class="p-md flex-1 relative w-full h-full flex flex-col justify-center items-center">
                            <div class="h-48 w-48 relative">
                                <canvas id="genderChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center flex-col">
                                    <span class="text-headline-md font-headline-md text-on-surface">Ratio</span>
                                    <span class="text-label-md font-label-md text-secondary">Boy/Girl</span>
                                </div>
                            </div>
                            <div class="mt-lg w-full flex justify-around">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-primary mr-sm"></div>
                                    <span class="text-body-md font-body-md text-on-surface">Boys (52%)</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-surface-tint mr-sm"></div>
                                    <span class="text-body-md font-body-md text-on-surface">Girls (48%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Academic Performance (Bar Chart) -->
                    <div class="lg:col-span-12 bg-surface-container-lowest rounded-lg border border-outline-variant flex flex-col h-[400px]">
                        <div class="p-md border-b border-outline-variant flex justify-between items-center">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Academic Performance (Pass % per Class)</h3>
                            <div class="flex items-center space-x-sm">
                                <select class="text-body-md font-body-md border-outline-variant rounded-lg py-1 px-2 text-secondary bg-surface">
                                    <option>2023-2024</option>
                                    <option>2022-2023</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-md flex-1 relative w-full h-full">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <script>
        // Chart Configuration (using Chart.js)
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#526069'; // text-secondary

        // Colors from Tailwind config
        const colorPrimary = '#000666';
        const colorSurfaceTint = '#4c56af';
        const colorSecondaryContainer = '#d3e2ed';
        const colorOutlineVariant = '#c6c5d4';

        // 1. Enrollment Trends (Line Chart)
        const ctxEnrollment = document.getElementById('enrollmentChart').getContext('2d');
        new Chart(ctxEnrollment, {
            type: 'line',
            data: {
                labels: ['2019', '2020', '2021', '2022', '2023'],
                datasets: [{
                    label: 'Total Students',
                    data: [1050, 1120, 1080, 1198, 1248],
                    borderColor: colorPrimary,
                    backgroundColor: 'rgba(0, 6, 102, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: colorPrimary,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#191c1d',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 14 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: '#f0f1f2', drawBorder: false },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });

        // 2. Gender Distribution (Doughnut Chart)
        const ctxGender = document.getElementById('genderChart').getContext('2d');
        new Chart(ctxGender, {
            type: 'doughnut',
            data: {
                labels: ['Boys', 'Girls'],
                datasets: [{
                    data: [648, 600],
                    backgroundColor: [colorPrimary, colorSurfaceTint],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#191c1d',
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed !== null) {
                                    label += context.parsed + ' students';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // 3. Academic Performance (Bar Chart)
        const ctxPerformance = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctxPerformance, {
            type: 'bar',
            data: {
                labels: ['Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10', 'Class 11', 'Class 12'],
                datasets: [{
                    label: 'Pass Percentage (%)',
                    data: [94, 91, 88, 82, 95, 78, 89],
                    backgroundColor: colorSurfaceTint,
                    borderRadius: 4,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#191c1d',
                        padding: 12
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: '#f0f1f2', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            callback: function (value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });
    </script>

<?php include 'includes/footer.php'; ?>
