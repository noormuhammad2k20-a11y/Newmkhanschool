<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolBranch;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Fee;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Super Admin');
    }

    public function index()
    {
        $branches = SchoolBranch::withCount(['students','teachers'])->get();

        // Summary stats across all branches
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalRevenue  = Fee::where('status','Paid')->sum('paid_amount');

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
            'code'           => 'required|string|max:20|unique:school_branches',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:150',
            'principal_name' => 'nullable|string|max:150',
            'logo'           => 'nullable|image|max:2048',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('branch-logos','public');
        }

        SchoolBranch::create($data);
        return redirect()->route('admin.branches.index')->with('success','Branch created successfully.');
    }

    public function show($id)
    {
        $branch = SchoolBranch::findOrFail($id);

        // Branch-specific stats
        $stats = [
            'students'         => Student::where('school_id', $id)->count(),
            'teachers'         => Teacher::where('school_id', $id)->count(),
            'revenue_this_month' => Fee::where('school_id', $id)
                ->where('status','Paid')
                ->whereMonth('created_at', now()->month)->sum('paid_amount'),
            'attendance_today' => StudentAttendance::where('date', today()->toDateString())
                ->whereHas('student', fn($q) => $q->where('school_id',$id))
                ->where('status','P')->count(),
        ];

        return view('admin.branches.show', compact('branch','stats'));
    }

    public function edit($id)
    {
        $branch = SchoolBranch::findOrFail($id);
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = SchoolBranch::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:20|unique:school_branches,code,'.$id,
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
        // Allow super admin to switch context to a specific branch
        $request->validate(['branch_id' => 'required|exists:school_branches,id']);
        session(['active_branch_id' => $request->branch_id]);
        return back()->with('success','Switched to branch successfully.');
    }
}
