@if($students && count($students) > 0)
@php
    $maxMarks = $currentExam ? $currentExam->max_marks : 100;
    $passingMarks = $currentExam ? $currentExam->passing_marks : 40;
@endphp
<!-- Marks Entry Form -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden mt-md">
    <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
        <h3 class="text-headline-md font-headline-md text-on-surface">Mark Entry List</h3>
    </div>
    <form action="{{ route('teacher.marks.store') }}" method="POST" id="marksForm">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClass }}">
        <input type="hidden" name="section_id" value="{{ $selectedSection }}">
        <input type="hidden" name="subject" value="{{ $selectedSubject }}">
        <input type="hidden" name="exam_schedule_id" value="{{ $selectedExam }}">
        
        <div class="p-4 bg-surface-container-low border-b border-outline-variant flex items-center gap-4">
            <div class="flex items-center gap-2 bg-surface-bright border border-outline-variant rounded p-2 text-label-md">
                <span class="text-secondary">Max Marks:</span> <span class="font-bold text-on-surface">{{ $maxMarks }}</span>
            </div>
            <div class="flex items-center gap-2 bg-surface-bright border border-outline-variant rounded p-2 text-label-md">
                <span class="text-secondary">Passing Marks:</span> <span class="font-bold text-on-surface">{{ $passingMarks }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                        <th class="py-3 px-4 font-semibold">Roll No</th>
                        <th class="py-3 px-4 font-semibold">Name</th>
                        <th class="py-3 px-4 font-semibold">Father's Name</th>
                        <th class="py-3 px-4 font-semibold text-center w-32">Obtained</th>
                        <th class="py-3 px-4 font-semibold text-center w-24">%</th>
                        <th class="py-3 px-4 font-semibold text-center w-20">Grade</th>
                        <th class="py-3 px-4 font-semibold text-center w-20">GPA</th>
                        <th class="py-3 px-4 font-semibold text-center w-24">Status</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md">
                    @foreach($students as $student)
                    @php
                        $existing = $existingMarks[$student->id] ?? null;
                        $obtained = $existing ? $existing->marks_obtained : '';
                        $perc = $existing ? $existing->percentage : '-';
                        $grade = $existing ? $existing->grade : '-';
                        $gpa = $existing ? $existing->gpa : '-';
                        $status = $existing ? ($existing->is_pass ? 'Pass' : 'Fail') : '-';
                        $statusColor = $existing ? ($existing->is_pass ? 'text-green-600' : 'text-red-600') : 'text-secondary';
                    @endphp
                    <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors {{ $existing ? 'bg-surface-container-lowest/50' : '' }}">
                        <td class="py-3 px-4 text-secondary">{{ $student->admission_no }}</td>
                        <td class="py-3 px-4 font-medium text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td class="py-3 px-4 text-secondary">{{ $student->father_name }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($existing)
                                <div class="flex items-center justify-center gap-2">
                                    <div class="relative flex items-center">
                                        <input type="number" step="0.01" name="marks[{{ $student->id }}]" id="mark_input_{{ $student->id }}" value="{{ $obtained }}" oninput="calculateGrade(this, {{ $student->id }}, {{ $maxMarks }}, {{ $passingMarks }})" class="w-24 bg-surface-container border border-outline-variant rounded p-2 text-center text-on-surface font-bold opacity-70 pointer-events-none pr-6" placeholder="0" readonly>
                                        <span class="material-symbols-outlined text-green-600 text-[14px] absolute right-1 pointer-events-none" id="saved_check_{{ $student->id }}">check_circle</span>
                                    </div>
                                    <button type="button" onclick="enableEdit({{ $student->id }})" class="flex items-center justify-center w-8 h-8 rounded-lg border border-primary text-primary bg-surface-bright hover:bg-primary hover:text-on-primary transition-all shadow-sm" title="Edit Mark">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center justify-center gap-2">
                                    <div class="relative flex items-center">
                                        <input type="number" step="0.01" name="marks[{{ $student->id }}]" id="mark_input_{{ $student->id }}" value="{{ $obtained }}" oninput="calculateGrade(this, {{ $student->id }}, {{ $maxMarks }}, {{ $passingMarks }})" class="w-24 bg-surface-bright border border-outline-variant rounded p-2 text-center focus:border-primary focus:ring-1 focus:ring-primary outline-none font-bold pr-6" placeholder="0">
                                    </div>
                                    <div class="w-8"></div>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center font-medium" id="perc_{{ $student->id }}">{{ $perc !== '-' ? number_format($perc, 1) . '%' : '-' }}</td>
                        <td class="py-3 px-4 text-center font-bold" id="grade_{{ $student->id }}">{{ $grade }}</td>
                        <td class="py-3 px-4 text-center font-medium" id="gpa_{{ $student->id }}">{{ $gpa !== '-' ? number_format($gpa, 1) : '-' }}</td>
                        <td class="py-3 px-4 text-center font-bold {{ $statusColor }}" id="status_{{ $student->id }}">{{ $status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-md bg-surface-bright flex justify-end">
            <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-dark transition-colors">Save Marks</button>
        </div>
    </form>
</div>
@else
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md text-center py-12 mt-md">
    <span class="material-symbols-outlined text-4xl text-secondary mb-2">info</span>
    <p class="text-body-lg text-secondary">No students found for this combination.</p>
</div>
@endif

@if(isset($pendingStudents) && $pendingStudents->count() > 0)
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md mt-md">
    <h4 class="text-label-lg font-label-lg text-secondary flex items-center gap-2">
        <span class="material-symbols-outlined text-xl">pending_actions</span>
        Pending Attendance
    </h4>
    <p class="text-body-md text-secondary mt-1 mb-3">The following students have not had their attendance marked for this exam date ({{ \Carbon\Carbon::parse($currentExam->exam_date)->format('d M Y') }}). They are hidden from the marks entry until their attendance is confirmed.</p>
    <div class="flex flex-wrap gap-2">
        @foreach($pendingStudents as $pending)
            <span class="bg-surface-bright border border-outline-variant text-secondary text-label-sm px-3 py-1 rounded-full">
                {{ $pending->first_name }} {{ $pending->last_name }} ({{ $pending->admission_no }})
            </span>
        @endforeach
    </div>
</div>
@endif

