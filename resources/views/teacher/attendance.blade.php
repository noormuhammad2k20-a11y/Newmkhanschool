@extends('layouts.app')

@section('title', 'Student Attendance')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Student Attendance</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Mark daily attendance for your assigned classes.</p>
        </div>



<!-- Filter Form -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
            <form action="{{ route('teacher.attendance') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-md items-end">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Select Class</label>
                    <select name="class_id" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required>
                        <option value="">-- Choose Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Select Date</label>
                    <input type="date" name="date" value="{{ $date }}" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface" required>
                </div>
                <div>
                    <button type="submit" class="w-full bg-primary text-on-primary rounded-lg py-2 text-label-md font-label-md hover:bg-primary-dark transition-colors">
                        Load Students
                    </button>
                </div>
            </form>
        </div>

        @if($selectedClass && count($students) > 0)
        
        @php
            $hasExamToday = \App\Models\ExamSchedule::where('class_id', $selectedClass)->whereDate('exam_date', $date)->exists();
        @endphp
        
        @if($hasExamToday)
        <div class="mb-md bg-orange-50 border border-orange-200 text-orange-800 p-4 rounded-xl flex gap-3 items-start shadow-sm">
            <span class="material-symbols-outlined text-orange-600 mt-0.5">campaign</span>
            <div>
                <h4 class="font-bold text-orange-900 text-label-lg">Physical Exam Scheduled Today</h4>
                <p class="text-body-sm mt-1 opacity-90">An exam is scheduled for this class today. Marking a student Absent will automatically forfeit their exam status to "Absent / Missed".</p>
            </div>
        </div>
        @endif

        <!-- Attendance Form -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                <h3 class="text-headline-md font-headline-md text-on-surface">Mark Attendance for {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</h3>
                <div class="flex gap-2">
                    <button type="button" onclick="markAll('P')" class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded text-label-sm font-medium hover:bg-emerald-200">Mark All Present</button>
                    <button type="button" onclick="markAll('A')" class="bg-red-100 text-red-700 px-3 py-1 rounded text-label-sm font-medium hover:bg-red-200">Mark All Absent</button>
                </div>
            </div>
            <form action="{{ route('teacher.attendance.mark') }}" method="POST">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClass }}">
                <input type="hidden" name="date" value="{{ $date }}">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                <th class="py-3 px-4 font-semibold">Roll No</th>
                                <th class="py-3 px-4 font-semibold">Name</th>
                                <th class="py-3 px-4 font-semibold text-center">Attendance</th>
                            </tr>
                        </thead>
                        <tbody class="text-body-md font-body-md">
                            @foreach($students as $student)
                            @php
                                $status = isset($existingAttendance[$student->id]) ? $existingAttendance[$student->id]->status : 'P';
                            @endphp
                            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3 px-4 text-secondary">{{ $student->admission_no }}</td>
                                <td class="py-3 px-4 font-medium text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex justify-center gap-4">
                                        <label class="flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="P" {{ $status == 'P' ? 'checked' : '' }} class="attendance-radio P">
                                            <span class="text-emerald-600 font-medium">Present</span>
                                        </label>
                                        <label class="flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="A" {{ $status == 'A' ? 'checked' : '' }} class="attendance-radio A">
                                            <span class="text-red-600 font-medium">Absent</span>
                                        </label>
                                        <label class="flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="L" {{ $status == 'L' ? 'checked' : '' }} class="attendance-radio L">
                                            <span class="text-yellow-600 font-medium">Late</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-md bg-surface-bright flex justify-end">
                    <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-dark transition-colors">Save Attendance</button>
                </div>
            </form>
        </div>
        @elseif($selectedClass)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md text-center py-12">
            <span class="material-symbols-outlined text-4xl text-secondary mb-2">group_off</span>
            <p class="text-body-lg text-secondary">No students found in this class.</p>
        </div>
        @endif
    </div>
</main>

<script>
    function markAll(status) {
        document.querySelectorAll('.attendance-radio.' + status).forEach(radio => radio.checked = true);
    }
</script>
@endsection
