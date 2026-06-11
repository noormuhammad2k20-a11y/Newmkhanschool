@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Submissions: {{ $assignment->title }}</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Class: {{ $assignment->class_->name ?? 'N/A' }} | Subject: {{ $assignment->subject->name ?? 'N/A' }}</p>
            </div>
            <div class="flex gap-sm">
                <button type="button" onclick="submitBulkGrading()" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary-container hover:text-on-primary transition-colors flex items-center gap-xs shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">auto_awesome</span> Bulk Grade Selected
                </button>
                <a href="{{ route('teacher.assignments') }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high transition-colors">Back</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 text-emerald-800 p-4 rounded-lg text-body-md">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-error-container text-on-error-container p-4 rounded-lg text-body-md">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-error-container text-on-error-container p-4 rounded-lg text-body-md">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-md">
                        <th class="p-md border-b border-outline-variant w-12 text-center">
                            <input type="checkbox" id="selectAll" class="rounded border-outline-variant text-primary focus:ring-primary">
                        </th>
                        <th class="p-md border-b border-outline-variant">Student</th>
                    <th class="p-md border-b border-outline-variant">Submission Date</th>
                    <th class="p-md border-b border-outline-variant">Status</th>
                    <th class="p-md border-b border-outline-variant">Score</th>
                    <th class="p-md border-b border-outline-variant">AI Feedback</th>
                    <th class="p-md border-b border-outline-variant text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-body-md text-on-surface divide-y divide-outline-variant">
                @forelse($submissions as $submission)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="p-md text-center">
                        <input type="checkbox" name="submission_ids[]" value="{{ $submission->id }}" class="submission-checkbox rounded border-outline-variant text-primary focus:ring-primary">
                    </td>
                    <td class="p-md font-medium">
                        {{ $submission->student->first_name ?? '' }} {{ $submission->student->last_name ?? '' }}
                        <div class="text-xs text-secondary">{{ $submission->student->admission_no ?? '' }}</div>
                    </td>
                    <td class="p-md">
                        {{ $submission->created_at->format('d M, Y h:i A') }}
                    </td>
                    <td class="p-md">
                        @if($submission->status == 'graded')
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Graded</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Pending</span>
                        @endif
                    </td>
                    <td class="p-md font-semibold">
                        {{ $submission->marks_obtained ?? '-' }} / 100
                    </td>
                    <td class="p-md max-w-xs">
                        @if($submission->aiGradingResult)
                            <div class="text-xs">
                                <span class="font-semibold text-primary">Score: {{ $submission->aiGradingResult->suggested_score }}/100</span>
                                <p class="truncate text-secondary" title="{{ $submission->aiGradingResult->feedback }}">{{ $submission->aiGradingResult->feedback }}</p>
                            </div>
                        @else
                            <span class="text-xs text-secondary italic">Not graded by AI yet</span>
                        @endif
                    </td>
                    <td class="p-md">
                        <div class="flex items-center justify-end gap-2">
                            @if($submission->file_path)
                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg border border-blue-200 hover:bg-blue-100 text-sm font-medium transition-colors whitespace-nowrap">
                                    View File
                                </a>
                            @endif
                            
                            <button onclick="openGradingModal({{ $submission->id }}, {{ $submission->marks_obtained ?? 'null' }}, '{{ addslashes($submission->teacher_feedback ?? '') }}')" class="px-3 py-1.5 bg-surface-container-high text-on-surface rounded-lg border border-outline-variant hover:bg-surface-container-highest text-sm font-medium transition-colors whitespace-nowrap">
                                Grade
                            </button>

                            <form method="POST" action="{{ route('teacher.submissions.grade-ai', $submission->id) }}" class="m-0 p-0 flex">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-primary text-white rounded-lg border border-primary hover:bg-primary/90 text-sm font-medium inline-flex items-center gap-1 transition-colors whitespace-nowrap" title="Use AI to grade this submission">
                                    <span class="material-symbols-outlined text-[16px]">auto_awesome</span> AI Grade
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-xl text-center text-secondary">
                        No submissions yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</main>

<form id="bulkGradeForm" method="POST" action="{{ route('teacher.submissions.bulk-grade-ai', $assignment->id) }}" class="hidden">
    @csrf
</form>

<!-- Grading Modal -->
<div id="gradingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-surface border border-outline-variant rounded-xl max-w-md w-full shadow-lg">
        <div class="p-md border-b border-outline-variant flex justify-between items-center">
            <h3 class="text-headline-sm font-semibold text-on-surface">Apply Final Grade</h3>
            <button onclick="closeGradingModal()" class="text-secondary hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="gradingForm" method="POST" action="" class="p-md space-y-md">
            @csrf
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Marks Obtained (out of 100)</label>
                <input type="number" name="marks_obtained" id="marksInput" required min="0" max="100" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Teacher Feedback</label>
                <textarea name="teacher_feedback" id="feedbackInput" rows="4" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary"></textarea>
            </div>
            <div class="flex justify-end gap-sm pt-sm">
                <button type="button" onclick="closeGradingModal()" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high">Cancel</button>
                <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant">Save Grade</button>
            </div>
        </form>
    </div>
</div>

<script>
function openGradingModal(submissionId, marks, feedback) {
    document.getElementById('gradingForm').action = '/teacher/submissions/' + submissionId + '/apply-grade';
    document.getElementById('marksInput').value = marks !== null ? marks : '';
    document.getElementById('feedbackInput').value = feedback || '';
    document.getElementById('gradingModal').classList.remove('hidden');
}

function closeGradingModal() {
    document.getElementById('gradingModal').classList.add('hidden');
}
</script>

<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.submission-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function submitBulkGrading() {
        const selected = document.querySelectorAll('.submission-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one submission to grade.');
            return;
        }
        if (confirm(`Are you sure you want to AI grade ${selected.length} submissions?`)) {
            const form = document.getElementById('bulkGradeForm');
            // Clear previous inputs except CSRF
            form.querySelectorAll('input[name="submission_ids[]"]').forEach(el => el.remove());
            
            selected.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'submission_ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
            form.submit();
        }
    }
</script>
@endsection
