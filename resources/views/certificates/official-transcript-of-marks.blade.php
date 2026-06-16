{{-- Official Transcript of Marks - Dynamic Blade Template --}}
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
        --royal-blue: #0A1931;
        --rich-gold: #C5A059;
        --text-main: #222222;
        --paper-white: #ffffff;
    }
    .a4-page {
        width: 210mm; height: 297mm;
        background-color: var(--paper-white);
        position: relative; overflow: hidden;
    }
    .frame-outer {
        position: absolute; top: 12mm; left: 12mm; right: 12mm; bottom: 12mm;
        border: 4px solid var(--royal-blue); z-index: 1; pointer-events: none;
    }
    .frame-inner {
        position: absolute;
        top: calc(12mm + 6px); left: calc(12mm + 6px); right: calc(12mm + 6px); bottom: calc(12mm + 6px);
        border: 1px solid var(--rich-gold); z-index: 1; pointer-events: none;
    }
    .corner { position: absolute; width: 30px; height: 30px; z-index: 2; }
    .top-left     { top: -2px; left: -2px; border-top: 5px solid var(--rich-gold); border-left: 5px solid var(--rich-gold); }
    .top-right    { top: -2px; right: -2px; border-top: 5px solid var(--rich-gold); border-right: 5px solid var(--rich-gold); }
    .bottom-left  { bottom: -2px; left: -2px; border-bottom: 5px solid var(--rich-gold); border-left: 5px solid var(--rich-gold); }
    .bottom-right { bottom: -2px; right: -2px; border-bottom: 5px solid var(--rich-gold); border-right: 5px solid var(--rich-gold); }
    .watermark {
        position: absolute; top: 50%; left: 50%;
        width: 450px; height: 450px;
        transform: translate(-50%, -50%);
        opacity: 0.04; z-index: 0; pointer-events: none; filter: grayscale(100%);
    }
    .content-container {
        position: relative; padding: 23mm 22mm;
        z-index: 5; height: 100%;
        display: flex; flex-direction: column;
        font-family: 'Montserrat', sans-serif; color: var(--text-main);
    }
    .header-section {
        text-align: center; position: relative; margin-bottom: 15px;
        border-bottom: 2px solid var(--royal-blue); padding-bottom: 10px;
    }
    .header-logo { width: 70px; margin-bottom: 5px; }
    .school-title {
        font-family: 'Cinzel', serif; font-size: 22px; font-weight: 800;
        color: var(--royal-blue); margin: 0 0 5px 0; line-height: 1.2; text-transform: uppercase;
    }
    .school-address {
        font-size: 11px; font-weight: 600; color: #555;
        letter-spacing: 2px; text-transform: uppercase; margin: 0 0 10px 0;
    }
    .document-title {
        display: inline-block; border-top: 1px solid var(--rich-gold);
        border-bottom: 1px solid var(--rich-gold); padding: 5px 25px;
        color: var(--royal-blue); font-family: 'Cinzel', serif; font-weight: 800;
        text-transform: uppercase; letter-spacing: 3px; font-size: 15px;
    }
    .student-info-grid {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 8px 30px; background: #fdfdfd; padding: 12px;
        border: 1px solid #e2e8f0; margin-bottom: 15px;
        border-left: 4px solid var(--rich-gold);
    }
    .field { display: flex; align-items: flex-end; }
    .key {
        font-weight: 700; color: var(--royal-blue); width: 120px;
        font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .value {
        flex-grow: 1; font-weight: 600; color: #111; font-size: 13px;
        border-bottom: 1px dashed #cbd5e1; padding-bottom: 2px; padding-left: 5px;
    }
    .transcript-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 12px; }
    .transcript-table th, .transcript-table td {
        border: 1px solid #cbd5e1; padding: 6px 10px; text-align: center;
    }
    .transcript-table th {
        background-color: var(--royal-blue); color: #ffffff;
        text-transform: uppercase; font-weight: 700; letter-spacing: 1px;
        font-size: 10px; padding: 8px 10px;
    }
    .transcript-table tr td:first-child, .transcript-table tr th:first-child {
        text-align: left; padding-left: 15px; font-weight: 600;
    }
    .transcript-table tbody tr:nth-child(even) { background-color: #f8fafc; }
    .total-row td {
        background-color: #f1f5f9; color: var(--royal-blue);
        font-weight: 800; border-top: 2px solid var(--royal-blue);
        font-size: 13px; padding: 8px 10px;
    }
    .total-row td:first-child {
        text-transform: uppercase; letter-spacing: 1px;
        text-align: right; padding-right: 15px;
    }
    .marks-legend {
        font-size: 10px; color: #64748b; margin-bottom: 15px;
        padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 4px; text-align: center;
    }
    .marks-legend strong { color: var(--royal-blue); }
    .result-summary {
        font-family: 'Cinzel', serif; font-weight: 800; text-align: center;
        margin-bottom: 20px; color: #111; font-size: 16px; padding: 10px;
        background: #fdfbef; border: 1px solid var(--rich-gold); letter-spacing: 1px;
    }
    .result-summary span { color: var(--royal-blue); margin: 0 10px; font-size: 18px; }
    .footer-content-block {
        display: flex; justify-content: space-between;
        align-items: flex-start; gap: 20px; margin-bottom: auto;
    }
    .remarks-container { flex: 1; }
    .remarks-label {
        font-weight: 800; color: var(--royal-blue); text-transform: uppercase;
        font-size: 11px; margin-bottom: 4px; letter-spacing: 1px;
    }
    .remarks-text-box {
        font-style: italic; color: #334155; padding: 10px; min-height: 60px;
        border: 1px solid #e2e8f0; background: #f8fafc; font-size: 12px;
        line-height: 1.5; border-left: 3px solid var(--royal-blue);
    }
    .stamp-container {
        width: 90px; height: 90px; border: 2px solid var(--royal-blue);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        text-align: center; font-size: 9px; color: var(--royal-blue);
        font-weight: 800; text-transform: uppercase;
        background: rgba(10, 25, 49, 0.03); flex-shrink: 0;
        transform: rotate(-15deg); position: relative;
        box-shadow: inset 0 0 0 2px var(--rich-gold);
    }
    .stamp-container::after {
        content: "OFFICIAL SEAL"; position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%); width: 100%;
        border-top: 1px dashed var(--royal-blue); border-bottom: 1px dashed var(--royal-blue);
        padding: 2px 0;
    }
    .signature-grid {
        margin-top: auto; display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 30px; position: relative;
    }
    .signature-block { text-align: center; position: relative; }
    .signature-line {
        border-top: 1px solid var(--royal-blue); margin-bottom: 6px;
        width: 80%; margin-left: auto; margin-right: auto;
    }
    .signature-block label {
        display: block; font-size: 10px; color: var(--royal-blue);
        font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
    }
    .issue-date-val {
        position: absolute; bottom: 15px; width: 100%; text-align: center;
        font-family: 'Courier New', Courier, monospace; font-weight: bold;
        font-size: 14px; color: #333;
    }
</style>

@php
    // Retrieve student marks from the marks table
    $marks = collect([]);
    $totalObtained = 0;
    $totalMax = 0;

    if ($student->id) {
        $activeYear = \App\Models\AcademicYear::where('is_active', 1)->first();
        $query = \App\Models\Mark::where('student_id', $student->id)
            ->with('subject');

        if ($activeYear) {
            $query->where('academic_year_id', $activeYear->id);
        }

        $marks = $query->latest('id')->get()->unique('subject_id');
    }

    foreach ($marks as $mark) {
        $totalObtained += $mark->marks_obtained ?? 0;
        $totalMax += $mark->total_marks ?? 100;
    }

    $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;

    if ($percentage >= 80) $grade = 'A-1';
    elseif ($percentage >= 70) $grade = 'A';
    elseif ($percentage >= 60) $grade = 'B';
    elseif ($percentage >= 50) $grade = 'C';
    elseif ($percentage >= 33) $grade = 'D';
    else $grade = 'F';

    $status = $percentage >= 33 ? 'PASS' : 'FAIL';
@endphp

<div class="a4-page">
    <div class="frame-outer">
        <div class="corner top-left"></div>
        <div class="corner top-right"></div>
        <div class="corner bottom-left"></div>
        <div class="corner bottom-right"></div>
    </div>
    <div class="frame-inner"></div>

    <img src="data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpath d='M60 10 L20 25 L20 65 C20 90 50 110 60 110 C70 110 100 90 100 65 L100 25 Z' fill='%23ffffff' stroke='%230A1931' stroke-width='4'/%3E%3Cpath d='M60 16 L26 29 L26 63 C26 84 52 102 60 102 C68 102 94 84 94 63 L94 29 Z' fill='%230A1931'/%3E%3Cpolygon points='60,25 65,35 75,35 67,42 70,52 60,46 50,52 53,42 45,35 55,35' fill='%23C5A059'/%3E%3Cpath d='M45 75 L45 60 L60 65 L75 60 L75 75 L60 80 Z' fill='%23C5A059'/%3E%3Cpath d='M60 65 L60 80' stroke='%230A1931' stroke-width='2'/%3E%3C/svg%3E" class="watermark" alt="">

    <div class="content-container">
        <div class="header-section">
            <img src="data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpath d='M60 10 L20 25 L20 65 C20 90 50 110 60 110 C70 110 100 90 100 65 L100 25 Z' fill='%23ffffff' stroke='%230A1931' stroke-width='4'/%3E%3Cpath d='M60 16 L26 29 L26 63 C26 84 52 102 60 102 C68 102 94 84 94 63 L94 29 Z' fill='%230A1931'/%3E%3Cpolygon points='60,25 65,35 75,35 67,42 70,52 60,46 50,52 53,42 45,35 55,35' fill='%23C5A059'/%3E%3Cpath d='M45 75 L45 60 L60 65 L75 60 L75 75 L60 80 Z' fill='%23C5A059'/%3E%3Cpath d='M60 65 L60 80' stroke='%230A1931' stroke-width='2'/%3E%3C/svg%3E" alt="" class="header-logo">
            <h1 class="school-title">{{ $school_name }}</h1>
            <p class="school-address">{{ $school_address }}</p>
            <div class="document-title">Official Transcript of Marks</div>
        </div>

        <div class="student-info-grid">
            <div class="field">
                <span class="key">Student Name:</span>
                <span class="value">{{ $student_name }}</span>
            </div>
            <div class="field">
                <span class="key">Roll No:</span>
                <span class="value">{{ $admission_no }}</span>
            </div>
            <div class="field">
                <span class="key">Father's Name:</span>
                <span class="value">{{ $father_name }}</span>
            </div>
            <div class="field">
                <span class="key">Examination:</span>
                <span class="value">Final Exam {{ $academic_year }}</span>
            </div>
            <div class="field">
                <span class="key">Class/Grade:</span>
                <span class="value">{{ $class_name }}</span>
            </div>
            <div class="field">
                <span class="key">Academic Year:</span>
                <span class="value">{{ $academic_year }}</span>
            </div>
        </div>

        <table class="transcript-table">
            <thead>
                <tr>
                    <th>Subject Description</th>
                    <th>Maximum Marks</th>
                    <th>Marks Obtained</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @if($marks->count() > 0)
                    @foreach($marks as $mark)
                    <tr>
                        <td>{{ $mark->subject->name ?? 'Subject' }}</td>
                        <td>{{ $mark->total_marks ?? 100 }}</td>
                        <td>{{ $mark->marks_obtained ?? '-' }}</td>
                        <td>
                            @php
                                $pct = ($mark->total_marks > 0) ? ($mark->marks_obtained / $mark->total_marks) * 100 : 0;
                            @endphp
                            @if($pct >= 80) Excellent
                            @elseif($pct >= 60) Good
                            @elseif($pct >= 33) Satisfactory
                            @else Needs Improvement
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    {{-- Fallback placeholder rows when no marks data --}}
                    <tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                    <tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                    <tr><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td>Total Marks</td>
                    <td>{{ $totalMax > 0 ? $totalMax : '-' }}</td>
                    <td>{{ $totalObtained > 0 ? $totalObtained : '-' }}</td>
                    <td>-</td>
                </tr>
            </tfoot>
        </table>

        <div class="marks-legend">
            <strong>Grading Legend:</strong> 80%+ (A1/Excellent), 70%+ (A/Good), 60%+ (B/Satisfactory) | <strong>Pass Mark: 33%</strong>
        </div>

        <div class="result-summary">
            Percentage: <span>{{ $percentage }}%</span> | Final Grade: <span>{{ $grade }}</span> | Status: <span>{{ $status }}</span>
        </div>

        <div class="footer-content-block">
            <div class="remarks-container">
                <div class="remarks-label">Principal / Teacher Remarks</div>
                <div class="remarks-text-box">
                    @if($percentage >= 80)
                        Excellent performance across all subjects. Shows great interest in learning and consistently demonstrates academic excellence.
                    @elseif($percentage >= 60)
                        Good overall performance. Student shows satisfactory progress and is encouraged to continue improving.
                    @elseif($percentage >= 33)
                        Performance is acceptable. Student is advised to put in more effort in upcoming examinations.
                    @else
                        Performance needs improvement. Student is advised to seek additional help and focus on studies.
                    @endif
                </div>
            </div>

            <div class="stamp-container">
                <span style="margin-top: -20px; display: block;">GBHSS<br>DHILYAR</span>
                <span style="margin-top: 25px; display: block; font-size: 7px;">SINDH BD. OF ED.</span>
            </div>
        </div>

        <div class="signature-grid">
            <div class="signature-block">
                <div class="signature-line"></div>
                <label>Class Teacher</label>
            </div>
            <div class="signature-block">
                {!! $signature !!}
                <div class="signature-line"></div>
                <label>Headmaster</label>
            </div>
            <div class="signature-block">
                <div class="issue-date-val">{{ $issue_date }}</div>
                <div class="signature-line"></div>
                <label>Issue Date</label>
            </div>
        </div>
    </div>
</div>