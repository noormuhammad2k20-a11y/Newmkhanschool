@extends('layouts.app')

@section('content')
<main class="flex-1 p-lg overflow-y-auto w-full">
    <div class="max-w-[1440px] mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
            <div>
                <h2 class="font-headline-lg text-headline-lg font-bold text-primary mb-xs">AI Report Generator</h2>
                <p class="font-body-md text-body-md text-secondary">Generate intelligent, summarized reports with actionable insights.</p>
            </div>
        </div>

        <div class="bento-grid">
            <!-- Report Config -->
            <div class="bento-item-side bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm self-start">
                <h3 class="font-headline-md text-headline-md font-semibold text-on-surface mb-md">Report Configuration</h3>
                <form id="reportForm" class="space-y-md">
                    <div>
                        <label class="block font-label-md text-on-surface-variant mb-xs">Select Report Type</label>
                        <select name="report_type" id="report_type" class="w-full px-md py-sm rounded-lg border border-outline-variant bg-surface text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="student_performance">Student Performance Analysis</option>
                            <option value="attendance_trends">Attendance Trends & Forecast</option>
                            <option value="fee_collection">Fee Collection Prediction</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full py-sm rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">magic_button</span>
                        Generate AI Report
                    </button>
                </form>
            </div>

            <!-- Report Output -->
            <div class="bento-item-main bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col min-h-[500px]">
                <!-- Initial State -->
                <div id="initialState" class="flex-1 flex flex-col items-center justify-center p-xl text-center">
                    <span class="material-symbols-outlined text-[64px] text-secondary mb-md opacity-50">description</span>
                    <h3 class="font-headline-md text-on-surface mb-xs">Select a report type to begin</h3>
                    <p class="text-body-md text-on-surface-variant">Our AI will aggregate the data and generate a comprehensive executive summary.</p>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="hidden flex-1 flex flex-col items-center justify-center p-xl">
                    <div class="w-12 h-12 border-4 border-primary/30 border-t-primary rounded-full animate-spin mb-md"></div>
                    <p class="font-label-md text-primary animate-pulse">Analyzing data and generating insights...</p>
                </div>

                <!-- Result State -->
                <div id="resultState" class="hidden flex-1 flex flex-col">
                    <div class="p-lg border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                        <h3 id="reportTitle" class="font-headline-md font-bold text-primary">Report Title</h3>
                        <div class="flex gap-sm">
                            <button class="text-secondary hover:text-primary transition-colors flex items-center gap-xs bg-surface-container-lowest px-sm py-xs rounded border border-outline-variant">
                                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> PDF
                            </button>
                            <button class="text-secondary hover:text-primary transition-colors flex items-center gap-xs bg-surface-container-lowest px-sm py-xs rounded border border-outline-variant">
                                <span class="material-symbols-outlined text-[18px]">grid_on</span> Excel
                            </button>
                            <button class="text-secondary hover:text-primary transition-colors flex items-center gap-xs bg-surface-container-lowest px-sm py-xs rounded border border-outline-variant">
                                <span class="material-symbols-outlined text-[18px]">print</span> Print
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-lg flex-1 overflow-y-auto">
                        <div class="mb-lg p-md bg-primary-fixed/20 rounded-lg border border-primary/10">
                            <h4 class="font-label-md text-primary font-bold uppercase tracking-wider mb-sm flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[16px]">summarize</span>
                                AI Executive Summary
                            </h4>
                            <p id="executiveSummary" class="text-body-md text-on-surface-variant leading-relaxed"></p>
                        </div>

                        <div class="h-64 relative w-full mb-lg">
                            <canvas id="reportChart"></canvas>
                        </div>
                        
                        <p class="text-xs text-secondary text-center mt-auto pt-md border-t border-outline-variant">
                            Report Generated on: <span id="generationTime"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let reportChartInstance = null;

    document.getElementById('reportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const reportType = document.getElementById('report_type').value;
        const typeLabels = {
            'student_performance': 'Student Performance Analysis',
            'attendance_trends': 'Attendance Trends & Forecast',
            'fee_collection': 'Fee Collection Prediction'
        };

        // UI States
        document.getElementById('initialState').classList.add('hidden');
        document.getElementById('resultState').classList.add('hidden');
        document.getElementById('loadingState').classList.remove('hidden');

        fetch("{{ route('admin.ai.reports.generate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ report_type: reportType })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('reportTitle').textContent = typeLabels[reportType];
                document.getElementById('executiveSummary').textContent = data.data.executive_summary;
                document.getElementById('generationTime').textContent = data.data.generated_at;

                renderChart(data.data.chart_data);

                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('resultState').classList.remove('hidden');
                document.getElementById('resultState').classList.add('flex');
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            UI.showToast('Error generating report', 'error');
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('initialState').classList.remove('hidden');
        });
    });

    function renderChart(chartData) {
        const ctx = document.getElementById('reportChart').getContext('2d');
        
        if (reportChartInstance) {
            reportChartInstance.destroy();
        }

        // Apply theme colors to datasets
        const colors = [
            { bg: 'rgba(76, 86, 175, 0.7)', border: '#4c56af' }, // Primary
            { bg: 'rgba(239, 68, 68, 0.7)', border: '#ef4444' },  // Red
            { bg: 'rgba(245, 158, 11, 0.7)', border: '#f59e0b' }  // Yellow
        ];

        chartData.datasets.forEach((dataset, index) => {
            const colorObj = colors[index % colors.length];
            dataset.backgroundColor = colorObj.bg;
            dataset.borderColor = colorObj.border;
            dataset.borderWidth = 1;
        });

        reportChartInstance = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endsection
