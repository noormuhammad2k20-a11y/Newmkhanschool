<?php

use Illuminate\Support\Facades\DB;

$styles = <<<CSS
<style>
.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 48px;
    font-weight: bold;
    color: rgba(26, 42, 108, 0.06);
    z-index: -1;
    white-space: nowrap;
    pointer-events: none;
    width: 100%;
    text-align: center;
}
.cert-wrapper {
    width: 100%;
    border: 3px solid #1a3a6b;
    padding: 15px;
    position: relative;
}
.cert-header {
    text-align: center;
    border-bottom: 2px solid #c9a227;
    padding-bottom: 8px;
    margin-bottom: 10px;
}
.cert-h1 {
    font-size: 16px;
    font-weight: bold;
    color: #1a2a6c;
    margin: 0;
    line-height: 1.3;
    text-transform: uppercase;
}
.cert-h2 {
    font-size: 13px;
    letter-spacing: 2px;
    color: #c9a227;
    margin: 6px 0 0 0;
    text-transform: uppercase;
}
.cert-divider {
    width: 60px;
    height: 3px;
    background: #c9a227;
    margin: 6px auto 0 auto;
}
.section-title {
    background: #1a2a6c;
    color: #fff;
    font-weight: bold;
    font-size: 10px;
    padding: 4px 8px;
    margin-bottom: 6px;
}
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    font-size: 10px;
}
.data-table td.label {
    width: 22%;
    font-weight: bold;
    padding: 4px 0;
    color: #1a2a6c;
}
.data-table td.value {
    width: 28%;
    color: #8b6914;
    border-bottom: 1px dashed #ccc;
    padding: 4px 4px 4px 2px;
}
.footer-table {
    width: 100%;
    margin-top: 15px;
    border-collapse: collapse;
}
.footer-col {
    text-align: center;
    vertical-align: bottom;
    padding: 0 5px;
}
.footer-qr { width: 25%; }
.footer-seal { width: 50%; }
.footer-sign { width: 25%; }
</style>
CSS;

$transfer = $styles . <<<HTML
<div class="watermark">{{school_name}}</div>
<div class="cert-wrapper">
    <div class="cert-header">
        <h1 class="cert-h1">{{school_name}}</h1>
        <h2 class="cert-h2">School Leaving Certificate</h2>
        <div class="cert-divider"></div>
    </div>

    <div class="section-title">Student Details</div>
    <table class="data-table">
        <tr>
            <td class="label">Certificate No:</td>
            <td class="value">{{certificate_no}}</td>
            <td class="label">Issue Date:</td>
            <td class="value">{{issue_date}}</td>
        </tr>
        <tr>
            <td class="label">Admission No:</td>
            <td class="value">{{admission_no}}</td>
            <td class="label">Student Name:</td>
            <td class="value">{{student_name}}</td>
        </tr>
        <tr>
            <td class="label">Father Name:</td>
            <td class="value">{{father_name}}</td>
            <td class="label">Date of Birth:</td>
            <td class="value">{{dob}}</td>
        </tr>
    </table>

    <div class="section-title">Academic Record</div>
    <table class="data-table">
        <tr>
            <td class="label">Date of Admission:</td>
            <td class="value">{{admission_date}}</td>
            <td class="label">Class Admitted:</td>
            <td class="value">{{class_admitted}}</td>
        </tr>
        <tr>
            <td class="label">Current Class:</td>
            <td class="value">{{class_name}}</td>
            <td class="label">Academic Year:</td>
            <td class="value">{{academic_year}}</td>
        </tr>
        <tr>
            <td class="label">Date of Leaving:</td>
            <td class="value">{{leaving_date}}</td>
            <td class="label">Reason:</td>
            <td class="value">{{purpose}}</td>
        </tr>
    </table>

    <div style="border-left: 3px solid #e0e0e0; padding: 8px 10px; margin: 10px 0; font-size: 10px; line-height: 1.6; color: #333;">
        This is to certify that the above mentioned student was a bonafide student of this institution.
        His/Her character and conduct were found to be <strong>Good</strong> during his/her stay.
        No dues are pending against him/her.
    </div>

    <table class="footer-table">
        <tr>
            <td class="footer-col footer-qr">
                {{qr_code}}
                <div style="font-size: 8px; color: #666; margin-top: 3px;">Scan to Verify</div>
            </td>
            <td class="footer-col footer-seal">
                <div style="width: 80px; height: 80px; border: 2px solid #1a2a6c; border-radius: 50%; margin: 0 auto; line-height: 80px; font-size: 9px; color: #999;">
                    School Seal
                </div>
            </td>
            <td class="footer-col footer-sign">
                {{signature}}
                <div style="border-top: 1px solid #333; padding-top: 4px; font-size: 9px; font-weight: bold; color: #1a2a6c; white-space: nowrap; margin-top: 5px;">
                    Principal's Signature & Stamp
                </div>
            </td>
        </tr>
    </table>
</div>
HTML;

$character = $styles . <<<HTML
<div class="watermark">{{school_name}}</div>
<div class="cert-wrapper" style="border-color: #2c3e50;">
    <div class="cert-header" style="border-color: #e74c3c;">
        <h1 class="cert-h1" style="color: #2c3e50;">{{school_name}}</h1>
        <h2 class="cert-h2" style="color: #e74c3c;">Character Certificate</h2>
        <div class="cert-divider" style="background: #2c3e50;"></div>
    </div>

    <table style="width: 100%; margin-bottom: 20px; font-size: 10px;">
        <tr>
            <td style="text-align: left; color: #555;"><strong>Ref No:</strong> {{certificate_no}}</td>
            <td style="text-align: right; color: #555;"><strong>Date:</strong> {{issue_date}}</td>
        </tr>
    </table>

    <div style="margin: 20px 0; font-size: 12px; line-height: 1.8; color: #333; text-align: justify;">
        <p style="text-indent: 40px; margin-bottom: 10px;">
            This is to certify with great pleasure that <strong>{{student_name}}</strong>, 
            Son/Daughter of <strong>{{father_name}}</strong>, 
            bearing Admission No. <strong>{{admission_no}}</strong>, has been a regular and bonafide student of Class 
            <strong>{{class_name}}</strong> at this institution during the academic session <strong>{{academic_year}}</strong>.
        </p>
        <p style="text-indent: 40px; margin-bottom: 10px;">
            During his/her time at this school, his/her character, conduct, and moral behavior have been found to be 
            <strong style="color: #e74c3c; font-style: italic; font-size: 14px;">Excellent</strong>. 
            He/She has shown great dedication to studies and maintained a disciplined and respectful attitude towards teachers and peers.
        </p>
        <p style="text-align: center; margin-top: 20px; font-style: italic; color: #7f8c8d; font-size: 11px;">
            "We wish him/her all the best for a bright and successful future."
        </p>
    </div>

    <table class="footer-table" style="margin-top: 40px;">
        <tr>
            <td class="footer-col footer-qr">
                {{qr_code}}
                <div style="font-size: 8px; color: #666; margin-top: 3px;">Scan to Verify</div>
            </td>
            <td class="footer-col footer-seal">
                <div style="width: 80px; height: 80px; border: 2px solid #2c3e50; border-radius: 50%; margin: 0 auto; line-height: 80px; font-size: 9px; color: #999;">
                    School Seal
                </div>
            </td>
            <td class="footer-col footer-sign">
                {{signature}}
                <div style="border-top: 1px solid #333; padding-top: 4px; font-size: 9px; font-weight: bold; color: #2c3e50; white-space: nowrap; margin-top: 5px;">
                    Principal's Signature & Stamp
                </div>
            </td>
        </tr>
    </table>
</div>
HTML;

$bonafide = $styles . <<<HTML
<div class="watermark">{{school_name}}</div>
<div class="cert-wrapper" style="border-color: #00695c;">
    <div class="cert-header" style="border-color: #004d40;">
        <h1 class="cert-h1" style="color: #004d40;">{{school_name}}</h1>
        <h2 class="cert-h2" style="color: #00695c;">Bonafide Certificate</h2>
        <div class="cert-divider" style="background: #004d40;"></div>
    </div>

    <table style="width: 100%; margin-bottom: 20px; font-size: 10px;">
        <tr>
            <td style="text-align: left; color: #555;"><strong>Ref No:</strong> {{certificate_no}}</td>
            <td style="text-align: right; color: #555;"><strong>Date:</strong> {{issue_date}}</td>
        </tr>
    </table>

    <div style="margin: 20px 0; font-size: 12px; line-height: 2; color: #333;">
        <p style="margin-bottom: 10px;">
            This is to certify that <strong>{{student_name}}</strong>, 
            Son/Daughter of <strong>{{father_name}}</strong>, 
            Resident of <strong style="font-style: italic; font-weight: normal; border-bottom: 1px dotted #999;">{{address}}</strong>, 
        </p>
        <p style="margin-bottom: 10px;">
            is a bonafide student of this school. He/She is presently studying in Class 
            <strong>{{class_name}}</strong> under Admission Number <strong>{{admission_no}}</strong> 
            for the academic session <strong>{{academic_year}}</strong>.
        </p>
        <p style="margin-bottom: 10px;">
            His/Her date of birth as per our school records is <strong>{{dob}}</strong>.
        </p>
        <div style="margin-top: 20px; font-size: 11px; background: #f0fdf4; padding: 8px; border-left: 3px solid #00695c;">
            This certificate is being issued on the request of the parent/guardian for the purpose of: <br>
            <strong style="color: #004d40;">{{purpose}}</strong>.
        </div>
    </div>

    <table class="footer-table" style="margin-top: 40px;">
        <tr>
            <td class="footer-col footer-qr">
                {{qr_code}}
                <div style="font-size: 8px; color: #666; margin-top: 3px;">Scan to Verify</div>
            </td>
            <td class="footer-col footer-seal">
                <div style="width: 80px; height: 80px; border: 2px solid #00695c; margin: 0 auto; line-height: 80px; font-size: 9px; color: #999;">
                    School Seal
                </div>
            </td>
            <td class="footer-col footer-sign">
                {{signature}}
                <div style="border-top: 1px solid #333; padding-top: 4px; font-size: 9px; font-weight: bold; color: #00695c; white-space: nowrap; margin-top: 5px;">
                    Principal's Signature & Stamp
                </div>
            </td>
        </tr>
    </table>
</div>
HTML;

DB::table('document_templates')->where('slug', 'transfer-certificate')->update(['content' => $transfer, 'design_type' => 'portrait']);
DB::table('document_templates')->where('slug', 'character-certificate')->update(['content' => $character, 'design_type' => 'portrait']);
DB::table('document_templates')->where('slug', 'bonafide-certificate')->update(['content' => $bonafide, 'design_type' => 'portrait']);

echo "DOMPDF optimized portrait certificates successfully seeded!\n";
