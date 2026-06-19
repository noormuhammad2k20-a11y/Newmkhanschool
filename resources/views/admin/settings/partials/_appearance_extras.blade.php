{{-- Appearance tab extras: Live theme preview --}}
<div class="p-5 border-t border-outline-variant/20">
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <h4 class="text-[14px] font-semibold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary">preview</span>
            Live Theme Preview
        </h4>

        {{-- Mini sidebar/navbar preview --}}
        <div class="flex rounded-xl overflow-hidden border border-outline-variant/30 h-[200px]">
            {{-- Mini sidebar --}}
            <div class="w-[180px] flex-shrink-0 flex flex-col p-3" id="theme-preview-sidebar"
                 style="background-color: {{ setting('appearance.primary_color', '#000666') }}">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[14px]">school</span>
                    </div>
                    <span class="text-[11px] text-white font-bold">{{ setting('general.app_name', 'School') }}</span>
                </div>
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-md bg-white/20">
                        <span class="material-symbols-outlined text-white text-[14px]">dashboard</span>
                        <span class="text-[10px] text-white font-semibold">Dashboard</span>
                    </div>
                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-md text-white/70">
                        <span class="material-symbols-outlined text-[14px]">school</span>
                        <span class="text-[10px]">Students</span>
                    </div>
                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-md text-white/70">
                        <span class="material-symbols-outlined text-[14px]">person</span>
                        <span class="text-[10px]">Teachers</span>
                    </div>
                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-md text-white/70">
                        <span class="material-symbols-outlined text-[14px]">settings</span>
                        <span class="text-[10px]">Settings</span>
                    </div>
                </div>
            </div>

            {{-- Mini content area --}}
            <div class="flex-1 bg-surface p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-[12px] font-bold text-on-surface">Dashboard</div>
                    <div class="w-6 h-6 rounded-full bg-surface-container"></div>
                </div>
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <div class="rounded-lg p-2 bg-surface-container-lowest border border-outline-variant/20">
                        <div class="text-[9px] text-on-surface-variant">Students</div>
                        <div class="text-[14px] font-bold text-on-surface">1,234</div>
                    </div>
                    <div class="rounded-lg p-2 bg-surface-container-lowest border border-outline-variant/20">
                        <div class="text-[9px] text-on-surface-variant">Teachers</div>
                        <div class="text-[14px] font-bold text-on-surface">56</div>
                    </div>
                    <div class="rounded-lg p-2 bg-surface-container-lowest border border-outline-variant/20">
                        <div class="text-[9px] text-on-surface-variant">Revenue</div>
                        <div class="text-[14px] font-bold" id="theme-preview-accent" style="color: {{ setting('appearance.accent_color', '#059669') }}">₨ 2.5M</div>
                    </div>
                </div>
                <div class="h-3 rounded-full overflow-hidden bg-surface-container">
                    <div class="h-full rounded-full" id="theme-preview-bar" style="width: 75%; background-color: {{ setting('appearance.accent_color', '#059669') }}"></div>
                </div>
            </div>
        </div>
        <p class="text-[11px] text-on-surface-variant mt-3">
            Colors update in real-time as you change them above. Save to apply across the entire admin panel.
        </p>
    </div>
</div>
