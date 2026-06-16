{{-- Certificate of Achievement - Dynamic Blade Template --}}
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
        width: 450px; height: 450px;
        transform: translate(-50%, -50%);
        opacity: 0.03; z-index: 0; pointer-events: none; filter: grayscale(100%);
    }
    .content-container {
        position: relative; padding: 25mm 22mm;
        z-index: 5; height: 100%;
        display: flex; flex-direction: column;
        font-family: 'Montserrat', sans-serif; color: var(--text-main);
    }
    .header-section { text-align: center; position: relative; margin-bottom: 25px; }
    .header-logo { width: 80px; margin-bottom: 10px; }
    .school-title {
        font-family: 'Cinzel', serif; font-size: 20px; font-weight: 800;
        color: var(--royal-blue); margin: 0 0 5px 0; line-height: 1.3; text-transform: uppercase;
    }
    .school-address {
        font-size: 11px; font-weight: 600; color: #64748b;
        letter-spacing: 2px; text-transform: uppercase; margin: 0 0 25px 0;
    }
    .award-title-wrapper { text-align: center; margin-bottom: 35px; }
    .document-title {
        display: inline-block; border-top: 2px solid var(--rich-gold);
        border-bottom: 2px solid var(--rich-gold); padding: 10px 40px;
        color: var(--royal-blue); font-family: 'Cinzel', serif; font-weight: 800;
        text-transform: uppercase; letter-spacing: 4px; font-size: 26px;
    }
    .award-body {
        text-align: center; flex-grow: 1;
        display: flex; flex-direction: column;
        justify-content: center; padding: 0 20px; margin-top: -30px;
    }
    .pre-text {
        font-size: 15px; color: #475569; text-transform: uppercase;
        letter-spacing: 2px; margin-bottom: 20px; font-weight: 500;
    }
    .recipient-name {
        font-family: 'Dancing Script', cursive; font-size: 52px;
        color: var(--royal-blue); font-weight: 700;
        border-bottom: 2px solid var(--rich-gold);
        display: inline-block; padding: 0 50px 5px 50px;
        margin: 0 auto 25px auto; line-height: 1.2;
    }
    .post-text { font-size: 15px; color: #334155; line-height: 1.8; margin-bottom: 25px; }
    .achievement-box {
        font-family: 'Cinzel', serif; font-size: 18px; font-weight: 700;
        color: var(--rich-gold); text-transform: uppercase; letter-spacing: 1.5px;
        margin: 0 auto 30px auto; padding: 15px 30px;
        background: rgba(197, 160, 89, 0.05); border: 1px dashed var(--rich-gold);
        display: inline-block; max-width: 90%; line-height: 1.5;
    }
    .final-text { font-size: 14px; color: #475569; line-height: 1.8; font-style: italic; }
    .stamp-container {
        width: 110px; height: 110px; border: 2px solid var(--royal-blue);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        text-align: center; font-size: 10px; color: var(--royal-blue);
        font-weight: 800; text-transform: uppercase;
        background: rgba(10, 25, 49, 0.02); transform: rotate(-15deg);
        position: relative; box-shadow: inset 0 0 0 3px var(--rich-gold); margin: 0 auto;
    }
    .stamp-container::after {
        content: "OFFICIAL SEAL"; position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%); width: 100%;
        border-top: 1px dashed var(--royal-blue); border-bottom: 1px dashed var(--royal-blue);
        padding: 3px 0; background: rgba(255,255,255,0.8);
    }
    .signature-grid {
        margin-top: auto; display: flex; justify-content: space-between;
        align-items: flex-end; position: relative; padding-bottom: 10mm;
    }
    .signature-block { text-align: center; position: relative; width: 180px; }
    .signature-line { border-top: 1px solid var(--royal-blue); margin-bottom: 8px; width: 100%; }
    .signature-block label {
        display: block; font-size: 11px; color: var(--royal-blue);
        font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;
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

    <img src="data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpath d='M60 10 L20 25 L20 65 C20 90 50 110 60 110 C70 110 100 90 100 65 L100 25 Z' fill='%23ffffff' stroke='%230A1931' stroke-width='4'/%3E%3Cpath d='M60 16 L26 29 L26 63 C26 84 52 102 60 102 C68 102 94 84 94 63 L94 29 Z' fill='%230A1931'/%3E%3Cpolygon points='60,25 65,35 75,35 67,42 70,52 60,46 50,52 53,42 45,35 55,35' fill='%23C5A059'/%3E%3Cpath d='M45 75 L45 60 L60 65 L75 60 L75 75 L60 80 Z' fill='%23C5A059'/%3E%3Cpath d='M60 65 L60 80' stroke='%230A1931' stroke-width='2'/%3E%3C/svg%3E" class="watermark" alt="">

    <div class="content-container">
        <div class="header-section">
            <img src="data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpath d='M60 10 L20 25 L20 65 C20 90 50 110 60 110 C70 110 100 90 100 65 L100 25 Z' fill='%23ffffff' stroke='%230A1931' stroke-width='4'/%3E%3Cpath d='M60 16 L26 29 L26 63 C26 84 52 102 60 102 C68 102 94 84 94 63 L94 29 Z' fill='%230A1931'/%3E%3Cpolygon points='60,25 65,35 75,35 67,42 70,52 60,46 50,52 53,42 45,35 55,35' fill='%23C5A059'/%3E%3Cpath d='M45 75 L45 60 L60 65 L75 60 L75 75 L60 80 Z' fill='%23C5A059'/%3E%3Cpath d='M60 65 L60 80' stroke='%230A1931' stroke-width='2'/%3E%3C/svg%3E" alt="" class="header-logo">
            <h1 class="school-title">{{ $school_name }}</h1>
            <p class="school-address">{{ $school_address }}</p>
        </div>

        <div class="award-title-wrapper">
            <div class="document-title">Certificate of Achievement</div>
        </div>

        <div class="award-body">
            <div class="pre-text">This certificate is proudly presented to</div>

            <div class="recipient-name">{{ $student_name }}</div>

            <div class="post-text">
                In formal recognition of their exceptional dedication, outstanding performance,<br>
                and remarkable accomplishment in:
            </div>

            <div class="achievement-box">
                {{ $purpose }}
            </div>

            <div class="final-text">
                During the academic year {{ $academic_year }}. We commend their hard work, innovative<br>
                thinking, and wish them continued success in all future academic endeavors.
            </div>
        </div>

        <div class="signature-grid">
            <div class="signature-block">
                <div class="signature-line"></div>
                <label>Class Teacher</label>
            </div>

            <div class="stamp-container">
                <span style="margin-top: -25px; display: block;">GBHSS<br>DHILYAR</span>
                <span style="margin-top: 30px; display: block; font-size: 8px;">SINDH BD. OF ED.</span>
            </div>

            <div class="signature-block">
                {!! $signature !!}
                <div class="signature-line"></div>
                <label>Headmaster</label>
            </div>
        </div>
    </div>
</div>