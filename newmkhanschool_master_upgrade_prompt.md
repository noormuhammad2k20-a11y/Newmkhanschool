# Newmkhanschool — Full Master Upgrade Prompt
**System:** Laravel (PHP 8.2) · MariaDB 10.4 · Existing Theme Must Be Preserved  
**Repo:** https://github.com/noormuhammad2k20-a11y/Newmkhanschool  
**Database:** newschool

---

## ⚠️ ABSOLUTE RULE — READ BEFORE EVERYTHING ELSE

> **Do NOT change, remove, or override any existing theme, CSS classes, layout structure, sidebar, navbar, or design elements.**
> Every new page, controller, migration, and component must inherit and strictly match the existing theme.
> Use the same `@extends('layouts.app')` (or whatever the master layout is), the same sidebar partial, the same card classes, table classes, button classes, and badge patterns already used in the project.
> When in doubt about styling: copy the pattern from an existing working page.

---

## PHASE 1 — FIX DATABASE STRUCTURE

### 1.1 Remove Duplicate Tables

**Problem:** Two attendance tables exist (`attendance` and `student_attendances`). Two library tables exist (`books` and `library_books`).

Run these migrations in order:

```sql
-- Step 1: Migrate orphaned data from old attendance table into student_attendances
-- (Only insert rows where no matching student_id + date already exists)
INSERT INTO student_attendances (student_id, academic_year_id, date, status, marked_by, created_at)
SELECT a.student_id, 1, a.date, a.status, a.marked_by, NOW()
FROM attendance a
WHERE NOT EXISTS (
  SELECT 1 FROM student_attendances sa
  WHERE sa.student_id = a.student_id AND sa.date = a.date
);

-- Step 2: Drop old attendance table
DROP TABLE IF EXISTS attendance;

-- Step 3: Add missing columns to library_books
ALTER TABLE library_books
  ADD COLUMN IF NOT EXISTS isbn VARCHAR(20) DEFAULT NULL AFTER author,
  ADD COLUMN IF NOT EXISTS publisher VARCHAR(100) DEFAULT NULL AFTER isbn,
  ADD COLUMN IF NOT EXISTS total_copies INT NOT NULL DEFAULT 1 AFTER publisher,
  ADD COLUMN IF NOT EXISTS available_copies INT NOT NULL DEFAULT 1 AFTER total_copies;

-- Step 4: Migrate data from books into library_books (if books has unique entries)
INSERT INTO library_books (title, author, category, status, created_at)
SELECT title, author, category, status, created_at FROM books b
WHERE NOT EXISTS (
  SELECT 1 FROM library_books lb WHERE lb.title = b.title AND lb.author = b.author
);

-- Step 5: Drop old books table
DROP TABLE IF EXISTS books;
```

### 1.2 Fix Broken Foreign Keys

```sql
-- Fix timetables: replace free-text teacher/subject with proper FK columns
ALTER TABLE timetables
  ADD COLUMN teacher_id INT NULL AFTER teacher,
  ADD COLUMN subject_id_ref INT NULL AFTER subject,
  ADD COLUMN section_id_ref INT NULL AFTER section_id;

ALTER TABLE timetables
  ADD CONSTRAINT fk_timetable_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_timetable_subject FOREIGN KEY (subject_id_ref) REFERENCES subjects(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_timetable_section FOREIGN KEY (section_id_ref) REFERENCES sections(id) ON DELETE SET NULL;

-- Fix exam_schedules: replace free-text class_name/subject with FK columns
ALTER TABLE exam_schedules
  ADD COLUMN class_id INT NULL,
  ADD COLUMN subject_id INT NULL,
  ADD COLUMN academic_year_id INT NULL;

ALTER TABLE exam_schedules
  ADD CONSTRAINT fk_exam_class FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_exam_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_exam_year FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE SET NULL;

-- Fix hostel_assignments: student_id must be INT FK, not VARCHAR
ALTER TABLE hostel_assignments MODIFY COLUMN student_id INT NULL;
ALTER TABLE hostel_assignments
  ADD COLUMN room_id INT NULL,
  ADD CONSTRAINT fk_hostel_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_hostel_room FOREIGN KEY (room_id) REFERENCES hostel_rooms(id) ON DELETE SET NULL;

-- Fix payroll: add teacher FK, fix status ENUM
ALTER TABLE payroll
  ADD COLUMN teacher_id INT NULL,
  ADD CONSTRAINT fk_payroll_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL;

ALTER TABLE payroll
  MODIFY COLUMN status ENUM('Paid','Processing','Pending','Failed') DEFAULT 'Pending';

-- Fix teacher_assignments: remove rows with NULL class_id OR NULL subject_id
-- Each row must represent one specific teacher → class → subject mapping
DELETE FROM teacher_assignments WHERE class_id IS NULL OR subject_id IS NULL;
ALTER TABLE teacher_assignments MODIFY COLUMN class_id INT NOT NULL;
ALTER TABLE teacher_assignments MODIFY COLUMN subject_id INT NOT NULL;

-- Fix transport_students: a student should only be on ONE active route
-- Add unique constraint
ALTER TABLE transport_students ADD UNIQUE KEY unique_student_route (student_id);
-- (delete duplicate rows manually first if needed)

-- Fix users: assign school_id where NULL (for non-super-admin users)
-- After fixing, add a check constraint or application-level validation
-- that school_id is required for roles 2,3,4,5
```

### 1.3 Add Missing Tables

```sql
-- Parent-student relationship
CREATE TABLE IF NOT EXISTS parent_students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  parent_user_id INT NOT NULL,
  student_id INT NOT NULL,
  relationship VARCHAR(50) NOT NULL DEFAULT 'Parent',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (parent_user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  UNIQUE KEY unique_parent_student (parent_user_id, student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Student leave requests
CREATE TABLE IF NOT EXISTS student_leave_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  leave_type VARCHAR(100) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  reason TEXT DEFAULT NULL,
  status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  approved_by INT NULL,
  rejection_reason TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Assignment submissions
CREATE TABLE IF NOT EXISTS assignment_submissions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  assignment_id BIGINT UNSIGNED NOT NULL,
  student_id INT NOT NULL,
  file_path VARCHAR(255) DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  marks_obtained DECIMAL(5,2) DEFAULT NULL,
  teacher_feedback TEXT DEFAULT NULL,
  status ENUM('Submitted','Graded','Late','Pending') NOT NULL DEFAULT 'Pending',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  UNIQUE KEY unique_submission (assignment_id, student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type VARCHAR(100) NOT NULL,
  title VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  action_url VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  KEY idx_user_read (user_id, is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fee structures (master fee plan per class per year)
CREATE TABLE IF NOT EXISTS fee_structures (
  id INT AUTO_INCREMENT PRIMARY KEY,
  class_id INT NOT NULL,
  fee_type VARCHAR(100) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  frequency ENUM('Monthly','Quarterly','Annually','One-Time') NOT NULL DEFAULT 'Monthly',
  academic_year_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Report cards
CREATE TABLE IF NOT EXISTS report_cards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  academic_year_id INT NOT NULL,
  exam_type_id INT NOT NULL,
  total_marks DECIMAL(7,2) DEFAULT NULL,
  obtained_marks DECIMAL(7,2) DEFAULT NULL,
  percentage DECIMAL(5,2) DEFAULT NULL,
  grade VARCHAR(5) DEFAULT NULL,
  rank INT DEFAULT NULL,
  remarks TEXT DEFAULT NULL,
  generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
  FOREIGN KEY (exam_type_id) REFERENCES exam_types(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Library issue/return records
CREATE TABLE IF NOT EXISTS library_issues (
  id INT AUTO_INCREMENT PRIMARY KEY,
  book_id INT NOT NULL,
  student_id INT NOT NULL,
  issued_date DATE NOT NULL,
  due_date DATE NOT NULL,
  return_date DATE DEFAULT NULL,
  fine DECIMAL(7,2) NOT NULL DEFAULT 0.00,
  status ENUM('Issued','Returned','Overdue','Lost') NOT NULL DEFAULT 'Issued',
  issued_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (book_id) REFERENCES library_books(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Audit logs for sensitive actions
CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  action VARCHAR(100) NOT NULL,
  model_type VARCHAR(100) DEFAULT NULL,
  model_id INT DEFAULT NULL,
  description TEXT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 1.4 Populate Permissions & Role-Permission Mappings (Full RBAC Seed)

```sql
-- Clear and repopulate permissions
TRUNCATE TABLE role_permissions;
DELETE FROM permissions;

INSERT INTO permissions (name, slug) VALUES
('View Dashboard',           'view_dashboard'),
('Manage Students',          'manage_students'),
('View Students',            'view_students'),
('Manage Teachers',          'manage_teachers'),
('View Teachers',            'view_teachers'),
('Mark Attendance',          'mark_attendance'),
('View Attendance',          'view_attendance'),
('View Own Attendance',      'view_own_attendance'),
('Enter Marks',              'enter_marks'),
('View Marks',               'view_marks'),
('View Own Marks',           'view_own_marks'),
('Manage Fees',              'manage_fees'),
('View Fees',                'view_fees'),
('View Own Fees',            'view_own_fees'),
('Manage Timetable',         'manage_timetable'),
('View Timetable',           'view_timetable'),
('Create Assignments',       'create_assignments'),
('View Assignments',         'view_assignments'),
('Submit Assignments',       'submit_assignments'),
('Manage Announcements',     'manage_announcements'),
('View Announcements',       'view_announcements'),
('Send Messages',            'send_messages'),
('Manage Library',           'manage_library'),
('View Library',             'view_library'),
('Manage Payroll',           'manage_payroll'),
('Manage Transport',         'manage_transport'),
('View Transport',           'view_transport'),
('View Health Records',      'view_health_records'),
('Manage Health Records',    'manage_health_records'),
('Manage Hostel',            'manage_hostel'),
('View Own Profile',         'view_own_profile'),
('Edit Own Profile',         'edit_own_profile'),
('Apply Leave',              'apply_leave'),
('Approve Leave',            'approve_leave'),
('View Reports',             'view_reports'),
('Generate Reports',         'generate_reports'),
('System Settings',          'system_settings'),
('View Own Children',        'view_own_children'),
('Manage Events',            'manage_events'),
('View Events',              'view_events'),
('Manage Exam Schedule',     'manage_exam_schedule'),
('View Exam Schedule',       'view_exam_schedule'),
('Download Report Card',     'download_report_card');

-- Super Admin (id=1): ALL permissions
INSERT INTO role_permissions (role_id, permission_id)
  SELECT 1, id FROM permissions;

-- School Admin (id=2): all except system_settings
INSERT INTO role_permissions (role_id, permission_id)
  SELECT 2, id FROM permissions WHERE slug != 'system_settings';

-- Teacher (id=3)
INSERT INTO role_permissions (role_id, permission_id)
  SELECT 3, id FROM permissions WHERE slug IN (
    'view_dashboard','view_students','mark_attendance','view_attendance',
    'enter_marks','view_marks','view_timetable','create_assignments',
    'view_assignments','manage_announcements','view_announcements',
    'send_messages','view_library','view_own_profile','edit_own_profile',
    'apply_leave','approve_leave','view_reports','view_health_records',
    'view_events','view_exam_schedule','manage_exam_schedule'
  );

-- Student (id=4)
INSERT INTO role_permissions (role_id, permission_id)
  SELECT 4, id FROM permissions WHERE slug IN (
    'view_dashboard','view_own_attendance','view_own_marks','view_own_fees',
    'view_timetable','view_assignments','submit_assignments','view_announcements',
    'send_messages','view_library','view_own_profile','edit_own_profile',
    'apply_leave','view_transport','view_health_records','view_events',
    'view_exam_schedule','download_report_card'
  );

-- Parent (id=5)
INSERT INTO role_permissions (role_id, permission_id)
  SELECT 5, id FROM permissions WHERE slug IN (
    'view_dashboard','view_own_children','view_own_attendance','view_own_marks',
    'view_own_fees','view_timetable','view_announcements','send_messages',
    'view_transport','view_events'
  );
```

### 1.5 Fix Demo/Test Data Issues

```sql
-- Remove Lorem Ipsum test data from events
DELETE FROM events WHERE description LIKE '%Lorem%' OR description LIKE '%quibusdam%' OR description LIKE '%Reprehenderit%';

-- Remove fake exam schedule entries (date from 1974 is test data)
DELETE FROM exam_schedules WHERE exam_date < '2000-01-01';

-- Remove duplicate transport student entries
-- Keep only the most recent entry per student
DELETE ts1 FROM transport_students ts1
INNER JOIN transport_students ts2
WHERE ts1.student_id = ts2.student_id AND ts1.id < ts2.id;

-- Fix transport route with empty route_code
UPDATE transport_routes SET route_code = CONCAT('R-0', id) WHERE route_code = '' OR route_code IS NULL;

-- Assign school_id to users who are missing it (for roles 2,3,4,5)
UPDATE users SET school_id = 1 WHERE school_id IS NULL AND role_id IN (2,3,4,5);
```

---

## PHASE 2 — LARAVEL ROLE & PERMISSION MIDDLEWARE

### 2.1 Update User Model

In `app/Models/User.php`, add relationships and a helper:

```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password_hash', 'role_id', 'school_id', 'status'];

    protected $hidden = ['password_hash', 'remember_token'];

    public function getAuthPassword() { return $this->password_hash; }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role?->name, $roles);
    }

    public function hasPermission(string $slug): bool
    {
        return $this->role?->permissions()->where('slug', $slug)->exists() ?? false;
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function linkedStudents()
    {
        // For parent users — returns all children linked via parent_students
        return $this->hasManyThrough(Student::class, ParentStudent::class,
            'parent_user_id', 'id', 'id', 'student_id');
    }
}
```

### 2.2 Update Role Model

In `app/Models/Role.php`:

```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

### 2.3 Create Permission Model

Create `app/Models/Permission.php`:

```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
```

### 2.4 Create RoleMiddleware

Create `app/Http/Middleware/RoleMiddleware.php`:

```php
<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasAnyRole($roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
```

### 2.5 Create PermissionMiddleware

Create `app/Http/Middleware/PermissionMiddleware.php`:

```php
<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
```

### 2.6 Register Middleware

**For Laravel 11** — in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role'       => \App\Http\Middleware\RoleMiddleware::class,
        'permission' => \App\Http\Middleware\PermissionMiddleware::class,
    ]);
})
```

**For Laravel 10** — in `app/Http/Kernel.php` under `$routeMiddleware`:

```php
'role'       => \App\Http\Middleware\RoleMiddleware::class,
'permission' => \App\Http\Middleware\PermissionMiddleware::class,
```

### 2.7 Create SameSchoolMiddleware (Admin Scope)

Create `app/Http/Middleware/SameSchoolMiddleware.php`:

```php
<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class SameSchoolMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        // Super Admin bypasses school scope
        if (auth()->user()->hasRole('Super Admin')) {
            return $next($request);
        }

        // For all other roles, every model access must be scoped to their school_id
        // This middleware stores the school_id in the request for controllers to use
        $request->merge(['_school_id' => auth()->user()->school_id]);

        return $next($request);
    }
}
```

### 2.8 Define All Route Groups

Replace your existing `routes/web.php` route groups with the following structure.
Keep all existing route definitions; only add the middleware wrappers:

```php
<?php
use Illuminate\Support\Facades\Route;

// Public routes (login, register, password reset)
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ─── SUPER ADMIN & SCHOOL ADMIN ──────────────────────────────────────────────
Route::middleware(['auth', 'role:Super Admin,School Admin', 'same_school'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Students
        Route::resource('students', App\Http\Controllers\Admin\StudentController::class);
        Route::get('students/{id}/promote', [App\Http\Controllers\Admin\StudentController::class, 'promote'])->name('students.promote');
        Route::post('students/{id}/promote', [App\Http\Controllers\Admin\StudentController::class, 'doPromote'])->name('students.doPromote');

        // Teachers
        Route::resource('teachers', App\Http\Controllers\Admin\TeacherController::class);
        Route::get('teachers/{id}/assign', [App\Http\Controllers\Admin\TeacherController::class, 'assign'])->name('teachers.assign');
        Route::post('teachers/{id}/assign', [App\Http\Controllers\Admin\TeacherController::class, 'saveAssign'])->name('teachers.saveAssign');

        // Classes & Sections
        Route::resource('classes', App\Http\Controllers\Admin\ClassController::class);
        Route::resource('sections', App\Http\Controllers\Admin\SectionController::class);
        Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);
        Route::resource('academic-years', App\Http\Controllers\Admin\AcademicYearController::class);

        // Attendance
        Route::get('attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance/mark', [App\Http\Controllers\Admin\AttendanceController::class, 'mark'])->name('attendance.mark');
        Route::get('attendance/report', [App\Http\Controllers\Admin\AttendanceController::class, 'report'])->name('attendance.report');

        // Marks & Exam Types
        Route::resource('marks', App\Http\Controllers\Admin\MarksController::class);
        Route::resource('exam-types', App\Http\Controllers\Admin\ExamTypeController::class);
        Route::resource('exam-schedules', App\Http\Controllers\Admin\ExamScheduleController::class);

        // Fees
        Route::resource('fees', App\Http\Controllers\Admin\FeeController::class);
        Route::resource('fee-structures', App\Http\Controllers\Admin\FeeStructureController::class);
        Route::get('fees/{id}/pay', [App\Http\Controllers\Admin\FeeController::class, 'pay'])->name('fees.pay');
        Route::post('fees/{id}/pay', [App\Http\Controllers\Admin\FeeController::class, 'recordPayment'])->name('fees.recordPayment');

        // Timetable
        Route::resource('timetables', App\Http\Controllers\Admin\TimetableController::class);

        // Assignments
        Route::resource('assignments', App\Http\Controllers\Admin\AssignmentController::class);

        // Announcements
        Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);

        // Events
        Route::resource('events', App\Http\Controllers\Admin\EventController::class);

        // Messages
        Route::get('messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
        Route::post('messages', [App\Http\Controllers\Admin\MessageController::class, 'send'])->name('messages.send');

        // Library
        Route::resource('library', App\Http\Controllers\Admin\LibraryController::class);
        Route::post('library/{id}/issue', [App\Http\Controllers\Admin\LibraryController::class, 'issue'])->name('library.issue');
        Route::post('library/{id}/return', [App\Http\Controllers\Admin\LibraryController::class, 'return'])->name('library.return');

        // Health Records
        Route::resource('health-records', App\Http\Controllers\Admin\HealthRecordController::class);

        // Hostel
        Route::resource('hostel-rooms', App\Http\Controllers\Admin\HostelController::class);
        Route::post('hostel-rooms/{id}/assign', [App\Http\Controllers\Admin\HostelController::class, 'assign'])->name('hostel.assign');

        // Transport
        Route::resource('transport', App\Http\Controllers\Admin\TransportController::class);
        Route::post('transport/{id}/assign-student', [App\Http\Controllers\Admin\TransportController::class, 'assignStudent'])->name('transport.assignStudent');

        // Inventory & Assets
        Route::resource('inventory', App\Http\Controllers\Admin\InventoryController::class);
        Route::resource('assets', App\Http\Controllers\Admin\AssetController::class);

        // Payroll
        Route::resource('payroll', App\Http\Controllers\Admin\PayrollController::class);

        // Leave Requests
        Route::get('leave-requests', [App\Http\Controllers\Admin\LeaveController::class, 'index'])->name('leave.index');
        Route::post('leave-requests/{id}/approve', [App\Http\Controllers\Admin\LeaveController::class, 'approve'])->name('leave.approve');
        Route::post('leave-requests/{id}/reject', [App\Http\Controllers\Admin\LeaveController::class, 'reject'])->name('leave.reject');

        // Reports
        Route::get('reports/attendance', [App\Http\Controllers\Admin\ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('reports/marks', [App\Http\Controllers\Admin\ReportController::class, 'marks'])->name('reports.marks');
        Route::get('reports/fees', [App\Http\Controllers\Admin\ReportController::class, 'fees'])->name('reports.fees');
        Route::get('reports/students', [App\Http\Controllers\Admin\ReportController::class, 'students'])->name('reports.students');

        // School Settings (Super Admin only)
        Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])
            ->name('settings.index')
            ->middleware('role:Super Admin');
        Route::post('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])
            ->name('settings.update')
            ->middleware('role:Super Admin');

        // User management (Super Admin only)
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)
            ->middleware('role:Super Admin');
    });

// ─── TEACHER ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:Teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

        // Attendance — only for teacher's assigned classes
        Route::get('/attendance', [App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/mark', [App\Http\Controllers\Teacher\AttendanceController::class, 'mark'])->name('attendance.mark');
        Route::get('/attendance/view', [App\Http\Controllers\Teacher\AttendanceController::class, 'view'])->name('attendance.view');

        // Marks — only for teacher's assigned subjects
        Route::get('/marks', [App\Http\Controllers\Teacher\MarksController::class, 'index'])->name('marks.index');
        Route::post('/marks', [App\Http\Controllers\Teacher\MarksController::class, 'store'])->name('marks.store');
        Route::get('/marks/{id}/edit', [App\Http\Controllers\Teacher\MarksController::class, 'edit'])->name('marks.edit');
        Route::put('/marks/{id}', [App\Http\Controllers\Teacher\MarksController::class, 'update'])->name('marks.update');

        // Assignments
        Route::resource('assignments', App\Http\Controllers\Teacher\AssignmentController::class);
        Route::get('assignments/{id}/submissions', [App\Http\Controllers\Teacher\AssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::post('assignments/{id}/grade/{submission_id}', [App\Http\Controllers\Teacher\AssignmentController::class, 'grade'])->name('assignments.grade');

        // Timetable view
        Route::get('/timetable', [App\Http\Controllers\Teacher\TimetableController::class, 'index'])->name('timetable');

        // Leave requests
        Route::get('/leaves', [App\Http\Controllers\Teacher\LeaveController::class, 'index'])->name('leaves.index');
        Route::post('/leaves', [App\Http\Controllers\Teacher\LeaveController::class, 'store'])->name('leaves.store');

        // Announcements (teacher can create for their classes)
        Route::resource('announcements', App\Http\Controllers\Teacher\AnnouncementController::class);

        // Messages
        Route::get('/messages', [App\Http\Controllers\Teacher\MessageController::class, 'index'])->name('messages.index');
        Route::post('/messages', [App\Http\Controllers\Teacher\MessageController::class, 'send'])->name('messages.send');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Teacher\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Teacher\ProfileController::class, 'update'])->name('profile.update');

        // Exam schedules view
        Route::get('/exams', [App\Http\Controllers\Teacher\ExamController::class, 'index'])->name('exams');

        // Performance reports for teacher's classes
        Route::get('/reports', [App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('reports');
    });

// ─── STUDENT ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:Student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/attendance', [App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance');
        Route::get('/marks', [App\Http\Controllers\Student\MarksController::class, 'index'])->name('marks');
        Route::get('/fees', [App\Http\Controllers\Student\FeeController::class, 'index'])->name('fees');
        Route::get('/timetable', [App\Http\Controllers\Student\TimetableController::class, 'index'])->name('timetable');
        Route::get('/assignments', [App\Http\Controllers\Student\AssignmentController::class, 'index'])->name('assignments');
        Route::post('/assignments/{id}/submit', [App\Http\Controllers\Student\AssignmentController::class, 'submit'])->name('assignments.submit');
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
    });

// ─── PARENT ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:Parent'])
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
        Route::get('/announcements', [App\Http\Controllers\ParentPortal\AnnouncementController::class, 'index'])->name('announcements');
        Route::get('/messages', [App\Http\Controllers\ParentPortal\MessageController::class, 'index'])->name('messages');
        Route::post('/messages', [App\Http\Controllers\ParentPortal\MessageController::class, 'send'])->name('messages.send');
        Route::get('/transport', [App\Http\Controllers\ParentPortal\TransportController::class, 'index'])->name('transport');
        Route::get('/profile', [App\Http\Controllers\ParentPortal\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ParentPortal\ProfileController::class, 'update'])->name('profile.update');
    });
```

---

## PHASE 3 — STUDENT PORTAL CONTROLLERS & VIEWS

> All views go in `resources/views/student/`. All controllers go in `app/Http/Controllers/Student/`.
> Every view must `@extends` the same layout used by the rest of the app.
> Copy the exact sidebar, navbar, and card HTML patterns from an existing working admin page.

### 3.1 Student Dashboard Controller

`app/Http/Controllers/Student/DashboardController.php`:

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\Fee;
use App\Models\Announcement;
use App\Models\ExamSchedule;
use App\Models\Timetable;
use App\Models\AcademicYear;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        abort_if(!$student, 403, 'Student record not found for this account.');

        $academicYear = AcademicYear::where('is_active', 1)->first();

        // Attendance stats
        $totalDays   = StudentAttendance::where('student_id', $student->id)
                         ->where('academic_year_id', $academicYear?->id)->count();
        $presentDays = StudentAttendance::where('student_id', $student->id)
                         ->where('academic_year_id', $academicYear?->id)
                         ->where('status', 'P')->count();
        $attendancePct = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        // Pending fees
        $pendingFees = Fee::where('student_id', $student->id)
                         ->whereIn('status', ['Pending','Overdue'])->sum('amount');

        // Announcements
        $announcements = Announcement::whereIn('target_role', ['all','student'])
                           ->latest()->take(5)->get();

        // Today's timetable
        $dayName = Carbon::today()->format('l'); // Monday, Tuesday...
        $todayClasses = Timetable::where('class_id', $student->current_class_id)
                          ->where('section_id_ref', $student->current_section_id)
                          ->where('day_of_week', $dayName)
                          ->orderBy('start_time')->get();

        // Upcoming exams
        $upcomingExams = ExamSchedule::where('class_id', $student->current_class_id)
                           ->where('exam_date', '>=', today())
                           ->orderBy('exam_date')->take(3)->get();

        return view('student.dashboard', compact(
            'student','attendancePct','presentDays','totalDays',
            'pendingFees','announcements','todayClasses','upcomingExams'
        ));
    }
}
```

### 3.2 Student Attendance Controller

`app/Http/Controllers/Student/AttendanceController.php`:

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentLeaveRequest;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student     = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $month       = $request->input('month', now()->month);
        $year        = $request->input('year', now()->year);

        // Get all attendance records for the selected month
        $records = StudentAttendance::where('student_id', $student->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(fn($r) => Carbon::parse($r->date)->format('Y-m-d'));

        // Full-year stats
        $stats = [
            'present' => StudentAttendance::where('student_id', $student->id)
                           ->where('academic_year_id', $academicYear?->id)->where('status','P')->count(),
            'absent'  => StudentAttendance::where('student_id', $student->id)
                           ->where('academic_year_id', $academicYear?->id)->where('status','A')->count(),
            'leave'   => StudentAttendance::where('student_id', $student->id)
                           ->where('academic_year_id', $academicYear?->id)->where('status','L')->count(),
        ];
        $stats['total']      = $stats['present'] + $stats['absent'] + $stats['leave'];
        $stats['percentage'] = $stats['total'] > 0
            ? round(($stats['present'] / $stats['total']) * 100, 1) : 0;

        // Leave requests
        $leaveRequests = StudentLeaveRequest::where('student_id', $student->id)
                           ->latest()->take(10)->get();

        // Build calendar days for the month
        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $daysInMonth  = $startOfMonth->daysInMonth;
        $startDay     = $startOfMonth->dayOfWeek; // 0=Sunday

        return view('student.attendance', compact(
            'records','stats','leaveRequests','month','year','daysInMonth','startDay'
        ));
    }
}
```

### 3.3 Student Marks Controller

`app/Http/Controllers/Student/MarksController.php`:

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\ExamType;
use App\Models\AcademicYear;

class MarksController extends Controller
{
    public function index()
    {
        $student     = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();

        $marks = Mark::with(['subject','examType'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYear?->id)
            ->get()
            ->groupBy('exam_type_id');

        $examTypes = ExamType::all()->keyBy('id');

        // Calculate totals per exam type
        $summaries = [];
        foreach ($marks as $examTypeId => $examMarks) {
            $obtained = $examMarks->sum('marks_obtained');
            $total    = $examMarks->sum('total_marks');
            $pct      = $total > 0 ? round(($obtained / $total) * 100, 1) : 0;
            $summaries[$examTypeId] = [
                'obtained'   => $obtained,
                'total'      => $total,
                'percentage' => $pct,
                'grade'      => $this->calculateGrade($pct),
            ];
        }

        return view('student.marks', compact('marks','examTypes','summaries','student'));
    }

    private function calculateGrade(float $pct): string
    {
        return match(true) {
            $pct >= 90 => 'A+',
            $pct >= 80 => 'A',
            $pct >= 70 => 'B+',
            $pct >= 60 => 'B',
            $pct >= 50 => 'C',
            $pct >= 40 => 'D',
            default    => 'F',
        };
    }
}
```

### 3.4 Student Fee Controller

`app/Http/Controllers/Student/FeeController.php`:

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\FeePayment;

class FeeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $fees = Fee::with('payments')
            ->where('student_id', $student->id)
            ->orderByDesc('due_date')
            ->paginate(15);

        $totals = [
            'total'   => Fee::where('student_id', $student->id)->sum('amount'),
            'paid'    => Fee::where('student_id', $student->id)->where('status','Paid')->sum('paid_amount'),
            'pending' => Fee::where('student_id', $student->id)->whereIn('status',['Pending','Overdue'])->sum('amount'),
        ];

        return view('student.fees', compact('fees','totals','student'));
    }
}
```

### 3.5 Student Timetable Controller

`app/Http/Controllers/Student/TimetableController.php`:

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Timetable;

class TimetableController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $timetable = Timetable::with(['teacher','subjectRef'])
            ->where('class_id', $student->current_class_id)
            ->where('section_id_ref', $student->current_section_id)
            ->orderByRaw("FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

        return view('student.timetable', compact('timetable','days','student'));
    }
}
```

### 3.6 Student Assignment Controller

`app/Http/Controllers/Student/AssignmentController.php`:

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $assignments = Assignment::with(['subject','teacher'])
            ->where('class_id', $student->current_class_id)
            ->orderByDesc('due_date')
            ->paginate(15);

        // Attach submission status for each assignment
        $submittedIds = AssignmentSubmission::where('student_id', $student->id)
            ->pluck('assignment_id')
            ->toArray();

        return view('student.assignments', compact('assignments','submittedIds','student'));
    }

    public function submit(Request $request, $assignmentId)
    {
        $student    = auth()->user()->student;
        $assignment = Assignment::where('id', $assignmentId)
            ->where('class_id', $student->current_class_id)
            ->firstOrFail();

        $request->validate([
            'file'  => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,png,zip',
            'notes' => 'nullable|string|max:1000',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            [
                'file_path' => $filePath,
                'notes'     => $request->notes,
                'status'    => Carbon::now()->gt($assignment->due_date) ? 'Late' : 'Submitted',
            ]
        );

        return back()->with('success', 'Assignment submitted successfully.');
    }
}
```

### 3.7 Student Report Card Controller

`app/Http/Controllers/Student/ReportCardController.php`:

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\ReportCard;
use App\Models\ExamType;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $student     = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $examTypeId  = $request->input('exam_type_id');

        $examTypes = ExamType::all();

        $marks = [];
        $summary = null;

        if ($examTypeId) {
            $marks = Mark::with('subject')
                ->where('student_id', $student->id)
                ->where('academic_year_id', $academicYear?->id)
                ->where('exam_type_id', $examTypeId)
                ->get();

            $totalObtained = $marks->sum('marks_obtained');
            $totalMax      = $marks->sum('total_marks');
            $pct           = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0;

            $summary = [
                'obtained'   => $totalObtained,
                'total'      => $totalMax,
                'percentage' => $pct,
                'grade'      => $this->grade($pct),
                'rank'       => ReportCard::where('student_id', $student->id)
                                  ->where('exam_type_id', $examTypeId)
                                  ->value('rank'),
            ];
        }

        return view('student.report-card', compact('student','marks','summary','examTypes','examTypeId','academicYear'));
    }

    public function download(Request $request)
    {
        $student     = auth()->user()->student;
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $examTypeId  = $request->input('exam_type_id');

        $marks = Mark::with('subject')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYear?->id)
            ->where('exam_type_id', $examTypeId)
            ->get();

        $totalObtained = $marks->sum('marks_obtained');
        $totalMax      = $marks->sum('total_marks');
        $pct           = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0;

        $pdf = Pdf::loadView('student.report-card-pdf', compact('student','marks','academicYear','pct'));
        return $pdf->download("report_card_{$student->admission_no}.pdf");
    }

    private function grade(float $pct): string
    {
        return match(true) {
            $pct >= 90 => 'A+', $pct >= 80 => 'A',
            $pct >= 70 => 'B+', $pct >= 60 => 'B',
            $pct >= 50 => 'C',  $pct >= 40 => 'D',
            default    => 'F',
        };
    }
}
```

### 3.8 Student Leave Controller

`app/Http/Controllers/Student/LeaveController.php`:

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentLeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $leaves  = StudentLeaveRequest::where('student_id', $student->id)
                     ->latest()->paginate(15);
        return view('student.leave-requests', compact('leaves','student'));
    }

    public function store(Request $request)
    {
        $student = auth()->user()->student;

        $request->validate([
            'leave_type' => 'required|string|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:1000',
        ]);

        StudentLeaveRequest::create([
            'student_id' => $student->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'Pending',
        ]);

        return back()->with('success', 'Leave request submitted successfully.');
    }
}
```

---

## PHASE 4 — TEACHER PORTAL FIXES (REMOVE SHADOW SYSTEM)

### 4.1 Fix Teacher Module Access Logic

The `teacher_module_access` table may remain for **sidebar visibility** only.
It must **never** be the security gate. Security is always handled by route middleware.

In every Teacher controller, **always scope queries** to the teacher's own data:

```php
// Add this helper trait: app/Traits/TeacherScoped.php
<?php
namespace App\Traits;
use App\Models\TeacherAssignment;

trait TeacherScoped
{
    protected function getTeacher()
    {
        $teacher = auth()->user()->teacher;
        abort_if(!$teacher, 403, 'Teacher record not found.');
        return $teacher;
    }

    protected function getAssignedClassIds($teacher): \Illuminate\Support\Collection
    {
        return TeacherAssignment::where('teacher_id', $teacher->id)->pluck('class_id');
    }

    protected function getAssignedSubjectIds($teacher): \Illuminate\Support\Collection
    {
        return TeacherAssignment::where('teacher_id', $teacher->id)->pluck('subject_id');
    }
}
```

Use this trait in all Teacher controllers:

```php
use App\Traits\TeacherScoped;

class AttendanceController extends Controller
{
    use TeacherScoped;

    public function index()
    {
        $teacher        = $this->getTeacher();
        $assignedClasses = $this->getAssignedClassIds($teacher);

        // Teacher can ONLY see students in their assigned classes
        $classes = \App\Models\ClassModel::whereIn('id', $assignedClasses)->get();

        return view('teacher.attendance.index', compact('classes'));
    }

    public function mark(Request $request)
    {
        $teacher        = $this->getTeacher();
        $assignedClasses = $this->getAssignedClassIds($teacher);

        // Security check: ensure the submitted class_id is one the teacher is assigned to
        abort_unless($assignedClasses->contains($request->class_id), 403);

        // ... mark attendance logic
    }
}
```

### 4.2 Fix Teacher Assignments Data Model

After the DB migration that makes class_id and subject_id both required:

```php
// app/Models/TeacherAssignment.php
class TeacherAssignment extends Model
{
    protected $table = 'teacher_assignments';
    protected $fillable = ['teacher_id', 'class_id', 'subject_id'];

    public function teacher()  { return $this->belongsTo(Teacher::class); }
    public function class_()   { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function subject()  { return $this->belongsTo(Subject::class); }
}
```

In the admin Teacher edit/assign page, always save a complete triplet:

```php
// TeacherController@saveAssign
public function saveAssign(Request $request, $teacherId)
{
    $request->validate([
        'assignments'              => 'required|array',
        'assignments.*.class_id'   => 'required|exists:classes,id',
        'assignments.*.subject_id' => 'required|exists:subjects,id',
    ]);

    TeacherAssignment::where('teacher_id', $teacherId)->delete();

    foreach ($request->assignments as $row) {
        TeacherAssignment::create([
            'teacher_id' => $teacherId,
            'class_id'   => $row['class_id'],
            'subject_id' => $row['subject_id'],
        ]);
    }

    return redirect()->route('admin.teachers.index')->with('success', 'Assignments saved.');
}
```

---

## PHASE 5 — PARENT PORTAL

> All views go in `resources/views/parent/`. All controllers go in `app/Http/Controllers/ParentPortal/`.

### 5.1 Base Query Pattern (Use in ALL Parent Controllers)

Every parent controller must use this pattern to prevent data leakage:

```php
private function getLinkedStudentIds(): \Illuminate\Support\Collection
{
    return \App\Models\ParentStudent::where('parent_user_id', auth()->id())
        ->pluck('student_id');
}

// Then in every query:
// $studentIds = $this->getLinkedStudentIds();
// abort_if($studentIds->isEmpty(), 403, 'No students linked to your account.');
// Always: ->whereIn('student_id', $studentIds)
// For single student routes: abort_unless($studentIds->contains($student_id), 403);
```

### 5.2 Parent Dashboard Controller

`app/Http/Controllers/ParentPortal/DashboardController.php`:

```php
<?php
namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Fee;
use App\Models\Announcement;
use App\Models\AcademicYear;

class DashboardController extends Controller
{
    public function index()
    {
        $studentIds = ParentStudent::where('parent_user_id', auth()->id())->pluck('student_id');
        abort_if($studentIds->isEmpty(), 403, 'No children linked to your account. Please contact the school.');

        $children    = Student::with(['currentClass','currentSection'])->whereIn('id', $studentIds)->get();
        $academicYear = AcademicYear::where('is_active', 1)->first();

        // Summary per child
        $childSummaries = [];
        foreach ($children as $child) {
            $total   = StudentAttendance::where('student_id',$child->id)->where('academic_year_id',$academicYear?->id)->count();
            $present = StudentAttendance::where('student_id',$child->id)->where('academic_year_id',$academicYear?->id)->where('status','P')->count();
            $pending = Fee::where('student_id',$child->id)->whereIn('status',['Pending','Overdue'])->sum('amount');

            $childSummaries[$child->id] = [
                'attendance_pct' => $total > 0 ? round(($present/$total)*100,1) : 0,
                'pending_fees'   => $pending,
            ];
        }

        $announcements = Announcement::whereIn('target_role',['all','parent'])->latest()->take(5)->get();

        return view('parent.dashboard', compact('children','childSummaries','announcements'));
    }

    public function children()
    {
        $studentIds = ParentStudent::where('parent_user_id', auth()->id())->pluck('student_id');
        $children   = Student::with(['currentClass','currentSection'])->whereIn('id', $studentIds)->get();
        return view('parent.children', compact('children'));
    }
}
```

---

## PHASE 6 — REPLACE ALL HARDCODED / STATIC DATA

### 6.1 Payroll Page — Make Dynamic

`app/Http/Controllers/Admin/PayrollController.php` — the `index` and `store` methods must:

```php
public function index()
{
    // Always join with teachers table — never use hardcoded names
    $payrolls = \App\Models\Payroll::with('teacher.user')
        ->orderByDesc('created_at')
        ->paginate(15);

    $teachers = \App\Models\Teacher::with('user')->get();

    return view('admin.payroll.index', compact('payrolls','teachers'));
}

public function store(Request $request)
{
    $request->validate([
        'teacher_id'  => 'required|exists:teachers,id',
        'basic_pay'   => 'required|numeric|min:0',
        'allowances'  => 'required|numeric|min:0',
        'deductions'  => 'required|numeric|min:0',
        'month_year'  => 'required|string',
    ]);

    $teacher = \App\Models\Teacher::with('user')->findOrFail($request->teacher_id);

    \App\Models\Payroll::create([
        'teacher_id' => $teacher->id,
        'emp_id'     => $teacher->employee_number,
        'name'       => $teacher->user->name,
        'role'       => 'Teacher',
        'basic_pay'  => $request->basic_pay,
        'allowances' => $request->allowances,
        'deductions' => $request->deductions,
        'net_salary' => $request->basic_pay + $request->allowances - $request->deductions,
        'status'     => 'Pending',
        'month_year' => $request->month_year,
    ]);

    return redirect()->route('admin.payroll.index')->with('success', 'Payroll entry created.');
}
```

### 6.2 Exam Schedule Page — Fix to Use FK Dropdowns

In the exam schedule create/edit form view, replace free-text inputs with dynamic dropdowns:

```blade
{{-- resources/views/admin/exam-schedules/form.blade.php --}}
<div class="form-group">
    <label>Class</label>
    <select name="class_id" class="form-control" required>
        <option value="">-- Select Class --</option>
        @foreach($classes as $class)
            <option value="{{ $class->id }}" {{ old('class_id', $examSchedule->class_id ?? '') == $class->id ? 'selected' : '' }}>
                Class {{ $class->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Subject</label>
    <select name="subject_id" class="form-control" required>
        <option value="">-- Select Subject --</option>
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}" {{ old('subject_id', $examSchedule->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                {{ $subject->name }} ({{ $subject->code }})
            </option>
        @endforeach
    </select>
</div>
```

### 6.3 Timetable Page — Fix to Use FK Dropdowns

Replace teacher and subject text inputs with:

```blade
<select name="teacher_id" class="form-control">
    <option value="">-- Select Teacher --</option>
    @foreach($teachers as $teacher)
        <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
    @endforeach
</select>

<select name="subject_id_ref" class="form-control">
    <option value="">-- Select Subject --</option>
    @foreach($subjects as $subject)
        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
    @endforeach
</select>
```

### 6.4 Transport Page — Enforce Single-Route Per Student

In `TransportController@assignStudent`:

```php
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
```

---

## PHASE 7 — SECURITY HARDENING

### 7.1 Add Fillable to All Models

Every model must have explicit `$fillable`. Example:

```php
// app/Models/Student.php
protected $fillable = [
    'admission_no','user_id','first_name','last_name','gender','dob',
    'b_form_number','father_name','father_cnic','mobile_number',
    'current_class_id','current_section_id','status','exam_roll',
    'class_admitted','admission_date','previous_school','placeofbirth',
    'address','religion','caste','photo','current_school',
];

// app/Models/Fee.php
protected $fillable = [
    'challan_no','student_id','fee_category','amount','discount',
    'fine','paid_amount','due_date','status',
];
```

### 7.2 Add FormRequest Validation Classes

Create one FormRequest per store/update action. Example:

`app/Http/Requests/StoreStudentRequest.php`:

```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'gender'            => 'required|in:Male,Female,Other',
            'dob'               => 'required|date|before:today',
            'admission_no'      => 'required|string|max:100|unique:students,admission_no',
            'current_class_id'  => 'required|exists:classes,id',
            'current_section_id'=> 'required|exists:sections,id',
            'mobile_number'     => 'nullable|string|max:20',
            'photo'             => 'nullable|image|max:2048',
        ];
    }
}
```

### 7.3 Scope All Admin Queries by School

Add a global scope for school isolation. Create `app/Scopes/SchoolScope.php`:

```php
<?php
namespace App\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SchoolScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Super Admin sees everything
        if (auth()->check() && auth()->user()->hasRole('Super Admin')) return;

        $schoolId = auth()->user()?->school_id;
        if ($schoolId) {
            $builder->where($model->getTable() . '.school_id', $schoolId);
        }
    }
}
```

Apply it to the `Student`, `Teacher`, `Class`, and `Section` models:

```php
protected static function booted(): void
{
    static::addGlobalScope(new \App\Scopes\SchoolScope());
}
```

### 7.4 Add Soft Deletes

```sql
ALTER TABLE students ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE teachers ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE users    ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
```

In the corresponding models: add `use SoftDeletes;` and `use Illuminate\Database\Eloquent\SoftDeletes;`

### 7.5 Add Login Rate Limiting

In `routes/web.php`:

```php
Route::middleware('throttle:5,1')->post('/login', [LoginController::class, 'login']);
```

### 7.6 Add Audit Logging for Sensitive Actions

Create `app/Observers/AuditObserver.php`:

```php
<?php
namespace App\Observers;
use App\Models\AuditLog;

class AuditObserver
{
    public static function log(string $action, string $modelType, int $modelId, string $desc = ''): void
    {
        if (!auth()->check()) return;
        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $desc,
            'ip_address'  => request()->ip(),
        ]);
    }
}
```

Call it in sensitive controllers:

```php
// In FeeController@recordPayment:
AuditObserver::log('fee_payment', 'Fee', $fee->id, "Paid {$request->amount_paid} for student {$fee->student_id}");

// In MarksController@store:
AuditObserver::log('marks_entry', 'Mark', $mark->id, "Marks entered for student {$mark->student_id}");
```

---

## PHASE 8 — STUDENT PORTAL BLADE VIEWS

> Every view below must use the existing master layout. Replace `layouts.app` with the actual layout name in your project.

### 8.1 Dashboard View

`resources/views/student/dashboard.blade.php`:

```blade
@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
{{-- Copy the exact page-header HTML pattern from an existing admin page --}}
<div class="row">
    {{-- Attendance Card --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Attendance</h6>
                <h3>{{ $attendancePct }}%</h3>
                <small class="text-muted">{{ $presentDays }} / {{ $totalDays }} days</small>
            </div>
        </div>
    </div>

    {{-- Pending Fees Card --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Pending Fees</h6>
                <h3>{{ number_format($pendingFees, 2) }}</h3>
                <a href="{{ route('student.fees') }}" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
            </div>
        </div>
    </div>

    {{-- Today's Classes Card --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Today's Timetable</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Time</th><th>Subject</th><th>Teacher</th></tr></thead>
                    <tbody>
                    @forelse($todayClasses as $period)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }}</td>
                            <td>{{ $period->subjectRef->name ?? $period->subject }}</td>
                            <td>{{ $period->teacher->full_name ?? $period->teacher }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No classes today</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Announcements --}}
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Recent Announcements</div>
            <div class="card-body">
                @forelse($announcements as $ann)
                    <div class="border-bottom pb-2 mb-2">
                        <strong>{{ $ann->title }}</strong>
                        <p class="text-muted small mb-0">{{ Str::limit($ann->content, 120) }}</p>
                        <small class="text-muted">{{ $ann->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted">No announcements yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Upcoming Exams --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Upcoming Exams</div>
            <div class="card-body">
                @forelse($upcomingExams as $exam)
                    <div class="border-bottom pb-2 mb-2">
                        <strong>{{ $exam->subjectRef->name ?? $exam->subject }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}
                            @if($exam->exam_time) · {{ $exam->exam_time }} @endif
                        </small>
                    </div>
                @empty
                    <p class="text-muted small">No upcoming exams.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
```

### 8.2 Attendance View

`resources/views/student/attendance.blade.php`:

```blade
@extends('layouts.app')
@section('title', 'My Attendance')

@section('content')
<div class="row mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body text-center">
        <h6 class="text-muted">Present</h6><h3 class="text-success">{{ $stats['present'] }}</h3>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center">
        <h6 class="text-muted">Absent</h6><h3 class="text-danger">{{ $stats['absent'] }}</h3>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center">
        <h6 class="text-muted">Leave</h6><h3 class="text-warning">{{ $stats['leave'] }}</h3>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center">
        <h6 class="text-muted">Percentage</h6><h3>{{ $stats['percentage'] }}%</h3>
    </div></div></div>
</div>

{{-- Month Navigation --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <a href="?month={{ $month == 1 ? 12 : $month-1 }}&year={{ $month == 1 ? $year-1 : $year }}" class="btn btn-sm btn-outline-secondary">← Prev</a>
        <strong>{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</strong>
        <a href="?month={{ $month == 12 ? 1 : $month+1 }}&year={{ $month == 12 ? $year+1 : $year }}" class="btn btn-sm btn-outline-secondary">Next →</a>
    </div>
    <div class="card-body">
        <div class="row text-center mb-2">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                <div class="col"><strong>{{ $day }}</strong></div>
            @endforeach
        </div>
        <div class="row text-center">
            @for($i = 0; $i < $startDay; $i++)
                <div class="col"></div>
            @endfor
            @for($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $dateKey = \Carbon\Carbon::createFromDate($year,$month,$d)->format('Y-m-d');
                    $record  = $records[$dateKey] ?? null;
                    $status  = $record ? $record->status : null;
                    $class   = match($status) { 'P'=>'bg-success text-white','A'=>'bg-danger text-white','L'=>'bg-warning','T'=>'bg-info text-white', default=>'' };
                @endphp
                <div class="col mb-1">
                    <span class="badge {{ $class }} d-block p-2">{{ $d }}<br><small>{{ $status ?? '-' }}</small></span>
                </div>
                @if((($d + $startDay) % 7 == 0) && $d < $daysInMonth)
                    </div><div class="row text-center">
                @endif
            @endfor
        </div>
    </div>
</div>

{{-- Leave Application --}}
<div class="card">
    <div class="card-header">Apply for Leave</div>
    <div class="card-body">
        <form method="POST" action="{{ route('student.leave.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label>Leave Type</label>
                    <select name="leave_type" class="form-control" required>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Personal Leave">Personal Leave</option>
                        <option value="Family Emergency">Family Emergency</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" required min="{{ today()->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control" required min="{{ today()->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <label>Reason</label>
                    <input type="text" name="reason" class="form-control" required maxlength="1000">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit Leave Request</button>
        </form>
    </div>
</div>
@endsection
```

---

## PHASE 9 — ELOQUENT MODEL RELATIONSHIPS (QUICK REFERENCE)

Add these relationships to your models so controllers use clean Eloquent syntax:

```php
// Student model
public function currentClass()  { return $this->belongsTo(SchoolClass::class,'current_class_id'); }
public function currentSection(){ return $this->belongsTo(Section::class,'current_section_id'); }
public function attendances()   { return $this->hasMany(StudentAttendance::class); }
public function marks()         { return $this->hasMany(Mark::class); }
public function fees()          { return $this->hasMany(Fee::class); }
public function submissions()   { return $this->hasMany(AssignmentSubmission::class); }
public function leaveRequests() { return $this->hasMany(StudentLeaveRequest::class); }
public function reportCards()   { return $this->hasMany(ReportCard::class); }
public function user()          { return $this->belongsTo(User::class); }

// Teacher model
public function user()          { return $this->belongsTo(User::class); }
public function assignments()   { return $this->hasMany(TeacherAssignment::class); }
public function classes()       { return $this->hasManyThrough(SchoolClass::class, TeacherAssignment::class,'teacher_id','id','id','class_id'); }

// Fee model
public function student()       { return $this->belongsTo(Student::class); }
public function payments()      { return $this->hasMany(FeePayment::class,'fee_id'); }

// Assignment model
public function teacher()       { return $this->belongsTo(Teacher::class); }
public function class_()        { return $this->belongsTo(SchoolClass::class,'class_id'); }
public function subject()       { return $this->belongsTo(Subject::class); }
public function submissions()   { return $this->hasMany(AssignmentSubmission::class); }

// Timetable model
public function teacher()       { return $this->belongsTo(Teacher::class,'teacher_id'); }
public function subjectRef()    { return $this->belongsTo(Subject::class,'subject_id_ref'); }
public function sectionRef()    { return $this->belongsTo(Section::class,'section_id_ref'); }
public function class_()        { return $this->belongsTo(SchoolClass::class,'class_id'); }
```

---

## PHASE 10 — INSTALL REQUIRED PACKAGES

Run these in the project root:

```bash
# PDF generation for report cards
composer require barryvdh/laravel-dompdf

# (Optional) Advanced permission management — alternative to custom system
# composer require spatie/laravel-permission

# Publish config
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## FINAL CHECKLIST — VERIFY AFTER EACH PHASE

- [ ] Phase 1: Run all SQL migrations. No errors. `SHOW TABLES;` confirms new tables exist.
- [ ] Phase 1: `SELECT COUNT(*) FROM permissions;` returns 43. `SELECT COUNT(*) FROM role_permissions;` is non-zero.
- [ ] Phase 2: Middleware registered. `php artisan route:list | grep middleware` shows `role:` on protected routes.
- [ ] Phase 2: Logging in as a student redirects to `/student/dashboard`, not `/admin/dashboard`.
- [ ] Phase 2: Accessing `/admin/students` as a logged-in student returns HTTP 403.
- [ ] Phase 3: Student dashboard shows real data from DB. No hardcoded values visible.
- [ ] Phase 3: Student cannot access another student's data by changing a URL ID parameter.
- [ ] Phase 4: Teacher controllers only return students from their assigned classes.
- [ ] Phase 4: Teacher cannot mark attendance for a class they are not assigned to.
- [ ] Phase 5: Parent portal returns 403 if no `parent_students` row exists for that parent.
- [ ] Phase 5: Parent cannot access another child's data by guessing a student_id in the URL.
- [ ] Phase 6: Payroll create form shows teacher dropdown from DB. No hardcoded names.
- [ ] Phase 6: Exam schedule form shows class and subject dropdowns from DB.
- [ ] Phase 7: `php artisan route:list` — every non-public route has `auth` middleware.
- [ ] Phase 7: Login page shows error after 5 failed attempts (rate limiting active).
- [ ] Theme: Every new page uses the same layout, same sidebar, same card/table/button CSS classes as existing pages.

---

*Generated by Claude Sonnet 4.6 — Full Master Upgrade Prompt for Newmkhanschool*  
*Database: newschool · PHP 8.2 · Laravel · MariaDB 10.4*
