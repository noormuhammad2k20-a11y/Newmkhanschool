<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\School;

class IDCardController extends Controller
{
    public function download()
    {
        $student = auth()->user()->student;
        $school = School::find(auth()->user()->school_id);
        
        $data = [
            'student' => $student,
            'school' => $school
        ];

        // We will define a simple view for the ID card pdf
        $pdf = Pdf::loadView('student.id-card-pdf', $data);
        
        return $pdf->download('id_card_' . $student->admission_no . '.pdf');
    }
}
