<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IssuedDocument;

class VerificationController extends Controller
{
    public function verifyQR($uuid)
    {
        $document = IssuedDocument::with(['student', 'template'])->where('uuid', $uuid)->first();

        if (!$document) {
            return view('verify.document', [
                'status' => 'invalid',
                'message' => 'The scanned QR code does not match any authentic document in our system.'
            ]);
        }

        return view('verify.document', [
            'status' => 'valid',
            'document' => $document
        ]);
    }
}
