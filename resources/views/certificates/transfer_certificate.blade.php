<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>School Leaving Certificate — {{ $school->name ?? 'GBHSS Dhilyar' }}</title>
<style>
/* ─────────────────────────────────────────
   RESET & PAGE
───────────────────────────────────────── */
@page {
    size: A4 portrait;
    margin: 8mm;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: DejaVu Sans, Helvetica, sans-serif;
    font-size: 13px;
    color: #1a1a1e;
    background: #ffffff;
    margin: 0;
    padding: 0;
}

/* Typography Classes */
.font-serif { font-family: DejaVu Serif, Times, serif; }
.font-sans { font-family: DejaVu Sans, Helvetica, sans-serif; }

/* ─────────────────────────────────────────
   CERTIFICATE PAGE
───────────────────────────────────────── */
.cert-page {
    width: 100%;
    background: #fdfcf8;
    position: relative;
    border: 5px solid #1a2a6c;
}

.cert-inner-border {
    border: 1px solid #c9a227;
    margin: 8px;
    padding: 24px 30px 20px;
    position: relative;
}

/* ─────────────────────────────────────────
   CORNER ORNAMENTS
───────────────────────────────────────── */
.corner {
    position: absolute;
    width: 52px;
    height: 52px;
}
.corner-tl { top: 13px; left: 13px; }
.corner-tr { top: 13px; right: 13px; }
.corner-bl { bottom: 13px; left: 13px; }
.corner-br { bottom: 13px; right: 13px; }

/* ─────────────────────────────────────────
   WATERMARK
───────────────────────────────────────── */
.watermark {
    position: fixed;
    top: 42%;
    left: 0;
    width: 100%;
    text-align: center;
    transform: rotate(-28deg);
    font-size: 46px;
    font-weight: bold;
    color: #1a2a6c;
    opacity: 0.04;
    white-space: nowrap;
    z-index: -1;
    letter-spacing: 4px;
}

/* ─────────────────────────────────────────
   HEADER
───────────────────────────────────────── */
.hdr-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 2px;
}
.hdr-emblem-cell {
    width: 80px;
    text-align: center;
    vertical-align: middle;
}
.hdr-emblem {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 2.5px solid #1a2a6c;
    background: #eef1ff;
    margin: 0 auto;
}
.hdr-emblem-star {
    color: #c9a227;
    font-size: 18px;
    margin-top: 10px;
    line-height: 1;
}
.hdr-emblem-text {
    font-size: 7.5px;
    font-weight: bold;
    color: #1a2a6c;
    letter-spacing: 1px;
    line-height: 1.2;
    margin-top: 2px;
}

.hdr-center {
    text-align: center;
    vertical-align: middle;
}
.hdr-school-name {
    font-size: 18px;
    font-weight: bold;
    color: #1a2a6c;
    letter-spacing: 0.5px;
    line-height: 1.2;
}
.hdr-school-name-large {
    font-size: 21px;
    display: block;
}
.hdr-school-sub {
    font-size: 11.5px;
    color: #6b6b7a;
    margin-top: 3px;
    letter-spacing: 0.3px;
}

.hdr-right-emblem {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 2.5px solid #c9a227;
    background: #fdf6e3;
    margin: 0 auto;
}
.est-label {
    font-size: 7px;
    color: #b8910f;
    letter-spacing: 1.5px;
    font-weight: bold;
    margin-top: 14px;
}
.est-year {
    font-size: 15px;
    font-weight: bold;
    color: #1a2a6c;
    margin-top: 1px;
}
.est-label2 {
    font-size: 6.5px;
    color: #6b6b7a;
    letter-spacing: 1px;
    margin-top: 1px;
}

/* ─────────────────────────────────────────
   DIVIDER
───────────────────────────────────────── */
.divider-table {
    width: 100%;
    border-collapse: collapse;
    margin: 8px 0 6px;
}
.divider-line {
    border-bottom: 1.5px solid #1a2a6c;
    width: 48%;
}
.divider-diamond-cell {
    width: 4%;
    text-align: center;
    vertical-align: middle;
}
.divider-diamond {
    display: inline-block;
    width: 10px;
    height: 10px;
    background: #c9a227;
    transform: rotate(45deg);
}
.divider-line-thin {
    height: 1px;
    background: #d4c080;
    margin-bottom: 12px;
}

/* ─────────────────────────────────────────
   CERTIFICATE TITLE
───────────────────────────────────────── */
.cert-title-wrap {
    text-align: center;
    margin-bottom: 12px;
}
.cert-title-eyebrow {
    font-size: 9px;
    color: #b8910f;
    letter-spacing: 3px;
    font-weight: bold;
    text-transform: uppercase;
    margin-bottom: 4px;
}
.cert-title-main {
    font-size: 22px;
    font-weight: bold;
    color: #1a2a6c;
    letter-spacing: 4px;
    text-transform: uppercase;
    line-height: 1.1;
}

/* ─────────────────────────────────────────
   META BAR
───────────────────────────────────────── */
.meta-table {
    width: 100%;
    border-collapse: collapse;
    background: #eef1ff;
    border: 1px solid #c8d0ee;
    border-left: 4px solid #1a2a6c;
    margin-bottom: 14px;
}
.meta-table td {
    padding: 7px 14px;
    vertical-align: middle;
}
.meta-label {
    font-size: 10px;
    color: #6b6b7a;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}
.meta-value {
    font-size: 12px;
    font-weight: bold;
    color: #1a2a6c;
}
.meta-sep {
    border-right: 1px solid #c0c8e8;
    width: 1px;
    padding: 0;
}

/* ─────────────────────────────────────────
   SECTION HEADER
───────────────────────────────────────── */
.sec-head-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 8px;
    margin-top: 4px;
}
.sec-head-bar-navy {
    width: 4px;
    background: #1a2a6c;
}
.sec-head-bar-gold {
    width: 4px;
    background: #c9a227;
}
.sec-head-title {
    font-size: 9.5px;
    font-weight: bold;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    padding-left: 8px;
    padding-bottom: 2px;
    border-bottom: 1px solid #dde0f0;
}
.sec-navy .sec-head-title { color: #1a2a6c; border-bottom: 1px solid #c8d0f0; }
.sec-gold .sec-head-title { color: #b8910f; border-bottom: 1px solid #e0d090; }

.sec-block {
    margin-bottom: 12px;
}

/* ─────────────────────────────────────────
   DATA GRID
───────────────────────────────────────── */
.data-table {
    width: 100%;
    border-collapse: collapse;
}
.data-table td {
    padding: 5px 8px 4px;
    border-bottom: 1px solid #edf0f8;
    vertical-align: bottom;
}
.data-table tr:nth-child(odd) td {
    background: #f7f8fd;
}
.data-lbl {
    font-size: 9.5px;
    font-weight: bold;
    color: #3a3a45;
    white-space: nowrap;
    width: 18%;
}
.data-val {
    font-size: 12.5px;
    color: #1a2a6c;
    border-bottom: 1px dashed #b0b8d8;
    width: 32%;
}
.data-val-gold { color: #b8910f; font-weight: bold; }
.data-val-good { color: #1a7a3a; font-weight: bold; }

/* ─────────────────────────────────────────
   CERTIFICATION TEXT
───────────────────────────────────────── */
.cert-text-block {
    background: #fdf6e3;
    border: 1px solid #e0d090;
    border-left: 4px solid #c9a227;
    padding: 10px 14px;
    margin: 10px 0;
}
.cert-text-block p {
    font-size: 13px;
    line-height: 1.75;
    color: #1a1a1e;
    text-align: justify;
    margin: 0;
}
.cert-text-block p strong {
    font-weight: bold;
    color: #1a2a6c;
}

/* ─────────────────────────────────────────
   FOOTER SIGNATURES
───────────────────────────────────────── */
.footer-divider {
    border: 0;
    border-top: 1px solid #d0d4e8;
    margin: 12px 0 10px;
}
.footer-table {
    width: 100%;
    border-collapse: collapse;
}
.footer-table td {
    width: 33.33%;
    text-align: center;
    vertical-align: bottom;
    padding: 0 10px;
}
.qr-box {
    width: 64px;
    height: 64px;
    border: 1.5px solid #c0c8e8;
    margin: 0 auto 6px;
    background: #fff;
    padding: 2px;
}
.seal-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    border: 2px solid #1a2a6c;
    background: #eef1ff;
    margin: 0 auto 6px;
    text-align: center;
}
.seal-star { color: #c9a227; font-size: 14px; margin-top: 14px; line-height: 1;}
.seal-text {
    font-size: 7px;
    font-weight: bold;
    color: #1a2a6c;
    letter-spacing: 1px;
    line-height: 1.3;
}
.sig-space {
    height: 44px;
    width: 120px;
    border-bottom: 1px solid #1a2a6c;
    margin: 0 auto 6px;
}
.footer-caption {
    font-size: 8.5px;
    font-weight: bold;
    color: #1a2a6c;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.footer-sub {
    font-size: 7.5px;
    color: #6b6b7a;
    font-style: italic;
}

/* ─────────────────────────────────────────
   BOTTOM STRIP
───────────────────────────────────────── */
.bottom-strip {
    background: #1a2a6c;
    color: #c9a227;
    text-align: center;
    font-size: 8px;
    letter-spacing: 2px;
    padding: 5px 8px;
    margin-top: 10px;
    font-weight: bold;
}
</style>
</head>
<body>

<div class="cert-page" id="certificate">

  <!-- Corner ornaments -->
  <div class="corner corner-tl">
    <svg viewBox="0 0 52 52" fill="none">
      <path d="M2 2 L22 2 L22 6 L6 6 L6 22 L2 22 Z" fill="#1a2a6c" opacity="0.9"/>
      <path d="M4 4 L20 4 L20 8 L8 8 L8 20 L4 20 Z" fill="none" stroke="#c9a227" stroke-width="0.8"/>
      <circle cx="26" cy="26" r="4" fill="#c9a227" opacity="0.4"/>
      <circle cx="26" cy="26" r="2" fill="#1a2a6c" opacity="0.6"/>
    </svg>
  </div>
  <div class="corner corner-tr">
    <svg viewBox="0 0 52 52" fill="none">
      <path d="M50 2 L30 2 L30 6 L46 6 L46 22 L50 22 Z" fill="#1a2a6c" opacity="0.9"/>
      <path d="M48 4 L32 4 L32 8 L44 8 L44 20 L48 20 Z" fill="none" stroke="#c9a227" stroke-width="0.8"/>
      <circle cx="26" cy="26" r="4" fill="#c9a227" opacity="0.4"/>
      <circle cx="26" cy="26" r="2" fill="#1a2a6c" opacity="0.6"/>
    </svg>
  </div>
  <div class="corner corner-bl">
    <svg viewBox="0 0 52 52" fill="none">
      <path d="M2 50 L22 50 L22 46 L6 46 L6 30 L2 30 Z" fill="#1a2a6c" opacity="0.9"/>
      <path d="M4 48 L20 48 L20 44 L8 44 L8 32 L4 32 Z" fill="none" stroke="#c9a227" stroke-width="0.8"/>
      <circle cx="26" cy="26" r="4" fill="#c9a227" opacity="0.4"/>
      <circle cx="26" cy="26" r="2" fill="#1a2a6c" opacity="0.6"/>
    </svg>
  </div>
  <div class="corner corner-br">
    <svg viewBox="0 0 52 52" fill="none">
      <path d="M50 50 L30 50 L30 46 L46 46 L46 30 L50 30 Z" fill="#1a2a6c" opacity="0.9"/>
      <path d="M48 48 L32 48 L32 44 L44 44 L44 32 L48 32 Z" fill="none" stroke="#c9a227" stroke-width="0.8"/>
      <circle cx="26" cy="26" r="4" fill="#c9a227" opacity="0.4"/>
      <circle cx="26" cy="26" r="2" fill="#1a2a6c" opacity="0.6"/>
    </svg>
  </div>

  <!-- Watermark -->
  <div class="watermark font-serif">GOVT. BOYS HIGHER SECONDARY SCHOOL DHILYAR</div>

  <div class="cert-inner-border">

    <!-- ══════ HEADER ══════ -->
    <table class="hdr-table">
      <tr>
        <td class="hdr-emblem-cell">
            @if(!empty($schoolLogo))
                <img src="{{ $schoolLogo }}" style="width:70px;height:70px;border-radius:50%;border:2.5px solid #1a2a6c;" alt="Logo">
            @else
              <div class="hdr-emblem">
                <div class="hdr-emblem-star">★</div>
                <div class="hdr-emblem-text font-serif">GOVT<br>BOYS<br>HSS</div>
              </div>
            @endif
        </td>
        <td class="hdr-center">
          <div class="hdr-school-name font-serif">
            GOVERNMENT BOYS HIGHER SECONDARY
            <span class="hdr-school-name-large">SCHOOL DHILYAR</span>
          </div>
          <div class="hdr-school-sub font-serif">
            {{ $school->address ?? 'Dhilyar, Sindh, Pakistan' }} &nbsp;|&nbsp; Est. {{ $school->established_year ?? '1975' }}
          </div>
        </td>
        <td class="hdr-emblem-cell">
          <div class="hdr-right-emblem">
            <div class="est-label font-serif">EST.</div>
            <div class="est-year font-serif">{{ $school->established_year ?? '1975' }}</div>
            <div class="est-label2 font-sans">FOUNDED</div>
          </div>
        </td>
      </tr>
    </table>

    <!-- Decorative divider -->
    <table class="divider-table">
      <tr>
        <td class="divider-line"></td>
        <td class="divider-diamond-cell"><div class="divider-diamond"></div></td>
        <td class="divider-line"></td>
      </tr>
    </table>
    <div class="divider-line-thin"></div>

    <!-- ══════ TITLE ══════ -->
    <div class="cert-title-wrap">
      <div class="cert-title-eyebrow font-sans">Official Document</div>
      <div class="cert-title-main font-serif">School Leaving Certificate</div>
    </div>

    <!-- ══════ META BAR ══════ -->
    <table class="meta-table">
      <tr>
        <td>
          <span class="meta-label font-sans">Certificate No: </span>
          <span class="meta-value font-serif">{{ $certificate->certificate_number ?? 'PREVIEW-001' }}</span>
        </td>
        <td class="meta-sep"></td>
        <td style="padding-left:14px;">
          <span class="meta-label font-sans">Issue Date: </span>
          <span class="meta-value font-serif">{{ isset($certificate->issue_date) ? \Carbon\Carbon::parse($certificate->issue_date)->format('d-m-Y') : date('d-m-Y') }}</span>
        </td>
      </tr>
    </table>

    <!-- ══════ STUDENT DETAILS ══════ -->
    <div class="sec-block sec-navy">
      <table class="sec-head-table">
        <tr>
          <td class="sec-head-bar-navy"></td>
          <td class="sec-head-title font-sans">Student Details</td>
        </tr>
      </table>
      
      <table class="data-table">
        <tr>
          <td class="data-lbl font-sans">Admission No</td>
          <td class="data-val font-serif data-val-gold">{{ $student->admission_no ?? 'N/A' }}</td>
          <td class="data-lbl font-sans">Student Name</td>
          <td class="data-val font-serif">{{ $student->name ?? 'N/A' }}</td>
        </tr>
        <tr>
          <td class="data-lbl font-sans">Father's Name</td>
          <td class="data-val font-serif">{{ $student->father_name ?? 'N/A' }}</td>
          <td class="data-lbl font-sans">Date of Birth</td>
          <td class="data-val font-serif">{{ isset($student->date_of_birth) ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') : 'N/A' }}</td>
        </tr>
        <tr>
          <td class="data-lbl font-sans">CNIC / B-Form</td>
          <td class="data-val font-serif">{{ $student->cnic ?? 'N/A' }}</td>
          <td class="data-lbl font-sans">Gender</td>
          <td class="data-val font-serif">{{ $student->gender ?? 'Male' }}</td>
        </tr>
        <tr>
          <td class="data-lbl font-sans">Religion</td>
          <td class="data-val font-serif">{{ $student->religion ?? 'Islam' }}</td>
          <td class="data-lbl font-sans">Nationality</td>
          <td class="data-val font-serif">{{ $student->nationality ?? 'Pakistani' }}</td>
        </tr>
      </table>
    </div>

    <!-- ══════ ACADEMIC RECORD ══════ -->
    <div class="sec-block sec-gold">
      <table class="sec-head-table">
        <tr>
          <td class="sec-head-bar-gold"></td>
          <td class="sec-head-title font-sans">Academic Record</td>
        </tr>
      </table>
      
      <table class="data-table">
        <tr>
          <td class="data-lbl font-sans">Date of Admission</td>
          <td class="data-val font-serif">{{ isset($student->admission_date) ? \Carbon\Carbon::parse($student->admission_date)->format('d-m-Y') : 'N/A' }}</td>
          <td class="data-lbl font-sans">Class Admitted</td>
          <td class="data-val font-serif">{{ $student->admission_class ?? 'N/A' }}</td>
        </tr>
        <tr>
          <td class="data-lbl font-sans">Current Class</td>
          <td class="data-val font-serif">{{ $student->currentClass->name ?? $student->class_name ?? 'N/A' }}</td>
          <td class="data-lbl font-sans">Section</td>
          <td class="data-val font-serif">{{ $student->section->name ?? 'N/A' }}</td>
        </tr>
        <tr>
          <td class="data-lbl font-sans">Roll Number</td>
          <td class="data-val font-serif">{{ $student->roll_number ?? 'N/A' }}</td>
          <td class="data-lbl font-sans">Academic Year</td>
          <td class="data-val font-serif">{{ $academicYear->name ?? 'N/A' }}</td>
        </tr>
        <tr>
          <td class="data-lbl font-sans">Date of Leaving</td>
          <td class="data-val font-serif">{{ isset($certificate->leaving_date) ? \Carbon\Carbon::parse($certificate->leaving_date)->format('d-m-Y') : date('d-m-Y') }}</td>
          <td class="data-lbl font-sans">Reason for Leaving</td>
          <td class="data-val font-serif">{{ $certificate->reason ?? 'School Transfer' }}</td>
        </tr>
      </table>
    </div>

    <!-- ══════ CHARACTER & CONDUCT ══════ -->
    <div class="sec-block sec-navy">
      <table class="sec-head-table">
        <tr>
          <td class="sec-head-bar-navy"></td>
          <td class="sec-head-title font-sans">Character &amp; Conduct</td>
        </tr>
      </table>
      
      <table class="data-table">
        <tr>
          <td class="data-lbl font-sans">Character</td>
          <td class="data-val font-serif data-val-good">{{ $certificate->character ?? 'Excellent' }}</td>
          <td class="data-lbl font-sans">Conduct</td>
          <td class="data-val font-serif data-val-good">{{ $certificate->conduct ?? 'Good' }}</td>
        </tr>
        <tr>
          <td class="data-lbl font-sans">Dues Clearance</td>
          <td class="data-val font-serif data-val-good" colspan="3">✓ &nbsp;No dues pending against the student</td>
        </tr>
      </table>
    </div>

    <!-- ══════ CERTIFICATION TEXT ══════ -->
    <div class="cert-text-block">
      <p class="font-serif">
        This is to certify that <strong>{{ $student->name ?? '___________' }}</strong>, Son/Daughter of <strong>{{ $student->father_name ?? '___________' }}</strong>,
        bearing Admission No. <strong>{{ $student->admission_no ?? '___________' }}</strong>, was a bonafide student of this institution. His/Her character and conduct
        were found to be <strong>{{ $certificate->character ?? 'Excellent' }}</strong> throughout his/her stay at this school. No dues are
        pending against him/her. This certificate is issued on his/her request for the purpose of
        <strong>{{ $certificate->reason ?? 'school transfer' }}</strong> and is correct to the best of our knowledge and records.
      </p>
    </div>

    <!-- ══════ FOOTER ══════ -->
    <hr class="footer-divider">
    
    <table class="footer-table">
      <tr>
        <!-- QR Code -->
        <td>
          <div class="qr-box">
             @if(!empty($qrCode))
                 <img src="data:image/png;base64,{{ $qrCode }}" style="width:100%;height:100%;display:block;" alt="QR">
             @else
                 <div style="font-size:10px;color:#aaa;line-height:60px;">QR CODE</div>
             @endif
          </div>
          <div class="footer-caption font-sans">Scan to Verify</div>
          <div class="footer-sub font-sans">scan.gbhss.edu.pk</div>
        </td>

        <!-- School Seal -->
        <td>
          @if(!empty($schoolSeal))
             <img src="{{ $schoolSeal }}" style="width:72px;height:72px;border-radius:50%;border:2px solid #1a2a6c;margin:0 auto 6px;display:block;" alt="Seal">
          @else
            <div class="seal-circle">
              <div class="seal-star">★</div>
              <div class="seal-text font-serif">OFFICIAL<br>SCHOOL<br>SEAL</div>
            </div>
          @endif
          <div class="footer-caption font-sans">Official Seal</div>
          <div class="footer-sub font-sans">GBHSS Dhilyar</div>
        </td>

        <!-- Principal Signature -->
        <td>
          <div class="sig-space"></div>
          <div class="footer-caption font-sans">Principal's Signature &amp; Stamp</div>
          <div class="footer-sub font-sans">Govt. Boys HSS Dhilyar</div>
        </td>
      </tr>
    </table>

    <!-- Bottom strip -->
    <div class="bottom-strip font-serif">
      GOVERNMENT BOYS HIGHER SECONDARY SCHOOL DHILYAR &nbsp;·&nbsp;
      CERT NO: {{ $certificate->certificate_number ?? 'N/A' }} &nbsp;·&nbsp; ISSUED: {{ isset($certificate->issue_date) ? \Carbon\Carbon::parse($certificate->issue_date)->format('d-m-Y') : date('d-m-Y') }}
    </div>

  </div><!-- .cert-inner-border -->
</div><!-- .cert-page -->

</body>
</html>
