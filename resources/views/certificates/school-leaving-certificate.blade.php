{{-- School Leaving Certificate - Dynamic Blade Template --}}
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
        position: relative; padding: 22mm 22mm;
        z-index: 5; height: 100%;
        display: flex; flex-direction: column;
        font-family: 'Montserrat', sans-serif; color: var(--text-main);
    }
    .header-section { text-align: center; position: relative; margin-bottom: 20px; }
    .meta-tags {
        display: flex; justify-content: space-between;
        font-size: 11px; font-weight: 700; color: var(--royal-blue);
        letter-spacing: 1px; margin-bottom: 5px;
    }
    .header-logo { width: 90px; margin-bottom: 10px; }
    .school-title {
        font-family: 'Cinzel', serif; font-size: 25px; font-weight: 800;
        color: var(--royal-blue); margin: 0; line-height: 1.2; text-transform: uppercase;
    }
    .school-address {
        font-size: 12px; font-weight: 700; color: #555;
        letter-spacing: 3px; text-transform: uppercase; margin: 8px 0 20px 0;
    }
    .certificate-title-wrapper { text-align: center; margin-bottom: 25px; }
    .certificate-title {
        display: inline-block; background-color: var(--royal-blue); color: #fff;
        font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700;
        padding: 8px 40px; border: 2px solid var(--rich-gold);
        letter-spacing: 3px; text-transform: uppercase;
    }
    .form-section { margin-bottom: 25px; }
    .section-heading {
        font-family: 'Cinzel', serif; font-size: 15px; font-weight: 800;
        color: var(--royal-blue); border-left: 4px solid var(--rich-gold);
        background: rgba(10, 25, 49, 0.05); padding: 6px 12px;
        margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;
    }
    .form-table { width: 100%; border-collapse: separate; border-spacing: 0 16px; }
    .form-table td { vertical-align: bottom; }
    .label-cell {
        font-size: 13px; font-weight: 700; color: var(--royal-blue);
        white-space: nowrap; width: 1%; padding-right: 10px;
    }
    .value-cell {
        font-size: 15px; font-weight: 600; color: #111;
        border-bottom: 2px dotted #888; padding-bottom: 2px; padding-left: 5px;
    }
    .footer-area {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-top: auto; padding-bottom: 15mm;
    }
    .signature-block { text-align: center; width: 200px; }
    .sign-line { border-top: 1px solid var(--royal-blue); margin-bottom: 8px; }
    .sign-text {
        font-family: 'Cinzel', serif; font-weight: 700; font-size: 12px;
        color: var(--royal-blue); letter-spacing: 1px;
    }
    .stamp-box {
        width: 120px; height: 120px; border: 2px dashed var(--rich-gold);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        text-align: center; font-size: 12px; font-family: 'Cinzel', serif;
        font-weight: 800; color: var(--rich-gold); text-transform: uppercase;
        background: rgba(197, 160, 89, 0.05); transform: rotate(-10deg);
    }
</style>

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
            <div class="meta-tags">
                <span>S. NO: <strong>{{ $certificate_no }}</strong></span>
                <span>DATE: <strong>{{ $issue_date }}</strong></span>
            </div>

            <img src="data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpath d='M60 10 L20 25 L20 65 C20 90 50 110 60 110 C70 110 100 90 100 65 L100 25 Z' fill='%23ffffff' stroke='%230A1931' stroke-width='4'/%3E%3Cpath d='M60 16 L26 29 L26 63 C26 84 52 102 60 102 C68 102 94 84 94 63 L94 29 Z' fill='%230A1931'/%3E%3Cpolygon points='60,25 65,35 75,35 67,42 70,52 60,46 50,52 53,42 45,35 55,35' fill='%23C5A059'/%3E%3Cpath d='M45 75 L45 60 L60 65 L75 60 L75 75 L60 80 Z' fill='%23C5A059'/%3E%3Cpath d='M60 65 L60 80' stroke='%230A1931' stroke-width='2'/%3E%3C/svg%3E" alt="" class="header-logo">

            <h1 class="school-title">{{ $school_name }}</h1>
            <p class="school-address">{{ $school_address }}</p>
        </div>

        <div class="certificate-title-wrapper">
            <div class="certificate-title">School Leaving Certificate</div>
        </div>

        <div class="form-section">
            <div class="section-heading">I. Student Profile</div>
            <table class="form-table">
                <tr>
                    <td class="label-cell">Certificate No:</td>
                    <td class="value-cell" style="width: 30%;">{{ $certificate_no }}</td>
                    <td class="label-cell" style="padding-left: 15px;">General Register (GR) No:</td>
                    <td class="value-cell" style="width: 30%;">{{ $admission_no }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Student Name:</td>
                    <td class="value-cell" colspan="3">{{ $student_name }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Father's Name:</td>
                    <td class="value-cell" colspan="3">{{ $father_name }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Caste / Tribe:</td>
                    <td class="value-cell">{{ $caste }}</td>
                    <td class="label-cell" style="padding-left: 15px;">Religion:</td>
                    <td class="value-cell">{{ $religion }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Date of Birth (Figures):</td>
                    <td class="value-cell">{{ $dob }}</td>
                    <td class="label-cell" style="padding-left: 15px;">Place of Birth:</td>
                    <td class="value-cell">{{ $birth_place }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Date of Birth (Words):</td>
                    <td class="value-cell" colspan="3">{{ $dob_words }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Previous School Attended:</td>
                    <td class="value-cell" colspan="3">{{ $previous_school }}</td>
                </tr>
            </table>
        </div>

        <div class="form-section">
            <div class="section-heading">II. Academic History</div>
            <table class="form-table">
                <tr>
                    <td class="label-cell">Date of Admission:</td>
                    <td class="value-cell" style="width: 30%;">{{ $admission_date }}</td>
                    <td class="label-cell" style="padding-left: 15px;">Admitted to Class:</td>
                    <td class="value-cell" style="width: 30%;">{{ $class_admitted }}{{ $section_name ? ' (Section '.$section_name.')' : '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Current / Last Class:</td>
                    <td class="value-cell">{{ $class_name }}{{ $section_name ? ' (Section '.$section_name.')' : '' }}</td>
                    <td class="label-cell" style="padding-left: 15px;">Session:</td>
                    <td class="value-cell">{{ $academic_year }}</td>
                </tr>
            </table>
        </div>

        <div class="form-section">
            <div class="section-heading">III. Departure Information</div>
            <table class="form-table">
                <tr>
                    <td class="label-cell">Date of Leaving:</td>
                    <td class="value-cell" style="width: 30%;">{{ $leaving_date }}</td>
                    <td class="label-cell" style="padding-left: 15px;">Academic Progress:</td>
                    <td class="value-cell" style="width: 30%;">Good</td>
                </tr>
                <tr>
                    <td class="label-cell">Reason for Leaving:</td>
                    <td class="value-cell" colspan="3">{{ $purpose }}</td>
                </tr>
                <tr>
                    <td class="label-cell">General Remarks:</td>
                    <td class="value-cell" colspan="3">No adverse remarks. Conduct was satisfactory throughout.</td>
                </tr>
            </table>
        </div>

        <div class="footer-area">
            <div class="signature-block">
                <div class="sign-line"></div>
                <div class="sign-text">Class Teacher</div>
            </div>
            <div class="stamp-box">
                Official<br>Seal / Stamp
            </div>
            <div class="signature-block">
                {!! $signature !!}
                <div class="sign-line"></div>
                <div class="sign-text">Principal / Headmaster</div>
            </div>
        </div>
    </div>
</div>