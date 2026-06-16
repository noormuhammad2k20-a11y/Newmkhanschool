<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');

// Public Verification Route
Route::get('/verify/document/{uuid}', [\App\Http\Controllers\VerificationController::class, 'verifyQR'])->name('verify.qr');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/', function () {
    $guards = ['admin', 'teacher', 'student', 'parent', 'accountant'];
    foreach ($guards as $guard) {
        if (auth()->guard($guard)->check()) {
            $roleId = auth()->guard($guard)->user()->role_id;
            if (in_array($roleId, [1, 2])) return redirect()->route('admin.dashboard');
            if ($roleId == 3) return redirect()->route('teacher.dashboard');
            if ($roleId == 4) return redirect()->route('student.dashboard');
            if ($roleId == 5) return redirect()->route('parent.dashboard');
            if ($roleId == 6) return redirect()->route('accountant.dashboard');
        }
    }
    return redirect()->route('login');
});

// ADMIN ROUTES
Route::middleware(['auth:admin', 'same_school', 'role:Super Admin,School Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/ai/risk-analysis', function() {
        return view('admin.ai.risk-analysis');
    })->name('admin.ai.risk-analysis');
    Route::get('/timetables/generate', function() {
        return view('admin.timetables.generate');
    })->name('admin.timetables.generate');
    Route::get('/students', [\App\Http\Controllers\StudentController::class, 'index'])->name('admin.students');
    Route::get('/students/create', [\App\Http\Controllers\StudentController::class, 'create'])->name('admin.students.create');
    Route::get('/students/import', function() {
        return view('students.import');
    })->name('admin.students.import');
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
    Route::put('/academics/subjects/{id}', [\App\Http\Controllers\AcademicController::class, 'updateSubject'])->name('admin.academics.subjects.update');
    Route::delete('/academics/subjects/{id}', [\App\Http\Controllers\AcademicController::class, 'destroySubject'])->name('admin.academics.subjects.destroy');
    Route::post('/academics/assignments', [\App\Http\Controllers\AcademicController::class, 'storeAssignment'])->name('admin.academics.assignments.store');
    Route::delete('/academics/assignments/{id}', [\App\Http\Controllers\AcademicController::class, 'destroyAssignment'])->name('admin.academics.assignments.destroy');
    Route::get('/classes/timetable', [\App\Http\Controllers\ClassController::class, 'timetable'])->name('admin.classes.timetable');
    Route::get('/attendance/mark', [\App\Http\Controllers\AttendanceController::class, 'mark'])->name('admin.attendance.mark');
    Route::get('/attendance/teachers', [\App\Http\Controllers\AttendanceController::class, 'teacher'])->name('admin.attendance.teacher');
    Route::get('/exams', [\App\Http\Controllers\ExamController::class, 'index'])->name('admin.exams');
    Route::post('/exams', [\App\Http\Controllers\ExamController::class, 'store'])->name('admin.exams.store');
    Route::get('/exams/classes/{class_id}/subjects', [\App\Http\Controllers\ExamController::class, 'getSubjectsByClass'])->name('admin.exams.class-subjects');
    Route::get('/exams/classes/{class_id}/type/{exam_type}', [\App\Http\Controllers\ExamController::class, 'getEventSchedules'])->name('admin.exams.event-schedules');
    Route::put('/exams/{id}', [\App\Http\Controllers\ExamController::class, 'update'])->name('admin.exams.update');
    Route::delete('/exams/{id}', [\App\Http\Controllers\ExamController::class, 'destroy'])->name('admin.exams.destroy');
    Route::get('/exams/marks', [\App\Http\Controllers\ExamController::class, 'marks'])->name('admin.exams.marks');
    Route::get('/fees', [\App\Http\Controllers\FeeController::class, 'index'])->name('admin.fees');
    Route::post('/fees/categories', [\App\Http\Controllers\Admin\FeeCategoryController::class, 'store'])->name('admin.fees.categories.store');
    Route::delete('/fees/categories/{id}', [\App\Http\Controllers\Admin\FeeCategoryController::class, 'destroy'])->name('admin.fees.categories.destroy');
    Route::post('/fees/structures', [\App\Http\Controllers\Admin\FeeStructureController::class, 'store'])->name('admin.fees.structures.store');
    Route::post('/fees/structures/bulk', [\App\Http\Controllers\Admin\FeeStructureController::class, 'bulkUpdate'])->name('admin.fees.structures.bulk');
    Route::delete('/fees/structures/{id}', [\App\Http\Controllers\Admin\FeeStructureController::class, 'destroy'])->name('admin.fees.structures.destroy');
    Route::post('/fees/bulk-generate', [\App\Http\Controllers\Admin\FeeInvoiceController::class, 'bulkGenerate'])->name('admin.fees.bulk-generate');
    Route::get('/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('admin.inventory');
    Route::get('/inventory/create', [\App\Http\Controllers\Admin\InventoryController::class, 'create'])->name('admin.inventory.create');
    Route::post('/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'store'])->name('admin.inventory.store');
    Route::get('/inventory/low-stock', [\App\Http\Controllers\Admin\InventoryController::class, 'lowStock'])->name('admin.inventory.low-stock');
    Route::get('/inventory/{id}', [\App\Http\Controllers\Admin\InventoryController::class, 'show'])->name('admin.inventory.show');
    Route::get('/inventory/{id}/edit', [\App\Http\Controllers\Admin\InventoryController::class, 'edit'])->name('admin.inventory.edit');
    Route::put('/inventory/{id}', [\App\Http\Controllers\Admin\InventoryController::class, 'update'])->name('admin.inventory.update');
    Route::delete('/inventory/{id}', [\App\Http\Controllers\Admin\InventoryController::class, 'destroy'])->name('admin.inventory.destroy');
    Route::get('/inventory/{id}/stock-in', [\App\Http\Controllers\Admin\InventoryController::class, 'stockInForm'])->name('admin.inventory.stock-in.form');
    Route::post('/inventory/{id}/stock-in', [\App\Http\Controllers\Admin\InventoryController::class, 'stockIn'])->name('admin.inventory.stock-in');
    Route::get('/inventory/{id}/stock-out', [\App\Http\Controllers\Admin\InventoryController::class, 'stockOutForm'])->name('admin.inventory.stock-out.form');
    Route::post('/inventory/{id}/stock-out', [\App\Http\Controllers\Admin\InventoryController::class, 'stockOut'])->name('admin.inventory.stock-out');
    Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('admin.calendar');
    Route::get('/payroll', [\App\Http\Controllers\PayrollController::class, 'index'])->name('admin.payroll');
    Route::post('/payroll', [\App\Http\Controllers\PayrollController::class, 'store'])->name('admin.payroll.store');
    
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
        Route::get('/promotions/preview', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'preview'])->name('promotions.preview');
        Route::post('/promotions/execute', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'execute'])->name('promotions.execute');
        Route::get('/promotions/rules', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'rules'])->name('promotions.rules');
        Route::post('/promotions/rules', [\App\Http\Controllers\Admin\StudentPromotionController::class, 'saveRule'])->name('promotions.rules.save');



        // Document Generation
        Route::get('/documents', [\App\Http\Controllers\Admin\DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [\App\Http\Controllers\Admin\DocumentController::class, 'create'])->name('documents.create');
        Route::get('/documents/select-template/{student}', [\App\Http\Controllers\Admin\DocumentController::class, 'selectTemplate'])->name('documents.select-template');
        Route::get('/documents/ajax-search', [\App\Http\Controllers\Admin\DocumentController::class, 'ajaxSearch'])->name('documents.ajax-search');
        Route::post('/documents/preview', [\App\Http\Controllers\Admin\DocumentController::class, 'preview'])->name('documents.preview');
        Route::post('/documents/generate', [\App\Http\Controllers\Admin\DocumentController::class, 'generate'])->name('documents.generate');
        Route::get('/documents/download/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'download'])->name('documents.download');
        Route::post('/documents/bulk-destroy', [\App\Http\Controllers\Admin\DocumentController::class, 'bulkDestroy'])->name('documents.bulk-destroy');
        Route::delete('/documents/destroy-all', [\App\Http\Controllers\Admin\DocumentController::class, 'destroyAll'])->name('documents.destroy-all');
        Route::delete('/documents/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::get('/documents/student-history/{student}', [\App\Http\Controllers\Admin\DocumentController::class, 'studentHistory'])->name('documents.student-history');
        Route::get('/documents/templates', [\App\Http\Controllers\Admin\DocumentController::class, 'templates'])->name('documents.templates');
        Route::get('/documents/templates/{id}/edit', [\App\Http\Controllers\Admin\DocumentController::class, 'editTemplate'])->name('documents.templates.edit');
        Route::put('/documents/templates/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'updateTemplate'])->name('documents.templates.update');
        
        Route::get('/documents/signatures', [\App\Http\Controllers\Admin\DocumentController::class, 'signatures'])->name('documents.signatures');
        Route::post('/documents/signatures', [\App\Http\Controllers\Admin\DocumentController::class, 'updateSignature'])->name('documents.signatures.update');

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
        Route::post('/quizzes/{id}/questions/bulk', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'bulkStoreQuestions'])->name('quizzes.questions.bulk_store');
        Route::put('/quizzes/{quiz_id}/questions/{question_id}', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'updateQuestion'])->name('quizzes.questions.update');
        Route::delete('/quizzes/{quiz_id}/questions/{question_id}', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

        Route::get('/quizzes/{id}/results', [\App\Http\Controllers\Admin\DigitalLearningController::class, 'quizResults'])->name('quizzes.results');
    });
});

// ACCOUNTANT ROUTES
Route::middleware(['auth:accountant', 'same_school', 'role:Accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Accountant\AccountantDashboardController::class, 'index'])->name('dashboard');

    Route::resource('fees', \App\Http\Controllers\Accountant\AccountantFeeController::class);
    Route::post('fees/{fee}/collect', [\App\Http\Controllers\Accountant\AccountantFeeController::class, 'collectPayment'])->name('fees.collect');
    Route::get('fees/{fee}/receipt', [\App\Http\Controllers\Accountant\AccountantFeeController::class, 'printReceipt'])->name('fees.receipt');
    Route::post('fees/generate-challans', [\App\Http\Controllers\Accountant\AccountantFeeController::class, 'generateChallans'])->name('fees.generate-challans');
    Route::get('transactions', [\App\Http\Controllers\Accountant\FeeTransactionController::class, 'index'])->name('transactions.index');

    Route::get('fee-structure', [\App\Http\Controllers\Accountant\FeeStructureController::class, 'index'])->name('fee-structure.index');
    Route::resource('fee-categories', \App\Http\Controllers\Accountant\FeeCategoryController::class);

    Route::get('defaulters', [\App\Http\Controllers\Accountant\DefaulterController::class, 'index'])->name('defaulters.index');
    Route::post('defaulters/remind-all', [\App\Http\Controllers\Accountant\DefaulterController::class, 'sendReminders'])->name('defaulters.remind-all');
    Route::post('defaulters/{student}/remind', [\App\Http\Controllers\Accountant\DefaulterController::class, 'remind'])->name('defaulters.remind');

    Route::get('payroll', [\App\Http\Controllers\Accountant\PayrollController::class, 'index'])->name('payroll.index');
    Route::post('payroll/generate', [\App\Http\Controllers\Accountant\PayrollController::class, 'generate'])->name('payroll.generate');
    Route::put('payroll/{payroll}', [\App\Http\Controllers\Accountant\PayrollController::class, 'update'])->name('payroll.update');
    Route::post('payroll/{payroll}/mark-paid', [\App\Http\Controllers\Accountant\PayrollController::class, 'markPaid'])->name('payroll.mark-paid');
    Route::get('payroll/{payroll}/slip', [\App\Http\Controllers\Accountant\PayrollController::class, 'slip'])->name('payroll.slip');

    Route::resource('tax-slips', \App\Http\Controllers\Accountant\TaxSlipController::class);
    Route::get('tax-slips/{taxSlip}/pdf', [\App\Http\Controllers\Accountant\TaxSlipController::class, 'pdf'])->name('tax-slips.pdf');

    Route::resource('expenses', \App\Http\Controllers\Accountant\ExpenseController::class);
    Route::resource('expense-categories', \App\Http\Controllers\Accountant\ExpenseCategoryController::class);
    Route::post('expenses/{expense}/status', [\App\Http\Controllers\Accountant\ExpenseController::class, 'updateStatus'])->name('expenses.status');
    Route::get('expenses/{expense}/voucher', [\App\Http\Controllers\Accountant\ExpenseController::class, 'voucher'])->name('expenses.voucher');

    Route::resource('bank-accounts', \App\Http\Controllers\Accountant\BankAccountController::class);
    Route::post('bank-accounts/{bankAccount}/transaction', [\App\Http\Controllers\Accountant\BankAccountController::class, 'transaction'])->name('bank-accounts.transaction');

    Route::get('cash-book', [\App\Http\Controllers\Accountant\CashBookController::class, 'index'])->name('cash-book.index');
    Route::post('cash-book', [\App\Http\Controllers\Accountant\CashBookController::class, 'store'])->name('cash-book.store');
    Route::get('cash-book/print', [\App\Http\Controllers\Accountant\CashBookController::class, 'print'])->name('cash-book.print');

    Route::get('inventory-purchases', [\App\Http\Controllers\Accountant\InventoryPurchaseController::class, 'index'])->name('inventory-purchases.index');
    Route::post('inventory-purchases', [\App\Http\Controllers\Accountant\InventoryPurchaseController::class, 'store'])->name('inventory-purchases.store');



    Route::get('profile', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\Accountant\AccountantProfileController::class, 'update'])->name('profile.update');
});

// TEACHER ROUTES
Route::middleware(['auth:teacher', 'same_school', 'role:Teacher'])->prefix('teacher')->group(function () {
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
        
        // AJAX Endpoints
        Route::get('/api/sections', [\App\Http\Controllers\TeacherPortalController::class, 'getSections'])->name('teacher.api.sections');
        Route::get('/api/subjects', [\App\Http\Controllers\TeacherPortalController::class, 'getSubjects'])->name('teacher.api.subjects');
        Route::get('/api/exams', [\App\Http\Controllers\TeacherPortalController::class, 'getExams'])->name('teacher.api.exams');
        Route::post('/api/marks/students', [\App\Http\Controllers\TeacherPortalController::class, 'getStudentsForMarks'])->name('teacher.api.marks.students');
    });
    
    Route::middleware('teacher_module:assignments')->group(function() {
        Route::get('/assignments', [\App\Http\Controllers\TeacherPortalController::class, 'assignments'])->name('teacher.assignments');
        Route::post('/assignments', [\App\Http\Controllers\TeacherPortalController::class, 'storeAssignment'])->name('teacher.assignments.store');
        
        // AI Auto Grader Routes
        Route::get('/ai-grader', [\App\Http\Controllers\Teacher\AIGraderController::class, 'index'])->name('teacher.ai-grader');
        Route::get('/assignments/{assignment_id}/submissions', [\App\Http\Controllers\Teacher\AIGraderController::class, 'showSubmissions'])->name('teacher.assignments.submissions');
        Route::post('/submissions/{submission_id}/grade-ai', [\App\Http\Controllers\Teacher\AIGraderController::class, 'gradeWithAI'])->name('teacher.submissions.grade-ai');
        Route::post('/assignments/{assignment_id}/bulk-grade-ai', [\App\Http\Controllers\Teacher\AIGraderController::class, 'bulkGradeWithAI'])->name('teacher.submissions.bulk-grade-ai');
        Route::post('/submissions/{submission_id}/apply-grade', [\App\Http\Controllers\Teacher\AIGraderController::class, 'applyGrade'])->name('teacher.submissions.apply-grade');
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
    

    Route::middleware('teacher_module:performance')->get('/performance', [\App\Http\Controllers\TeacherPortalController::class, 'performance'])->name('teacher.performance');
    
    Route::middleware('teacher_module:profile')->group(function() {
        Route::get('/profile', [\App\Http\Controllers\TeacherPortalController::class, 'profile'])->name('teacher.profile');
        Route::post('/profile', [\App\Http\Controllers\TeacherPortalController::class, 'updateProfile'])->name('teacher.profile.update');
    });
    
    // Digital Learning Routes
    Route::prefix('digital-learning')->name('teacher.digital_learning.')->group(function() {
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
        Route::post('/quizzes/{id}/questions/bulk', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'bulkStoreQuestions'])->name('quizzes.questions.bulk_store');
        Route::put('/quizzes/{quiz_id}/questions/{question_id}', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'updateQuestion'])->name('quizzes.questions.update');
        Route::delete('/quizzes/{quiz_id}/questions/{question_id}', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

        Route::get('/quizzes/{id}/results', [\App\Http\Controllers\Teacher\DigitalLearningController::class, 'quizResults'])->name('quizzes.results');
    });
    
    Route::middleware('teacher_module:messages')->group(function() {
        Route::get('/messages', [\App\Http\Controllers\TeacherPortalController::class, 'messages'])->name('teacher.messages');
        Route::post('/messages', [\App\Http\Controllers\TeacherPortalController::class, 'storeMessage'])->name('teacher.messages.store');
    });
    
    // Seating Plan Routes
    Route::prefix('seating')->name('teacher.seating.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Teacher\SeatingPlanController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Teacher\SeatingPlanController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Teacher\SeatingPlanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Teacher\SeatingPlanController::class, 'edit'])->name('edit');
        Route::post('/{id}/update-grid', [\App\Http\Controllers\Teacher\SeatingPlanController::class, 'updateGrid'])->name('update-grid');
        Route::post('/{id}/auto-arrange', [\App\Http\Controllers\Teacher\SeatingPlanController::class, 'autoArrange'])->name('auto-arrange');
        Route::get('/{id}', [\App\Http\Controllers\Teacher\SeatingPlanController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\Teacher\SeatingPlanController::class, 'destroy'])->name('destroy');
    });
    


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
Route::middleware(['auth:student', 'role:Student', 'same_school'])
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

        Route::get('/exam-schedule', [\App\Http\Controllers\Student\ExamController::class, 'index'])->name('exam-schedule');
        Route::get('/api/exam-statuses', [\App\Http\Controllers\Student\ExamController::class, 'getExamStatuses'])->name('api.exam-statuses');
        Route::get('/library', [\App\Http\Controllers\Student\LibraryController::class, 'index'])->name('library');
        Route::get('/transport', [\App\Http\Controllers\Student\TransportController::class, 'index'])->name('transport');
        Route::get('/health-records', [App\Http\Controllers\Student\HealthController::class, 'index'])->name('health-records');
        Route::get('/leave-requests', [App\Http\Controllers\Student\LeaveController::class, 'index'])->name('leave.index');
        Route::post('/leave-requests', [App\Http\Controllers\Student\LeaveController::class, 'store'])->name('leave.store');

        // S-02: Progress Timeline
        Route::get('/progress', [App\Http\Controllers\Student\DashboardController::class, 'progress'])->name('progress');
        // S-08: Quiz Results
        Route::get('/quiz-results', [App\Http\Controllers\Student\DashboardController::class, 'quizResults'])->name('quiz-results');
        

        Route::get('/messages', [App\Http\Controllers\Student\MessageController::class, 'index'])->name('messages');
        Route::post('/messages', [App\Http\Controllers\Student\MessageController::class, 'send'])->name('messages.send');
        
        Route::get('/notifications', [\App\Http\Controllers\Student\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Student\NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Student\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::get('/notifications/unread-count', [\App\Http\Controllers\Student\NotificationController::class, 'unreadCount'])->name('notifications.count');
        
        Route::get('/id-card/download', [\App\Http\Controllers\Student\IDCardController::class, 'download'])->name('id-card.download');

        Route::get('/profile', [App\Http\Controllers\Student\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');

        // Advanced Online Exams
        Route::get('/online-exams', [\App\Http\Controllers\Student\OnlineExamController::class, 'index'])->name('online-exams.index');
        Route::get('/online-exams/{id}/take', [\App\Http\Controllers\Student\OnlineExamController::class, 'start'])->name('online-exams.start');
        Route::post('/online-exams/{id}/submit', [\App\Http\Controllers\Student\OnlineExamController::class, 'submit'])->name('online-exams.submit');
        Route::get('/online-exams/{id}/result', [\App\Http\Controllers\Student\OnlineExamController::class, 'result'])->name('online-exams.result');
    });

// ─── PARENT ───────────────────────────────────────────────────────────────────
Route::middleware(['auth:parent', 'role:Parent', 'same_school'])
    ->prefix('parent')
    ->name('parent.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\ParentPortal\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/children', [App\Http\Controllers\ParentPortal\DashboardController::class, 'children'])->name('children');
        Route::get('/children/{student_id}/attendance', [App\Http\Controllers\ParentPortal\AttendanceController::class, 'show'])->name('child.attendance');
        Route::get('/children/{student_id}/marks', [App\Http\Controllers\ParentPortal\MarksController::class, 'show'])->name('child.marks');
        Route::get('/children/{student_id}/fees', [App\Http\Controllers\ParentPortal\FeeController::class, 'show'])->name('child.fees');
        Route::get('/children/{student_id}/fees/{fee_id}/pay', [App\Http\Controllers\ParentPortal\FeePaymentController::class, 'showPaymentForm'])->name('child.fees.pay');
        Route::post('/children/{student_id}/fees/{fee_id}/process', [App\Http\Controllers\ParentPortal\FeePaymentController::class, 'processPayment'])->name('child.fees.process');
        Route::get('/children/{student_id}/fees/{fee_id}/receipt', [App\Http\Controllers\ParentPortal\FeePaymentController::class, 'receipt'])->name('child.fees.receipt');
        // S-03: PDF Receipt Download
        Route::get('/children/{student_id}/fees/{fee_id}/receipt/pdf', [App\Http\Controllers\ParentPortal\FeePaymentController::class, 'downloadReceiptPdf'])->name('child.fees.receipt.pdf');
        Route::get('/children/{student_id}/timetable', [App\Http\Controllers\ParentPortal\TimetableController::class, 'show'])->name('child.timetable');
        Route::get('/children/{student_id}/assignments', [App\Http\Controllers\ParentPortal\AssignmentController::class, 'show'])->name('child.assignments');
        Route::get('/children/{student_id}/exam-schedule', [App\Http\Controllers\ParentPortal\ExamController::class, 'show'])->name('child.exam-schedule');

        Route::get('/children/{student_id}/leave', [App\Http\Controllers\ParentPortal\LeaveController::class, 'show'])->name('child.leave');
        Route::post('/children/{student_id}/leave', [App\Http\Controllers\ParentPortal\LeaveController::class, 'store'])->name('child.leave.store');

        Route::get('/messages', [App\Http\Controllers\ParentPortal\MessageController::class, 'index'])->name('messages');
        Route::post('/messages', [App\Http\Controllers\ParentPortal\MessageController::class, 'send'])->name('messages.send');
        Route::get('/profile', [App\Http\Controllers\ParentPortal\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ParentPortal\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/children/{student_id}/online-exams',           [App\Http\Controllers\ParentPortal\OnlineExamController::class, 'index'])->name('child.online-exams.index');
        Route::get('/children/{student_id}/online-exams/{exam_id}', [App\Http\Controllers\ParentPortal\OnlineExamController::class, 'result'])->name('child.online-exams.result');

        Route::get('/notifications',              [App\Http\Controllers\ParentPortal\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read',   [App\Http\Controllers\ParentPortal\NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all',    [App\Http\Controllers\ParentPortal\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::get('/notifications/unread-count', [App\Http\Controllers\ParentPortal\NotificationController::class, 'unreadCount'])->name('notifications.count');
    });

// API ROUTES (Scoped internally in controllers based on auth()->user()->role_id)
Route::middleware(['auth:admin,teacher,student,parent,accountant', 'same_school'])->prefix('api')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index'])->name('api.dashboard');
    
    Route::get('/students', [\App\Http\Controllers\Api\StudentController::class, 'index'])->name('api.students.index');
    Route::post('/students', [\App\Http\Controllers\Api\StudentController::class, 'store'])->name('api.students.store');
    Route::put('/students/{id}', [\App\Http\Controllers\Api\StudentController::class, 'update'])->name('api.students.update');
    Route::delete('/students/{id}', [\App\Http\Controllers\Api\StudentController::class, 'destroy'])->name('api.students.destroy');
    Route::post('/students/{id}/restore', [\App\Http\Controllers\Api\StudentController::class, 'restore'])->name('api.students.restore');
    Route::get('/teachers', [\App\Http\Controllers\Api\TeacherController::class, 'index'])->name('api.teachers.index');
    Route::post('/teachers', [\App\Http\Controllers\Api\TeacherController::class, 'store'])->name('api.teachers.store');
    Route::put('/teachers/{id}', [\App\Http\Controllers\Api\TeacherController::class, 'update'])->name('api.teachers.update');
    Route::delete('/teachers/{id}', [\App\Http\Controllers\Api\TeacherController::class, 'destroy'])->name('api.teachers.destroy');
    Route::get('/classes/filters', [\App\Http\Controllers\Api\ClassController::class, 'filters'])->name('api.classes.filters');
    Route::get('/classes/timetable', [\App\Http\Controllers\Api\ClassController::class, 'timetable'])->name('api.classes.timetable');
    Route::get('/attendance', [\App\Http\Controllers\Api\AttendanceController::class, 'index'])->name('api.attendance.index');
    Route::post('/attendance', [\App\Http\Controllers\Api\AttendanceController::class, 'store'])->name('api.attendance.store');
    Route::get('/teacher-attendance/dashboard', [\App\Http\Controllers\Api\TeacherAttendanceController::class, 'dashboard'])->name('api.teacher-attendance.dashboard');
    Route::get('/teacher-attendance/roster', [\App\Http\Controllers\Api\TeacherAttendanceController::class, 'roster'])->name('api.teacher-attendance.roster');
    Route::post('/teacher-attendance/mark', [\App\Http\Controllers\Api\TeacherAttendanceController::class, 'markAttendance'])->name('api.teacher-attendance.mark');
    Route::put('/teacher-attendance/leaves/{id}/status', [\App\Http\Controllers\Api\TeacherAttendanceController::class, 'updateLeaveStatus'])->name('api.teacher-attendance.leaves.status');
    Route::get('/exams', [\App\Http\Controllers\Api\ExamController::class, 'index'])->name('api.exams');
    Route::get('/exams/marks', [\App\Http\Controllers\Api\ExamController::class, 'getMarks'])->name('api.exams.marks');
    Route::post('/exams/marks', [\App\Http\Controllers\Api\ExamController::class, 'storeMarks'])->name('api.exams.marks.store');
    Route::get('/fees', [\App\Http\Controllers\Api\FeeController::class, 'index'])->name('api.fees');

});

// EasyPaisa Callback (Exclude CSRF)
Route::post('/admin/fees/easypaisa/callback', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class, 'easyPaisaCallback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('admin.fees.easypaisa.callback');

Route::post('/student/fees/easypaisa/callback', [\App\Http\Controllers\Student\OnlineFeePaymentController::class, 'easyPaisaCallback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('student.fees.easypaisa.callback');

// Generic Shared Routes
Route::middleware(['auth:admin,teacher,student,parent,accountant', 'same_school'])->group(function () {
    Route::get('/fees/receipt/{id}', [\App\Http\Controllers\FeeReceiptController::class, 'download'])->name('fees.receipt.download');
});



Route::get('/verify-certificate/{uuid}', [\App\Http\Controllers\CertificateVerificationController::class, 'verify'])->name('verify.certificate');

Route::get('/student/achievements', [\App\Http\Controllers\StudentBadgeController::class, 'index'])->name('student.achievements')->middleware(['auth:student']);



Route::get('/portfolio/{student_id}', [\App\Http\Controllers\StudentPortfolioController::class, 'show'])->name('public.portfolio');
Route::get('/student/portfolio', [\App\Http\Controllers\StudentPortfolioController::class, 'myPortfolio'])->name('student.portfolio.index')->middleware(['auth:student']);


Route::get('/student/portfolio', [\App\Http\Controllers\StudentPortfolioController::class, 'myPortfolio'])->name('student.portfolio.index')->middleware(['auth:student']);







Route::get('/student/portfolio/{id}/resume', [\App\Http\Controllers\StudentPortfolioController::class, 'downloadResume'])->name('student.portfolio.resume');

// AI Auto Grader API Endpoint
Route::post('/api/assignments/auto-grade', [\App\Http\Controllers\Api\AssignmentController::class, 'autoGrade']);
