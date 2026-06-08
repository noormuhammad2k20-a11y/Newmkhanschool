<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }
    public function create()
    {
        return view('students.create');
    }
    public function show($id)
    {
        $student = \Illuminate\Support\Facades\DB::table('students as s')
            ->leftJoin('classes as c', 's.current_class_id', '=', 'c.id')
            ->leftJoin('sections as sec', 's.current_section_id', '=', 'sec.id')
            ->where('s.id', $id)
            ->select('s.*', 'c.name as class_name', 'sec.name as section_name')
            ->first();

        if (!$student) {
            abort(404, 'Student not found');
        }

        return view('students.show', compact('student'));
    }
    public function edit($id)
    {
        $student = \Illuminate\Support\Facades\DB::table('students')->where('id', $id)->first();
        if (!$student) abort(404);
        
        $classes = \Illuminate\Support\Facades\DB::table('classes')->get();
        $sections = \Illuminate\Support\Facades\DB::table('sections')->get();
        
        return view('students.edit', compact('student', 'classes', 'sections'));
    }
}