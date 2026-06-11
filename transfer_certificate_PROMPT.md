# TASK: Replace Transfer Certificate Blade Template — Complete Rewrite

## Project
NewMkhanSchool · Laravel 11 · PHP 8.2 · dompdf (barryvdh/laravel-dompdf)

## File to Replace
`resources/views/certificates/transfer_certificate.blade.php`
(find exact path via the controller at route `admin/advanced/documents/preview`)

## Action
**Delete the entire existing Blade file content and replace with the complete template below.**
Do NOT modify anything else — no controller changes, no config changes needed unless noted.

---

## COMPLETE BLADE TEMPLATE (copy exactly as-is)

```blade
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>

/* ═══════════════════════════════════════════
   PAGE SETUP — dompdf A4 single page
═══════════════════════════════════════════ */
@page {
    size: A4 portrait;
    margin: 10mm 12mm 10mm 12mm;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10.5px;
    color: #1a1a2e;
    background: #ffffff;
    margin: 0;
    padding: 0;
}

/* ═══════════════════════════════════════════
   WATERMARK — very faint, behind everything
═══════════════════════════════════════════ */
.watermark {
    position: fixed;
    top: 38%;
    left: -5%;
    width: 110%;
    text-align: center;
    font-size: 52px;
    font-weight: bold;
    color: #1a2a6c;
    opacity: 0.035;
    transform: rotate(-28deg);
    z-index: 0;
    white-space: nowrap;
    letter-spacing: 2px;
}

/* ═══════════════════════════════════════════
   OUTER CERTIFICATE FRAME
═══════════════════════════════════════════ */
.cert-frame {
    width: 100%;
    position: relative;
    z-index: 1;
    border: 3px solid #1a2a6c;
    padding: 0;
}
.cert-inner {
    border: 1px solid #c9a227;
    margin: 4px;
    padding: 14px 16px 12px 16px;
}

/* ═══════════════════════════════════════════
   HEADER
═══════════════════════════════════════════ */
.header-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 6px;
}
.header-logo-cell {
    width: 60px;
    text-align: center;
    vertical-align: middle;
}
.header-logo {
    width: 52px;
    height: 52px;
    border: 2px solid #1a2a6c;
    border-radius: 50%;
    background: #f0f4ff;
    text-align: center;
    line-height: 52px;
    font-size: 8px;
    color: #1a2a6c;
    font-weight: bold;
}
.header-center {
    text-align: center;
    vertical-align: middle;
    padding: 0 8px;
}
.school-name {
    font-size: 16px;
    font-weight: bold;
    color: #1a2a6c;
    letter-spacing: 0.5px;
    line-height: 1.25;
}
.school-address {
    font-size: 8.5px;
    color: #555;
    margin-top: 2px;
}
.header-divider {
    height: 3px;
    background: linear-gradient(to right, #1a2a6c, #c9a227, #1a2a6c);
    margin: 6px 0;
    /* dompdf doesn't support linear-gradient — use border trick */
    background: #1a2a6c;
    border-top: 1px solid #c9a227;
    border-bottom: 1px solid #c9a227;
}
.cert-title-wrap {
    text-align: center;
    margin: 6px 0 10px 0;
}
.cert-title {
    font-size: 13px;
    font-weight: bold;
    letter-spacing: 3px;
    color: #c9a227;
}
.cert-title-line {
    width: 80px;
    height: 2px;
    background: #c9a227;
    margin: 3px auto 0 auto;
}

/* ═══════════════════════════════════════════
   CERT NO / DATE — top bar
═══════════════════════════════════════════ */
.cert-meta-table {
    width: 100%;
    border-collapse: collapse;
    background: #f5f7ff;
    border: 1px solid #d0d8f0;
    margin-bottom: 10px;
}
.cert-meta-table td {
    padding: 4px 10px;
    font-size: 9.5px;
}
.cert-meta-label { color: #555; }
.cert-meta-value { font-weight: bold; color: #1a2a6c; }

/* ═══════════════════════════════════════════
   SECTION HEADERS
═══════════════════════════════════════════ */
.section-header {
    background: #1a2a6c;
    color: #ffffff;
    font-weight: bold;
    font-size: 9.5px;
    padding: 4px 10px;
    letter-spacing: 1px;
    margin-bottom: 4px;
}
.section-header-gold {
    background: #c9a227;
    color: #ffffff;
    font-weight: bold;
    font-size: 9.5px;
    padding: 4px 10px;
    letter-spacing: 1px;
    margin-bottom: 4px;
}

/* ═══════════════════════════════════════════
   DATA TABLES
═══════════════════════════════════════════ */
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 8px;
}
.data-table td {
    padding: 4px 4px 3px 4px;
    vertical-align: bottom;
    font-size: 10px;
}
.data-label {
    font-weight: bold;
    color: #1a2a6c;
    width: 22%;
    white-space: nowrap;
}
.data-value {
    color: #8b6400;
    border-bottom: 1px dashed #aab;
    width: 27%;
}

/* ═══════════════════════════════════════════
   CERTIFICATION TEXT
═══════════════════════════════════════════ */
.cert-text-box {
    border: 1px solid #d0d8f0;
    border-left: 4px solid #1a2a6c;
    background: #fafbff;
    padding: 8px 12px;
    font-size: 10px;
    line-height: 1.65;
    color: #222;
    margin: 8px 0;
}

/* ═══════════════════════════════════════════
   FOOTER SIGNATURES
═══════════════════════════════════════════ */
.footer-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
    border-top: 1px solid #d0d8f0;
    padding-top: 8px;
}
.footer-table td {
    width: 33.33%;
    text-align: center;
    vertical-align: bottom;
    padding: 6px 4px 4px 4px;
}
.footer-label {
    font-size: 8px;
    color: #888;
    margin-top: 3px;
    font-style: italic;
}
.footer-sig-line {
    border-top: 1px solid #1a2a6c;
    width: 90%;
    margin: 0 auto;
    padding-top: 4px;
    font-size: 9px;
    font-weight: bold;
    color: #1a2a6c;
}
.qr-box {
    width: 62px;
    height: 62px;
    border: 1px solid #ccc;
    margin: 0 auto;
    display: block;
    text-align: center;
    line-height: 62px;
    font-size: 8px;
    color: #aaa;
}
.seal-circle {
    width: 68px;
    height: 68px;
    border: 2px solid #1a2a6c;
    border-radius: 50%;
    margin: 0 auto;
    line-height: 68px;
    font-size: 8px;
    color: #1a2a6c;
    text-align: center;
    font-weight: bold;
}

/* ═══════════════════════════════════════════
   BOTTOM STRIP
═══════════════════════════════════════════ */
.bottom-strip {
    background: #1a2a6c;
    text-align: center;
    color: #c9a227;
    font-size: 7.5px;
    letter-spacing: 1.5px;
    padding: 3px 0;
    margin-top: 10px;
}

</style>
</head>
<body>

{{-- ══════════════════════════════════════════
     WATERMARK — faint school name background
══════════════════════════════════════════ --}}
<div class="watermark">GOVT. BOYS HIGHER SECONDARY SCHOOL DHILYAR</div>

{{-- ══════════════════════════════════════════
     OUTER DOUBLE BORDER FRAME
══════════════════════════════════════════ --}}
<div class="cert-frame">
<div class="cert-inner">

    {{-- ── HEADER ── --}}
    <table class="header-table">
        <tr>
            <td class="header-logo-cell">
                {{-- School logo if available --}}
                @if(!empty($schoolLogo))
                    <img src="{{ $schoolLogo }}" style="width:52px;height:52px;border-radius:50%;" alt="Logo">
                @else
                    <div class="header-logo">GBHSS</div>
                @endif
            </td>
            <td class="header-center">
                <div class="school-name">GOVERNMENT BOYS HIGHER SECONDARY SCHOOL</div>
                <div class="school-name" style="font-size:17px;">DHILYAR</div>
                <div class="school-address">
                    {{ $school->address ?? 'Dhilyar, Sindh, Pakistan' }}
                    &nbsp;|&nbsp;
                    Est. {{ $school->established_year ?? '' }}
                </div>
            </td>
            <td class="header-logo-cell">
                <div class="header-logo" style="font-size:7px; line-height:14px; padding-top:12px;">
                    NAIB<br>QASID<br>VERIFIED
                </div>
            </td>
        </tr>
    </table>

    {{-- ── HEADER DIVIDER ── --}}
    <div class="header-divider"></div>

    {{-- ── CERTIFICATE TITLE ── --}}
    <div class="cert-title-wrap">
        <div class="cert-title">SCHOOL LEAVING CERTIFICATE</div>
        <div class="cert-title-line"></div>
    </div>

    {{-- ── CERT NO + ISSUE DATE BAR ── --}}
    <table class="cert-meta-table">
        <tr>
            <td class="cert-meta-label">Certificate No:</td>
            <td class="cert-meta-value">{{ $certificate->certificate_number ?? 'PREVIEW-001' }}</td>
            <td style="width:30px;"></td>
            <td class="cert-meta-label">Issue Date:</td>
            <td class="cert-meta-value">{{ isset($certificate->issue_date) ? \Carbon\Carbon::parse($certificate->issue_date)->format('d-m-Y') : date('d-m-Y') }}</td>
        </tr>
    </table>

    {{-- ══════════════════════════
         STUDENT DETAILS SECTION
    ══════════════════════════ --}}
    <div class="section-header">&#9654; STUDENT DETAILS</div>
    <table class="data-table">
        <tr>
            <td class="data-label">Admission No:</td>
            <td class="data-value">{{ $student->admission_no ?? 'N/A' }}</td>
            <td class="data-label">Student Name:</td>
            <td class="data-value">{{ $student->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="data-label">Father Name:</td>
            <td class="data-value">{{ $student->father_name ?? 'N/A' }}</td>
            <td class="data-label">Date of Birth:</td>
            <td class="data-value">
                {{ isset($student->date_of_birth) ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td class="data-label">CNIC / B-Form:</td>
            <td class="data-value">{{ $student->cnic ?? 'N/A' }}</td>
            <td class="data-label">Religion:</td>
            <td class="data-value">{{ $student->religion ?? 'Islam' }}</td>
        </tr>
        <tr>
            <td class="data-label">Nationality:</td>
            <td class="data-value">{{ $student->nationality ?? 'Pakistani' }}</td>
            <td class="data-label">Gender:</td>
            <td class="data-value">{{ $student->gender ?? 'Male' }}</td>
        </tr>
    </table>

    {{-- ══════════════════════════
         ACADEMIC RECORD SECTION
    ══════════════════════════ --}}
    <div class="section-header-gold">&#9654; ACADEMIC RECORD</div>
    <table class="data-table">
        <tr>
            <td class="data-label">Date of Admission:</td>
            <td class="data-value">
                {{ isset($student->admission_date) ? \Carbon\Carbon::parse($student->admission_date)->format('d-m-Y') : 'N/A' }}
            </td>
            <td class="data-label">Class Admitted:</td>
            <td class="data-value">{{ $student->admission_class ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="data-label">Current Class:</td>
            <td class="data-value">{{ $student->currentClass->name ?? $student->class_name ?? 'N/A' }}</td>
            <td class="data-label">Section:</td>
            <td class="data-value">{{ $student->section->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="data-label">Academic Year:</td>
            <td class="data-value">{{ $academicYear->name ?? 'N/A' }}</td>
            <td class="data-label">Roll Number:</td>
            <td class="data-value">{{ $student->roll_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="data-label">Date of Leaving:</td>
            <td class="data-value">{{ isset($certificate->leaving_date) ? \Carbon\Carbon::parse($certificate->leaving_date)->format('d-m-Y') : date('d-m-Y') }}</td>
            <td class="data-label">Reason:</td>
            <td class="data-value">{{ $certificate->reason ?? 'General Purpose' }}</td>
        </tr>
    </table>

    {{-- ══════════════════════════
         CONDUCT / CHARACTER
    ══════════════════════════ --}}
    <div class="section-header">&#9654; CHARACTER &amp; CONDUCT</div>
    <table class="data-table" style="margin-bottom:0;">
        <tr>
            <td class="data-label">Character:</td>
            <td class="data-value">{{ $certificate->character ?? 'Good' }}</td>
            <td class="data-label">Conduct:</td>
            <td class="data-value">{{ $certificate->conduct ?? 'Good' }}</td>
        </tr>
        <tr>
            <td class="data-label">Dues Status:</td>
            <td class="data-value" colspan="3">No dues pending against the student</td>
        </tr>
    </table>

    {{-- ══════════════════════════
         CERTIFICATION TEXT
    ══════════════════════════ --}}
    <div class="cert-text-box">
        This is to certify that <strong>{{ $student->name ?? '___________' }}</strong>,
        Son/Daughter of <strong>{{ $student->father_name ?? '___________' }}</strong>,
        bearing Admission No. <strong>{{ $student->admission_no ?? '___________' }}</strong>,
        was a bonafide student of this institution. His/Her character and conduct were found
        to be <strong>{{ $certificate->character ?? 'Good' }}</strong> during his/her stay.
        No dues are pending against him/her. This certificate is issued on his/her request
        for <strong>{{ $certificate->reason ?? 'general purpose' }}</strong>.
    </div>

    {{-- ══════════════════════════
         FOOTER — QR / SEAL / SIGNATURE
    ══════════════════════════ --}}
    <table class="footer-table">
        <tr>
            {{-- QR Code --}}
            <td>
                @if(!empty($qrCode))
                    <img src="data:image/png;base64,{{ $qrCode }}"
                         style="width:62px;height:62px;display:block;margin:0 auto;" alt="QR">
                @else
                    <div class="qr-box">QR</div>
                @endif
                <div class="footer-label">Scan to Verify</div>
            </td>

            {{-- School Seal --}}
            <td>
                @if(!empty($schoolSeal))
                    <img src="{{ $schoolSeal }}"
                         style="width:68px;height:68px;border-radius:50%;display:block;margin:0 auto;" alt="Seal">
                @else
                    <div class="seal-circle">SCHOOL<br>SEAL</div>
                @endif
                <div class="footer-label">Official Seal</div>
            </td>

            {{-- Principal Signature --}}
            <td>
                <div style="height:42px;"></div>
                <div class="footer-sig-line">Principal's Signature &amp; Stamp</div>
                <div class="footer-label">{{ $school->name ?? 'GBHSS Dhilyar' }}</div>
            </td>
        </tr>
    </table>

    {{-- ── BOTTOM STRIP ── --}}
    <div class="bottom-strip">
        GOVERNMENT BOYS HIGHER SECONDARY SCHOOL DHILYAR &nbsp;·&nbsp;
        CERTIFICATE NO: {{ $certificate->certificate_number ?? 'N/A' }}
    </div>

</div>{{-- .cert-inner --}}
</div>{{-- .cert-frame --}}

</body>
</html>
```

---

## Variable Reference (controller se pass hone wale variables)

| Blade Variable | Source | Fallback |
|---|---|---|
| `$student->name` | `students` table | `'N/A'` |
| `$student->admission_no` | `students` table | `'N/A'` |
| `$student->father_name` | `students` table | `'N/A'` |
| `$student->date_of_birth` | `students` table | `'N/A'` |
| `$student->cnic` | `students` table | `'N/A'` |
| `$student->gender` | `students` table | `'Male'` |
| `$student->religion` | `students` table | `'Islam'` |
| `$student->nationality` | `students` table | `'Pakistani'` |
| `$student->admission_date` | `students` table | `'N/A'` |
| `$student->admission_class` | `students` table | `'N/A'` |
| `$student->currentClass->name` | relationship | `'N/A'` |
| `$student->section->name` | relationship | `'N/A'` |
| `$student->roll_number` | `students` table | `'N/A'` |
| `$certificate->certificate_number` | `transfer_certificates` table | auto |
| `$certificate->issue_date` | `transfer_certificates` table | today |
| `$certificate->leaving_date` | `transfer_certificates` table | today |
| `$certificate->reason` | `transfer_certificates` table | `'General Purpose'` |
| `$certificate->character` | `transfer_certificates` table | `'Good'` |
| `$certificate->conduct` | `transfer_certificates` table | `'Good'` |
| `$academicYear->name` | `academic_years` table | `'N/A'` |
| `$qrCode` | base64 PNG string | shows placeholder box |
| `$schoolLogo` | image path/URL | shows `GBHSS` text |
| `$schoolSeal` | image path/URL | shows circle placeholder |
| `$school->address` | `schools` table | default string |
| `$school->name` | `schools` table | `'GBHSS Dhilyar'` |

---

## Controller — Ensure These Are Passed to View

```php
// In your TransferCertificateController or DocumentController:

$data = [
    'student'      => $student->load(['currentClass', 'section']),
    'certificate'  => $certificate,
    'academicYear' => $academicYear,
    'school'       => $school,
    'qrCode'       => base64_encode(
                        QrCode::format('png')->size(120)->generate(
                            url('/verify-certificate/' . $certificate->certificate_number)
                        )
                      ),
    'schoolLogo'   => null,  // or: public_path('images/school-logo.png')
    'schoolSeal'   => null,  // or: public_path('images/school-seal.png')
];

$pdf = Pdf::loadView('certificates.transfer_certificate', $data)
    ->setPaper('a4', 'portrait')
    ->setOption('isHtml5ParserEnabled', true)
    ->setOption('isRemoteEnabled', false)
    ->setOption('dpi', 96);
```

---

## Issues Fixed in This Template

| Issue | Fix Applied |
|---|---|
| Blank first page | `@page margin: 10mm`, body `margin:0`, no min-height |
| 2 pages | All content sized for single A4, compact paddings |
| Right side cut off | `width:100%` everywhere, footer `33.33%×3` columns |
| Principal's Signature duplicate | Single occurrence in footer table cell only |
| Watermark too large/visible | `font-size:52px`, `opacity:0.035`, `z-index:0` |
| No outer border | Double frame: `3px solid #1a2a6c` + inner `1px solid #c9a227` |
| Basic design | Navy+gold color scheme, section headers, bottom strip, double border |
| flexbox issues in dompdf | 100% `<table>` based layout, no flexbox/grid anywhere |

