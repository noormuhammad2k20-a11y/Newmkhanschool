<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransportController extends Controller
{
    public function assignStudent(Request $request, $routeId)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'stop_name'  => 'required|string|max:100',
        ]);

        // Remove student from any existing route first
        \App\Models\TransportStudent::where('student_id', $request->student_id)->delete();

        \App\Models\TransportStudent::create([
            'route_id'   => $routeId,
            'student_id' => $request->student_id,
            'stop_name'  => $request->stop_name,
            'status'     => 'Awaiting Boarding',
        ]);

        return back()->with('success', 'Student assigned to route.');
    }
}
