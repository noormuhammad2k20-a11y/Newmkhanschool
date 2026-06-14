@extends('layouts.app')

@section('title', 'Bulk Marks Entry')

@section('content')
    <style>
        /* Custom scrollbar for table */
        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: var(--color-surface-container-lowest, #ffffff);
        }

        .table-container::-webkit-scrollbar-thumb {
            background: var(--color-outline-variant, #c6c5d4);
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: var(--color-outline, #767683);
        }
    </style>

    <main class="flex-grow p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
        <!-- Page Header -->
        <div class="mb-lg flex flex-col md:flex-row md:items-end justify-between gap-md">
            <div>
                <div class="flex items-center gap-sm text-secondary mb-xs">
                    <a class="text-label-md font-label-md hover:underline" href="{{ route('admin.exams') }}">Examination</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-label-md font-label-md text-on-surface">Bulk Marks Entry</span>
                </div>
                <h1 class="text-headline-lg-mobile md:text-headline-xl font-headline-lg-mobile md:font-headline-xl text-on-surface">Bulk Marks Entry</h1>
            </div>
            <div class="flex gap-sm">
                <button class="px-md py-sm border border-outline-variant rounded bg-surface-container-lowest text-on-surface text-label-md font-label-md hover:bg-surface-container-low transition-colors flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Export Template
                </button>
            </div>
        </div>
        <!-- Context Banner -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md mb-lg flex flex-col md:flex-row gap-lg md:items-center shadow-sm">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-md">
                <div>
                    <span class="text-label-md font-label-md text-secondary block mb-base">Class / Section</span>
                    <span class="text-body-lg font-body-lg text-on-surface font-semibold">Grade 10 - Section A</span>
                </div>
                <div>
                    <span class="text-label-md font-label-md text-secondary block mb-base">Subject</span>
                    <span class="text-body-lg font-body-lg text-on-surface font-semibold">Mathematics (MAT-101)</span>
                </div>
                <div>
                    <span class="text-label-md font-label-md text-secondary block mb-base">Exam Type</span>
                    <span class="text-body-lg font-body-lg text-on-surface font-semibold">Midterm 2023</span>
                </div>
            </div>
            <div class="md:border-l md:border-outline-variant md:pl-lg flex gap-md items-center">
                <div>
                    <span class="text-label-md font-label-md text-secondary block mb-base">Total Students</span>
                    <span id="total-students" class="text-headline-md font-headline-md text-primary">0</span>
                </div>
                <div>
                    <span class="text-label-md font-label-md text-secondary block mb-base">Entries Completed</span>
                    <span id="entries-completed" class="text-headline-md font-headline-md text-[#4c56af]">0/0</span>
                </div>
            </div>
        </div>
        <!-- Data Entry Table Card -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col h-[600px] shadow-sm">
            <!-- Table Header Actions -->
            <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low rounded-t-lg">
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary">info</span>
                    <span class="text-body-md font-body-md text-secondary">Press 'Tab' or 'Enter' to move to the next row. Maximum marks: 100.</span>
                </div>
                <div class="flex items-center gap-xs">
                    <label class="text-label-md font-label-md text-on-surface flex items-center gap-xs cursor-pointer">
                        <input class="rounded border-outline-variant text-primary focus:ring-primary h-4 w-4" type="checkbox" />
                        Show absent students
                    </label>
                </div>
            </div>
            <!-- Scrollable Table -->
            <div class="flex-grow overflow-auto table-container">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-surface-container-low border-b border-outline-variant z-10 shadow-sm">
                        <tr>
                            <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant w-24">Roll No.</th>
                            <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant">Student Name</th>
                            <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant w-32 text-center">Total Marks</th>
                            <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant w-48">Obtained Marks</th>
                            <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant w-64">Remarks</th>
                            <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant w-24 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="marks-tbody" class="text-body-md font-body-md text-on-surface divide-y divide-outline-variant">
                        <tr><td colspan="6" class="py-8 text-center text-secondary">Loading students...</td></tr>
                    </tbody>
                </table>
            </div>
            <!-- Footer Actions -->
            <div class="p-md border-t border-outline-variant bg-surface-container-lowest rounded-b-lg flex flex-col sm:flex-row justify-between items-center gap-md">
                <div class="text-body-md font-body-md text-secondary" id="save-status">
                    Last auto-saved: 2 mins ago
                </div>
                <div class="flex gap-sm w-full sm:w-auto">
                    <button class="flex-1 sm:flex-none px-lg py-sm border border-outline-variant rounded bg-surface text-primary text-label-md font-label-md hover:bg-surface-container-low transition-colors">
                        Save Draft
                    </button>
                    <button id="save-marks" class="flex-1 sm:flex-none px-lg py-sm rounded bg-primary text-on-primary text-label-md font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Save &amp; Submit
                    </button>
                </div>
            </div>
        </div>
    </main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('marks-tbody');
        let totalStudents = 0;
        
        fetch(`/api/exams/marks`)
            .then(res => res.json())
            .then(response => {
                if(response.status === 'success') {
                    renderStudents(response.data);
                }
            });

        function renderStudents(students) {
            totalStudents = students.length;
            document.getElementById('total-students').textContent = totalStudents;
            document.getElementById('entries-completed').textContent = `0/${totalStudents}`;

            if(students.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-secondary">No students found.</td></tr>`;
                return;
            }

            let html = '';
            let tabIndex = 1;
            students.forEach((student, index) => {
                const rowClass = index % 2 === 0 ? 'hover:bg-surface-container-lowest' : 'bg-surface hover:bg-surface-container-lowest';
                
                html += `
                <tr class="${rowClass} transition-colors marks-row">
                    <td class="py-sm px-md font-semibold">{student.roll_no || '-'}</td>
                    <td class="py-sm px-md">{student.first_name} ${student.last_name || ''}</td>
                    <td class="py-sm px-md text-center text-secondary">100</td>
                    <td class="py-sm px-md">
                        <input class="mark-input w-full px-sm py-xs border border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary text-body-md outline-none transition-all" max="100" min="0" tabindex="${tabIndex++}" type="number" />
                    </td>
                    <td class="py-sm px-md">
                        <input class="w-full px-sm py-xs border border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary text-body-md outline-none transition-all" placeholder="Optional" tabindex="${tabIndex++}" type="text" />
                    </td>
                    <td class="py-sm px-md text-center">
                        <span class="status-dot inline-block w-2 h-2 rounded-full bg-outline-variant" title="Pending"></span>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;

            attachEventListeners();
        }

        function attachEventListeners() {
            const inputs = document.querySelectorAll('.mark-input');
            inputs.forEach(input => {
                input.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextTab = parseInt(this.getAttribute('tabindex')) + 2;
                        const nextInput = document.querySelector(`[tabindex="${nextTab}"]`);
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }
                });

                input.addEventListener('input', function () {
                    const statusDot = this.closest('tr').querySelector('.status-dot');
                    if (statusDot && this.value !== '') {
                        statusDot.classList.remove('bg-outline-variant');
                        statusDot.classList.add('bg-[#4c56af]'); // Using inline color as stand-in for surface-tint
                    } else if (statusDot && this.value === '') {
                        statusDot.classList.remove('bg-[#4c56af]');
                        statusDot.classList.add('bg-outline-variant');
                    }
                    updateCompletedCount();
                });
            });
        }

        function updateCompletedCount() {
            const completed = document.querySelectorAll('.mark-input').length - Array.from(document.querySelectorAll('.mark-input')).filter(i => i.value === '').length;
            document.getElementById('entries-completed').textContent = `${completed}/${totalStudents}`;
        }

        document.getElementById('save-marks').addEventListener('click', () => {
            fetch(`/api/exams/marks`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: 'save' })
            }).then(res => res.json()).then(res => {
                if (res.status === 'success') {
                    document.getElementById('save-status').textContent = 'Saved successfully!';
                    document.getElementById('save-status').className = 'text-body-md font-body-md text-[#137333]';
                }
            });
        });
    });
</script>
@endsection
