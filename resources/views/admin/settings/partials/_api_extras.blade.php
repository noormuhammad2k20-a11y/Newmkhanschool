{{-- API tab extras: JazzCash, EasyPaisa, Gemini, OpenAI test buttons --}}
<div class="p-5 border-t border-outline-variant/20 space-y-4">

    {{-- JazzCash Test --}}
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-[14px] font-semibold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">payment</span>
                JazzCash Gateway
                <span id="jazzcash-env-badge" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Unknown</span>
            </h4>
            <button type="button" class="btn-outline text-[13px] gap-2" onclick="testJazzCashConnection(this)">
                <span class="material-symbols-outlined text-[16px]">link</span>
                <span class="btn-text">Test Connection</span>
            </button>
        </div>
        <div id="jazzcash-test-result" class="hidden rounded-lg p-3 text-[13px] font-medium"></div>
    </div>

    {{-- EasyPaisa Test --}}
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-[14px] font-semibold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">account_balance_wallet</span>
                EasyPaisa Gateway
            </h4>
            <button type="button" class="btn-outline text-[13px] gap-2" onclick="testEasyPaisaConnection(this)">
                <span class="material-symbols-outlined text-[16px]">link</span>
                <span class="btn-text">Test Connection</span>
            </button>
        </div>
        <div id="easypaisa-test-result" class="hidden rounded-lg p-3 text-[13px] font-medium"></div>
    </div>

    {{-- Gemini AI Test --}}
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-[14px] font-semibold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">auto_awesome</span>
                Gemini AI
                <span class="text-[10px] text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">Used by: AI Grading, Timetable Generator</span>
            </h4>
            <button type="button" class="btn-outline text-[13px] gap-2" onclick="testGeminiConnection(this)">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                <span class="btn-text">Verify Key</span>
            </button>
        </div>
        <div id="gemini-test-result" class="hidden rounded-lg p-3 text-[13px] font-medium"></div>
    </div>

    {{-- OpenAI Test --}}
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-[14px] font-semibold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">smart_toy</span>
                OpenAI
                <span class="text-[10px] text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">Used by: Document Enhancement</span>
            </h4>
            <button type="button" class="btn-outline text-[13px] gap-2" onclick="testOpenAiConnection(this)">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                <span class="btn-text">Verify Key</span>
            </button>
        </div>
        <div id="openai-test-result" class="hidden rounded-lg p-3 text-[13px] font-medium"></div>
    </div>
</div>
