<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public function index()
    {
        return view('teachers.index');
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function show($id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);
        return view('teachers.show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.edit', compact('teacher'));
    }

    public function permissions($id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $classes = DB::table('classes')->get();
        $subjects = DB::table('subjects')->get();
        
        $assignedClassIds = DB::table('teacher_assignments')->where('teacher_id', $id)->whereNotNull('class_id')->pluck('class_id')->toArray();
        $assignedSubjectIds = DB::table('teacher_assignments')->where('teacher_id', $id)->whereNotNull('subject_id')->pluck('subject_id')->toArray();
        $assignedModules = DB::table('teacher_module_access')->where('teacher_id', $id)->pluck('module_name')->toArray();
        
        $modules = [
            'dashboard' => 'Dashboard',
            'attendance' => 'Student Attendance',
            'classes' => 'My Classes',
            'subjects' => 'My Subjects',
            'students' => 'Student Lists',
            'marks' => 'Marks & Grades',
            'assignments' => 'Assignments',
            'homework' => 'Homework',
            'exams' => 'Exams & Results',
            'timetable' => 'Timetable',
            'leaves' => 'Leave Requests',
            'announcements' => 'Announcements',
            'performance' => 'Student Performance',
            'profile' => 'My Profile',
            'messages' => 'Messaging',
            'reports' => 'Reports',
        ];

        return view('teachers.permissions', compact('teacher', 'classes', 'subjects', 'assignedClassIds', 'assignedSubjectIds', 'assignedModules', 'modules'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Update Classes
            $classIds = $request->input('classes', []);
            $subjectIds = $request->input('subjects', []);
            
            DB::table('teacher_assignments')->where('teacher_id', $id)->delete();
            
            $assignments = [];
            
            if (!empty($classIds) && !empty($subjectIds)) {
                // Cartesian product: map all selected classes to all selected subjects
                foreach ($classIds as $classId) {
                    foreach ($subjectIds as $subjectId) {
                        $assignments[] = [
                            'teacher_id' => $id,
                            'class_id' => $classId,
                            'subject_id' => $subjectId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (!empty($assignments)) {
                DB::table('teacher_assignments')->insert($assignments);
            }

            // Update Modules
            $modules = $request->input('modules', []);
            DB::table('teacher_module_access')->where('teacher_id', $id)->delete();
            
            $moduleAccess = [];
            foreach ($modules as $module) {
                $moduleAccess[] = [
                    'teacher_id' => $id,
                    'module_name' => $module,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($moduleAccess)) {
                DB::table('teacher_module_access')->insert($moduleAccess);
            }

            DB::commit();
            return redirect()->route('admin.teachers.permissions', $id)->with('success', 'Teacher permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating permissions: ' . $e->getMessage());
        }
    }
}
