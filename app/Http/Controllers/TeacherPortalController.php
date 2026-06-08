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
    private function getTeacher()
    {
        return DB::table('teachers')->where('user_id', auth()->id())->first();
    }

    private function getAssignedClassIds($teacher)
    {
        if (!$teacher) return collect();
        return DB::table('teacher_assignments')
            ->where('teacher_id', $teacher->id)
            ->whereNotNull('class_id')
            ->pluck('class_id')
            ->unique();
    }

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
            
        $announcements = Announcement::whereIn('target_role', ['all', 'teacher'])->orderBy('created_at', 'desc')->take(5)->get();

        return view('teacher.dashboard', compact('classesCount', 'subjectsCount', 'totalStudents', 'pendingAssignments', 'todaysTimetable', 'announcements')); 
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
            return redirect()->back()->with('error', 'Unauthorized access to this class.');
        }

        foreach ($request->attendance as $studentId => $status) {
            DB::table('student_attendances')->updateOrInsert(
                ['student_id' => $studentId, 'date' => $request->date],
                ['status' => $status, 'marked_by' => auth()->id(), 'academic_year_id' => 1, 'updated_at' => now()]
            );
        }

        return redirect()->back()->with('success', 'Attendance marked successfully.');
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
        
        $examTypes = DB::table('exam_types')->get();
        
        $selectedClass = $request->get('class_id');
        $selectedSubject = $request->get('subject');
        $selectedExam = $request->get('exam_type_id');
        
        $subjects = collect();
        if ($selectedClass) {
            $subjects = DB::table('teacher_assignments')
                ->where('teacher_assignments.class_id', $selectedClass)
                ->where('teacher_assignments.teacher_id', $teacher?->id)
                ->join('subjects', 'teacher_assignments.subject_id', '=', 'subjects.id')
                ->select('subjects.name as subject')
                ->distinct()
                ->get();
        }
        
        $students = collect();
        $existingMarks = collect();
        
        if ($selectedClass && $selectedSubject && $selectedExam && $classIds->contains($selectedClass)) {
            $students = DB::table('students')->where('current_class_id', $selectedClass)->get();
            $existingMarks = DB::table('marks')
                ->where('exam_type_id', $selectedExam)
                ->whereIn('student_id', $students->pluck('id'))
                // For simplicity, matching subject by ID would be better, but the schema has subjects linked by name in timetables.
                // Assuming subject_id needs to be matched. We'll need a subject mapping here.
                ->get()
                ->keyBy('student_id');
        }
        
        return view('teacher.marks', compact('classes', 'subjects', 'examTypes', 'selectedClass', 'selectedSubject', 'selectedExam', 'students', 'existingMarks')); 
    }

    public function storeMarks(Request $request) {
        // Validation and RBAC check
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        if (!$classIds->contains($request->class_id)) {
            return redirect()->back()->with('error', 'Unauthorized access to this class.');
        }
        
        // Resolve subject_id
        $subjectId = DB::table('subjects')->where('name', $request->subject)->value('id');
        if(!$subjectId) return redirect()->back()->with('error', 'Subject not found.');

        foreach ($request->marks as $studentId => $mark) {
            if($mark !== null) {
                DB::table('marks')->updateOrInsert(
                    ['student_id' => $studentId, 'subject_id' => $subjectId, 'exam_type_id' => $request->exam_type_id, 'academic_year_id' => 1],
                    ['marks_obtained' => $mark, 'total_marks' => $request->total_marks ?? 100, 'created_at' => now()]
                );
            }
        }
        return redirect()->back()->with('success', 'Marks saved successfully.');
    }

    // 7. Assignments
    public function assignments() { 
        $teacher = $this->getTeacher();
        $assignments = [];
        if ($teacher) {
            $assignments = Assignment::with(['class', 'subject'])->where('teacher_id', $teacher->id)->where('type', 'assignment')->orderBy('due_date', 'asc')->get();
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
                return redirect()->back()->with('error', 'Unauthorized to assign to this class.');
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
            return redirect()->back()->with('success', $msg . ' created successfully.');
        }
        return redirect()->back()->with('error', 'Teacher profile not found.');
    }

    // 8. Homework
    public function homework() { 
        $teacher = $this->getTeacher();
        $homeworks = [];
        if ($teacher) {
            $homeworks = Assignment::with(['class', 'subject'])->where('teacher_id', $teacher->id)->where('type', 'homework')->orderBy('due_date', 'asc')->get();
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
        
        // Need to join classes table to match class_name if exam_schedules uses class_name.
        // Assuming exam_schedules uses class_name text.
        $classNames = DB::table('classes')->whereIn('id', $classIds)->pluck('name');
        
        $exams = DB::table('exam_schedules')->whereIn('class_name', $classNames)->get();
            
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
            $leaves = DB::table('leave_requests')->where('teacher_id', $teacher->id)->orderBy('created_at', 'desc')->get();
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
            DB::table('leave_requests')->insert([
                'teacher_id' => $teacher->id,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'Pending',
                'created_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Leave request submitted successfully.');
        }
        return redirect()->back()->with('error', 'Teacher profile not found.');
    }

    // 12. Announcements
    public function announcements() { 
        $announcements = Announcement::whereIn('target_role', ['all', 'teacher'])->orderBy('created_at', 'desc')->get();
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
            return redirect()->back()->with('success', 'Profile updated successfully.');
        }
        return redirect()->back()->with('error', 'Profile not found.');
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
        return redirect()->back()->with('success', 'Message sent.');
    }

    // 16. Reports
    public function reports() { 
        $teacher = $this->getTeacher();
        $classIds = $this->getAssignedClassIds($teacher);
        $classes = DB::table('classes')->whereIn('id', $classIds)->orderBy('name')->get();
        return view('teacher.reports', compact('classes')); 
    }
}
