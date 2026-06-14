@extends('layouts.app')

@section('content')
<main class="flex-1 p-lg overflow-y-auto w-full min-w-0">
    <div class="max-w-[1440px] mx-auto">
        <!-- Header Section -->
            <div class="flex flex-col 2xl:flex-row 2xl:items-center justify-between gap-md mb-xl">
            <div class="flex-1 min-w-0 mb-4 2xl:mb-0">
                <h2 class="font-headline-lg text-headline-lg font-bold text-primary mb-xs truncate">AI Timetable Generator</h2>
                <p class="font-body-md text-body-md text-secondary truncate">Intelligently create and edit collision-free class schedules.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 shrink-0 bg-surface-container-lowest p-2 rounded-xl border border-outline-variant shadow-sm w-full 2xl:w-auto overflow-x-auto">
                <button id="approveBtn" class="hidden px-4 py-2 rounded-lg bg-blue-600 text-white font-label-md hover:bg-blue-700 transition-colors shadow-sm items-center justify-center gap-2 whitespace-nowrap min-w-[160px] h-[40px]">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    Approve Timetable
                </button>
                
                <button id="historyBtn" class="px-4 py-2 rounded-lg border border-outline text-secondary font-label-md hover:bg-surface-container transition-colors shadow-sm flex items-center justify-center gap-2 whitespace-nowrap min-w-[140px] h-[40px]">
                    <span class="material-symbols-outlined text-[18px]">history</span>
                    View History
                </button>
                
                <button id="editBtn" class="hidden px-4 py-2 rounded-lg border border-outline text-primary font-label-md hover:bg-surface-container transition-colors shadow-sm items-center justify-center gap-2 whitespace-nowrap min-w-[160px] h-[40px]">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit AI Timetable
                </button>
                
                <button id="generateBtn" class="px-4 py-2 rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center justify-center gap-2 whitespace-nowrap min-w-[200px] h-[40px]">
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
                <h3 class="font-headline-md text-on-surface mb-sm">No Timetable Found</h3>
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
            <h3 class="font-headline-md text-primary animate-pulse" id="loadingText">AI is calculating optimal schedules...</h3>
            <p class="text-body-md text-secondary mt-2">Checking constraints and avoiding conflicts.</p>
        </div>

        <!-- Result State -->
        <div id="resultState" class="hidden">
            <!-- Timetable Information Card -->
            <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm mb-lg">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-secondary mb-2">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">person</span>
                                <span id="infoCreatedBy">Created By</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                <span id="infoCreatedAt">Date</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">update</span>
                                <span id="infoUpdatedAt">Last Updated</span>
                            </div>
                        </div>
                        <div id="infoActionMetadata" class="text-xs text-secondary mt-2 hidden flex-wrap gap-x-4 gap-y-1"></div>
                    </div>
                </div>
            </div>

            <div class="p-sm bg-green-100 text-green-800 rounded-lg border border-green-200 flex items-center gap-sm mb-lg shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                <span id="resultMessage" class="font-medium text-sm">Timetable loaded.</span>
            </div>

            <div id="timetableContainer" class="space-y-lg">
                <!-- Timetables will be injected here via JS -->
            </div>
        </div>
    </div>
</main>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-surface p-lg rounded-xl shadow-lg w-full max-w-md border border-outline-variant">
        <h3 class="font-headline-md text-primary mb-md flex justify-between">
            Edit Timetable Slot
            <button onclick="closeEditModal()" class="text-secondary hover:text-primary"><span class="material-symbols-outlined">close</span></button>
        </h3>
        
        <form id="editSlotForm" class="space-y-sm">
            <input type="hidden" id="editSlotId" name="id">
            <input type="hidden" id="editDayOfWeek" name="day_of_week">
            <input type="hidden" id="editStartTime" name="start_time">
            <input type="hidden" id="editEndTime" name="end_time">
            
            <div class="bg-surface-container p-3 rounded-lg border border-outline-variant mb-4 text-sm text-on-surface-variant flex flex-col gap-1">
                <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">school</span> <strong id="editClassSectionLabel">Class</strong></div>
                <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">schedule</span> <strong id="editTimeLabel">Time</strong></div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary mb-1">Subject</label>
                <select id="editSubject" name="subject_id" class="w-full p-2 border border-outline rounded-md bg-surface text-on-surface" required>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary mb-1">Teacher</label>
                <select id="editTeacher" name="teacher_id" class="w-full p-2 border border-outline rounded-md bg-surface text-on-surface" required>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary mb-1">Room</label>
                <select id="editRoom" name="room" class="w-full p-2 border border-outline rounded-md bg-surface text-on-surface" required>
                </select>
            </div>

            <div class="mt-md flex flex-col gap-2">
                <button type="button" id="aiSuggestBtn" class="w-full py-2 bg-secondary-container text-on-secondary-container rounded-md flex justify-center items-center gap-1 hover:bg-opacity-80 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">psychology</span>
                    Suggest AI Alternatives
                </button>
                <button type="submit" class="w-full py-2 bg-primary text-on-primary rounded-md flex justify-center items-center gap-1 hover:bg-primary-container transition-colors">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    Save Changes
                </button>
            </div>
            <p id="editError" class="text-red-500 text-sm mt-2 hidden"></p>
        </form>
    </div>
</div>

<!-- History Modal -->
<div id="historyModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-surface p-lg rounded-xl shadow-lg w-full max-w-2xl border border-outline-variant max-h-[80vh] flex flex-col">
        <h3 class="font-headline-md text-primary mb-md flex justify-between">
            Timetable Change History
            <button onclick="closeHistoryModal()" class="text-secondary hover:text-primary"><span class="material-symbols-outlined">close</span></button>
        </h3>
        <div class="overflow-y-auto flex-1 pr-2 space-y-sm" id="historyContainer">
            <!-- History items here -->
        </div>
    </div>
</div>

<script>
    let isEditMode = false;
    let currentTimetableData = null;

    let currentVersionId = null;

    document.addEventListener('DOMContentLoaded', () => {
        fetchVersions().then(() => fetchTimetable());
    });
    
    function fetchVersions() {
        return fetch("{{ route('admin.ai.timetable.versions') }}")
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Handled gracefully in showResult
                }
            });
    }



    document.getElementById('approveBtn').addEventListener('click', function() {
        if(!currentVersionId) return;
        
        UI.confirm('Approve Timetable', 'Are you sure you want to approve this timetable?').then(confirmed => {
            if(confirmed) {
                fetch(`/admin/ai/timetable/versions/${currentVersionId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    UI.showToast(data.message, data.status);
                    fetchVersions();
                    fetchTimetable(currentVersionId);
                });
            }
        });
    });

    document.getElementById('editBtn').addEventListener('click', function() {
        isEditMode = !isEditMode;
        this.classList.toggle('bg-primary-container', isEditMode);
        this.classList.toggle('text-primary', !isEditMode);
        
        if (isEditMode) {
            UI.showToast('Edit Mode Enabled. Click any cell to modify.');
        } else {
            UI.showToast('Edit Mode Disabled.');
        }
        
        // Re-render to add/remove clickable styling
        if (currentTimetableData) renderTimetable(currentTimetableData);
    });

    document.getElementById('generateBtn').addEventListener('click', function() {
        showLoading('AI is calculating optimal schedules...');

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
                currentTimetableData = data.data;
                currentVersionId = data.version_id;
                renderTimetable(data.data);
                document.getElementById('resultMessage').textContent = data.message;
                showResult(data.version);
                UI.showToast('Timetable generated successfully');
                fetchVersions();
            } else {
                throw new Error('Failed to generate timetable');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            UI.showToast('Error generating timetable', 'error');
            showInitial();
        });
    });

    function fetchTimetable(versionId = null) {
        let url = "{{ route('admin.ai.timetable.fetch') }}";
        if (versionId) {
            url += `?version_id=${versionId}`;
        }
        
        fetch(url)
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                currentTimetableData = data.data;
                currentVersionId = data.version ? data.version.id : null;
                renderTimetable(data.data);
                document.getElementById('resultMessage').textContent = data.message;
                showResult(data.version);
            } else {
                showInitial();
            }
        });
    }

    function showLoading(text) {
        document.getElementById('initialState').classList.add('hidden');
        document.getElementById('resultState').classList.add('hidden');
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('loadingText').textContent = text;
    }

    function showResult(versionData = null) {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('initialState').classList.add('hidden');
        document.getElementById('resultState').classList.remove('hidden');
        
        const approveBtn = document.getElementById('approveBtn');
        const editBtn = document.getElementById('editBtn');
        const metaDiv = document.getElementById('infoActionMetadata');
        
        approveBtn.classList.add('hidden'); approveBtn.classList.remove('flex');
        editBtn.classList.add('hidden'); editBtn.classList.remove('flex');
        metaDiv.classList.add('hidden');
        metaDiv.innerHTML = '';
        
        if (versionData) {
            document.getElementById('infoCreatedBy').textContent = versionData.created_by ? versionData.created_by.name : 'System';
            document.getElementById('infoCreatedAt').textContent = new Date(versionData.created_at).toLocaleString();
            document.getElementById('infoUpdatedAt').textContent = new Date(versionData.updated_at).toLocaleString();
            
            // Format Dates
            let metadataHtml = [];
            
            if (versionData.approved_by && versionData.approved_by.name) {
                metadataHtml.push(`<div><strong class="text-on-surface">Approved:</strong>{versionData.approved_by.name} on ${new Date(versionData.approved_at).toLocaleString()}</div>`);
            }
            
            if (metadataHtml.length > 0) {
                metaDiv.innerHTML = metadataHtml.join('');
                metaDiv.classList.remove('hidden');
                metaDiv.classList.add('flex');
            }

            if (versionData.status !== 'Approved') {
                approveBtn.classList.remove('hidden'); approveBtn.classList.add('flex');
                editBtn.classList.remove('hidden'); editBtn.classList.add('flex');
            }
        }
    }

    function showInitial() {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('resultState').classList.add('hidden');
        document.getElementById('initialState').classList.remove('hidden');
        
        ['editBtn', 'approveBtn'].forEach(id => {
            const btn = document.getElementById(id);
            if(btn) {
                btn.classList.add('hidden');
                btn.classList.remove('flex');
            }
        });
    }

    function renderTimetable(data) {
        const container = document.getElementById('timetableContainer');
        container.innerHTML = ''; 

        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        const periods = ['Period 1', 'Period 2', 'Period 3', 'Break', 'Period 4', 'Period 5', 'Period 6'];

        for (const [className, schedule] of Object.entries(data)) {
            let html = `
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden mb-lg">
                    <div class="p-lg border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                        <h3 class="font-headline-md text-headline-md font-semibold text-primary">Class: {className}</h3>
                        <button class="text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">download</span>
                        </button>
                    </div>
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="bg-surface text-on-surface-variant border-b border-outline-variant">
                                    <th class="p-md font-label-md border-r border-outline-variant w-32 min-w-[120px] whitespace-nowrap">Day / Period</th>{periods.map(p => `<th class="p-md font-label-md text-center border-r border-outline-variant min-w-[160px] ${p==='Break'?'bg-surface-container min-w-[120px]':''} whitespace-nowrap">{p}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">{days.map(day => `
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="p-md font-semibold border-r border-outline-variant bg-surface text-on-surface whitespace-nowrap">{day}</td>{periods.map(period => {
                                            const cell = schedule[day] ? schedule[day][period] : null;
                                            if (!cell) return '<td class="p-sm border-r border-outline-variant text-center text-secondary min-w-[160px]">-</td>';
                                            if (period === 'Break') {
                                                return `<td class="p-sm border-r border-outline-variant bg-surface-container text-center font-bold text-secondary uppercase tracking-widest text-xs min-w-[120px]">{cell.subject}</td>`;
                                            }
                                            
                                            const cursorClass = isEditMode ? 'cursor-pointer hover:bg-primary-container/20 transition-colors' : '';
                                            const onClick = isEditMode ? `onclick="openEditModal(${cell.id}, '${day}', '${cell.time}', '${cell.subject_id}', '${cell.teacher_id}', '${cell.room}', '${className}')"` : '';
                                            
                                            return `
                                                <td class="p-sm border-r border-outline-variant align-top min-w-[160px] w-40 ${cursorClass}" ${onClick}>
                                                    <div class="font-body-md font-bold text-on-surface mb-1 text-sm">{cell.subject}</div>
                                                    <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">
                                                        <span class="material-symbols-outlined text-[12px]">person</span>{cell.teacher}
                                                    </div>
                                                    <div class="text-xs text-secondary flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[12px]">room</span>{cell.room}
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

    let currentEditValues = {};

    function openEditModal(slotId, dayOfWeek, timeStr, subjectId, teacherId, room, className) {
        document.getElementById('editSlotId').value = slotId;
        document.getElementById('editDayOfWeek').value = dayOfWeek;
        
        const [start, end] = timeStr.split(' - ');
        document.getElementById('editStartTime').value = start + ':00';
        document.getElementById('editEndTime').value = end + ':00';
        
        document.getElementById('editClassSectionLabel').textContent = className;
        document.getElementById('editTimeLabel').textContent = `${dayOfWeek}, ${timeStr}`;
        
        currentEditValues = {
            subject_id: subjectId,
            teacher_id: teacherId,
            room: room
        };

        document.getElementById('editError').classList.add('hidden');
        document.getElementById('editModal').classList.remove('hidden');

        // Fetch suggestions to populate dropdowns immediately on open
        fetchSuggestions(true);
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function fetchSuggestions(isInitialLoad = false) {
        const aiSuggestBtn = document.getElementById('aiSuggestBtn');
        aiSuggestBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Loading...';
        
        fetch("{{ route('admin.ai.timetable.suggestions') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                slot_id: document.getElementById('editSlotId').value,
                day_of_week: document.getElementById('editDayOfWeek').value,
                start_time: document.getElementById('editStartTime').value,
                end_time: document.getElementById('editEndTime').value,
                initial_load: isInitialLoad
            })
        })
        .then(res => res.json())
        .then(data => {
            aiSuggestBtn.innerHTML = '<span class="material-symbols-outlined">psychology</span> Suggest AI Alternatives';
            if (data.status === 'success') {
                populateDropdowns(data.data);
                if (!isInitialLoad) {
                    UI.showToast('AI Suggestions Loaded');
                }
            }
        });
    }

    document.getElementById('aiSuggestBtn').addEventListener('click', () => fetchSuggestions(false));

    function populateDropdowns(data) {
        const teacherSel = document.getElementById('editTeacher');
        teacherSel.innerHTML = '<option value="">Select Teacher</option>';
        data.teachers.forEach(t => {
            teacherSel.innerHTML += `<option value="${t.id}">{t.full_name}</option>`;
        });
        if (currentEditValues.teacher_id && currentEditValues.teacher_id !== 'null') {
            teacherSel.value = currentEditValues.teacher_id;
        }

        const roomSel = document.getElementById('editRoom');
        roomSel.innerHTML = '<option value="">Select Room</option>';
        data.rooms.forEach(r => {
            roomSel.innerHTML += `<option value="${r}">{r}</option>`;
        });
        if (currentEditValues.room && currentEditValues.room !== 'null') {
            roomSel.value = currentEditValues.room;
        }

        const subSel = document.getElementById('editSubject');
        subSel.innerHTML = '<option value="">Select Subject</option>';
        data.subjects.forEach(s => {
            subSel.innerHTML += `<option value="${s.id}">{s.name}</option>`;
        });
        if (currentEditValues.subject_id && currentEditValues.subject_id !== 'null') {
            subSel.value = currentEditValues.subject_id;
        }
    }

    document.getElementById('editSlotForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const slotId = document.getElementById('editSlotId').value;
        const errEl = document.getElementById('editError');
        
        errEl.classList.add('hidden');

        fetch(`/admin/ai/timetable/slot/${slotId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                teacher_id: document.getElementById('editTeacher').value,
                room: document.getElementById('editRoom').value,
                subject_id: document.getElementById('editSubject').value
            })
        })
        .then(async (res) => {
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Error saving slot');
            }
            return data;
        })
        .then(data => {
            UI.showToast(data.message);
            closeEditModal();
            fetchTimetable(); // Refresh table
        })
        .catch(err => {
            errEl.textContent = err.message;
            errEl.classList.remove('hidden');
        });
    });

    document.getElementById('historyBtn').addEventListener('click', function() {
        document.getElementById('historyModal').classList.remove('hidden');
        document.getElementById('historyContainer').innerHTML = '<p class="text-secondary text-center">Loading history...</p>';

        fetch("{{ route('admin.ai.timetable.history') }}")
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.data.length === 0) {
                    document.getElementById('historyContainer').innerHTML = '<p class="text-secondary text-center">No history found.</p>';
                    return;
                }
                
                let html = '';
                data.data.forEach(log => {
                    const date = new Date(log.created_at).toLocaleString();
                    html += `
                        <div class="p-sm bg-surface-container-lowest border border-outline-variant rounded-md">
                            <div class="text-xs text-secondary mb-1">{date}</div>
                            <div class="text-sm text-on-surface">{log.description}</div>
                        </div>
                    `;
                });
                document.getElementById('historyContainer').innerHTML = html;
            }
        });
    });

    function closeHistoryModal() {
        document.getElementById('historyModal').classList.add('hidden');
    }
</script>
@endsection
