{{-- Character Certificate - Dynamic Blade Template --}}
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    .a4-page {
        width: 210mm; height: 297mm;
        max-height: 297mm;
        background-color: #fdfbf7;
        position: relative; overflow: hidden;
        page-break-inside: avoid;
        page-break-after: avoid;
        page-break-before: avoid;
    }
    .frame-outer {
        position: absolute; top: 15mm; left: 15mm; right: 15mm; bottom: 15mm;
        border: 4px solid #1a365d; z-index: 1; pointer-events: none;
    }
    .frame-inner {
        position: absolute;
        top: calc(15mm + 8px); left: calc(15mm + 8px); right: calc(15mm + 8px); bottom: calc(15mm + 8px);
        border: 1px solid #c9a73d; z-index: 1; pointer-events: none;
    }
    .corner { position: absolute; width: 35px; height: 35px; z-index: 2; }
    .top-left     { top: -2px; left: -2px; border-top: 6px solid #c9a73d; border-left: 6px solid #c9a73d; }
    .top-right    { top: -2px; right: -2px; border-top: 6px solid #c9a73d; border-right: 6px solid #c9a73d; }
    .bottom-left  { bottom: -2px; left: -2px; border-bottom: 6px solid #c9a73d; border-left: 6px solid #c9a73d; }
    .bottom-right { bottom: -2px; right: -2px; border-bottom: 6px solid #c9a73d; border-right: 6px solid #c9a73d; }
    .watermark {
        position: absolute; top: 50%; left: 50%;
        width: 450px; height: 450px;
        transform: translate(-50%, -50%);
        opacity: 0.05; z-index: 0; pointer-events: none;
    }
    .content-container {
        position: relative; padding: 25mm 25mm;
        z-index: 5; height: 297mm; max-height: 297mm; overflow: hidden;
        display: block; /* Removed flexbox for strict PDF compatibility */
        font-family: 'Times New Roman', Times, serif; color: #1e293b;
    }
    .header-table {
        width: 100%; border-bottom: 2px solid #e2e8f0;
        padding-bottom: 12px; margin-bottom: 12px; table-layout: fixed;
    }
    .header-left { width: 25%; vertical-align: top; }
    .header-center { width: 50%; text-align: center; vertical-align: top; }
    .header-right { width: 25%; vertical-align: top; text-align: right; }
    .meta-badge {
        background-color: #ffffff; border: 1px solid #cbd5e1;
        padding: 8px 12px; display: inline-block;
        font-family: 'Helvetica', Arial, sans-serif; font-size: 11px;
        color: #64748b; text-align: left; border-radius: 4px;
    }
    .meta-badge b { color: #1a365d; font-size: 13px; display: block; margin-top: 4px; }
    .school-logo { width: 70px; height: auto; margin-bottom: 8px; }
    .school-name {
        font-size: 20px; color: #1a365d; font-weight: bold;
        text-transform: uppercase; letter-spacing: 1px; margin: 0 0 4px 0; line-height: 1.2;
    }
    .location-text {
        font-family: 'Helvetica', Arial, sans-serif; font-size: 11px;
        color: #c9a73d; font-weight: bold; letter-spacing: 2px;
        text-transform: uppercase; margin: 0;
    }
    .title-section { text-align: center; margin: 15px 0 20px 0; }
    .title-banner {
        font-family: 'Georgia', serif; font-size: 30px;
        color: #1a365d; font-style: italic; font-weight: bold;
        letter-spacing: 2px; border-bottom: 3px solid #c9a73d;
        padding-bottom: 6px; display: inline-block;
    }
    .cert-body { text-align: justify; font-size: 15px; line-height: 1.7; margin-bottom: 20px; }
    .cert-body p { margin: 0 0 12px 0; }
    .fill-val {
        font-family: 'Georgia', serif; font-weight: bold;
        color: #1a365d; font-size: 17px;
        border-bottom: 1px dashed #64748b; padding: 0 8px;
    }
    .lbl {
        font-family: 'Helvetica', Arial, sans-serif; font-size: 10px;
        color: #94a3b8; vertical-align: super;
    }
    .grade-val {
        font-family: 'Georgia', serif; font-weight: bold;
        color: #c9a73d; font-size: 17px; text-transform: uppercase;
        letter-spacing: 1.5px; border-bottom: 2px solid #1a365d; padding: 0 8px;
    }
    .signatures-area { 
        position: absolute; 
        bottom: 28mm; 
        left: 25mm; 
        right: 25mm; 
        height: 35mm;
    }
    .sig-table { width: 100%; border-collapse: collapse; height: 100%; }
    .sig-table td { vertical-align: bottom; text-align: center; width: 33.33%; padding-bottom: 0; }
    .sign-line { border-top: 1px solid #1a365d; margin: 0 auto 8px auto; width: 70%; }
    .sign-label {
        font-family: 'Helvetica', Arial, sans-serif; font-size: 12px;
        font-weight: bold; color: #1a365d; text-transform: uppercase; letter-spacing: 1px;
    }
    .stamp-circle {
        display: inline-block; border: 2px solid #c9a73d; border-radius: 50%;
        width: 110px; height: 110px; background: rgba(253, 251, 247, 0.8); position: relative;
    }
    .stamp-circle::before {
        content: ''; position: absolute;
        top: 4px; left: 4px; right: 4px; bottom: 4px;
        border: 1px dashed #1a365d; border-radius: 50%;
    }
    .stamp-tbl { display: table; width: 100%; height: 100%; }
    .stamp-txt {
        display: table-cell; vertical-align: middle;
        font-family: 'Helvetica', Arial, sans-serif; font-size: 11px;
        font-weight: bold; color: #1a365d; text-transform: uppercase; line-height: 1.4;
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

    <img src="data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpath d='M60 10 L20 25 L20 65 C20 90 50 110 60 110 C70 110 100 90 100 65 L100 25 Z' fill='%23faf9f6' stroke='%231a365d' stroke-width='4'/%3E%3Cpath d='M60 16 L26 29 L26 63 C26 84 52 102 60 102 C68 102 94 84 94 63 L94 29 Z' fill='%231a365d'/%3E%3Cpolygon points='60,25 65,35 75,35 67,42 70,52 60,46 50,52 53,42 45,35 55,35' fill='%23c9a73d'/%3E%3Cpath d='M45 75 L45 60 L60 65 L75 60 L75 75 L60 80 Z' fill='%23c9a73d'/%3E%3Cpath d='M60 65 L60 80' stroke='%231a365d' stroke-width='2'/%3E%3C/svg%3E" class="watermark" alt="">

    <div class="content-container">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <div class="meta-badge">
                        Certificate No:
                        <b>{{ $certificate_no }}</b>
                    </div>
                </td>
                <td class="header-center">
                    <img src="data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpath d='M60 10 L20 25 L20 65 C20 90 50 110 60 110 C70 110 100 90 100 65 L100 25 Z' fill='%23faf9f6' stroke='%231a365d' stroke-width='4'/%3E%3Cpath d='M60 16 L26 29 L26 63 C26 84 52 102 60 102 C68 102 94 84 94 63 L94 29 Z' fill='%231a365d'/%3E%3Cpolygon points='60,25 65,35 75,35 67,42 70,52 60,46 50,52 53,42 45,35 55,35' fill='%23c9a73d'/%3E%3Cpath d='M45 75 L45 60 L60 65 L75 60 L75 75 L60 80 Z' fill='%23c9a73d'/%3E%3Cpath d='M60 65 L60 80' stroke='%231a365d' stroke-width='2'/%3E%3C/svg%3E" class="school-logo" alt="">
                    <h1 class="school-name">{{ $school_name }}</h1>
                    <p class="location-text">{{ $school_address }}</p>
                </td>
                <td class="header-right">
                    <div class="meta-badge">
                        Date of Issue:
                        <b>{{ $issue_date_formatted }}</b>
                    </div>
                </td>
            </tr>
        </table>

        <div class="title-section">
            <div class="title-banner">Character Certificate</div>
        </div>

        <div class="cert-body">
            <p>
                This is to officially certify that Mr./Ms.
                <span class="fill-val">{{ $student_name }}</span>
                <span class="lbl">(Student Name)</span>,
                son/daughter of Mr.
                <span class="fill-val">{{ $father_name }}</span>
                <span class="lbl">(Father Name)</span>,
                bearing General Register (GR) Number
                <span class="fill-val">{{ $admission_no }}</span>
                <span class="lbl">(GR. No)</span>,
                has been a bona fide student of this institution.
            </p>

            <p>
                He/She successfully completed his/her course of studies in
                <span class="fill-val">{{ $class_name }}</span>
                during the academic year
                <span class="fill-val">{{ $academic_year }}</span>.
                He/She attended this school during the period from
                <span class="fill-val">{{ $admission_date }}</span> to
                <span class="fill-val">{{ $leaving_date }}</span>.
            </p>

            <p>
                During his/her tenure at this school, his/her conduct and deportment have been observed
                as being <span class="grade-val">Excellent</span>.
                He/She is known to be hardworking, honest, and respectful towards his/her superiors and peers.
            </p>

            <p>
                To the best of our knowledge and belief, he/she has never participated in any activities
                subversive to the discipline or reputation of the institution. We wish him/her continued
                prosperity and success in all his/her future academic and personal endeavors.
            </p>
        </div>

        <div class="signatures-area">
            <table class="sig-table">
                <tr>
                    <td>
                        <div class="sign-line"></div>
                        <div class="sign-label">Class Teacher</div>
                    </td>
                    <td>
                        <div class="stamp-circle">
                            <div class="stamp-tbl">
                                <div class="stamp-txt">Official<br>Seal / Stamp</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        {!! $signature !!}
                        <div class="sign-line"></div>
                        <div class="sign-label">Headmaster</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>