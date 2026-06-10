<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        $roleId = auth()->user()->role_id;
        if (in_array($roleId, [1, 2])) return redirect()->route('admin.dashboard');
        if ($roleId == 3) return redirect()->route('teacher.dashboard');
        if ($roleId == 4) return redirect()->route('student.dashboard');
        if ($roleId == 5) return redirect()->route('parent.dashboard');
    }
    return redirect()->route('login');
});

// ADMIN ROUTES
Route::middleware(['auth', 'same_school', 'role:Super Admin,School Admin'])->prefix('admin')->group(function () {
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
    Route::post('/exams', [\App\Http\Controllers\ExamController::class, 'store'])->name('admin.exams.store');
    Route::put('/exams/{id}', [\App\Http\Controllers\ExamController::class, 'update'])->name('admin.exams.update');
    Route::delete('/exams/{id}', [\App\Http\Controllers\ExamController::class, 'destroy'])->name('admin.exams.destroy');
    Route::get('/exams/marks', [\App\Http\Controllers\ExamController::class, 'marks'])->name('admin.exams.marks');
    Route::get('/fees', [\App\Http\Controllers\FeeController::class, 'index'])->name('admin.fees');
    Route::post('/fees/categories', [\App\Http\Controllers\Admin\FeeCategoryController::class, 'store'])->name('admin.fees.categories.store');
    Route::delete('/fees/categories/{id}', [\App\Http\Controllers\Admin\FeeCategoryController::class, 'destroy'])->name('admin.fees.categories.destroy');
    Route::post('/fees/structures', [\App\Http\Controllers\Admin\FeeStructureController::class, 'store'])->name('admin.fees.structures.store');
    Route::delete('/fees/structures/{id}', [\App\Http\Controllers\Admin\FeeStructureController::class, 'destroy'])->name('admin.fees.structures.destroy');
    Route::post('/fees/bulk-generate', [\App\Http\Controllers\Admin\FeeInvoiceController::class, 'bulkGenerate'])->name('admin.fees.bulk-generate');
    Route::get('/inventory', [\App\Http\Controllers\InventoryController::class, 'index'])->name('admin.inventory');
    Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('admin.calendar');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports');
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('admin.events');
    Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('admin.announcements');
    Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::delete('/announcements/{id}', [\App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
    Route::get('/payroll', [\App\Http\Controllers\PayrollController::class, 'index'])->name('admin.payroll');
    
    // ADVANCED UPGRADE ROUTES
    Route::prefix('advanced')->name('admin.')->group(function() {
        // Analytics
        Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'chartData'])->name('analytics.data');
        
        // Fee Payment & Challans
        Route::get('/fees/{fee_id}/pay', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class, 'initiatePayment'])->name('fees.pay');
        Route::post('/fees/{fee_id}/jazzcash', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class, 'processJazzCash'])->name('fees.jazzcash');
        Route::post('/fees/{fee_id}/easypaisa', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class, 'processEasyPaisa'])->name('fees.easypaisa');
        Route::get('/fees/receipt/{receipt_id}', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class, 'downloadReceipt'])->name('fees.receipt.download');
        Route::get('/fees/challan/{student_id}', [\App\Http\Controllers\Admin\FeeChallanController::class, 'generate'])->name('fees.challan');
        Route::post('/fees/challan/bulk', [\App\Http\Controllers\Admin\FeeChallanController::class, 'bulkGenerate'])->name('fees.challan.bulk');

        // Student Promotions
        Route::get('/promotions', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'index'])->name('promotions.index');
        Route::post('/promotions/preview', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'preview'])->name('promotions.preview');
        Route::post('/promotions/execute', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'execute'])->name('promotions.execute');
        Route::get('/promotions/rules', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'rules'])->name('promotions.rules');
        Route::post('/promotions/rules', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'saveRule'])->name('promotions.rules.save');

        // Document Generation
        Route::get('/documents', [\App\Http\Controllers\Admin\DocumentController::class, 'index'])->name('documents.index');
        Route::post('/documents/generate', [\App\Http\Controllers\Admin\DocumentController::class, 'generate'])->name('documents.generate');
        Route::get('/documents/templates', [\App\Http\Controllers\Admin\DocumentController::class, 'templates'])->name('documents.templates');
        Route::get('/documents/templates/{id}/edit', [\App\Http\Controllers\Admin\DocumentController::class, 'editTemplate'])->name('documents.templates.edit');
        Route::put('/documents/templates/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'updateTemplate'])->name('documents.templates.update');

        // Staff Leave Management
        Route::get('/staff-leaves', [\App\Http\Controllers\Admin\StaffLeaveController::class, 'index'])->name('staff-leaves.index');
        Route::post('/staff-leaves/{id}/approve', [\App\Http\Controllers\Admin\StaffLeaveController::class, 'approve'])->name('staff-leaves.approve');
        Route::post('/staff-leaves/{id}/reject', [\App\Http\Controllers\Admin\StaffLeaveController::class, 'reject'])->name('staff-leaves.reject');
        Route::post('/staff-leaves/{id}/substitute', [\App\Http\Controllers\Admin\StaffLeaveController::class, 'assignSubstituteManually'])->name('staff-leaves.substitute');
        Route::get('/staff-leaves/substitutes', [\App\Http\Controllers\Admin\StaffLeaveController::class, 'substituteSchedule'])->name('staff-leaves.substitutes');
        Route::get('/staff-leaves/balances', [\App\Http\Controllers\Admin\StaffLeaveController::class, 'leaveBalances'])->name('staff-leaves.balances');
    });

    // Multi-Branch (Super Admin only handled in Controller constructor)
    Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class, ['as' => 'admin']);
    Route::post('/branches/switch', [\App\Http\Controllers\Admin\BranchController::class, 'switchBranch'])->name('admin.branches.switch');
    // AI Modules
    Route::prefix('ai')->name('admin.ai.')->group(function () {
        Route::get('/attendance', [\App\Http\Controllers\Admin\AI\AttendanceAnomalyController::class, 'index'])->name('attendance');
        Route::post('/attendance/predict', [\App\Http\Controllers\Admin\AiAttendanceController::class, 'predict'])->name('attendance.predict');
        Route::post('/attendance/{anomaly}/resolve', [\App\Http\Controllers\Admin\AI\AttendanceAnomalyController::class, 'resolve'])->name('attendance.resolve');
        
        Route::get('/risk', [\App\Http\Controllers\Admin\AiStudentRiskController::class, 'index'])->name('risk');
        Route::post('/risk/analyze', [\App\Http\Controllers\Admin\AiStudentRiskController::class, 'analyze'])->name('risk.analyze');
        
        Route::get('/timetable', [\App\Http\Controllers\Admin\AiTimetableController::class, 'index'])->name('timetable');
        Route::get('/timetable/fetch', [\App\Http\Controllers\Admin\AiTimetableController::class, 'fetch'])->name('timetable.fetch');
        Route::post('/timetable/generate', [\App\Http\Controllers\Admin\AiTimetableController::class, 'generate'])->name('timetable.generate');
        Route::get('/timetable/versions', [\App\Http\Controllers\Admin\AiTimetableController::class, 'getVersions'])->name('timetable.versions');
        Route::post('/timetable/versions/{id}/approve', [\App\Http\Controllers\Admin\AiTimetableController::class, 'approve'])->name('timetable.approve');
        Route::post('/timetable/suggestions', [\App\Http\Controllers\Admin\AiTimetableController::class, 'getSuggestions'])->name('timetable.suggestions');
        Route::post('/timetable/slot/{id}', [\App\Http\Controllers\Admin\AiTimetableController::class, 'updateSlot'])->name('timetable.update');
        Route::get('/timetable/history', [\App\Http\Controllers\Admin\AiTimetableController::class, 'history'])->name('timetable.history');
        
        Route::get('/reports', [\App\Http\Controllers\Admin\AiReportController::class, 'index'])->name('reports');
        Route::post('/reports/generate', [\App\Http\Controllers\Admin\AiReportController::class, 'generate'])->name('reports.generate');
    });
    
    // Roles and Permissions (Super Admin Only)
    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles');
        Route::post('/roles/{id}/permissions', [\App\Http\Controllers\RoleController::class, 'updatePermissions'])->name('admin.roles.permissions.update');
    });

    // Digital Learning Routes
    Route::prefix('digital-learning')->name('admin.digital_learning.')->group(function() {
        Route::get('/notes', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'notesIndex'])->name('notes');
        Route::post('/notes', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'storeNote'])->name('notes.store');
        Route::put('/notes/{id}', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'updateNote'])->name('notes.update');
        Route::delete('/notes/{id}', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'destroyNote'])->name('notes.destroy');

        Route::get('/quizzes', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'quizzesIndex'])->name('quizzes');
        Route::post('/quizzes', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'storeQuiz'])->name('quizzes.store');
        Route::put('/quizzes/{id}', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'updateQuiz'])->name('quizzes.update');
        Route::delete('/quizzes/{id}', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'destroyQuiz'])->name('quizzes.destroy');

        Route::get('/quizzes/{id}/questions', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'manageQuestions'])->name('quizzes.questions');
        Route::post('/quizzes/{id}/questions', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'storeQuestion'])->name('quizzes.questions.store');
        Route::put('/quizzes/{quiz_id}/questions/{question_id}', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'updateQuestion'])->name('quizzes.questions.update');
        Route::delete('/quizzes/{quiz_id}/questions/{question_id}', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

        Route::get('/quizzes/{id}/results', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'quizResults'])->name('quizzes.results');
    });
});

// TEACHER ROUTES
Route::middleware(['auth', 'same_school', 'role:Teacher'])->prefix('teacher')->group(function () {
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
    Route::middleware('teacher_module:exams')->get('/exam-schedule', [\App\Http\Controllers\TeacherPortalController::class, 'examSchedule'])->name('teacher.exam-schedule');

    Route::middleware('teacher_module:leaves')->group(function () {
        Route::get('/student-leaves', [\App\Http\Controllers\TeacherPortalController::class, 'studentLeaves'])->name('teacher.student-leaves');
        Route::post('/student-leaves/{id}/approve', [\App\Http\Controllers\TeacherPortalController::class, 'approveStudentLeave'])->name('teacher.student-leaves.approve');
        Route::post('/student-leaves/{id}/reject', [\App\Http\Controllers\TeacherPortalController::class, 'rejectStudentLeave'])->name('teacher.student-leaves.reject');
    });

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
    
    // Digital Learning Routes
    Route::middleware('teacher_module:digital_learning')->prefix('digital-learning')->name('teacher.digital_learning.')->group(function() {
        Route::get('/notes', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'notesIndex'])->name('notes');
        Route::post('/notes', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'storeNote'])->name('notes.store');
        Route::put('/notes/{id}', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'updateNote'])->name('notes.update');
        Route::delete('/notes/{id}', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'destroyNote'])->name('notes.destroy');

        Route::get('/quizzes', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'quizzesIndex'])->name('quizzes');
        Route::post('/quizzes', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'storeQuiz'])->name('quizzes.store');
        Route::put('/quizzes/{id}', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'updateQuiz'])->name('quizzes.update');
        Route::delete('/quizzes/{id}', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'destroyQuiz'])->name('quizzes.destroy');

        Route::get('/quizzes/{id}/questions', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'manageQuestions'])->name('quizzes.questions');
        Route::post('/quizzes/{id}/questions', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'storeQuestion'])->name('quizzes.questions.store');
        Route::put('/quizzes/{quiz_id}/questions/{question_id}', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'updateQuestion'])->name('quizzes.questions.update');
        Route::delete('/quizzes/{quiz_id}/questions/{question_id}', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

        Route::get('/quizzes/{id}/results', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'quizResults'])->name('quizzes.results');
    });
    
    Route::middleware('teacher_module:messages')->group(function() {
        Route::get('/messages', [\App\Http\Controllers\TeacherPortalController::class, 'messages'])->name('teacher.messages');
        Route::post('/messages', [\App\Http\Controllers\TeacherPortalController::class, 'storeMessage'])->name('teacher.messages.store');
    });
    
    Route::middleware('teacher_module:reports')->get('/reports', [\App\Http\Controllers\TeacherPortalController::class, 'reports'])->name('teacher.reports');

    // Advanced Online Exams
    Route::get('/online-exams', [\App\Http\Controllers\Teacher\OnlineExamController::class, 'index'])->name('teacher.online-exams.index');
    Route::get('/online-exams/create', [\App\Http\Controllers\Teacher\OnlineExamController::class, 'create'])->name('teacher.online-exams.create');
    Route::post('/online-exams', [\App\Http\Controllers\Teacher\OnlineExamController::class, 'store'])->name('teacher.online-exams.store');
    Route::get('/online-exams/{id}/questions', [\App\Http\Controllers\Teacher\OnlineExamController::class, 'questions'])->name('teacher.online-exams.questions');
    Route::post('/online-exams/{id}/questions', [\App\Http\Controllers\Teacher\OnlineExamController::class, 'storeQuestion'])->name('teacher.online-exams.questions.store');
    Route::post('/online-exams/{id}/publish', [\App\Http\Controllers\Teacher\OnlineExamController::class, 'publish'])->name('teacher.online-exams.publish');
    Route::get('/online-exams/{id}/results', [\App\Http\Controllers\Teacher\OnlineExamController::class, 'results'])->name('teacher.online-exams.results');
});

// ─── STUDENT ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:Student', 'same_school'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/attendance', [App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance');
        Route::get('/marks', [App\Http\Controllers\Student\MarksController::class, 'index'])->name('marks');
        Route::get('/fees', [App\Http\Controllers\Student\FeeController::class, 'index'])->name('fees');
        Route::get('/fees/{fee_id}/pay', [\App\Http\Controllers\Student\OnlineFeePaymentController::class, 'initiatePayment'])->name('fees.pay');
        Route::post('/fees/{fee_id}/jazzcash', [\App\Http\Controllers\Student\OnlineFeePaymentController::class, 'processJazzCash'])->name('fees.jazzcash');
        Route::post('/fees/{fee_id}/easypaisa', [\App\Http\Controllers\Student\OnlineFeePaymentController::class, 'processEasyPaisa'])->name('fees.easypaisa');
        Route::get('/timetable', [App\Http\Controllers\Student\TimetableController::class, 'index'])->name('timetable');
        Route::get('/assignments', [App\Http\Controllers\Student\AssignmentController::class, 'index'])->name('assignments');
        Route::post('/assignments/{id}/submit', [App\Http\Controllers\Student\AssignmentController::class, 'submit'])->name('assignments.submit');

        // Digital Learning Routes
        Route::prefix('digital-learning')->name('digital_learning.')->group(function() {
            Route::get('/notes', [\App\Http\Controllers\Student\DigitalLearningController::class, 'notesIndex'])->name('notes');
            Route::get('/quizzes', [\App\Http\Controllers\Student\DigitalLearningController::class, 'quizzesIndex'])->name('quizzes');
            Route::get('/quizzes/{id}/take', [\App\Http\Controllers\Student\DigitalLearningController::class, 'takeQuiz'])->name('quizzes.take');
            Route::post('/quizzes/{id}/submit', [\App\Http\Controllers\Student\DigitalLearningController::class, 'submitQuiz'])->name('quizzes.submit');
        });
        Route::get('/announcements', [App\Http\Controllers\Student\AnnouncementController::class, 'index'])->name('announcements');
        Route::get('/report-card', [App\Http\Controllers\Student\ReportCardController::class, 'index'])->name('report-card');
        Route::get('/report-card/download', [App\Http\Controllers\Student\ReportCardController::class, 'download'])->name('report-card.download');
        Route::get('/exam-schedule', [App\Http\Controllers\Student\ExamController::class, 'index'])->name('exam-schedule');
        Route::get('/library', [App\Http\Controllers\Student\LibraryController::class, 'index'])->name('library');
        Route::get('/transport', [App\Http\Controllers\Student\TransportController::class, 'index'])->name('transport');
        Route::get('/health-records', [App\Http\Controllers\Student\HealthController::class, 'index'])->name('health-records');
        Route::get('/leave-requests', [App\Http\Controllers\Student\LeaveController::class, 'index'])->name('leave.index');
        Route::post('/leave-requests', [App\Http\Controllers\Student\LeaveController::class, 'store'])->name('leave.store');
        

        Route::get('/messages', [App\Http\Controllers\Student\MessageController::class, 'index'])->name('messages');
        Route::post('/messages', [App\Http\Controllers\Student\MessageController::class, 'send'])->name('messages.send');
        Route::get('/profile', [App\Http\Controllers\Student\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');

        // Advanced Online Exams
        Route::get('/online-exams', [\App\Http\Controllers\Student\OnlineExamController::class, 'index'])->name('online-exams.index');
        Route::get('/online-exams/{id}/take', [\App\Http\Controllers\Student\OnlineExamController::class, 'start'])->name('online-exams.start');
        Route::post('/online-exams/{id}/submit', [\App\Http\Controllers\Student\OnlineExamController::class, 'submit'])->name('online-exams.submit');
        Route::get('/online-exams/{id}/result', [\App\Http\Controllers\Student\OnlineExamController::class, 'result'])->name('online-exams.result');
    });

// ─── PARENT ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:Parent', 'same_school'])
    ->prefix('parent')
    ->name('parent.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\ParentPortal\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/children', [App\Http\Controllers\ParentPortal\DashboardController::class, 'children'])->name('children');
        Route::get('/children/{student_id}/attendance', [App\Http\Controllers\ParentPortal\AttendanceController::class, 'show'])->name('child.attendance');
        Route::get('/children/{student_id}/marks', [App\Http\Controllers\ParentPortal\MarksController::class, 'show'])->name('child.marks');
        Route::get('/children/{student_id}/fees', [App\Http\Controllers\ParentPortal\FeeController::class, 'show'])->name('child.fees');
        Route::get('/children/{student_id}/timetable', [App\Http\Controllers\ParentPortal\TimetableController::class, 'show'])->name('child.timetable');
        Route::get('/children/{student_id}/assignments', [App\Http\Controllers\ParentPortal\AssignmentController::class, 'show'])->name('child.assignments');
        Route::get('/children/{student_id}/exam-schedule', [App\Http\Controllers\ParentPortal\ExamController::class, 'show'])->name('child.exam-schedule');
        Route::get('/children/{student_id}/report-card', [App\Http\Controllers\ParentPortal\ReportCardController::class, 'show'])->name('child.report-card');
        Route::get('/children/{student_id}/leave', [App\Http\Controllers\ParentPortal\LeaveController::class, 'show'])->name('child.leave');
        Route::post('/children/{student_id}/leave', [App\Http\Controllers\ParentPortal\LeaveController::class, 'store'])->name('child.leave.store');
        Route::get('/announcements', [App\Http\Controllers\ParentPortal\AnnouncementController::class, 'index'])->name('announcements');
        Route::get('/messages', [App\Http\Controllers\ParentPortal\MessageController::class, 'index'])->name('messages');
        Route::post('/messages', [App\Http\Controllers\ParentPortal\MessageController::class, 'send'])->name('messages.send');
        Route::get('/profile', [App\Http\Controllers\ParentPortal\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ParentPortal\ProfileController::class, 'update'])->name('profile.update');
    });

// API ROUTES (Scoped internally in controllers based on auth()->user()->role_id)
Route::middleware(['auth', 'same_school'])->prefix('api')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index'])->name('api.dashboard');
    Route::get('/students', [\App\Http\Controllers\Api\StudentController::class, 'index'])->name('api.students.index');
    Route::post('/students', [\App\Http\Controllers\Api\StudentController::class, 'store'])->name('api.students.store');
    Route::put('/students/{id}', [\App\Http\Controllers\Api\StudentController::class, 'update'])->name('api.students.update');
    Route::delete('/students/{id}', [\App\Http\Controllers\Api\StudentController::class, 'destroy'])->name('api.students.destroy');
    Route::get('/teachers', [\App\Http\Controllers\Api\TeacherController::class, 'index'])->name('api.teachers.index');
    Route::post('/teachers', [\App\Http\Controllers\Api\TeacherController::class, 'store'])->name('api.teachers.store');
    Route::put('/teachers/{id}', [\App\Http\Controllers\Api\TeacherController::class, 'update'])->name('api.teachers.update');
    Route::delete('/teachers/{id}', [\App\Http\Controllers\Api\TeacherController::class, 'destroy'])->name('api.teachers.destroy');
    Route::get('/classes/filters', [\App\Http\Controllers\Api\ClassController::class, 'filters'])->name('api.classes.filters');
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

// EasyPaisa Callback (Exclude CSRF)
Route::post('/admin/fees/easypaisa/callback', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class, 'easyPaisaCallback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('admin.fees.easypaisa.callback');

Route::post('/student/fees/easypaisa/callback', [\App\Http\Controllers\Student\OnlineFeePaymentController::class, 'easyPaisaCallback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('student.fees.easypaisa.callback');

// Generic Shared Routes
Route::middleware(['auth', 'same_school'])->group(function () {
    Route::get('/fees/receipt/{id}', [\App\Http\Controllers\FeeReceiptController::class, 'download'])->name('fees.receipt.download');
});
