<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fee;
use Barryvdh\DomPDF\Facade\Pdf;

class FeeReceiptController extends Controller
{
    public function download($id)
    {
        $fee = Fee::with('student')->findOrFail($id);

        // Security check
        if (auth()->user()->role === 'student' && $fee->student_id !== auth()->user()->student_id) {
            abort(403, 'Unauthorized access to this receipt.');
        }

        $pdf = Pdf::loadView('fees.receipt_pdf', compact('fee'));
        
        return $pdf->download('Receipt_'.$fee->challan_no.'.pdf');
    }
}
