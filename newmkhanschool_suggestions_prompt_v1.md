# NewMkhanSchool — Suggestions S-01 to S-10 Implementation Prompt
**Project:** NewMkhanSchool (Laravel · PHP 8.2 · Blade · MariaDB · dompdf · Chart.js)  
**Repo:** https://github.com/noormuhammad2k20-a11y/Newmkhanschool  
**Constraint:** Existing theme/CSS/layout/colors/fonts bilkul unchanged. Sirf backend logic + minimal Blade additions.

---

## GLOBAL RULES (Har feature pe apply hoti hain)

1. Existing controllers mein **new methods add karo**, poora controller rewrite mat karo.
2. Blade files mein sirf **targeted sections add karo** — layout/header/sidebar touch mat karo.
3. Har route `routes/web.php` mein existing route groups ke andar add karo.
4. Models jo already exist karti hain unhe reuse karo — naya model sirf tab bano jab table ka model na ho.
5. dompdf already installed hai (`barryvdh/laravel-dompdf`) — koi naya package install mat karo.
6. Chart.js CDN already load ho raha hai — koi naya library mat add karo.
7. `school_id = 1` hardcoded use karo jahan `auth()->user()->school_id` null aa sakta hai (single school setup).

---

## S-01 — Teacher Portal: Attendance Pattern Summary Card

### Kya banana hai
Teacher dashboard par ek info card: current month mein jinke 3+ absences hain unki count aur list.

### Files touch karne hain
- `app/Http/Controllers/Teacher/DashboardController.php` — existing `index()` mein data add karo
- `resources/views/teacher/dashboard.blade.php` — card insert karo existing grid mein

### Implementation

**Step 1 — DashboardController.php mein `index()` method update karo:**

```php
// Existing $teacher fetch ke baad ye add karo:
$teacherClassIds = \App\Models\TeacherAssignment::where('teacher_id', $teacher->id)
    ->pluck('class_id');

$currentMonthAbsentees = \App\Models\StudentAttendance::query()
    ->whereIn('student_id', function($q) use ($teacherClassIds) {
        $q->select('id')->from('students')
          ->whereIn('current_class_id', $teacherClassIds)
          ->whereNull('deleted_at');
    })
    ->whereMonth('date', now()->month)
    ->whereYear('date', now()->year)
    ->where('status', 'A')
    ->select('student_id', \DB::raw('COUNT(*) as absent_count'))
    ->groupBy('student_id')
    ->having('absent_count', '>=', 3)
    ->with('student:id,first_name,last_name,current_class_id')
    ->get();

// View mein pass karo:
return view('teacher.dashboard', compact(
    // ...existing variables...,
    'currentMonthAbsentees'
));
```

**Step 2 — `resources/views/teacher/dashboard.blade.php` mein existing cards ke baad add karo:**

```blade
{{-- S-01: Attendance Pattern Card --}}
@if($currentMonthAbsentees->count() > 0)
<div class="col-md-12 mb-4">
    <div class="card border-warning">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <span><i class="fas fa-exclamation-triangle"></i> Attendance Alert — {{ now()->format('F Y') }}</span>
            <span class="badge bg-dark">{{ $currentMonthAbsentees->count() }} Students</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Absences This Month</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currentMonthAbsentees as $row)
                    <tr>
                        <td>{{ $row->student->first_name ?? '—' }} {{ $row->student->last_name ?? '' }}</td>
                        <td>{{ $row->student->currentClass->name ?? '—' }}</td>
                        <td><span class="badge bg-danger">{{ $row->absent_count }} days</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
```

### Model check
`StudentAttendance` model mein ye relation add karo agar nahi hai:
```php
public function student() {
    return $this->belongsTo(\App\Models\Student::class);
}
```

---

## S-02 — Student Portal: My Progress Timeline

### Kya banana hai
Student apna marks history ek Chart.js line chart mein dekhe — per subject, sab exams across. Filter by subject.

### Files touch karne hain
- `app/Http/Controllers/Student/PortalController.php` (ya jo student portal controller ho) — naya `progress()` method
- `routes/web.php` — 1 route
- `resources/views/student/progress.blade.php` — naya view (layout extend karo existing student layout se)

### Implementation

**Step 1 — Route add karo `routes/web.php` mein student group mein:**
```php
Route::get('/progress', [\App\Http\Controllers\Student\PortalController::class, 'progress'])->name('student.progress');
```

**Step 2 — Controller method:**
```php
public function progress()
{
    $student = auth()->user()->student; // assumes Student hasOne/belongsTo User

    // Sab marks fetch karo with subject + exam schedule date
    $marks = \App\Models\Mark::where('student_id', $student->id)
        ->with(['subject:id,name', 'examSchedule:id,exam_date,exam_type'])
        ->orderBy('created_at')
        ->get();

    // Per-subject group karo chart ke liye
    $chartData = [];
    foreach ($marks->groupBy('subject_id') as $subjectId => $subjectMarks) {
        $subjectName = $subjectMarks->first()->subject->name ?? 'Subject';
        $chartData[] = [
            'label'  => $subjectName,
            'data'   => $subjectMarks->map(fn($m) => [
                'x' => optional($m->examSchedule)->exam_date ?? $m->created_at->format('Y-m-d'),
                'y' => (float)$m->percentage,
            ])->values()->toArray(),
        ];
    }

    $subjects = \App\Models\Subject::whereIn('id', $marks->pluck('subject_id')->unique())->get();

    return view('student.progress', compact('marks', 'chartData', 'subjects'));
}
```

**Step 3 — `resources/views/student/progress.blade.php`:**
```blade
@extends('layouts.student') {{-- existing student layout --}}
@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">My Progress Timeline</h5></div>
    <div class="card-body">
        <canvas id="progressChart" height="100"></canvas>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Marks Detail</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Subject</th><th>Exam Type</th><th>Date</th><th>Marks</th><th>Percentage</th><th>Grade</th></tr>
            </thead>
            <tbody>
                @foreach($marks as $m)
                <tr>
                    <td>{{ $m->subject->name ?? '—' }}</td>
                    <td>{{ $m->examSchedule->exam_type ?? 'General' }}</td>
                    <td>{{ optional($m->examSchedule)->exam_date ?? $m->created_at->format('d M Y') }}</td>
                    <td>{{ $m->marks_obtained }}/{{ $m->total_marks }}</td>
                    <td>{{ $m->percentage }}%</td>
                    <td><span class="badge bg-secondary">{{ $m->grade }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
const datasets = @json($chartData);
const colors = ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#858796'];
new Chart(document.getElementById('progressChart'), {
    type: 'line',
    data: {
        datasets: datasets.map((d, i) => ({
            label: d.label,
            data: d.data,
            borderColor: colors[i % colors.length],
            backgroundColor: 'transparent',
            tension: 0.3,
            pointRadius: 5,
        }))
    },
    options: {
        parsing: false,
        scales: {
            x: { type: 'category', title: { display: true, text: 'Exam Date' } },
            y: { min: 0, max: 100, title: { display: true, text: 'Percentage (%)' } }
        },
        plugins: { legend: { position: 'top' } }
    }
});
</script>
@endpush
@endsection
```

**Step 4 — Student sidebar mein link add karo:**
```blade
<a href="{{ route('student.progress') }}" class="nav-link {{ request()->routeIs('student.progress') ? 'active' : '' }}">
    <i class="fas fa-chart-line"></i> My Progress
</a>
```

### Mark model relation (agar nahi hai):
```php
// app/Models/Mark.php
public function examSchedule() {
    return $this->belongsTo(\App\Models\ExamSchedule::class, 'exam_schedule_id');
}
public function subject() {
    return $this->belongsTo(\App\Models\Subject::class);
}
```

---

## S-03 — Parent Portal: PDF Receipt Download

### Kya banana hai
Parent apni fees ki receipt PDF download kare. `fee_receipts` table mein already data hai.

### Files touch karne hain
- `app/Http/Controllers/Parent/FeeController.php` (ya existing parent fee controller) — `downloadReceipt()` method
- `routes/web.php` — 1 route
- `resources/views/pdf/fee_receipt.blade.php` — dompdf template

### Implementation

**Step 1 — Route:**
```php
// Parent route group mein
Route::get('/fee/receipt/{receiptId}/download', [\App\Http\Controllers\Parent\FeeController::class, 'downloadReceipt'])
    ->name('parent.fee.receipt.download');
```

**Step 2 — Controller method:**
```php
use Barryvdh\DomPDF\Facade\Pdf;

public function downloadReceipt($receiptId)
{
    $parentUserId = auth()->id();

    // Security: sirf apne bachon ki receipts
    $receipt = \App\Models\FeeReceipt::where('id', $receiptId)
        ->whereHas('student', function($q) use ($parentUserId) {
            $q->whereHas('parentStudents', fn($q2) => $q2->where('parent_user_id', $parentUserId));
        })
        ->with([
            'student:id,first_name,last_name,admission_no',
            'fee:id,challan_no,fee_category,amount,due_date',
            'transaction:id,gateway,transaction_ref,paid_at',
        ])
        ->firstOrFail();

    $school = \App\Models\School::find(1);

    $pdf = Pdf::loadView('pdf.fee_receipt', compact('receipt', 'school'))
              ->setPaper('a5', 'portrait');

    return $pdf->download('Receipt-' . $receipt->receipt_no . '.pdf');
}
```

**Step 3 — `resources/views/pdf/fee_receipt.blade.php`:**
```blade
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #1f3d7a; padding-bottom: 10px; margin-bottom: 15px; }
    .header h2 { color: #1f3d7a; margin: 0; font-size: 18px; }
    .header p { margin: 3px 0; font-size: 11px; }
    .title { background: #1f3d7a; color: #fff; text-align: center; padding: 8px; font-size: 14px; font-weight: bold; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 7px 10px; border-bottom: 1px solid #eee; }
    td:first-child { font-weight: bold; color: #555; width: 45%; }
    .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    .amount-box { background: #f0f8e8; border: 1px solid #1cc88a; padding: 12px; text-align: center; margin: 15px 0; border-radius: 4px; }
    .amount-box .amount { font-size: 22px; font-weight: bold; color: #1a7a4a; }
</style>
</head>
<body>
<div class="header">
    <h2>{{ $school->name ?? 'School' }}</h2>
    <p>{{ $school->address ?? '' }}</p>
</div>

<div class="title">FEE PAYMENT RECEIPT</div>

<table>
    <tr><td>Receipt No:</td><td><strong>{{ $receipt->receipt_no }}</strong></td></tr>
    <tr><td>Date:</td><td>{{ \Carbon\Carbon::parse($receipt->generated_at)->format('d M Y, h:i A') }}</td></tr>
    <tr><td>Student Name:</td><td>{{ $receipt->student->first_name }} {{ $receipt->student->last_name }}</td></tr>
    <tr><td>Admission No:</td><td>{{ $receipt->student->admission_no }}</td></tr>
    <tr><td>Challan No:</td><td>{{ $receipt->fee->challan_no ?? '—' }}</td></tr>
    <tr><td>Fee Type:</td><td>{{ $receipt->fee->fee_category ?? '—' }}</td></tr>
    <tr><td>Due Date:</td><td>{{ optional($receipt->fee)->due_date }}</td></tr>
    <tr><td>Payment Method:</td><td>{{ $receipt->transaction->gateway ?? 'Cash' }}</td></tr>
    <tr><td>Transaction Ref:</td><td>{{ $receipt->transaction->transaction_ref ?? '—' }}</td></tr>
</table>

<div class="amount-box">
    <div style="color:#555; margin-bottom:4px;">Amount Paid</div>
    <div class="amount">PKR {{ number_format($receipt->amount, 2) }}</div>
</div>

<div class="footer">
    This is a computer-generated receipt. No signature required.<br>
    {{ $school->name ?? '' }} — {{ now()->format('Y') }}
</div>
</body>
</html>
```

**Step 4 — Parent fee view mein download button add karo:**
```blade
{{-- Existing fee table mein ek column add karo --}}
<td>
    @if($fee->receipts->count())
        <a href="{{ route('parent.fee.receipt.download', $fee->receipts->first()->id) }}" 
           class="btn btn-sm btn-success">
            <i class="fas fa-download"></i> Receipt
        </a>
    @else
        <span class="text-muted">—</span>
    @endif
</td>
```

### FeeReceipt model relations (add karo agar missing):
```php
// app/Models/FeeReceipt.php
public function student() { return $this->belongsTo(Student::class); }
public function fee()     { return $this->belongsTo(Fee::class); }
public function transaction() { return $this->belongsTo(FeePaymentTransaction::class, 'transaction_id'); }
```

---

## S-04 — Admin Portal: Student Promotion Bulk Action

### Kya banana hai
Admin ek click mein sab students ko promote kare — `promotion_rules` se criteria check ho, `student_promotions` mein log ho, `students.current_class_id` update ho.

### Files touch karne hain
- `app/Http/Controllers/Admin/StudentController.php` — 2 methods: `promotionPreview()`, `runPromotion()`
- `routes/web.php` — 2 routes
- `resources/views/admin/students/promotion.blade.php` — naya view

### Implementation

**Step 1 — Routes (admin group mein):**
```php
Route::get('/students/promotion',  [\App\Http\Controllers\Admin\StudentController::class, 'promotionPreview'])->name('admin.students.promotion');
Route::post('/students/promotion', [\App\Http\Controllers\Admin\StudentController::class, 'runPromotion'])->name('admin.students.promotion.run');
```

**Step 2 — Controller methods:**
```php
public function promotionPreview()
{
    $academicYear = \App\Models\AcademicYear::where('is_active', 1)->first();
    $rules        = \App\Models\PromotionRule::with(['fromClass', 'toClass'])->get();
    $preview      = collect();

    foreach ($rules as $rule) {
        $students = \App\Models\Student::where('current_class_id', $rule->from_class_id)
            ->where('status', 'Regular')
            ->whereNull('deleted_at')
            ->get();

        foreach ($students as $student) {
            $avgPercentage  = \App\Models\Mark::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->avg('percentage') ?? 0;

            $totalDays = \App\Models\StudentAttendance::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)->count();
            $presentDays = \App\Models\StudentAttendance::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('status', 'P')->count();
            $attendancePct = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            $canPromote = $avgPercentage >= $rule->min_percentage
                       && $attendancePct   >= $rule->min_attendance_pct;

            $preview->push([
                'student'        => $student,
                'rule'           => $rule,
                'avg_percentage' => round($avgPercentage, 1),
                'attendance_pct' => $attendancePct,
                'can_promote'    => $canPromote,
                'action'         => $canPromote ? 'Promoted' : 'Repeated',
            ]);
        }
    }

    return view('admin.students.promotion', compact('preview', 'academicYear', 'rules'));
}

public function runPromotion(\Illuminate\Http\Request $request)
{
    $academicYear = \App\Models\AcademicYear::where('is_active', 1)->firstOrFail();
    $rules        = \App\Models\PromotionRule::all()->keyBy('from_class_id');
    $promoted = $repeated = 0;

    $students = \App\Models\Student::where('status', 'Regular')->whereNull('deleted_at')->get();

    foreach ($students as $student) {
        $rule = $rules->get($student->current_class_id);
        if (!$rule) continue;

        $avgPercentage = \App\Models\Mark::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)->avg('percentage') ?? 0;

        $totalDays   = \App\Models\StudentAttendance::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)->count();
        $presentDays = \App\Models\StudentAttendance::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)->where('status', 'P')->count();
        $attendancePct = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        $canPromote = $avgPercentage >= $rule->min_percentage
                   && $attendancePct  >= $rule->min_attendance_pct;

        $promotionType = $canPromote ? 'Promoted' : 'Repeated';
        $toClassId     = $canPromote ? $rule->to_class_id : $student->current_class_id;

        // Log in student_promotions
        \App\Models\StudentPromotion::create([
            'student_id'       => $student->id,
            'academic_year_id' => $academicYear->id,
            'from_class_id'    => $student->current_class_id,
            'from_section_id'  => $student->current_section_id,
            'to_class_id'      => $toClassId,
            'to_section_id'    => $student->current_section_id, // same section
            'promotion_type'   => $promotionType,
            'promoted_by'      => auth()->id(),
            'remarks'          => "Avg: {$avgPercentage}%, Attendance: {$attendancePct}%",
        ]);

        // Update student
        $student->update(['current_class_id' => $toClassId]);

        $canPromote ? $promoted++ : $repeated++;
    }

    return redirect()->route('admin.students.promotion')
        ->with('success', "Promotion complete. Promoted: {$promoted}, Repeated: {$repeated}.");
}
```

**Step 3 — `resources/views/admin/students/promotion.blade.php`:**
```blade
@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Student Promotion — {{ $academicYear->year ?? '' }}</h4>
    <form method="POST" action="{{ route('admin.students.promotion.run') }}"
          onsubmit="return confirm('Kya aap sure hain? Ye action sab students ko update karega.')">
        @csrf
        <button class="btn btn-danger"><i class="fas fa-bolt"></i> Run Promotion Now</button>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">Preview — {{ $preview->count() }} Students</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student</th>
                    <th>Current Class</th>
                    <th>To Class</th>
                    <th>Avg %</th>
                    <th>Attendance %</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preview as $row)
                <tr class="{{ $row['can_promote'] ? '' : 'table-warning' }}">
                    <td>{{ $row['student']->first_name }} {{ $row['student']->last_name }}</td>
                    <td>{{ $row['rule']->fromClass->name }}</td>
                    <td>{{ $row['can_promote'] ? $row['rule']->toClass->name : $row['rule']->fromClass->name }}</td>
                    <td>{{ $row['avg_percentage'] }}%</td>
                    <td>{{ $row['attendance_pct'] }}%</td>
                    <td>
                        <span class="badge bg-{{ $row['can_promote'] ? 'success' : 'warning text-dark' }}">
                            {{ $row['action'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
```

### Naye Models (agar missing):
```php
// app/Models/StudentPromotion.php
class StudentPromotion extends Model {
    public $timestamps = false;
    protected $fillable = ['student_id','academic_year_id','from_class_id','from_section_id','to_class_id','to_section_id','promotion_type','promoted_by','remarks'];
}

// app/Models/PromotionRule.php
class PromotionRule extends Model {
    public $timestamps = false;
    protected $fillable = ['from_class_id','to_class_id','min_percentage','min_attendance_pct','academic_year_id'];
    public function fromClass() { return $this->belongsTo(SchoolClass::class, 'from_class_id'); }
    public function toClass()   { return $this->belongsTo(SchoolClass::class, 'to_class_id'); }
}
```

---

## S-05 — Accountant Portal: Auto Ledger Entry on Fee Payment

### Kya banana hai
Jab `fee_payment_transactions.status = 'Success'` ho to automatically `ledger_entries` mein Income entry bane.

### Files touch karne hain
- `app/Models/FeePaymentTransaction.php` — Observer register karo
- `app/Observers/FeePaymentTransactionObserver.php` — naya file
- `app/Providers/AppServiceProvider.php` — observer register

### Implementation

**Step 1 — Observer create karo:**
```php
// app/Observers/FeePaymentTransactionObserver.php
namespace App\Observers;

use App\Models\FeePaymentTransaction;
use App\Models\LedgerEntry;

class FeePaymentTransactionObserver
{
    public function updated(FeePaymentTransaction $transaction): void
    {
        // Sirf tab jab status 'Success' bane
        if ($transaction->isDirty('status') && $transaction->status === 'Success') {
            $this->createLedgerEntry($transaction);
        }
    }

    public function created(FeePaymentTransaction $transaction): void
    {
        if ($transaction->status === 'Success') {
            $this->createLedgerEntry($transaction);
        }
    }

    private function createLedgerEntry(FeePaymentTransaction $transaction): void
    {
        // Duplicate check
        $exists = LedgerEntry::where('reference_type', 'fee_payment_transactions')
            ->where('reference_id', $transaction->id)
            ->exists();

        if ($exists) return;

        $student = $transaction->student; // relation assume
        $name = $student ? "{$student->first_name} {$student->last_name}" : "Student #{$transaction->student_id}";

        LedgerEntry::create([
            'school_id'      => auth()->user()?->school_id ?? 1,
            'date'           => now()->toDateString(),
            'description'    => "Fee received from {$name} via {$transaction->gateway}",
            'type'           => 'Income',
            'amount'         => $transaction->amount,
            'reference_type' => 'fee_payment_transactions',
            'reference_id'   => $transaction->id,
            'bank_account_id'=> null,
        ]);
    }
}
```

**Step 2 — AppServiceProvider mein register karo:**
```php
// app/Providers/AppServiceProvider.php → boot() mein add karo:
\App\Models\FeePaymentTransaction::observe(\App\Observers\FeePaymentTransactionObserver::class);
```

**Step 3 — LedgerEntry model (agar missing):**
```php
// app/Models/LedgerEntry.php
class LedgerEntry extends Model {
    protected $fillable = ['school_id','date','description','type','amount','reference_type','reference_id','bank_account_id'];
}
```

**Step 4 — Accountant ledger view check karo** — existing ledger list page mein ab auto entries aayengi. Koi extra UI nahi chahiye.

---

## S-06 — Admin Portal: Section-wise Report Card Generation

### Kya banana hai
Admin class + exam type select kare → "Generate All Report Cards" → sab students ke liye `report_cards` rows create hon → dompdf bulk PDF.

### Files touch karne hain
- `app/Http/Controllers/Admin/ReportCardController.php` — 3 methods
- `routes/web.php` — 3 routes
- `resources/views/admin/report_cards/generate.blade.php` — naya view
- `resources/views/pdf/report_card.blade.php` — dompdf template

### Implementation

**Step 1 — Routes:**
```php
Route::get('/report-cards',          [\App\Http\Controllers\Admin\ReportCardController::class, 'index'])->name('admin.reportcards.index');
Route::post('/report-cards/generate',[\App\Http\Controllers\Admin\ReportCardController::class, 'generate'])->name('admin.reportcards.generate');
Route::get('/report-cards/{id}/pdf', [\App\Http\Controllers\Admin\ReportCardController::class, 'downloadPdf'])->name('admin.reportcards.pdf');
```

**Step 2 — Controller:**
```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ReportCard, Mark, Student, SchoolClass, ExamType, AcademicYear, Subject};
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends Controller
{
    public function index()
    {
        $classes      = SchoolClass::where('school_id', 1)->get();
        $examTypes    = ExamType::all();
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $reportCards  = ReportCard::with('student')->latest()->paginate(20);

        return view('admin.report_cards.generate', compact('classes','examTypes','academicYear','reportCards'));
    }

    public function generate(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'class_id'      => 'required|exists:classes,id',
            'exam_type_id'  => 'required|exists:exam_types,id',
        ]);

        $academicYear = AcademicYear::where('is_active', 1)->firstOrFail();
        $students     = Student::where('current_class_id', $request->class_id)
                               ->where('status', 'Regular')
                               ->whereNull('deleted_at')->get();
        $subjects     = Subject::where('class_id', $request->class_id)->get();
        $generated    = 0;

        foreach ($students as $student) {
            $marks = Mark::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->whereIn('subject_id', $subjects->pluck('id'))
                ->get();

            if ($marks->isEmpty()) continue;

            $totalObtained = $marks->sum('marks_obtained');
            $totalMax      = $marks->sum('total_marks');
            $percentage    = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
            $grade         = $this->calculateGrade($percentage);

            ReportCard::updateOrCreate(
                [
                    'student_id'      => $student->id,
                    'academic_year_id'=> $academicYear->id,
                    'exam_type_id'    => $request->exam_type_id,
                ],
                [
                    'total_obtained' => $totalObtained,
                    'total_max'      => $totalMax,
                    'percentage'     => $percentage,
                    'grade'          => $grade,
                    'remarks'        => $percentage >= 50 ? 'Pass' : 'Fail',
                ]
            );
            $generated++;
        }

        // Calculate ranks
        $this->calculateRanks($request->class_id, $academicYear->id, $request->exam_type_id);

        return redirect()->route('admin.reportcards.index')
            ->with('success', "Report cards generated for {$generated} students.");
    }

    public function downloadPdf($id)
    {
        $reportCard   = ReportCard::with(['student.currentClass', 'examType'])->findOrFail($id);
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $subjects     = Subject::where('class_id', $reportCard->student->current_class_id)->get();
        $marks        = Mark::where('student_id', $reportCard->student_id)
                           ->where('academic_year_id', $academicYear->id)
                           ->with('subject')->get()->keyBy('subject_id');
        $school       = \App\Models\School::find(1);

        $pdf = Pdf::loadView('pdf.report_card', compact('reportCard','marks','subjects','school','academicYear'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("ReportCard-{$reportCard->student->admission_no}.pdf");
    }

    private function calculateGrade(float $pct): string
    {
        return match(true) {
            $pct >= 90 => 'A+', $pct >= 80 => 'A', $pct >= 70 => 'B',
            $pct >= 60 => 'C', $pct >= 50 => 'D', default => 'F',
        };
    }

    private function calculateRanks(int $classId, int $yearId, int $examTypeId): void
    {
        $cards = ReportCard::whereHas('student', fn($q) => $q->where('current_class_id', $classId))
            ->where('academic_year_id', $yearId)
            ->where('exam_type_id', $examTypeId)
            ->orderByDesc('percentage')->get();

        foreach ($cards as $i => $card) {
            $card->update(['rank' => $i + 1]);
        }
    }
}
```

**Step 3 — PDF Template `resources/views/pdf/report_card.blade.php`:**
```blade
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .header { text-align: center; border-bottom: 3px solid #1f3d7a; padding-bottom: 12px; margin-bottom: 15px; }
    .header h2 { color: #1f3d7a; margin: 0; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    th { background: #1f3d7a; color: #fff; padding: 8px; }
    td { padding: 7px 8px; border: 1px solid #ddd; }
    .info-table td { border: none; border-bottom: 1px dotted #ccc; }
    .summary { background: #f0f8e8; border: 1px solid #1cc88a; padding: 12px; text-align: center; }
    .grade { font-size: 28px; font-weight: bold; color: #1f3d7a; }
    .footer-sigs { margin-top: 40px; }
    .sig-line { border-top: 1px solid #333; width: 150px; display: inline-block; text-align: center; margin: 0 20px; }
</style>
</head>
<body>
<div class="header">
    <h2>{{ $school->name ?? 'School' }}</h2>
    <p>{{ $school->address ?? '' }}</p>
    <strong>ACADEMIC PROGRESS REPORT — {{ $academicYear->year }}</strong>
</div>

<table class="info-table">
    <tr>
        <td><b>Student:</b> {{ $reportCard->student->first_name }} {{ $reportCard->student->last_name }}</td>
        <td><b>Adm. No:</b> {{ $reportCard->student->admission_no }}</td>
    </tr>
    <tr>
        <td><b>Class:</b> {{ $reportCard->student->currentClass->name ?? '—' }}</td>
        <td><b>Exam:</b> {{ $reportCard->examType->name ?? '—' }}</td>
    </tr>
    <tr>
        <td><b>Rank in Class:</b> {{ $reportCard->rank ?? '—' }}</td>
        <td><b>Date:</b> {{ now()->format('d M Y') }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr><th>Subject</th><th>Max Marks</th><th>Marks Obtained</th><th>Percentage</th><th>Grade</th></tr>
    </thead>
    <tbody>
        @foreach($subjects as $subject)
        @php $m = $marks->get($subject->id); @endphp
        <tr>
            <td>{{ $subject->name }}</td>
            <td>{{ $m ? $m->total_marks : '—' }}</td>
            <td>{{ $m ? $m->marks_obtained : '—' }}</td>
            <td>{{ $m ? $m->percentage . '%' : '—' }}</td>
            <td>{{ $m ? $m->grade : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight:bold; background:#f5f5f5;">
            <td>TOTAL</td>
            <td>{{ $reportCard->total_max }}</td>
            <td>{{ $reportCard->total_obtained }}</td>
            <td>{{ $reportCard->percentage }}%</td>
            <td>{{ $reportCard->grade }}</td>
        </tr>
    </tfoot>
</table>

<div class="summary">
    <div>Overall Result</div>
    <div class="grade">{{ $reportCard->grade }}</div>
    <div>{{ $reportCard->percentage }}% — {{ $reportCard->remarks }}</div>
</div>

<div class="footer-sigs" style="margin-top:50px;">
    <span class="sig-line">Class Teacher</span>
    <span class="sig-line">Principal</span>
    <span class="sig-line">Parent Signature</span>
</div>
</body>
</html>
```

**Step 4 — ReportCard model relations:**
```php
// app/Models/ReportCard.php
public function student()     { return $this->belongsTo(Student::class); }
public function examType()    { return $this->belongsTo(ExamType::class); }
public function academicYear(){ return $this->belongsTo(AcademicYear::class); }
```

---

## S-07 — Teacher Portal: Leave System Unification

### Kya karna hai
`teacher_leaves` (old table, has data) ko `teacher_leave_requests` mein migrate karo. Phir portal sirf `teacher_leave_requests` use kare. Old table drop karo.

### Implementation

**Step 1 — Data migration (ek baar run karo):**
```php
// php artisan tinker mein ya ek-time migration ke through:
$old = DB::table('teacher_leaves')->get();
foreach ($old as $row) {
    DB::table('teacher_leave_requests')->insertOrIgnore([
        'teacher_id'  => $row->teacher_id,
        'leave_type'  => $row->leave_type ?? 'Casual',
        'start_date'  => $row->start_date,
        'end_date'    => $row->end_date,
        'total_days'  => \Carbon\Carbon::parse($row->start_date)->diffInDays($row->end_date) + 1,
        'status'      => $row->status ?? 'Pending',
        'created_at'  => $row->created_at,
        'updated_at'  => $row->created_at,
    ]);
}
```

**Step 2 — TeacherLeaveRequest model:**
```php
// app/Models/TeacherLeaveRequest.php
class TeacherLeaveRequest extends Model {
    protected $table = 'teacher_leave_requests';
    protected $fillable = ['teacher_id','leave_type','start_date','end_date','total_days','reason','status','approved_by','rejection_reason','substitute_assigned'];

    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
}
```

**Step 3 — Controller update:**
Agar teacher portal `TeacherLeave` model use karta tha, replace karo `TeacherLeaveRequest` se. Search & replace across controllers:
- `TeacherLeave::` → `TeacherLeaveRequest::`
- `teacher_leaves` table name references → `teacher_leave_requests`

**Step 4 — Drop old table (sirf data migrate hone ke baad):**
```sql
DROP TABLE IF EXISTS `teacher_leaves`;
```

---

## S-08 — Student Portal: Quiz Results Tab

### Kya banana hai
Student portal mein "My Quiz Results" page — simple table with quiz name, subject, date, score.

### Files touch karne hain
- Existing student portal controller — `quizResults()` method
- `routes/web.php` — 1 route
- `resources/views/student/quiz_results.blade.php` — naya view

### Implementation

**Step 1 — Route:**
```php
Route::get('/quiz-results', [\App\Http\Controllers\Student\PortalController::class, 'quizResults'])->name('student.quiz.results');
```

**Step 2 — Controller method:**
```php
public function quizResults()
{
    $student = auth()->user()->student;

    $attempts = \App\Models\QuizAttempt::where('student_id', $student->id)
        ->with(['quiz' => fn($q) => $q->with('subject:id,name')])
        ->orderByDesc('submitted_at')
        ->get();

    return view('student.quiz_results', compact('attempts'));
}
```

**Step 3 — View:**
```blade
@extends('layouts.student')
@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">My Quiz Results</h5></div>
    <div class="card-body p-0">
        @if($attempts->isEmpty())
            <p class="text-muted text-center p-4">Koi quiz attempt nahi mili abhi tak.</p>
        @else
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Quiz Title</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Score</th>
                    <th>Percentage</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempts as $i => $attempt)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $attempt->quiz->title ?? '—' }}</td>
                    <td>{{ $attempt->quiz->subject->name ?? '—' }}</td>
                    <td>{{ $attempt->submitted_at ? \Carbon\Carbon::parse($attempt->submitted_at)->format('d M Y') : '—' }}</td>
                    <td>{{ $attempt->score }}/{{ $attempt->total_marks }}</td>
                    <td>
                        <div class="progress" style="height:18px; min-width:80px;">
                            <div class="progress-bar bg-{{ $attempt->percentage >= 50 ? 'success' : 'danger' }}"
                                 style="width:{{ $attempt->percentage }}%">
                                {{ $attempt->percentage }}%
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ $attempt->status === 'submitted' ? 'success' : 'secondary' }}">
                            {{ ucfirst($attempt->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
```

**Step 4 — Sidebar link:**
```blade
<a href="{{ route('student.quiz.results') }}" class="nav-link">
    <i class="fas fa-poll"></i> Quiz Results
</a>
```

### QuizAttempt model relation:
```php
public function quiz() { return $this->belongsTo(Quiz::class); }
```

---

## S-09 — Admin Portal: Timetable Column Cleanup

### Kya karna hai
`timetables` table se duplicate columns remove karo: `section_id` (no FK) aur `subject` (VARCHAR text). Sirf `section_id_ref` aur `subject_id_ref` rakho.

### Implementation

**Step 1 — Migration create karo:**
```bash
php artisan make:migration cleanup_timetables_duplicate_columns --table=timetables
```

```php
// Migration up():
public function up(): void
{
    // Pehle koi views etc. check karo
    Schema::table('timetables', function (Blueprint $table) {
        $table->dropColumn(['section_id', 'subject']);
    });
}

public function down(): void
{
    Schema::table('timetables', function (Blueprint $table) {
        $table->integer('section_id')->nullable()->after('class_id');
        $table->string('subject', 100)->nullable()->after('teacher');
    });
}
```

**Step 2 — Codebase search & replace:**

In `app/` folder aur `resources/views/` folder mein globally search karo:
- `$slot->section_id` ya `timetable->section_id` → replace with `->section_id_ref`
- `$slot->subject` ya `timetable->subject` → replace with `->subjectModel->name` (relation se)
- `'section_id'` in timetable queries → `'section_id_ref'`
- `'subject'` in timetable select → `'subject_id_ref'`

**Step 3 — Timetable model relation add karo:**
```php
// app/Models/Timetable.php
public function subjectModel() {
    return $this->belongsTo(\App\Models\Subject::class, 'subject_id_ref');
}
public function sectionModel() {
    return $this->belongsTo(\App\Models\Section::class, 'section_id_ref');
}
```

**Step 4 — Timetable view Blade files mein update:**
```blade
{{-- OLD --}}
{{ $slot->subject }}
{{-- NEW --}}
{{ $slot->subjectModel->name ?? $slot->subject_id_ref }}
```

---

## S-10 — Parent Portal: Notification Bell

### Kya banana hai
Parent portal sidebar mein bell icon with unread count badge. Click pe notification list. Mark-as-read functionality.

### Files touch karne hain
- `app/Http/Controllers/Parent/NotificationController.php` — 2 methods
- `routes/web.php` — 2 routes
- `resources/views/layouts/parent.blade.php` (sidebar/header) — bell icon add
- `resources/views/parent/notifications.blade.php` — naya view

### Implementation

**Step 1 — Routes:**
```php
Route::get('/notifications',        [\App\Http\Controllers\Parent\NotificationController::class, 'index'])->name('parent.notifications');
Route::post('/notifications/{id}/read', [\App\Http\Controllers\Parent\NotificationController::class, 'markRead'])->name('parent.notifications.read');
Route::post('/notifications/read-all', [\App\Http\Controllers\Parent\NotificationController::class, 'markAllRead'])->name('parent.notifications.readall');
```

**Step 2 — Controller:**
```php
namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        // All mark read on open
        Notification::where('user_id', auth()->id())->where('is_read', 0)->update(['is_read' => 1]);

        return view('parent.notifications', compact('notifications'));
    }

    public function markRead($id)
    {
        Notification::where('id', $id)->where('user_id', auth()->id())->update(['is_read' => 1]);
        return back();
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())->update(['is_read' => 1]);
        return back()->with('success', 'Sab notifications read mark ho gayi.');
    }
}
```

**Step 3 — Parent layout header mein bell icon add karo:**

`resources/views/layouts/parent.blade.php` mein header/topbar section dhundo aur ye add karo:
```blade
{{-- Notification Bell --}}
@php
    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', 0)->count();
@endphp
<a href="{{ route('parent.notifications') }}" class="btn btn-sm btn-light position-relative me-2" title="Notifications">
    <i class="fas fa-bell"></i>
    @if($unreadCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              style="font-size:10px;">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
    @endif
</a>
```

**Step 4 — `resources/views/parent/notifications.blade.php`:**
```blade
@extends('layouts.parent')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Notifications</h5>
    <form method="POST" action="{{ route('parent.notifications.readall') }}">
        @csrf
        <button class="btn btn-sm btn-outline-secondary">Mark All Read</button>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($notifications as $notif)
        <div class="d-flex align-items-start p-3 border-bottom {{ $notif->is_read ? '' : 'bg-light' }}">
            <div class="me-3 pt-1">
                <i class="fas fa-bell text-{{ $notif->is_read ? 'secondary' : 'primary' }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold">{{ $notif->title }}</div>
                <div class="text-muted small">{{ $notif->body }}</div>
                <div class="text-muted" style="font-size:11px;">
                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                </div>
            </div>
            @if(!$notif->is_read)
            <form method="POST" action="{{ route('parent.notifications.read', $notif->id) }}">
                @csrf
                <button class="btn btn-sm btn-link text-primary p-0">Mark Read</button>
            </form>
            @endif
        </div>
        @empty
        <p class="text-muted text-center p-4">Koi notification nahi hai abhi.</p>
        @endforelse
    </div>
</div>
{{ $notifications->links() }}
@endsection
```

**Step 5 — Notification model (agar missing):**
```php
// app/Models/Notification.php
class Notification extends Model {
    public $timestamps = false;
    const CREATED_AT = 'created_at';
    protected $fillable = ['user_id','type','title','body','is_read','action_url'];

    // Auto-notify parents jab koi event/announcement aaye:
    // (Optional) AppServiceProvider mein Announcement Observer add karo
}
```

---

## RUN ORDER (Sab ek saath run karna ho to ye sequence follow karo)

```
Step 1 — S-07 pehle (leave system cleanup)
  → Data migrate karo teacher_leaves → teacher_leave_requests
  → Controllers update karo
  → teacher_leaves drop karo

Step 2 — S-09 (timetable column cleanup migration)
  → php artisan migrate
  → Blade files update

Step 3 — S-05 (Observer register)
  → Observer file create
  → AppServiceProvider update

Step 4 — S-04 (Promotion — model + controller + view)
Step 5 — S-06 (Report Card — controller + pdf template)
Step 6 — S-03 (Receipt PDF — simple, already table mein data)
Step 7 — S-01 (Teacher dashboard card)
Step 8 — S-02 (Student progress chart)
Step 9 — S-08 (Quiz results tab)
Step 10 — S-10 (Parent notification bell)
```

---

## FINAL CHECKLIST

| # | Feature | Portal | New Files | DB Changes |
|---|---------|--------|-----------|------------|
| S-01 | Attendance Alert Card | Teacher | 0 | None |
| S-02 | Progress Timeline Chart | Student | 1 view | None |
| S-03 | PDF Receipt Download | Parent | 1 controller method + 1 pdf view | None |
| S-04 | Bulk Student Promotion | Admin | 1 controller + 1 view | student_promotions INSERT |
| S-05 | Auto Ledger Entry | Accountant | 1 Observer | ledger_entries INSERT |
| S-06 | Report Card Generation | Admin | 1 controller + 2 views | report_cards INSERT |
| S-07 | Leave System Cleanup | Teacher | 1 Model | teacher_leaves DROP |
| S-08 | Quiz Results Tab | Student | 1 view | None |
| S-09 | Timetable Cleanup | Admin | 1 migration | timetables 2 columns DROP |
| S-10 | Notification Bell | Parent | 1 controller + 1 view | notifications READ |

**Total: ~10 controller methods · ~8 new views · 1 migration · 1 observer · 0 new packages**
