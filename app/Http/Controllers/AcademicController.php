<?php

namespace App\Http\Controllers;

use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Subject;

class AcademicController extends Controller
{
    use AjaxResponseTrait;
    public function index()
    {
        $classes = SchoolClass::all();
        $subjects = Subject::select('subjects.*', 'classes.name as class_name')
            ->leftJoin('classes', 'subjects.class_id', '=', 'classes.id')
            ->get();

        return view('admin.academics.index', compact('classes', 'subjects'));
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $class = new SchoolClass();
        $class->name = $request->name;
        $class->school_id = 1; // Assuming default school_id as 1 for now if single-school
        $class->save();

        return $this->ajaxSuccess($request, 'Class added successfully.');
    }

    public function destroyClass(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);
        // Also delete related subjects
        Subject::where('class_id', $class->id)->delete();
        $class->delete();

        return $this->ajaxSuccess($request, 'Class deleted successfully.');
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'class_id' => 'required|exists:classes,id',
        ]);

        $subject = new Subject();
        $subject->name = $request->name;
        $subject->code = $request->code;
        $subject->class_id = $request->class_id;
        $subject->save();

        return $this->ajaxSuccess($request, 'Subject added successfully.');
    }

    public function destroySubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return $this->ajaxSuccess($request, 'Subject deleted successfully.');
    }
}
