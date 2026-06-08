<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function timetable()
    {
        $timetable = [
            [
                'time' => '08:00 AM',
                'time_end' => '08:45 AM',
                'is_break' => false,
                'days' => [
                    'monday' => ['subject' => 'Mathematics', 'teacher' => 'Dr. S. Williams', 'room' => 'Rm 102', 'conflict' => false],
                    'tuesday' => ['subject' => 'Physics', 'teacher' => 'Mr. J. Davis', 'room' => 'Lab 3', 'conflict' => false],
                    'wednesday' => ['subject' => 'Mathematics', 'teacher' => 'Dr. S. Williams', 'room' => 'Rm 102', 'conflict' => false],
                    'thursday' => null, // empty
                    'friday' => ['subject' => 'Chemistry', 'teacher' => 'Mrs. L. Smith', 'room' => 'Lab 1', 'conflict' => false],
                    'saturday' => ['subject' => 'Physical Ed.', 'teacher' => 'Coach Carter', 'room' => 'Gym', 'conflict' => false],
                ]
            ],
            [
                'time' => '08:50 AM',
                'time_end' => '09:35 AM',
                'is_break' => false,
                'days' => [
                    'monday' => ['subject' => 'English Lit.', 'teacher' => 'Ms. A. Taylor', 'room' => 'Rm 205', 'conflict' => true],
                    'tuesday' => ['subject' => 'Chemistry', 'teacher' => 'Mrs. L. Smith', 'room' => 'Lab 1', 'conflict' => false],
                    'wednesday' => ['subject' => 'History', 'teacher' => 'Mr. R. Johnson', 'room' => 'Rm 301', 'conflict' => false],
                    'thursday' => ['subject' => 'Physics', 'teacher' => 'Mr. J. Davis', 'room' => 'Lab 3', 'conflict' => false],
                    'friday' => ['subject' => 'Mathematics', 'teacher' => 'Dr. S. Williams', 'room' => 'Rm 102', 'conflict' => false],
                    'saturday' => null,
                ]
            ],
            [
                'time' => '09:35 AM',
                'time_end' => '09:50 AM',
                'is_break' => true,
                'label' => 'Morning Break'
            ]
        ];
        
        return response()->json(['status' => 'success', 'data' => $timetable]);
    }
}
