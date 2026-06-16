<?php

$faviconBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents('public/favicon.png'));

$newCharHTML = <<<HTML
<style>
/* ── RESET & PAGE ── */
* { margin: 0; padding: 0; box-sizing: border-box; }
@page { size: A4 portrait; margin: 10mm; }
body {
    font-family: 'Helvetica', 'Arial', sans-serif;
    color: #1e293b;
    margin: 0;
    padding: 0;
}

/* ── EXACT A4 WRAPPER ── */
.a4-wrapper {
    position: relative;
    width: 100%;
    height: 275mm; /* Safe height slightly under 277mm */
    box-sizing: border-box;
    overflow: hidden;
    padding: 15mm;
    border: 3px double #1a365d;
}

/* ── INNER WATERMARK ── */
.watermark {
    position: absolute;
    /* Center manually to avoid DOMPDF transform bug */
    top: 350px;
    left: 200px;
    width: 350px;
    height: auto;
    opacity: 0.05;
    z-index: 1;
}

/* ── CONTENT AREA ── */
.content-area {
    position: relative;
    z-index: 5;
    width: 100%;
}

/* ── HEADER ── */
.header-table {
    width: 100%;
    margin-bottom: 20px;
    table-layout: fixed;
}
.header-table td { vertical-align: top; }
.header-left { width: 25%; }
.header-center { width: 50%; text-align: center; }
.header-right { width: 25%; text-align: right; }

.school-logo { width: 90px; height: auto; }
.meta-badge { font-size: 11px; padding: 5px; border: 1px solid #ccc; display: inline-block; background: #f8fafc; }
.school-name { font-size: 24px; font-weight: bold; color: #1a365d; margin: 5px 0; text-transform: uppercase; }
.location-text { font-size: 12px; color: #64748b; margin-bottom: 5px; }
.location-text:empty { display: none; }
.cert-no-label { font-size: 11px; color: #94a3b8; }
.cert-no-val { font-size: 12px; font-weight: bold; color: #b91c1c; }

/* ── TITLE ── */
.title-section { text-align: center; margin: 15px 0 25px 0; }
.title-banner { 
    display: inline-block; 
    background-color: #1a365d; 
    color: #fff; 
    padding: 8px 30px; 
    font-size: 20px; 
    font-weight: bold; 
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* ── BODY TEXT ── */
.cert-body { font-size: 15px; line-height: 1.6; text-align: justify; }
.cert-body p { margin-bottom: 10px; }

.fill-val { font-weight: bold; color: #1e293b; border-bottom: 1px solid #64748b; padding: 0 4px; }
.fill-date { white-space: nowrap; }
.lbl { font-size: 11px; color: #94a3b8; }
.grade-val { font-weight: bold; color: #b91c1c; font-style: italic; }

/* ── SIGNATURE ROW (FIXED TO BOTTOM) ── */
.signatures-area {
    position: absolute;
    bottom: 20px;
    left: 15mm;
    right: 15mm;
}
.sig-table { width: 100%; table-layout: fixed; }
.sig-table td { text-align: center; vertical-align: bottom; }
.sign-line { border-top: 1px solid #1a365d; width: 140px; margin: 0 auto 5px auto; }
.sign-label { font-size: 12px; font-weight: bold; text-transform: uppercase; color: #1a365d; }
.stamp-circle {
    width: 90px; height: 90px;
    border: 2px dashed #94a3b8;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    display: table;
}
.stamp-txt { display: table-cell; vertical-align: middle; font-size: 10px; color: #94a3b8; text-transform: uppercase; }
</style>

<div class="a4-wrapper">
    <img src="{$faviconBase64}" class="watermark" alt="">

    <div class="content-area">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <div class="meta-badge">
                        <div><span class="lbl">Date:</span> <span class="fill-date fill-val">{{issue_date}}</span></div>
                    </div>
                </td>
                <td class="header-center">
                    <img src="{$faviconBase64}" alt="School Crest" class="school-logo">
                    <h1 class="school-name">{{school_name}}</h1>
                    <p class="location-text">{{address}}</p>
                </td>
                <td class="header-right">
                    <div class="meta-badge">
                        <div class="cert-no-label">Certificate No.</div>
                        <div class="cert-no-val"><span style="white-space: nowrap">{{certificate_no}}</span></div>
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
                <span class="fill-val">{{student_name}}</span>
                <span class="lbl">(Student Name)</span>,
                son/daughter of Mr.
                <span class="fill-val">{{father_name}}</span>
                <span class="lbl">(Father Name)</span>,
                bearing General Register (GR) Number
                <span class="fill-val">{{admission_no}}</span>
                <span class="lbl">(GR. No)</span>,
                has been a bona fide student of this institution.
            </p>

            <p>
                He/She successfully completed his/her course of studies and passed the
                <span class="fill-val">{{class_name}}</span> Examination held in the academic year
                <span style="white-space: nowrap" class="fill-val fill-date">{{academic_year}}</span>.
                He/She attended this school during the period from
                <span style="white-space: nowrap" class="fill-val fill-date">{{admission_date}}</span> to
                <span style="white-space: nowrap" class="fill-val fill-date">{{leaving_date}}</span>.
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
                        <div class="stamp-txt">Official<br>Seal / Stamp</div>
                    </div>
                </td>
                <td>
                    <div class="sign-line"></div>
                    <div class="sign-label">Headmaster</div>
                </td>
            </tr>
        </table>
    </div>
</div>
HTML;

$file = 'update_certs.php';
$content = file_get_contents($file);

// Find where $characterContent starts and ends
$startPattern = '/\$characterContent = <<<[\'"]?EOD[\'"]?.*?(?=\n)/s';
$endPattern = '/\nEOD;\n/';

if (preg_match($startPattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
    $startPos = $matches[0][1] + strlen($matches[0][0]);
    $endMatchPos = strpos($content, "\nEOD;\n", $startPos);
    
    if ($endMatchPos !== false) {
        $before = substr($content, 0, $startPos);
        $after = substr($content, $endMatchPos);
        
        $newFileContent = $before . "\n" . $newCharHTML . $after;
        file_put_contents($file, $newFileContent);
        echo "Updated update_certs.php successfully.\n";
    } else {
        echo "Could not find end of EOD.\n";
    }
} else {
    echo "Could not find start of characterContent.\n";
}
