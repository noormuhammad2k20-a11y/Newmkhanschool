<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiTimetableGeneratorService;
use App\Models\Timetable;
use App\Models\AuditLog;
use App\Models\Teacher;
use App\Models\Subject;

class AiTimetableController extends Controller
{
    protected $timetableService;

    public function __construct(AiTimetableGeneratorService $timetableService)
    {
        $this->timetableService = $timetableService;
    }

    public function index()
    {
        return view('admin.ai.timetable');
    }

    public function fetch()
    {
        $result = $this->timetableService->getTimetable();
        if (!$result) {
            return response()->json(['status' => 'empty']);
        }
        return response()->json($result);
    }

    public function generate(Request $request)
    {
        $result = $this->timetableService->generateTimetable();
        return response()->json($result);
    }

    public function getSuggestions(Request $request)
    {
        $dayOfWeek = $request->day_of_week;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        $suggestions = $this->timetableService->getAiSuggestions($dayOfWeek, $startTime, $endTime);
        return response()->json(['status' => 'success', 'data' => $suggestions]);
    }

    public function updateSlot(Request $request, $id)
    {
        $slot = Timetable::with(['class_', 'sectionRef'])->findOrFail($id);
        
        $teacherId = $request->teacher_id;
        $roomId = $request->room;
        $subjectId = $request->subject_id;

        $conflicts = $this->timetableService->checkConflicts(
            $teacherId, 
            $roomId, 
            $slot->day_of_week, 
            $slot->start_time, 
            $slot->end_time, 
            $slot->id
        );

        if (count($conflicts) > 0) {
            return response()->json(['status' => 'error', 'message' => implode(' ', $conflicts)], 400);
        }

        // Keep track of old values for AuditLog
        $oldTeacher = $slot->teacher;
        $oldRoom = $slot->room;
        $oldSubject = $slot->subject;

        $teacher = Teacher::find($teacherId);
        $subject = Subject::find($subjectId);

        $newTeacherName = $teacher ? $teacher->full_name : 'TBD';
        $newSubjectName = $subject ? $subject->name : 'Self Study';

        $slot->teacher_id = $teacherId;
        $slot->teacher = $newTeacherName;
        $slot->room = $roomId ?: 'Library';
        $slot->subject_id_ref = $subjectId;
        $slot->subject = $newSubjectName;
        
        $slot->save();

        $className = $slot->class_->name . ($slot->sectionRef ? ' - ' . $slot->sectionRef->name : '');
        $timeStr = substr($slot->start_time, 0, 5) . ' - ' . substr($slot->end_time, 0, 5);
        $desc = "Changed slot for $className ($slot->day_of_week, $timeStr): ";
        if ($oldTeacher !== $newTeacherName) $desc .= "Teacher: $oldTeacher -> $newTeacherName. ";
        if ($oldRoom !== $slot->room) $desc .= "Room: $oldRoom -> $slot->room. ";
        if ($oldSubject !== $newSubjectName) $desc .= "Subject: $oldSubject -> $newSubjectName. ";

        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'Timetable Edit',
            'model_type' => Timetable::class,
            'model_id' => $slot->id,
            'description' => $desc,
            'ip_address' => $request->ip()
        ]);

        return response()->json(['status' => 'success', 'message' => 'Slot updated successfully.']);
    }

    public function history()
    {
        $logs = AuditLog::where('model_type', Timetable::class)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get(['id', 'description', 'created_at']);
            
        return response()->json(['status' => 'success', 'data' => $logs]);
    }
}
