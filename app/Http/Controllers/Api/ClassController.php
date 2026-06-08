<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function filters()
    {
        $classes = \App\Models\SchoolClass::orderBy('name')->get(['id', 'name']);
        $sections = \App\Models\Section::orderBy('name')->get(['id', 'name', 'class_id']);
        $sessions = \App\Models\AcademicYear::orderBy('year', 'desc')->get(['id', 'year']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'classes' => $classes,
                'sections' => $sections,
                'sessions' => $sessions
            ]
        ]);
    }

    public function timetable(Request $request)
    {
        $classId = $request->query('class_id', 3);
        $sectionId = $request->query('section_id', null);
        $sessionId = $request->query('session_id', null);

        // Determine the published version for the given session (or active session)
        $sessionQuery = \App\Models\AcademicYear::query();
        if ($sessionId) {
            $sessionQuery->where('id', $sessionId);
        } else {
            $sessionQuery->where('is_active', 1);
        }
        $academicYear = $sessionQuery->first();

        $publishedVersion = null;
        if ($academicYear) {
            $publishedVersion = \App\Models\TimetableVersion::where('academic_year_id', $academicYear->id)
                ->where('status', 'Approved')
                ->first();
        }

        if (!$publishedVersion) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $query = \App\Models\Timetable::where('timetable_version_id', $publishedVersion->id)
            ->where('class_id', $classId)
            ->orderBy('start_time');
            
        if ($sectionId) {
            $query->where('section_id_ref', $sectionId);
        }

        $dbTimetables = $query->get();

        $periods = [];
        
        foreach ($dbTimetables as $t) {
            $key = $t->start_time . '-' . $t->end_time;
            
            if (!isset($periods[$key])) {
                $periods[$key] = [
                    'start_time' => $t->start_time,
                    'time' => date('h:i A', strtotime($t->start_time)),
                    'time_end' => date('h:i A', strtotime($t->end_time)),
                    'is_break' => false,
                    'days' => []
                ];
            }
            
            $day = strtolower($t->day_of_week);
            $periods[$key]['days'][$day] = [
                'subject' => $t->subject,
                'teacher' => $t->teacher,
                'room' => $t->room,
                'conflict' => false
            ];
        }

        $timetable = array_values($periods);
        
        // Sort by start_time
        usort($timetable, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });

        // Insert break if missing (between 10:15 and 10:45) based on current DB times
        $breakInserted = false;
        $finalTimetable = [];
        foreach($timetable as $t) {
            if (!$breakInserted && $t['start_time'] > '10:15:00') {
                $finalTimetable[] = [
                    'time' => '10:15 AM',
                    'time_end' => '10:45 AM',
                    'is_break' => true,
                    'label' => 'Morning Break'
                ];
                $breakInserted = true;
            }
            $finalTimetable[] = $t;
        }

        return response()->json(['status' => 'success', 'data' => $finalTimetable]);
    }
}
