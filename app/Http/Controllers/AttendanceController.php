<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function mark()
    {
        return view('attendance.mark');
    }

    public function teacher()
    {
        return view('attendance.teacher');
    }
}
