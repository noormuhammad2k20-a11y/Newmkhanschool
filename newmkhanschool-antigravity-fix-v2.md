# Newmkhanschool — Master Fix Prompt (Antigravity Style) v2
**Project:** Laravel School Management System  
**Stack:** PHP 8.2 · Laravel · MariaDB · Tailwind CSS (CDN) · Blade Templates  
**Repo:** https://github.com/noormuhammad2k20-a11y/Newmkhanschool  
**Database:** `newschool`  
**Generated:** 2026-06-09

---

## CONTEXT & INSTRUCTIONS FOR AI

You are working on a multi-role school management system. The system has 5 roles:
- `role_id = 1` → **Super Admin** (full access all schools)
- `role_id = 2` → **School Admin** (single school admin)
- `role_id = 3` → **Teacher** (module-based access via `teacher_module_access`)
- `role_id = 4` → **Student**
- `role_id = 5` → **Parent**

The sidebar in `resources/views/layouts/app.blade.php` renders different nav links per role using `@if(auth()->user()->role_id == X)` blocks.

Below is a **complete audit** of: what currently exists, what is broken, what is missing, and exactly what needs to be added/fixed. Implement ALL items in this document.

---

## PART 1 — CRITICAL BUGS TO FIX IMMEDIATELY

### Bug 1 — Sidebar Title Always Shows "Admin Portal"
**File:** `resources/views/layouts/app.blade.php` (line ~167)

**Current broken code:**
```html
<p class="font-label-md text-label-md text-secondary">Admin Portal</p>
```

**Fix — make it dynamic:**
```blade
<p class="font-label-md text-label-md text-secondary">
    @if(auth()->check())
        @php $roleId = auth()->user()->role_id; @endphp
        @if(in_array($roleId, [1,2])) Admin Portal
        @elseif($roleId == 3) Teacher Portal
        @elseif($roleId == 4) Student Portal
        @elseif($roleId == 5) Parent Portal
        @else Portal @endif
    @endif
</p>
```

---

### Bug 2 — Student Sidebar Missing Routes That Already Exist
The following routes are **fully built** in `routes/web.php` and have controllers + views, but are **NOT linked in the sidebar**. Add them to the Student sidebar section (`role_id == 4`):

| Missing Sidebar Item | Route Name | Icon |
|---|---|---|
| Attendance | `student.attendance` | `how_to_reg` |
| Report Card | `student.report-card` | `description` |
| Exam Schedule | `student.exam-schedule` | `event_note` |
| Digital Learning | `student.digital-learning` | `school` |
| Quiz | `student.quiz` | `quiz` |

**Add these Blade links inside the student `@elseif` block, after "Leave Requests":**
```blade
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.attendance*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.attendance') }}">
        <span class="material-symbols-outlined" data-icon="how_to_reg">how_to_reg</span>
        <span class="font-label-md text-label-md">My Attendance</span>
    </a>
</li>
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.report-card*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.report-card') }}">
        <span class="material-symbols-outlined" data-icon="description">description</span>
        <span class="font-label-md text-label-md">Report Card</span>
    </a>
</li>
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.exam-schedule*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.exam-schedule') }}">
        <span class="material-symbols-outlined" data-icon="event_note">event_note</span>
        <span class="font-label-md text-label-md">Exam Schedule</span>
    </a>
</li>
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.digital-learning*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.digital-learning') }}">
        <span class="material-symbols-outlined" data-icon="school">school</span>
        <span class="font-label-md text-label-md">Digital Learning</span>
    </a>
</li>
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.quiz*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.quiz') }}">
        <span class="material-symbols-outlined" data-icon="quiz">quiz</span>
        <span class="font-label-md text-label-md">Quizzes</span>
    </a>
</li>
```

---

### Bug 3 — Admin "Parent Portal" Link Opens in new tab (Wrong)
**File:** `resources/views/layouts/app.blade.php`

The Admin sidebar has this:
```blade
<a href="{{ route('parent.dashboard') }}" target="_blank">Parent Portal</a>
```
`target="_blank"` should be removed. A logged-in admin cannot access the parent portal anyway because of role middleware. Remove this link entirely OR replace with a proper "Switch Role Preview" feature later. For now just remove `target="_blank"`:
```blade
<a href="{{ route('parent.dashboard') }}">Parent Portal</a>
```
But note: this link will redirect back to admin dashboard due to middleware. Best solution: remove this link from admin sidebar entirely, it serves no purpose.

---

### Bug 4 — Mobile Sidebar Has No Implementation
**File:** `resources/views/layouts/app.blade.php`

There is a mobile menu button but no mobile drawer/overlay sidebar. The sidebar is `hidden md:flex` so on mobile users have NO navigation. 

**Add after the `<nav>` closing tag:**
```blade
<!-- Mobile Sidebar Overlay -->
<div id="mobile-overlay" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-30 hidden" onclick="closeMobileSidebar()"></div>
<nav id="mobile-sidebar" class="md:hidden flex flex-col bg-surface-container w-64 h-full fixed left-0 top-0 z-40 py-md transform -translate-x-full transition-transform duration-300 ease-in-out">
    {{-- Copy the exact same <ul> sidebar content here (same role-based links) --}}
</nav>
<script>
function openMobileSidebar() {
    document.getElementById('mobile-sidebar').classList.remove('-translate-x-full');
    document.getElementById('mobile-overlay').classList.remove('hidden');
}
function closeMobileSidebar() {
    document.getElementById('mobile-sidebar').classList.add('-translate-x-full');
    document.getElementById('mobile-overlay').classList.add('hidden');
}
document.querySelector('.md\\:hidden button')?.addEventListener('click', openMobileSidebar);
</script>
```

---

### Bug 5 — `leave_requests` vs `teacher_leaves` Duplication
There are **two separate tables** for teacher leave:
- `leave_requests` (older, with `reason` missing)
- `teacher_leaves` (newer, with proper structure)

The `TeacherPortalController` uses `teacher_leaves`. The `leave_requests` table has `leave_requests_ibfk_1` FK to `teachers` but `teacher_leaves` also has FK to `teachers`. Pick ONE and delete the other via migration. Keep `teacher_leaves` (it has proper `reason` field).

**Migration to run:**
```php
Schema::dropIfExists('leave_requests');
```

Update any existing code references from `leave_requests` to `teacher_leaves`.

---

### Bug 6 — `assignment_submissions.assignment_id` Type Mismatch
**Table:** `assignment_submissions`

```sql
`assignment_id` int(11) NOT NULL   -- references assignments.id which is bigint(20) UNSIGNED
```
Type mismatch means FK constraint fails silently. Fix:
```sql
ALTER TABLE `assignment_submissions` 
MODIFY COLUMN `assignment_id` bigint(20) UNSIGNED NOT NULL;

ALTER TABLE `assignment_submissions`
ADD CONSTRAINT `assignment_submissions_assignment_id_foreign` 
FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;
```

---

### Bug 7 — `marks.exam_type_id` Has No FK Constraint
```sql
`exam_type_id` int(11) DEFAULT NULL   -- no FK to exam_types table
```
Fix:
```sql
ALTER TABLE `marks`
ADD CONSTRAINT `marks_ibfk_3` 
FOREIGN KEY (`exam_type_id`) REFERENCES `exam_types` (`id`) ON DELETE SET NULL;
```

---

### Bug 8 — `marks.exam_schedule_id` Has No FK Constraint  
```sql
`exam_schedule_id` int(10) UNSIGNED DEFAULT NULL   -- no FK to exam_schedules
```
Fix:
```sql
ALTER TABLE `marks`
ADD CONSTRAINT `marks_ibfk_5` 
FOREIGN KEY (`exam_schedule_id`) REFERENCES `exam_schedules` (`id`) ON DELETE SET NULL;
```

---

### Bug 9 — `parent_students` Has No FK Constraints
Despite having `parent_user_id` and `student_id`, there are **no FK constraints** defined in the SQL dump.
Fix:
```sql
ALTER TABLE `parent_students`
ADD CONSTRAINT `parent_students_parent_user_id_foreign` 
FOREIGN KEY (`parent_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `parent_students_student_id_foreign` 
FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
```

---

### Bug 10 — `student_leave_requests` Has No FK Constraint
```sql
`student_id` int(11) NOT NULL   -- no FK to students table
```
Fix:
```sql
ALTER TABLE `student_leave_requests`
ADD CONSTRAINT `student_leave_requests_student_id_foreign` 
FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
```

---

### Bug 11 — `teacher_assignments` Has No FK Constraints
Despite having `teacher_id`, `class_id`, `subject_id`, no FK constraints are defined.
Fix:
```sql
ALTER TABLE `teacher_assignments`
ADD CONSTRAINT `ta_teacher_id_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `ta_class_id_fk` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `ta_subject_id_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
```

---

### Bug 12 — `teacher_module_access` Has No FK Constraints
Fix:
```sql
ALTER TABLE `teacher_module_access`
ADD CONSTRAINT `tma_teacher_id_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;
```

---

### Bug 13 — `report_cards.exam_type_id` Has No FK Constraint
Fix:
```sql
ALTER TABLE `report_cards`
ADD CONSTRAINT `rc_exam_type_id_fk` 
FOREIGN KEY (`exam_type_id`) REFERENCES `exam_types` (`id`) ON DELETE SET NULL;

ALTER TABLE `report_cards`
ADD CONSTRAINT `rc_student_id_fk` 
FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

ALTER TABLE `report_cards`
ADD CONSTRAINT `rc_academic_year_id_fk` 
FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE;
```

---

### Bug 14 — `payroll` Table Completely Disconnected from Teachers
All 5 existing payroll records have `teacher_id = NULL`. The `name`, `role`, `emp_id` are stored as freetext and duplicated from the `teachers` table.

**Fix the payroll table — add school_id and fix data:**
```sql
ALTER TABLE `payroll`
ADD COLUMN `school_id` int(11) NOT NULL DEFAULT 1 AFTER `teacher_id`,
ADD CONSTRAINT `fk_payroll_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;
```

In the PayrollController, when creating payroll records, auto-populate `emp_id`, `name`, `role` by joining with `teachers` and `users` tables via the `teacher_id` FK. Do NOT let these be freetext inputs.

---

### Bug 15 — `exam_schedules` Has Redundant Denormalized Columns
The table has both:
- `class_name` VARCHAR (freetext, gets out of sync)
- `class_id` INT FK → `classes`
- `subject` VARCHAR (freetext)
- `subject_id` INT FK → `subjects`

The freetext columns exist but should be **removed** since FKs are present. Any query using `class_name` or `subject` text columns should use the JOIN instead.

**Migration:**
```sql
ALTER TABLE `exam_schedules`
DROP COLUMN `class_name`,
DROP COLUMN `subject`;
```

Update all views/controllers that reference `$exam->class_name` or `$exam->subject` to use `$exam->class->name` and `$exam->subject->name` instead (via Eloquent relationships).

---

### Bug 16 — `assets` Table Has No `school_id` (Multi-School Issue)
```sql
CREATE TABLE `assets` (no school_id column)
```
For multi-school support:
```sql
ALTER TABLE `assets`
ADD COLUMN `school_id` int(11) NOT NULL DEFAULT 1 AFTER `created_at`,
ADD CONSTRAINT `assets_school_id_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;
```

---

### Bug 17 — `hostel_assignments` Has Redundant `block`/`room` Text Columns
The table has both:
- `block` VARCHAR — freetext
- `room` VARCHAR — freetext  
- `room_id` INT → `hostel_rooms`

The freetext `block` and `room` should be populated from `hostel_rooms` via FK, not stored separately.

```sql
ALTER TABLE `hostel_assignments`
DROP COLUMN `block`,
DROP COLUMN `room`;
```

---

### Bug 18 — `inventory` and `assets` Are Duplicate Tables
There are **two separate tables** doing the same job:
- `assets` (asset_code, name, category, condition_status — no quantity)
- `inventory` (asset_code, name, category, quantity, condition_status)

The admin inventory route (`admin.inventory`) uses the `inventory` table. The `assets` table appears to be unused. 

**Resolution:** Merge `assets` into `inventory` by adding missing columns to `inventory`, then drop `assets`:
```sql
ALTER TABLE `inventory`
ADD COLUMN `school_id` int(11) NOT NULL DEFAULT 1,
ADD CONSTRAINT `inventory_school_id_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

DROP TABLE `assets`;
```

---

## PART 2 — MISSING SIDEBAR ITEMS + FULL PAGE SPECS

Below are NEW features to add to each portal. For each item: sidebar link + route + controller method + view content is specified.

---

### ADMIN PORTAL — Missing Sidebar Items

#### 2.1 — Announcements Management (Admin)
**Why needed:** `announcements` table exists but there is NO admin route to create/manage announcements. Admin sidebar has no announcements link.

**Add to `routes/web.php` in admin group:**
```php
Route::get('/announcements', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('admin.announcements');
Route::post('/announcements', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('admin.announcements.store');
Route::delete('/announcements/{id}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
```

**Create `app/Http/Controllers/AnnouncementController.php`:**
- `index()` → list all announcements with author and target_role
- `store()` → create announcement (auto-set author_id = auth()->id())
- `destroy()` → soft delete or hard delete

**DB Connections (FKs):**
```
announcements.author_id → users.id  (ON DELETE CASCADE) ← already exists
```

**View `resources/views/admin/announcements/index.blade.php` content:**
- List: Title, Content (truncated), Target Audience (All / Teacher / Student / Parent), Author, Date, Delete button
- Form section: New Announcement — Title (input), Content (textarea), Target Role (select: all, teacher, student, parent)

**Add to Admin sidebar (after School Events):**
```blade
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.announcements*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.announcements') }}">
        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
        <span class="font-label-md text-label-md">Announcements</span>
    </a>
</li>
```

---

#### 2.2 — Role & Permission Management (Admin)
**Why needed:** `roles`, `permissions`, `role_permissions` tables are fully populated but there is **no UI** to manage them.

**Add to `routes/web.php` in admin group (Super Admin only):**
```php
Route::middleware('role:Super Admin')->group(function () {
    Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles');
    Route::post('/roles/{id}/permissions', [App\Http\Controllers\RoleController::class, 'updatePermissions'])->name('admin.roles.permissions.update');
});
```

**Create `app/Http/Controllers/RoleController.php`:**
- `index()` → list all roles with their permissions (checkboxes)
- `updatePermissions($id)` → sync permissions for a role in `role_permissions`

**DB Connections (FKs):**
```
role_permissions.role_id       → roles.id        (ON DELETE CASCADE) ← exists
role_permissions.permission_id → permissions.id  (ON DELETE CASCADE) ← exists
users.role_id                  → roles.id                            ← exists
```

**View `resources/views/admin/roles/index.blade.php` content:**
- Card for each role (Super Admin, School Admin, Teacher, Student, Parent)
- Checkbox grid of all 43 permissions, pre-checked per role
- Save button per role (AJAX or form POST)
- Warning on Super Admin card: "Modifying Super Admin permissions may break system access"

**Add to Admin sidebar (in a "System" section, after AI Modules):**
```blade
<li class="px-md py-xs mt-sm">
    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">System</span>
</li>
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.roles*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.roles') }}">
        <span class="material-symbols-outlined" data-icon="admin_panel_settings">admin_panel_settings</span>
        <span class="font-label-md text-label-md">Roles & Permissions</span>
    </a>
</li>
```

---

#### 2.3 — Smart Attendance with Pattern Analysis (Admin)

**Why needed:** The existing `student_attendances` and `teacher_attendances` tables record raw data but provide NO intelligence layer. This module adds anomaly detection, fake attendance detection, and pattern insights.

**New Database Tables:**

```sql
CREATE TABLE `attendance_anomalies` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` int(11) DEFAULT NULL,
    `teacher_id` int(11) DEFAULT NULL,
    `anomaly_type` enum('fake_attendance','absence_pattern','late_pattern','consecutive_absent') NOT NULL,
    `description` text NOT NULL,
    `severity` enum('low','medium','high') NOT NULL DEFAULT 'low',
    `detected_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `resolved` tinyint(1) NOT NULL DEFAULT 0,
    `resolved_at` timestamp NULL DEFAULT NULL,
    `school_id` int(11) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`school_id`) REFERENCES `schools`(`id`) ON DELETE CASCADE
);

CREATE TABLE `attendance_patterns` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `entity_type` enum('student','teacher') NOT NULL,
    `entity_id` int(11) NOT NULL,
    `pattern_type` enum('day_of_week','monthly','subject_specific') NOT NULL,
    `pattern_key` varchar(50) NOT NULL COMMENT 'e.g. Monday, January, Math',
    `absence_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
    `total_days` int(11) NOT NULL DEFAULT 0,
    `absent_days` int(11) NOT NULL DEFAULT 0,
    `last_calculated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `school_id` int(11) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`school_id`) REFERENCES `schools`(`id`) ON DELETE CASCADE
);
```

**Add to `routes/web.php` in admin group:**
```php
Route::prefix('smart-attendance')->name('admin.smart-attendance.')->group(function () {
    Route::get('/', [App\Http\Controllers\SmartAttendanceController::class, 'dashboard'])->name('dashboard');
    Route::get('/anomalies', [App\Http\Controllers\SmartAttendanceController::class, 'anomalies'])->name('anomalies');
    Route::post('/anomalies/{id}/resolve', [App\Http\Controllers\SmartAttendanceController::class, 'resolveAnomaly'])->name('anomalies.resolve');
    Route::get('/patterns', [App\Http\Controllers\SmartAttendanceController::class, 'patterns'])->name('patterns');
    Route::post('/analyze', [App\Http\Controllers\SmartAttendanceController::class, 'runAnalysis'])->name('analyze');
    Route::get('/student/{id}', [App\Http\Controllers\SmartAttendanceController::class, 'studentInsight'])->name('student-insight');
    Route::get('/teacher/{id}', [App\Http\Controllers\SmartAttendanceController::class, 'teacherInsight'])->name('teacher-insight');
});
```

**Create `app/Http/Controllers/SmartAttendanceController.php`:**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentAttendance;
use App\Models\TeacherAttendance;
use App\Models\AttendanceAnomaly;
use App\Models\AttendancePattern;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SmartAttendanceController extends Controller
{
    /**
     * Main Smart Attendance Dashboard
     */
    public function dashboard()
    {
        $schoolId = auth()->user()->school_id;

        // Summary stats
        $totalStudents   = Student::where('school_id', $schoolId)->count();
        $totalTeachers   = Teacher::where('school_id', $schoolId)->count();
        $openAnomalies   = AttendanceAnomaly::where('school_id', $schoolId)->where('resolved', 0)->count();
        $highSeverity    = AttendanceAnomaly::where('school_id', $schoolId)->where('resolved', 0)->where('severity', 'high')->count();

        // Top 5 most absent students this month
        $month = now()->month;
        $year  = now()->year;
        $topAbsentStudents = DB::table('student_attendances')
            ->join('students', 'student_attendances.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.school_id', $schoolId)
            ->whereMonth('student_attendances.date', $month)
            ->whereYear('student_attendances.date', $year)
            ->where('student_attendances.status', 'absent')
            ->select('students.id', 'users.name', DB::raw('COUNT(*) as absent_count'))
            ->groupBy('students.id', 'users.name')
            ->orderByDesc('absent_count')
            ->limit(5)
            ->get();

        // Recent anomalies
        $recentAnomalies = AttendanceAnomaly::with(['student.user', 'teacher.user'])
            ->where('school_id', $schoolId)
            ->where('resolved', 0)
            ->orderBy('detected_at', 'desc')
            ->limit(10)
            ->get();

        // Day-of-week absence heatmap for students (last 90 days)
        $dayPatterns = DB::table('student_attendances')
            ->join('students', 'student_attendances.student_id', '=', 'students.id')
            ->where('students.school_id', $schoolId)
            ->where('student_attendances.date', '>=', now()->subDays(90))
            ->where('student_attendances.status', 'absent')
            ->select(DB::raw('DAYNAME(date) as day_name'), DB::raw('COUNT(*) as count'))
            ->groupBy('day_name')
            ->orderByRaw('FIELD(day_name,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')
            ->get();

        return view('admin.smart-attendance.dashboard', compact(
            'totalStudents', 'totalTeachers', 'openAnomalies', 'highSeverity',
            'topAbsentStudents', 'recentAnomalies', 'dayPatterns'
        ));
    }

    /**
     * Anomaly Detection List
     */
    public function anomalies()
    {
        $schoolId  = auth()->user()->school_id;
        $anomalies = AttendanceAnomaly::with(['student.user', 'teacher.user'])
            ->where('school_id', $schoolId)
            ->orderByRaw("FIELD(severity,'high','medium','low')")
            ->orderBy('detected_at', 'desc')
            ->paginate(20);
        return view('admin.smart-attendance.anomalies', compact('anomalies'));
    }

    /**
     * Mark anomaly resolved
     */
    public function resolveAnomaly($id)
    {
        AttendanceAnomaly::where('id', $id)->update([
            'resolved'    => 1,
            'resolved_at' => now(),
        ]);
        return back()->with('success', 'Anomaly marked as resolved.');
    }

    /**
     * Patterns View
     */
    public function patterns()
    {
        $schoolId = auth()->user()->school_id;
        $patterns = AttendancePattern::where('school_id', $schoolId)
            ->where('absence_percentage', '>=', 50)
            ->orderByDesc('absence_percentage')
            ->paginate(30);
        return view('admin.smart-attendance.patterns', compact('patterns'));
    }

    /**
     * Run full analysis — detect anomalies and recalculate patterns
     * Trigger manually or via scheduled command
     */
    public function runAnalysis(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $this->detectFakeAttendance($schoolId);
        $this->detectAbsencePatterns($schoolId);
        $this->detectConsecutiveAbsences($schoolId);
        return back()->with('success', 'Smart analysis complete. Anomalies updated.');
    }

    /**
     * Individual Student Insight
     */
    public function studentInsight($id)
    {
        $student = Student::with(['user', 'currentClass'])->findOrFail($id);

        // Day-of-week breakdown
        $dayBreakdown = DB::table('student_attendances')
            ->where('student_id', $id)
            ->select(
                DB::raw('DAYNAME(date) as day_name'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(status = "absent") as absent'),
                DB::raw('ROUND(SUM(status = "absent") / COUNT(*) * 100, 1) as absence_pct')
            )
            ->groupBy('day_name')
            ->orderByRaw('FIELD(day_name,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')
            ->get();

        // Monthly breakdown (last 6 months)
        $monthlyBreakdown = DB::table('student_attendances')
            ->where('student_id', $id)
            ->where('date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(date) as yr'),
                DB::raw('MONTH(date) as mo'),
                DB::raw('MONTHNAME(date) as month_name'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(status = "absent") as absent'),
                DB::raw('ROUND(SUM(status = "absent") / COUNT(*) * 100, 1) as absence_pct')
            )
            ->groupBy('yr', 'mo', 'month_name')
            ->orderBy('yr')->orderBy('mo')
            ->get();

        // Anomalies for this student
        $anomalies = AttendanceAnomaly::where('student_id', $id)->orderBy('detected_at', 'desc')->get();

        // Overall stats
        $overallStats = DB::table('student_attendances')
            ->where('student_id', $id)
            ->select(
                DB::raw('COUNT(*) as total_days'),
                DB::raw('SUM(status = "present") as present_days'),
                DB::raw('SUM(status = "absent") as absent_days'),
                DB::raw('SUM(status = "late") as late_days'),
                DB::raw('ROUND(SUM(status = "present") / COUNT(*) * 100, 1) as attendance_pct')
            )
            ->first();

        return view('admin.smart-attendance.student-insight', compact(
            'student', 'dayBreakdown', 'monthlyBreakdown', 'anomalies', 'overallStats'
        ));
    }

    /**
     * Individual Teacher Insight
     */
    public function teacherInsight($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);

        $dayBreakdown = DB::table('teacher_attendances')
            ->where('teacher_id', $id)
            ->select(
                DB::raw('DAYNAME(date) as day_name'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(status = "absent") as absent'),
                DB::raw('ROUND(SUM(status = "absent") / COUNT(*) * 100, 1) as absence_pct')
            )
            ->groupBy('day_name')
            ->orderByRaw('FIELD(day_name,"Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")')
            ->get();

        $monthlyBreakdown = DB::table('teacher_attendances')
            ->where('teacher_id', $id)
            ->where('date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(date) as yr'),
                DB::raw('MONTH(date) as mo'),
                DB::raw('MONTHNAME(date) as month_name'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(status = "absent") as absent'),
                DB::raw('ROUND(SUM(status = "absent") / COUNT(*) * 100, 1) as absence_pct')
            )
            ->groupBy('yr', 'mo', 'month_name')
            ->orderBy('yr')->orderBy('mo')
            ->get();

        $anomalies = AttendanceAnomaly::where('teacher_id', $id)->orderBy('detected_at', 'desc')->get();

        $overallStats = DB::table('teacher_attendances')
            ->where('teacher_id', $id)
            ->select(
                DB::raw('COUNT(*) as total_days'),
                DB::raw('SUM(status = "present") as present_days'),
                DB::raw('SUM(status = "absent") as absent_days'),
                DB::raw('ROUND(SUM(status = "present") / COUNT(*) * 100, 1) as attendance_pct')
            )
            ->first();

        return view('admin.smart-attendance.teacher-insight', compact(
            'teacher', 'dayBreakdown', 'monthlyBreakdown', 'anomalies', 'overallStats'
        ));
    }

    // ─── PRIVATE ANALYSIS METHODS ────────────────────────────────────────────

    /**
     * Fake Attendance Detection
     * Detects: student marked present but IP differs from all other students on same day
     * OR: attendance marked outside school hours (before 6am / after 8pm)
     */
    private function detectFakeAttendance($schoolId)
    {
        // Flag: all attendances marked for a student in under 10 seconds (impossible speed)
        $suspiciousMarkers = DB::table('student_attendances as a1')
            ->join('student_attendances as a2', function ($join) {
                $join->on('a1.marked_by', '=', 'a2.marked_by')
                     ->on('a1.date', '=', 'a2.date')
                     ->whereRaw('a1.id != a2.id')
                     ->whereRaw('ABS(TIMESTAMPDIFF(SECOND, a1.created_at, a2.created_at)) < 10');
            })
            ->join('students', 'a1.student_id', '=', 'students.id')
            ->where('students.school_id', $schoolId)
            ->select('a1.student_id', 'a1.date', 'a1.marked_by')
            ->distinct()
            ->get();

        foreach ($suspiciousMarkers as $row) {
            $exists = AttendanceAnomaly::where('student_id', $row->student_id)
                ->where('anomaly_type', 'fake_attendance')
                ->whereDate('detected_at', today())
                ->exists();
            if (!$exists) {
                AttendanceAnomaly::create([
                    'student_id'   => $row->student_id,
                    'anomaly_type' => 'fake_attendance',
                    'description'  => "Attendance for {$row->date} was marked suspiciously fast (under 10 seconds between multiple records). Possible bulk/fake marking.",
                    'severity'     => 'high',
                    'school_id'    => $schoolId,
                ]);
            }
        }
    }

    /**
     * Absence Pattern Detection
     * Example: "Student X is absent 80% on Mondays"
     */
    private function detectAbsencePatterns($schoolId)
    {
        $students = Student::where('school_id', $schoolId)->pluck('id');

        foreach ($students as $studentId) {
            $dayStats = DB::table('student_attendances')
                ->where('student_id', $studentId)
                ->select(
                    DB::raw('DAYNAME(date) as day_name'),
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(status = "absent") as absent'),
                    DB::raw('ROUND(SUM(status = "absent") / COUNT(*) * 100, 1) as pct')
                )
                ->groupBy('day_name')
                ->having('total', '>=', 5) // Only flag if at least 5 occurrences
                ->having('pct', '>=', 60)  // Flag if absent 60%+ on a specific day
                ->get();

            foreach ($dayStats as $stat) {
                // Update or insert pattern record
                AttendancePattern::updateOrCreate(
                    ['entity_type' => 'student', 'entity_id' => $studentId, 'pattern_type' => 'day_of_week', 'pattern_key' => $stat->day_name, 'school_id' => $schoolId],
                    ['absence_percentage' => $stat->pct, 'total_days' => $stat->total, 'absent_days' => $stat->absent]
                );

                // Create anomaly insight
                $exists = AttendanceAnomaly::where('student_id', $studentId)
                    ->where('anomaly_type', 'absence_pattern')
                    ->where('description', 'like', "%{$stat->day_name}%")
                    ->where('resolved', 0)
                    ->exists();

                if (!$exists) {
                    AttendanceAnomaly::create([
                        'student_id'   => $studentId,
                        'anomaly_type' => 'absence_pattern',
                        'description'  => "Student is absent {$stat->pct}% on {$stat->day_name}s ({$stat->absent} out of {$stat->total} {$stat->day_name}s).",
                        'severity'     => $stat->pct >= 80 ? 'high' : 'medium',
                        'school_id'    => $schoolId,
                    ]);
                }
            }
        }
    }

    /**
     * Consecutive Absence Detection
     * Flags students absent 3+ consecutive school days
     */
    private function detectConsecutiveAbsences($schoolId)
    {
        $students = Student::where('school_id', $schoolId)->pluck('id');

        foreach ($students as $studentId) {
            $absences = DB::table('student_attendances')
                ->where('student_id', $studentId)
                ->where('status', 'absent')
                ->orderBy('date')
                ->pluck('date')
                ->map(fn($d) => Carbon::parse($d));

            $streak = 1;
            for ($i = 1; $i < $absences->count(); $i++) {
                $diff = $absences[$i]->diffInDays($absences[$i - 1]);
                if ($diff === 1) {
                    $streak++;
                    if ($streak >= 3) {
                        $exists = AttendanceAnomaly::where('student_id', $studentId)
                            ->where('anomaly_type', 'consecutive_absent')
                            ->where('resolved', 0)
                            ->exists();
                        if (!$exists) {
                            AttendanceAnomaly::create([
                                'student_id'   => $studentId,
                                'anomaly_type' => 'consecutive_absent',
                                'description'  => "Student has been absent for {$streak} consecutive school days ending {$absences[$i]->toDateString()}.",
                                'severity'     => $streak >= 5 ? 'high' : 'medium',
                                'school_id'    => $schoolId,
                            ]);
                        }
                        break;
                    }
                } else {
                    $streak = 1;
                }
            }
        }
    }
}
```

**Create Models:**

```php
// app/Models/AttendanceAnomaly.php
class AttendanceAnomaly extends Model {
    protected $fillable = ['student_id','teacher_id','anomaly_type','description','severity','school_id','resolved','resolved_at'];
    public function student() { return $this->belongsTo(Student::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
}

// app/Models/AttendancePattern.php
class AttendancePattern extends Model {
    protected $fillable = ['entity_type','entity_id','pattern_type','pattern_key','absence_percentage','total_days','absent_days','school_id'];
}
```

**Artisan Command for Scheduled Analysis:**

Create `app/Console/Commands/RunAttendanceAnalysis.php`:
```php
php artisan make:command RunAttendanceAnalysis
```
Schedule in `app/Console/Kernel.php`:
```php
$schedule->command('attendance:analyze')->dailyAt('23:00');
```

**View `resources/views/admin/smart-attendance/dashboard.blade.php` content:**
- Summary cards: Total Students, Total Teachers, Open Anomalies (with red badge if high severity), High Severity count
- Section: "Day-of-Week Absence Heatmap" — bar chart showing which days have most absences
- Section: "Top 5 Most Absent Students This Month" — table with student name, class, absent count, and link to insight page
- Section: "Recent Anomalies" — table with type badge, student/teacher name, description, severity badge, Resolve button
- Button: "Run Smart Analysis Now" (POST to `admin.smart-attendance.analyze`)

**View `resources/views/admin/smart-attendance/student-insight.blade.php` content:**
- Student info card: Name, Class, Section, Roll No
- Overall stats: Total Days, Present, Absent, Late, Attendance %
- Table: Day-of-Week Breakdown — Day | Total | Absent | Absence % — rows with `>=60%` highlighted in red/orange
- Chart: Monthly attendance over last 6 months (line or bar)
- Section: "Detected Anomalies" — list of anomalies for this student with resolve buttons

**View `resources/views/admin/smart-attendance/anomalies.blade.php` content:**
- Filter: by type (fake_attendance, absence_pattern, consecutive_absent, late_pattern), severity, resolved status
- Table: Type, Entity (Student/Teacher name), Description, Severity badge, Detected At, Resolved, Actions
- Bulk resolve button

**Add to Admin sidebar (after AI Modules or in a dedicated "Intelligence" section):**
```blade
<li class="px-md py-xs mt-sm">
    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Intelligence</span>
</li>
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.smart-attendance*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.smart-attendance.dashboard') }}">
        <span class="material-symbols-outlined" data-icon="monitoring">monitoring</span>
        <span class="font-label-md text-label-md">Smart Attendance</span>
    </a>
</li>
```

---

#### 2.4 — Digital Learning System (Admin)

**Why needed:** Replaces traditional Library/Hostel systems. Covers notes sharing, assignment submission tracking, and quiz management from the admin/teacher side.

**New Database Tables:**

```sql
-- Notes / Study Materials
CREATE TABLE `digital_notes` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `file_path` varchar(500) DEFAULT NULL,
    `file_type` enum('pdf','doc','ppt','image','link','text') NOT NULL DEFAULT 'pdf',
    `external_url` varchar(500) DEFAULT NULL,
    `subject_id` int(11) NOT NULL,
    `class_id` int(11) NOT NULL,
    `uploaded_by` int(11) NOT NULL COMMENT 'users.id',
    `is_public` tinyint(1) NOT NULL DEFAULT 1,
    `download_count` int(11) NOT NULL DEFAULT 0,
    `school_id` int(11) NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`school_id`) REFERENCES `schools`(`id`) ON DELETE CASCADE
);

-- Quizzes
CREATE TABLE `quizzes` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `subject_id` int(11) NOT NULL,
    `class_id` int(11) NOT NULL,
    `created_by` int(11) NOT NULL COMMENT 'users.id (teacher)',
    `total_marks` int(11) NOT NULL DEFAULT 10,
    `duration_minutes` int(11) NOT NULL DEFAULT 30,
    `start_at` datetime DEFAULT NULL,
    `end_at` datetime DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT 0,
    `school_id` int(11) NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`school_id`) REFERENCES `schools`(`id`) ON DELETE CASCADE
);

-- Quiz Questions
CREATE TABLE `quiz_questions` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `quiz_id` bigint(20) UNSIGNED NOT NULL,
    `question_text` text NOT NULL,
    `option_a` varchar(500) NOT NULL,
    `option_b` varchar(500) NOT NULL,
    `option_c` varchar(500) DEFAULT NULL,
    `option_d` varchar(500) DEFAULT NULL,
    `correct_option` enum('a','b','c','d') NOT NULL,
    `marks` int(11) NOT NULL DEFAULT 1,
    `order` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE
);

-- Quiz Attempts (Student)
CREATE TABLE `quiz_attempts` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `quiz_id` bigint(20) UNSIGNED NOT NULL,
    `student_id` int(11) NOT NULL,
    `started_at` timestamp NULL DEFAULT NULL,
    `submitted_at` timestamp NULL DEFAULT NULL,
    `score` decimal(5,2) DEFAULT NULL,
    `total_marks` int(11) NOT NULL DEFAULT 0,
    `percentage` decimal(5,2) DEFAULT NULL,
    `status` enum('in_progress','submitted','timed_out') NOT NULL DEFAULT 'in_progress',
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_attempt` (`quiz_id`,`student_id`),
    FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
);

-- Quiz Answers
CREATE TABLE `quiz_answers` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `attempt_id` bigint(20) UNSIGNED NOT NULL,
    `question_id` bigint(20) UNSIGNED NOT NULL,
    `selected_option` enum('a','b','c','d') DEFAULT NULL,
    `is_correct` tinyint(1) DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`question_id`) REFERENCES `quiz_questions`(`id`) ON DELETE CASCADE
);
```

**Add to `routes/web.php` in admin group:**
```php
Route::prefix('digital-learning')->name('admin.digital-learning.')->group(function () {
    Route::get('/', [App\Http\Controllers\DigitalLearningController::class, 'index'])->name('index');
    
    // Notes
    Route::get('/notes', [App\Http\Controllers\DigitalLearningController::class, 'notes'])->name('notes');
    Route::post('/notes', [App\Http\Controllers\DigitalLearningController::class, 'storeNote'])->name('notes.store');
    Route::delete('/notes/{id}', [App\Http\Controllers\DigitalLearningController::class, 'destroyNote'])->name('notes.destroy');
    
    // Assignments (tracking only — assignments table already exists)
    Route::get('/assignments', [App\Http\Controllers\DigitalLearningController::class, 'assignments'])->name('assignments');
    Route::get('/assignments/{id}/submissions', [App\Http\Controllers\DigitalLearningController::class, 'submissionTracking'])->name('assignments.submissions');
    
    // Quizzes
    Route::get('/quizzes', [App\Http\Controllers\DigitalLearningController::class, 'quizzes'])->name('quizzes');
    Route::post('/quizzes', [App\Http\Controllers\DigitalLearningController::class, 'storeQuiz'])->name('quizzes.store');
    Route::get('/quizzes/{id}/edit', [App\Http\Controllers\DigitalLearningController::class, 'editQuiz'])->name('quizzes.edit');
    Route::post('/quizzes/{id}/questions', [App\Http\Controllers\DigitalLearningController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::delete('/quizzes/{id}', [App\Http\Controllers\DigitalLearningController::class, 'destroyQuiz'])->name('quizzes.destroy');
    Route::get('/quizzes/{id}/results', [App\Http\Controllers\DigitalLearningController::class, 'quizResults'])->name('quizzes.results');
});
```

**Create `app/Http/Controllers/DigitalLearningController.php`:**

```php
<?php

namespace App\Http\Controllers;

use App\Models\DigitalNote;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DigitalLearningController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $totalNotes       = DigitalNote::where('school_id', $schoolId)->count();
        $totalQuizzes     = Quiz::where('school_id', $schoolId)->count();
        $activeQuizzes    = Quiz::where('school_id', $schoolId)->where('is_active', 1)->count();
        $totalAssignments = Assignment::where('school_id', $schoolId)->count();
        $pendingSubmissions = AssignmentSubmission::where('status', 'submitted')->count(); // pending grade

        $recentNotes = DigitalNote::with(['subject','class','uploader'])
            ->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->limit(5)->get();

        $upcomingQuizzes = Quiz::where('school_id', $schoolId)
            ->where('is_active', 1)
            ->where('end_at', '>=', now())
            ->orderBy('start_at')
            ->limit(5)->get();

        return view('admin.digital-learning.index', compact(
            'totalNotes','totalQuizzes','activeQuizzes','totalAssignments',
            'pendingSubmissions','recentNotes','upcomingQuizzes'
        ));
    }

    // ─── NOTES ──────────────────────────────────────────────────────────────

    public function notes()
    {
        $schoolId = auth()->user()->school_id;
        $notes = DigitalNote::with(['subject','class','uploader'])
            ->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.digital-learning.notes', compact('notes'));
    }

    public function storeNote(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'subject_id'   => 'required|exists:subjects,id',
            'class_id'     => 'required|exists:classes,id',
            'file_type'    => 'required|in:pdf,doc,ppt,image,link,text',
            'external_url' => 'nullable|url',
            'file'         => 'nullable|file|max:20480', // 20MB
            'is_public'    => 'boolean',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('digital-learning/notes', 'public');
        }

        DigitalNote::create([
            ...$validated,
            'file_path'   => $filePath,
            'uploaded_by' => auth()->id(),
            'school_id'   => auth()->user()->school_id,
        ]);

        return back()->with('success', 'Note uploaded successfully.');
    }

    public function destroyNote($id)
    {
        $note = DigitalNote::findOrFail($id);
        if ($note->file_path) Storage::disk('public')->delete($note->file_path);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }

    // ─── ASSIGNMENTS ─────────────────────────────────────────────────────────

    public function assignments()
    {
        $schoolId = auth()->user()->school_id;
        $assignments = Assignment::with(['teacher.user','class','subject'])
            ->where('school_id', $schoolId)
            ->withCount(['submissions', 'submissions as graded_count' => fn($q) => $q->where('status', 'graded')])
            ->orderBy('due_date', 'desc')
            ->paginate(20);
        return view('admin.digital-learning.assignments', compact('assignments'));
    }

    public function submissionTracking($id)
    {
        $assignment  = Assignment::with(['class','subject','teacher.user'])->findOrFail($id);
        $submissions = AssignmentSubmission::with('student.user')
            ->where('assignment_id', $id)
            ->get();

        // Students who have NOT submitted
        $classStudentIds   = \App\Models\Student::where('current_class_id', $assignment->class_id)->pluck('id');
        $submittedIds      = $submissions->pluck('student_id');
        $notSubmittedIds   = $classStudentIds->diff($submittedIds);
        $notSubmitted      = \App\Models\Student::with('user')->whereIn('id', $notSubmittedIds)->get();

        return view('admin.digital-learning.submission-tracking', compact(
            'assignment','submissions','notSubmitted'
        ));
    }

    // ─── QUIZZES ─────────────────────────────────────────────────────────────

    public function quizzes()
    {
        $schoolId = auth()->user()->school_id;
        $quizzes = Quiz::with(['subject','class','creator'])
            ->where('school_id', $schoolId)
            ->withCount('questions','attempts')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.digital-learning.quizzes', compact('quizzes'));
    }

    public function storeQuiz(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'subject_id'       => 'required|exists:subjects,id',
            'class_id'         => 'required|exists:classes,id',
            'total_marks'      => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:5',
            'start_at'         => 'nullable|date',
            'end_at'           => 'nullable|date|after:start_at',
            'is_active'        => 'boolean',
        ]);
        Quiz::create([
            ...$validated,
            'created_by' => auth()->id(),
            'school_id'  => auth()->user()->school_id,
        ]);
        return back()->with('success', 'Quiz created.');
    }

    public function editQuiz($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('admin.digital-learning.quiz-edit', compact('quiz'));
    }

    public function storeQuestion(Request $request, $quizId)
    {
        $validated = $request->validate([
            'question_text'  => 'required|string',
            'option_a'       => 'required|string|max:500',
            'option_b'       => 'required|string|max:500',
            'option_c'       => 'nullable|string|max:500',
            'option_d'       => 'nullable|string|max:500',
            'correct_option' => 'required|in:a,b,c,d',
            'marks'          => 'required|integer|min:1',
        ]);
        QuizQuestion::create([...$validated, 'quiz_id' => $quizId]);
        return back()->with('success', 'Question added.');
    }

    public function destroyQuiz($id)
    {
        Quiz::findOrFail($id)->delete();
        return back()->with('success', 'Quiz deleted.');
    }

    public function quizResults($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $attempts = QuizAttempt::with('student.user')
            ->where('quiz_id', $id)
            ->where('status', 'submitted')
            ->orderByDesc('score')
            ->get();

        $avg = $attempts->avg('percentage');
        $highest = $attempts->max('score');
        $lowest  = $attempts->min('score');
        $notAttempted = \App\Models\Student::where('current_class_id', $quiz->class_id)
            ->whereNotIn('id', $attempts->pluck('student_id'))
            ->with('user')->get();

        return view('admin.digital-learning.quiz-results', compact(
            'quiz','attempts','avg','highest','lowest','notAttempted'
        ));
    }
}
```

**Create Models:**
```php
// app/Models/DigitalNote.php
class DigitalNote extends Model {
    protected $table = 'digital_notes';
    protected $fillable = ['title','description','file_path','file_type','external_url','subject_id','class_id','uploaded_by','is_public','download_count','school_id'];
    public function subject()  { return $this->belongsTo(Subject::class); }
    public function class()    { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}

// app/Models/Quiz.php
class Quiz extends Model {
    protected $fillable = ['title','description','subject_id','class_id','created_by','total_marks','duration_minutes','start_at','end_at','is_active','school_id'];
    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime'];
    public function questions() { return $this->hasMany(QuizQuestion::class); }
    public function attempts()  { return $this->hasMany(QuizAttempt::class); }
    public function subject()   { return $this->belongsTo(Subject::class); }
    public function class()     { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function creator()   { return $this->belongsTo(User::class, 'created_by'); }
}

// app/Models/QuizQuestion.php
class QuizQuestion extends Model {
    public $timestamps = false;
    protected $fillable = ['quiz_id','question_text','option_a','option_b','option_c','option_d','correct_option','marks','order'];
    public function quiz() { return $this->belongsTo(Quiz::class); }
}

// app/Models/QuizAttempt.php
class QuizAttempt extends Model {
    protected $fillable = ['quiz_id','student_id','started_at','submitted_at','score','total_marks','percentage','status'];
    protected $casts = ['started_at' => 'datetime', 'submitted_at' => 'datetime'];
    public function quiz()    { return $this->belongsTo(Quiz::class); }
    public function student() { return $this->belongsTo(Student::class); }
    public function answers() { return $this->hasMany(QuizAnswer::class, 'attempt_id'); }
}

// app/Models/QuizAnswer.php
class QuizAnswer extends Model {
    public $timestamps = false;
    protected $fillable = ['attempt_id','question_id','selected_option','is_correct'];
}
```

**View structure for `resources/views/admin/digital-learning/`:**

`index.blade.php` — Dashboard:
- 5 summary cards: Total Notes, Total Quizzes, Active Quizzes, Total Assignments, Pending Grading
- Section: Recent Notes (5 most recent with subject, class, uploader, file type badge)
- Section: Upcoming Active Quizzes (title, class, subject, start/end time, # questions)
- Quick action buttons: "Upload Note", "Create Quiz"

`notes.blade.php`:
- Filter: by subject, by class
- Table: Title, Subject, Class, File Type badge, Uploader, Uploaded Date, Downloads, Actions (View/Download/Delete)
- Modal: Upload Note form (title, description, subject, class, file type, file upload or URL, is_public toggle)

`assignments.blade.php`:
- Table: Assignment Title, Subject, Class, Teacher, Due Date, Total Submissions / Class Size, Graded Count, Actions (View Submissions)
- Color-code overdue assignments in red

`submission-tracking.blade.php`:
- Assignment info header (title, subject, class, due date, teacher)
- Two tabs:
  - "Submitted" — Student Name, Roll No, Submitted At, Status (graded/pending), Marks Obtained
  - "Not Submitted" — Student Name, Roll No, Email (for follow-up), Days Overdue

`quizzes.blade.php`:
- Filter: by class, by subject, by active/inactive
- Table: Title, Subject, Class, Questions, Duration, Marks, Start–End Time, Status badge, Attempts, Actions (Edit/Results/Delete)
- Modal: Create Quiz form

`quiz-edit.blade.php`:
- Quiz details summary at top (editable)
- Questions list (drag-reorder)
- "Add Question" form: question text, options A/B/C/D, mark correct, marks
- Delete question button per row

`quiz-results.blade.php`:
- Summary: Average Score %, Highest Score, Lowest Score, Completion Rate (attempted / class size)
- Leaderboard table: Rank, Student Name, Score, Total Marks, Percentage, Time Taken, Status
- Section: "Did Not Attempt" — list of students with contact info

**Add to Admin sidebar (in "Intelligence" section, after Smart Attendance):**
```blade
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.digital-learning*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.digital-learning.index') }}">
        <span class="material-symbols-outlined" data-icon="school">school</span>
        <span class="font-label-md text-label-md">Digital Learning</span>
    </a>
</li>
```

---

### TEACHER PORTAL — Missing Sidebar Items

The teacher sidebar already works via `teacher_module_access`. The following modules exist in the access table but have **no corresponding routes or views**. Add them:

#### 2.5 — Teacher: Exam Schedule View
**Add to `routes/web.php` in teacher group:**
```php
Route::middleware('teacher_module:exams')->get('/exam-schedule', [App\Http\Controllers\TeacherPortalController::class, 'examSchedule'])->name('teacher.exam-schedule');
```

**Add method to `TeacherPortalController`:**
```php
public function examSchedule() {
    $teacher = Teacher::where('user_id', auth()->id())->first();
    $classIds = TeacherAssignment::where('teacher_id', $teacher->id)->pluck('class_id')->unique();
    $schedules = ExamSchedule::whereIn('class_id', $classIds)
        ->with(['class', 'subject'])
        ->orderBy('exam_date')
        ->get();
    return view('teacher.exam-schedule', compact('schedules'));
}
```

**View `resources/views/teacher/exam-schedule.blade.php` content:**
- Table: Exam Type, Class, Subject, Date, Time, Max Marks, Passing Marks, Status
- Filter by class
- Status badge: Scheduled / Completed / Cancelled

**Add to Teacher sidebar:**
```blade
@if(in_array('exam_schedule', $assignedModules))
<li>
    <a class="..." href="{{ route('teacher.exam-schedule') }}">
        <span class="material-symbols-outlined">event_note</span>
        <span class="font-label-md text-label-md">Exam Schedule</span>
    </a>
</li>
@endif
```

---

#### 2.6 — Teacher: Student Leave Approval
**Why needed:** `student_leave_requests` table exists but teachers cannot approve/reject them.

**Add to `routes/web.php` in teacher group:**
```php
Route::middleware('teacher_module:leaves')->group(function () {
    Route::get('/student-leaves', [App\Http\Controllers\TeacherPortalController::class, 'studentLeaves'])->name('teacher.student-leaves');
    Route::post('/student-leaves/{id}/approve', [App\Http\Controllers\TeacherPortalController::class, 'approveStudentLeave'])->name('teacher.student-leaves.approve');
    Route::post('/student-leaves/{id}/reject', [App\Http\Controllers\TeacherPortalController::class, 'rejectStudentLeave'])->name('teacher.student-leaves.reject');
});
```

**Add to TeacherPortalController:**
```php
public function studentLeaves() {
    $teacher = Teacher::where('user_id', auth()->id())->first();
    $classIds = TeacherAssignment::where('teacher_id', $teacher->id)->pluck('class_id');
    $studentIds = Student::whereIn('current_class_id', $classIds)->pluck('id');
    $leaves = StudentLeaveRequest::whereIn('student_id', $studentIds)
        ->with('student')
        ->orderBy('created_at', 'desc')
        ->get();
    return view('teacher.student-leaves', compact('leaves'));
}
```

**View `resources/views/teacher/student-leaves.blade.php` content:**
- Filter: Pending / Approved / Rejected
- Table: Student Name, Class, Leave Type, From Date, To Date, Reason, Status, Actions (Approve/Reject buttons for Pending)

**Add to Teacher sidebar:**
```blade
@if(in_array('leaves', $assignedModules))
<li>
    <a class="..." href="{{ route('teacher.student-leaves') }}">
        <span class="material-symbols-outlined">pending_actions</span>
        <span class="font-label-md text-label-md">Student Leave Requests</span>
    </a>
</li>
@endif
```

---

#### 2.7 — Teacher: Digital Learning (Notes + Assignments + Quizzes)

**Add to `routes/web.php` in teacher group:**
```php
Route::middleware('teacher_module:digital_learning')->prefix('digital-learning')->name('teacher.digital-learning.')->group(function () {
    Route::get('/', [App\Http\Controllers\Teacher\DigitalLearningController::class, 'index'])->name('index');
    
    // Notes
    Route::post('/notes', [App\Http\Controllers\Teacher\DigitalLearningController::class, 'uploadNote'])->name('notes.store');
    Route::delete('/notes/{id}', [App\Http\Controllers\Teacher\DigitalLearningController::class, 'deleteNote'])->name('notes.destroy');
    
    // Quizzes
    Route::post('/quizzes', [App\Http\Controllers\Teacher\DigitalLearningController::class, 'createQuiz'])->name('quizzes.store');
    Route::post('/quizzes/{id}/questions', [App\Http\Controllers\Teacher\DigitalLearningController::class, 'addQuestion'])->name('quizzes.questions.store');
    Route::get('/quizzes/{id}/results', [App\Http\Controllers\Teacher\DigitalLearningController::class, 'quizResults'])->name('quizzes.results');
    
    // Assignment grading
    Route::post('/assignments/{id}/grade', [App\Http\Controllers\Teacher\DigitalLearningController::class, 'gradeSubmission'])->name('assignments.grade');
});
```

**Create `app/Http/Controllers/Teacher/DigitalLearningController.php`** — scoped to the teacher's assigned classes only. Teacher can:
- Upload notes for their assigned subjects/classes
- Create quizzes for their assigned subjects/classes
- View submission tracking and grade assignment submissions
- View quiz results

**View `resources/views/teacher/digital-learning/index.blade.php` content:**
- My Notes: list of notes I've uploaded — title, class, subject, file type, downloads, actions
- My Assignments: list of assignments I've created with submission count and grading progress bar
- My Quizzes: list of quizzes I've created with attempt count and avg score

**Add to Teacher sidebar:**
```blade
@if(in_array('digital_learning', $assignedModules))
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.digital-learning*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.digital-learning.index') }}">
        <span class="material-symbols-outlined">school</span>
        <span class="font-label-md text-label-md">Digital Learning</span>
    </a>
</li>
@endif
```

---

### STUDENT PORTAL — Missing Pages

#### 2.8 — Student: Report Card Download
**File:** `app/Http/Controllers/Student/ReportCardController.php`

The `download` route should generate a PDF report card using the `marks` table. Use `barryvdh/laravel-dompdf`. Report card should show:
- Student info, class, section, academic year
- Subject-wise marks table: Subject | Marks Obtained | Total | Percentage | Grade
- Summary: Total %, Overall Grade, Rank (from `report_cards.rank`), Remarks
- School header with school name

---

#### 2.9 — Student: Digital Learning Hub

**Add to `routes/web.php` in student group:**
```php
Route::prefix('digital-learning')->name('student.digital-learning.')->group(function () {
    Route::get('/', [App\Http\Controllers\Student\DigitalLearningController::class, 'index'])->name('index');
    Route::get('/notes/{id}/download', [App\Http\Controllers\Student\DigitalLearningController::class, 'downloadNote'])->name('notes.download');
    Route::get('/quiz/{id}', [App\Http\Controllers\Student\DigitalLearningController::class, 'startQuiz'])->name('quiz.start');
    Route::post('/quiz/{id}/submit', [App\Http\Controllers\Student\DigitalLearningController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/quiz/{id}/result', [App\Http\Controllers\Student\DigitalLearningController::class, 'quizResult'])->name('quiz.result');
});

Route::get('/digital-learning', fn() => redirect()->route('student.digital-learning.index'))->name('student.digital-learning');
Route::get('/quiz', fn() => redirect()->route('student.digital-learning.index'))->name('student.quiz');
```

**Create `app/Http/Controllers/Student/DigitalLearningController.php`:**

```php
<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DigitalNote;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalLearningController extends Controller
{
    private function getStudent() {
        return Student::where('user_id', auth()->id())->firstOrFail();
    }

    public function index()
    {
        $student = $this->getStudent();

        $notes = DigitalNote::with(['subject','uploader'])
            ->where('class_id', $student->current_class_id)
            ->where('is_public', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $quizzes = Quiz::with(['subject'])
            ->where('class_id', $student->current_class_id)
            ->where('is_active', 1)
            ->where(fn($q) => $q->whereNull('end_at')->orWhere('end_at', '>=', now()))
            ->withCount('questions')
            ->get();

        // Check which quizzes this student has already attempted
        $attemptedIds = QuizAttempt::where('student_id', $student->id)
            ->pluck('quiz_id')
            ->toArray();

        return view('student.digital-learning.index', compact('notes', 'quizzes', 'attemptedIds', 'student'));
    }

    public function downloadNote($id)
    {
        $note = DigitalNote::findOrFail($id);
        $note->increment('download_count');

        if ($note->external_url) {
            return redirect($note->external_url);
        }
        return Storage::disk('public')->download($note->file_path, $note->title);
    }

    public function startQuiz($id)
    {
        $student = $this->getStudent();
        $quiz = Quiz::with('questions')->findOrFail($id);

        // Check already attempted
        $attempt = QuizAttempt::where('quiz_id', $id)
            ->where('student_id', $student->id)
            ->first();

        if ($attempt && $attempt->status === 'submitted') {
            return redirect()->route('student.digital-learning.quiz.result', $id)
                ->with('info', 'You have already submitted this quiz.');
        }

        // Create or resume attempt
        if (!$attempt) {
            $attempt = QuizAttempt::create([
                'quiz_id'    => $id,
                'student_id' => $student->id,
                'started_at' => now(),
                'status'     => 'in_progress',
                'total_marks'=> $quiz->total_marks,
            ]);
        }

        // Shuffle questions for fairness
        $questions = $quiz->questions->shuffle();

        return view('student.digital-learning.quiz', compact('quiz', 'questions', 'attempt'));
    }

    public function submitQuiz(Request $request, $id)
    {
        $student = $this->getStudent();
        $quiz    = Quiz::with('questions')->findOrFail($id);

        $attempt = QuizAttempt::where('quiz_id', $id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $answers = $request->input('answers', []);
        $score   = 0;

        foreach ($quiz->questions as $question) {
            $selected = $answers[$question->id] ?? null;
            $correct  = $selected && strtolower($selected) === $question->correct_option;

            QuizAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $question->id],
                ['selected_option' => $selected, 'is_correct' => $correct]
            );

            if ($correct) $score += $question->marks;
        }

        $percentage = $quiz->total_marks > 0 ? round(($score / $quiz->total_marks) * 100, 2) : 0;

        $attempt->update([
            'submitted_at' => now(),
            'score'        => $score,
            'percentage'   => $percentage,
            'status'       => 'submitted',
        ]);

        return redirect()->route('student.digital-learning.quiz.result', $id)
            ->with('success', "Quiz submitted! You scored {$score}/{$quiz->total_marks} ({$percentage}%)");
    }

    public function quizResult($id)
    {
        $student = $this->getStudent();
        $quiz    = Quiz::with('questions')->findOrFail($id);

        $attempt = QuizAttempt::with('answers.question')
            ->where('quiz_id', $id)
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->firstOrFail();

        return view('student.digital-learning.quiz-result', compact('quiz', 'attempt'));
    }
}
```

**View `resources/views/student/digital-learning/index.blade.php` content:**
- Section header: "Digital Learning Hub"
- Tab 1: Study Notes
  - Filter by subject
  - Cards: Note title, subject badge, uploader (teacher name), file type icon, upload date, download button / link button
- Tab 2: Quizzes
  - Cards for each quiz: title, subject, duration, total marks, # questions
  - Status badge: "Not Attempted", "In Progress", "Completed"
  - Button: "Start Quiz" (if not attempted) / "View Result" (if completed) / "Resume" (if in_progress)

**View `resources/views/student/digital-learning/quiz.blade.php` content:**
- Quiz timer (JavaScript countdown based on `duration_minutes`)
- Progress bar: Question X of Y
- One question per page OR all questions on one scrollable page (prefer all-at-once)
- Each question: question text, radio buttons for A/B/C/D
- "Submit Quiz" button — confirm dialog before submitting
- Auto-submit on timer expiry (JavaScript form.submit())

**View `resources/views/student/digital-learning/quiz-result.blade.php` content:**
- Score card: Your Score, Total Marks, Percentage, Pass/Fail badge (pass if ≥ 50%)
- Question-by-question review: question text, your answer, correct answer, ✓ / ✗ icon
- "Back to Digital Learning" button

---

### PARENT PORTAL — Missing Sidebar Items

#### 2.10 — Parent: Exam Schedule for Child
**Add to `routes/web.php` in parent group:**
```php
Route::get('/children/{student_id}/exam-schedule', [App\Http\Controllers\ParentPortal\ExamController::class, 'show'])->name('parent.child.exam-schedule');
```

**Create `app/Http/Controllers/ParentPortal/ExamController.php`:**
```php
public function show($student_id) {
    $check = ParentStudent::where('parent_user_id', auth()->id())
        ->where('student_id', $student_id)->firstOrFail();
    
    $student = Student::with('currentClass')->findOrFail($student_id);
    $schedules = ExamSchedule::where('class_id', $student->current_class_id)
        ->with('subject')
        ->orderBy('exam_date')
        ->get();
    return view('parent.child-exam-schedule', compact('student', 'schedules'));
}
```

**View `resources/views/parent/child-exam-schedule.blade.php` content:**
- Child name header (with class/section)
- Table: Subject, Exam Type, Date, Time, Max Marks, Passing Marks, Status
- Note: "Exams in RED are within 3 days" (conditional CSS)

**Add to Parent sidebar:**
```blade
<li>
    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('parent.child.exam-schedule*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('parent.children') }}">
        <span class="material-symbols-outlined" data-icon="event_note">event_note</span>
        <span class="font-label-md text-label-md">Exam Schedule</span>
    </a>
</li>
```

---

#### 2.11 — Parent: Child Report Card
**Add to `routes/web.php` in parent group:**
```php
Route::get('/children/{student_id}/report-card', [App\Http\Controllers\ParentPortal\ReportCardController::class, 'show'])->name('parent.child.report-card');
```

**Create `app/Http/Controllers/ParentPortal/ReportCardController.php`:**
```php
public function show($student_id) {
    ParentStudent::where('parent_user_id', auth()->id())
        ->where('student_id', $student_id)->firstOrFail();
    $student = Student::with(['currentClass'])->findOrFail($student_id);
    $marks = Mark::where('student_id', $student_id)
        ->with('subject', 'academicYear')
        ->get();
    $reportCard = ReportCard::where('student_id', $student_id)->latest()->first();
    return view('parent.child-report-card', compact('student', 'marks', 'reportCard'));
}
```

**View `resources/views/parent/child-report-card.blade.php` content:**
- Student info, marks table, overall grade, rank, remarks (read-only)

**Add to Parent sidebar:**
```blade
<li>
    <a class="..." href="{{ route('parent.children') }}">
        <span class="material-symbols-outlined">description</span>
        <span class="font-label-md text-label-md">Report Card</span>
    </a>
</li>
```

---

#### 2.12 — Parent: Leave Application for Child
**Add to `routes/web.php` in parent group:**
```php
Route::get('/children/{student_id}/leave', [App\Http\Controllers\ParentPortal\LeaveController::class, 'show'])->name('parent.child.leave');
Route::post('/children/{student_id}/leave', [App\Http\Controllers\ParentPortal\LeaveController::class, 'store'])->name('parent.child.leave.store');
```

**Create `app/Http/Controllers/ParentPortal/LeaveController.php`:**
```php
public function show($student_id) {
    ParentStudent::where('parent_user_id', auth()->id())
        ->where('student_id', $student_id)->firstOrFail();
    $leaves = StudentLeaveRequest::where('student_id', $student_id)
        ->orderBy('created_at', 'desc')->get();
    return view('parent.child-leave', compact('student', 'leaves'));
}

public function store(Request $request, $student_id) {
    ParentStudent::where('parent_user_id', auth()->id())
        ->where('student_id', $student_id)->firstOrFail();
    $validated = $request->validate([
        'leave_type' => 'required|string',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'reason'     => 'required|string|max:500',
    ]);
    $validated['student_id'] = $student_id;
    StudentLeaveRequest::create($validated);
    return back()->with('success', 'Leave application submitted.');
}
```

---

## PART 3 — COMPLETE FOREIGN KEY REFERENCE MAP

This is the complete FK map for the entire database. Use this when building Eloquent relationships.

```
users.role_id               → roles.id

students.user_id            → users.id
students.current_class_id   → classes.id
students.current_section_id → sections.id
students.school_id          → schools.id

teachers.user_id            → users.id
(teachers.school_id is bigint UNSIGNED — fix to int(11) for consistency)

teacher_assignments.teacher_id → teachers.id   ← ADD FK (Bug 11)
teacher_assignments.class_id   → classes.id    ← ADD FK (Bug 11)
teacher_assignments.subject_id → subjects.id   ← ADD FK (Bug 11)

teacher_module_access.teacher_id → teachers.id  ← ADD FK (Bug 12)

classes.school_id → schools.id
sections.class_id → classes.id
sections.school_id → schools.id
subjects.class_id → classes.id

marks.student_id        → students.id
marks.subject_id        → subjects.id
marks.exam_type_id      → exam_types.id       ← ADD FK (Bug 7)
marks.exam_schedule_id  → exam_schedules.id   ← ADD FK (Bug 8)
marks.academic_year_id  → academic_years.id

report_cards.student_id       → students.id        ← ADD FK (Bug 13)
report_cards.academic_year_id → academic_years.id  ← ADD FK (Bug 13)
report_cards.exam_type_id     → exam_types.id       ← ADD FK (Bug 13)

exam_schedules.class_id        → classes.id         ← exists
exam_schedules.subject_id      → subjects.id        ← exists
exam_schedules.academic_year_id → academic_years.id ← exists
exam_schedules.school_id       → schools.id         ← exists

student_attendances.student_id       → students.id
student_attendances.academic_year_id → academic_years.id
student_attendances.marked_by        → users.id

teacher_attendances.teacher_id → teachers.id
teacher_leaves.teacher_id      → teachers.id

fees.student_id          → students.id
fee_payments.fee_id      → fees.id
fee_structures.class_id  → classes.id
fee_structures.academic_year_id → academic_years.id

assignments.teacher_id → teachers.id
assignments.class_id   → classes.id
assignments.subject_id → subjects.id

assignment_submissions.assignment_id → assignments.id  ← FIX TYPE (Bug 6)
assignment_submissions.student_id    → students.id     ← ADD FK

announcements.author_id → users.id

messages.sender_id   → users.id
messages.receiver_id → users.id

notifications.user_id → users.id

parent_students.parent_user_id → users.id    ← ADD FK (Bug 9)
parent_students.student_id     → students.id ← ADD FK (Bug 9)

student_leave_requests.student_id → students.id ← ADD FK (Bug 10)

payroll.teacher_id → teachers.id
payroll.school_id  → schools.id  ← ADD COLUMN + FK (Bug 14)

inventory.school_id → schools.id ← ADD COLUMN + FK

assets: DROP TABLE (Bug 18 — merged into inventory)

timetables.timetable_version_id → timetable_versions.id
timetables.section_id_ref       → sections.id
timetables.subject_id_ref       → subjects.id
timetables.teacher_id           → teachers.id

roles.id (standalone)
permissions.id (standalone)
role_permissions.role_id       → roles.id
role_permissions.permission_id → permissions.id

audit_logs.user_id → users.id

events (no FK — add school_id)

-- NEW: Smart Attendance
attendance_anomalies.student_id → students.id
attendance_anomalies.teacher_id → teachers.id
attendance_anomalies.school_id  → schools.id

attendance_patterns.school_id → schools.id

-- NEW: Digital Learning
digital_notes.subject_id   → subjects.id
digital_notes.class_id     → classes.id
digital_notes.uploaded_by  → users.id
digital_notes.school_id    → schools.id

quizzes.subject_id  → subjects.id
quizzes.class_id    → classes.id
quizzes.created_by  → users.id
quizzes.school_id   → schools.id

quiz_questions.quiz_id → quizzes.id
quiz_attempts.quiz_id    → quizzes.id
quiz_attempts.student_id → students.id
quiz_answers.attempt_id  → quiz_attempts.id
quiz_answers.question_id → quiz_questions.id
```

---

## PART 4 — MISSING ELOQUENT MODELS

These tables have no Model file. Create them:

| Table | Model Class | File |
|---|---|---|
| `parent_students` | `ParentStudent` | `app/Models/ParentStudent.php` |
| `student_leave_requests` | `StudentLeaveRequest` | `app/Models/StudentLeaveRequest.php` |
| `teacher_assignments` | `TeacherAssignment` | `app/Models/TeacherAssignment.php` |
| `report_cards` | `ReportCard` | `app/Models/ReportCard.php` |
| `audit_logs` | `AuditLog` | `app/Models/AuditLog.php` |
| `events` | `SchoolEvent` | `app/Models/SchoolEvent.php` |
| `announcements` | `Announcement` | `app/Models/Announcement.php` |
| `notifications` | `SchoolNotification` | `app/Models/SchoolNotification.php` |
| `attendance_anomalies` | `AttendanceAnomaly` | `app/Models/AttendanceAnomaly.php` |
| `attendance_patterns` | `AttendancePattern` | `app/Models/AttendancePattern.php` |
| `digital_notes` | `DigitalNote` | `app/Models/DigitalNote.php` |
| `quizzes` | `Quiz` | `app/Models/Quiz.php` |
| `quiz_questions` | `QuizQuestion` | `app/Models/QuizQuestion.php` |
| `quiz_attempts` | `QuizAttempt` | `app/Models/QuizAttempt.php` |
| `quiz_answers` | `QuizAnswer` | `app/Models/QuizAnswer.php` |

Each model should include `$fillable`, `$table`, and relationship methods (`belongsTo`, `hasMany`) based on the FK map in Part 3.

---

## PART 5 — SUMMARY MIGRATION FILE

Create one migration `2026_06_09_000001_fix_all_foreign_keys_and_schema.php` that executes ALL fixes in this order:

1. Drop redundant columns from `exam_schedules` (Bug 15)
2. Drop `block`, `room` from `hostel_assignments` (Bug 17)
3. Modify `assignment_submissions.assignment_id` type (Bug 6)
4. Add FK to `assignment_submissions.assignment_id` (Bug 6)
5. Add FK to `marks.exam_type_id` (Bug 7)
6. Add FK to `marks.exam_schedule_id` (Bug 8)
7. Add FKs to `parent_students` (Bug 9)
8. Add FK to `student_leave_requests.student_id` (Bug 10)
9. Add FKs to `teacher_assignments` (Bug 11)
10. Add FK to `teacher_module_access.teacher_id` (Bug 12)
11. Add FKs to `report_cards` (Bug 13)
12. Add `school_id` + FK to `payroll` (Bug 14)
13. Add `school_id` + FK to `assets` — then run `INSERT INTO inventory SELECT ...` migration — then DROP TABLE `assets` (Bug 18)
14. Add `school_id` to `inventory` (Bug 16)
15. Add `school_id` to `transport_routes` and `hostel_rooms`
16. Drop `leave_requests` table (Bug 5)
17. Create `attendance_anomalies` table (Smart Attendance)
18. Create `attendance_patterns` table (Smart Attendance)
19. Create `digital_notes` table (Digital Learning)
20. Create `quizzes` table (Digital Learning)
21. Create `quiz_questions` table (Digital Learning)
22. Create `quiz_attempts` table (Digital Learning)
23. Create `quiz_answers` table (Digital Learning)

Wrap everything in `Schema::disableForeignKeyConstraints()` and `Schema::enableForeignKeyConstraints()`.

---

## PART 6 — TEACHER `school_id` TYPE INCONSISTENCY

**File:** `teachers` table

```sql
`school_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1
```

All other tables use `int(11)` for school_id. This inconsistency can cause issues in JOINs.

**Fix:**
```sql
ALTER TABLE `teachers`
MODIFY COLUMN `school_id` int(11) NOT NULL DEFAULT 1;
```

---

## IMPLEMENTATION ORDER (Priority)

1. **FIRST** — All database FK fixes (Part 1, Bugs 6–18) via single migration
2. **SECOND** — Create new tables: `attendance_anomalies`, `attendance_patterns`, `digital_notes`, `quizzes`, `quiz_questions`, `quiz_attempts`, `quiz_answers`
3. **THIRD** — Sidebar Bug fixes (Bugs 1–4)  
4. **FOURTH** — Add missing Student sidebar links (Bug 2)
5. **FIFTH** — Create missing Models (Part 4)
6. **SIXTH** — Build Smart Attendance module: `SmartAttendanceController` + all views + Models
7. **SEVENTH** — Build Digital Learning module (Admin + Teacher + Student): `DigitalLearningController` (admin), `Teacher/DigitalLearningController`, `Student/DigitalLearningController` + all views
8. **EIGHTH** — Build Announcements + Roles pages (Part 2, items 2.1–2.2)
9. **NINTH** — Build Teacher missing features (Part 2, items 2.5–2.6)
10. **TENTH** — Build Parent missing pages (Part 2, items 2.10–2.12)

---

*End of Newmkhanschool Master Fix Prompt — Antigravity System v2*
*Changes from v1: Removed Library System, Hostel System, Transport System, Health Records. Added Smart Attendance with Pattern Analysis and Digital Learning System (Notes, Assignments, Quizzes).*
