<!DOCTYPE html>
<html>
<head>
    <title>Report Card - {{ $student->first_name }} {{ $student->last_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .info { margin-bottom: 20px; }
        .info p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 50px; display: table; width: 100%; }
        .footer-cell { display: table-cell; text-align: center; width: 50%; }
        .signature-line { border-top: 1px solid #000; width: 200px; margin: 0 auto; margin-top: 50px; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Official Report Card</h2>
        <p>Academic Year: {{ $academicYear->name ?? 'N/A' }}</p>
    </div>
    
    <div class="info">
        <p><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
        <p><strong>Class:</strong> {{ $student->currentClass->name ?? '' }} {{ $student->currentSection->name ?? '' }}</p>
        <p><strong>Admission No:</strong> {{ $student->admission_no ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Marks Obtained</th>
                <th>Max Marks</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($student->marks as $mark)
            <tr>
                <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                <td>{{ $mark->marks_obtained }}</td>
                <td>{{ $mark->max_marks ?? 100 }}</td>
                <td>{{ $mark->grade ?? 'N/A' }}</td>
                <td>{{ $mark->remarks ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No marks recorded.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-cell">
            <div class="signature-line">Class Teacher Signature</div>
        </div>
        <div class="footer-cell">
            <div class="signature-line">Principal Signature</div>
        </div>
    </div>
</body>
</html>
