@extends('layouts.app')

@section('content')
<main class="flex-1 p-lg overflow-y-auto w-full">
    <div class="max-w-[1440px] mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
            <div>
                <h2 class="font-headline-lg text-headline-lg font-bold text-primary mb-xs">AI Timetable Generator</h2>
                <p class="font-body-md text-body-md text-secondary">Intelligently create collision-free class schedules.</p>
            </div>
            <div>
                <button id="generateBtn" class="px-lg py-sm rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[18px]">auto_awesome</span>
                    1-Click Generate Timetable
                </button>
            </div>
        </div>

        <!-- Initial State (Before Generation) -->
        <div id="initialState" class="bento-grid mb-xl">
            <div class="bento-item-large bg-surface-container-lowest p-xl rounded-xl border border-outline-variant shadow-sm text-center py-20">
                <div class="w-20 h-20 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-md">
                    <span class="material-symbols-outlined text-[40px]">calendar_month</span>
                </div>
                <h3 class="font-headline-md text-on-surface mb-sm">No Timetable Generated</h3>
                <p class="text-body-md text-on-surface-variant max-w-md mx-auto mb-lg">
                    Click the "1-Click Generate" button above to let our AI optimize teacher, room, and subject assignments to create a conflict-free schedule.
                </p>
                <div class="flex justify-center gap-md text-sm text-secondary">
                    <div class="flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px] text-green-600">check_circle</span>
                        Avoids Teacher Conflicts
                    </div>
                    <div class="flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px] text-green-600">check_circle</span>
                        Avoids Room Conflicts
                    </div>
                    <div class="flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px] text-green-600">check_circle</span>
                        Optimizes Subject Distribution
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden flex flex-col items-center justify-center py-20 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm">
            <div class="w-16 h-16 border-4 border-primary/30 border-t-primary rounded-full animate-spin mb-md"></div>
            <h3 class="font-headline-md text-primary animate-pulse">AI is calculating optimal schedules...</h3>
            <p class="text-body-md text-secondary mt-2">Checking constraints and avoiding conflicts.</p>
        </div>

        <!-- Result State -->
        <div id="resultState" class="hidden">
            <div class="p-sm bg-green-100 text-green-800 rounded-lg border border-green-200 flex items-center gap-sm mb-lg shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                <span id="resultMessage" class="font-medium text-sm">Timetable generated successfully!</span>
            </div>

            <div id="timetableContainer" class="space-y-lg">
                <!-- Timetables will be injected here via JS -->
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('generateBtn').addEventListener('click', function() {
        // Show loading state
        document.getElementById('initialState').classList.add('hidden');
        document.getElementById('resultState').classList.add('hidden');
        document.getElementById('loadingState').classList.remove('hidden');

        // Simulate network request to AI backend
        fetch("{{ route('admin.ai.timetable.generate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                renderTimetable(data.data);
                document.getElementById('resultMessage').textContent = data.message;
                
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('resultState').classList.remove('hidden');
                UI.showToast('Timetable generated successfully');
            } else {
                throw new Error('Failed to generate timetable');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            UI.showToast('Error generating timetable', 'error');
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('initialState').classList.remove('hidden');
        });
    });

    function renderTimetable(data) {
        const container = document.getElementById('timetableContainer');
        container.innerHTML = ''; // Clear existing

        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        const periods = ['Period 1', 'Period 2', 'Period 3', 'Break', 'Period 4', 'Period 5', 'Period 6'];

        for (const [className, schedule] of Object.entries(data)) {
            let html = `
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden mb-lg">
                    <div class="p-lg border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                        <h3 class="font-headline-md text-headline-md font-semibold text-primary">Class: ${className}</h3>
                        <button class="text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">download</span>
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-surface text-on-surface-variant border-b border-outline-variant">
                                    <th class="p-md font-label-md border-r border-outline-variant w-32">Day / Period</th>
                                    ${periods.map(p => `<th class="p-md font-label-md text-center border-r border-outline-variant ${p==='Break'?'bg-surface-container':''}">${p}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                ${days.map(day => `
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="p-md font-semibold border-r border-outline-variant bg-surface text-on-surface">${day}</td>
                                        ${periods.map(period => {
                                            const cell = schedule[day][period];
                                            if (!cell) return '<td class="p-sm border-r border-outline-variant text-center text-secondary">-</td>';
                                            if (period === 'Break') {
                                                return `<td class="p-sm border-r border-outline-variant bg-surface-container text-center font-bold text-secondary uppercase tracking-widest text-xs">${cell.subject}</td>`;
                                            }
                                            return `
                                                <td class="p-sm border-r border-outline-variant align-top w-40">
                                                    <div class="font-body-md font-bold text-on-surface mb-1 text-sm">${cell.subject}</div>
                                                    <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">
                                                        <span class="material-symbols-outlined text-[12px]">person</span> ${cell.teacher}
                                                    </div>
                                                    <div class="text-xs text-secondary flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[12px]">room</span> ${cell.room}
                                                    </div>
                                                </td>
                                            `;
                                        }).join('')}
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }
    }
</script>
@endsection
