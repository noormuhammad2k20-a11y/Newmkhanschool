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
                        Showing {{ count($exams) }} scheduled exams
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
                                @forelse($exams as $exam)
                                <tr class="hover:bg-surface-container-low transition-colors even:bg-secondary-fixed/30">
                                    <td class="py-md px-md text-body-md font-body-md text-on-surface font-semibold">{{ $exam->exam_type }}</td>
                                    <td class="py-md px-md text-body-md font-body-md text-on-surface-variant">{{ $exam->class_ ? $exam->class_->name : $exam->class_name }}</td>
                                    <td class="py-md px-md text-body-md font-body-md text-on-surface">{{ $exam->subjectRelation ? $exam->subjectRelation->name : $exam->subject }}</td>
                                    <td class="py-md px-md text-body-md font-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}</td>
                                    <td class="py-md px-md text-body-md font-body-md text-on-surface-variant">{{ $exam->exam_time }}</td>
                                    <td class="py-md px-md">
                                        @if($exam->status === 'In Progress')
                                            <span class="inline-flex items-center gap-xs px-sm py-[2px] rounded-full bg-primary/10 text-primary text-label-md font-label-md font-bold border border-primary/20"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span>In Progress</span>
                                        @elseif($exam->status === 'Completed')
                                            <span class="inline-flex items-center gap-xs px-sm py-[2px] rounded-full bg-secondary/10 text-secondary text-label-md font-label-md font-bold border border-secondary/20"><span class="material-symbols-outlined text-[12px]">check_circle</span>Completed</span>
                                        @else
                                            <span class="inline-flex items-center gap-xs px-sm py-[2px] rounded-full bg-on-surface/10 text-on-surface-variant text-label-md font-label-md font-bold border border-outline-variant"><span class="material-symbols-outlined text-[12px]">schedule</span>Scheduled</span>
                                        @endif
                                    </td>
                                    <td class="py-md px-md flex justify-end gap-2">
                                        <button onclick="editExam({{ $exam->id }}, '{{ addslashes($exam->exam_type) }}', '{{ $exam->class_id }}', '{{ $exam->subject_id }}', '{{ $exam->exam_date }}', '{{ addslashes($exam->exam_time) }}', '{{ $exam->max_marks }}', '{{ $exam->passing_marks }}', '{{ $exam->status }}')" class="text-primary hover:text-primary-container p-xs rounded-full hover:bg-surface-variant transition-colors" title="Edit Exam">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" class="inline" data-confirm="Are you sure you want to delete this exam?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-error hover:text-error-container p-xs rounded-full hover:bg-surface-variant transition-colors" title="Delete Exam">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
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
        <div class="bg-surface-container-lowest rounded-xl max-w-lg w-full">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">Schedule New Exam</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="text-secondary hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('admin.exams.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-label-md text-on-surface mb-1">Exam Type</label>
                    <input type="text" name="exam_type" required placeholder="e.g. Annual Examination" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Class</label>
                        <select name="class_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="">Select Class</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Subject</label>
                        <select name="subject_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Date</label>
                        <input type="date" name="exam_date" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Time</label>
                        <input type="text" name="exam_time" required placeholder="e.g. 09:00 AM - 12:00 PM" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Max Marks</label>
                        <input type="number" name="max_marks" required value="100" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Passing Marks</label>
                        <input type="number" name="passing_marks" required value="40" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-1">Status</label>
                    <select name="status" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        <option value="Scheduled">Scheduled</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="px-4 py-2 border border-outline-variant rounded text-on-surface hover:bg-surface-container-low transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded hover:bg-primary-dark transition-colors">Schedule</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-xl max-w-lg w-full">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">Edit Exam</h3>
                <button onclick="document.getElementById('editModal').classList.add('hidden'); document.body.style.overflow = '';" class="text-secondary hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-label-md text-on-surface mb-1">Exam Type</label>
                    <input type="text" name="exam_type" id="edit_exam_type" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Class</label>
                        <select name="class_id" id="edit_class_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="">Select Class</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Subject</label>
                        <select name="subject_id" id="edit_subject_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Date</label>
                        <input type="date" name="exam_date" id="edit_exam_date" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Time</label>
                        <input type="text" name="exam_time" id="edit_exam_time" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Max Marks</label>
                        <input type="number" name="max_marks" id="edit_max_marks" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Passing Marks</label>
                        <input type="number" name="passing_marks" id="edit_passing_marks" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                    </div>
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-1">Status</label>
                    <select name="status" id="edit_status" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        <option value="Scheduled">Scheduled</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden'); document.body.style.overflow = '';" class="px-4 py-2 border border-outline-variant rounded text-on-surface hover:bg-surface-container-low transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded hover:bg-primary-dark transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

<script>
    function editExam(id, type, classId, subjectId, date, time, max_marks, passing_marks, status) {
        document.getElementById('editForm').action = `/admin/exams/${id}`;
        document.getElementById('edit_exam_type').value = type;
        document.getElementById('edit_class_id').value = classId;
        document.getElementById('edit_subject_id').value = subjectId;
        document.getElementById('edit_exam_date').value = date;
        document.getElementById('edit_exam_time').value = time;
        document.getElementById('edit_max_marks').value = max_marks;
        document.getElementById('edit_passing_marks').value = passing_marks;
        document.getElementById('edit_status').value = status;
        
        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
</script>
@endsection
