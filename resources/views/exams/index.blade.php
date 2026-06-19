@extends('layouts.app')

@section('title', 'Exam Schedule')

@section('content')
            <main class="flex-1 overflow-y-auto p-margin-desktop w-full max-w-[1440px] mx-auto">
                <!-- Page Header & Actions -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-xl">
                    <div>
                        <h1 class="text-headline-lg font-headline-lg font-semibold text-on-surface mb-xs">Exam Schedule</h1>
                        <p class="text-body-md font-body-md text-on-surface-variant">Manage and monitor upcoming institutional assessments.</p>
                    </div>
                    <button onclick="document.getElementById('createModal').classList.remove('hidden'); document.body.style.overflow = 'hidden';" class="bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container px-lg py-sm rounded-lg text-label-md font-label-md transition-colors shadow-sm flex items-center justify-center gap-xs">
                        <span class="material-symbols-rounded text-[18px]">event_note</span>
                        Schedule New Exam
                    </button>
                </div>
                <!-- Filters Bar -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md mb-lg flex flex-wrap gap-md items-center shadow-sm">
                    <div class="flex items-center gap-sm text-on-surface-variant">
                        <span class="material-symbols-rounded text-[20px]">filter_list</span>
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
                        Showing {{ count($groupedExams) }} scheduled exams
                    </div>
                </div>
                <!-- Data Table Card -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-surface-variant/50 border-b border-outline-variant">
                                    <th class="py-3 px-4 text-left text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-bold">Exam Name</th>
                                    <th class="py-3 px-4 text-left text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-bold">Class</th>
                                    <th class="py-3 px-4 text-left text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-bold">Date & Time</th>
                                    <th class="py-3 px-4 text-left text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-bold">Subjects</th>
                                    <th class="py-3 px-4 text-left text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-bold">Status</th>
                                    <th class="py-3 px-4 text-right text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-bold">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="exams-tbody" class="divide-y divide-outline-variant/50">
                                @forelse($groupedExams as $groupKey => $group)
                                @php 
                                    $firstExam = $group->first(); 
                                    $lastExam = $group->last();
                                    $firstDate = \Carbon\Carbon::parse($firstExam->exam_date);
                                    $lastDate = \Carbon\Carbon::parse($lastExam->exam_date);
                                    $dateDisplay = $firstDate->equalTo($lastDate) ? $firstDate->format('d M Y') : $firstDate->format('d M') . ' - ' . $lastDate->format('d M Y');
                                    
                                    $subjectsData = $group->map(function($sub) {
                                        try {
                                            $startIso = \Carbon\Carbon::parse($sub->exam_date . ' ' . $sub->exam_time)->toIso8601String();
                                            $endIso = \Carbon\Carbon::parse($sub->exam_date . ' ' . $sub->end_time)->toIso8601String();
                                        } catch (\Exception $e) {
                                            $startIso = '';
                                            $endIso = '';
                                        }
                                        return [
                                            'id' => $sub->id,
                                            'start' => $startIso,
                                            'end' => $endIso
                                        ];
                                    })->toJson();
                                @endphp
                                <tr class="hover:bg-surface-container-low transition-colors cursor-pointer group" onclick="toggleSubjects('{{ $groupKey }}')">
                                    <td class="py-3 px-4 text-body-md font-semibold text-on-surface">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                                            {{ $firstExam->exam_type }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-body-md text-on-surface-variant">
                                        <span class="bg-surface-container px-2 py-1 rounded-md border border-outline-variant/50">{{ $firstExam->class_ ? $firstExam->class_->name : $firstExam->class_name }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-body-md text-on-surface-variant">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-on-surface"><span class="material-symbols-rounded text-[14px] align-middle mr-1">calendar_today</span>{{ $dateDisplay }}</span>
                                            <span class="text-body-sm mt-0.5"><span class="material-symbols-rounded text-[14px] align-middle mr-1">schedule</span>{{ $firstExam->exam_time }} - {{ $firstExam->end_time }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <button class="inline-flex items-center gap-1 bg-primary/10 hover:bg-primary/20 text-primary px-3 py-1.5 rounded-md text-label-md font-medium transition-colors border border-primary/20" onclick="event.stopPropagation(); toggleSubjects('{{ $groupKey }}')">
                                            View ({{ $group->count() }}) 
                                            <span id="icon-{{ $groupKey }}" class="material-symbols-rounded text-[18px] transition-transform duration-200">expand_more</span>
                                        </button>
                                    </td>
                                    <td class="py-3 px-4 exam-status-cell" data-subjects="{{ $subjectsData }}" data-current-status="">
                                        <div class="status-badge-container">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-variant text-on-surface-variant text-label-sm font-bold border border-outline-variant"><span class="material-symbols-rounded text-[14px]">hourglass_empty</span>Calculating...</span>
                                        </div>
                                        <div class="exam-progress-text mt-1.5 text-[11px] text-on-surface-variant font-medium">
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-right" onclick="event.stopPropagation();">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="editExam({{ $firstExam->id }}, '{{ addslashes($firstExam->exam_type) }}', '{{ $firstExam->class_id }}')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-surface-container text-primary hover:bg-primary hover:text-on-primary rounded-md transition-colors border border-outline-variant/50 text-label-md font-medium" title="Edit Exam">
                                                <span class="material-symbols-rounded text-[16px]">edit</span> Edit
                                            </button>
                                            <form action="{{ route('admin.exams.destroy', $firstExam->id) }}" method="POST" class="inline" data-confirm="Are you sure you want to delete this entire exam event?">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="delete_group" value="1">
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-surface-container text-error hover:bg-error hover:text-on-error rounded-md transition-colors border border-outline-variant/50 text-label-md font-medium" title="Delete Exam">
                                                    <span class="material-symbols-rounded text-[16px]">delete</span> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Expandable Subjects Row -->
                                <tr id="subjects-{{ $groupKey }}" class="hidden bg-surface-container-lowest border-b border-outline-variant">
                                    <td colspan="6" class="p-0">
                                        <div class="px-8 py-4 bg-surface-variant/20 border-l-4 border-primary">
                                            <h4 class="text-label-lg font-bold text-on-surface mb-3 flex items-center gap-2">
                                                <span class="material-symbols-rounded text-[18px] text-primary">book</span> 
                                                Subject Details for {{ $firstExam->exam_type }}
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-4">
                                                @foreach($group as $sub)
                                                <div id="subject-card-{{ $sub->id }}" class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm hover:shadow-md hover:border-primary/40 transition-all flex flex-col h-full relative group/card">
                                                    
                                                    <!-- Header -->
                                                    <div class="flex justify-between items-start mb-5">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                                                <span class="material-symbols-rounded text-[24px]">auto_stories</span>
                                                            </div>
                                                            <div>
                                                                <h5 class="font-bold text-on-surface text-title-md leading-tight">{{ $sub->subjectRelation ? $sub->subjectRelation->name : $sub->subject }}</h5>
                                                                <div class="subject-status-badge mt-1.5 h-[20px]">
                                                                    <span class="text-label-sm text-on-surface-variant">Calculating...</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <form action="{{ route('admin.exams.destroy', $sub->id) }}" method="POST" class="inline" data-confirm="Are you sure you want to remove this subject from the exam?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-on-surface-variant hover:text-error hover:bg-error/10 w-9 h-9 flex items-center justify-center rounded-full transition-colors opacity-0 group-hover/card:opacity-100 focus:opacity-100" title="Remove Subject">
                                                                <span class="material-symbols-rounded text-[20px]">delete</span>
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <!-- Details Grid -->
                                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                                        <div class="flex flex-col gap-1.5 p-3 rounded-lg bg-surface-variant/30 border border-outline-variant/30">
                                                            <div class="flex items-center gap-1.5 text-on-surface-variant">
                                                                <span class="material-symbols-rounded text-[16px]">calendar_month</span>
                                                                <span class="text-label-sm uppercase tracking-wider font-bold">Date</span>
                                                            </div>
                                                            <span class="text-body-md font-semibold text-on-surface">{{ \Carbon\Carbon::parse($sub->exam_date)->format('d M, Y') }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-1.5 p-3 rounded-lg bg-surface-variant/30 border border-outline-variant/30">
                                                            <div class="flex items-center gap-1.5 text-on-surface-variant">
                                                                <span class="material-symbols-rounded text-[16px]">schedule</span>
                                                                <span class="text-label-sm uppercase tracking-wider font-bold">Time</span>
                                                            </div>
                                                            <span class="text-body-md font-semibold text-on-surface">{{ $sub->exam_time }} - {{ $sub->end_time }}</span>
                                                        </div>
                                                    </div>

                                                    <!-- Footer / Marks -->
                                                    <div class="mt-auto pt-4 border-t border-outline-variant/60 flex justify-between items-center">
                                                        <div class="flex items-center gap-2">
                                                            <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                                                            <span class="text-label-md text-on-surface-variant font-medium">Passing: <strong class="text-on-surface text-body-lg ml-1">{{ $sub->passing_marks }}</strong></span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                                                            <span class="text-label-md text-on-surface-variant font-medium">Total: <strong class="text-on-surface text-body-lg ml-1">{{ $sub->max_marks }}</strong></span>
                                                        </div>
                                                    </div>

                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="py-8 text-center text-secondary">No exams scheduled.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center sticky top-0 bg-surface-container-lowest z-10">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">Schedule New Exam Event</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="text-secondary hover:text-on-surface">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
            <form id="createExamForm" action="{{ route('admin.exams.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <!-- Step 1: Event Details -->
                <div id="createStep1">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">Exam Type</label>
                            <input type="text" name="exam_type" id="create_exam_type" required placeholder="e.g. Final Term" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        </div>
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">Class</label>
                            <select name="class_id" id="create_class_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                                <option value="">Select Class</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">Event Start Date</label>
                            <input type="date" id="event_start_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        </div>
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">Event End Date</label>
                            <input type="date" id="event_end_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="px-4 py-2 border border-outline-variant rounded text-on-surface hover:bg-surface-container-low transition-colors">Cancel</button>
                        <button type="button" id="btnNextStep" class="px-4 py-2 bg-primary text-on-primary rounded hover:bg-primary-dark transition-colors">Next: Build Timetable</button>
                    </div>
                </div>

                <!-- Step 2: Subject Schedule Builder -->
                <div id="createStep2" class="hidden">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h4 class="text-title-md font-bold text-on-surface" id="builder_title">Subject Timetable</h4>
                            <p class="text-body-sm text-on-surface-variant" id="builder_subtitle">Assign dates and times for each subject.</p>
                        </div>
                        <button type="button" id="btnGenerateTimetable" class="inline-flex items-center gap-1 px-3 py-1.5 bg-secondary/10 text-secondary hover:bg-secondary hover:text-on-secondary rounded-md transition-colors text-label-md font-medium border border-secondary/20">
                            <span class="material-symbols-rounded text-[16px]">auto_awesome</span> Generate Timetable
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low text-on-surface text-label-sm border-b border-outline-variant">
                                    <th class="py-2 px-3">Subject</th>
                                    <th class="py-2 px-3">Exam Date</th>
                                    <th class="py-2 px-3">Start Time</th>
                                    <th class="py-2 px-3">End Time</th>
                                    <th class="py-2 px-3 w-20">Max</th>
                                    <th class="py-2 px-3 w-20">Pass</th>
                                </tr>
                            </thead>
                            <tbody id="subjects_table_body">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-6 flex justify-between items-center">
                        <button type="button" id="btnPrevStep" class="px-4 py-2 border border-outline-variant rounded text-on-surface hover:bg-surface-container-low transition-colors">Back</button>
                        <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded hover:bg-primary-dark transition-colors font-medium">Save Exam Event</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center sticky top-0 bg-surface-container-lowest z-10">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">Edit Exam Timetable</h3>
                <button onclick="document.getElementById('editModal').classList.add('hidden'); document.body.style.overflow = '';" class="text-secondary hover:text-on-surface">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Exam Type</label>
                        <input type="text" name="exam_type" id="edit_exam_type" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Class</label>
                        <select name="class_id" id="edit_class_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface pointer-events-none bg-surface-container-lowest text-on-surface-variant" tabindex="-1">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface text-label-sm border-b border-outline-variant">
                                <th class="py-2 px-3">Subject</th>
                                <th class="py-2 px-3">Exam Date</th>
                                <th class="py-2 px-3">Start Time</th>
                                <th class="py-2 px-3">End Time</th>
                                <th class="py-2 px-3 w-20">Max</th>
                                <th class="py-2 px-3 w-20">Pass</th>
                            </tr>
                        </thead>
                        <tbody id="edit_subjects_table_body">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>

                <div class="pt-6 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden'); document.body.style.overflow = '';" class="px-4 py-2 border border-outline-variant rounded text-on-surface hover:bg-surface-container-low transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded hover:bg-primary-dark transition-colors font-medium">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

<script>
    async function editExam(id, type, classId) {
        document.getElementById('editForm').action = `/admin/exams/${id}`;
        document.getElementById('edit_exam_type').value = type;
        document.getElementById('edit_class_id').value = classId;
        
        try {
            const response = await fetch(`/admin/exams/classes/${classId}/type/${encodeURIComponent(type)}`);
            const schedules = await response.json();
            
            const tbody = document.getElementById('edit_subjects_table_body');
            tbody.innerHTML = '';
            
            schedules.forEach((schedule, index) => {
                const tr = document.createElement('tr');
                tr.className = 'border-b border-outline-variant/50 hover:bg-surface-container-low transition-colors';
                tr.innerHTML = `
                    <td class="py-2 px-3 text-body-md text-on-surface">
                        <input type="hidden" name="subjects[${index}][subject_id]" value="${schedule.subject_id}">
                        ${schedule.subject_name}
                    </td>
                    <td class="py-2 px-3">
                        <input type="date" name="subjects[${index}][exam_date]" value="${schedule.exam_date}" min="${new Date().toISOString().split('T')[0]}" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                    <td class="py-2 px-3">
                        <input type="time" name="subjects[${index}][exam_time]" value="${schedule.exam_time}" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                    <td class="py-2 px-3">
                        <input type="time" name="subjects[${index}][end_time]" value="${schedule.end_time}" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                    <td class="py-2 px-3">
                        <input type="number" name="subjects[${index}][max_marks]" value="${schedule.max_marks}" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                    <td class="py-2 px-3">
                        <input type="number" name="subjects[${index}][passing_marks]" value="${schedule.passing_marks}" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } catch (err) {
            console.error('Failed to load event schedules', err);
            window.UI?.showToast('Failed to load schedules.', 'error') || alert('Failed to load schedules.');
        }
    }

    function toggleSubjects(groupId) {
        const row = document.getElementById(`subjects-${groupId}`);
        const icon = document.getElementById(`icon-${groupId}`);
        
        if (row.classList.contains('hidden')) {
            row.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            row.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    function updateExamStatuses() {
        const now = new Date();
        document.querySelectorAll('td.exam-status-cell').forEach(cell => {
            const badgeContainer = cell.querySelector('.status-badge-container');
            const progressContainer = cell.querySelector('.exam-progress-text');

            const setInvalidStatus = (msg) => {
                const currentBadge = cell.getAttribute('data-current-status');
                if (currentBadge !== 'Invalid') {
                    cell.setAttribute('data-current-status', 'Invalid');
                    if (badgeContainer) {
                        badgeContainer.innerHTML = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-error/10 text-error text-label-sm font-bold border border-error/20"><span class="material-symbols-rounded text-[14px]">error</span>${msg}</span>`;
                    }
                }
                if (progressContainer) progressContainer.innerHTML = '';
            };

            const subjectsDataStr = cell.getAttribute('data-subjects');
            if (!subjectsDataStr || subjectsDataStr.trim() === '') {
                return setInvalidStatus('Missing Exam Data');
            }

            let subjects;
            try {
                subjects = JSON.parse(subjectsDataStr);
            } catch(e) { 
                return setInvalidStatus('Invalid Exam Data'); 
            }

            if (!subjects || subjects.length === 0) {
                return setInvalidStatus('No Subjects');
            }

            let completedCount = 0;
            let runningCount = 0;
            let pendingCount = 0;
            let invalidSubjectsCount = 0;

            subjects.forEach(sub => {
                if (!sub.start || !sub.end || sub.start === '' || sub.end === '') {
                    invalidSubjectsCount++;
                    // Update individual subject badge in the drawer
                    const subjectCard = document.getElementById(`subject-card-${sub.id}`);
                    if (subjectCard) {
                        const sBadge = subjectCard.querySelector('.subject-status-badge');
                        if (sBadge && sBadge.getAttribute('data-current-badge') !== 'Invalid') {
                            sBadge.setAttribute('data-current-badge', 'Invalid');
                            sBadge.innerHTML = '<span class="inline-flex items-center gap-1 text-error text-label-sm font-bold"><span class="material-symbols-rounded text-[14px]">error</span> Invalid Schedule</span>';
                        }
                    }
                    return;
                }
                
                const startTime = new Date(sub.start);
                const endTime = new Date(sub.end);
                
                if (isNaN(startTime.getTime()) || isNaN(endTime.getTime())) {
                    invalidSubjectsCount++;
                    const subjectCard = document.getElementById(`subject-card-${sub.id}`);
                    if (subjectCard) {
                        const sBadge = subjectCard.querySelector('.subject-status-badge');
                        if (sBadge && sBadge.getAttribute('data-current-badge') !== 'Invalid') {
                            sBadge.setAttribute('data-current-badge', 'Invalid');
                            sBadge.innerHTML = '<span class="inline-flex items-center gap-1 text-error text-label-sm font-bold"><span class="material-symbols-rounded text-[14px]">error</span> Invalid Schedule</span>';
                        }
                    }
                    return;
                }

                let subStatus = 'Pending';
                if (now > endTime) {
                    subStatus = 'Completed';
                    completedCount++;
                } else if (now >= startTime && now <= endTime) {
                    subStatus = 'Running';
                    runningCount++;
                } else {
                    pendingCount++;
                }

                // Update individual subject badge in the drawer
                const subjectCard = document.getElementById(`subject-card-${sub.id}`);
                if (subjectCard) {
                    const badgeContainer = subjectCard.querySelector('.subject-status-badge');
                    if (badgeContainer) {
                        const currentBadge = badgeContainer.getAttribute('data-current-badge');
                        if (currentBadge !== subStatus) {
                            badgeContainer.setAttribute('data-current-badge', subStatus);
                            if (subStatus === 'Completed') {
                                badgeContainer.innerHTML = '<span class="inline-flex items-center gap-1 text-secondary text-label-sm font-bold"><span class="material-symbols-rounded text-[14px]">check</span> Completed</span>';
                            } else if (subStatus === 'Running') {
                                badgeContainer.innerHTML = '<span class="inline-flex items-center gap-1 text-primary text-label-sm font-bold"><span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span> In Progress</span>';
                            } else {
                                badgeContainer.innerHTML = '<span class="inline-flex items-center gap-1 text-on-surface-variant text-label-sm font-bold"><span class="material-symbols-rounded text-[14px]">hourglass_empty</span> Upcoming</span>';
                            }
                        }
                    }
                }
            });

            if (invalidSubjectsCount > 0) {
                return setInvalidStatus('Invalid Schedule');
            }

            const total = subjects.length;
            let groupStatus = 'Scheduled';

            if (completedCount === total && total > 0) {
                groupStatus = 'Completed';
            } else if (runningCount > 0 || completedCount > 0) {
                groupStatus = 'In Progress';
            }

            // Update main row group status badge
            const currentBadge = cell.getAttribute('data-current-status');
            if (currentBadge !== groupStatus) {
                cell.setAttribute('data-current-status', groupStatus);
                if (badgeContainer) {
                    if (groupStatus === 'In Progress') {
                        badgeContainer.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary/10 text-primary text-label-sm font-bold border border-primary/20"><span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>In Progress</span>';
                    } else if (groupStatus === 'Completed') {
                        badgeContainer.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-secondary/10 text-secondary text-label-sm font-bold border border-secondary/20"><span class="material-symbols-rounded text-[14px]">check_circle</span>Completed</span>';
                    } else {
                        badgeContainer.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-variant text-on-surface-variant text-label-sm font-bold border border-outline-variant"><span class="material-symbols-rounded text-[14px]">schedule</span>Scheduled</span>';
                    }
                }
            }

            // Update group progress text
            if (progressContainer) {
                const progressHtml = `${completedCount} / ${total} Subjects Completed`;
                if (progressContainer.innerHTML !== progressHtml) {
                    progressContainer.innerHTML = progressHtml;
                }
            }
        });
    }

    // Update statuses every 1 second for instant transitions
    setInterval(updateExamStatuses, 1000);
    
    // Run once on load
    updateExamStatuses();
    
    let currentSubjects = [];

    // Step 1 to Step 2
    document.getElementById('btnNextStep')?.addEventListener('click', async function() {
        const classId = document.getElementById('create_class_id').value;
        const examType = document.getElementById('create_exam_type').value;
        const startDate = document.getElementById('event_start_date').value;
        const endDate = document.getElementById('event_end_date').value;
        
        if (!classId || !examType || !startDate || !endDate) {
            window.UI?.showToast('Please fill all event details.', 'error') || alert('Please fill all event details.');
            return;
        }
        if (new Date(endDate) < new Date(startDate)) {
            window.UI?.showToast('End date cannot be before start date.', 'error') || alert('End date cannot be before start date.');
            return;
        }

        try {
            const response = await fetch(`/admin/exams/classes/${classId}/subjects`);
            currentSubjects = await response.json();
            
            if (currentSubjects.length === 0) {
                window.UI?.showToast('No subjects assigned to this class.', 'error') || alert('No subjects assigned to this class.');
                return;
            }

            const tbody = document.getElementById('subjects_table_body');
            tbody.innerHTML = '';
            
            currentSubjects.forEach((subject, index) => {
                const tr = document.createElement('tr');
                tr.className = 'border-b border-outline-variant/50 hover:bg-surface-container-low transition-colors';
                tr.innerHTML = `
                    <td class="py-2 px-3 text-body-md text-on-surface">
                        <input type="hidden" name="subjects[${index}][subject_id]" value="${subject.id}">
                        ${subject.name}
                    </td>
                    <td class="py-2 px-3">
                        <input type="date" name="subjects[${index}][exam_date]" min="${new Date().toISOString().split('T')[0]}" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                    <td class="py-2 px-3">
                        <input type="time" name="subjects[${index}][exam_time]" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                    <td class="py-2 px-3">
                        <input type="time" name="subjects[${index}][end_time]" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                    <td class="py-2 px-3">
                        <input type="number" name="subjects[${index}][max_marks]" value="100" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                    <td class="py-2 px-3">
                        <input type="number" name="subjects[${index}][passing_marks]" value="40" required class="w-full bg-surface-bright border border-outline-variant rounded p-1.5 text-body-sm text-on-surface">
                    </td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('createStep1').classList.add('hidden');
            document.getElementById('createStep2').classList.remove('hidden');
            
        } catch (err) {
            console.error('Failed to load subjects', err);
            window.UI?.showToast('Failed to fetch subjects.', 'error') || alert('Failed to fetch subjects.');
        }
    });

    // Step 2 to Step 1 (Back)
    document.getElementById('btnPrevStep')?.addEventListener('click', function() {
        document.getElementById('createStep2').classList.add('hidden');
        document.getElementById('createStep1').classList.remove('hidden');
    });

    // Generate Timetable Button
    document.getElementById('btnGenerateTimetable')?.addEventListener('click', function() {
        const startDate = new Date(document.getElementById('event_start_date').value);
        const endDate = new Date(document.getElementById('event_end_date').value);
        
        let currentDate = new Date(startDate);
        const tbody = document.getElementById('subjects_table_body');
        const rows = tbody.querySelectorAll('tr');
        
        rows.forEach((row, index) => {
            // Skip Sundays (0)
            while (currentDate.getDay() === 0) {
                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            if (currentDate > endDate) {
                // if we run out of days, just stack them on the last day
                currentDate = new Date(endDate);
            }

            const dateStr = currentDate.toISOString().split('T')[0];
            row.querySelector(`input[name="subjects[${index}][exam_date]"]`).value = dateStr;
            row.querySelector(`input[name="subjects[${index}][exam_time]"]`).value = "09:00";
            row.querySelector(`input[name="subjects[${index}][end_time]"]`).value = "12:00";
            
            currentDate.setDate(currentDate.getDate() + 1);
        });
    });

</script>
@endsection
