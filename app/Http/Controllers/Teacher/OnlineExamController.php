<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\OnlineExam;
use App\Models\ExamQuestion;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Traits\TeacherScoped;
use Illuminate\Http\Request;

class OnlineExamController extends Controller
{
    use TeacherScoped;

    public function index()
    {
        $teacher = $this->getTeacher();
        $exams   = OnlineExam::where('teacher_id', $teacher->id)
            ->with(['subject','class_'])
            ->orderByDesc('exam_date')
            ->paginate(15);
        return view('teacher.online-exams.index', compact('exams'));
    }

    public function create()
    {
        $teacher  = $this->getTeacher();
        $classes  = SchoolClass::whereIn('id', $this->getAssignedClassIds($teacher))->get();
        $subjects = Subject::whereIn('id', $this->getAssignedSubjectIds($teacher))->get();
        return view('teacher.online-exams.create', compact('classes','subjects'));
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();
        $request->validate([
            'title'             => 'required|string|max:255',
            'subject_id'        => 'required|exists:subjects,id',
            'class_id'          => 'required|exists:classes,id',
            'exam_date'         => 'required|date',
            'start_time'        => 'required',
            'end_time'          => 'required|after:start_time',
            'duration_minutes'  => 'required|integer|min:5|max:480',
            'total_marks'       => 'required|integer|min:1',
            'passing_marks'     => 'required|integer|min:1|lte:total_marks',
            'instructions'      => 'nullable|string',
        ]);

        // Verify teacher is assigned to this class/subject
        abort_unless(
            $this->getAssignedClassIds($teacher)->contains($request->class_id) &&
            $this->getAssignedSubjectIds($teacher)->contains($request->subject_id), 403
        );

        $academicYear = AcademicYear::where('is_active', 1)->firstOrFail();

        $exam = OnlineExam::create([
            'title'                   => $request->title,
            'description'             => $request->description,
            'subject_id'              => $request->subject_id,
            'class_id'                => $request->class_id,
            'academic_year_id'        => $academicYear->id,
            'teacher_id'              => $teacher->id,
            'exam_date'               => $request->exam_date,
            'start_time'              => $request->start_time,
            'end_time'                => $request->end_time,
            'duration_minutes'        => $request->duration_minutes,
            'total_marks'             => $request->total_marks,
            'passing_marks'           => $request->passing_marks,
            'instructions'            => $request->instructions,
            'shuffle_questions'       => $request->boolean('shuffle_questions', true),
            'show_result_immediately' => $request->boolean('show_result_immediately'),
            'status'                  => 'Draft',
        ]);

        return redirect()->route('teacher.online-exams.questions', $exam->id)
            ->with('success', 'Exam created. Now add questions.');
    }

    public function questions($examId)
    {
        $teacher  = $this->getTeacher();
        $exam     = OnlineExam::where('id', $examId)->where('teacher_id', $teacher->id)->firstOrFail();
        $questions = ExamQuestion::where('exam_id', $exam->id)->orderBy('order_no')->get();
        return view('teacher.online-exams.questions', compact('exam','questions'));
    }

    public function storeQuestion(Request $request, $examId)
    {
        $teacher = $this->getTeacher();
        $exam    = OnlineExam::where('id', $examId)->where('teacher_id', $teacher->id)->firstOrFail();

        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:MCQ,True/False,Short',
            'marks'         => 'required|integer|min:1',
            'correct_answer'=> 'required_if:question_type,MCQ,True/False',
        ]);

        ExamQuestion::create([
            'exam_id'        => $exam->id,
            'question_text'  => $request->question_text,
            'question_type'  => $request->question_type,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'marks'          => $request->marks,
            'order_no'       => ExamQuestion::where('exam_id', $exam->id)->max('order_no') + 1,
        ]);

        return back()->with('success', 'Question added.');
    }

    public function publish($examId)
    {
        $teacher = $this->getTeacher();
        $exam    = OnlineExam::where('id', $examId)->where('teacher_id', $teacher->id)->firstOrFail();
        abort_if(ExamQuestion::where('exam_id', $exam->id)->count() === 0, 422, 'Add questions before publishing.');
        $exam->update(['status' => 'Published']);
        return back()->with('success', 'Exam published. Students can now attempt it.');
    }

    // View results/attempts
    public function results($examId)
    {
        $teacher  = $this->getTeacher();
        $exam     = OnlineExam::where('id', $examId)->where('teacher_id', $teacher->id)->firstOrFail();
        $attempts = ExamAttempt::with('student.user')
            ->where('exam_id', $exam->id)
            ->orderByDesc('obtained_marks')->get();
        return view('teacher.online-exams.results', compact('exam','attempts'));
    }
}
