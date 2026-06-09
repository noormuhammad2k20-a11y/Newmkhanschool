<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Card - {{ $student->first_name }} {{ $student->last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 28px;
            font-weight: bold;
            color: #0056b3;
            margin: 0;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-top: 5px;
        }
        .student-info {
            width: 100%;
            margin-bottom: 30px;
        }
        .student-info td {
            padding: 8px;
            font-size: 14px;
        }
        .student-info .label {
            font-weight: bold;
            width: 15%;
        }
        .student-info .value {
            width: 35%;
            border-bottom: 1px solid #ddd;
        }
        table.marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.marks-table th, table.marks-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table.marks-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #0056b3;
        }
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        .summary-box table {
            width: 100%;
        }
        .summary-box th {
            text-align: left;
            padding: 8px;
        }
        .summary-box td {
            text-align: left;
            padding: 8px;
            font-weight: bold;
            color: #0056b3;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .signatures {
            margin-top: 50px;
            width: 100%;
        }
        .signatures td {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #333;
            width: 33%;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="school-name">{{ $student->school->name ?? 'EduGov Management System' }}</h1>
        <p class="subtitle">Academic Report Card | {{ $academicYear->name ?? 'Current Year' }}</p>
    </div>

    <table class="student-info">
        <tr>
            <td class="label">Student Name:</td>
            <td class="value">{{ $student->first_name }} {{ $student->last_name }}</td>
            <td class="label">Admission No:</td>
            <td class="value">{{ $student->admission_no }}</td>
        </tr>
        <tr>
            <td class="label">Class:</td>
            <td class="value">{{ $student->currentClass->name ?? '' }}</td>
            <td class="label">Section:</td>
            <td class="value">{{ $student->currentSection->name ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Date of Birth:</td>
            <td class="value">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d-M-Y') }}</td>
            <td class="label">Roll No:</td>
            <td class="value">{{ $student->roll_number }}</td>
        </tr>
    </table>

    <table class="marks-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Marks Obtained</th>
                <th>Total Marks</th>
                <th>Percentage</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($marks as $mark)
                @php
                    $subjectPct = $mark->total_marks > 0 ? round(($mark->marks_obtained / $mark->total_marks) * 100, 1) : 0;
                    $subjectGrade = match(true) {
                        $subjectPct >= 90 => 'A+', $subjectPct >= 80 => 'A',
                        $subjectPct >= 70 => 'B+', $subjectPct >= 60 => 'B',
                        $subjectPct >= 50 => 'C',  $subjectPct >= 40 => 'D',
                        default => 'F',
                    };
                @endphp
                <tr>
                    <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                    <td>{{ $mark->marks_obtained }}</td>
                    <td>{{ $mark->total_marks }}</td>
                    <td>{{ $subjectPct }}%</td>
                    <td>{{ $subjectGrade }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <table>
            <tr>
                <th>Total Percentage:</th>
                <td>{{ $pct }}%</td>
                <th>Overall Grade:</th>
                <td>
                    {{ match(true) {
                        $pct >= 90 => 'A+', $pct >= 80 => 'A',
                        $pct >= 70 => 'B+', $pct >= 60 => 'B',
                        $pct >= 50 => 'C',  $pct >= 40 => 'D',
                        default => 'F',
                    } }}
                </td>
            </tr>
        </table>
    </div>

    <table class="signatures" style="margin-top: 80px;">
        <tr>
            <td>Class Teacher</td>
            <td>Principal</td>
            <td>Parent/Guardian</td>
        </tr>
    </table>

    <div class="footer">
        <p>This is a system-generated document. Printed on {{ now()->format('d-M-Y h:i A') }}</p>
    </div>

</body>
</html>
