<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class TransportController extends Controller
{
    public function index()
    {
        $parent = auth()->user();
        
        $students = Student::whereHas('user.linkedStudents', function($q) use ($parent) {
            $q->where('parent_user_id', $parent->id);
        })->get();
        
        $studentIds = $students->pluck('id');

        $transports = DB::table('transport_students')
            ->join('transport_routes', 'transport_students.route_id', '=', 'transport_routes.id')
            ->whereIn('transport_students.student_id', $studentIds)
            ->select('transport_routes.*', 'transport_students.student_id')
            ->get()
            ->groupBy('student_id');

        return view('parent.transport', compact('transports', 'students'));
    }
}
