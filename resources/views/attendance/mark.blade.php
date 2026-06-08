@extends('layouts.app')

@section('title', 'Daily Student Attendance')

@section('content')
        <style>
            /* Custom segmented control for quick attendance marking */
            .attendance-radio:checked+label.status-present {
                background-color: var(--color-primary, #000666);
                color: #ffffff;
                border-color: var(--color-primary, #000666);
            }

            .attendance-radio:checked+label.status-absent {
                background-color: var(--color-error, #ba1a1a);
                color: #ffffff;
                border-color: var(--color-error, #ba1a1a);
            }

            .attendance-radio:checked+label.status-leave {
                background-color: var(--color-secondary, #526069);
                color: #ffffff;
                border-color: var(--color-secondary, #526069);
            }
        </style>
        <!-- Canvas Area -->
        <main class="flex-1 p-lg bg-surface">
            <div class="max-w-[max-width] mx-auto">
                <!-- Page Header -->
                <div class="mb-lg flex justify-between items-end">
                    <div>
                        <h2 class="text-headline-lg font-headline-lg text-on-surface mb-xs">Daily Student Attendance</h2>
                        <p class="text-body-md font-body-md text-on-surface-variant">Record and verify daily attendance for academic blocks.</p>
                    </div>
                    <div class="flex items-center gap-sm">
                        <span class="text-label-md font-label-md text-secondary">Status:</span>
                        <span id="attendance-status" class="bg-error-container text-on-error-container px-sm py-[2px] rounded-full text-label-md font-label-md border border-error/20">Pending Submission</span>
                    </div>
                </div>
                <!-- Filters Card (Level 1 Surface) -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md mb-lg shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-md items-end">
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md font-label-md text-on-surface-variant uppercase">Class Grade</label>
                            <select id="class-filter" class="w-full border border-outline-variant rounded py-sm px-sm text-body-md font-body-md bg-transparent focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                @foreach(DB::table('classes')->orderBy('name')->get() as $class)
                                    <option value="{{ $class->name }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md font-label-md text-on-surface-variant uppercase">Section</label>
                            <select id="section-filter" class="w-full border border-outline-variant rounded py-sm px-sm text-body-md font-body-md bg-transparent focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                @foreach(DB::table('sections')->select('name')->distinct()->orderBy('name')->get() as $section)
                                    <option value="{{ $section->name }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md font-label-md text-on-surface-variant uppercase">Date</label>
                            <input id="date-filter" class="w-full border border-outline-variant rounded py-sm px-sm text-body-md font-body-md bg-transparent focus:border-primary focus:ring-1 focus:ring-primary outline-none text-on-surface" type="date" value="{{ date('Y-m-d') }}" />
                        </div>
                        <div class="flex justify-end">
                            <button id="apply-filters" class="bg-surface-container-high text-on-surface border border-outline-variant px-md py-sm rounded text-label-md font-label-md hover:bg-surface-variant transition-colors flex items-center gap-xs w-full justify-center">
                                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Table Card (Level 1 Surface) -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden flex flex-col">
                    <!-- Table Toolbar -->
                    <div class="p-md border-b border-outline-variant bg-surface-container-lowest flex justify-between items-center">
                        <h3 class="text-headline-md font-headline-md text-on-surface">Attendance Roster</h3>
                        <button class="bg-primary-fixed text-on-primary-fixed px-md py-sm rounded border border-primary/20 text-label-md font-label-md hover:bg-primary-fixed-dim transition-colors flex items-center gap-xs" onclick="markAllPresent()">
                            <span class="material-symbols-outlined text-[18px]">done_all</span>
                            Mark All Present
                        </button>
                    </div>
                    <!-- Data Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-outline-variant">
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant uppercase w-24">Roll No</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant uppercase">Student Name</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant uppercase text-center w-96">Attendance Status</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant uppercase text-right w-32">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-body-md font-body-md" id="attendance-tbody">
                                <tr><td colspan="4" class="py-8 text-center text-secondary">Loading students...</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Table Footer / Pagination Placeholder -->
                    <div class="p-md bg-surface-container-lowest border-t border-outline-variant flex justify-between items-center text-label-md font-label-md text-on-surface-variant">
                        <span id="student-count">Showing 0 students</span>
                        <div class="flex gap-sm">
                            <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container-high disabled:opacity-50" disabled="">Prev</button>
                            <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container-high disabled:opacity-50" disabled="">Next</button>
                        </div>
                    </div>
                </div>
                <!-- Action Bar -->
                <div class="mt-lg flex justify-end gap-md">
                    <button class="px-lg py-sm border border-outline-variant rounded text-on-surface text-label-md font-label-md hover:bg-surface-container-high transition-colors">
                        Cancel
                    </button>
                    <button id="save-attendance" class="px-lg py-sm bg-primary text-on-primary rounded text-label-md font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Save Attendance
                    </button>
                </div>
            </div>
        </main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('attendance-tbody');
        
        function fetchAttendance() {
            const classFilter = encodeURIComponent(document.getElementById('class-filter').value);
            const sectionFilter = encodeURIComponent(document.getElementById('section-filter').value);
            const dateFilter = encodeURIComponent(document.getElementById('date-filter').value);
            
            fetch(`/api/attendance?class_grade=${classFilter}&section=${sectionFilter}&date=${dateFilter}`)
                .then(res => res.json())
                .then(response => {
                    if (response.status === 'success') {
                        renderAttendance(response.data);
                    }
                });
        }

        // Initial fetch
        fetchAttendance();

        document.getElementById('apply-filters').addEventListener('click', fetchAttendance);

        function renderAttendance(students) {
            document.getElementById('student-count').textContent = `Showing ${students.length} students`;
            
            if (students.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-secondary">No students found for this class.</td></tr>`;
                return;
            }

            let html = '';
            students.forEach((student, index) => {
                const rowClass = index % 2 !== 0 ? 'bg-surface-bright' : 'bg-surface-container-lowest';
                
                const pChecked = student.attendance_status === 'P' ? 'checked' : '';
                const aChecked = student.attendance_status === 'A' ? 'checked' : '';
                const lChecked = student.attendance_status === 'L' ? 'checked' : '';

                html += `
                <tr class="${rowClass} border-b border-outline-variant/50 hover:bg-surface-container-low transition-colors">
                    <td class="py-sm px-md text-on-surface-variant font-medium">${student.roll_no || '-'}</td>
                    <td class="py-sm px-md text-on-surface">${student.first_name} ${student.last_name || ''}</td>
                    <td class="py-sm px-md">
                        <div class="flex items-center justify-center gap-sm">
                            <div class="relative">
                                <input class="attendance-radio sr-only peer" id="p-${student.id}" name="status-${student.id}" type="radio" value="present" ${pChecked} />
                                <label class="status-present cursor-pointer border border-outline-variant px-sm py-[4px] rounded text-label-md font-label-md text-on-surface-variant hover:bg-surface-container-high transition-colors inline-block w-24 text-center" for="p-${student.id}">Present</label>
                            </div>
                            <div class="relative">
                                <input class="attendance-radio sr-only peer" id="a-${student.id}" name="status-${student.id}" type="radio" value="absent" ${aChecked} />
                                <label class="status-absent cursor-pointer border border-outline-variant px-sm py-[4px] rounded text-label-md font-label-md text-on-surface-variant hover:bg-surface-container-high transition-colors inline-block w-24 text-center" for="a-${student.id}">Absent</label>
                            </div>
                            <div class="relative">
                                <input class="attendance-radio sr-only peer" id="l-${student.id}" name="status-${student.id}" type="radio" value="leave" ${lChecked} />
                                <label class="status-leave cursor-pointer border border-outline-variant px-sm py-[4px] rounded text-label-md font-label-md text-on-surface-variant hover:bg-surface-container-high transition-colors inline-block w-24 text-center" for="l-${student.id}">Leave</label>
                            </div>
                        </div>
                    </td>
                    <td class="py-sm px-md text-right">
                        <button class="text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                        </button>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        window.markAllPresent = function() {
            const radios = document.querySelectorAll('input[type="radio"][value="present"]');
            radios.forEach(radio => {
                radio.checked = true;
            });
        };

        document.getElementById('save-attendance').addEventListener('click', function() {
            // Collect attendance data
            const attendanceData = [];
            document.querySelectorAll('#attendance-tbody tr').forEach(row => {
                const checkedRadio = row.querySelector('.attendance-radio:checked');
                if (checkedRadio) {
                    const studentId = checkedRadio.id.split('-')[1];
                    let status = 'P';
                    if (checkedRadio.value === 'absent') status = 'A';
                    if (checkedRadio.value === 'leave') status = 'L';
                    
                    attendanceData.push({
                        student_id: studentId,
                        status: status
                    });
                }
            });

            if (attendanceData.length === 0) {
                alert('Please mark attendance for at least one student.');
                return;
            }

            fetch('/api/attendance', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: 'save', attendance: attendanceData, date: document.getElementById('date-filter').value })
            }).then(res => res.json()).then(res => {
                if (res.status === 'success') {
                    const statusBadge = document.getElementById('attendance-status');
                    statusBadge.textContent = 'Submitted';
                    statusBadge.className = 'bg-[#e6f4ea] text-[#137333] px-sm py-[2px] rounded-full text-label-md font-label-md border border-[#137333]/20';
                    alert(res.message);
                } else {
                    alert(res.message || 'Error saving attendance');
                }
            }).catch(err => {
                alert('Failed to save attendance.');
                console.error(err);
            });
        });
    });
</script>
@endsection
