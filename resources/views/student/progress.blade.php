@extends('layouts.app')

@section('title', 'My Progress Timeline')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('student.dashboard') }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">My Progress Timeline</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Track your marks and performance across exams.</p>
            </div>
        </div>

        <!-- Chart Card -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]">show_chart</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">Performance Chart</h3>
            </div>
            <div class="p-md">
                @if(count($chartData) > 0)
                    <canvas id="progressChart" height="100"></canvas>
                @else
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-[48px] text-secondary opacity-50">insert_chart_outlined</span>
                        <p class="text-body-lg font-body-lg text-secondary mt-3">No marks data available yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Marks Detail Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined text-[22px]">table_chart</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">Marks Detail</h3>
            </div>
            <div class="overflow-x-auto">
                @if($marks->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Subject</th>
                            <th class="py-3 px-4 font-semibold">Exam Type</th>
                            <th class="py-3 px-4 font-semibold">Date</th>
                            <th class="py-3 px-4 font-semibold">Marks</th>
                            <th class="py-3 px-4 font-semibold">Percentage</th>
                            <th class="py-3 px-4 font-semibold">Grade</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @foreach($marks as $m)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-medium text-on-surface">{{ $m->subject->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $m->examSchedule->exam_type ?? 'General' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ optional($m->examSchedule)->exam_date ?? $m->created_at->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-on-surface">{{ $m->marks_obtained }}/{{ $m->total_marks }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-surface-variant rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $m->percentage >= 50 ? 'bg-emerald-500' : 'bg-error' }}" style="width: {{ min($m->percentage, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold {{ $m->percentage >= 50 ? 'text-emerald-700' : 'text-error' }}">{{ $m->percentage }}%</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $m->grade == 'A+' || $m->grade == 'A' ? 'bg-emerald-100 text-emerald-700' :
                                       ($m->grade == 'B' ? 'bg-blue-100 text-blue-700' :
                                       ($m->grade == 'C' ? 'bg-amber-100 text-amber-700' :
                                       ($m->grade == 'D' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700'))) }}">
                                    {{ $m->grade }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-12">
                    <span class="material-symbols-outlined text-[48px] text-secondary opacity-50">school</span>
                    <p class="text-body-lg font-body-lg text-secondary mt-3">No marks recorded yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>

@if(count($chartData) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const datasets = @json($chartData);
const colors = ['#000666','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#858796','#5a5c69','#6610f2'];
new Chart(document.getElementById('progressChart'), {
    type: 'line',
    data: {
        datasets: datasets.map((d, i) => ({
            label: d.label,
            data: d.data,
            borderColor: colors[i % colors.length],
            backgroundColor: 'transparent',
            tension: 0.3,
            pointRadius: 5,
            pointHoverRadius: 7,
            borderWidth: 2,
        }))
    },
    options: {
        responsive: true,
        parsing: false,
        scales: {
            x: { type: 'category', title: { display: true, text: 'Exam Date' }, grid: { display: false } },
            y: { min: 0, max: 100, title: { display: true, text: 'Percentage (%)' } }
        },
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y}%`
                }
            }
        }
    }
});
</script>
@endif
@endsection
