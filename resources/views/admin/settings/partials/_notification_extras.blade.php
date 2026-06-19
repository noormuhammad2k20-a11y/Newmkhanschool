{{-- Notification tab extras: SMTP/SMS/WhatsApp test buttons --}}
<div class="p-5 border-t border-outline-variant/20">
    {{-- SMTP Test --}}
    <div class="border border-outline-variant/30 rounded-xl p-5 mb-4">
        <h4 class="text-[14px] font-semibold text-on-surface mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary">mail</span>
            Test Email (SMTP)
        </h4>
        <div class="flex items-center gap-3 mb-3">
            <input type="email" id="smtp-test-email" class="input-field flex-1" placeholder="Send test to (defaults to your email)" />
            <button type="button" class="btn-primary text-[13px] gap-2 whitespace-nowrap" onclick="testSmtpConnection(this)">
                <span class="material-symbols-outlined text-[16px]">send</span>
                <span class="btn-text">Send Test Email</span>
            </button>
        </div>
        <div id="smtp-test-result" class="hidden rounded-lg p-3 text-[13px] font-medium"></div>
        <p class="text-[11px] text-on-surface-variant mt-2">
            Tests with the <strong>current form values</strong> (even unsaved) so you can verify before saving.
        </p>
    </div>

    {{-- SMS Test --}}
    <div class="border border-outline-variant/30 rounded-xl p-5 mb-4">
        <h4 class="text-[14px] font-semibold text-on-surface mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary">sms</span>
            Test SMS Gateway
        </h4>
        <div class="flex items-center gap-3 mb-3">
            <input type="tel" id="sms-test-phone" class="input-field flex-1" placeholder="Phone number (e.g. +923001234567)" />
            <button type="button" class="btn-outline text-[13px] gap-2 whitespace-nowrap" onclick="testSmsConnection(this)">
                <span class="material-symbols-outlined text-[16px]">send</span>
                <span class="btn-text">Send Test SMS</span>
            </button>
        </div>
        <div id="sms-test-result" class="hidden rounded-lg p-3 text-[13px] font-medium"></div>
    </div>

    {{-- WhatsApp Test --}}
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <h4 class="text-[14px] font-semibold text-on-surface mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary">chat</span>
            Test WhatsApp API
        </h4>
        <div class="flex items-center gap-3 mb-3">
            <input type="tel" id="whatsapp-test-phone" class="input-field flex-1" placeholder="Phone number" />
            <button type="button" class="btn-outline text-[13px] gap-2 whitespace-nowrap" onclick="testWhatsappConnection(this)">
                <span class="material-symbols-outlined text-[16px]">send</span>
                <span class="btn-text">Send Test Message</span>
            </button>
        </div>
        <div id="whatsapp-test-result" class="hidden rounded-lg p-3 text-[13px] font-medium"></div>
    </div>
</div>
