<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        $roleId = auth()->user()->role_id;
        if (in_array($roleId, [1, 2])) return redirect()->route('admin.dashboard');
        if ($roleId == 3) return redirect()->route('teacher.dashboard');
        if ($roleId == 4) return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/students', [\App\Http\Controllers\StudentController::class, 'index'])->name('admin.students');
    Route::get('/students/create', [\App\Http\Controllers\StudentController::class, 'create'])->name('admin.students.create');
    Route::get('/students/{id}', [\App\Http\Controllers\StudentController::class, 'show'])->name('admin.students.show');
    Route::get('/students/{id}/edit', [\App\Http\Controllers\StudentController::class, 'edit'])->name('admin.students.edit');
    Route::get('/teachers', [\App\Http\Controllers\TeacherController::class, 'index'])->name('admin.teachers');
    Route::get('/teachers/create', [\App\Http\Controllers\TeacherController::class, 'create'])->name('admin.teachers.create');
    Route::get('/teachers/{id}', [\App\Http\Controllers\TeacherController::class, 'show'])->name('admin.teachers.show');
    Route::get('/teachers/{id}/edit', [\App\Http\Controllers\TeacherController::class, 'edit'])->name('admin.teachers.edit');
    Route::get('/teachers/{id}/permissions', [\App\Http\Controllers\TeacherController::class, 'permissions'])->name('admin.teachers.permissions');
    Route::post('/teachers/{id}/permissions', [\App\Http\Controllers\TeacherController::class, 'updatePermissions'])->name('admin.teachers.permissions.update');
    Route::get('/academics', [\App\Http\Controllers\AcademicController::class, 'index'])->name('admin.academics.index');
    Route::post('/academics/classes', [\App\Http\Controllers\AcademicController::class, 'storeClass'])->name('admin.academics.classes.store');
    Route::delete('/academics/classes/{id}', [\App\Http\Controllers\AcademicController::class, 'destroyClass'])->name('admin.academics.classes.destroy');
    Route::post('/academics/subjects', [\App\Http\Controllers\AcademicController::class, 'storeSubject'])->name('admin.academics.subjects.store');
    Route::delete('/academics/subjects/{id}', [\App\Http\Controllers\AcademicController::class, 'destroySubject'])->name('admin.academics.subjects.destroy');
    Route::get('/classes/timetable', [\App\Http\Controllers\ClassController::class, 'timetable'])->name('admin.classes.timetable');
    Route::get('/attendance/mark', [\App\Http\Controllers\AttendanceController::class, 'mark'])->name('admin.attendance.mark');
    Route::get('/attendance/teachers', [\App\Http\Controllers\AttendanceController::class, 'teacher'])->name('admin.attendance.teacher');
    Route::get('/exams', [\App\Http\Controllers\ExamController::class, 'index'])->name('admin.exams');
    Route::get('/exams/marks', [\App\Http\Controllers\ExamController::class, 'marks'])->name('admin.exams.marks');
    Route::get('/fees', [\App\Http\Controllers\FeeController::class, 'index'])->name('admin.fees');
    Route::get('/inventory', [\App\Http\Controllers\InventoryController::class, 'index'])->name('admin.inventory');
    Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('admin.calendar');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports');
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('admin.events');
    Route::get('/payroll', [\App\Http\Controllers\PayrollController::class, 'index'])->name('admin.payroll');
});

// TEACHER ROUTES
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TeacherPortalController::class, 'dashboard'])->name('teacher.dashboard');
    
    Route::middleware('teacher_module:attendance')->group(function() {
        Route::get('/attendance', [\App\Http\Controllers\TeacherPortalController::class, 'attendance'])->name('teacher.attendance');
        Route::post('/attendance/mark', [\App\Http\Controllers\TeacherPortalController::class, 'markAttendance'])->name('teacher.attendance.mark');
    });
    
    Route::middleware('teacher_module:classes')->get('/classes', [\App\Http\Controllers\TeacherPortalController::class, 'classes'])->name('teacher.classes');
    Route::middleware('teacher_module:subjects')->get('/subjects', [\App\Http\Controllers\TeacherPortalController::class, 'subjects'])->name('teacher.subjects');
    Route::middleware('teacher_module:students')->get('/students', [\App\Http\Controllers\TeacherPortalController::class, 'students'])->name('teacher.students');
    
    Route::middleware('teacher_module:marks')->group(function() {
        Route::get('/marks', [\App\Http\Controllers\TeacherPortalController::class, 'marks'])->name('teacher.marks');
        Route::post('/marks', [\App\Http\Controllers\TeacherPortalController::class, 'storeMarks'])->name('teacher.marks.store');
    });
    
    Route::middleware('teacher_module:assignments')->group(function() {
        Route::get('/assignments', [\App\Http\Controllers\TeacherPortalController::class, 'assignments'])->name('teacher.assignments');
        Route::post('/assignments', [\App\Http\Controllers\TeacherPortalController::class, 'storeAssignment'])->name('teacher.assignments.store');
    });
    
    Route::middleware('teacher_module:homework')->group(function() {
        Route::get('/homework', [\App\Http\Controllers\TeacherPortalController::class, 'homework'])->name('teacher.homework');
        Route::post('/homework', [\App\Http\Controllers\TeacherPortalController::class, 'storeAssignment'])->name('teacher.homework.store');
    });
    
    Route::middleware('teacher_module:exams')->get('/exams', [\App\Http\Controllers\TeacherPortalController::class, 'exams'])->name('teacher.exams');
    Route::middleware('teacher_module:timetable')->get('/timetable', [\App\Http\Controllers\TeacherPortalController::class, 'timetable'])->name('teacher.timetable');
    
    Route::middleware('teacher_module:leaves')->group(function() {
        Route::get('/leaves', [\App\Http\Controllers\TeacherPortalController::class, 'leaves'])->name('teacher.leaves');
        Route::post('/leaves', [\App\Http\Controllers\TeacherPortalController::class, 'storeLeave'])->name('teacher.leaves.store');
    });
    
    Route::middleware('teacher_module:announcements')->get('/announcements', [\App\Http\Controllers\TeacherPortalController::class, 'announcements'])->name('teacher.announcements');
    Route::middleware('teacher_module:performance')->get('/performance', [\App\Http\Controllers\TeacherPortalController::class, 'performance'])->name('teacher.performance');
    
    Route::middleware('teacher_module:profile')->group(function() {
        Route::get('/profile', [\App\Http\Controllers\TeacherPortalController::class, 'profile'])->name('teacher.profile');
        Route::post('/profile', [\App\Http\Controllers\TeacherPortalController::class, 'updateProfile'])->name('teacher.profile.update');
    });
    
    Route::middleware('teacher_module:messages')->group(function() {
        Route::get('/messages', [\App\Http\Controllers\TeacherPortalController::class, 'messages'])->name('teacher.messages');
        Route::post('/messages', [\App\Http\Controllers\TeacherPortalController::class, 'storeMessage'])->name('teacher.messages.store');
    });
    
    Route::middleware('teacher_module:reports')->get('/reports', [\App\Http\Controllers\TeacherPortalController::class, 'reports'])->name('teacher.reports');
});

// STUDENT ROUTES
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', function() {
        return view('student.dashboard');
    })->name('student.dashboard');
});

Route::get('/parent/dashboard', [\App\Http\Controllers\ParentController::class, 'index'])->name('parent.dashboard')->middleware('auth');

// API ROUTES (Scoped internally in controllers based on auth()->user()->role_id)
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index'])->name('api.dashboard');
    Route::get('/students', [\App\Http\Controllers\Api\StudentController::class, 'index'])->name('api.students.index');
    Route::post('/students', [\App\Http\Controllers\Api\StudentController::class, 'store'])->name('api.students.store');
    Route::put('/students/{id}', [\App\Http\Controllers\Api\StudentController::class, 'update'])->name('api.students.update');
    Route::delete('/students/{id}', [\App\Http\Controllers\Api\StudentController::class, 'destroy'])->name('api.students.destroy');
    Route::get('/teachers', [\App\Http\Controllers\Api\TeacherController::class, 'index'])->name('api.teachers.index');
    Route::post('/teachers', [\App\Http\Controllers\Api\TeacherController::class, 'store'])->name('api.teachers.store');
    Route::put('/teachers/{id}', [\App\Http\Controllers\Api\TeacherController::class, 'update'])->name('api.teachers.update');
    Route::delete('/teachers/{id}', [\App\Http\Controllers\Api\TeacherController::class, 'destroy'])->name('api.teachers.destroy');
    Route::get('/classes/timetable', [\App\Http\Controllers\Api\ClassController::class, 'timetable'])->name('api.classes.timetable');
    Route::get('/attendance', [\App\Http\Controllers\Api\AttendanceController::class, 'index'])->name('api.attendance.index');
    Route::post('/attendance', [\App\Http\Controllers\Api\AttendanceController::class, 'store'])->name('api.attendance.store');
    Route::get('/teacher-attendance/dashboard', [\App\Http\Controllers\Api\TeacherAttendanceController::class, 'dashboard'])->name('api.teacher-attendance.dashboard');
    Route::put('/teacher-attendance/leaves/{id}/status', [\App\Http\Controllers\Api\TeacherAttendanceController::class, 'updateLeaveStatus'])->name('api.teacher-attendance.leaves.status');
    Route::get('/exams', [\App\Http\Controllers\Api\ExamController::class, 'index'])->name('api.exams');
    Route::get('/exams/marks', [\App\Http\Controllers\Api\ExamController::class, 'getMarks'])->name('api.exams.marks');
    Route::post('/exams/marks', [\App\Http\Controllers\Api\ExamController::class, 'storeMarks'])->name('api.exams.marks.store');
    Route::get('/fees', [\App\Http\Controllers\Api\FeeController::class, 'index'])->name('api.fees');
    Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index'])->name('api.events');
    Route::post('/events', [\App\Http\Controllers\Api\EventController::class, 'store'])->name('api.events.store');
});

