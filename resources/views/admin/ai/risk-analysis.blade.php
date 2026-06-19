@extends('layouts.app')

@section('title', 'AI Risk Analysis')

@section('content')
<main class="flex-grow p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
    <!-- Header -->
    <div class="mb-xl flex flex-col md:flex-row md:items-end justify-between gap-md">
        <div>
            <h1 class="text-headline-lg-mobile md:text-headline-xl font-headline-lg-mobile md:font-headline-xl text-on-surface flex items-center gap-2">
                <span class="material-symbols-rounded text-primary text-[32px] md:text-[40px]">psychology</span>
                AI Risk Analysis
            </h1>
            <p class="text-body-md font-body-md text-secondary mt-1">Predictive insights on student dropouts, academic struggles, and attendance risks.</p>
        </div>
        <div class="flex gap-sm">
            <button class="px-md py-sm border border-outline-variant rounded bg-surface-container-lowest text-on-surface text-label-md font-label-md hover:bg-surface-container-low transition-colors flex items-center gap-xs">
                <span class="material-symbols-rounded text-[18px]">download</span>
                Export Report
            </button>
            <button class="px-md py-sm bg-primary text-on-primary rounded font-label-md hover:bg-primary-dark transition-colors flex items-center gap-xs shadow-sm" onclick="runAnalysis()">
                <span class="material-symbols-rounded text-[18px]">refresh</span>
                Run Analysis Now
            </button>
        </div>
    </div>

    <!-- Summary Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-xl">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group">
            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-2">High Risk Students</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-headline-xl font-headline-xl text-error">42</span>
            </div>
            <p class="text-xs text-error mt-2">12% increase from last month</p>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group">
            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-2">Academic Decline Risk</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-headline-xl font-headline-xl text-[#f59e0b]">85</span>
            </div>
            <p class="text-xs text-[#92400e] mt-2">Students showing a negative grade trend</p>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group">
            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-2">System Accuracy</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-headline-xl font-headline-xl text-emerald-600">94.2%</span>
            </div>
            <p class="text-xs text-emerald-800 mt-2">Based on historical prediction validation</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        
        <!-- Detailed Table List -->
        <div class="lg:col-span-2 bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">At-Risk Student Profiles</h3>
                <div class="flex gap-2">
                    <select class="bg-surface-container border border-outline-variant text-on-surface text-sm rounded-lg focus:ring-primary focus:border-primary block p-2">
                        <option>All Risk Types</option>
                        <option>Dropout Risk</option>
                        <option>Academic Risk</option>
                        <option>Attendance Risk</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto flex-grow">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                        <tr>
                            <th class="py-3 px-4">Student</th>
                            <th class="py-3 px-4">Class</th>
                            <th class="py-3 px-4">Risk Factor</th>
                            <th class="py-3 px-4">AI Confidence</th>
                            <th class="py-3 px-4 text-right">Action Plan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant" id="risk-tbody">
                        <tr class="hover:bg-surface-container-lowest">
                            <td class="py-3 px-4 font-medium text-on-surface">Ali Khan</td>
                            <td class="py-3 px-4 text-secondary">10-A</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 text-error text-sm font-bold">
                                    <span class="material-symbols-rounded text-[16px]">trending_down</span>
                                    Dropout Risk
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-full bg-surface-container-high rounded-full h-2.5 max-w-[100px]">
                                  <div class="bg-error h-2.5 rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="text-xs text-secondary mt-1 block">85%</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button class="text-primary hover:bg-primary-fixed p-1 rounded transition-colors text-sm font-bold">Schedule Counseling</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-lowest">
                            <td class="py-3 px-4 font-medium text-on-surface">Sara Ahmed</td>
                            <td class="py-3 px-4 text-secondary">9-B</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 text-[#f59e0b] text-sm font-bold">
                                    <span class="material-symbols-rounded text-[16px]">warning</span>
                                    Academic Risk
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-full bg-surface-container-high rounded-full h-2.5 max-w-[100px]">
                                  <div class="bg-[#f59e0b] h-2.5 rounded-full" style="width: 72%"></div>
                                </div>
                                <span class="text-xs text-secondary mt-1 block">72%</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button class="text-primary hover:bg-primary-fixed p-1 rounded transition-colors text-sm font-bold">Assign Tutor</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-lowest">
                            <td class="py-3 px-4 font-medium text-on-surface">Usman Tariq</td>
                            <td class="py-3 px-4 text-secondary">8-C</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 text-[#f59e0b] text-sm font-bold">
                                    <span class="material-symbols-rounded text-[16px]">schedule</span>
                                    Attendance Risk
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-full bg-surface-container-high rounded-full h-2.5 max-w-[100px]">
                                  <div class="bg-[#f59e0b] h-2.5 rounded-full" style="width: 65%"></div>
                                </div>
                                <span class="text-xs text-secondary mt-1 block">65%</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button class="text-primary hover:bg-primary-fixed p-1 rounded transition-colors text-sm font-bold">Contact Parents</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ML Model Insights -->
        <div class="flex flex-col gap-lg">
            <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-md">
                <h3 class="text-title-lg font-title-lg text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-rounded text-primary">data_usage</span>
                    Risk Distribution
                </h3>
                <div class="h-48 relative flex items-center justify-center">
                    <div class="w-40 h-40 rounded-full border-[16px] border-surface-container-highest relative flex items-center justify-center">
                        <!-- Mock donut segments -->
                        <div class="absolute inset-0 border-[16px] border-[#f59e0b] rounded-full" style="clip-path: polygon(50% 50%, 100% 0, 100% 100%, 0 100%, 0 50%);"></div>
                        <div class="absolute inset-0 border-[16px] border-error rounded-full" style="clip-path: polygon(50% 50%, 0 50%, 0 0, 50% 0);"></div>
                        <div class="text-center">
                            <span class="text-headline-md font-bold text-on-surface">127</span>
                            <span class="text-xs text-secondary block">Total Alerts</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 mt-4 text-sm">
                    <div class="flex justify-between items-center"><span class="flex items-center gap-2"><div class="w-3 h-3 bg-error rounded-sm"></div> Dropout</span> <span>42</span></div>
                    <div class="flex justify-between items-center"><span class="flex items-center gap-2"><div class="w-3 h-3 bg-[#f59e0b] rounded-sm"></div> Academic</span> <span>85</span></div>
                </div>
            </div>

            <div class="bg-primary-container text-on-primary-container rounded-xl shadow-sm p-md">
                <h3 class="text-title-md font-title-md mb-2 flex items-center gap-2">
                    <span class="material-symbols-rounded">lightbulb</span>
                    AI Recommendation
                </h3>
                <p class="text-body-md font-body-md">
                    Historically, students flagged with <strong>Attendance Risk</strong> are 60% more likely to recover if parental contact is established within 48 hours of the alert. Consider automating SMS alerts for early-stage attendance risks.
                </p>
                <button class="mt-4 px-4 py-2 bg-primary text-on-primary rounded text-sm font-bold w-full hover:bg-primary-dark transition-colors">
                    Setup Automation
                </button>
            </div>
        </div>
    </div>
</main>

<script>
    function runAnalysis() {
        const btn = document.querySelector('button[onclick="runAnalysis()"]');
        const icon = btn.querySelector('.material-symbols-rounded');
        
        icon.classList.add('animate-spin');
        btn.innerHTML = `<span class="material-symbols-rounded text-[18px] animate-spin">refresh</span> Analyzing...`;
        btn.disabled = true;

        setTimeout(() => {
            icon.classList.remove('animate-spin');
            btn.innerHTML = `<span class="material-symbols-rounded text-[18px]">refresh</span> Run Analysis Now`;
            btn.disabled = false;
            alert('AI Risk Analysis completed successfully. Predictions are up to date.');
        }, 2000);
    }
</script>
@endsection
