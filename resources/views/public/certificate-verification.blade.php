<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification</title>
    <!-- Use Bootstrap to match theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .verification-card { max-width: 600px; margin: 50px auto; border-radius: 15px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card verification-card">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="School Logo" height="80" onerror="this.src='https://via.placeholder.com/80?text=Logo'">
                </div>
                
                @if($verified)
                    <div class="text-success mb-3">
                        <i class="fas fa-check-circle fa-4x"></i>
                    </div>
                    <h2 class="text-success mb-3">Verified Successfully</h2>
                    <p class="text-muted mb-4">{{ $message }}</p>
                    
                    <div class="text-start bg-light p-4 rounded text-dark">
                        <p><strong>Certificate Title:</strong> {{ $document->documentTemplate->name ?? 'Certificate' }}</p>
                        <p><strong>Issued To:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
                        <p><strong>Admission No:</strong> {{ $student->admission_no }}</p>
                        <p><strong>Issue Date:</strong> {{ $document->issue_date ? $document->issue_date->format('F d, Y') : 'N/A' }}</p>
                        <p><strong>Certificate No:</strong> {{ $document->document_number }}</p>
                    </div>
                @else
                    <div class="text-danger mb-3">
                        <i class="fas fa-times-circle fa-4x"></i>
                    </div>
                    <h2 class="text-danger mb-3">Verification Failed</h2>
                    <p class="text-muted">{{ $message }}</p>
                @endif
                
                <div class="mt-5 text-muted small">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'NewMkhanSchool') }}. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
