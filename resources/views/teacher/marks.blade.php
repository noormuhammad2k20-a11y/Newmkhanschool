@extends('layouts.app')

@section('title', 'Marks & Grades')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Marks & Grades</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Enter marks and generate grades for your assigned subjects.</p>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <!-- Filter Form -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
            <form action="{{ route('teacher.marks') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-md items-end" id="filterForm">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Class</label>
                    <select name="class_id" onchange="document.getElementById('filterForm').submit()" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Subject</label>
                    <select name="subject" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required {{ !$selectedClass ? 'disabled' : '' }}>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->subject }}" {{ $selectedSubject == $s->subject ? 'selected' : '' }}>{{ $s->subject }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Exam Type</label>
                    <select name="exam_type_id" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required>
                        <option value="">-- Select Exam --</option>
                        @foreach($examTypes as $exam)
                            <option value="{{ $exam->id }}" {{ $selectedExam == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
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

        @if($selectedClass && $selectedSubject && $selectedExam && count($students) > 0)
        <!-- Marks Entry Form -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <h3 class="text-headline-md font-headline-md text-on-surface">Mark Entry List</h3>
            </div>
            <form action="{{ route('teacher.marks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClass }}">
                <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                <input type="hidden" name="exam_type_id" value="{{ $selectedExam }}">
                
                <div class="p-4 bg-surface-container-low border-b border-outline-variant flex items-center gap-4">
                    <label class="text-label-md font-label-md text-on-surface">Total Marks:</label>
                    <input type="number" name="total_marks" value="100" class="w-24 bg-surface-bright border border-outline-variant rounded p-1 text-center" required>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                <th class="py-3 px-4 font-semibold">Roll No</th>
                                <th class="py-3 px-4 font-semibold">Name</th>
                                <th class="py-3 px-4 font-semibold text-center w-48">Marks Obtained</th>
                            </tr>
                        </thead>
                        <tbody class="text-body-md font-body-md">
                            @foreach($students as $student)
                            @php
                                $obtained = isset($existingMarks[$student->id]) ? $existingMarks[$student->id]->marks_obtained : '';
                            @endphp
                            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3 px-4 text-secondary">{{ $student->admission_no }}</td>
                                <td class="py-3 px-4 font-medium text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td class="py-3 px-4 text-center">
                                    <input type="number" step="0.01" name="marks[{{ $student->id }}]" value="{{ $obtained }}" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-center focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="0">
                                </td>
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
        @elseif($selectedClass && $selectedSubject && $selectedExam)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md text-center py-12">
            <span class="material-symbols-outlined text-4xl text-secondary mb-2">info</span>
            <p class="text-body-lg text-secondary">No students found for this combination.</p>
        </div>
        @endif
    </div>
</main>
@endsection
