<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function timetable()
    {
        return view('classes.timetable');
    }
}
