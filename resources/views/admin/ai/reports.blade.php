@extends('layouts.app')

@section('content')
<main class="flex-1 p-lg overflow-y-auto w-full min-w-0">
    <div class="max-w-[1440px] mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
            <div>
                <h2 class="font-headline-lg text-headline-lg font-bold text-primary mb-xs">AI Report Generator</h2>
                <p class="font-body-md text-body-md text-secondary">Generate intelligent, summarized reports with actionable insights.</p>
            </div>
        </div>

        <!-- Initial State (Before Generation) -->
        <div id="initialState" class="mb-xl space-y-lg">
            <!-- Form Configuration -->
            <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm">
                <form id="reportForm" class="flex flex-col lg:flex-row gap-lg items-end">
                    <div class="flex-1 w-full">
                        <label class="block font-label-md text-on-surface-variant mb-xs">Select Report Type</label>
                        <select name="report_type" id="report_type" class="w-full px-md py-sm rounded-lg border border-outline-variant bg-surface text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="student_performance">Student Performance Analysis</option>
                            <option value="attendance_trends">Attendance Trends & Forecast</option>
                            <option value="fee_collection">Fee Collection Prediction</option>
                        </select>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-md w-full lg:w-auto">
                        <button type="submit" class="flex-1 sm:flex-none py-sm px-xl rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center justify-center gap-xs">
                            <span class="material-symbols-outlined text-[18px]">magic_button</span>
                            Generate AI Report
                        </button>
                        <button type="button" id="historyBtn" class="flex-1 sm:flex-none py-sm px-xl rounded-lg border border-outline-variant bg-surface-container-lowest text-secondary font-label-md hover:bg-surface-container transition-colors shadow-sm flex items-center justify-center gap-xs">
                            <span class="material-symbols-outlined text-[18px]">history</span>
                            View History
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Empty State -->
            <div class="text-center py-20 border border-dashed border-outline-variant rounded-xl bg-surface-container-lowest/50">
                <div class="w-16 h-16 bg-surface-container text-secondary rounded-full flex items-center justify-center mx-auto mb-md">
                    <span class="material-symbols-outlined text-[32px]">analytics</span>
                </div>
                <h3 class="font-headline-md font-medium text-on-surface mb-xs">Ready to Generate</h3>
                <p class="text-body-md text-secondary max-w-md mx-auto">
                    Select a report type from the configuration panel above and click generate to receive an AI-powered executive summary.
                </p>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden flex flex-col items-center justify-center py-20 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm">
            <div class="w-16 h-16 border-4 border-primary/30 border-t-primary rounded-full animate-spin mb-md"></div>
            <h3 class="font-headline-md text-primary animate-pulse" id="loadingText">Analyzing data and generating insights...</h3>
            <p class="text-body-md text-secondary mt-2">Aggregating records and running ML models.</p>
        </div>

        <!-- Result State -->
        <div id="resultState" class="hidden">
            <div class="p-sm bg-green-100 text-green-800 rounded-lg border border-green-200 flex items-center gap-sm mb-lg shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                <span id="resultMessage" class="font-medium text-sm">Report generated successfully.</span>
            </div>

            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden mb-lg">
                <div class="p-lg border-b border-outline-variant bg-surface-container-low flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 id="reportTitle" class="font-headline-md text-headline-md font-semibold text-primary">Report Title</h3>
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
                
                <div class="p-lg">
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
                    
                    <p class="text-xs text-secondary text-center mt-xl pt-md border-t border-outline-variant">
                        Report Generated on: <span id="generationTime"></span>
                    </p>
                </div>
            </div>
            
            <div class="flex justify-center mt-lg">
                <button onclick="resetReport()" class="px-lg py-sm rounded-lg border border-outline text-secondary font-label-md hover:bg-surface-container transition-colors shadow-sm flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[18px]">refresh</span>
                    Generate Another Report
                </button>
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
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof UI !== 'undefined' && UI.showToast) {
                UI.showToast('Error generating report', 'error');
            } else {
                UI.showToast('Error generating report', 'error');
            }
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

        if (chartData && chartData.datasets) {
            chartData.datasets.forEach((dataset, index) => {
                const colorObj = colors[index % colors.length];
                dataset.backgroundColor = colorObj.bg;
                dataset.borderColor = colorObj.border;
                dataset.borderWidth = 1;
            });
        }

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

    function resetReport() {
        document.getElementById('resultState').classList.add('hidden');
        document.getElementById('initialState').classList.remove('hidden');
    }
</script>
@endsection
