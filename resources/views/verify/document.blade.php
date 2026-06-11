<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        @if($status === 'valid')
            <div class="bg-green-600 text-white p-6 text-center">
                <span class="material-symbols-outlined text-[64px] mb-2">verified</span>
                <h1 class="text-2xl font-bold">Document Verified</h1>
                <p class="text-green-100">This is an authentic document.</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Document Type</p>
                        <p class="text-lg text-gray-900 font-semibold">{{ $document->template->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Issued To</p>
                        <p class="text-lg text-gray-900 font-semibold">{{ $document->student->first_name }} {{ $document->student->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Admission Number</p>
                        <p class="text-gray-900">{{ $document->student->admission_no }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Document Number</p>
                        <p class="text-gray-900">{{ $document->document_no }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Date of Issue</p>
                        <p class="text-gray-900">{{ $document->issued_at->format('d M, Y') }}</p>
                    </div>
                </div>
                
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500">Verified by School Management System</p>
                </div>
            </div>
        @else
            <div class="bg-red-600 text-white p-6 text-center">
                <span class="material-symbols-outlined text-[64px] mb-2">error</span>
                <h1 class="text-2xl font-bold">Invalid Document</h1>
                <p class="text-red-100">Verification Failed</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-gray-700">{{ $message }}</p>
                <div class="mt-8">
                    <p class="text-xs text-gray-500">School Management System</p>
                </div>
            </div>
        @endif
    </div>

</body>
</html>
