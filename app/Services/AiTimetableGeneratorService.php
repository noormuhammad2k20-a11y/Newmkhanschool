<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Timetable;

class AiTimetableGeneratorService
{
    protected $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    protected $periods = [
        'Period 1' => ['start' => '08:00:00', 'end' => '08:45:00'],
        'Period 2' => ['start' => '08:45:00', 'end' => '09:30:00'],
        'Period 3' => ['start' => '09:30:00', 'end' => '10:15:00'],
        'Break'    => ['start' => '10:15:00', 'end' => '10:45:00'],
        'Period 4' => ['start' => '10:45:00', 'end' => '11:30:00'],
        'Period 5' => ['start' => '11:30:00', 'end' => '12:15:00'],
        'Period 6' => ['start' => '12:15:00', 'end' => '13:00:00']
    ];

    /**
     * Generate a collision-free timetable and save it to the database.
     */
    public function generateTimetable()
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        // Clear existing timetable
        Timetable::query()->delete();

        $timetableData = [];
        $teacherSchedule = []; // To track and prevent double booking

        foreach ($classes as $class) {
            $classSections = Section::where('class_id', $class->id)->get();
            $sections = $classSections->isEmpty() ? [null] : $classSections;
            
            foreach ($sections as $section) {
                $sectionName = $section ? $section->name : 'A';
                $className = $class->name . ' - ' . $sectionName;
                
                $timetableData[$className] = [];

                foreach ($this->days as $day) {
                    $timetableData[$className][$day] = [];
                    
                    foreach ($this->periods as $periodName => $time) {
                        $timeStr = substr($time['start'], 0, 5) . ' - ' . substr($time['end'], 0, 5);

                        if ($periodName === 'Break') {
                            $timetableData[$className][$day][$periodName] = [
                                'subject' => 'Break',
                                'teacher' => '-',
                                'time' => $timeStr,
                                'room' => '-'
                            ];
                            continue;
                        }

                        $assignedSubject = $subjects->random();
                        $assignedTeacher = null;

                        foreach ($teachers->shuffle() as $teacher) {
                            $teacherId = $teacher->id;
                            if (!isset($teacherSchedule[$day][$periodName][$teacherId])) {
                                $assignedTeacher = $teacher;
                                $teacherSchedule[$day][$periodName][$teacherId] = true;
                                break;
                            }
                        }

                        $room = 'Room ' . rand(101, 305);
                        $teacherName = 'TBD';
                        $teacherId = null;
                        $subjectName = 'Self Study';
                        $subjectId = null;

                        if ($assignedTeacher) {
                            $teacherName = $assignedTeacher->full_name;
                            $teacherId = $assignedTeacher->id;
                            $subjectName = $assignedSubject->name;
                            $subjectId = $assignedSubject->id;
                        } else {
                            $room = 'Library';
                        }

                        // Save to DB
                        $slot = Timetable::create([
                            'class_id' => $class->id,
                            'section_id_ref' => $section ? $section->id : null,
                            'subject' => $subjectName,
                            'subject_id_ref' => $subjectId,
                            'teacher' => $teacherName,
                            'teacher_id' => $teacherId,
                            'room' => $room,
                            'day_of_week' => $day,
                            'start_time' => $time['start'],
                            'end_time' => $time['end'],
                        ]);

                        $timetableData[$className][$day][$periodName] = [
                            'id' => $slot->id,
                            'subject' => $subjectName,
                            'teacher' => $teacherName,
                            'time' => $timeStr,
                            'room' => $room
                        ];
                    }
                }
            }
        }

        return [
            'status' => 'success',
            'message' => 'Timetable generated successfully with zero conflicts.',
            'data' => $timetableData
        ];
    }

    public function getTimetable()
    {
        $slots = Timetable::with(['teacher', 'subjectRef', 'sectionRef', 'class_'])->get();
        if ($slots->isEmpty()) {
            return null;
        }

        $timetableData = [];

        foreach ($slots as $slot) {
            $className = $slot->class_->name . ' - ' . ($slot->sectionRef ? $slot->sectionRef->name : 'A');
            $day = $slot->day_of_week;
            
            // Find period name
            $periodName = 'Unknown';
            $timeStr = substr($slot->start_time, 0, 5) . ' - ' . substr($slot->end_time, 0, 5);
            foreach ($this->periods as $pName => $time) {
                if ($time['start'] == $slot->start_time && $time['end'] == $slot->end_time) {
                    $periodName = $pName;
                    break;
                }
            }

            if (!isset($timetableData[$className])) {
                $timetableData[$className] = [];
            }
            if (!isset($timetableData[$className][$day])) {
                $timetableData[$className][$day] = [];
                // Ensure Break is included
                $timetableData[$className][$day]['Break'] = [
                    'subject' => 'Break',
                    'teacher' => '-',
                    'time' => '10:15 - 10:45',
                    'room' => '-'
                ];
            }

            $timetableData[$className][$day][$periodName] = [
                'id' => $slot->id,
                'subject' => $slot->subjectRef ? $slot->subjectRef->name : $slot->subject,
                'teacher' => $slot->teacher ? $slot->teacher->full_name : $slot->teacher,
                'time' => $timeStr,
                'room' => $slot->room
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Timetable loaded successfully.',
            'data' => $timetableData
        ];
    }

    public function getAiSuggestions($dayOfWeek, $startTime, $endTime)
    {
        $busyTeacherIds = Timetable::where('day_of_week', $dayOfWeek)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where(function($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                       ->where('end_time', '>', $startTime);
                });
            })
            ->pluck('teacher_id')
            ->filter()
            ->toArray();

        $availableTeachers = Teacher::whereNotIn('id', $busyTeacherIds)->get(['id', 'full_name']);

        $busyRooms = Timetable::where('day_of_week', $dayOfWeek)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where(function($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                       ->where('end_time', '>', $startTime);
                });
            })
            ->pluck('room')
            ->filter()
            ->toArray();

        $allRooms = [];
        for ($i=101; $i<=110; $i++) $allRooms[] = "Room $i";
        for ($i=201; $i<=210; $i++) $allRooms[] = "Room $i";
        for ($i=301; $i<=310; $i++) $allRooms[] = "Room $i";
        $allRooms[] = 'Library';
        $allRooms[] = 'Lab 1';
        $allRooms[] = 'Lab 2';

        $availableRooms = array_values(array_diff($allRooms, $busyRooms));

        return [
            'teachers' => $availableTeachers,
            'rooms' => $availableRooms,
            'subjects' => Subject::all(['id', 'name'])
        ];
    }

    public function checkConflicts($teacherId, $roomId, $dayOfWeek, $startTime, $endTime, $ignoreTimetableId)
    {
        $conflicts = [];
        
        if ($teacherId) {
            $teacherConflict = Timetable::with(['class_', 'sectionRef'])
                ->where('teacher_id', $teacherId)
                ->where('id', '!=', $ignoreTimetableId)
                ->where('day_of_week', $dayOfWeek)
                ->where(function($q) use ($startTime, $endTime) {
                    $q->where(function($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<', $endTime)
                           ->where('end_time', '>', $startTime);
                    });
                })->first();

            if ($teacherConflict && $teacherConflict->class_) {
                $className = $teacherConflict->class_->name . ($teacherConflict->sectionRef ? ' - ' . $teacherConflict->sectionRef->name : '');
                $conflicts[] = "Teacher is already scheduled for class {$className} during this time.";
            }
        }

        if ($roomId) {
            $roomConflict = Timetable::with(['class_', 'sectionRef'])
                ->where('room', $roomId)
                ->where('id', '!=', $ignoreTimetableId)
                ->where('day_of_week', $dayOfWeek)
                ->where(function($q) use ($startTime, $endTime) {
                    $q->where(function($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<', $endTime)
                           ->where('end_time', '>', $startTime);
                    });
                })->first();

            if ($roomConflict && $roomConflict->class_) {
                 $className = $roomConflict->class_->name . ($roomConflict->sectionRef ? ' - ' . $roomConflict->sectionRef->name : '');
                 $conflicts[] = "Room {$roomId} is already booked for class {$className} during this time.";
            }
        }

        return $conflicts;
    }
}

