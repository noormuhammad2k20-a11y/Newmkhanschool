<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Fee;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;

class BranchController extends Controller
{

    public function index()
    {
        $mainSchoolId = auth()->user()->school_id;
        
        $branches = School::where('parent_school_id', $mainSchoolId)
            ->orWhere('id', $mainSchoolId)
            ->withCount(['students', 'teachers'])
            ->get();

        // Summary stats across all branches (could be filtered)
        $totalStudents = Student::withoutGlobalScope('branch')->whereIn('school_id', $branches->pluck('id'))->count();
        $totalTeachers = Teacher::withoutGlobalScope('branch')->whereIn('school_id', $branches->pluck('id'))->count();
        $totalRevenue  = Fee::whereHas('student', function ($query) use ($branches) {
            $query->withoutGlobalScope('branch')->whereIn('school_id', $branches->pluck('id'));
        })->where('status','Paid')->sum('paid_amount');

        return view('admin.branches.index', compact('branches','totalStudents','totalTeachers','totalRevenue'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:200',
            'branch_code'    => 'required|string|max:50|unique:schools',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:150',
            'principal_name' => 'nullable|string|max:150',
            'logo'           => 'nullable|image|max:2048',
        ]);

        $data = $request->except('logo');
        $data['parent_school_id'] = auth()->user()->school_id;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('branch-logos','public');
        }

        School::create($data);
        return redirect()->route('admin.branches.index')->with('success','Branch created successfully.');
    }

    public function show($id)
    {
        $branch = School::findOrFail($id);

        // Branch-specific stats
        $stats = [
            'students'         => Student::withoutGlobalScope('branch')->where('school_id', $id)->count(),
            'teachers'         => Teacher::withoutGlobalScope('branch')->where('school_id', $id)->count(),
            'revenue_this_month' => Fee::whereHas('student', function($q) use ($id) {
                $q->withoutGlobalScope('branch')->where('school_id', $id);
            })->where('status','Paid')->whereMonth('created_at', now()->month)->sum('paid_amount'),
            'attendance_today' => StudentAttendance::withoutGlobalScope('branch')->where('date', today()->toDateString())
                ->whereHas('student', fn($q) => $q->withoutGlobalScope('branch')->where('school_id',$id))
                ->where('status','P')->count(),
        ];

        return view('admin.branches.show', compact('branch','stats'));
    }

    public function edit($id)
    {
        $branch = School::findOrFail($id);
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = School::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:200',
            'branch_code' => 'required|string|max:50|unique:schools,branch_code,'.$id,
        ]);

        $data = $request->except('logo');
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('branch-logos','public');
        }

        $branch->update($data);
        return redirect()->route('admin.branches.index')->with('success','Branch updated.');
    }

    public function switchBranch(Request $request)
    {
        $request->validate(['branch_id' => 'required|exists:schools,id']);
        session(['active_branch_id' => $request->branch_id]);
        return back()->with('success','Switched to branch context successfully.');
    }
}
