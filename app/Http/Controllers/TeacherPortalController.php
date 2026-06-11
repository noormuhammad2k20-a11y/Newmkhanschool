<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Assignment;
use App\Models\Announcement;
use App\Models\Message;
use Carbon\Carbon;

class TeacherPortalController extends Controller
{
    use \App\Traits\TeacherScoped;
    use \App\Http\Traits\AjaxResponseTrait;
    private function getAssignedSubjects($teacher)
    {
        if (!$teacher) return collect();
        return DB::table('teacher_assignments')
            ->where('teacher_id', $teacher->id)
            ->whereNotNull('subject_id')
            ->join('subjects', 'teacher_assignments.subject_id', '=', 'subjects.id')
            ->select('subjects.name as subject')
            ->distinct()
            ->get();
    }

    // 1. Dashboard
    public function dashboard() { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        $classesCount = $classIds->count();
        $subjectsCount = $this->getAssignedSubjects($teacher)->count();
        
        $totalStudents = DB::table('students')->whereIn('current_class_id', $classIds)->count();
        $pendingAssignments = Assignment::where('teacher_id', $teacher?->id)->where('type', 'assignment')->count();
        
        $today = Carbon::now()->format('l');
        $todaysTimetable = DB::table('timetables')
            ->where('teacher', 'like', '%'.$teacher?->full_name.'%')
            ->where('day_of_week', $today)
            ->get();
            
        $announcements = Announcement::where('status', 'published')
            ->whereIn('role_visibility', ['all', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $aiGradedCount = \App\Models\AssignmentSubmission::where('status', 'graded')->whereHas('assignment', function($q) use($teacher) { $q->where('teacher_id', $teacher?->id); })->count();
        $seatingPlansCount = class_exists(\App\Models\SeatingPlan::class) ? \App\Models\SeatingPlan::where('teacher_id', $teacher?->id)->count() : 0;
        $pendingSubmissionsCount = \App\Models\AssignmentSubmission::where('status', 'submitted')->whereHas('assignment', function($q) use($teacher) { $q->where('teacher_id', $teacher?->id); })->count();

        return view('teacher.dashboard', compact('classesCount', 'subjectsCount', 'totalStudents', 'pendingAssignments', 'todaysTimetable', 'announcements', 'aiGradedCount', 'seatingPlansCount', 'pendingSubmissionsCount')); 
    }

    // 2. Attendance
    public function attendance(Request $request) { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->orderBy('name')->get();
        
        $selectedClass = $request->get('class_id');
        $date = $request->get('date', date('Y-m-d'));
        
        $students = collect();
        $existingAttendance = collect();
        
        if ($selectedClass && $classIds->contains($selectedClass)) {
            $students = DB::table('students')->where('current_class_id', $selectedClass)->get();
            $existingAttendance = DB::table('student_attendances')
                ->where('date', $date)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        return view('teacher.attendance', compact('classes', 'students', 'existingAttendance', 'selectedClass', 'date')); 
    }

    public function markAttendance(Request $request) {
        $request->validate([
            'class_id' => 'required',
            'date' => 'required|date',
            'attendance' => 'required|array'
        ]);
        
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        
        if (!$classIds->contains($request->class_id)) {
            return $this->ajaxError($request, 'Unauthorized access to this class.');
        }

        foreach ($request->attendance as $studentId => $status) {
            DB::table('student_attendances')->updateOrInsert(
                ['student_id' => $studentId, 'date' => $request->date],
                ['status' => $status, 'marked_by' => auth()->id(), 'academic_year_id' => 1]
            );
        }

        return $this->ajaxSuccess($request, 'Attendance marked successfully.');
    }

    // 3. Classes
    public function classes() { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->orderBy('name')->get();
        
        foreach ($classes as $class) {
            $class->student_count = DB::table('students')->where('current_class_id', $class->id)->count();
            $class->subjects = DB::table('teacher_assignments')
                ->where('teacher_assignments.class_id', $class->id)
                ->where('teacher_assignments.teacher_id', $teacher?->id)
                ->join('subjects', 'teacher_assignments.subject_id', '=', 'subjects.id')
                ->pluck('subjects.name')
                ->unique()
                ->implode(', ');
        }
        
        return view('teacher.classes', compact('classes')); 
    }

    // 4. Subjects
    public function subjects() { 
        $teacher = $this->getTeacher();
        $subjects = collect();
        if ($teacher) {
            $subjects = DB::table('teacher_assignments')
                ->where('teacher_id', $teacher->id)
                ->join('classes', 'teacher_assignments.class_id', '=', 'classes.id')
                ->join('subjects', 'teacher_assignments.subject_id', '=', 'subjects.id')
                ->select('subjects.name as subject', 'classes.name as class_name', 'classes.id as class_id')
                ->distinct()
                ->get();
        }
        
        return view('teacher.subjects', compact('subjects')); 
    }

    // 5. Students
    public function students(Request $request) { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->orderBy('name')->get();
        
        $selectedClass = $request->get('class_id');
        $search = $request->get('search');
        
        $query = DB::table('students')->whereIn('current_class_id', $classIds);
        
        if ($selectedClass) {
            $query->where('current_class_id', $selectedClass);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%");
            });
        }
        
        $students = $query->get();
        
        return view('teacher.students', compact('students', 'classes', 'selectedClass', 'search')); 
    }

    // 6. Marks & Grades
    public function marks(Request $request) { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->orderBy('name')->get();
        
        $selectedClass = null;
        $selectedSection = null;
        $selectedSubject = null;
        $selectedExam = null;
        
        $sections = collect();
        $subjects = collect();
        $examSchedules = collect();
        $students = collect();
        $existingMarks = collect();
        
        return view('teacher.marks', compact('classes', 'sections', 'subjects', 'examSchedules', 'selectedClass', 'selectedSection', 'selectedSubject', 'selectedExam', 'students', 'existingMarks')); 
    }

    public function getStudentsForMarks(Request $request) {
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        
        $selectedClass = $request->post('class_id');
        $selectedSection = $request->post('section_id');
        $selectedSubject = $request->post('subject');
        $selectedExam = $request->post('exam_schedule_id');
        
        if (!$classIds->contains($selectedClass)) {
            abort(403, 'Unauthorized access to this class.');
        }

        $isAssignedSubject = DB::table('teacher_assignments')
            ->where('teacher_assignments.class_id', $selectedClass)
            ->where('teacher_assignments.teacher_id', $teacher?->id)
            ->join('subjects', 'teacher_assignments.subject_id', '=', 'subjects.id')
            ->where('subjects.name', $selectedSubject)
            ->exists();

        if (!$isAssignedSubject) {
            abort(403, 'Unauthorized access to this subject.');
        }

        $subjectId = DB::table('subjects')->where('name', $selectedSubject)->value('id');
        
        $examValid = \App\Models\ExamSchedule::where('id', $selectedExam)
            ->where('class_id', $selectedClass)
            ->where('subject_id', $subjectId)
            ->exists();
            
        if (!$examValid) {
            abort(403, 'Unauthorized access to this exam schedule.');
        }

        // Validate section belongs to class
        $sectionValid = DB::table('sections')
            ->where('id', $selectedSection)
            ->where('class_id', $selectedClass)
            ->exists();
            
        if (!$sectionValid) {
            abort(403, 'Unauthorized access to this section.');
        }

        $students = DB::table('students')
            ->where('current_class_id', $selectedClass)
            ->where('current_section_id', $selectedSection)
            ->get();
            
        $existingMarks = DB::table('marks')
            ->where('exam_schedule_id', $selectedExam)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');
            
        $currentExam = \App\Models\ExamSchedule::find($selectedExam);

        return view('teacher.partials.marks_table', compact('students', 'existingMarks', 'currentExam', 'selectedClass', 'selectedSection', 'selectedSubject', 'selectedExam'))->render();
    }

    public function storeMarks(Request $request) {
        // Validation and RBAC check
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        if (!$classIds->contains($request->class_id)) {
            return $this->ajaxError($request, 'Unauthorized access to this class.');
        }
        
        // Resolve subject_id
        $isAssignedSubject = DB::table('teacher_assignments')
            ->where('teacher_assignments.class_id', $request->class_id)
            ->where('teacher_assignments.teacher_id', $teacher?->id)
            ->join('subjects', 'teacher_assignments.subject_id', '=', 'subjects.id')
            ->where('subjects.name', $request->subject)
            ->exists();

        if (!$isAssignedSubject) {
            return $this->ajaxError($request, 'Unauthorized access to this subject.');
        }

        $subjectId = DB::table('subjects')->where('name', $request->subject)->value('id');
        if(!$subjectId) return $this->ajaxError($request, 'Subject not found.');

        $examSchedule = \App\Models\ExamSchedule::find($request->exam_schedule_id);
        if(!$examSchedule || $examSchedule->class_id != $request->class_id || $examSchedule->subject_id != $subjectId) {
            return $this->ajaxError($request, 'Unauthorized access to this exam schedule.');
        }

        // Validate section
        $sectionValid = DB::table('sections')
            ->where('id', $request->section_id)
            ->where('class_id', $request->class_id)
            ->exists();
            
        if (!$sectionValid) {
            return $this->ajaxError($request, 'Unauthorized access to this section.');
        }

        // Fetch valid student IDs for this class and section
        $validStudentIds = DB::table('students')
            ->where('current_class_id', $request->class_id)
            ->where('current_section_id', $request->section_id)
            ->pluck('id')
            ->toArray();

        $maxMarks = $examSchedule->max_marks ?? 100;
        $passingMarks = $examSchedule->passing_marks ?? 40;

        foreach ($request->marks as $studentId => $mark) {
            if (!in_array($studentId, $validStudentIds)) {
                continue; // Skip invalid student IDs
            }
            
            if($mark !== null) {
                // Ensure mark is not greater than max marks
                if ($mark > $maxMarks) $mark = $maxMarks;
                
                $percentage = ($mark / $maxMarks) * 100;
                $isPass = $mark >= $passingMarks;
                
                $grade = 'F';
                $gpa = 0.0;
                if ($percentage >= 90) { $grade = 'A+'; $gpa = 4.0; }
                elseif ($percentage >= 80) { $grade = 'A'; $gpa = 4.0; }
                elseif ($percentage >= 70) { $grade = 'B'; $gpa = 3.0; }
                elseif ($percentage >= 60) { $grade = 'C'; $gpa = 2.0; }
                elseif ($percentage >= 50) { $grade = 'D'; $gpa = 1.0; }

                DB::table('marks')->updateOrInsert(
                    ['student_id' => $studentId, 'subject_id' => $subjectId, 'exam_schedule_id' => $request->exam_schedule_id, 'academic_year_id' => 1],
                    [
                        'exam_type_id' => null, // Fallback for old schema
                        'marks_obtained' => $mark, 
                        'total_marks' => $maxMarks, 
                        'percentage' => $percentage,
                        'grade' => $grade,
                        'gpa' => $gpa,
                        'is_pass' => $isPass,
                        'created_at' => now()
                    ]
                );
            }
        }
        
        \App\Observers\AuditObserver::log('marks_entry', 'Mark', 0, "Marks entered for class {$request->class_id} subject {$subjectId}");
        
        return $this->ajaxSuccess($request, 'Marks saved successfully.');
    }

    // 7. Assignments
    public function assignments() { 
        $teacher = $this->getTeacher();
        $assignments = [];
        if ($teacher) {
            $assignments = Assignment::with(['class_', 'subject'])->where('teacher_id', $teacher->id)->where('type', 'assignment')->orderBy('due_date', 'asc')->get();
        }
        
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->get();
        $subjects = DB::table('subjects')->get(); // Ideally filter by assigned
        return view('teacher.assignments', compact('assignments', 'classes', 'subjects')); 
    }

    public function storeAssignment(Request $request) {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required',
            'due_date' => 'required|date',
            'type' => 'required|in:assignment,homework',
        ]);

        $teacher = $this->getTeacher();
        if($teacher) {
            $classIds = $this->getAssignedClassIds($teacher);
            if (!$classIds->contains($request->class_id)) {
                return $this->ajaxError($request, 'Unauthorized to assign to this class.');
            }

            Assignment::create([
                'teacher_id' => $teacher->id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'due_date' => $request->due_date,
            ]);
            $msg = $request->type == 'homework' ? 'Homework' : 'Assignment';
            return $this->ajaxSuccess($request, $msg . ' created successfully.');
        }
        return $this->ajaxError($request, 'Teacher profile not found.');
    }

    // 8. Homework
    public function homework() { 
        $teacher = $this->getTeacher();
        $homeworks = [];
        if ($teacher) {
            $homeworks = \App\Models\Assignment::with(['class_', 'subject'])->where('teacher_id', $teacher->id)->where('type', 'homework')->orderBy('due_date', 'asc')->get();
        }
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->get();
        $subjects = DB::table('subjects')->get();
        return view('teacher.homework', compact('homeworks', 'classes', 'subjects')); 
    }

    // 9. Exams
    public function exams() { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        
        $exams = DB::table('exam_schedules')
            ->whereIn('exam_schedules.class_id', $classIds)
            ->join('classes', 'exam_schedules.class_id', '=', 'classes.id')
            ->join('subjects', 'exam_schedules.subject_id', '=', 'subjects.id')
            ->select('exam_schedules.*', 'classes.name as class_name', 'subjects.name as subject')
            ->orderBy('exam_schedules.exam_date', 'asc')
            ->get();
            
        return view('teacher.exams', compact('exams')); 
    }

    // 10. Timetable
    public function timetable() { 
        $teacher = $this->getTeacher();
        $timetable = collect();
        if ($teacher) {
            $timetable = DB::table('timetables')
                ->where('teacher', 'like', '%'.$teacher->full_name.'%')
                ->join('classes', 'timetables.class_id', '=', 'classes.id')
                ->select('timetables.*', 'classes.name as class_name')
                ->get();
        }
        return view('teacher.timetable', compact('timetable')); 
    }

    // 11. Leaves
    public function leaves() { 
        $teacher = $this->getTeacher();
        $leaves = [];
        if ($teacher) {
            $leaves = DB::table('teacher_leaves')->where('teacher_id', $teacher->id)->orderBy('created_at', 'desc')->get();
        }
        return view('teacher.leaves', compact('leaves')); 
    }

    public function storeLeave(Request $request) {
        $request->validate([
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $teacher = $this->getTeacher();
        if($teacher) {
            DB::table('teacher_leaves')->insert([
                'teacher_id' => $teacher->id,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'Pending',
                'created_at' => now(),
            ]);
            return $this->ajaxSuccess($request, 'Leave request submitted successfully.');
        }
        return $this->ajaxError($request, 'Teacher profile not found.');
    }

    // 12. Announcements
    public function announcements() { 
        $announcements = Announcement::where('status', 'published')
            ->whereIn('role_visibility', ['all', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('teacher.announcements', compact('announcements')); 
    }

    // 13. Performance
    public function performance() { 
        return view('teacher.performance'); 
    }

    // 14. Profile
    public function profile() { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->pluck('name')->implode(', ');
        $subjects = $this->getAssignedSubjects($teacher)->pluck('subject')->implode(', ');
        
        return view('teacher.profile', compact('teacher', 'classes', 'subjects')); 
    }

    public function updateProfile(Request $request) {
        $request->validate([
            'full_name' => 'required|string',
            'mobile' => 'nullable|string',
        ]);
        $teacher = $this->getTeacher();
        if($teacher) {
            DB::table('teachers')->where('id', $teacher->id)->update([
                'full_name' => $request->full_name,
                'mobile' => $request->mobile,
            ]);
            // Optional: update users table name
            DB::table('users')->where('id', auth()->id())->update(['name' => $request->full_name]);
            return $this->ajaxSuccess($request, 'Profile updated successfully.');
        }
        return $this->ajaxError($request, 'Profile not found.');
    }

    // 15. Messages
    public function messages() { 
        $messages = Message::with('sender')->where('receiver_id', auth()->id())->orderBy('created_at', 'desc')->get();
        $users = DB::table('users')->select('id', 'name', 'role_id')->where('id', '!=', auth()->id())->get();
        return view('teacher.messages', compact('messages', 'users')); 
    }

    public function storeMessage(Request $request) {
        $request->validate([
            'receiver_id' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);
        return $this->ajaxSuccess($request, 'Message sent.');
    }

    // 16. Reports
    public function reports() { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->orderBy('name')->get();
        return view('teacher.reports', compact('classes')); 
    }

    // 17. Exam Schedule
    public function examSchedule(Request $request) {
        $teacher = $this->getTeacher();
        if(!$teacher) return $this->ajaxError($request, 'Teacher profile not found.');
        
        $classIds = $this->getAssignedClassIds($teacher);
        
        $schedules = \App\Models\ExamSchedule::whereIn('class_id', $classIds)
            ->with(['class', 'subjectRel'])
            ->orderBy('exam_date')
            ->get();
            
        return view('teacher.exam-schedule', compact('schedules'));
    }

    // 18. Student Leave Approval
    public function studentLeaves() {
        $teacher = $this->getTeacher();
        if(!$teacher) return $this->ajaxError($request, 'Teacher profile not found.');

        $classIds = $this->getAssignedClassIds($teacher);
        $studentIds = \App\Models\Student::whereIn('current_class_id', $classIds)->pluck('id');
        
        // Assuming student_leave_requests table exists
        $leaves = DB::table('student_leave_requests')
            ->whereIn('student_id', $studentIds)
            ->join('students', 'student_leave_requests.student_id', '=', 'students.id')
            ->select('student_leave_requests.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.student-leaves', compact('leaves'));
    }

    public function approveStudentLeave(Request $request, $id) {
        DB::table('student_leave_requests')->where('id', $id)->update(['status' => 'Approved', 'updated_at' => now()]);
        return $this->ajaxSuccess($request, 'Student leave approved.');
    }

    public function rejectStudentLeave(Request $request, $id) {
        DB::table('student_leave_requests')->where('id', $id)->update(['status' => 'Rejected', 'updated_at' => now()]);
        return $this->ajaxSuccess($request, 'Student leave rejected.');
    }

    // AJAX Methods
    public function getSections(Request $request) {
        $sections = DB::table('sections')->where('class_id', $request->class_id)->get();
        return response()->json($sections);
    }
    
    public function getSubjects(Request $request) {
        $teacher = $this->getTeacher();
        $subjects = DB::table('teacher_assignments')
            ->where('teacher_assignments.class_id', $request->class_id)
            ->where('teacher_assignments.teacher_id', $teacher?->id)
            ->join('subjects', 'teacher_assignments.subject_id', '=', 'subjects.id')
            ->select('subjects.name as subject')
            ->distinct()
            ->get();
        return response()->json($subjects);
    }
    
    public function getExams(Request $request) {
        $subjectId = DB::table('subjects')->where('name', $request->subject)->value('id');
        $exams = \App\Models\ExamSchedule::where('class_id', $request->class_id)
            ->where('subject_id', $subjectId)
            ->get()
            ->map(function($exam) {
                return [
                    'id' => $exam->id,
                    'text' => $exam->exam_type . ' (' . \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') . ')'
                ];
            });
        return response()->json($exams);
    }
}
