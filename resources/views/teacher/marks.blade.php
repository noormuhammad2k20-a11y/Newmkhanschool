@extends('layouts.app')

@section('title', 'Marks & Grades')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Marks & Grades</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Enter marks and generate grades for your assigned subjects.</p>
        </div>



<!-- Filter Form -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
            <form class="grid grid-cols-1 md:grid-cols-5 gap-md items-end" id="filterForm">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Class</label>
                    <select name="class_id" id="class_id" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Section</label>
                    <select name="section_id" id="section_id" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required {{ !$selectedClass ? 'disabled' : '' }}>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ $selectedSection == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Subject</label>
                    <select name="subject" id="subject" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required {{ !$selectedClass ? 'disabled' : '' }}>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->subject }}" {{ $selectedSubject == $s->subject ? 'selected' : '' }}>{{ $s->subject }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Exam Schedule</label>
                    <select name="exam_schedule_id" id="exam_schedule_id" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required {{ !$selectedSubject ? 'disabled' : '' }}>
                        <option value="">-- Select Exam --</option>
                        @foreach($examSchedules as $exam)
                            <option value="{{ $exam->id }}" {{ $selectedExam == $exam->id ? 'selected' : '' }}>{{ $exam->exam_type }} ({{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-primary text-on-primary rounded-lg py-2 text-label-md font-label-md hover:bg-primary-dark transition-colors">
                        Load Students
                    </button>
                </div>
            </form>
        </div>

        <div id="marksTableContainer"></div>
    </div>
</main>

<script>
    function calculateGrade(input, studentId, maxMarks, passingMarks) {
        let val = parseFloat(input.value);
        if (isNaN(val)) {
            document.getElementById('perc_' + studentId).innerText = '-';
            document.getElementById('grade_' + studentId).innerText = '-';
            document.getElementById('gpa_' + studentId).innerText = '-';
            let statusEl = document.getElementById('status_' + studentId);
            statusEl.innerText = '-';
            statusEl.className = 'text-center font-bold text-secondary';
            return;
        }
        
        if (val > maxMarks) {
            alert('Marks cannot exceed max marks (' + maxMarks + ')');
            input.value = maxMarks;
            val = maxMarks;
        }
        
        let percentage = (val / maxMarks) * 100;
        let isPass = val >= passingMarks;
        let grade = 'F';
        let gpa = 0.0;
        
        if (percentage >= 90) { grade = 'A+'; gpa = 4.0; }
        else if (percentage >= 80) { grade = 'A'; gpa = 4.0; }
        else if (percentage >= 70) { grade = 'B'; gpa = 3.0; }
        else if (percentage >= 60) { grade = 'C'; gpa = 2.0; }
        else if (percentage >= 50) { grade = 'D'; gpa = 1.0; }
        
        document.getElementById('perc_' + studentId).innerText = percentage.toFixed(1) + '%';
        document.getElementById('grade_' + studentId).innerText = grade;
        document.getElementById('gpa_' + studentId).innerText = gpa.toFixed(1);
        
        let statusEl = document.getElementById('status_' + studentId);
        if (isPass) {
             statusEl.innerText = 'Pass';
             statusEl.className = 'text-center font-bold text-green-600';
        } else {
             statusEl.innerText = 'Fail';
             statusEl.className = 'text-center font-bold text-red-600';
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        const subjectSelect = document.getElementById('subject');
        const examSelect = document.getElementById('exam_schedule_id');
        
        classSelect.addEventListener('change', function() {
            const classId = this.value;
            
            sectionSelect.innerHTML = '<option value="">Loading...</option>';
            subjectSelect.innerHTML = '<option value="">Loading...</option>';
            examSelect.innerHTML = '<option value="">-- Select Exam --</option>';
            
            sectionSelect.disabled = true;
            subjectSelect.disabled = true;
            examSelect.disabled = true;
            
            if (!classId) {
                sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
                subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
                return;
            }
            
            // Fetch Sections
            fetch(`{{ route('teacher.api.sections') }}?class_id=${classId}`)
                .then(res => res.json())
                .then(data => {
                    sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
                    data.forEach(s => {
                        sectionSelect.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                    });
                    sectionSelect.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                });
                
            // Fetch Subjects
            fetch(`{{ route('teacher.api.subjects') }}?class_id=${classId}`)
                .then(res => res.json())
                .then(data => {
                    subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
                    data.forEach(s => {
                        subjectSelect.innerHTML += `<option value="${s.subject}">${s.subject}</option>`;
                    });
                    subjectSelect.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
                });
        });
        
        subjectSelect.addEventListener('change', function() {
            const classId = classSelect.value;
            const subject = this.value;
            
            examSelect.innerHTML = '<option value="">Loading...</option>';
            examSelect.disabled = true;
            
            if (!classId || !subject) {
                examSelect.innerHTML = '<option value="">-- Select Exam --</option>';
                return;
            }
            
            fetch(`{{ route('teacher.api.exams') }}?class_id=${classId}&subject=${subject}`)
                .then(res => res.json())
                .then(data => {
                    examSelect.innerHTML = '<option value="">-- Select Exam --</option>';
                    data.forEach(e => {
                        examSelect.innerHTML += `<option value="${e.id}">${e.text}</option>`;
                    });
                    examSelect.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    examSelect.innerHTML = '<option value="">Error loading exams</option>';
                });
        });

        const filterForm = document.getElementById('filterForm');
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = filterForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Loading...';
            
            const formData = new FormData(filterForm);
            
            fetch(`{{ route('teacher.api.marks.students') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'text/html'
                },
                body: formData
            })
            .then(async res => {
                if (!res.ok) {
                    const text = await res.text();
                    throw new Error('Unauthorized or server error');
                }
                return res.text();
            })
            .then(html => {
                document.getElementById('marksTableContainer').innerHTML = html;
                submitBtn.disabled = false;
                submitBtn.innerText = 'Load Students';
            })
            .catch(err => {
                console.error(err);
                document.getElementById('marksTableContainer').innerHTML = '<div class="p-4 mb-4 mt-4 text-sm text-red-800 rounded-lg bg-red-50 text-center border border-red-200">Error loading data or unauthorized access.</div>';
                submitBtn.disabled = false;
                submitBtn.innerText = 'Load Students';
            });
        });
    });
</script>

@endsection
