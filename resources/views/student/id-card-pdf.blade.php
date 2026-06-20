<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student ID Card</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; }
        .id-card {
            width: 3.375in;
            height: 2.125in;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            box-sizing: border-box;
            background: #fff;
            margin: 0 auto;
        }
        .header { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 5px; color: #333; }
        .school-name { font-size: 12px; color: #555; text-align: center; margin-bottom: 10px; }
        .photo { width: 60px; height: 60px; background: #eee; float: left; margin-right: 10px; border: 1px solid #ddd; }
        .details { float: left; width: calc(100% - 75px); font-size: 11px; line-height: 1.4; }
        .label { font-weight: bold; color: #555; }
        .footer { clear: both; text-align: center; margin-top: 10px; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="header">STUDENT ID CARD</div>
        <div class="school-name">{{ $school->name ?? setting('general.organization_name', 'Galaxy Academy') }}</div>
        
        <div class="photo">
            @if($student->photo)
                <img src="{{ public_path('storage/' . $student->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
            @endif
        </div>
        
        <div class="details">
            <div><span class="label">Name:</span> {{ $student->first_name }} {{ $student->last_name }}</div>
            <div><span class="label">Admission No:</span> {{ $student->admission_no }}</div>
            <div><span class="label">Class:</span> {{ $student->currentClass->name ?? 'N/A' }}</div>
            <div><span class="label">Section:</span> {{ $student->currentSection->name ?? 'N/A' }}</div>
            <div><span class="label">DOB:</span> {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-M-Y') : 'N/A' }}</div>
        </div>
        
        <div class="footer">
            This card is the property of the school.
        </div>
    </div>
</body>
</html>
