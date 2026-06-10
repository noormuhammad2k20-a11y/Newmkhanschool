# NewMkhanSchool — Parent Portal Completion Prompt
**Version:** v1.1 | **Date:** 2026-06-10  
**Project:** NewMkhanSchool (Laravel 11, PHP 8.2, MariaDB, Blade)  
**Repo:** https://github.com/noormuhammad2k20-a11y/Newmkhanschool  
**Target Role:** Parent (role_id = 5)

---

## 1. CONTEXT & CURRENT STATE

### Stack
- Laravel 11 | PHP 8.2 | MariaDB 10.4
- Auth: custom `same_school` + `role:Parent` middleware
- Parent linked to students via `parent_students` table (`parent_user_id`, `student_id`)
- Blade templates, Bootstrap/Tailwind frontend (match existing layout)
- PDF: `barryvdh/laravel-dompdf` already installed

### What Is Already Built (DO NOT rebuild)
| Route | Controller | Status |
|---|---|---|
| `GET /parent/dashboard` | `ParentPortal\DashboardController@index` | ✅ Exists |
| `GET /parent/children` | `ParentPortal\DashboardController@children` | ✅ Exists |
| `GET /parent/children/{id}/attendance` | `ParentPortal\AttendanceController@show` | ✅ Exists |
| `GET /parent/children/{id}/marks` | `ParentPortal\MarksController@show` | ✅ Exists |
| `GET /parent/children/{id}/fees` | `ParentPortal\FeeController@show` | ✅ Exists |
| `GET /parent/children/{id}/timetable` | `ParentPortal\TimetableController@show` | ✅ Exists |
| `GET /parent/children/{id}/assignments` | `ParentPortal\AssignmentController@show` | ✅ Exists |
| `GET /parent/children/{id}/exam-schedule` | `ParentPortal\ExamController@show` | ✅ Exists |
| `GET /parent/children/{id}/report-card` | `ParentPortal\ReportCardController@show` | ✅ Exists (view only) |
| `GET+POST /parent/children/{id}/leave` | `ParentPortal\LeaveController` | ✅ Exists |
| `GET /parent/announcements` | `ParentPortal\AnnouncementController@index` | ✅ Exists |
| `GET+POST /parent/messages` | `ParentPortal\MessageController` | ✅ Exists |
| `GET /parent/transport` | `ParentPortal\TransportController@index` | ✅ Exists |
| `GET+PUT /parent/profile` | `ParentPortal\ProfileController` | ✅ Exists |

### Relevant Database Tables (already exist, no migration needed unless noted)
```
parent_students          (parent_user_id, student_id)
students                 (id, name, roll_no, class_id, section_id, ...)
student_attendances      (student_id, date, status [P/A/L], academic_year_id)
attendance_patterns      (student_id, month, year, present_days, absent_days, attendance_pct)
fees                     (id, student_id, amount, due_date, status [Pending/Paid/Overdue])
fee_payments             (id, fee_id, amount_paid, payment_method, transaction_ref)
fee_payment_transactions (id, fee_id, student_id, gateway, amount, status, ref_no, ...)
fee_receipts             (id, receipt_no, transaction_id, student_id, fee_id, amount, pdf_path)
notifications            (id, user_id, type, title, body, is_read, action_url, created_at)
messages                 (id, sender_id, receiver_id, subject, body, is_read)
online_exams             (id, title, class_id, subject_id, ...)
exam_attempts            (id, online_exam_id, student_id, score, total_marks, submitted_at)
student_leave_requests   (id, student_id, leave_type, start_date, end_date, reason, status)
report_cards             (id, student_id, academic_year_id, ...)
marks                    (id, student_id, subject_id, exam_schedule_id, marks_obtained, ...)
timetables               (id, class_id, section_id, day, period, subject_id, teacher_id)
assignments              (id, teacher_id, class_id, subject_id, title, due_date, type)
assignment_submissions   (id, assignment_id, student_id, submitted_at, file_path, grade)
```

---

## 2. WHAT NEEDS TO BE BUILT

Build all 7 modules below. Each module includes: Routes, Controller, Blade view, and any Model additions needed.

---

### MODULE 1 — Fee Payment for Parent
**Gap:** Parent can VIEW fees but cannot PAY. Student portal has full JazzCash/EasyPaisa integration. Replicate for Parent.

**Add to `routes/web.php`** (inside existing `parent.` route group):
```php
Route::get('/children/{student_id}/fees/{fee_id}/pay',            [App\Http\Controllers\ParentPortal\FeePaymentController::class, 'initiate'])->name('child.fees.pay');
Route::post('/children/{student_id}/fees/{fee_id}/jazzcash',      [App\Http\Controllers\ParentPortal\FeePaymentController::class, 'processJazzCash'])->name('child.fees.jazzcash');
Route::post('/children/{student_id}/fees/{fee_id}/easypaisa',     [App\Http\Controllers\ParentPortal\FeePaymentController::class, 'processEasyPaisa'])->name('child.fees.easypaisa');
Route::get('/children/{student_id}/fees/receipt/{receipt_id}',    [App\Http\Controllers\ParentPortal\FeePaymentController::class, 'downloadReceipt'])->name('child.fees.receipt.download');
```

**Create:** `app/Http/Controllers/ParentPortal/FeePaymentController.php`
- `initiate($student_id, $fee_id)`: validate parent owns student, load fee, return `parent.fees.pay` view
- `processJazzCash(Request, $student_id, $fee_id)`: same logic as `Student\OnlineFeePaymentController@processJazzCash` — copy and adapt namespace. Create `fee_payment_transactions` record with `gateway='JazzCash'`, create `fee_receipts` record, update `fees.status = 'Paid'`.
- `processEasyPaisa(Request, $student_id, $fee_id)`: same as above for EasyPaisa. Redirect to callback handled by `easyPaisaCallback`.
- `downloadReceipt($student_id, $receipt_id)`: verify `fee_receipts.student_id` belongs to parent's children, generate PDF using dompdf from `parent.fees.receipt-pdf` Blade view, return download.

**Create Blade views:**
- `resources/views/parent/fees/pay.blade.php` — payment gateway selection (JazzCash / EasyPaisa buttons), fee summary card
- `resources/views/parent/fees/receipt-pdf.blade.php` — printable receipt (school logo, student name, receipt no, amount, date, payment method)

**Update existing** `resources/views/parent/fees/show.blade.php`:
- Add "Pay Now" button per unpaid fee row linking to `route('parent.child.fees.pay', [$student_id, $fee->id])`
- Add "Download Receipt" button per paid fee linking to `route('parent.child.fees.receipt.download', [$student_id, $receipt->id])`

---

### MODULE 2 — Report Card PDF Download
**Gap:** `parent.child.report-card` route exists (view only). No download. Student portal has download.

**Add to `routes/web.php`** (inside `parent.` group):
```php
Route::get('/children/{student_id}/report-card/download', [App\Http\Controllers\ParentPortal\ReportCardController::class, 'download'])->name('child.report-card.download');
```

**Update:** `app/Http/Controllers/ParentPortal/ReportCardController.php`
Add `download($student_id)` method:
```php
public function download($student_id)
{
    abort_unless($this->parentOwnsStudent($student_id), 403);
    $student = Student::with(['currentClass','currentSection','marks.subject','marks.examSchedule'])->findOrFail($student_id);
    $academicYear = AcademicYear::where('is_active', 1)->first();
    $pdf = Pdf::loadView('parent.report-card-pdf', compact('student', 'academicYear'));
    return $pdf->download("report-card-{$student->name}.pdf");
}
```

**Create:** `resources/views/parent/report-card-pdf.blade.php`
- School header, student info table, marks table (subject | exam | marks obtained | max marks | grade), attendance summary, class teacher remarks placeholder, principal signature line.

---

### MODULE 3 — Notifications Bell
**Gap:** `notifications` table exists (user_id, type, title, body, is_read, action_url) but no UI for parent.

**Add to `routes/web.php`:**
```php
Route::get('/notifications',              [App\Http\Controllers\ParentPortal\NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/read',   [App\Http\Controllers\ParentPortal\NotificationController::class, 'markRead'])->name('notifications.read');
Route::post('/notifications/read-all',    [App\Http\Controllers\ParentPortal\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
Route::get('/notifications/unread-count', [App\Http\Controllers\ParentPortal\NotificationController::class, 'unreadCount'])->name('notifications.count');
```

**Create:** `app/Http/Controllers/ParentPortal/NotificationController.php`
- `index()`: `Notification::where('user_id', auth()->id())->orderByDesc('created_at')->paginate(20)`
- `markRead($id)`: find + update `is_read=1`, return JSON `{success: true}`
- `markAllRead()`: bulk update, redirect back
- `unreadCount()`: return JSON `{count: N}` — used by AJAX bell polling

**Create:** `resources/views/parent/notifications/index.blade.php`
- List with unread highlighted, "Mark all read" button, clickable `action_url`

**Update parent layout** `resources/views/parent/layouts/app.blade.php` (or wherever nav is):
- Add bell icon in navbar with badge: `<span id="notif-badge">{{ $unreadCount }}</span>`
- AJAX polling every 60s: `fetch('/parent/notifications/unread-count')` → update badge

**Create Model** (if not exists): `app/Models/Notification.php`
```php
class Notification extends Model {
    protected $table = 'notifications';
    public $timestamps = false;
    protected $fillable = ['user_id','type','title','body','is_read','action_url'];
}
```

**Trigger notifications automatically** — update existing controllers to insert notification records:
- When attendance marked Absent: insert notification for parent (`type='attendance'`, title="Child Absent Today")
- When fee becomes Overdue (already in FeeController): insert `type='fee_overdue'`
- When leave request approved/rejected (TeacherPortalController@approveStudentLeave): insert `type='leave_update'`

---

### MODULE 4 — Online Exam Results (Read-Only)
**Gap:** Student can see online exam results at `/student/online-exams/{id}/result`. Parent has no visibility.

**Add to `routes/web.php`:**
```php
Route::get('/children/{student_id}/online-exams',           [App\Http\Controllers\ParentPortal\OnlineExamController::class, 'index'])->name('child.online-exams.index');
Route::get('/children/{student_id}/online-exams/{exam_id}', [App\Http\Controllers\ParentPortal\OnlineExamController::class, 'result'])->name('child.online-exams.result');
```

**Create:** `app/Http/Controllers/ParentPortal/OnlineExamController.php`
```php
public function index($student_id)
{
    abort_unless($this->parentOwnsStudent($student_id), 403);
    $student  = Student::findOrFail($student_id);
    $attempts = ExamAttempt::with('onlineExam.subject')
        ->where('student_id', $student_id)
        ->whereNotNull('submitted_at')
        ->orderByDesc('submitted_at')->get();
    return view('parent.online-exams.index', compact('student', 'attempts'));
}

public function result($student_id, $exam_id)
{
    abort_unless($this->parentOwnsStudent($student_id), 403);
    $attempt = ExamAttempt::with('onlineExam.subject', 'answers.question')
        ->where('student_id', $student_id)
        ->where('online_exam_id', $exam_id)
        ->firstOrFail();
    return view('parent.online-exams.result', compact('attempt'));
}
```

**Create Blade views:**
- `resources/views/parent/online-exams/index.blade.php` — table: Exam | Subject | Score | Total | Percentage | Date
- `resources/views/parent/online-exams/result.blade.php` — score summary card, per-question correct/wrong breakdown (read-only, no answers editable)

---

### MODULE 5 — Attendance Monthly Chart (Dashboard Enhancement)
**Gap:** `attendance_patterns` table exists (student_id, month, year, present_days, absent_days, attendance_pct) but parent dashboard only shows a single percentage number. No visual trend.

**Update:** `app/Http/Controllers/ParentPortal/DashboardController.php`

In `index()` method, add for each child inside the foreach loop:
```php
$patterns = AttendancePattern::where('student_id', $child->id)
    ->orderByRaw('year ASC, month ASC')
    ->take(6)->get();
$childSummaries[$child->id]['monthly_chart'] = $patterns->map(fn($p) => [
    'label'   => date('M Y', mktime(0, 0, 0, $p->month, 1, $p->year)),
    'present' => $p->present_days,
    'absent'  => $p->absent_days,
]);
```

**Update:** `resources/views/parent/dashboard.blade.php`
- Per child card: add a small Chart.js bar chart (present=green, absent=red bars, last 6 months)
- Include Chart.js CDN: `<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>`
- Render chart with inline `<canvas id="chart-{{ $child->id }}">` and JS: `new Chart(ctx, {type:'bar', data:{...}})`

---

### MODULE 6 — Leave Request Status Tracking
**Gap:** Parent submits leave requests but has no way to see approval status after submission. The `student_leave_requests.status` field has Pending/Approved/Rejected but is not displayed.

**Update existing** `app/Http/Controllers/ParentPortal/LeaveController.php`
In `show($student_id)`, change query to paginate and pass history:
```php
$leaves = StudentLeaveRequest::where('student_id', $student_id)
    ->orderByDesc('created_at')->paginate(15);
return view('parent.leave.show', compact('student', 'leaves'));
```

**Update existing** `resources/views/parent/leave/show.blade.php`
- Add status column with color-coded badge: Pending=yellow, Approved=green, Rejected=red
- Move "Apply Leave" form to bottom or a modal so the history table is prominent at top

---

### MODULE 7 — Assignment Submission Status
**Gap:** Parent sees assignments but doesn't know if child has submitted them. `assignment_submissions` table has `student_id, assignment_id, submitted_at` but is not queried.

**Update:** `app/Http/Controllers/ParentPortal/AssignmentController.php`
```php
public function show($student_id)
{
    abort_unless($this->parentOwnsStudent($student_id), 403);
    $student     = Student::with('currentClass')->findOrFail($student_id);
    $assignments = Assignment::where('class_id', $student->class_id)
        ->orderByDesc('due_date')
        ->get()
        ->map(function ($a) use ($student_id) {
            $a->submission = AssignmentSubmission::where('assignment_id', $a->id)
                ->where('student_id', $student_id)->first();
            return $a;
        });
    return view('parent.assignments.show', compact('student', 'assignments'));
}
```

**Update existing** `resources/views/parent/assignments/show.blade.php`
- Add "Submission" column: Submitted ✅ (with date) | Not Submitted ❌ (red if past due_date)

---

## 3. SHARED HELPER METHOD

All parent controllers need `parentOwnsStudent()`. Create a base class:

**Create:** `app/Http/Controllers/ParentPortal/BaseParentController.php`
```php
<?php
namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;

abstract class BaseParentController extends Controller
{
    protected function parentOwnsStudent(int $student_id): bool
    {
        return ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)
            ->exists();
    }

    protected function getLinkedStudentIds(): \Illuminate\Support\Collection
    {
        return ParentStudent::where('parent_user_id', auth()->id())
            ->pluck('student_id');
    }
}
```

Update ALL ParentPortal controllers to extend `BaseParentController` instead of `Controller`. This includes both new and existing: `DashboardController`, `FeeController`, `FeePaymentController`, `AttendanceController`, `MarksController`, `LeaveController`, `AssignmentController`, `ReportCardController`, `ExamController`, `OnlineExamController`, `TimetableController`, `NotificationController`, `MessageController`, `TransportController`, `ProfileController`.

---

## 4. SIDEBAR NAVIGATION UPDATE

**Update** parent sidebar Blade partial (wherever `parent.layouts.sidebar` or equivalent is):

```html
<!-- Already existing - keep -->
<li><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
<li><a href="{{ route('parent.children') }}">My Children</a></li>
<li><a href="{{ route('parent.announcements') }}">Announcements</a></li>
<li><a href="{{ route('parent.messages') }}">Messages</a></li>
<li><a href="{{ route('parent.transport') }}">Transport</a></li>
<li><a href="{{ route('parent.profile') }}">Profile</a></li>

<!-- NEW items to add -->
<li>
    <a href="{{ route('parent.notifications.index') }}">
        Notifications
        <span class="badge" id="sidebar-notif-count"></span>
    </a>
</li>
```

Per-child section links (when viewing a specific child):
- Online Exam Results → `route('parent.child.online-exams.index', $student_id)`

---

## 5. MODELS REQUIRED

Ensure these Eloquent models exist (create if missing):

```php
// app/Models/Notification.php
class Notification extends Model {
    protected $table    = 'notifications';
    public    $timestamps = false;
    protected $fillable = ['user_id','type','title','body','is_read','action_url','created_at'];
    public function user() { return $this->belongsTo(User::class); }
}

// app/Models/AttendancePattern.php
class AttendancePattern extends Model {
    protected $table    = 'attendance_patterns';
    public    $timestamps = false;
    protected $fillable = ['student_id','month','year','present_days','absent_days','attendance_pct'];
}

// app/Models/ExamAttempt.php
class ExamAttempt extends Model {
    protected $table    = 'exam_attempts';
    protected $fillable = ['online_exam_id','student_id','score','total_marks','submitted_at'];
    public function onlineExam() { return $this->belongsTo(OnlineExam::class); }
    public function answers()    { return $this->hasMany(ExamAnswer::class, 'attempt_id'); }
}
```

---

## 6. NO NEW MIGRATIONS NEEDED

All 7 modules above use **existing tables only**. Do NOT create new tables.  
The only schema action needed: verify `assignment_submissions` has `grade` column — if not, it's optional display, just skip it.

---

## 7. SECURITY RULES (ENFORCE IN ALL CONTROLLERS)

1. Every controller method accessing student data MUST call `abort_unless($this->parentOwnsStudent($student_id), 403)` as the **first line**.
2. Fee receipt download MUST verify `fee_receipts.student_id` is in `getLinkedStudentIds()`.
3. Notifications MUST only show `user_id = auth()->id()`.
4. No parent should ever see another parent's child data under any circumstance.

---

## 8. IMPLEMENTATION ORDER (Recommended)

1. `BaseParentController` → update all existing controllers to extend it  
2. Module 4 (Online Exam Results) — read-only, simple query joins  
3. Module 7 (Assignment Submission Status) — update existing controller  
4. Module 6 (Leave Status) — update existing controller  
5. Module 5 (Dashboard Attendance Chart) — update existing dashboard  
6. Module 2 (Report Card PDF) — requires dompdf  
7. Module 3 (Notifications) — requires navbar update + AJAX  
8. Module 1 (Fee Payment) — most complex, save for last  

---

## 9. BLADE LAYOUT CONSISTENCY

- Extend `parent.layouts.app` (or whatever the parent portal layout is named)
- Match existing sidebar style exactly
- Use same card/table component classes as existing parent views
- All new pages need breadcrumb: `Home > Children > [Child Name] > [Section]`

---

## 10. DELIVERABLES CHECKLIST

- [ ] `BaseParentController.php`
- [ ] `FeePaymentController.php` + 2 Blade views (`pay.blade.php`, `receipt-pdf.blade.php`)
- [ ] `ReportCardController.php` (updated with `download()`) + `report-card-pdf.blade.php`
- [ ] `NotificationController.php` + `notifications/index.blade.php` + navbar bell update
- [ ] `OnlineExamController.php` (ParentPortal) + 2 Blade views (`index`, `result`)
- [ ] `DashboardController.php` (updated with monthly chart data)
- [ ] `dashboard.blade.php` (updated with Chart.js monthly chart per child)
- [ ] `LeaveController.php` (updated with status badges + paginate)
- [ ] `AssignmentController.php` (updated with submission status)
- [ ] Models: `Notification.php`, `AttendancePattern.php`, `ExamAttempt.php`
- [ ] `routes/web.php` (all new routes added inside `parent.` group)
- [ ] Sidebar navigation updated

---

*End of Prompt — NewMkhanSchool Parent Portal Completion v1.1*
