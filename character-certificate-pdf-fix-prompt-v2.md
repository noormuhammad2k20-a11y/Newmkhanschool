# Character Certificate — PDF 2-Page Bug Fix Prompt (v2)

## Exact Problem (Deeply Analyzed)

Do PDFs ko PyMuPDF se byte-level scan kiya gaya. Yeh findings hain:

### Page 1 (Blank dikhne wala page):
- **Text blocks: 0** — koi bhi text nahi
- **Drawing objects: 14–15** — sirf decorative borders/lines/boxes
- **Images: 2** — logo placeholder aur signature image
- **Conclusion:** Page 1 sirf outer decorative wrapper/frame render ho raha hai — koi content nahi

### Page 2 (Sara content yahan):
- **Text blocks: 14–19** — pura certificate text y=78 se shuru hota hai (page ke top ke qareeb)
- **Drawing objects: 62** — borders + field underlines
- **Seal/Stamp position: y=819–842** on a 841.9pt tall page — seal bilkul page ke last 22pts mein hai, almost cut off

### Real Root Cause:

> **Blade template mein outer decorative container (border + logo + header box) ka height poori ek page consume kar raha hai, aur text content use se bahar push ho kar page 2 par aa raha hai.**

Yeh 3 mein se ek cheez ki wajah se ho raha hai:

**Cause A** — Template mein `page-break-after: always` explicitly laga hua hai header div par:
```html
<div style="page-break-after: always;">
    <!-- header / border / logo -->
</div>
<!-- content yahan page 2 par jaata hai -->
```

**Cause B** — Outer container ki `height` ya `min-height` poori page height ke barabar set hai:
```css
.outer-wrapper {
    height: 100vh;        /* ← yeh poori page le leta hai */
    /* ya */
    min-height: 297mm;    /* ← overflow → page 2 */
}
```

**Cause C** — CSS flexbox ya table layout jahan `header` section `flex: 1` ya `height: 100%` se full height le raha hai

---

## Fix — Step by Step

### Step 1: Template File Kholo

File likely hai:
```
resources/views/certificates/character-certificate.blade.php
```

### Step 2: `page-break` Check Karo aur Hatao

Search karo in strings ke liye aur **remove** karo:

```html
<!-- HATAO yeh sab -->
style="page-break-after: always"
style="page-break-before: always"
class="page-break"
```

```css
/* HATAO CSS mein bhi */
page-break-after: always;
page-break-before: always;
break-after: page;
```

### Step 3: Outer Container Ki Height Fix Karo

**Galat (in mein se koi bhi):**
```css
.certificate-page, .outer-wrapper, body, html {
    height: 100vh;
    min-height: 297mm;
    height: 100%;
}
```

**Sahi — poori file ek hi div mein, fixed height:**
```css
@page {
    margin: 10mm 15mm;
    size: A4 portrait;
}

html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    font-size: 13px;
}

.certificate-wrapper {
    width: 100%;
    /* height fixed mat karo — DomPDF khud manage karega */
    /* overflow: hidden bhi mat lagao — content clip ho jaata hai */
    page-break-inside: avoid;
}
```

### Step 4: Template Structure Yeh Honi Chahiye (Ek Div, Ek Page)

```html
<!DOCTYPE html>
<html>
<head>
<style>
    @page {
        margin: 10mm 15mm;
        size: A4 portrait;
    }
    html, body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        font-size: 13px;
    }
    .certificate-wrapper {
        /* KUCH HEIGHT MAT LAGAO */
        page-break-inside: avoid;
    }
    /* ... baaki styles ... */
</style>
</head>
<body>

<div class="certificate-wrapper">
    <!-- Header section -->
    <div class="cert-header">
        <!-- logo, school name, cert no, date -->
    </div>

    <!-- Divider line -->
    <hr>

    <!-- Title -->
    <h2>Character Certificate</h2>

    <!-- Body content -->
    <div class="cert-body">
        <p>This is to officially certify...</p>
        <p>He/She successfully completed...</p>
        <p>During his/her tenure...</p>
        <p>To the best of our knowledge...</p>
    </div>

    <!-- Seal & Signature — FIXED position mat use karo -->
    <div class="cert-footer">
        <img src="..." alt="Official Seal">
        <img src="..." alt="Signature">
    </div>
</div>

</body>
</html>
```

> ⚠️ **IMPORTANT:** `position: fixed` ya `position: absolute` seal/signature ke liye mat use karo DomPDF mein. Yeh elements ko page ke bahar place kar deta hai ya page 1 par render karta hai alag se. Normal document flow mein rakho.

### Step 5: Seal/Stamp Position Fix

Scan se pata chala seal y=819–842 par hai (page end = 841.9pts) — yeh almost cut off ho raha hai.

**Galat:**
```css
.seal-stamp {
    position: fixed;
    bottom: 10px;    /* ← DomPDF mein fixed = page 1 ya cut-off */
}
```

**Sahi:**
```css
.seal-stamp {
    margin-top: 40px;    /* content ke baad normal flow mein */
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}
```

### Step 6: DomPDF Controller Settings

```php
$pdf = PDF::loadView('certificates.character-certificate', $data);
$pdf->setPaper('A4', 'portrait');
$pdf->setOptions([
    'defaultFont'         => 'DejaVu Sans',
    'isHtml5ParserEnabled'=> true,
    'isPhpEnabled'        => false,
    'dpi'                 => 150,
    'defaultPaperSize'    => 'A4',
    // In mat lagao:
    // 'isRemoteEnabled' => true  (security risk)
]);
return $pdf->download('character-certificate.pdf');
```

---

## Summary Table

| Problem | Galat Code | Sahi Fix |
|---------|-----------|----------|
| Page 1 blank, content page 2 par | `page-break-after: always` header par | Yeh line hatao |
| Blank page ban raha hai | `height: 100vh` ya `min-height: 297mm` | Height khatam karo wrapper se |
| Seal cut off | `position: fixed; bottom: 0` | Normal flow, `margin-top` use karo |
| 2 pages generate | Flexbox/table full height le raha | Single div, no fixed height |

---

## Verification

Fix karne ke baad:
1. PDF download karo
2. **Sirf 1 page** hona chahiye
3. Content y≈78 se shuru, seal page ke andar (y < 800) khatam
4. Koi blank page nahi

---

## Reference Files

| File | GR No | Pages (Bug) | Page 1 Text Blocks | Page 2 Text Blocks |
|------|-------|------------|-------------------|-------------------|
| CH-20260616-00004.pdf | ADM0018 | 2 | **0** (blank) | 19 |
| CH-20260616-00005.pdf | ADM0003 | 2 | **0** (blank) | 14 |

Dono mein same bug — page 1 sirf decorative frame, page 2 sara content.
