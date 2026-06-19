{{-- Transfer Certificate - Dynamic Blade Template --}}
@php
    $logoBase64 = '';
    $logoPath = public_path('images/certificate-logo.png');
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
    
    $stampBase64 = '';
    $stampPath = public_path('images/authentic_document_seal_transparent_background.png');
    if (file_exists($stampPath)) {
        $stampBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($stampPath));
    }
@endphp
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
<style>
    /* ── RESET & PAGE SETUP ── */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
        --royal-blue: #0A1931;
        --rich-gold: #C5A059;
        --text-main: #222222;
        --paper-white: #ffffff;
        --bg-color: #525659;
    }

    body {
        background-color: var(--paper-white);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        font-family: 'Montserrat', sans-serif;
        color: var(--text-main);
        padding: 0;
    }

    /* ── A4 PAGE CONTAINER ── */
    .a4-page {
        width: 210mm;
        background-color: var(--paper-white);
        position: relative;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
    }

    /* ── PRINT RULES ── */
    @media print {
        @page { size: A4 portrait; margin: 0; }
        body { background-color: transparent; margin: 0; padding: 0; display: block; }
        .a4-page { margin: 0; box-shadow: none; width: 210mm; page-break-after: avoid; }
    }

    /* ── ELEGANT DOUBLE FRAME ── */
    .frame-outer {
        position: absolute;
        top: 12mm; left: 12mm; right: 12mm; bottom: 8mm;
        border: 4px solid var(--royal-blue);
        z-index: 1;
        pointer-events: none;
    }
    .frame-inner {
        position: absolute;
        top: calc(12mm + 6px); left: calc(12mm + 6px); right: calc(12mm + 6px); bottom: calc(8mm + 6px);
        border: 1px solid var(--rich-gold);
        z-index: 1;
        pointer-events: none;
    }

    /* ── CORNER BRACKETS ── */
    .corner {
        position: absolute;
        width: 30px; height: 30px;
        z-index: 2;
    }
    .top-left     { top: -2px; left: -2px; border-top: 5px solid var(--rich-gold); border-left: 5px solid var(--rich-gold); }
    .top-right    { top: -2px; right: -2px; border-top: 5px solid var(--rich-gold); border-right: 5px solid var(--rich-gold); }
    .bottom-left  { bottom: -2px; left: -2px; border-bottom: 5px solid var(--rich-gold); border-left: 5px solid var(--rich-gold); }
    .bottom-right { bottom: -2px; right: -2px; border-bottom: 5px solid var(--rich-gold); border-right: 5px solid var(--rich-gold); }

    /* ── WATERMARK ── */
    .watermark {
        position: absolute;
        top: 50%; left: 50%;
        width: 450px; height: auto; max-height: 450px; object-fit: contain;
        transform: translate(-50%, -50%);
        opacity: 0.12;
        z-index: 0;
        pointer-events: none;
    }

    /* ── CONTENT CONTAINER ── */
    .content-container {
        position: relative;
        padding: 16mm 22mm 75mm 22mm;
        z-index: 5;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* ── HEADER SECTION ── */
    .header-section {
        text-align: center;
        position: relative;
        margin-bottom: 12px;
    }

    .meta-tags {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        font-weight: 700;
        color: var(--royal-blue);
        letter-spacing: 1px;
        margin-bottom: 2px;
    }

    .header-logo {
        width: 120px; height: auto; max-height: 120px; object-fit: contain;
        margin-bottom: 10px;
    }

    .school-title {
        font-family: 'Cinzel', serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--royal-blue);
        margin: 0;
        line-height: 1.3;
        text-transform: uppercase;
        padding: 0 10px;
        word-wrap: break-word;
    }

    .school-address {
        font-size: 11px;
        font-weight: 700;
        color: #555;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin: 4px 0 10px 0;
    }

    .certificate-title-wrapper { text-align: center; margin-bottom: 4px; }

    .certificate-title {
        display: inline-block;
        background-color: var(--royal-blue);
        color: #fff;
        font-family: 'Cinzel', serif;
        font-size: 20px;
        font-weight: 700;
        padding: 8px 40px;
        border: 2px solid var(--rich-gold);
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    /* ── FORM SECTIONS ── */
    .form-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px; /* Compressed spacing to fit one page */
    }

    .form-table td {
        vertical-align: bottom;
    }

    .label-cell {
        font-size: 11px;
        font-weight: 700;
        color: var(--royal-blue);
        white-space: nowrap;
        width: 1%;
        padding-right: 10px;
    }

    .label-num {
        color: var(--rich-gold);
        margin-right: 5px;
    }

    .value-cell {
        font-size: 13px;
        font-weight: 600;
        color: #111;
        border-bottom: 1px dotted #888;
        padding-bottom: 1px;
        padding-left: 5px;
        width: auto;
    }

    .footer-area {
        position: absolute;
        bottom: 25mm;
        left: 20mm;
        right: 20mm;
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        width: auto;
    }

    .signature-block {
        text-align: center;
        width: 35%;
        position: relative;
    }

    .sign-line {
        border-top: 1px solid var(--royal-blue);
        margin-bottom: 4px;
        width: 100%;
    }

    .sign-text {
        font-family: 'Cinzel', serif;
        font-weight: 700;
        font-size: 11px;
        color: var(--royal-blue);
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* Physical Stamp Placeholder CSS */
    .seal-placeholder {
        padding: 4px;
        border: 2px solid var(--royal-blue);
        border-radius: 8px;
        background: rgba(197, 160, 89, 0.03);
        display: inline-block;
        box-shadow: 0 0 0 1px var(--rich-gold);
        width: 180px; 
        font-family: 'Montserrat', system-ui, -apple-system, sans-serif;
        user-select: none;
        margin: 0 auto;
        transform: rotate(-2deg);
    }

    .seal-placeholder .inner-wrapper {
        border: 1.5px dashed var(--royal-blue);
        border-radius: 4px;
        padding: 8px;
        text-align: center;
        min-height: 85px; 
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Light Placeholder Text for Stamp Area */
    .seal-placeholder .stamp-indicator {
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 600;
        color: rgba(10, 25, 49, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .seal-placeholder .school-name {
        font-size: 0.7rem; 
        font-weight: 700;
        letter-spacing: 0.03em;
        color: var(--royal-blue); 
        text-transform: uppercase;
        border-top: 1px solid rgba(197, 160, 89, 0.3);
        padding-top: 6px;
        margin-top: 6px;
        line-height: 1.2; 
    }

    /* Generated Fake Signatures using Dancing Script Font */
    .fake-signature {
        font-family: 'Dancing Script', cursive;
        font-size: 24px;
        color: #000080;
        position: absolute;
        bottom: 20px; 
        width: 100%;
        text-align: center;
        transform: rotate(-5deg); 
        opacity: 0.8;
        pointer-events: none;
    }
    .fake-signature.sig2 {
        font-size: 26px;
        transform: rotate(2deg);
        color: #1a1a1a;
    }

    /* ── Canvas Watermark Container ── */
    .text-watermark-pattern {
        position: absolute;
        top: 12mm; left: 12mm; right: 12mm; bottom: 8mm;
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .text-watermark-pattern canvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
    }

</style>

<!-- Certificate Box -->
<div class="a4-page">

    <!-- ABSOLUTE FRAMES -->
    
    {{-- ── Canvas-based diagonal tiled watermark ── --}}
    <div class="text-watermark-pattern">
        <canvas id="wm-canvas"></canvas>
    </div>

    <div class="frame-outer">
        <div class="corner top-left"></div>
        <div class="corner top-right"></div>
        <div class="corner bottom-left"></div>
        <div class="corner bottom-right"></div>
    </div>
    <div class="frame-inner"></div>

    <!-- WATERMARK -->
    <img src="{{ $logoBase64 }}" class="watermark" alt="">

    <div class="content-container">

        <div class="header-section">
            <div class="meta-tags">
                <span>TC. NO: <strong>{{ $certificate_no ?? '2024/089' }}</strong></span>
                <span>ADMISSION NO (GR): <strong>{{ $admission_no ?? '4052' }}</strong></span>
            </div>
            
            <img src="{{ $logoBase64 }}" alt="" class="header-logo">
            
            <h1 class="school-title">{!! nl2br(e($school_name)) !!}</h1>
            <p class="school-address">{{ $school_address }}</p>
        </div>

        <div class="certificate-title-wrapper">
            <div class="certificate-title">Transfer Certificate</div>
        </div>

        <table class="form-table">
            <tr>
                <td class="label-cell"><span class="label-num">1.</span>Name of the Pupil:</td>
                <td class="value-cell" colspan="3">{{ $student_name }}</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">2.</span>Father's / Guardian's Name:</td>
                <td class="value-cell" colspan="3">{{ $father_name }}</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">3.</span>Nationality & Religion:</td>
                <td class="value-cell">{{ $nationality }} / {{ $religion }}</td>
                <td class="label-cell" style="padding-left: 15px;"><span class="label-num">4.</span>Caste:</td>
                <td class="value-cell">{{ $caste }}</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">5.</span>Date of Birth (in figures):</td>
                <td class="value-cell" style="width: 35%;">{{ $dob }}</td>
                <td class="label-cell" style="padding-left: 15px;"><span class="label-num">6.</span>Place of Birth:</td>
                <td class="value-cell">{{ $birth_place }}</td>
            </tr>
            <tr>
                <td class="label-cell"></td>
                <td class="label-cell" colspan="3" style="padding-right:0; padding-top: 5px;">(in words): <span class="value-cell" style="display:inline-block; width: 85%; font-weight: 600;">{{ $dob_words }}</span></td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">7.</span>Date of First Admission:</td>
                <td class="value-cell">{{ $admission_date }}</td>
                <td class="label-cell" style="padding-left: 15px;"><span class="label-num">8.</span>Admitted to Class:</td>
                <td class="value-cell">{{ $class_admitted }}</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">9.</span>Class in which last studied:</td>
                <td class="value-cell" colspan="3">{{ $class_name }}{{ $section_name ? ' ('.$section_name.')' : '' }}</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">10.</span>Board / Annual Exam Result:</td>
                <td class="value-cell" colspan="3">As per school records</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">11.</span>Qualified for Promotion:</td>
                <td class="value-cell">Yes</td>
                <td class="label-cell" style="padding-left: 15px;"><span class="label-num">12.</span>General Conduct:</td>
                <td class="value-cell">Excellent</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">13.</span>School Dues Cleared Upto:</td>
                <td class="value-cell" colspan="3">{{ now()->format('F Y') }} (No Dues Pending)</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">14.</span>Date of Application for TC:</td>
                <td class="value-cell">{{ $leaving_date ?? date('d-m-Y') }}</td>
                <td class="label-cell" style="padding-left: 15px;"><span class="label-num">15.</span>Date of Issue:</td>
                <td class="value-cell">{{ $issue_date }}</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">16.</span>Reason for Leaving:</td>
                <td class="value-cell" colspan="3">{{ $purpose ?? 'Completion of Course / Admission to College' }}</td>
            </tr>
            <tr>
                <td class="label-cell"><span class="label-num">17.</span>Any other Remarks:</td>
                <td class="value-cell" colspan="3">He/She is a hardworking student. We wish them the best.</td>
            </tr>
        </table>

        <div class="footer-area">
            
            <div class="signature-block" style="padding-bottom: 10px;">
                @if($stampBase64)
                    <img src="{{ $stampBase64 }}" alt="Official Stamp" style="max-width: 110px; height: auto; object-fit: contain; transform: translateY(0px) rotate(-2deg); opacity: 0.95; margin-bottom: 0px;">
                @else
                    <div class="seal-placeholder">
                        <div class="inner-wrapper">
                            <div class="stamp-indicator">Place Official Stamp Here</div>
                            <div class="school-name">{{ $school_name ?? 'Galaxy Public School & College Umerkot' }}</div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="signature-block">
                @if(isset($signature))
                    {!! $signature !!}
                @else
                    <div class="fake-signature">Dr. A. Khan</div>
                @endif
                <div class="sign-line"></div>
                <div class="sign-text">Principal / Headmaster</div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         ULTRA-PROFESSIONAL SECURITY WATERMARK — Canvas Multi-Layer
         Layers:
           1. Warm paper base wash
           2. PRIMARY diagonal text rows  (blue + gold alternating, brick-offset)
           3. COUNTER-DIAGONAL text rows  (+22° — cross-hatch effect)
           4. Micro dot security grid
           5. Edge vignette
         ══════════════════════════════════════════════════════════════ --}}
    <script>
    (function () {
        'use strict';

        /* ══ MASTER CONFIG ══════════════════════════════════════════════ */
        var TEXT_A   = 'GALAXY PUBLIC SCHOOL';           /* primary track   */
        var TEXT_B   = 'COLLEGE UMERKOT';                /* secondary track */
        var SEP_STAR = '  \u2736  ';                     /* ✶ star          */
        var SEP_DIAM = '  \u25C6  ';                     /* ◆ diamond       */

        var BLUE  = { r:10,  g:25,  b:49  };
        var GOLD  = { r:165, g:120, b:55  };

        var DPR = Math.min(window.devicePixelRatio || 1, 3); /* cap at 3× */

        /* ── rgba builder ── */
        function rgba(c, a) {
            return 'rgba(' + c.r + ',' + c.g + ',' + c.b + ',' + a + ')';
        }

        /* ── letter-spaced text draw, returns total width drawn ── */
        function drawSpaced(ctx, text, x, y, lsp) {
            var cx = x;
            for (var i = 0; i < text.length; i++) {
                var ch = text[i];
                ctx.fillText(ch, cx, y);
                cx += ctx.measureText(ch).width + lsp;
            }
            return cx - x;
        }

        /* ── tile a row of text across full diagonal length ── */
        function tileRow(ctx, unit, unitW, startX, y, nCols, lsp) {
            for (var c = 0; c < nCols + 3; c++) {
                drawSpaced(ctx, unit, startX + c * unitW, y, lsp);
            }
        }

        /* ══ MAIN RENDER ════════════════════════════════════════════════ */
        function render() {
            var wrap   = document.querySelector('.text-watermark-pattern');
            var canvas = document.getElementById('wm-canvas');
            if (!wrap || !canvas) return;

            var cssW = wrap.offsetWidth;
            var cssH = wrap.offsetHeight;
            if (cssW < 20 || cssH < 20) { setTimeout(render, 90); return; }

            /* ── HiDPI sizing ── */
            canvas.width        = cssW * DPR;
            canvas.height       = cssH * DPR;
            canvas.style.width  = cssW + 'px';
            canvas.style.height = cssH + 'px';

            var ctx = canvas.getContext('2d');
            ctx.scale(DPR, DPR);

            var diagLen = Math.sqrt(cssW * cssW + cssH * cssH) + 40;

            /* ════════════════════════════════════════════════════════════
               LAYER 1 — Warm ivory paper wash
               ════════════════════════════════════════════════════════════ */
            ctx.fillStyle = 'rgba(250,248,244,0.60)';
            ctx.fillRect(0, 0, cssW, cssH);

            /* ════════════════════════════════════════════════════════════
               LAYER 2 — PRIMARY diagonal text  (angle −22°)
               Blue rows: "GALAXY PUBLIC SCHOOL ✶  GALAXY PUBLIC SCHOOL ✶ …"
               Gold rows: "COLLEGE UMERKOT ◆  COLLEGE UMERKOT ◆ …"
               Brick-offset on alternating rows for dense interlocking
               ════════════════════════════════════════════════════════════ */
            (function primaryLayer() {
                var FS      = 10;                   /* font size px          */
                var LSP     = 0.9;                  /* letter spacing px     */
                var ROW_H   = FS + 17;              /* row pitch             */
                var ANG     = -22 * Math.PI / 180;

                ctx.save();
                ctx.translate(cssW / 2, cssH / 2);
                ctx.rotate(ANG);

                /* measure with blue font (heavier weight) */
                ctx.font = '700 ' + FS + 'px "Cinzel","Times New Roman",serif';
                var unitA  = TEXT_A + SEP_STAR;
                var unitAW = ctx.measureText(unitA).width + unitA.length * LSP;

                ctx.font = '600 ' + FS + 'px "Cinzel","Times New Roman",serif';
                var unitB  = TEXT_B + SEP_DIAM;
                var unitBW = ctx.measureText(unitB).width + unitB.length * LSP;

                var nRows = Math.ceil(diagLen / ROW_H) + 10;
                var startY = -Math.floor(nRows / 2) * ROW_H;

                for (var r = 0; r < nRows; r++) {
                    var y      = startY + r * ROW_H;
                    var isBlue = (r % 2 === 0);

                    if (isBlue) {
                        ctx.fillStyle = rgba(BLUE, 0.11);
                        ctx.font      = '700 ' + FS + 'px "Cinzel","Times New Roman",serif';
                        var colsA  = Math.ceil(diagLen / unitAW) + 4;
                        var xShiftA = 0;
                        var startXA = -Math.ceil(colsA / 2) * unitAW - xShiftA;
                        tileRow(ctx, unitA, unitAW, startXA, y, colsA, LSP);
                    } else {
                        ctx.fillStyle = rgba(GOLD, 0.09);
                        ctx.font      = '600 ' + FS + 'px "Cinzel","Times New Roman",serif';
                        var colsB  = Math.ceil(diagLen / unitBW) + 4;
                        /* brick shift = half of blue unit width */
                        var xShiftB = unitAW / 2;
                        var startXB = -Math.ceil(colsB / 2) * unitBW - xShiftB;
                        tileRow(ctx, unitB, unitBW, startXB, y, colsB, LSP);
                    }
                }
                ctx.restore();
            })();

            /* ════════════════════════════════════════════════════════════
               LAYER 3 — COUNTER-DIAGONAL text  (angle +18°)
               Creates the classic cross-hatch seen on banknotes / degrees
               Uses both texts but at lower opacity and smaller font
               ════════════════════════════════════════════════════════════ */
            (function counterLayer() {
                var FS    = 8.5;
                var LSP   = 0.6;
                var ROW_H = FS + 22;
                var ANG   = 18 * Math.PI / 180;

                ctx.save();
                ctx.translate(cssW / 2, cssH / 2);
                ctx.rotate(ANG);

                ctx.font = '600 ' + FS + 'px "Cinzel","Times New Roman",serif';
                var unitC  = TEXT_A + SEP_DIAM + TEXT_B + SEP_STAR;
                var unitCW = ctx.measureText(unitC).width + unitC.length * LSP;

                var nRows  = Math.ceil(diagLen / ROW_H) + 10;
                var startY = -Math.floor(nRows / 2) * ROW_H;
                var nCols  = Math.ceil(diagLen / unitCW) + 4;

                for (var r = 0; r < nRows; r++) {
                    var y = startY + r * ROW_H;
                    /* alternate micro-opacity between blue and gold */
                    ctx.fillStyle = (r % 2 === 0)
                        ? rgba(BLUE, 0.055)
                        : rgba(GOLD, 0.045);
                    var xShift = (r % 2 === 0) ? 0 : unitCW / 2;
                    var startX = -Math.ceil(nCols / 2) * unitCW - xShift;
                    tileRow(ctx, unitC, unitCW, startX, y, nCols, LSP);
                }
                ctx.restore();
            })();

            /* ════════════════════════════════════════════════════════════
               LAYER 4 — Micro security dot grid
               Tiny dots at every 14px in a rotated grid — classic
               government-document guilloche substitute
               ════════════════════════════════════════════════════════════ */
            (function dotGrid() {
                var STEP = 14;
                var ANG  = -22 * Math.PI / 180;

                ctx.save();
                ctx.translate(cssW / 2, cssH / 2);
                ctx.rotate(ANG);

                var half = diagLen / 2 + STEP * 2;
                var cols = Math.ceil(half * 2 / STEP);
                var rows = Math.ceil(half * 2 / STEP);

                for (var r = 0; r < rows; r++) {
                    for (var c = 0; c < cols; c++) {
                        var dx = -half + c * STEP;
                        var dy = -half + r * STEP;
                        /* alternate dot color by checker pattern */
                        var checker = (r + c) % 2 === 0;
                        ctx.fillStyle = checker
                            ? rgba(BLUE, 0.06)
                            : rgba(GOLD, 0.05);
                        ctx.beginPath();
                        ctx.arc(dx, dy, 0.7, 0, Math.PI * 2);
                        ctx.fill();
                    }
                }
                ctx.restore();
            })();

            /* ════════════════════════════════════════════════════════════
               LAYER 5 — Radial edge vignette (depth + frame feel)
               ════════════════════════════════════════════════════════════ */
            (function vignette() {
                var gr = ctx.createRadialGradient(
                    cssW / 2, cssH / 2, cssH * 0.22,
                    cssW / 2, cssH / 2, cssH * 0.85
                );
                gr.addColorStop(0,    'rgba(0,0,0,0)');
                gr.addColorStop(0.65, 'rgba(0,0,0,0)');
                gr.addColorStop(1,    rgba(BLUE, 0.055));
                ctx.fillStyle = gr;
                ctx.fillRect(0, 0, cssW, cssH);
            })();
        }

        /* ══ BOOT ════════════════════════════════════════════════════════ */
        function boot() {
            render();
            /* re-render once web fonts are fully loaded for crisp text */
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(render);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }

    })();
    </script>

</div>
