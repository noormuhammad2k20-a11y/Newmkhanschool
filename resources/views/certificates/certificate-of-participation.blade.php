{{-- Certificate of Participation - Dynamic Blade Template --}}
@php
    $logoBase64 = '';
    $logoPath = public_path('images/certificate-logo.png');
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
    
    // Format academic session dynamically
    $academic_session = $academic_year ?? '';
    if (preg_match('/(\d{4})-\d{2}-\d{2}\s*to\s*(\d{4})-\d{2}-\d{2}/', $academic_session, $matches)) {
        $academic_session = $matches[1] . ' - ' . $matches[2];
    }
@endphp
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
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
        width: 450px; height: auto; max-height: 450px; object-fit: contain;
        transform: translate(-50%, -50%);
        opacity: 0.12; z-index: 0; pointer-events: none;
    }
    .content-container {
        position: relative; padding: 25mm 22mm;
        z-index: 5; height: 100%;
        display: flex; flex-direction: column;
        font-family: 'Montserrat', sans-serif; color: var(--text-main);
    }
    .header-section { text-align: center; position: relative; margin-bottom: 30px; }
    .header-logo { width: 110px; height: auto; max-height: 110px; object-fit: contain; margin-bottom: 10px; }
    .school-title {
        font-family: 'Cinzel', serif; font-size: 22px; font-weight: 800;
        color: var(--royal-blue); margin: 0 0 5px 0; line-height: 1.3; text-transform: uppercase;
    }
    .school-address {
        font-size: 11px; font-weight: 600; color: #64748b;
        letter-spacing: 2px; text-transform: uppercase; margin: 0 0 25px 0;
    }
    .document-title-wrapper { text-align: center; margin-bottom: 35px; }
    .document-title {
        display: inline-block; border-top: 2px solid var(--rich-gold);
        border-bottom: 2px solid var(--rich-gold); padding: 10px 40px;
        color: var(--royal-blue); font-family: 'Cinzel', serif; font-weight: 800;
        text-transform: uppercase; letter-spacing: 3px; font-size: 24px;
    }
    .cert-body {
        text-align: center; flex-grow: 1;
        display: flex; flex-direction: column;
        justify-content: center; padding: 0 15px; margin-top: -20px;
    }
    .pre-text {
        font-size: 15px; color: #475569; text-transform: uppercase;
        letter-spacing: 2px; margin-bottom: 25px; font-weight: 500;
    }
    .recipient-name {
        font-family: 'Dancing Script', cursive; font-size: 50px;
        color: var(--royal-blue); font-weight: 700;
        border-bottom: 2px solid var(--rich-gold);
        display: inline-block; padding: 0 50px 5px 50px;
        margin: 0 auto 30px auto; line-height: 1.2;
    }
    .post-text { font-size: 15px; color: #334155; line-height: 1.8; margin-bottom: 30px; }
    .event-box {
        font-family: 'Cinzel', serif; font-size: 19px; font-weight: 800;
        color: var(--royal-blue); text-transform: uppercase; letter-spacing: 1.5px;
        margin: 0 auto 30px auto; padding: 18px 30px;
        background: rgba(197, 160, 89, 0.06);
        border-left: 4px solid var(--rich-gold); border-right: 4px solid var(--rich-gold);
        display: inline-block; max-width: 95%; line-height: 1.5;
    }
    .final-text { font-size: 14px; color: #475569; line-height: 1.8; }
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
    .signature-grid {
        margin-top: auto; display: flex; justify-content: space-around;
        align-items: flex-end; position: relative; padding-bottom: 10mm;
    }
    .signature-block { text-align: center; position: relative; width: 35%; }
    .signature-line { border-top: 1px solid var(--royal-blue); margin-bottom: 8px; width: 100%; }
    .signature-block label {
        display: block; font-size: 11px; color: var(--royal-blue);
        font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;
    }

    /* ── Canvas Watermark Container ── */
    .text-watermark-pattern {
        position: absolute;
        top: 12mm; left: 12mm; right: 12mm; bottom: 12mm;
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

<div class="a4-page">
    
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

    <img src="{{ $logoBase64 }}" class="watermark" alt="">

    <div class="content-container">
        <div class="header-section">
            <img src="{{ $logoBase64 }}" alt="" class="header-logo">
            <h1 class="school-title">{{ $school_name }}</h1>
            <p class="school-address">{{ $school_address }}</p>
        </div>

        <div class="document-title-wrapper">
            <div class="document-title">Certificate of Participation</div>
        </div>

        <div class="cert-body">
            <div class="pre-text">This certificate is proudly presented to</div>

            <div class="recipient-name">{{ $student_name }}</div>

            <div class="post-text">
                In recognition of their active and invaluable participation in:
            </div>

            <div class="event-box">
                {{ $purpose }}
            </div>

            <div class="final-text">
                Held during the academic session {{ $academic_session }}.<br>
                We highly appreciate their dedication, collaborative spirit, and commitment to continuous learning.
            </div>
        </div>

        <div class="signature-grid">

            <div class="seal-placeholder">
                <div class="inner-wrapper">
                    <div class="stamp-indicator">Place Official Stamp Here</div>
                    <div class="school-name">{{ $school_name ?? 'Galaxy Public School & College Umerkot' }}</div>
                </div>
            </div>

            <div class="signature-block">
                {!! $signature !!}
                <div class="signature-line"></div>
                <label>Principal / Dean</label>
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
