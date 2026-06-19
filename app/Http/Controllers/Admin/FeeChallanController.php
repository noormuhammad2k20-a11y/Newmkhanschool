<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use App\Models\FeeStructure;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class FeeChallanController extends Controller
{
    // Generate challan for a single student
    public function generate($studentId)
    {
        $student      = Student::with(['currentClass'])->findOrFail($studentId);
        $academicYear = AcademicYear::where('is_active', 1)->first();

        $pendingFees = Fee::where('student_id', $student->id)
            ->whereIn('status', ['Pending','Overdue'])
            ->get();

        if ($pendingFees->isEmpty()) {
            return back()->with('info', 'No pending fees for this student.');
        }

        $challanData = [
            'student'      => $student,
            'fees'         => $pendingFees,
            'total_amount' => $pendingFees->sum('amount'),
            'challan_no'   => 'CHN-' . now()->format('Ymd') . '-' . str_pad($student->id, 5,'0',STR_PAD_LEFT),
            'due_date'     => now()->addDays(15)->format('d M Y'),
            'issued_date'  => now()->format('d M Y'),
            'school'       => $this->getSchoolSettings($student->school_id ?? 1),
            'qr_data'      => "STUDENT:{$student->admission_no}|AMOUNT:{$pendingFees->sum('amount')}|DATE:".now()->format('Ymd'),
            'academic_year'=> $academicYear,
        ];

        $pdf = Pdf::loadView('admin.fees.challan-pdf', $challanData)
            ->setPaper('a4', 'portrait');

        return $pdf->download("challan_{$student->admission_no}_{$challanData['challan_no']}.pdf");
    }

    // Bulk generate challans for an entire class
    public function bulkGenerate(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:classes,id']);

        $students = Student::where('current_class_id', $request->class_id)
            ->where('status', 'Active')->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No active students in this class.');
        }

        // For bulk, generate a ZIP file of PDFs or a single merged PDF
        // Here we do a summary table PDF
        $academicYear = AcademicYear::where('is_active', 1)->first();

        $studentFees = $students->map(function ($student) use ($academicYear) {
            $pending = Fee::where('student_id', $student->id)
                ->whereIn('status', ['Pending','Overdue'])->sum('amount');
            return ['student' => $student, 'pending' => $pending];
        })->filter(fn($s) => $s['pending'] > 0);

        $pdf = Pdf::loadView('admin.fees.bulk-challan-pdf', [
            'studentFees'  => $studentFees,
            'academicYear' => $academicYear,
            'class'        => \App\Models\SchoolClass::find($request->class_id),
            'generated_at' => now(),
        ])->setPaper('a4','portrait');

        return $pdf->download("bulk_challans_class_{$request->class_id}.pdf");
    }

    private function getSchoolSettings($schoolId): array
    {
        // Pull school info from centralized settings
        return [
            'name'    => setting('school.name', config('app.school_name', 'MKhan School')),
            'address' => setting('general.address', config('app.school_address', 'School Address')),
            'phone'   => setting('general.phone', config('app.school_phone', '')),
            'logo'    => setting('general.system_logo', ''),
        ];
    }
}
