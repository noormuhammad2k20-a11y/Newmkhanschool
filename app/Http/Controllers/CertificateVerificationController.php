<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function verify($uuid)
    {
        // Certificates are usually stored in IssuedDocument model
        $document = \App\Models\IssuedDocument::where('document_number', $uuid)->first();

        if (!$document) {
            return view('public.certificate-verification', [
                'verified' => false,
                'message' => 'Certificate not found or invalid UUID.'
            ]);
        }

        return view('public.certificate-verification', [
            'verified' => true,
            'document' => $document,
            'student' => $document->student,
            'message' => 'This certificate is valid and issued by our institution.'
        ]);
    }
}
