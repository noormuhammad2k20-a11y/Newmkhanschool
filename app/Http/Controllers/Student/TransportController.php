<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransportController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        // Fetch from transport_students table mapping to transport_routes
        $transport = DB::table('transport_students')
            ->join('transport_routes', 'transport_students.route_id', '=', 'transport_routes.id')
            ->where('transport_students.student_id', $student->id)
            ->first();

        return view('student.transport', compact('transport'));
    }
}
