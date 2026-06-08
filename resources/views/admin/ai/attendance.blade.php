@extends('layouts.app')

@section('content')
<main class="flex-1 p-lg overflow-y-auto w-full">
    <div class="max-w-[1440px] mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
            <div>
                <h2 class="font-headline-lg text-headline-lg font-bold text-primary mb-xs">AI Attendance Prediction</h2>
                <p class="font-body-md text-body-md text-secondary">Predictive analysis of student and teacher attendance using AI.</p>
            </div>
            <div class="flex gap-sm">
                <form action="{{ route('admin.ai.attendance') }}" method="GET" class="flex gap-sm">
                    <select name="class_id" class="px-md py-sm rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-lg py-sm rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                        Filter
                    </button>
                </form>
            </div>
        </div>

        <div class="bento-grid mb-xl">
            <!-- System-wide Trends Chart -->
            <div class="bento-item-main bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm">
                <div class="flex items-center justify-between mb-md">
                    <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">System-Wide Attendance Trends (Next 6 Months Forecast)</h3>
                </div>
                <div class="h-64 relative w-full">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>

            <!-- AI Summary Side Panel -->
            <div class="bento-item-side bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm flex flex-col">
                <div class="flex items-center gap-sm mb-md text-primary">
                    <span class="material-symbols-outlined">auto_awesome</span>
                    <h3 class="font-headline-md text-headline-md font-semibold">AI Insights</h3>
                </div>
                <p class="text-body-md text-on-surface-variant mb-md">
                    Based on historical data, the AI model predicts a <strong>stable</strong> attendance rate for the upcoming month. However, there are localized risks in certain classes.
                </p>
                <div class="mt-auto space-y-sm">
                    <div class="p-sm bg-error-container rounded-lg border border-error/20 flex items-start gap-sm">
                        <span class="material-symbols-outlined text-error">warning</span>
                        <div>
                            <h4 class="font-label-md text-error font-bold">High Risk Alert</h4>
                            <p class="text-body-md text-error/80 text-sm">Class 10-A shows a declining trend.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Predictions Table -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="p-lg border-b border-outline-variant flex items-center justify-between">
                <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Student Attendance Predictions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant">
                            <th class="p-md font-label-md text-label-md font-semibold">Student Name</th>
                            <th class="p-md font-label-md text-label-md font-semibold">Class/Section</th>
                            <th class="p-md font-label-md text-label-md font-semibold">Current %</th>
                            <th class="p-md font-label-md text-label-md font-semibold">Predicted % (Next Week)</th>
                            <th class="p-md font-label-md text-label-md font-semibold">AI Risk Level</th>
                            <th class="p-md font-label-md text-label-md font-semibold">Detected Pattern</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($predictions as $prediction)
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="p-md">
                                    <div class="font-body-md font-semibold text-on-surface">{{ $prediction['student_name'] }}</div>
                                    <div class="text-xs text-on-surface-variant">{{ $prediction['admission_no'] }}</div>
                                </td>
                                <td class="p-md text-body-md text-on-surface-variant">{{ $prediction['class_section'] }}</td>
                                <td class="p-md text-body-md font-medium text-on-surface">{{ $prediction['current_percentage'] }}%</td>
                                <td class="p-md text-body-md font-bold text-primary">{{ $prediction['predicted_percentage'] }}%</td>
                                <td class="p-md">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prediction['risk_color'] }}">
                                        {{ $prediction['risk_level'] }}
                                    </span>
                                </td>
                                <td class="p-md text-body-md text-on-surface-variant text-sm">
                                    {{ implode(', ', $prediction['patterns']) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-xl text-center text-on-surface-variant font-body-md">
                                    No predictions available for the selected criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('trendsChart').getContext('2d');
        const trendsData = @json($trends);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendsData.labels,
                datasets: [{
                    label: 'Attendance %',
                    data: trendsData.data,
                    borderColor: '#4c56af',
                    backgroundColor: 'rgba(76, 86, 175, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#000666',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 70,
                        max: 100
                    }
                }
            }
        });
    });
</script>
@endsection
