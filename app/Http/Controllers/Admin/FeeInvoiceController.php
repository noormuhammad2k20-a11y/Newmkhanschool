<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\AjaxResponseTrait;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\FeeStructure;
use App\Models\Student;

class FeeInvoiceController extends Controller
{
    use AjaxResponseTrait;
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'due_date' => 'required|date|after_or_equal:today'
        ]);

        if (auth()->user()->hasRole('Super Admin')) {
            $structure = FeeStructure::with('category')
                            ->where('class_id', $request->class_id)
                            ->where('fee_category_id', $request->fee_category_id)
                            ->first();
            $studentsQuery = Student::where('current_class_id', $request->class_id);
        } else {
            $schoolId = auth()->user()->school_id;
            $structure = FeeStructure::with('category')
                            ->where('school_id', $schoolId)
                            ->where('class_id', $request->class_id)
                            ->where('fee_category_id', $request->fee_category_id)
                            ->first();
            $studentsQuery = Student::where('school_id', $schoolId)->where('current_class_id', $request->class_id);
        }

        if (!$structure) {
            return $this->ajaxError($request, 'No fee structure found for this class and category.');
        }

        if (stripos($structure->category->name, 'tuition') !== false) {
            $studentsQuery->where('is_tuition', true);
        }

        $students = $studentsQuery->get();

        if ($students->isEmpty()) {
            return $this->ajaxError($request, 'No students found in this class matching the criteria.');
        }

        $existingStudentIds = Fee::whereIn('student_id', $students->pluck('id'))
                            ->where('fee_category_id', $request->fee_category_id)
                            ->whereMonth('due_date', date('m', strtotime($request->due_date)))
                            ->whereYear('due_date', date('Y', strtotime($request->due_date)))
                            ->pluck('student_id')
                            ->toArray();

        $count = 0;
        $skipped = 0;
        foreach ($students as $student) {
            if (in_array($student->id, $existingStudentIds)) {
                $skipped++;
                continue;
            }

            $challanNo = strtoupper(uniqid('CH-'));
            Fee::create([
                'student_id' => $student->id,
                'fee_category_id' => $structure->category->id,
                'fee_category' => $structure->category->name,
                'amount' => $structure->amount,
                'discount' => 0,
                'fine' => 0,
                'paid_amount' => 0,
                'due_date' => $request->due_date,
                'status' => 'Pending',
                'challan_no' => $challanNo
            ]);
            $count++;
        }

        if ($count === 0 && $skipped > 0) {
            return $this->ajaxError($request, "No new invoices generated. All students in this class already have this fee for the selected month.");
        }

        return $this->ajaxSuccess($request, "Generated $count invoices successfully. " . ($skipped > 0 ? "Skipped $skipped duplicates." : ""));
    }
}
