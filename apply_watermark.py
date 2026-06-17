import os
import glob
import re

files = [
    "transfer-certificate.blade.php",
    "school-leaving-certificate.blade.php",
    "character-certificate.blade.php",
    "certificate-of-participation.blade.php",
    "certificate-of-achievement.blade.php"
]

base_dir = r"d:\Xamp\htdocs\school\resources\views\certificates"

script_template = """
    {{-- ── Canvas-based diagonal tiled watermark ── --}}
    <script>
    (function() {
        function drawWatermark() {
            var container = document.querySelector('.text-watermark-pattern');
            var canvas    = document.getElementById('wm-canvas');
            if (!canvas || !container) return;

            var cw = container.offsetWidth;
            var ch = container.offsetHeight;
            if (cw < 10 || ch < 10) { setTimeout(drawWatermark, 80); return; }

            canvas.width  = cw;
            canvas.height = ch;
            var ctx = canvas.getContext('2d');

            var FONT_SIZE  = 11;         /* px  — readable but subtle          */
            var ROW_GAP    = 20;         /* px  — breathing room between rows  */
            var ROW_H      = FONT_SIZE + ROW_GAP;
            var ANGLE_DEG  = -15;        /* °   — classic security-paper tilt  */
            var ANGLE_RAD  = ANGLE_DEG * Math.PI / 180;

            var BLUE_COLOR  = 'rgba(10,  25, 49,  0.09)';   /* royal blue     */
            var GOLD_COLOR  = 'rgba(197, 160, 89, 0.075)';  /* rich gold      */

            var TEXT_MAIN = 'GALAXY PUBLIC SCHOOL & COLLEGE UMERKOT';
            var SEPARATOR = '   \\u2736   ';   /* ✶  */

            ctx.save();
            ctx.font = '700 ' + FONT_SIZE + 'px "Cinzel", "Georgia", serif';

            var singleW  = ctx.measureText(TEXT_MAIN + SEPARATOR).width;
            var diagLen  = Math.sqrt(cw * cw + ch * ch);
            var numRows  = Math.ceil(diagLen / ROW_H) + 6;
            var numCols  = Math.ceil(diagLen / singleW) + 3;

            ctx.translate(cw / 2, ch / 2);
            ctx.rotate(ANGLE_RAD);

            var startY = -Math.floor(numRows / 2) * ROW_H;

            for (var r = 0; r < numRows; r++) {
                var y       = startY + r * ROW_H;
                var isBlue  = (r % 2 === 0);
                var xShift  = isBlue ? 0 : singleW / 2;   /* brick offset on alt rows */

                ctx.fillStyle = isBlue ? BLUE_COLOR : GOLD_COLOR;

                var startX = -Math.ceil(numCols / 2) * singleW - xShift;
                for (var c = 0; c < numCols + 2; c++) {
                    ctx.fillText(TEXT_MAIN + SEPARATOR, startX + c * singleW, y);
                }
            }

            ctx.restore();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', drawWatermark);
        } else {
            drawWatermark();
        }

        /* Re-draw if fonts load late */
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(drawWatermark);
        }
    })();
    </script>
"""

for fname in files:
    fpath = os.path.join(base_dir, fname)
    if not os.path.exists(fpath):
        continue
    
    with open(fpath, "r", encoding="utf-8") as f:
        content = f.read()

    # Skip if already has canvas
    if '<canvas id="wm-canvas"></canvas>' in content:
        continue

    # 1. Extract the frame-outer borders to apply to .text-watermark-pattern
    match = re.search(r'\.frame-outer\s*\{[^}]*?top:\s*([^;]+);[^}]*?left:\s*([^;]+);[^}]*?right:\s*([^;]+);[^}]*?bottom:\s*([^;]+);', content)
    if not match:
        # Fallback if the regex fails
        top, left, right, bottom = "12mm", "12mm", "12mm", "12mm"
    else:
        top, left, right, bottom = match.groups()

    css_inject = f"""
    /* ── Canvas Watermark Container ── */
    .text-watermark-pattern {{
        position: absolute;
        top: {top}; left: {left}; right: {right}; bottom: {bottom};
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
    }}
    .text-watermark-pattern canvas {{
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
    }}
"""

    # Insert CSS before closing </style>
    content = content.replace("</style>", css_inject + "\n</style>")

    html_inject = """
    {{-- ── Canvas-based diagonal tiled watermark ── --}}
    <div class="text-watermark-pattern">
        <canvas id="wm-canvas"></canvas>
    </div>
"""
    # Insert HTML before <div class="frame-outer">
    content = content.replace('<div class="frame-outer">', html_inject + '\n    <div class="frame-outer">')

    # Replace the old <img src="{{ $logoBase64 }}" class="watermark" alt=""> if needed, 
    # but the user said "Keep the existing logo watermark (if present) unchanged in each certificate."
    # So we don't remove it!

    # Insert script before the final </div> (which is the closing of .a4-page)
    # We find the last </div> in the file
    last_div_idx = content.rfind("</div>")
    if last_div_idx != -1:
        content = content[:last_div_idx] + script_template + "\n" + content[last_div_idx:]
    
    with open(fpath, "w", encoding="utf-8") as f:
        f.write(content)

print("Watermark applied successfully!")
