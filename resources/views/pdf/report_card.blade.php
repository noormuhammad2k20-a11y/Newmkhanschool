<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; margin: 30px; }
    .header { text-align: center; border-bottom: 3px double #000666; padding-bottom: 12px; margin-bottom: 15px; }
    .header h1 { color: #000666; margin: 0; font-size: 20px; }
    .header h2 { color: #333; margin: 4px 0; font-size: 14px; font-weight: normal; }
    .header p { margin: 2px 0; font-size: 10px; color: #666; }
    .title { background: #000666; color: #fff; text-align: center; padding: 8px; font-size: 13px; font-weight: bold; margin: 15px 0; }
    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .info-table td { padding: 5px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
    .info-table td.label { font-weight: bold; color: #555; width: 30%; }
    .marks-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .marks-table th { background: #f5f5f5; border: 1px solid #ddd; padding: 7px 10px; text-align: left; font-size: 10px; color: #555; font-weight: bold; }
    .marks-table td { border: 1px solid #ddd; padding: 7px 10px; font-size: 11px; }
    .marks-table tr:nth-child(even) { background: #fafafa; }
    .marks-table .total-row { background: #f0f0f0; font-weight: bold; }
    .summary-box { display: inline-block; border: 2px solid #000666; padding: 15px 30px; text-align: center; margin-top: 10px; }
    .summary-box .grade { font-size: 28px; font-weight: bold; color: #000666; }
    .summary-box .pct { font-size: 14px; color: #555; }
    .signatures { width: 100%; margin-top: 40px; }
    .signatures td { padding: 0 20px; text-align: center; width: 33%; vertical-align: top; }
    .signatures .line { border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; font-size: 10px; color: #555; }
    .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
</style>
</head>
<body>
<div class="header">
    <h1>{{ $school->name ?? 'School' }}</h1>
    <p>{{ $school->address ?? '' }}</p>
    <p>Phone: {{ $school->phone ?? '' }} | Email: {{ $school->email ?? '' }}</p>
</div>

<div class="title">REPORT CARD — {{ strtoupper($card->examType->name ?? 'EXAM') }}</div>

<table class="info-table">
    <tr>
        <td class="label">Student Name:</td>
        <td><strong>{{ $student->first_name }} {{ $student->last_name }}</strong></td>
        <td class="label">Admission No:</td>
        <td>{{ $student->admission_no }}</td>
    </tr>
    <tr>
        <td class="label">Class:</td>
        <td>{{ $student->currentClass->name ?? '—' }} {{ $student->currentSection->name ?? '' }}</td>
        <td class="label">Academic Year:</td>
        <td>{{ $activeYear->name ?? '—' }}</td>
    </tr>
    <tr>
        <td class="label">Rank in Class:</td>
        <td><strong>#{{ $card->rank }}</strong></td>
        <td class="label">Date Issued:</td>
        <td>{{ now()->format('d M Y') }}</td>
    </tr>
</table>

<table class="marks-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Marks Obtained</th>
            <th>Total Marks</th>
            <th>Percentage</th>
            <th>Grade</th>
        </tr>
    </thead>
    <tbody>
        @php $totalObtained = 0; $totalMax = 0; @endphp
        @foreach($marks as $i => $m)
        @php $totalObtained += $m->marks_obtained; $totalMax += $m->total_marks; @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $m->subject->name ?? '—' }}</td>
            <td>{{ $m->marks_obtained }}</td>
            <td>{{ $m->total_marks }}</td>
            <td>{{ $m->percentage }}%</td>
            <td>{{ $m->grade }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="2"><strong>TOTAL</strong></td>
            <td><strong>{{ $totalObtained }}</strong></td>
            <td><strong>{{ $totalMax }}</strong></td>
            <td><strong>{{ $card->total_percentage }}%</strong></td>
            <td><strong>{{ $card->grade }}</strong></td>
        </tr>
    </tbody>
</table>

<div style="text-align: center;">
    <div class="summary-box">
        <div class="grade">{{ $card->grade }}</div>
        <div class="pct">{{ $card->total_percentage }}%</div>
        <div style="font-size: 10px; color: #888; margin-top: 4px;">{{ $card->remarks }}</div>
    </div>
</div>

<table class="signatures">
    <tr>
        <td><div class="line">Class Teacher</div></td>
        <td><div class="line">Principal</div></td>
        <td><div class="line">Parent / Guardian</div></td>
    </tr>
</table>

<div class="footer">
    This report card is computer-generated and is official property of {{ $school->name ?? 'the School' }}.<br>
    Generated on {{ now()->format('d M Y, h:i A') }}
</div>
</body>
</html>
