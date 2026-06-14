<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\Fee;
use App\Models\Announcement;
use App\Models\ExamSchedule;
use App\Models\Timetable;
use App\Models\AcademicYear;
use App\Models\DigitalNote;
use App\Models\Quiz;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        abort_if(!$student, 403, 'Student record not found for this account.');

        $academicYear = AcademicYear::where('is_active', 1)->first();

        // Attendance stats
        $totalDays   = StudentAttendance::where('student_id', $student->id)
                         ->where('academic_year_id', $academicYear?->id)->count();
        $presentDays = StudentAttendance::where('student_id', $student->id)
                         ->where('academic_year_id', $academicYear?->id)
                         ->where('status', 'P')->count();
        $attendancePct = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        // Pending fees
        $pendingFees = Fee::where('student_id', $student->id)
                         ->whereIn('status', ['Pending','Overdue'])->sum('amount');

        // Announcements
        $announcements = Announcement::where('status', 'published')
                           ->whereIn('role_visibility', ['all','student'])
                           ->orderBy('created_at', 'desc')->take(5)->get();

        // Today's timetable
        $dayName = Carbon::today()->format('l'); // Monday, Tuesday...
        $activeVersion = \App\Models\TimetableVersion::where('status', 'Approved')->latest()->first();
        
        if (!$activeVersion) {
            $activeVersion = \App\Models\TimetableVersion::latest()->first();
        }
        
        $query = Timetable::where('class_id', $student->current_class_id)
                          ->where('section_id_ref', $student->current_section_id)
                          ->where('day_of_week', $dayName);
                          
        if ($activeVersion) {
            $query->where('timetable_version_id', $activeVersion->id);
        } else {
            $query->where('id', '<', 0); // No timetable versions exist
        }
        
        $todayClasses = $query->orderBy('start_time')->get();

        // Upcoming exams
        $upcomingExams = ExamSchedule::where('class_id', $student->current_class_id)
                           ->where('exam_date', '>=', today())
                           ->orderBy('exam_date')->take(3)->get();

        // Recent Digital Notes
        $recentNotes = DigitalNote::with(['subject', 'uploader'])
            ->where('is_public', 1)
            ->where('class_id', $student->current_class_id)
            ->where(function($q) use ($student) {
                $q->whereNull('section_id')
                  ->orWhere('section_id', 0)
                  ->orWhere('section_id', '')
                  ->orWhere('section_id', $student->current_section_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Upcoming Quizzes
        $upcomingQuizzes = Quiz::with(['subject'])
            ->where('is_active', 1)
            ->where('class_id', $student->current_class_id)
            ->where(function($q) use ($student) {
                $q->whereNull('section_id')
                  ->orWhere('section_id', 0)
                  ->orWhere('section_id', '')
                  ->orWhere('section_id', $student->current_section_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('student.dashboard', compact(
            'student','attendancePct','presentDays','totalDays',
            'pendingFees','announcements','todayClasses','upcomingExams',
            'recentNotes', 'upcomingQuizzes'
        ));
    }

    // S-02: My Progress Timeline
    public function progress()
    {
        $student = auth()->user()->student;
        abort_if(!$student, 403, 'Student record not found.');

        $marks = \App\Models\Mark::where('student_id', $student->id)
            ->with(['subject:id,name', 'examSchedule:id,exam_date,exam_type'])
            ->orderBy('created_at')
            ->get();

        // Per-subject group for Chart.js
        $chartData = [];
        foreach ($marks->groupBy('subject_id') as $subjectId => $subjectMarks) {
            $subjectName = $subjectMarks->first()->subject->name ?? 'Subject';
            $chartData[] = [
                'label' => $subjectName,
                'data'  => $subjectMarks->map(fn($m) => [
                    'x' => optional($m->examSchedule)->exam_date ?? $m->created_at->format('Y-m-d'),
                    'y' => (float) $m->percentage,
                ])->values()->toArray(),
            ];
        }

        $subjects = \App\Models\Subject::whereIn('id', $marks->pluck('subject_id')->unique())->get();

        return view('student.progress', compact('marks', 'chartData', 'subjects'));
    }

    // S-08: Quiz Results
    public function quizResults()
    {
        $student = auth()->user()->student;
        abort_if(!$student, 403, 'Student record not found.');

        $attempts = \App\Models\QuizAttempt::where('student_id', $student->id)
            ->with(['quiz' => fn($q) => $q->with('subject:id,name')])
            ->orderByDesc('submitted_at')
            ->get();

        return view('student.quiz_results', compact('attempts'));
    }
}
