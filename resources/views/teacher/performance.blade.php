@extends('layouts.app')

@section('title', 'Student Performance')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Student Performance Insights</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Analyze performance trends across your assigned classes.</p>
        </div>

        <!-- Note: This is a static representation as requested. Real charts require Chart.js integration -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-md">
            <!-- Mock Chart 1 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
                <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Class Averages (Mid-Term)</h3>
                <div class="h-64 flex items-end justify-around border-b border-l border-outline-variant pb-2 pl-2">
                    <div class="w-16 bg-primary rounded-t" style="height: 75%;" title="Class X-A: 75%"></div>
                    <div class="w-16 bg-primary rounded-t" style="height: 82%;" title="Class IX-B: 82%"></div>
                    <div class="w-16 bg-primary rounded-t" style="height: 68%;" title="Class VIII-C: 68%"></div>
                    <div class="w-16 bg-primary rounded-t" style="height: 90%;" title="Class X-B: 90%"></div>
                </div>
                <div class="flex justify-around mt-2 text-label-sm text-secondary">
                    <span>X-A</span>
                    <span>IX-B</span>
                    <span>VIII-C</span>
                    <span>X-B</span>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
                <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Top Performers</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between items-center p-3 bg-surface-container-low rounded">
                        <div>
                            <p class="font-medium text-on-surface">Ali Raza</p>
                            <p class="text-xs text-secondary">Class X-A • Math</p>
                        </div>
                        <span class="text-emerald-600 font-bold">98%</span>
                    </li>
                    <li class="flex justify-between items-center p-3 bg-surface-container-low rounded">
                        <div>
                            <p class="font-medium text-on-surface">Sarah Khan</p>
                            <p class="text-xs text-secondary">Class IX-B • Physics</p>
                        </div>
                        <span class="text-emerald-600 font-bold">95%</span>
                    </li>
                    <li class="flex justify-between items-center p-3 bg-surface-container-low rounded">
                        <div>
                            <p class="font-medium text-on-surface">Ahmed Ali</p>
                            <p class="text-xs text-secondary">Class X-B • Math</p>
                        </div>
                        <span class="text-emerald-600 font-bold">94%</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>
@endsection
