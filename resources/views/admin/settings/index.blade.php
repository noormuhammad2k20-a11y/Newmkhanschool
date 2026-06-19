@extends('layouts.app')

@section('content')
<div class="p-4 md:p-6 w-full mx-auto" id="settings-app">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-headline-lg font-headline-lg text-on-surface flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-[#000444] shadow-lg flex items-center justify-center transform -rotate-3 hover:rotate-0 transition-all duration-300">
                    <span class="material-symbols-outlined text-white text-[24px]">settings</span>
                </div>
                Settings
            </h1>
            <p class="text-body-md text-secondary mt-1">Manage your application configuration from one place</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            {{-- Global Search --}}
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[18px]">search</span>
                <input type="text"
                       id="settings-search"
                       class="input-field pl-10 w-64 bg-surface-container-lowest shadow-sm border-outline-variant/30 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                       placeholder="Search settings..."
                       oninput="filterSettings(this.value)" />
            </div>
            {{-- Action Buttons --}}
            <button onclick="clearCacheAction(this)" class="btn-outline text-[13px] gap-2 shadow-sm hover:shadow-md transition-all" id="btn-clear-cache">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
                <span>Clear Cache</span>
            </button>
            <a href="{{ route('admin.settings.export') }}" class="btn-outline text-[13px] gap-2 shadow-sm hover:shadow-md transition-all" download>
                <span class="material-symbols-outlined text-[16px]">download</span> Export
            </a>
            <label class="btn-outline text-[13px] gap-2 cursor-pointer shadow-sm hover:shadow-md transition-all">
                <span class="material-symbols-outlined text-[16px]">upload</span> Import
                <input type="file" accept=".json,.txt" onchange="importSettings(event)" class="hidden" />
            </label>
            <button onclick="openAuditLogModal()" class="btn-outline text-[13px] gap-2 shadow-sm hover:shadow-md transition-all">
                <span class="material-symbols-outlined text-[16px]">history</span> Audit Log
            </button>
        </div>
    </div>

    {{-- Maintenance Mode Banner --}}
    @php $maintenanceEnabled = setting('maintenance.maintenance_mode'); @endphp
    <div id="maintenance-banner" class="{{ $maintenanceEnabled ? '' : 'hidden' }} mb-6 bg-error/10 border border-error/30 rounded-2xl p-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-error/20 flex items-center justify-center animate-pulse">
                <span class="material-symbols-outlined text-error text-[24px]">warning</span>
            </div>
            <div>
                <p class="text-[14px] font-semibold text-error">Maintenance Mode Active</p>
                <p class="text-[12px] text-error/80">Non-admin users cannot access the system.</p>
            </div>
        </div>
        <button onclick="toggleMaintenanceMode(false)" class="btn-primary text-[12px] !bg-error hover:!bg-error/90 gap-1 shadow-md hover:shadow-lg transition-all">
            <span class="material-symbols-outlined text-[16px]">power_settings_new</span> Disable
        </button>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Left: Tab Navigation --}}
        <div class="lg:w-80 flex-shrink-0">
            <div class="bg-surface-container-lowest rounded-3xl border border-primary/10 overflow-hidden sticky top-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="p-5 border-b border-primary/5 bg-gradient-to-r from-surface-container-lowest to-surface-container-low">
                    <h3 class="font-label-md text-[13px] text-primary font-bold uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">category</span> Categories
                    </h3>
                </div>
                <nav class="p-3 max-h-[calc(100vh-200px)] overflow-y-auto space-y-1" id="settings-nav">
                    @foreach($groups as $i => $group)
                        <button onclick="switchTab('{{ $group->slug }}')"
                                id="nav-{{ $group->slug }}"
                                class="settings-nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 text-left group relative overflow-hidden
                                       {{ $i === 0 ? 'bg-gradient-to-r from-primary to-[#000444] text-on-primary shadow-md transform scale-[1.02]' : 'text-secondary hover:bg-surface-container-high hover:translate-x-1' }}">
                            <div class="icon-wrap w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm
                                        {{ $i === 0 ? 'bg-white/20' : 'bg-surface-container-highest group-hover:bg-white group-hover:shadow-md' }}">
                                <span class="material-symbols-outlined text-[20px] transition-colors {{ $i === 0 ? 'text-white' : 'text-primary' }}">
                                    @switch($group->slug)
                                        @case('general') tune @break
                                        @case('school') school @break
                                        @case('certificate') workspace_premium @break
                                        @case('student') person @break
                                        @case('examination') history_edu @break
                                        @case('security') shield @break
                                        @case('notification') notifications @break
                                        @case('appearance') palette @break
                                        @case('maintenance') build @break
                                        @case('api') api @break
                                        @default settings
                                    @endswitch
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="nav-title font-label-md text-[13.5px] block truncate {{ $i === 0 ? 'font-bold' : 'font-medium group-hover:text-primary' }}">{{ $group->name }}</span>
                                <span class="nav-subtitle text-[11px] block truncate mt-0.5 {{ $i === 0 ? 'text-white/70' : 'text-on-surface-variant' }}">
                                    {{ $group->settings->count() }} settings
                                </span>
                            </div>
                            {{-- Dirty indicator --}}
                            <span class="tab-dirty-dot hidden w-2.5 h-2.5 rounded-full bg-error shadow-sm flex-shrink-0" data-dirty-tab="{{ $group->slug }}"></span>
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Right: Settings Content --}}
        <div class="flex-1 min-w-0">
            @foreach($groups as $i => $group)
                <div class="settings-tab-content {{ $i !== 0 ? 'hidden' : '' }}" id="tab-{{ $group->slug }}" data-tab="{{ $group->slug }}">
                    {{-- Group Header Card --}}
                    <div class="bg-surface-container-lowest rounded-3xl border border-primary/10 p-8 shadow-sm hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
                        {{-- Background accent --}}
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>

                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary to-[#000444] shadow-lg flex items-center justify-center transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-white text-[32px]">
                                        @switch($group->slug)
                                            @case('general') tune @break
                                            @case('school') school @break
                                            @case('certificate') workspace_premium @break
                                            @case('student') person @break
                                            @case('examination') history_edu @break
                                            @case('security') shield @break
                                            @case('notification') notifications @break
                                            @case('appearance') palette @break
                                            @case('maintenance') build @break
                                            @case('api') api @break
                                            @default settings
                                        @endswitch
                                    </span>
                                </div>
                                <div>
                                    <h2 class="text-[24px] font-bold text-on-surface tracking-tight">{{ $group->name }}</h2>
                                    <p class="text-[14px] text-secondary mt-1">{{ $group->description }}</p>
                                </div>
                            </div>
                            <span class="text-[12px] font-bold text-primary bg-primary/10 border border-primary/20 px-4 py-1.5 rounded-full shadow-sm">
                                {{ $group->settings->count() }} fields
                            </span>
                        </div>
                    </div>

                    {{-- Settings Form --}}
                    <form onsubmit="event.preventDefault(); saveGroup('{{ $group->slug }}', this)"
                          id="form-{{ $group->slug }}"
                          class="bg-surface-container-lowest rounded-3xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mt-6">

                        <div class="divide-y divide-outline-variant/20">
                            @foreach($group->settings as $setting)
                                @include('admin.settings.partials._field', ['setting' => $setting, 'group' => $group])
                            @endforeach
                        </div>

                        {{-- Tab-specific extra UI --}}
                        @if($group->slug === 'notification')
                            @include('admin.settings.partials._notification_extras', ['group' => $group])
                        @elseif($group->slug === 'maintenance')
                            @include('admin.settings.partials._maintenance_extras', ['group' => $group])
                        @elseif($group->slug === 'api')
                            @include('admin.settings.partials._api_extras', ['group' => $group])
                        @elseif($group->slug === 'appearance')
                            @include('admin.settings.partials._appearance_extras', ['group' => $group])
                        @elseif($group->slug === 'security')
                            @include('admin.settings.partials._security_extras', ['group' => $group])
                        @endif

                        {{-- Save Button Bar --}}
                        <div class="p-5 bg-surface-container-low/30 border-t border-outline-variant/20 flex items-center justify-between">
                            <p class="text-[12px] text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">info</span>
                                Changes are auto-saved on blur. Use "Save All" to batch-save.
                            </p>
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        class="btn-outline text-[13px] gap-1 discard-btn hidden"
                                        data-discard-tab="{{ $group->slug }}"
                                        onclick="discardChanges('{{ $group->slug }}')">
                                    <span class="material-symbols-outlined text-[14px]">undo</span> Discard
                                </button>
                                <button type="submit"
                                        class="btn-primary text-[13px] gap-2 min-w-[140px] save-group-btn"
                                        data-save-tab="{{ $group->slug }}">
                                    <span class="material-symbols-outlined text-[16px] save-icon">save</span>
                                    <span class="material-symbols-outlined text-[16px] saving-spinner hidden animate-spin">progress_activity</span>
                                    <span class="save-text">Save All</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach

            {{-- No Search Results --}}
            <div id="no-search-results" class="hidden bg-surface-container-lowest rounded-xl border border-outline-variant/30 p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-surface-container flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-on-surface-variant text-[28px]">search_off</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">No results found</h3>
                <p class="text-body-md text-secondary mt-2">Try a different search term</p>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ TOAST NOTIFICATION ═══════════════ --}}
<div id="toast-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-2"></div>

{{-- ═══════════════ CONFIRMATION MODAL ═══════════════ --}}
<div id="confirm-modal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-surface-container-lowest rounded-2xl shadow-2xl w-[440px] max-w-[90vw] overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-error/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-error text-[20px]">warning</span>
                </div>
                <h3 id="confirm-modal-title" class="text-headline-md font-headline-md text-on-surface">Confirm Action</h3>
            </div>
            <p id="confirm-modal-body" class="text-body-md text-secondary leading-relaxed"></p>
        </div>
        <div class="px-6 pb-6 flex items-center justify-end gap-3">
            <button onclick="closeConfirmModal()" class="btn-outline text-[13px]">Cancel</button>
            <button id="confirm-modal-ok" class="btn-primary text-[13px] !bg-error hover:!bg-error/90">Confirm</button>
        </div>
    </div>
</div>

{{-- ═══════════════ AUDIT LOG MODAL ═══════════════ --}}
<div id="audit-log-modal" class="fixed inset-0 z-[9998] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAuditLogModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-surface-container-lowest rounded-2xl shadow-2xl w-[800px] max-w-[95vw] max-h-[85vh] overflow-hidden flex flex-col">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary text-[24px]">history</span>
                <h3 class="text-headline-md font-headline-md text-on-surface">Settings Change History</h3>
            </div>
            <button onclick="closeAuditLogModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6" id="audit-log-content">
            <div class="flex justify-center py-8">
                <span class="material-symbols-outlined animate-spin text-primary text-[32px]">progress_activity</span>
            </div>
        </div>
        <div class="p-4 border-t border-outline-variant/20 flex items-center justify-between" id="audit-log-pagination">
        </div>
    </div>
</div>

{{-- ═══════════════ INLINE STYLES ═══════════════ --}}
<style>
    /* Field status indicators */
    .field-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        font-size: 12px;
        transition: all 0.3s ease;
    }
    .field-status.saving {
        display: inline-flex !important;
    }
    .field-status.saving::after {
        content: '';
        width: 14px;
        height: 14px;
        border: 2px solid transparent;
        border-top-color: #000666;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    .field-status.saved {
        display: inline-flex !important;
        color: #059669;
    }
    .field-status.saved::after {
        content: '✓';
        font-weight: bold;
    }
    .field-status.error {
        display: inline-flex !important;
        color: #dc2626;
        cursor: help;
    }
    .field-status.error::after {
        content: '✗';
        font-weight: bold;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Dirty field highlight */
    .input-field.dirty {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.15);
    }

    /* Reset button visibility on hover */
    .setting-row:hover .reset-btn {
        opacity: 1 !important;
    }

    /* Toast animation */
    .toast-enter {
        animation: toastSlideIn 0.3s ease-out;
    }
    .toast-exit {
        animation: toastSlideOut 0.2s ease-in forwards;
    }
    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes toastSlideOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(20px); }
    }

    /* Health status pills */
    .health-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
    }
    .health-pill.ok { background: #ecfdf5; color: #059669; }
    .health-pill.error { background: #fef2f2; color: #dc2626; }
    .health-pill.warning { background: #fffbeb; color: #d97706; }
</style>

{{-- ═══════════════ SETTINGS JS ═══════════════ --}}
<script src="{{ asset('assets/js/settings.js') }}"></script>

{{-- ═══════════════ ROUTES CONFIG ═══════════════ --}}
<script>
    window.SETTINGS_ROUTES = {
        updateField:    '{{ route("admin.settings.updateField") }}',
        updateGroup:    '{{ url("admin/settings/group") }}',
        uploadImage:    '{{ route("admin.settings.uploadImage") }}',
        removeImage:    '{{ url("admin/settings/image") }}',
        testSmtp:       '{{ route("admin.settings.test.smtp") }}',
        testSms:        '{{ route("admin.settings.test.sms") }}',
        testWhatsapp:   '{{ route("admin.settings.test.whatsapp") }}',
        testJazzCash:   '{{ route("admin.settings.test.jazzcash") }}',
        testEasyPaisa:  '{{ route("admin.settings.test.easypaisa") }}',
        testGemini:     '{{ route("admin.settings.test.gemini") }}',
        testOpenAi:     '{{ route("admin.settings.test.openai") }}',
        backupRun:      '{{ route("admin.settings.backup.run") }}',
        backupList:     '{{ route("admin.settings.backup.list") }}',
        backupDownload: '{{ url("admin/settings/backup") }}',
        backupDelete:   '{{ url("admin/settings/backup") }}',
        clearCache:     '{{ route("admin.settings.cache.clear") }}',
        health:         '{{ route("admin.settings.health") }}',
        export:         '{{ route("admin.settings.export") }}',
        import:         '{{ route("admin.settings.import") }}',
        reset:          '{{ url("admin/settings/reset") }}',
        auditLog:       '{{ route("admin.settings.auditLog") }}',
        maintenance:    '{{ route("admin.settings.maintenance.toggle") }}',
        myIp:           '{{ route("admin.settings.myIp") }}',
    };
    window.CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
</script>
@endsection
