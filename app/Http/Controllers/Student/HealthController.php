<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $records = DB::table('health_records')
            ->where('student_id', $student->id)
            ->orderByDesc('record_date')
            ->get();

        return view('student.health-records', compact('records'));
    }
}
