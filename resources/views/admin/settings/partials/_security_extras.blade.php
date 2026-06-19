{{-- Security tab extras: IP Whitelist helper --}}
<div class="p-5 border-t border-outline-variant/20">
    {{-- IP Whitelist Tag Input Enhancement --}}
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-[14px] font-semibold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">vpn_lock</span>
                IP Whitelist Management
            </h4>
            <button type="button" class="btn-outline text-[12px] gap-1" onclick="addMyCurrentIp()">
                <span class="material-symbols-outlined text-[14px]">my_location</span>
                Add My Current IP
            </button>
        </div>
        <p class="text-[12px] text-on-surface-variant mb-3">
            Enter IP addresses or CIDR ranges. Only listed IPs will be able to access the admin panel when enabled.
        </p>
        <div id="ip-whitelist-tags" class="flex flex-wrap gap-2 min-h-[40px] p-3 bg-surface-container rounded-lg border border-outline-variant/30">
            {{-- Tags populated by JS from the setting value --}}
        </div>
        <div class="flex items-center gap-2 mt-2">
            <input type="text" id="ip-whitelist-input" class="input-field flex-1 text-[13px]"
                   placeholder="Enter IP or CIDR (e.g. 192.168.1.0/24)"
                   onkeydown="if(event.key==='Enter'){event.preventDefault();addIpTag()}" />
            <button type="button" class="btn-outline text-[12px]" onclick="addIpTag()">Add</button>
        </div>
    </div>

    {{-- Password Strength Preview --}}
    <div class="border border-outline-variant/30 rounded-xl p-5 mt-4">
        <h4 class="text-[14px] font-semibold text-on-surface mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary">password</span>
            Password Rules Preview
        </h4>
        <div class="bg-surface-container rounded-lg p-4">
            <p class="text-[12px] text-on-surface-variant mb-2">Sample password check against current rules:</p>
            <div class="font-mono text-[14px] text-on-surface mb-3" id="password-sample">Aa1!@secure</div>
            <div class="space-y-1" id="password-rules-list">
                {{-- Populated by JS --}}
            </div>
        </div>
    </div>
</div>
