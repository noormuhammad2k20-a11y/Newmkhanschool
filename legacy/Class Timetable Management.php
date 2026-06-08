<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
    <!-- Main Content -->
    <main class="flex-1 p-margin-desktop max-w-[1440px] mx-auto min-h-[calc(100vh-64px)] w-full">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-lg gap-4">
            <div>
                <h2 class="font-headline-xl text-headline-xl text-on-surface mb-2">Class Timetable</h2>
                <p class="font-body-lg text-body-lg text-secondary">Manage weekly schedules for Grade 10 - Section A</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="bg-surface-container-lowest text-primary border border-outline-variant hover:bg-surface-container-high px-4 py-2 rounded font-label-md text-label-md flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined">print</span>
                    Print Timetable
                </button>
                <button class="bg-surface-container-lowest text-primary border border-outline-variant hover:bg-surface-container-high px-4 py-2 rounded font-label-md text-label-md flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined">picture_as_pdf</span>
                    Export PDF
                </button>
                <button class="bg-primary text-on-primary px-4 py-2 rounded font-label-md text-label-md flex items-center gap-2 hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined">edit</span>
                    Edit Schedule
                </button>
            </div>
        </div>
        <!-- Filters -->
        <div class="bg-surface-container-lowest p-md border border-outline-variant rounded mb-lg flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block font-label-md text-label-md text-secondary mb-1">Class Grade</label>
                <div class="relative">
                    <select class="w-full appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface py-2 pl-3 pr-10 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md transition-colors">
                        <option>Grade 10</option>
                        <option>Grade 11</option>
                        <option>Grade 12</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">expand_more</span>
                </div>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block font-label-md text-label-md text-secondary mb-1">Section</label>
                <div class="relative">
                    <select class="w-full appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface py-2 pl-3 pr-10 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md transition-colors">
                        <option>Section A</option>
                        <option>Section B</option>
                        <option>Section C</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">expand_more</span>
                </div>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block font-label-md text-label-md text-secondary mb-1">Academic Session</label>
                <div class="relative">
                    <select class="w-full appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface py-2 pl-3 pr-10 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md transition-colors">
                        <option>2023 - 2024 (Fall)</option>
                        <option>2023 - 2024 (Spring)</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">expand_more</span>
                </div>
            </div>
        </div>
        <!-- Timetable Grid -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] border-collapse">
                    <thead>
                        <tr class="bg-surface-container text-on-surface-variant font-label-md text-label-md">
                            <th class="border-b border-r border-outline-variant p-3 text-left w-24">Time / Day</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32">Monday</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32">Tuesday</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32">Wednesday</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32">Thursday</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32">Friday</th>
                            <th class="border-b border-outline-variant p-3 text-center w-32">Saturday</th>
                        </tr>
                    </thead>
                    <tbody id="timetable-tbody" class="font-body-md text-body-md">
                        <tr><td colspan="7" class="p-8 text-center text-secondary">Loading schedule...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('timetable-tbody');

        fetch(`api/classes.php`)
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    renderTimetable(response.data);
                }
            });

        function renderTimetable(data) {
            let html = '';
            
            data.forEach((slot, index) => {
                const bgClass = index % 2 === 0 ? 'bg-surface-container-lowest' : 'bg-surface';
                
                if (slot.is_break) {
                    html += `
                    <tr class="border-b border-outline-variant bg-surface-container-high text-on-surface-variant text-center font-label-md text-label-md">
                        <td class="border-r border-outline-variant p-2 text-secondary font-medium">${slot.time}<br /><span class="text-xs">${slot.time_end}</span></td>
                        <td class="p-2 uppercase tracking-widest text-secondary" colspan="6">${slot.label}</td>
                    </tr>`;
                    return;
                }
                
                let daysHtml = '';
                const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                
                days.forEach((day, dIdx) => {
                    const classData = slot.days[day];
                    const isLast = dIdx === days.length - 1;
                    const borderClass = isLast ? 'p-2 align-top' : 'border-r border-outline-variant p-2 align-top';
                    
                    if (!classData) {
                        daysHtml += `
                        <td class="${borderClass}">
                            <div class="border border-dashed border-outline-variant rounded p-2 h-full flex items-center justify-center text-secondary hover:bg-surface-container hover:text-primary cursor-pointer transition-colors group min-h-[80px]">
                                <div class="flex flex-col items-center gap-1 opacity-50 group-hover:opacity-100">
                                    <span class="material-symbols-outlined">add_circle</span>
                                    <span class="text-xs">Add Class</span>
                                </div>
                            </div>
                        </td>`;
                    } else if (classData.conflict) {
                        daysHtml += `
                        <td class="${borderClass}">
                            <div class="bg-error-container border border-error rounded p-2 h-full relative">
                                <span class="absolute -top-2 -right-2 bg-error text-on-error rounded-full w-5 h-5 flex items-center justify-center text-[10px]" title="Conflict"><span class="material-symbols-outlined text-[12px]">warning</span></span>
                                <div class="font-semibold text-on-surface">${classData.subject}</div>
                                <div class="text-error text-sm mt-1 font-medium">${classData.teacher}</div>
                                <div class="text-on-surface-variant text-xs mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">room</span> ${classData.room}</div>
                            </div>
                        </td>`;
                    } else {
                        daysHtml += `
                        <td class="${borderClass}">
                            <div class="bg-surface-container-low border border-outline-variant rounded p-2 h-full">
                                <div class="font-semibold text-on-surface">${classData.subject}</div>
                                <div class="text-secondary text-sm mt-1">${classData.teacher}</div>
                                <div class="text-secondary text-xs mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">room</span> ${classData.room}</div>
                            </div>
                        </td>`;
                    }
                });
                
                html += `
                <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors ${bgClass}">
                    <td class="border-r border-outline-variant p-3 text-secondary font-medium whitespace-nowrap">
                        ${slot.time}<br /><span class="text-xs text-outline">${slot.time_end}</span>
                    </td>
                    ${daysHtml}
                </tr>`;
            });
            
            tbody.innerHTML = html;
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
