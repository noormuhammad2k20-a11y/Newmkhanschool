/**
 * Settings Module — Core JavaScript
 * Newmkhanschool · Vanilla JS · Fetch API
 * Handles: autosave, tabs, toasts, modals, file upload, test connections,
 *          backup polling, health check, audit log, search, dirty tracking
 */

// ═══════════════ GLOBALS ═══════════════
let R = {};
let CSRF = '';
let activeTab = '';
let dirtyFields = new Set();
let healthInterval = null;
let backupPollInterval = null;

// ═══════════════ INITIALIZATION ═══════════════
document.addEventListener('DOMContentLoaded', function () {
    // Initialize config first
    R = window.SETTINGS_ROUTES || {};
    CSRF = window.CSRF_TOKEN || '';

    // Set initial active tab
    const firstTab = document.querySelector('.settings-tab-content');
    if (firstTab) {
        activeTab = firstTab.dataset.tab;
    }

    // Wire all input fields for autosave
    wireAllFields();

    // Wire color sync inputs
    wireColorSync();

    // Wire toggle labels
    wireToggleLabels();

    // Load initial data for active tab
    onTabActivated(activeTab);

    // Warn on page leave with dirty fields
    window.addEventListener('beforeunload', function (e) {
        if (dirtyFields.size > 0) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
});

// ═══════════════ TAB SWITCHING ═══════════════
function switchTab(slug) {
    // Hide all tabs
    document.querySelectorAll('.settings-tab-content').forEach(el => {
        el.classList.add('hidden');
    });

    // Show target tab
    const targetTab = document.getElementById('tab-' + slug);
    if (targetTab) {
        targetTab.classList.remove('hidden');
        // Simple fade-in animation
        targetTab.style.opacity = '0';
        targetTab.style.transform = 'translateY(8px)';
        requestAnimationFrame(() => {
            targetTab.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            targetTab.style.opacity = '1';
            targetTab.style.transform = 'translateY(0)';
        });
    }

    // Update nav buttons
    document.querySelectorAll('.settings-nav-btn').forEach(btn => {
        btn.classList.remove('bg-gradient-to-r', 'from-primary', 'to-[#000444]', 'text-on-primary', 'shadow-md', 'transform', 'scale-[1.02]');
        btn.classList.add('text-secondary', 'hover:bg-surface-container-high', 'hover:translate-x-1');
        
        const icon = btn.querySelector('.icon-wrap .material-symbols-outlined');
        if (icon) { icon.classList.remove('text-white'); icon.classList.add('text-primary'); }
        
        const iconWrap = btn.querySelector('.icon-wrap');
        if (iconWrap) { 
            iconWrap.classList.remove('bg-white/20'); 
            iconWrap.classList.add('bg-surface-container-highest', 'group-hover:bg-white', 'group-hover:shadow-md'); 
        }
        
        const title = btn.querySelector('.nav-title');
        if (title) {
            title.classList.remove('font-bold');
            title.classList.add('font-medium', 'group-hover:text-primary');
        }

        const subtitle = btn.querySelector('.nav-subtitle');
        if (subtitle) {
            subtitle.classList.remove('text-white/70');
            subtitle.classList.add('text-on-surface-variant');
        }
    });

    const activeBtn = document.getElementById('nav-' + slug);
    if (activeBtn) {
        activeBtn.classList.add('bg-gradient-to-r', 'from-primary', 'to-[#000444]', 'text-on-primary', 'shadow-md', 'transform', 'scale-[1.02]');
        activeBtn.classList.remove('text-secondary', 'hover:bg-surface-container-high', 'hover:translate-x-1');
        
        const icon = activeBtn.querySelector('.icon-wrap .material-symbols-outlined');
        if (icon) { icon.classList.add('text-white'); icon.classList.remove('text-primary'); }
        
        const iconWrap = activeBtn.querySelector('.icon-wrap');
        if (iconWrap) { 
            iconWrap.classList.add('bg-white/20'); 
            iconWrap.classList.remove('bg-surface-container-highest', 'group-hover:bg-white', 'group-hover:shadow-md'); 
        }

        const title = activeBtn.querySelector('.nav-title');
        if (title) {
            title.classList.add('font-bold');
            title.classList.remove('font-medium', 'group-hover:text-primary');
        }

        const subtitle = activeBtn.querySelector('.nav-subtitle');
        if (subtitle) {
            subtitle.classList.add('text-white/70');
            subtitle.classList.remove('text-on-surface-variant');
        }
    }

    activeTab = slug;
    onTabActivated(slug);
}

function onTabActivated(slug) {
    if (slug === 'maintenance') {
        loadBackupsList();
        refreshHealthCheck();
        // Poll health every 30s while tab is active
        clearInterval(healthInterval);
        healthInterval = setInterval(refreshHealthCheck, 30000);
    } else {
        clearInterval(healthInterval);
    }
}

// ═══════════════ FIELD WIRING ═══════════════
function wireAllFields() {
    document.querySelectorAll('[data-key]').forEach(input => {
        if (input.tagName === 'BUTTON' || input.tagName === 'DIV') return;

        const type = input.dataset.type || input.type;

        if (type === 'toggle' || input.type === 'checkbox') {
            input.addEventListener('change', function () {
                markDirty(this);
                saveField(this); // instant
            });
        } else if (type === 'color') {
            input.addEventListener('input', function () {
                markDirty(this);
                updateColorPreview(this);
            });
            input.addEventListener('change', function () {
                saveField(this); // save on final pick
            });
        } else {
            // Text, textarea, select, number, email, url, json, secret
            input.addEventListener('input', function () {
                markDirty(this);
                updateFormatPreview(this);
            });
            input.addEventListener('blur', function () {
                if (this.classList.contains('dirty')) {
                    debouncedSave(this);
                }
            });
        }
    });
}

// ═══════════════ DEBOUNCE ═══════════════
const debounce = (fn, delay = 600) => {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
};
const debouncedSave = debounce(saveField, 600);

// ═══════════════ FIELD SAVE ═══════════════
async function saveField(input) {
    const key = input.dataset.key;
    if (!key) return;

    let value;
    if (input.type === 'checkbox') {
        value = input.checked ? '1' : '0';
    } else {
        value = input.value;
    }

    setFieldStatus(key, 'saving');

    try {
        const res = await fetch(R.updateField, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ key, value })
        });

        const data = await res.json();

        if (!res.ok) {
            setFieldStatus(key, 'error', data.message || 'Save failed');
            toast(data.message || 'Validation error', 'error');
            return;
        }

        setFieldStatus(key, 'saved');
        markClean(input);

        if (data.message && !data.unchanged) {
            toast(data.message, 'success');
        }
    } catch (e) {
        setFieldStatus(key, 'error', e.message);
        toast('Network error: ' + e.message, 'error');
    }
}

// ═══════════════ GROUP SAVE ═══════════════
async function saveGroup(slug, formEl) {
    const btn = formEl.querySelector('.save-group-btn');
    const saveIcon = btn.querySelector('.save-icon');
    const spinner = btn.querySelector('.saving-spinner');
    const saveText = btn.querySelector('.save-text');

    saveIcon.classList.add('hidden');
    spinner.classList.remove('hidden');
    saveText.textContent = 'Saving...';
    btn.disabled = true;

    const formData = new FormData();
    const inputs = formEl.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.type === 'file' || !input.name) return;
        if (input.type === 'checkbox') {
            formData.append(input.name, input.checked ? '1' : '0');
        } else {
            formData.append(input.name, input.value);
        }
    });

    try {
        const res = await fetch(R.updateGroup + '/' + slug, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            toast(data.message, 'success');
            // Mark all fields in this tab as clean
            formEl.querySelectorAll('.dirty').forEach(el => markClean(el));
        } else {
            toast(data.message || 'Some fields failed validation', 'error');
            // Show per-field errors
            if (data.errors) {
                Object.entries(data.errors).forEach(([k, msg]) => {
                    setFieldStatus(k, 'error', msg);
                });
            }
        }
    } catch (e) {
        toast('Network error: ' + e.message, 'error');
    } finally {
        saveIcon.classList.remove('hidden');
        spinner.classList.add('hidden');
        saveText.textContent = 'Save All';
        btn.disabled = false;
    }
}

// ═══════════════ FIELD STATUS INDICATOR ═══════════════
function setFieldStatus(key, state, errorMsg) {
    const icon = document.querySelector(`.field-status[data-key="${key}"]`);
    if (!icon) return;

    icon.className = 'field-status ' + state;
    icon.title = errorMsg || '';

    if (state === 'saved') {
        setTimeout(() => {
            icon.className = 'field-status hidden';
        }, 2000);
    }
}

// ═══════════════ DIRTY TRACKING ═══════════════
function markDirty(input) {
    input.classList.add('dirty');
    dirtyFields.add(input.dataset.key);

    // Show tab dirty dot
    const row = input.closest('.settings-tab-content');
    if (row) {
        const tabSlug = row.dataset.tab;
        const dot = document.querySelector(`[data-dirty-tab="${tabSlug}"]`);
        if (dot) dot.classList.remove('hidden');
        const discard = document.querySelector(`[data-discard-tab="${tabSlug}"]`);
        if (discard) discard.classList.remove('hidden');
    }
}

function markClean(input) {
    input.classList.remove('dirty');
    dirtyFields.delete(input.dataset.key);

    // Check if tab still has dirty fields
    const row = input.closest('.settings-tab-content');
    if (row) {
        const tabSlug = row.dataset.tab;
        const hasDirty = row.querySelector('.dirty');
        if (!hasDirty) {
            const dot = document.querySelector(`[data-dirty-tab="${tabSlug}"]`);
            if (dot) dot.classList.add('hidden');
            const discard = document.querySelector(`[data-discard-tab="${tabSlug}"]`);
            if (discard) discard.classList.add('hidden');
        }
    }
}

function discardChanges(slug) {
    const form = document.getElementById('form-' + slug);
    if (form) {
        form.reset();
        form.querySelectorAll('.dirty').forEach(el => markClean(el));
    }
    toast('Changes discarded', 'info');
}

// ═══════════════ TOAST NOTIFICATIONS ═══════════════
function toast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const colors = {
        success: 'bg-[#065f46] border-[#059669]',
        error: 'bg-[#991b1b] border-[#dc2626]',
        info: 'bg-[#1e3a5f] border-[#3b82f6]'
    };
    const icons = {
        success: 'check_circle',
        error: 'error',
        info: 'info'
    };

    const toastEl = document.createElement('div');
    toastEl.className = `${colors[type] || colors.info} px-5 py-3.5 rounded-xl shadow-2xl text-white flex items-center gap-3 min-w-[300px] max-w-[450px] border toast-enter`;
    toastEl.innerHTML = `
        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[18px]">${icons[type] || icons.info}</span>
        </div>
        <div class="flex-1 text-[13px]">${message}</div>
        <button onclick="this.parentElement.remove()" class="text-white/60 hover:text-white ml-2">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    `;

    container.appendChild(toastEl);

    setTimeout(() => {
        toastEl.classList.add('toast-exit');
        setTimeout(() => toastEl.remove(), 200);
    }, 4000);
}

// ═══════════════ CONFIRMATION MODAL ═══════════════
let confirmCallback = null;

function showConfirmModal(title, body, callback) {
    document.getElementById('confirm-modal-title').textContent = title;
    document.getElementById('confirm-modal-body').textContent = body;
    document.getElementById('confirm-modal').classList.remove('hidden');
    confirmCallback = callback;

    document.getElementById('confirm-modal-ok').onclick = function () {
        closeConfirmModal();
        if (confirmCallback) confirmCallback();
    };
}

function closeConfirmModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
    confirmCallback = null;
}

// ═══════════════ FILE UPLOAD ═══════════════
async function uploadImage(event, key) {
    const file = event.target.files[0];
    if (!file) return;
    await doUpload(key, file);
}

function handleFileDrop(event, key) {
    const file = event.dataTransfer.files[0];
    if (!file) return;
    doUpload(key, file);
}

async function doUpload(key, file) {
    const progressWrap = document.querySelector(`[data-progress-key="${key}"]`);
    const progressBar = progressWrap?.querySelector('div > div');

    if (progressWrap) {
        progressWrap.classList.remove('hidden');
        if (progressBar) progressBar.style.width = '10%';
    }

    const fd = new FormData();
    fd.append('key', key);
    fd.append('file', file);

    // Simulate progress
    let progress = 10;
    const interval = setInterval(() => {
        progress = Math.min(progress + 20, 90);
        if (progressBar) progressBar.style.width = progress + '%';
    }, 200);

    try {
        const res = await fetch(R.uploadImage, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: fd
        });
        clearInterval(interval);
        const data = await res.json();

        if (data.success) {
            if (progressBar) progressBar.style.width = '100%';
            toast('File uploaded successfully!', 'success');

            // Update preview image
            const previewId = 'preview-' + key.replace(/\./g, '_');
            const previewImg = document.getElementById(previewId);
            if (previewImg) {
                previewImg.src = data.url + '?t=' + Date.now();
                previewImg.style.display = '';
            }

            // Update sidebar logo live if it's the system logo
            if (key === 'general.system_logo') {
                const sidebarLogo = document.querySelector('nav img[alt="Logo"]');
                if (sidebarLogo) sidebarLogo.src = data.url + '?t=' + Date.now();
            }
        } else {
            toast(data.message || 'Upload failed', 'error');
        }
    } catch (e) {
        clearInterval(interval);
        toast('Upload failed: ' + e.message, 'error');
    } finally {
        setTimeout(() => {
            if (progressWrap) progressWrap.classList.add('hidden');
            if (progressBar) progressBar.style.width = '0%';
        }, 1500);
    }
}

async function removeImage(key) {
    showConfirmModal('Remove Image', 'Are you sure you want to remove this image?', async () => {
        try {
            const res = await fetch(R.removeImage + '/' + key, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                toast(data.message, 'success');
                const wrap = document.querySelector(`.image-preview-wrap[data-key="${key}"]`);
                if (wrap) wrap.remove();
            }
        } catch (e) {
            toast('Remove failed: ' + e.message, 'error');
        }
    });
}

// ═══════════════ PASSWORD TOGGLE ═══════════════
function togglePasswordVisibility(btn) {
    const input = btn.parentElement.querySelector('input');
    const icon = btn.querySelector('.material-symbols-outlined');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility_off';
    }
}

// ═══════════════ COLOR SYNC ═══════════════
function wireColorSync() {
    document.querySelectorAll('[data-sync-color]').forEach(textInput => {
        const key = textInput.dataset.syncColor;
        const colorInput = document.querySelector(`input[type="color"][data-key="${key}"]`);
        if (!colorInput) return;

        textInput.addEventListener('input', function () {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorInput.value = this.value;
                updateColorPreview(colorInput);
            }
        });

        colorInput.addEventListener('input', function () {
            textInput.value = this.value;
        });
    });
}

function updateColorPreview(input) {
    const key = input.dataset.key;
    const swatch = document.querySelector(`[data-swatch="${key}"]`);
    if (swatch) swatch.style.backgroundColor = input.value;

    // Live theme preview for appearance colors
    if (key === 'appearance.primary_color') {
        const sidebar = document.getElementById('theme-preview-sidebar');
        if (sidebar) sidebar.style.backgroundColor = input.value;
    }
    if (key === 'appearance.accent_color') {
        const accent = document.getElementById('theme-preview-accent');
        const bar = document.getElementById('theme-preview-bar');
        if (accent) accent.style.color = input.value;
        if (bar) bar.style.backgroundColor = input.value;
    }
}

// ═══════════════ TOGGLE LABELS ═══════════════
function wireToggleLabels() {
    document.querySelectorAll('[data-type="toggle"]').forEach(input => {
        input.addEventListener('change', function () {
            const label = this.closest('label')?.querySelector('.toggle-label');
            if (label) label.textContent = this.checked ? 'Enabled' : 'Disabled';
        });
    });
}

// ═══════════════ FORMAT PREVIEW ═══════════════
function updateFormatPreview(input) {
    const key = input.dataset.key;

    // Date format preview
    if (key === 'general.date_format') {
        const preview = document.getElementById('date-format-preview');
        if (preview) {
            const now = new Date();
            const formatMap = {
                'd-m-Y': `${pad(now.getDate())}-${pad(now.getMonth()+1)}-${now.getFullYear()}`,
                'm-d-Y': `${pad(now.getMonth()+1)}-${pad(now.getDate())}-${now.getFullYear()}`,
                'Y-m-d': `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}`,
                'd/m/Y': `${pad(now.getDate())}/${pad(now.getMonth()+1)}/${now.getFullYear()}`,
                'm/d/Y': `${pad(now.getMonth()+1)}/${pad(now.getDate())}/${now.getFullYear()}`,
                'D, M d, Y': `${['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][now.getDay()]}, ${['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][now.getMonth()]} ${pad(now.getDate())}, ${now.getFullYear()}`
            };
            const sel = input.tagName === 'SELECT' ? input.value : input.value;
            preview.textContent = formatMap[sel] || sel;
        }
    }

    // Number format previews
    if (key === 'certificate.watermark_opacity') {
        // Could update a live opacity preview here
    }

    // Admission/Roll/Certificate number format preview
    if (key && key.includes('_format') && input.dataset.type !== 'select') {
        const previewEl = document.querySelector(`[data-format-key="${key}"] .format-preview-text`);
        if (previewEl) {
            let preview = input.value || '—';
            preview = preview.replace('{YEAR}', '2026')
                             .replace('{SEQ}', '00001')
                             .replace('{CLASS}', '10')
                             .replace('{BRANCH}', 'MAIN')
                             .replace('{SECTION}', 'A');
            previewEl.textContent = preview;
        }
    }

    // Passing marks live note
    if (key === 'examination.passing_marks') {
        // Could show "Students below X% will be marked Fail"
    }
}

function pad(n) { return n < 10 ? '0' + n : '' + n; }

// ═══════════════ SEARCH ═══════════════
function filterSettings(query) {
    const q = query.toLowerCase().trim();
    let anyVisible = false;

    document.querySelectorAll('.setting-row').forEach(row => {
        const searchText = row.dataset.search || '';
        const visible = !q || searchText.includes(q);
        row.style.display = visible ? '' : 'none';
        if (visible) anyVisible = true;
    });

    // If searching, show the tab that has matches
    if (q) {
        document.querySelectorAll('.settings-tab-content').forEach(tab => {
            const hasVisible = tab.querySelector('.setting-row:not([style*="display: none"])');
            if (hasVisible && tab.classList.contains('hidden')) {
                // Jump to the first tab with results
                switchTab(tab.dataset.tab);
            }
        });
    }

    document.getElementById('no-search-results').classList.toggle('hidden', anyVisible || !q);
}

// ═══════════════ RESET TO DEFAULT ═══════════════
async function resetToDefault(key, btn) {
    showConfirmModal('Reset to Default', `Reset "${key}" to its default value?`, async () => {
        try {
            const res = await fetch(R.reset + '/' + key, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                // Update the input value
                const input = document.querySelector(`[data-key="${key}"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = data.value === '1';
                    } else {
                        input.value = data.value;
                    }
                    markClean(input);
                }
                toast(data.message, 'success');
            }
        } catch (e) {
            toast('Reset failed: ' + e.message, 'error');
        }
    });
}

// ═══════════════ CACHE CLEAR ═══════════════
async function clearCacheAction(btn) {
    const origText = btn.querySelector('.btn-text, span:last-child');
    if (origText) origText.textContent = 'Clearing...';
    btn.disabled = true;

    try {
        const res = await fetch(R.clearCache, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await res.json();
        toast(data.message, data.success ? 'success' : 'error');
    } catch (e) {
        toast('Cache clear failed: ' + e.message, 'error');
    } finally {
        if (origText) origText.textContent = 'Clear Cache';
        btn.disabled = false;
    }
}

// ═══════════════ IMPORT SETTINGS ═══════════════
async function importSettings(event) {
    const file = event.target.files[0];
    if (!file) return;

    showConfirmModal(
        'Import Settings',
        'This will overwrite current settings with the imported values (secrets are excluded). Continue?',
        async () => {
            const fd = new FormData();
            fd.append('file', file);

            try {
                const res = await fetch(R.import, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (data.success) {
                    toast(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toast(data.message, 'error');
                }
            } catch (e) {
                toast('Import failed: ' + e.message, 'error');
            }
        }
    );

    event.target.value = '';
}

// ═══════════════ BACKUP MANAGEMENT ═══════════════
async function runBackupNow(btn) {
    const text = btn.querySelector('.btn-text');
    if (text) text.textContent = 'Running...';
    btn.disabled = true;

    try {
        const res = await fetch(R.backupRun, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await res.json();
        toast(data.message, data.success ? 'success' : 'error');
        loadBackupsList();
    } catch (e) {
        toast('Backup failed: ' + e.message, 'error');
    } finally {
        if (text) text.textContent = 'Run Backup Now';
        btn.disabled = false;
    }
}

async function loadBackupsList() {
    try {
        const res = await fetch(R.backupList, {
            headers: { 'Accept': 'application/json' }
        });
        const backups = await res.json();
        const tbody = document.getElementById('backups-tbody');
        if (!tbody) return;

        if (!backups.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-on-surface-variant">No backups yet</td></tr>';
            return;
        }

        tbody.innerHTML = backups.map(b => `
            <tr class="border-t border-outline-variant/10">
                <td class="px-4 py-3 text-[13px] text-on-surface font-mono">${b.file_path || '—'}</td>
                <td class="px-4 py-3 text-[13px] text-on-surface-variant">${b.file_size}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold
                        ${b.status === 'completed' ? 'bg-[#ecfdf5] text-[#059669]' : b.status === 'running' ? 'bg-[#fffbeb] text-[#d97706]' : 'bg-[#fef2f2] text-[#dc2626]'}">
                        <span class="material-symbols-outlined text-[12px]">${b.status === 'completed' ? 'check_circle' : b.status === 'running' ? 'progress_activity' : 'error'}</span>
                        ${b.status}
                    </span>
                </td>
                <td class="px-4 py-3 text-[13px] text-on-surface-variant">${b.created_at}</td>
                <td class="px-4 py-3 text-right">
                    ${b.status === 'completed' ? `
                        <a href="${R.backupDownload}/${b.id}/download" class="text-primary hover:underline text-[12px] mr-3">Download</a>
                        <button onclick="deleteBackup(${b.id})" class="text-error hover:underline text-[12px]">Delete</button>
                    ` : ''}
                </td>
            </tr>
        `).join('');

        // Poll if any backup is running
        const hasRunning = backups.some(b => b.status === 'running');
        clearInterval(backupPollInterval);
        if (hasRunning) {
            backupPollInterval = setInterval(loadBackupsList, 3000);
        }
    } catch (e) {
        console.error('Failed to load backups:', e);
    }
}

async function deleteBackup(id) {
    showConfirmModal('Delete Backup', 'Are you sure you want to permanently delete this backup?', async () => {
        try {
            const res = await fetch(R.backupDelete + '/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            const data = await res.json();
            toast(data.message, data.success ? 'success' : 'error');
            loadBackupsList();
        } catch (e) {
            toast('Delete failed: ' + e.message, 'error');
        }
    });
}

// ═══════════════ HEALTH CHECK ═══════════════
async function refreshHealthCheck() {
    try {
        const res = await fetch(R.health, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        const container = document.getElementById('health-pills');
        if (!container) return;

        const items = Object.entries(data).map(([name, info]) => {
            const iconMap = { database: 'storage', storage: 'folder', queue: 'queue', cache: 'cached' };
            return `
                <div class="health-pill ${info.status}">
                    <span class="material-symbols-outlined text-[14px]">${info.status === 'ok' ? 'check_circle' : 'error'}</span>
                    ${name.charAt(0).toUpperCase() + name.slice(1)}
                </div>
            `;
        });
        container.innerHTML = items.join('');
    } catch (e) {
        console.error('Health check failed:', e);
    }
}

// ═══════════════ TEST CONNECTIONS ═══════════════
async function testSmtpConnection(btn) {
    const text = btn.querySelector('.btn-text');
    if (text) text.textContent = 'Sending...';
    btn.disabled = true;

    const form = btn.closest('form') || btn.closest('.settings-tab-content');
    const payload = {
        smtp_host: getFieldValue(form, 'notification.smtp_host'),
        smtp_port: getFieldValue(form, 'notification.smtp_port'),
        smtp_username: getFieldValue(form, 'notification.smtp_username'),
        smtp_password: getFieldValue(form, 'notification.smtp_password'),
        test_email: document.getElementById('smtp-test-email')?.value || ''
    };

    await testConnection(R.testSmtp, payload, btn, 'smtp-test-result');
}

async function testSmsConnection(btn) {
    const form = btn.closest('.settings-tab-content');
    const payload = {
        gateway_url: getFieldValue(form, 'notification.sms_gateway_url'),
        api_key: getFieldValue(form, 'notification.sms_api_key')
    };
    await testConnection(R.testSms, payload, btn, 'sms-test-result');
}

async function testWhatsappConnection(btn) {
    const form = btn.closest('.settings-tab-content');
    const payload = {
        api_key: getFieldValue(form, 'notification.whatsapp_api_key')
    };
    await testConnection(R.testWhatsapp, payload, btn, 'whatsapp-test-result');
}

async function testJazzCashConnection(btn) {
    const form = btn.closest('.settings-tab-content');
    const payload = {
        merchant_id: getFieldValue(form, 'api.jazzcash_merchant_id'),
        password: getFieldValue(form, 'api.jazzcash_password')
    };
    await testConnection(R.testJazzCash, payload, btn, 'jazzcash-test-result');
}

async function testEasyPaisaConnection(btn) {
    const form = btn.closest('.settings-tab-content');
    const payload = {
        store_id: getFieldValue(form, 'api.easypaisa_store_id'),
        hash_key: getFieldValue(form, 'api.easypaisa_hash_key')
    };
    await testConnection(R.testEasyPaisa, payload, btn, 'easypaisa-test-result');
}

async function testGeminiConnection(btn) {
    const form = btn.closest('.settings-tab-content');
    const payload = {
        api_key: getFieldValue(form, 'api.gemini_api_key')
    };
    await testConnection(R.testGemini, payload, btn, 'gemini-test-result');
}

async function testOpenAiConnection(btn) {
    const form = btn.closest('.settings-tab-content');
    const payload = {
        api_key: getFieldValue(form, 'api.openai_api_key')
    };
    await testConnection(R.testOpenAi, payload, btn, 'openai-test-result');
}

async function testConnection(endpoint, payload, btn, resultId) {
    const text = btn.querySelector('.btn-text');
    const origText = text?.textContent || 'Test';
    if (text) text.textContent = 'Testing...';
    btn.disabled = true;

    const resultEl = document.getElementById(resultId);

    try {
        const res = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (resultEl) {
            resultEl.classList.remove('hidden');
            resultEl.className = `rounded-lg p-3 text-[13px] font-medium flex items-center gap-2
                ${data.success ? 'bg-[#ecfdf5] text-[#059669] border border-[#059669]/20' : 'bg-[#fef2f2] text-[#dc2626] border border-[#dc2626]/20'}`;
            resultEl.innerHTML = `
                <span class="material-symbols-outlined text-[16px]">${data.success ? 'check_circle' : 'error'}</span>
                ${data.message}
            `;
        }

        toast(data.message, data.success ? 'success' : 'error');
    } catch (e) {
        if (resultEl) {
            resultEl.classList.remove('hidden');
            resultEl.className = 'rounded-lg p-3 text-[13px] font-medium bg-[#fef2f2] text-[#dc2626] border border-[#dc2626]/20';
            resultEl.textContent = 'Connection failed: ' + e.message;
        }
        toast('Test failed: ' + e.message, 'error');
    } finally {
        if (text) text.textContent = origText;
        btn.disabled = false;
    }
}

function getFieldValue(context, key) {
    const input = context?.querySelector(`[data-key="${key}"]`) || document.querySelector(`[data-key="${key}"]`);
    return input?.value || '';
}

// ═══════════════ MAINTENANCE MODE ═══════════════
function toggleMaintenanceMode(enable) {
    const action = enable ? 'enable' : 'disable';
    const msg = enable
        ? 'This will block all non-admin access immediately. Continue?'
        : 'This will restore public access. Continue?';

    showConfirmModal(
        (enable ? 'Enable' : 'Disable') + ' Maintenance Mode',
        msg,
        async () => {
            try {
                const res = await fetch(R.maintenance, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ enable })
                });
                const data = await res.json();
                toast(data.message, data.success ? 'success' : 'error');

                // Toggle banner
                const banner = document.getElementById('maintenance-banner');
                if (banner) {
                    banner.classList.toggle('hidden', !data.enabled);
                }
            } catch (e) {
                toast('Failed: ' + e.message, 'error');
            }
        }
    );
}

// ═══════════════ AUDIT LOG MODAL ═══════════════
function openAuditLogModal() {
    document.getElementById('audit-log-modal').classList.remove('hidden');
    loadAuditLog(1);
}

function closeAuditLogModal() {
    document.getElementById('audit-log-modal').classList.add('hidden');
}

async function loadAuditLog(page = 1) {
    const content = document.getElementById('audit-log-content');
    const pagination = document.getElementById('audit-log-pagination');

    content.innerHTML = '<div class="flex justify-center py-8"><span class="material-symbols-outlined animate-spin text-primary text-[32px]">progress_activity</span></div>';

    try {
        const res = await fetch(R.auditLog + '?page=' + page, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();

        if (!data.data || !data.data.length) {
            content.innerHTML = '<p class="text-center text-on-surface-variant py-8">No audit log entries yet.</p>';
            pagination.innerHTML = '';
            return;
        }

        content.innerHTML = `
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="bg-surface-container">
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">Setting</th>
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">Old Value</th>
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">New Value</th>
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">Changed By</th>
                        <th class="text-left px-4 py-2.5 font-semibold text-on-surface-variant">When</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.data.map(log => `
                        <tr class="border-t border-outline-variant/10">
                            <td class="px-4 py-2.5 font-mono text-[12px] text-on-surface">${log.setting_key}</td>
                            <td class="px-4 py-2.5 text-on-surface-variant max-w-[120px] truncate" title="${(log.old_value || '').replace(/"/g, '&quot;')}">${truncate(log.old_value, 30)}</td>
                            <td class="px-4 py-2.5 text-on-surface max-w-[120px] truncate" title="${(log.new_value || '').replace(/"/g, '&quot;')}">${truncate(log.new_value, 30)}</td>
                            <td class="px-4 py-2.5 text-on-surface-variant">${log.user?.name || 'Unknown'}</td>
                            <td class="px-4 py-2.5 text-on-surface-variant text-[12px]">${formatDate(log.created_at)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

        // Pagination
        if (data.last_page > 1) {
            let paginationHtml = '<div class="flex items-center gap-2">';
            for (let i = 1; i <= data.last_page; i++) {
                paginationHtml += `<button onclick="loadAuditLog(${i})" class="px-3 py-1 rounded-lg text-[12px] ${i === data.current_page ? 'bg-primary text-white' : 'bg-surface-container text-on-surface hover:bg-surface-container-high'}">${i}</button>`;
            }
            paginationHtml += '</div>';
            pagination.innerHTML = paginationHtml;
        } else {
            pagination.innerHTML = '';
        }
    } catch (e) {
        content.innerHTML = '<p class="text-center text-error py-8">Failed to load audit log.</p>';
    }
}

// ═══════════════ IP WHITELIST HELPERS ═══════════════
function addIpTag() {
    const input = document.getElementById('ip-whitelist-input');
    const value = input?.value.trim();
    if (!value) return;

    // Validate IPv4 or CIDR
    const ipv4Regex = /^(\d{1,3}\.){3}\d{1,3}(\/\d{1,2})?$/;
    if (!ipv4Regex.test(value)) {
        toast('Invalid IP format. Use IPv4 or CIDR notation (e.g. 192.168.1.0/24)', 'error');
        return;
    }

    const container = document.getElementById('ip-whitelist-tags');
    if (!container) return;

    // Check for duplicates
    const existing = container.querySelectorAll('.ip-tag');
    for (const tag of existing) {
        if (tag.dataset.ip === value) {
            toast('This IP is already in the whitelist', 'error');
            return;
        }
    }

    const tag = document.createElement('span');
    tag.className = 'ip-tag inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary/10 text-primary text-[12px] font-mono';
    tag.dataset.ip = value;
    tag.innerHTML = `${value} <button type="button" onclick="this.parentElement.remove(); syncIpWhitelist()" class="hover:text-error ml-1"><span class="material-symbols-outlined text-[14px]">close</span></button>`;
    container.appendChild(tag);

    input.value = '';
    syncIpWhitelist();
}

async function addMyCurrentIp() {
    try {
        const res = await fetch(R.myIp, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        const input = document.getElementById('ip-whitelist-input');
        if (input) input.value = data.ip;
        addIpTag();
        toast('Your IP ' + data.ip + ' has been added', 'success');
    } catch (e) {
        toast('Could not detect your IP', 'error');
    }
}

function syncIpWhitelist() {
    const container = document.getElementById('ip-whitelist-tags');
    const tags = container?.querySelectorAll('.ip-tag');
    const ips = Array.from(tags || []).map(t => t.dataset.ip);
    const input = document.querySelector('[data-key="security.ip_whitelist"]');
    if (input) {
        input.value = JSON.stringify(ips);
        markDirty(input);
    }
}

// ═══════════════ UTILITIES ═══════════════
function truncate(str, len) {
    if (!str) return '—';
    return str.length > len ? str.substring(0, len) + '...' : str;
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}
