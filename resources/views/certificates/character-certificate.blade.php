{{-- Character Certificate - Dynamic Blade Template --}}
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
    
    // Format academic session dynamically
    $academic_session = $academic_year ?? '';
    if (preg_match('/(\d{4})-\d{2}-\d{2}\s*to\s*(\d{4})-\d{2}-\d{2}/', $academic_session, $matches)) {
        $academic_session = $matches[1] . ' - ' . $matches[2];
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Character Certificate - Ultra Premium</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

    <style>
        /* ── RESET & PAGE SETUP ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --royal-blue: #0A1931;
            --royal-blue-light: #162f55;
            --rich-gold: #C5A059;
            --accent-gold: #d4af37;
            --text-main: #1e293b;
            --paper-white: #ffffff;
            --bg-color: #525659;
        }

        body {
            background-color: var(--bg-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
            color: var(--text-main);
            padding: 20px 0;
        }

        /* ── PRINT & DOWNLOAD BUTTON CSS ── */
        .action-container {
            margin-bottom: 20px;
        }
        .btn-print {
            background-color: var(--rich-gold);
            color: var(--royal-blue);
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: 700;
            padding: 12px 28px;
            border: 2px solid var(--royal-blue);
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-print:hover {
            background-color: var(--royal-blue);
            color: var(--rich-gold);
            border-color: var(--rich-gold);
        }

        /* ── A4 PAGE CONTAINER ── */
        .a4-page {
            width: 210mm;
            height: 297mm;
            background-color: var(--paper-white);
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            overflow: hidden; 
            display: flex;
            flex-direction: column;
        }

        /* ── Canvas Watermark Container ── */
        .text-watermark-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        /* ── PRINT RULES ── */
        @media print {
            @page { size: A4 portrait; margin: 0; }
            body { background-color: transparent; margin: 0; padding: 0; display: block; }
            .a4-page { margin: 0; box-shadow: none; width: 210mm; height: 297mm; page-break-after: avoid; }
            .watermark { opacity: 0.12 !important; }
            .stamp-container { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .document-title { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .action-container { display: none !important; }
        }

        /* ── ELEGANT DOUBLE FRAME ── */
        .frame-outer {
            position: absolute;
            top: 12mm; left: 12mm; right: 12mm; bottom: 12mm;
            border: 4px solid var(--royal-blue);
            z-index: 1;
            pointer-events: none;
        }
        .frame-inner {
            position: absolute;
            top: calc(12mm + 6px); left: calc(12mm + 6px); right: calc(12mm + 6px); bottom: calc(12mm + 6px);
            border: 1.5px solid var(--rich-gold);
            z-index: 1;
            pointer-events: none;
        }

        /* ── CORNER BRACKETS ── */
        .corner {
            position: absolute;
            width: 35px; height: 35px;
            z-index: 2;
        }
        .top-left     { top: -2px; left: -2px; border-top: 6px solid var(--rich-gold); border-left: 6px solid var(--rich-gold); }
        .top-right    { top: -2px; right: -2px; border-top: 6px solid var(--rich-gold); border-right: 6px solid var(--rich-gold); }
        .bottom-left  { bottom: -2px; left: -2px; border-bottom: 6px solid var(--rich-gold); border-left: 6px solid var(--rich-gold); }
        .bottom-right { bottom: -2px; right: -2px; border-bottom: 6px solid var(--rich-gold); border-right: 6px solid var(--rich-gold); }

        /* ── WATERMARK ── */
        .watermark {
            position: absolute;
            top: 50%; left: 50%;
            width: 430px; height: 450px;
            transform: translate(-50%, -50%);
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }

        /* ── CONTENT CONTAINER ── */
        .content-container {
            position: relative;
            padding: 22mm 22mm 18mm 22mm; /* Perfect top and bottom safe padding */
            z-index: 5;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        /* ── HEADER SECTION ── */
        .header-section {
            text-align: center;
            position: relative;
            margin-bottom: 12px;
        }

        .header-logo {
            width: 120px; /* Slightly scaled down for safety */
            height: auto;
            max-height: 120px;
            object-fit: contain;
            margin-bottom: 8px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        .school-title {
            font-family: 'Cinzel', serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--royal-blue);
            margin: 0 0 4px 0;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .school-address {
            font-size: 11.5px;
            font-weight: 600;
            color: #475569;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin: 0 0 15px 0;
        }

        /* ── DOCUMENT TITLE ── */
        .document-title-wrapper {
            text-align: center;
            margin-bottom: 18px;
        }

        .document-title {
            display: inline-block;
            border-top: 2px solid var(--rich-gold);
            border-bottom: 2px solid var(--rich-gold);
            padding: 6px 40px;
            color: var(--royal-blue);
            font-family: 'Cinzel', serif;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 4px;
            font-size: 20px;
            background: linear-gradient(90deg, transparent, rgba(197, 160, 89, 0.08), transparent);
        }

        /* ── INFO BAR ── */
        .info-bar {
            display: flex;
            justify-content: space-between;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--royal-blue);
            border-bottom: 1.5px dashed var(--rich-gold);
            padding-bottom: 6px;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ── CERTIFICATE BODY ── */
        .cert-body {
            font-size: 15.5px;
            line-height: 1.95; /* Perfect readable height that doesn't overflow A4 */
            color: #1e293b;
            text-align: justify;
            padding: 0 5px;
            flex-grow: 1; 
        }

        .cert-body p {
            margin-bottom: 14px;
        }

        .fill-text {
            font-family: 'Dancing Script', cursive;
            font-size: 25px;
            color: var(--royal-blue);
            font-weight: 700;
            border-bottom: 1.5px solid #64748b;
            padding: 0 12px;
            display: inline-block;
            line-height: 0.9;
            transform: translateY(2px);
        }

        .highlight-text {
            font-family: 'Cinzel', serif;
            font-weight: 800;
            color: var(--rich-gold);
            font-size: 17px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── FOOTER, SIGNATURES & STAMP ── */
        .signature-grid {
            display: flex;
            justify-content: space-around; /* Standardized to space-around for 2 blocks */
            align-items: flex-end;
            padding-top: 15px;
            margin-top: auto; /* Forces block to stay at the absolute safe bottom */
            width: 100%;
        }

        .signature-block {
            text-align: center;
            position: relative;
            width: 35%; /* Standardized to 35% */
        }

        /* Fake Signatures Placement */
        .fake-signature {
            font-family: 'Dancing Script', cursive;
            font-size: 28px;
            color: #0f172a; 
            position: absolute;
            bottom: 32px; 
            width: 100%;
            text-align: center;
            transform: rotate(-5deg); 
            opacity: 0.85;
            pointer-events: none;
        }

        .fake-signature.sig2 {
            font-size: 32px;
            transform: rotate(4deg);
            color: #1a1a1a; 
            bottom: 28px;
        }

        .script-signature {
            height: 40px; display: flex; align-items: flex-end; justify-content: center;
            margin-bottom: 5px; width: 100%; position: absolute; bottom: 25px;
        }

        .signature-line {
            border-top: 1.5px solid var(--royal-blue);
            margin-bottom: 6px;
            width: 100%;
        }

        .signature-block label {
            display: block;
            font-size: 11px;
            color: var(--royal-blue);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
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
    </style>
</head>
<body>

    <div class="action-container">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print / Download PDF
        </button>
    </div>

    <div class="a4-page">
        <!-- Watermark Container -->
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
                <div class="document-title">Character Certificate</div>
            </div>

            <div class="info-bar">
                <span>Certificate No: <strong>{{ $certificate_no ?? 'CC/' . date('Y') . '/' . rand(100, 999) }}</strong></span>
                <span>Date of Issue: <strong>{{ $issue_date_formatted ?? $issue_date ?? date('d/m/Y') }}</strong></span>
                <span>G.R. No: <strong>{{ $admission_no }}</strong></span>
            </div>

            <div class="cert-body">
                <p>
                    This is to officially certify that Mr. / Ms. 
                    <span class="fill-text">{{ $student_name }}</span>, 
                    son / daughter of Mr. 
                    <span class="fill-text">{{ $father_name }}</span>, 
                    has been a regular and bona fide student of this institution during the academic session 
                    <span class="fill-text">{{ $academic_session }}</span>.
                </p>
                
                <p>
                    He / She has successfully completed his/her course of studies and passed the Higher Secondary School Certificate Examination in the 
                    <span class="fill-text">{{ $class_name }}</span> Group, securing an overall commendable performance.
                </p>

                <p>
                    During his / her stay at this institution, his/her conduct, deportment, and character have been found to be <span class="highlight-text">Excellent</span>. He / She has shown keen interest in co-curricular activities, possesses a refined personality, and is honest, respectful, and hardworking.
                </p>

                <p>
                    To the best of my knowledge and belief, he/she bears a good moral character and was never found involved in any activity detrimental to the discipline of the institution. I wish him/her the best of luck and success in all future academic endeavors.
                </p>
            </div>

            <div class="signature-grid">
                
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

                <div class="signature-block">
                    <div class="script-signature">
                        @if(isset($signature))
                            {!! $signature !!}
                        @else
                            <div class="fake-signature sig2">Principal</div>
                        @endif
                    </div>
                    <div class="signature-line"></div>
                    <label>Principal / Headmaster</label>
                </div>

            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         ULTRA-PROFESSIONAL SECURITY WATERMARK — Canvas Multi-Layer
         ══════════════════════════════════════════════════════════════ --}}
    <script>
    (function () {
        'use strict';

        var TEXT_A   = '{{ strtoupper($school_name ?? "GALAXY PUBLIC SCHOOL") }}';
        var TEXT_B   = 'CHARACTER CERTIFICATE';
        var SEP_STAR = '  \u2736  ';
        var SEP_DIAM = '  \u25C6  ';

        var BLUE  = { r:10,  g:25,  b:49  };
        var GOLD  = { r:165, g:120, b:55  };

        var DPR = Math.min(window.devicePixelRatio || 1, 3);

        function rgba(c, a) { return 'rgba(' + c.r + ',' + c.g + ',' + c.b + ',' + a + ')'; }

        function drawSpaced(ctx, text, x, y, lsp) {
            var cx = x;
            for (var i = 0; i < text.length; i++) {
                var ch = text[i];
                ctx.fillText(ch, cx, y);
                cx += ctx.measureText(ch).width + lsp;
            }
            return cx - x;
        }

        function tileRow(ctx, unit, unitW, startX, y, nCols, lsp) {
            for (var c = 0; c < nCols + 3; c++) {
                drawSpaced(ctx, unit, startX + c * unitW, y, lsp);
            }
        }

        function render() {
            var wrap   = document.querySelector('.text-watermark-pattern');
            var canvas = document.getElementById('wm-canvas');
            if (!wrap || !canvas) return;

            var cssW = wrap.offsetWidth;
            var cssH = wrap.offsetHeight;
            if (cssW < 20 || cssH < 20) { setTimeout(render, 90); return; }

            canvas.width        = cssW * DPR;
            canvas.height       = cssH * DPR;
            canvas.style.width  = cssW + 'px';
            canvas.style.height = cssH + 'px';

            var ctx = canvas.getContext('2d');
            ctx.scale(DPR, DPR);

            var diagLen = Math.sqrt(cssW * cssW + cssH * cssH) + 40;

            ctx.fillStyle = 'rgba(250,248,244,0.60)';
            ctx.fillRect(0, 0, cssW, cssH);

            (function primaryLayer() {
                var FS      = 10;
                var LSP     = 0.9;
                var ROW_H   = FS + 17;
                var ANG     = -22 * Math.PI / 180;

                ctx.save();
                ctx.translate(cssW / 2, cssH / 2);
                ctx.rotate(ANG);

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
                        var xShiftB = unitAW / 2;
                        var startXB = -Math.ceil(colsB / 2) * unitBW - xShiftB;
                        tileRow(ctx, unitB, unitBW, startXB, y, colsB, LSP);
                    }
                }
                ctx.restore();
            })();

            (function counterLayer() {
                var FS    = 8.5;
                var LSP   = 0.6;
                var ROW_H = FS + 22;
                var ANG   = 18 * Math.PI / 180;

                ctx.save();
                ctx.translate(cssW / 2, cssH / 2);
                ctx.rotate(ANG);

                ctx.font = '600 ' + FS + 'px "Cinzel","Times New Roman",serif';
                var unitC  = TEXT_A + SEP_DIAM + TEXT_B + TEXT_A + SEP_STAR;
                var unitCW = ctx.measureText(unitC).width + unitC.length * LSP;

                var nRows  = Math.ceil(diagLen / ROW_H) + 10;
                var startY = -Math.floor(nRows / 2) * ROW_H;
                var nCols  = Math.ceil(diagLen / unitCW) + 4;

                for (var r = 0; r < nRows; r++) {
                    var y = startY + r * ROW_H;
                    ctx.fillStyle = (r % 2 === 0) ? rgba(BLUE, 0.055) : rgba(GOLD, 0.045);
                    var xShift = (r % 2 === 0) ? 0 : unitCW / 2;
                    var startX = -Math.ceil(nCols / 2) * unitCW - xShift;
                    tileRow(ctx, unitC, unitCW, startX, y, nCols, LSP);
                }
                ctx.restore();
            })();

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
                        var checker = (r + c) % 2 === 0;
                        ctx.fillStyle = checker ? rgba(BLUE, 0.06) : rgba(GOLD, 0.05);
                        ctx.beginPath();
                        ctx.arc(dx, dy, 0.7, 0, Math.PI * 2);
                        ctx.fill();
                    }
                }
                ctx.restore();
            })();

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

        function boot() {
            render();
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

</body>
</html>
