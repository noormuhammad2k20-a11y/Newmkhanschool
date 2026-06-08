@extends('layouts.app')

@section('title', 'Exam Schedule')

@section('content')
            <main class="flex-1 overflow-y-auto p-margin-desktop w-full max-w-[1440px] mx-auto">
                <!-- Page Header & Actions -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-xl">
                    <div>
                        <h2 class="text-headline-xl font-headline-xl text-on-surface mb-xs">Exam Schedule</h2>
                        <p class="text-body-md font-body-md text-on-surface-variant">Manage and monitor upcoming institutional assessments.</p>
                    </div>
                    <button class="bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container px-lg py-sm rounded-lg text-label-md font-label-md transition-colors shadow-sm flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">event_note</span>
                        Schedule New Exam
                    </button>
                </div>
                <!-- Filters Bar -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md mb-lg flex flex-wrap gap-md items-center shadow-sm">
                    <div class="flex items-center gap-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-[20px]">filter_list</span>
                        <span class="text-label-md font-label-md uppercase tracking-wider">Filters:</span>
                    </div>
                    <div class="flex flex-col gap-base min-w-[150px]">
                        <select class="bg-surface-container-lowest border border-outline-variant text-on-surface text-body-md font-body-md rounded-DEFAULT px-md py-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                            <option value="">All Classes</option>
                            <option value="10">Class X (Secondary)</option>
                            <option value="12">Class XII (Higher Sec)</option>
                            <option value="8">Class VIII</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-base min-w-[150px]">
                        <select class="bg-surface-container-lowest border border-outline-variant text-on-surface text-body-md font-body-md rounded-DEFAULT px-md py-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                            <option value="">All Terms</option>
                            <option value="annual">Annual Examination</option>
                            <option value="midterm">Midterm Assessment</option>
                            <option value="unit">Unit Test</option>
                        </select>
                    </div>
                    <div class="flex-1"></div>
                    <div id="exam-count" class="text-label-md font-label-md text-on-surface-variant">
                        Showing 0 scheduled exams
                    </div>
                </div>
                <!-- Data Table Card -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-surface-variant border-b border-outline-variant">
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface uppercase tracking-wider font-semibold">Exam Type</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface uppercase tracking-wider font-semibold">Class</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface uppercase tracking-wider font-semibold">Subject</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface uppercase tracking-wider font-semibold">Date</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface uppercase tracking-wider font-semibold">Time</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface uppercase tracking-wider font-semibold">Status</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface uppercase tracking-wider font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="exams-tbody" class="divide-y divide-outline-variant">
                                <tr><td colspan="7" class="py-8 text-center text-secondary">Loading exams...</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Table Pagination/Footer -->
                    <div class="bg-surface-container border-t border-outline-variant px-md py-sm flex justify-between items-center">
                        <span class="text-label-md font-label-md text-on-surface-variant">Page 1 of 1</span>
                        <div class="flex gap-sm">
                            <button class="p-xs text-outline hover:text-on-surface transition-colors disabled:opacity-50" disabled="">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <button class="p-xs text-on-surface hover:bg-surface-variant rounded-full transition-colors">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('exams-tbody');
        
        fetch(`/api/exams`)
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    renderExams(response.data);
                }
            });

        function renderExams(data) {
            document.getElementById('exam-count').textContent = `Showing ${data.length} scheduled exams`;

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="py-8 text-center text-secondary">No exams scheduled.</td></tr>`;
                return;
            }

            let html = '';
            data.forEach(exam => {
                let statusBadge = '';
                if (exam.status === 'In Progress') {
                    statusBadge = `<span class="inline-flex items-center gap-xs px-sm py-[2px] rounded-full bg-primary/10 text-primary text-label-md font-label-md font-bold border border-primary/20"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span>In Progress</span>`;
                } else if (exam.status === 'Completed') {
                    statusBadge = `<span class="inline-flex items-center gap-xs px-sm py-[2px] rounded-full bg-secondary/10 text-secondary text-label-md font-label-md font-bold border border-secondary/20"><span class="material-symbols-outlined text-[12px]">check_circle</span>Completed</span>`;
                } else {
                    statusBadge = `<span class="inline-flex items-center gap-xs px-sm py-[2px] rounded-full bg-on-surface/10 text-on-surface-variant text-label-md font-label-md font-bold border border-outline-variant"><span class="material-symbols-outlined text-[12px]">schedule</span>Scheduled</span>`;
                }

                html += `
                <tr class="hover:bg-surface-container-low transition-colors even:bg-secondary-fixed/30">
                    <td class="py-md px-md text-body-md font-body-md text-on-surface font-semibold">${exam.type}</td>
                    <td class="py-md px-md text-body-md font-body-md text-on-surface-variant">${exam.class}</td>
                    <td class="py-md px-md text-body-md font-body-md text-on-surface">${exam.subject}</td>
                    <td class="py-md px-md text-body-md font-body-md text-on-surface-variant">${exam.date}</td>
                    <td class="py-md px-md text-body-md font-body-md text-on-surface-variant">${exam.time}</td>
                    <td class="py-md px-md">${statusBadge}</td>
                    <td class="py-md px-md text-right">
                        <button class="text-primary hover:text-primary-container p-xs rounded-full hover:bg-surface-variant transition-colors" title="Edit/View Exam">
                            <span class="material-symbols-outlined text-[20px]">${exam.status === 'Completed' ? 'visibility' : 'edit'}</span>
                        </button>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }
    });
</script>
@endsection
