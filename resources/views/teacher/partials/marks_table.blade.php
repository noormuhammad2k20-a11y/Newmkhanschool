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
    <form action="{{ route('teacher.marks.store') }}" method="POST">
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
                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                        <td class="py-3 px-4 text-secondary">{{ $student->admission_no }}</td>
                        <td class="py-3 px-4 font-medium text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td class="py-3 px-4 text-secondary">{{ $student->father_name }}</td>
                        <td class="py-3 px-4 text-center">
                            <input type="number" step="0.01" name="marks[{{ $student->id }}]" value="{{ $obtained }}" oninput="calculateGrade(this, {{ $student->id }}, {{ $maxMarks }}, {{ $passingMarks }})" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-center focus:border-primary focus:ring-1 focus:ring-primary outline-none font-bold" placeholder="0">
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
