# Fix Transfer Certificate PDF Layout — NewMkhanSchool Laravel Project

## Context

**Project:** NewMkhanSchool (Laravel 11, PHP 8.2, MariaDB, dompdf via barryvdh/laravel-dompdf)  
**File to fix:** The Blade template that generates the Transfer/School Leaving Certificate PDF  
**Preview URL:** `http://127.0.0.1:8000/admin/advanced/documents/preview`  
**Download filename pattern:** `transfer-certificate_{ADM_NO}_{CERT_NO}.pdf`

---

## Detected Issues (from visual PDF inspection)

| # | Issue | Root Cause |
|---|-------|-----------|
| 1 | **Page 1 is completely blank** | An empty container or stray `<div>` with height before actual content |
| 2 | **Content pushed to Page 2** | Outer wrapper has excessive padding/margin/min-height, or `margin-top` on body |
| 3 | **Right side cut off** | Fixed pixel widths (e.g. `width: 800px`) wider than A4 printable area |
| 4 | **Footer 3-column row overflows** | QR Code + School Seal + Principal's Signature row uses `display:flex` or `float` without proper width constraints |
| 5 | **"Principal's Signature & St..." truncated** | Right column text goes beyond right margin |
| 6 | **Top/right border missing on page 2** | Border defined on outer `div` — dompdf does not repeat `div` borders across page breaks |
| 7 | **Watermark overlapping foreground content** | `position: absolute` watermark `z-index` too high OR not set, covering form fields |
| 8 | **Certificate should be single page** | All content must fit on one A4 page |

---

## Fix Requirements

### 1. PDF Page Setup (CRITICAL)

Add this at the very top of the Blade template inside a `<style>` block:

```css
@page {
    size: A4 portrait;
    margin: 12mm 15mm 12mm 15mm;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    margin: 0;
    padding: 0;
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    color: #1a2a4a;
    background: #ffffff;
}
```

> **Note:** dompdf default font must be `DejaVu Sans` — do NOT use Google Fonts or web fonts; they will not load in dompdf.

---

### 2. Fix Blank Page 1

**Find and remove** any of these patterns at the top of the Blade file:

```html
<!-- REMOVE any of these patterns: -->
<div style="min-height: 297mm">...</div>
<div style="height: 100vh">...</div>
<div style="page-break-after: always"></div>
<br><br><br>  <!-- excessive line breaks -->
<div style="margin-top: 200px">
```

The main certificate wrapper must start with `margin: 0; padding: 0` and no height/min-height set.

---

### 3. Fix Width Overflow (Right Side Cut Off)

Replace any fixed pixel widths with percentage-based widths:

```css
/* WRONG — causes right-side overflow in dompdf */
.certificate-wrapper { width: 800px; }
.certificate-wrapper { width: 210mm; }  /* also wrong — page margin not accounted for */

/* CORRECT */
.certificate-wrapper {
    width: 100%;
    max-width: 100%;
}
```

Also fix the **border** — since a `div` border does not repeat across pages in dompdf, use a table-based border OR ensure single-page fit:

```css
.certificate-wrapper {
    width: 100%;
    border: 3px solid #1a3a6b;
    padding: 12px;
}
```

---

### 4. Fix Header Section

The header must be a simple centered block, not flexbox (dompdf has limited flex support):

```html
<div style="text-align: center; border-bottom: 2px solid #c9a227; padding-bottom: 8px; margin-bottom: 10px;">
    <h1 style="font-size: 16px; font-weight: bold; color: #1a2a6c; margin: 0; line-height: 1.3;">
        GOVERNMENT BOYS HIGHER SECONDARY SCHOOL DHILYAR
    </h1>
    <h2 style="font-size: 13px; letter-spacing: 2px; color: #c9a227; margin: 6px 0 0 0;">
        SCHOOL LEAVING CERTIFICATE
    </h2>
    <div style="width: 60px; height: 3px; background: #c9a227; margin: 6px auto 0 auto;"></div>
</div>
```

---

### 5. Fix Student Details & Academic Record Tables

Use HTML `<table>` (NOT CSS grid or flexbox) for form fields — dompdf renders tables reliably:

```html
<!-- Section Header -->
<div style="background: #1a2a6c; color: #fff; font-weight: bold; font-size: 10px;
            padding: 4px 8px; margin-bottom: 6px;">
    Student Details
</div>

<!-- Data Table -->
<table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px;">
    <tr>
        <td style="width: 22%; font-weight: bold; padding: 4px 0; color: #1a2a6c;">Certificate No:</td>
        <td style="width: 28%; color: #8b6914; border-bottom: 1px dashed #ccc; padding: 4px 4px 4px 2px;">
            {{ $certificate->certificate_number ?? 'N/A' }}
        </td>
        <td style="width: 22%; font-weight: bold; padding: 4px 0; color: #1a2a6c;">Issue Date:</td>
        <td style="width: 28%; color: #8b6914; border-bottom: 1px dashed #ccc; padding: 4px 4px 4px 2px;">
            {{ $certificate->issue_date ?? 'N/A' }}
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 4px 0; color: #1a2a6c;">Admission No:</td>
        <td style="color: #8b6914; border-bottom: 1px dashed #ccc; padding: 4px 4px 4px 2px;">
            {{ $student->admission_no ?? 'N/A' }}
        </td>
        <td style="font-weight: bold; padding: 4px 0; color: #1a2a6c;">Student Name:</td>
        <td style="color: #8b6914; border-bottom: 1px dashed #ccc; padding: 4px 4px 4px 2px;">
            {{ $student->name ?? 'N/A' }}
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 4px 0; color: #1a2a6c;">Father Name:</td>
        <td style="color: #8b6914; border-bottom: 1px dashed #ccc; padding: 4px 4px 4px 2px;">
            {{ $student->father_name ?? 'N/A' }}
        </td>
        <td style="font-weight: bold; padding: 4px 0; color: #1a2a6c;">Date of Birth:</td>
        <td style="color: #8b6914; border-bottom: 1px dashed #ccc; padding: 4px 4px 4px 2px;">
            {{ $student->date_of_birth ?? 'N/A' }}
        </td>
    </tr>
</table>
```

Repeat the same pattern for the **Academic Record** section.

---

### 6. Fix Certification Text Block

```html
<div style="border-left: 3px solid #e0e0e0; padding: 8px 10px; margin: 10px 0;
            font-size: 10px; line-height: 1.6; color: #333;">
    This is to certify that the above mentioned student was a bonafide student of this institution.
    His/Her character and conduct were found to be <strong>Good</strong> during his/her stay.
    No dues are pending against him/her.
</div>
```

---

### 7. Fix Footer (QR Code + School Seal + Principal's Signature) — CRITICAL

This is the primary cause of right-side overflow. Use a `<table>` with fixed column widths that add up to exactly 100%:

```html
<table style="width: 100%; margin-top: 15px; border-collapse: collapse;">
    <tr>
        <!-- QR Code column -->
        <td style="width: 25%; text-align: center; vertical-align: bottom; padding: 0 5px;">
            @if(!empty($qrCode))
                <img src="data:image/png;base64,{{ $qrCode }}" 
                     style="width: 70px; height: 70px;" alt="QR Code">
            @else
                <div style="width: 70px; height: 70px; border: 1px solid #ccc;
                            margin: 0 auto; line-height: 70px; text-align: center;
                            font-size: 9px; color: #999;">QR Code</div>
            @endif
            <div style="font-size: 8px; color: #666; margin-top: 3px;">Scan to Verify</div>
        </td>

        <!-- School Seal column -->
        <td style="width: 50%; text-align: center; vertical-align: bottom; padding: 0 5px;">
            <div style="width: 80px; height: 80px; border: 2px solid #1a2a6c;
                        border-radius: 50%; margin: 0 auto;
                        line-height: 80px; font-size: 9px; color: #999;">
                School Seal
            </div>
        </td>

        <!-- Principal's Signature column -->
        <td style="width: 25%; text-align: center; vertical-align: bottom; padding: 0 5px;">
            <div style="border-top: 1px solid #333; padding-top: 4px; font-size: 9px;
                        font-weight: bold; color: #1a2a6c; white-space: nowrap;">
                Principal's Signature & Stamp
            </div>
        </td>
    </tr>
</table>
```

> **Key fix:** Three columns = 25% + 50% + 25% = 100%. No overflow possible.

---

### 8. Fix Watermark

Watermark must be `position: fixed` with very low opacity and proper `z-index`:

```css
.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 48px;
    font-weight: bold;
    color: rgba(26, 42, 108, 0.06);  /* very low opacity */
    z-index: -1;                      /* behind all content */
    white-space: nowrap;
    pointer-events: none;
    width: 100%;
    text-align: center;
}
```

In dompdf, `position: fixed` elements repeat on every page — for single-page this is fine.

---

### 9. Complete Template Structure (Single Page)

The final Blade template must follow this exact structure:

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* ... all CSS here ... */
    </style>
</head>
<body>
    <!-- Watermark (behind everything) -->
    <div class="watermark">GOVERNMENT BOYS HIGHER SECONDARY SCHOOL DHILYAR</div>

    <!-- Single outer wrapper — NO min-height, NO fixed height -->
    <div style="width: 100%; border: 3px solid #1a3a6b; padding: 15px; position: relative;">

        <!-- 1. Header -->
        <!-- 2. Student Details Table -->
        <!-- 3. Academic Record Table -->
        <!-- 4. Certification Text -->
        <!-- 5. Footer Table (QR + Seal + Signature) -->

    </div>
</body>
</html>
```

---

### 10. dompdf Configuration in Laravel (if not already set)

In `config/dompdf.php` or wherever dompdf is configured:

```php
'options' => [
    'font_height_ratio'      => 1.1,
    'isPhpEnabled'           => false,
    'isRemoteEnabled'        => false,
    'isHtml5ParserEnabled'   => true,
    'isFontSubsettingEnabled'=> true,
    'defaultMediaType'       => 'print',
    'defaultPaperSize'       => 'a4',
    'defaultPaperOrientation'=> 'portrait',
    'dpi'                    => 96,
    'chroot'                 => realpath(base_path()),
],
```

In the controller method that generates the PDF:

```php
$pdf = Pdf::loadView('certificates.transfer_certificate', $data)
    ->setPaper('a4', 'portrait')
    ->setOption('isHtml5ParserEnabled', true)
    ->setOption('isRemoteEnabled', false);

return $pdf->download('transfer-certificate_' . $admNo . '_' . $certNo . '.pdf');
```

---

## Testing Checklist After Fix

- [ ] PDF is exactly 1 page (no blank page 1, no overflow to page 2)
- [ ] All 4 borders visible (left, right, top, bottom)
- [ ] Footer shows: QR Code | School Seal | Principal's Signature & Stamp — all 3 fully visible
- [ ] Right edge not cut off at any element
- [ ] Watermark is faint and does not obscure text
- [ ] Student data fields display correctly with Blade variables
- [ ] Certificate looks professional when printed on A4

---

## Files to Modify

1. **Blade template** — likely at: `resources/views/certificates/transfer_certificate.blade.php`  
   (or similar path — check the controller that handles the preview URL)
2. **Controller** — ensure `->setPaper('a4', 'portrait')` is set
3. **dompdf config** — `config/dompdf.php` — ensure `defaultPaperSize` is `a4`
