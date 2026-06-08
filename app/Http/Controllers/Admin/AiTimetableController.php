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

    public function fetch(Request $request)
    {
        $versionId = $request->query('version_id');
        $result = $this->timetableService->getTimetable($versionId);
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

    public function getVersions()
    {
        $versions = \App\Models\TimetableVersion::with(['createdBy', 'approvedBy', 'publishedBy'])
            ->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $versions]);
    }

    public function approve($id)
    {
        $version = \App\Models\TimetableVersion::findOrFail($id);
        
        // Archive previously approved versions for the same academic year
        \App\Models\TimetableVersion::where('academic_year_id', $version->academic_year_id)
            ->where('status', 'Approved')
            ->update(['status' => 'Archived']);

        $version->status = 'Approved';
        $version->approved_by = auth()->id() ?? 1;
        $version->approved_at = now();
        $version->save();
        
        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'Approve Timetable',
            'model_type' => \App\Models\TimetableVersion::class,
            'model_id' => $version->id,
            'description' => 'Approved timetable version: ' . $version->name,
            'ip_address' => request()->ip()
        ]);
        
        return response()->json(['status' => 'success', 'message' => 'Timetable approved successfully!']);
    }

    public function getSuggestions(Request $request)
    {
        $slotId = $request->slot_id;
        $dayOfWeek = $request->day_of_week;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $isInitialLoad = $request->initial_load;

        if ($isInitialLoad) {
            // For initial load, we return all teachers and rooms so the user can freely select.
            // Conflicts will be detected upon saving.
            $teachers = Teacher::all(['id', 'full_name']);
            
            $allRooms = [];
            for ($i=101; $i<=110; $i++) $allRooms[] = "Room $i";
            for ($i=201; $i<=210; $i++) $allRooms[] = "Room $i";
            for ($i=301; $i<=310; $i++) $allRooms[] = "Room $i";
            $allRooms[] = 'Library';
            $allRooms[] = 'Lab 1';
            $allRooms[] = 'Lab 2';

            return response()->json([
                'status' => 'success', 
                'data' => [
                    'teachers' => $teachers,
                    'rooms' => $allRooms,
                    'subjects' => Subject::all(['id', 'name'])
                ]
            ]);
        }

        // For AI suggestion, we return only available (conflict-free) teachers and rooms
        $suggestions = $this->timetableService->getAiSuggestions($dayOfWeek, $startTime, $endTime, $slotId);
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
