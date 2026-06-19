{{-- Maintenance tab extras: Backup table, Health widget --}}
<div class="p-5 border-t border-outline-variant/20 space-y-5">

    {{-- Run Backup Now --}}
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-[14px] font-semibold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">backup</span>
                Database Backups
            </h4>
            <button type="button" class="btn-primary text-[13px] gap-2" onclick="runBackupNow(this)" id="btn-run-backup">
                <span class="material-symbols-outlined text-[16px]">play_arrow</span>
                <span class="btn-text">Run Backup Now</span>
            </button>
        </div>

        {{-- Backups Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]" id="backups-table">
                <thead>
                    <tr class="bg-surface-container">
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">File</th>
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">Size</th>
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">Status</th>
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">Created</th>
                        <th class="text-right px-4 py-2.5 font-semibold text-on-surface-variant">Actions</th>
                    </tr>
                </thead>
                <tbody id="backups-tbody">
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
                            <span class="ml-2">Loading backups...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- System Health Widget --}}
    <div class="border border-outline-variant/30 rounded-xl p-5" id="health-widget">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-[14px] font-semibold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">monitor_heart</span>
                System Health
            </h4>
            <button type="button" class="text-[12px] text-primary font-semibold hover:underline" onclick="refreshHealthCheck()">
                Refresh
            </button>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="health-pills">
            <div class="health-pill ok">
                <span class="material-symbols-outlined text-[14px]">progress_activity</span>
                Loading...
            </div>
        </div>
    </div>

    {{-- Clear Cache Button --}}
    <div class="border border-outline-variant/30 rounded-xl p-5">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-[14px] font-semibold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-primary">cached</span>
                    Application Cache
                </h4>
                <p class="text-[12px] text-on-surface-variant mt-1">Clear config, view, and application cache.</p>
            </div>
            <button type="button" class="btn-outline text-[13px] gap-2" onclick="clearCacheAction(this)">
                <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                <span class="btn-text">Clear Cache Now</span>
            </button>
        </div>
    </div>
</div>
