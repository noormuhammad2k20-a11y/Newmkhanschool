@extends('layouts.app')

@section('title', 'AI Timetable Generator')

@section('content')
<main class="flex-grow p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
    <!-- Header -->
    <div class="mb-xl flex flex-col md:flex-row md:items-end justify-between gap-md">
        <div>
            <h1 class="text-headline-lg-mobile md:text-headline-xl font-headline-lg-mobile md:font-headline-xl text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[32px] md:text-[40px]">calendar_month</span>
                AI Timetable Generator
            </h1>
            <p class="text-body-md font-body-md text-secondary mt-1">Automatically generate optimized class schedules using AI constraints logic.</p>
        </div>
        <div class="flex gap-sm">
            <button class="px-md py-sm border border-outline-variant rounded bg-surface-container-lowest text-on-surface text-label-md font-label-md hover:bg-surface-container-low transition-colors flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">history</span>
                Generation History
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        
        <!-- Configuration Form -->
        <div class="lg:col-span-1 flex flex-col gap-md">
            <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col p-md">
                <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Constraints Setup</h3>
                
                <form id="generatorForm" class="flex flex-col gap-4" onsubmit="event.preventDefault(); startGeneration();">
                    <div>
                        <label class="block text-label-md font-label-md text-on-surface mb-2">Target Term / Session</label>
                        <select class="w-full bg-surface-container border border-outline-variant text-on-surface text-body-md rounded-lg p-2 focus:ring-primary focus:border-primary" required>
                            <option value="">Select Session</option>
                            <option value="2026-T1" selected>Term 1 - 2026</option>
                            <option value="2026-T2">Term 2 - 2026</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-label-md font-label-md text-on-surface mb-2">Classes</label>
                        <select multiple class="w-full bg-surface-container border border-outline-variant text-on-surface text-body-md rounded-lg p-2 focus:ring-primary focus:border-primary min-h-[100px]" required>
                            <option value="all" selected>All Classes</option>
                            <option value="grade1">Grade 1</option>
                            <option value="grade2">Grade 2</option>
                            <option value="grade3">Grade 3</option>
                        </select>
                        <p class="text-xs text-secondary mt-1">Hold Ctrl (or Cmd) to select multiple</p>
                    </div>

                    <div class="space-y-2 mt-2 border-t border-outline-variant pt-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 text-primary border-outline-variant rounded focus:ring-primary">
                            <span class="text-body-md text-on-surface">Optimize for Teacher Workload</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 text-primary border-outline-variant rounded focus:ring-primary">
                            <span class="text-body-md text-on-surface">Avoid Consecutive Hard Subjects</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 text-primary border-outline-variant rounded focus:ring-primary">
                            <span class="text-body-md text-on-surface">Room Availability Constraints</span>
                        </label>
                    </div>

                    <button type="submit" id="generateBtn" class="mt-4 px-md py-3 bg-primary text-on-primary rounded-xl font-label-lg text-label-lg hover:bg-primary-dark transition-colors flex items-center justify-center gap-2 w-full shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">magic_button</span>
                        Generate Timetable
                    </button>
                </form>
            </div>
            
            <!-- AI Feedback Panel -->
            <div id="aiFeedback" class="bg-primary-container text-on-primary-container rounded-xl shadow-sm p-md hidden">
                <h3 class="text-title-md font-title-md mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined">auto_awesome</span>
                    AI Optimization Results
                </h3>
                <ul class="text-body-md font-body-md space-y-2 mt-3 list-disc pl-5">
                    <li>Teacher workload balanced perfectly across all 32 staff.</li>
                    <li>No consecutive hard subjects (Math/Physics) detected for grades 8-10.</li>
                    <li>Resolved 12 scheduling conflicts from previous iteration.</li>
                </ul>
            </div>
        </div>

        <!-- Preview / Output Area -->
        <div class="lg:col-span-2 bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col min-h-[500px]">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">Generation Preview</h3>
                <div class="flex gap-2">
                    <button id="saveBtn" class="hidden px-md py-sm bg-success text-on-success rounded font-label-md hover:bg-success/90 transition-colors shadow-sm flex items-center gap-xs" onclick="saveTimetable()">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Save & Publish
                    </button>
                </div>
            </div>
            
            <div id="previewArea" class="flex-grow flex items-center justify-center p-xl relative bg-surface-container-lowest">
                <!-- Initial State -->
                <div id="initialState" class="text-center text-secondary">
                    <span class="material-symbols-outlined text-[64px] opacity-30 mb-4 block">event_note</span>
                    <p class="text-title-lg font-title-lg">Ready to Generate</p>
                    <p class="text-body-md mt-2">Configure constraints and click generate to see the AI output here.</p>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="hidden text-center text-primary w-full max-w-md">
                    <span class="material-symbols-outlined text-[48px] animate-spin mb-4 block mx-auto">sync</span>
                    <p class="text-title-md font-title-md mb-2">AI is calculating optimal schedules...</p>
                    <div class="w-full bg-surface-container-high rounded-full h-2.5 mb-2 overflow-hidden">
                        <div class="bg-primary h-2.5 rounded-full animate-pulse" style="width: 100%"></div>
                    </div>
                    <p class="text-xs text-secondary mt-4">Solving constraint matrix: Workload, Subjects, Rooms...</p>
                </div>

                <!-- Success State (Table Preview) -->
                <div id="successState" class="hidden w-full h-full overflow-auto">
                    <div class="bg-surface p-4 border border-outline-variant rounded mt-2">
                        <h4 class="font-bold text-on-surface mb-4">Sample Output: Grade 10-A</h4>
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-outline-variant">
                                    <th class="py-2 px-3">Time</th>
                                    <th class="py-2 px-3">Monday</th>
                                    <th class="py-2 px-3">Tuesday</th>
                                    <th class="py-2 px-3">Wednesday</th>
                                    <th class="py-2 px-3">Thursday</th>
                                    <th class="py-2 px-3">Friday</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <tr>
                                    <td class="py-2 px-3 font-medium">08:00 - 08:45</td>
                                    <td class="py-2 px-3 bg-blue-50 text-blue-900 border border-blue-100 rounded">Math<br><span class="text-xs">Mr. Ali</span></td>
                                    <td class="py-2 px-3 bg-green-50 text-green-900 border border-green-100 rounded">Physics<br><span class="text-xs">Mr. Tariq</span></td>
                                    <td class="py-2 px-3 bg-purple-50 text-purple-900 border border-purple-100 rounded">English<br><span class="text-xs">Ms. Sara</span></td>
                                    <td class="py-2 px-3 bg-yellow-50 text-yellow-900 border border-yellow-100 rounded">Chemistry<br><span class="text-xs">Mr. Usman</span></td>
                                    <td class="py-2 px-3 bg-blue-50 text-blue-900 border border-blue-100 rounded">Math<br><span class="text-xs">Mr. Ali</span></td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-3 font-medium">08:45 - 09:30</td>
                                    <td class="py-2 px-3 bg-purple-50 text-purple-900 border border-purple-100 rounded">English<br><span class="text-xs">Ms. Sara</span></td>
                                    <td class="py-2 px-3 bg-blue-50 text-blue-900 border border-blue-100 rounded">Math<br><span class="text-xs">Mr. Ali</span></td>
                                    <td class="py-2 px-3 bg-red-50 text-red-900 border border-red-100 rounded">Urdu<br><span class="text-xs">Mr. Hamza</span></td>
                                    <td class="py-2 px-3 bg-green-50 text-green-900 border border-green-100 rounded">Physics<br><span class="text-xs">Mr. Tariq</span></td>
                                    <td class="py-2 px-3 bg-orange-50 text-orange-900 border border-orange-100 rounded">Islamiat<br><span class="text-xs">Mr. Omer</span></td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-3 font-medium text-secondary text-center" colspan="6">-- BREAK --</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-3 font-medium">10:00 - 10:45</td>
                                    <td class="py-2 px-3 bg-yellow-50 text-yellow-900 border border-yellow-100 rounded">Chemistry<br><span class="text-xs">Mr. Usman</span></td>
                                    <td class="py-2 px-3 bg-purple-50 text-purple-900 border border-purple-100 rounded">English<br><span class="text-xs">Ms. Sara</span></td>
                                    <td class="py-2 px-3 bg-blue-50 text-blue-900 border border-blue-100 rounded">Math<br><span class="text-xs">Mr. Ali</span></td>
                                    <td class="py-2 px-3 bg-gray-50 text-gray-900 border border-gray-100 rounded">Computer<br><span class="text-xs">Ms. Zainab</span></td>
                                    <td class="py-2 px-3 bg-red-50 text-red-900 border border-red-100 rounded">Urdu<br><span class="text-xs">Mr. Hamza</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function startGeneration() {
        document.getElementById('initialState').classList.add('hidden');
        document.getElementById('successState').classList.add('hidden');
        document.getElementById('aiFeedback').classList.add('hidden');
        document.getElementById('saveBtn').classList.add('hidden');
        
        document.getElementById('loadingState').classList.remove('hidden');
        
        const btn = document.getElementById('generateBtn');
        btn.disabled = true;
        btn.innerHTML = `<span class="material-symbols-outlined text-[20px] animate-spin">sync</span> Generating...`;

        setTimeout(() => {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('successState').classList.remove('hidden');
            document.getElementById('aiFeedback').classList.remove('hidden');
            document.getElementById('saveBtn').classList.remove('hidden');
            
            btn.disabled = false;
            btn.innerHTML = `<span class="material-symbols-outlined text-[20px]">magic_button</span> Re-Generate Timetable`;
        }, 3000); // simulate ML backend processing delay
    }

    function saveTimetable() {
        const btn = document.getElementById('saveBtn');
        btn.innerHTML = `<span class="material-symbols-outlined text-[18px] animate-spin">sync</span> Saving...`;
        
        setTimeout(() => {
            alert("Timetable successfully saved and published.");
            btn.innerHTML = `<span class="material-symbols-outlined text-[18px]">check</span> Saved`;
            btn.classList.replace('bg-success', 'bg-surface-variant');
            btn.classList.replace('text-on-success', 'text-on-surface');
            btn.disabled = true;
        }, 1000);
    }
</script>
@endsection
