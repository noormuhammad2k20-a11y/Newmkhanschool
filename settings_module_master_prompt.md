# Newmkhanschool — Settings Module ("Admin Portal") Full Master Prompt

**System:** Laravel (PHP 8.2) · MariaDB 10.4 · Existing Theme Must Be Preserved
**Repo:** https://github.com/noormuhammad2k20-a11y/Newmkhanschool
**Database:** newschool
**Target page:** Admin → Settings ("Admin Portal")
**Goal:** Take the Settings page from "static form that reloads" to a **fully dynamic, AJAX-driven, zero-page-reload, production-grade Settings control panel** covering all 81 fields across the 10 groups below, using the existing `settings` + `setting_groups` tables.

---

## ⚠️ ABSOLUTE RULES — READ BEFORE EVERYTHING ELSE

1. **Do NOT change the existing theme** — sidebar, navbar, card classes, table classes, button classes, badge patterns, fonts, spacing. Every new element must visually match the rest of the admin panel. Copy patterns from existing working pages.
2. **No page reloads, anywhere, for any action.** Every save, upload, test-connection, backup, cache-clear, import/export must happen via `fetch()` AJAX calls with JSON or `FormData` (for files), with the UI updating in place.
3. **Every one of the 81 fields listed below must be a real, working, validated, persisted control** — not a placeholder. If a field type implies extra behavior (live preview, JSON builder, masking, test button), that behavior is mandatory, not optional polish.
4. **Only Super Admin role may access this page** (route already exists at `admin.settings.index` / `admin.settings.update` restricted via `role:Super Admin` middleware — keep this).
5. **Sensitive values must never be sent back to the browser in plaintext** after the first save (SMTP password, SMS/WhatsApp API keys, JazzCash/EasyPaisa credentials, Gemini/OpenAI keys). Mask them server-side (`••••••1234`) and only decrypt server-side when actually used (sending mail, test connection, payment call).

---

## 1. Existing Database Schema (already in the DB — do NOT recreate, just use it)

```sql
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `setting_group_id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'text',
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);

CREATE TABLE `setting_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'ri-settings-3-line',
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);
```

10 groups already seeded (`general`, `school`, `certificate`, `student`, `examination`, `security`, `notification`, `appearance`, `maintenance`, `api`) and all 81 settings rows already seeded with correct `key`, `type`, `label`, `description`, and `options` (JSON for selects). **Use this data as the single source of truth for rendering the form** — do not hardcode field lists in Blade; loop over `setting_groups` → `settings` ordered by `order`.

### 1.1 Add two supporting tables (new — needed for the enhancements below)

```sql
CREATE TABLE IF NOT EXISTS `settings_audit_log` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_id` BIGINT UNSIGNED NOT NULL,
  `setting_key` VARCHAR(191) NOT NULL,
  `old_value` LONGTEXT NULL,
  `new_value` LONGTEXT NULL,
  `changed_by` INT NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`changed_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings_backups` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` BIGINT NOT NULL DEFAULT 0,
  `type` ENUM('manual','scheduled') NOT NULL DEFAULT 'manual',
  `status` ENUM('completed','failed','running') NOT NULL DEFAULT 'running',
  `created_by` INT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Add to `Setting` model a cast/accessor so any setting whose key is in a `SECRET_KEYS` list (see §6) is encrypted at rest with Laravel's `Crypt::encryptString()` and masked on output.

---

## 2. Backend — Routes

Add to `routes/web.php` inside the existing `admin` + `role:Super Admin` group (keep existing `settings.index` / `settings.update`, add these):

```php
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/',                         [SettingsController::class, 'index'])->name('index');
    Route::post('/field',                   [SettingsController::class, 'updateField'])->name('updateField');     // autosave single field
    Route::post('/group/{slug}',            [SettingsController::class, 'updateGroup'])->name('updateGroup');     // save whole tab at once
    Route::post('/upload-image',            [SettingsController::class, 'uploadImage'])->name('uploadImage');     // logo/favicon/signature/stamp/watermark/login bg
    Route::delete('/image/{key}',           [SettingsController::class, 'removeImage'])->name('removeImage');
    Route::post('/test/smtp',               [SettingsController::class, 'testSmtp'])->name('test.smtp');
    Route::post('/test/sms',                [SettingsController::class, 'testSms'])->name('test.sms');
    Route::post('/test/whatsapp',           [SettingsController::class, 'testWhatsapp'])->name('test.whatsapp');
    Route::post('/test/jazzcash',           [SettingsController::class, 'testJazzCash'])->name('test.jazzcash');
    Route::post('/test/easypaisa',          [SettingsController::class, 'testEasyPaisa'])->name('test.easypaisa');
    Route::post('/test/gemini',             [SettingsController::class, 'testGemini'])->name('test.gemini');
    Route::post('/test/openai',             [SettingsController::class, 'testOpenAi'])->name('test.openai');
    Route::post('/backup/run',              [SettingsController::class, 'runBackupNow'])->name('backup.run');
    Route::get('/backup/list',              [SettingsController::class, 'listBackups'])->name('backup.list');
    Route::get('/backup/{id}/download',     [SettingsController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('/backup/{id}',           [SettingsController::class, 'deleteBackup'])->name('backup.delete');
    Route::post('/cache/clear',             [SettingsController::class, 'clearCache'])->name('cache.clear');
    Route::get('/health',                   [SettingsController::class, 'healthCheck'])->name('health');
    Route::get('/export',                   [SettingsController::class, 'exportSettings'])->name('export');
    Route::post('/import',                  [SettingsController::class, 'importSettings'])->name('import');
    Route::post('/reset/{key}',             [SettingsController::class, 'resetToDefault'])->name('reset');
    Route::get('/audit-log',                [SettingsController::class, 'auditLog'])->name('auditLog');
    Route::post('/maintenance/toggle',      [SettingsController::class, 'toggleMaintenance'])->name('maintenance.toggle');
});
```

All routes return **JSON** (`response()->json([...])`), never a redirect, so the frontend never reloads the page.

---

## 3. Backend — `SettingsController` (key methods to implement)

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingGroup;
use App\Models\SettingsAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Crypt, Cache, Storage, Artisan, Mail};

class SettingsController extends Controller
{
    /** Keys that must be encrypted at rest + masked in the API response */
    private const SECRET_KEYS = [
        'notification.smtp_password',
        'notification.sms_api_key',
        'notification.whatsapp_api_key',
        'api.jazzcash_password',
        'api.jazzcash_salt',
        'api.easypaisa_hash_key',
        'api.gemini_api_key',
        'api.openai_api_key',
    ];

    public function index()
    {
        $groups = SettingGroup::with(['settings' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')->get();

        // mask secrets before sending to the view/JSON
        $groups->each(fn($g) => $g->settings->each(function ($s) {
            if (in_array($s->key, self::SECRET_KEYS) && filled($s->value)) {
                $s->value = $this->mask($s->value);
                $s->is_masked = true;
            }
        }));

        return view('admin.settings.index', compact('groups'));
    }

    /** AJAX: autosave a single field on blur/change (debounced from JS) */
    public function updateField(Request $request)
    {
        $request->validate(['key' => 'required|string', 'value' => 'nullable']);

        $setting = Setting::where('key', $request->key)->firstOrFail();
        $this->validateFieldValue($setting, $request->value);   // type-aware validation, see §5

        $old = $setting->value;
        $newValue = $request->value;

        if (in_array($setting->key, self::SECRET_KEYS) && filled($newValue) && !str_contains($newValue, '••')) {
            $newValue = Crypt::encryptString($newValue);
        }

        // skip overwrite if the masked placeholder was resubmitted unchanged
        if (str_contains((string) $newValue, '••')) {
            return response()->json(['success' => true, 'unchanged' => true]);
        }

        $setting->update(['value' => $newValue]);

        SettingsAuditLog::create([
            'setting_id'  => $setting->id,
            'setting_key' => $setting->key,
            'old_value'   => in_array($setting->key, self::SECRET_KEYS) ? '[hidden]' : $old,
            'new_value'   => in_array($setting->key, self::SECRET_KEYS) ? '[hidden]' : $newValue,
            'changed_by'  => auth()->id(),
            'ip_address'  => $request->ip(),
        ]);

        Cache::forget("setting.{$setting->key}");

        return response()->json(['success' => true, 'message' => "{$setting->label} updated."]);
    }

    /** AJAX: save a whole tab/group at once (Save button at bottom of each tab) */
    public function updateGroup(Request $request, string $slug)
    {
        $group = SettingGroup::where('slug', $slug)->firstOrFail();
        $errors = [];

        foreach ($request->except('_token') as $key => $value) {
            $setting = $group->settings()->where('key', $key)->first();
            if (!$setting) continue;
            try {
                $this->validateFieldValue($setting, $value);
            } catch (\Throwable $e) {
                $errors[$key] = $e->getMessage();
                continue;
            }
            if (in_array($key, self::SECRET_KEYS) && filled($value) && !str_contains($value, '••')) {
                $value = Crypt::encryptString($value);
            }
            if (!str_contains((string) $value, '••')) {
                $setting->update(['value' => $value]);
                Cache::forget("setting.{$key}");
            }
        }

        if ($errors) {
            return response()->json(['success' => false, 'errors' => $errors], 422);
        }

        return response()->json(['success' => true, 'message' => ucfirst($group->name).' saved successfully.']);
    }

    /** AJAX file upload — logo, favicon, signature, stamp, watermark, login bg */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'key'  => 'required|string',
            'file' => 'required|file|max:2048|mimes:png,jpg,jpeg,svg,webp,ico',
        ]);

        $setting = Setting::where('key', $request->key)->firstOrFail();
        $path = $request->file('file')->store('settings', 'public');
        $setting->update(['value' => $path]);

        return response()->json(['success' => true, 'url' => Storage::url($path)]);
    }

    public function testSmtp(Request $request)
    {
        // build a one-off mail config from the form's CURRENT (unsaved) values
        // so the admin can test before saving
        config(['mail.mailers.smtp' => [
            'transport' => 'smtp',
            'host' => $request->smtp_host,
            'port' => $request->smtp_port,
            'username' => $request->smtp_username,
            'password' => $request->smtp_password,
            'encryption' => $request->smtp_port == 465 ? 'ssl' : 'tls',
        ]]);

        try {
            Mail::raw('This is a test email from '.setting('general.app_name').'.', function ($m) use ($request) {
                $m->to($request->test_email ?: auth()->user()->email)
                  ->subject('SMTP Test — Newmkhanschool');
            });
            return response()->json(['success' => true, 'message' => 'Test email sent successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'SMTP test failed: '.$e->getMessage()], 422);
        }
    }

    // testSms(), testWhatsapp(), testJazzCash(), testEasyPaisa(), testGemini(), testOpenAi()
    // → each does a lightweight live API ping (e.g. JazzCash sandbox auth check, OpenAI
    //   /v1/models list call, Gemini models.list call) and returns success/failure JSON.
    //   NEVER log the raw secret. Wrap every external call in try/catch with a 5s timeout.

    public function runBackupNow(Request $request)
    {
        // dispatch a queued job so the AJAX call returns immediately;
        // frontend polls /settings/backup/list every 3s until status = completed
        \App\Jobs\RunDatabaseBackup::dispatch(auth()->id());
        return response()->json(['success' => true, 'message' => 'Backup started.']);
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        return response()->json(['success' => true, 'message' => 'Application cache cleared.']);
    }

    public function healthCheck()
    {
        return response()->json([
            'database' => $this->checkDatabase(),
            'storage'  => $this->checkStorageWritable(),
            'queue'    => $this->checkQueueWorker(),
            'cache'    => $this->checkCacheDriver(),
        ]);
    }

    public function exportSettings()
    {
        $data = Setting::with('group')->get()
            ->reject(fn($s) => in_array($s->key, self::SECRET_KEYS)) // never export secrets
            ->map(fn($s) => ['key' => $s->key, 'value' => $s->value]);

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="settings-export-'.date('Y-m-d').'.json"');
    }

    public function importSettings(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:json']);
        $data = json_decode(file_get_contents($request->file('file')->getRealPath()), true);

        foreach ($data as $row) {
            Setting::where('key', $row['key'])
                ->whereNotIn('key', self::SECRET_KEYS) // never import secrets blindly
                ->update(['value' => $row['value']]);
        }

        Cache::flush();
        return response()->json(['success' => true, 'message' => 'Settings imported successfully.']);
    }

    public function auditLog(Request $request)
    {
        $logs = SettingsAuditLog::with('user:id,name')
            ->latest()->paginate(20);
        return response()->json($logs);
    }

    private function mask(string $value): string
    {
        $decrypted = '';
        try { $decrypted = Crypt::decryptString($value); } catch (\Throwable $e) { $decrypted = $value; }
        $len = strlen($decrypted);
        return $len <= 4 ? str_repeat('•', $len) : str_repeat('•', $len - 4).substr($decrypted, -4);
    }
}
```

---

## 4. Frontend — Page Structure (Blade + Vanilla JS, NO framework reload)

```
resources/views/admin/settings/index.blade.php
resources/views/admin/settings/partials/_field.blade.php      (renders ONE field by type)
resources/views/admin/settings/partials/_general.blade.php
resources/views/admin/settings/partials/_school.blade.php
resources/views/admin/settings/partials/_certificate.blade.php
resources/views/admin/settings/partials/_student.blade.php
resources/views/admin/settings/partials/_examination.blade.php
resources/views/admin/settings/partials/_security.blade.php
resources/views/admin/settings/partials/_notification.blade.php
resources/views/admin/settings/partials/_appearance.blade.php
resources/views/admin/settings/partials/_maintenance.blade.php
resources/views/admin/settings/partials/_api.blade.php
public/assets/js/settings.js
```

`index.blade.php` renders a **left vertical tab nav** (one tab per `setting_group`, icon from `setting_groups.icon`) and a right content pane that swaps tabs **without reload** (just toggle `display`/`active` class — all 10 tabs are rendered once on page load, hidden/shown with JS, so there is zero extra HTTP request for tab switching).

### 4.1 Universal field partial (`_field.blade.php`)

Switch on `$setting->type` and render the right control. Every input gets:
- `data-key="{{ $setting->key }}"`
- `data-type="{{ $setting->type }}"`
- a `.field-status` icon span (spinner → check ✓ → error ✗) next to the label
- `change`/`blur` (text-like) or `input` (color/range) listeners wired in `settings.js`

| `type` in DB | Rendered control |
|---|---|
| `text`, `email`, `url` | `<input>` with native HTML5 validation matching type |
| `textarea` | `<textarea>` auto-grow |
| `number` | `<input type="number">` with min/max from description where applicable |
| `select` | `<select>` populated from `options` JSON |
| `toggle` | Bootstrap/theme-matching switch (`<input type="checkbox" class="form-switch">`), saves `1`/`0` instantly on click (no separate Save needed) |
| `color` | `<input type="color">` paired with a synced text hex input + live swatch preview |
| `image` | Drag-and-drop dropzone + current-image preview thumbnail + "Remove" button (DELETE AJAX) + upload progress bar |
| `json` | Renders a **purpose-built mini builder UI** (see §5 per-field) instead of a raw textarea — raw JSON edit is the fallback "Advanced" toggle only |
| password-like (smtp_password, *_api_key, *_hash_key, jazzcash_password, jazzcash_salt) | `<input type="password">` with an eye-icon show/hide toggle, pre-filled with the masked value, only sent to server if the user actually edits it |

### 4.2 `settings.js` — core behaviors (vanilla JS, fetch API)

```javascript
const csrf = document.querySelector('meta[name="csrf-token"]').content;

function toast(message, type = 'success') {
  // reuse the existing theme's toast/notification component if one exists;
  // otherwise inject a small bootstrap-style toast in the corner
}

function setFieldStatus(input, state) {
  // state: 'saving' | 'saved' | 'error'
  const icon = input.closest('.field-wrap').querySelector('.field-status');
  icon.className = 'field-status ' + state;
}

const debounce = (fn, delay = 600) => {
  let t;
  return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
};

async function saveField(input) {
  const key = input.dataset.key;
  let value = input.type === 'checkbox' ? (input.checked ? '1' : '0') : input.value;

  setFieldStatus(input, 'saving');
  try {
    const res = await fetch('/admin/settings/field', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify({ key, value }),
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Save failed');
    setFieldStatus(input, 'saved');
    markClean(input);
    toast(data.message);
  } catch (e) {
    setFieldStatus(input, 'error');
    toast(e.message, 'error');
  }
}
const debouncedSave = debounce(saveField, 600);

// wire all inputs
document.querySelectorAll('[data-key]').forEach(input => {
  const evt = ['checkbox', 'color', 'range'].includes(input.type) ? 'input' : 'blur';
  input.addEventListener(evt, () => {
    markDirty(input);
    if (input.type === 'checkbox' || input.type === 'color') saveField(input); // instant
    else debouncedSave(input);                                                  // debounced
  });
});

// sticky "Save Tab" bar appears only when a tab has unsaved fields
function markDirty(input) { /* add .dirty class, show sticky bar for current tab */ }
function markClean(input) { /* remove .dirty, hide sticky bar if no dirty fields remain */ }

// Save Tab button → bulk save (collects all fields in the visible tab)
async function saveGroup(slug, formEl) {
  const body = new URLSearchParams(new FormData(formEl));
  const res = await fetch(`/admin/settings/group/${slug}`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    body,
  });
  const data = await res.json();
  res.ok ? toast(data.message) : toast('Some fields failed validation', 'error');
}

// Image upload (drag/drop + click)
async function uploadImage(key, file, previewEl) {
  const fd = new FormData();
  fd.append('key', key);
  fd.append('file', file);
  const res = await fetch('/admin/settings/upload-image', {
    method: 'POST', headers: { 'X-CSRF-TOKEN': csrf }, body: fd,
  });
  const data = await res.json();
  if (data.success) previewEl.src = data.url + '?t=' + Date.now(); // cache-bust
}

// Test-connection buttons (SMTP, SMS, WhatsApp, JazzCash, EasyPaisa, Gemini, OpenAI)
async function testConnection(endpoint, payload, btn) {
  btn.disabled = true; btn.innerText = 'Testing…';
  try {
    const res = await fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: JSON.stringify(payload),
    });
    const data = await res.json();
    toast(data.message, data.success ? 'success' : 'error');
  } finally { btn.disabled = false; btn.innerText = 'Test Connection'; }
}

// warn on tab/browser close if any field is dirty
window.addEventListener('beforeunload', (e) => {
  if (document.querySelector('.dirty')) { e.preventDefault(); e.returnValue = ''; }
});
```

---

## 5. Group-by-Group — What Specifically Must Be Added Beyond a Plain Input

This is the real "enhance" list — every group below needs MORE than a bare text box for at least one of its fields.

### 5.1 General Settings (12 fields)
- **System Logo / Favicon** → drag-drop uploader, instant live preview in the *actual sidebar/navbar* (swap the `<img>` src in the real layout the moment upload succeeds — admin sees the new logo immediately without reload).
- **Timezone / Date Format / Currency / Language** → searchable `<select>` (use a lightweight JS searchable-select, not a giant native dropdown) so 50+ timezones are easy to find.
- **Date Format** → show a **live example** next to the dropdown, e.g. "Preview: 19-06-2026", updating as the user changes the selection (pure JS, no save needed to preview).
- **Language** → on save, show a small note: "Some labels may require a page refresh to apply system-wide" (since full i18n isn't real-time).

### 5.2 School / Organization (10 fields)
- **Official Signature / Official Stamp** → PNG-with-transparency uploader that renders the preview **on a checkerboard background** so the admin can confirm transparency worked.
- **Academic Session / Current Academic Year** → when changed, show a confirmation modal ("This affects all new admissions, fee structures, and reports system-wide. Continue?") before the AJAX save fires, since this is a high-impact field.
- **Additional Contact Info** → rich mini-editor allowing multiple labeled rows (Facebook, WhatsApp, Helpline) saved as JSON internally even though DB type is `textarea` — render as a repeatable key/value row UI on top of the textarea, serialize to a simple `Label: value` line format on save.

### 5.3 Certificate Settings (9 fields)
- **Build a live Certificate Preview pane** (right side of this tab, sticky) that re-renders an actual mini certificate mockup using current header text, footer text, watermark image at the chosen opacity, signature/seal position, and a sample QR code — updates live via JS as any field changes (no save required to preview).
- **Watermark Opacity (%)** → range slider (0–100) synced to a number input, drives the live preview's `opacity` CSS in real time.
- **Signature Position / Seal Position** → instead of plain selects, render a 3x1 visual position-picker (small clickable diagram of a certificate footer with 3 zones) — clicking a zone sets the hidden select value.
- **Enable QR Code** → toggle that shows/hides the Verification URL field with a slide animation, and shows a live-generated sample QR (client-side QR via a small JS QR library, or server pre-rendered) pointing at `{verification_url}/CERT-DEMO-0001`.
- **Certificate Number Format** → live preview text under the input showing the resolved value, e.g. typing `CERT-{YEAR}-{SEQ}` shows "Preview: CERT-2026-00001".

### 5.4 Student Management (5 fields)
- **Admission Number Format / Roll Number Format** → same live-preview-under-input pattern as certificate number format, resolving `{YEAR}`, `{SEQ}`, `{CLASS}`, `{BRANCH}`, `{SECTION}` placeholders against sample data.
- **Promotion Rules** (JSON) → real form builder, not raw JSON: two labeled number inputs ("Minimum Attendance %", "Minimum Passing Marks %") that serialize to the underlying JSON on save. Add an "Advanced (raw JSON)" collapsible for power users.
- **Student Status Options** (JSON array) → tag-input UI (type a status, press Enter, it becomes a removable chip) instead of raw JSON array editing. Prevent duplicate/empty tags client-side.
- **Auto-generate Student IDs** → toggle; when OFF, show an inline warning badge: "Manual entry required — staff must assign admission/roll numbers."

### 5.5 Examination Settings (5 fields)
- **Grading System** (JSON) → a **visual grade-band table builder**: rows of [Grade label] [Min %] [Max %] with an "Add Grade" / remove-row button, client-side validation that ranges don't overlap and cover 0–100 with no gaps, live color-coded preview bar (e.g. a horizontal stacked bar showing each grade's % range in its own color).
- **Exam Types** (JSON array) → same tag-input UI as Student Status Options.
- **Passing Marks (%)** → number input with a live note: "Students below {value}% will be marked Fail" updating as typed.
- **Auto-generate Results** → toggle with explanatory helper text and a confirmation modal on enabling (system-wide behavior change).

### 5.6 User & Security (9 fields)
- **Minimum Password Length / Require Special Characters** → live password-strength preview widget showing a sample "Aa1!" string passing/failing against the current rule set as it changes.
- **Session Timeout / Lockout Duration / Max Login Attempts** → number inputs with inline unit labels ("minutes", "attempts") and sane min/max client validation (e.g. timeout 5–1440 minutes).
- **IP Whitelist** → tag-input UI for individual IPs/CIDR ranges with **client-side IPv4/CIDR format validation** per tag before it's accepted, plus a "Add my current IP" button (server returns `$request->ip()` via a tiny AJAX endpoint) so the admin doesn't lock themselves out.
- **Two-Factor Authentication (2FA)** → toggle that, when turned ON, opens an inline panel explaining it applies to admin accounts and links to (or stubs) a QR-based TOTP setup flow.
- **Enable Audit Logging / Enable Login History** → toggles with a "View Log →" link next to each that opens the Audit Log modal (see §7) filtered to that category, confirming the feature is actually wired to real data, not just a switch that does nothing.

### 5.7 Notification Settings (10 fields)
- **Enable Email Notifications** → toggle reveals the SMTP fields below it with a slide animation when ON, collapses them when OFF (don't just disable — actually hide, to declutter).
- **SMTP Host/Port/Username/Password** → grouped in a bordered "SMTP Configuration" card with a **"Send Test Email"** button + a small "Send to" email input (defaults to logged-in admin's email) that calls `/settings/test/smtp` and shows real success/failure feedback inline (not just a toast — a colored result banner: green "✓ Test email sent to admin@school.com" or red "✗ Connection refused on port 587").
- **SMTP Port** → small inline hint text under the field: "587 = TLS, 465 = SSL, 25 = unencrypted (not recommended)".
- **Enable SMS Notifications / SMS Gateway URL / SMS API Key** → same pattern: collapsible card, **"Send Test SMS"** button with a phone-number input, real AJAX test call.
- **Enable WhatsApp Notifications / WhatsApp API Key** → same pattern: collapsible card, **"Send Test WhatsApp Message"** button.
- All three password/key fields use the show/hide eye-toggle masked input described in §4.1.

### 5.8 Appearance (6 fields)
- **Primary Color / Accent Color** → color picker + hex text input + **live theme preview**: a small mocked mini-sidebar/mini-navbar swatch on the page that updates its CSS variables in real time as colors change (and ideally, since this is the actual live theme, apply the CSS variables to the *real* admin layout on save so the admin instantly sees their whole panel re-themed without reload).
- **Sidebar Style / Dashboard Layout** → instead of plain selects, render small visual thumbnail cards (radio-button style) showing a mini mockup of "Default / Compact / Expanded" so the admin picks visually, not by reading text.
- **Default Dark Mode** → toggle that **live-previews** by actually flipping a `data-theme="dark"` attribute on a preview iframe/panel (or the real `<html>` tag if your theme already supports dark mode) instantly on toggle.
- **Login Page Background** → image uploader with live preview shown inside a mocked mini login-screen frame so the admin can see contrast/legibility before saving.

### 5.9 System Maintenance (8 fields)
- **Auto Backup / Backup Frequency / Backup Retention** → grouped card; when Auto Backup is ON, show next-scheduled-run text computed client-side from frequency.
- **"Run Backup Now" button** (not in the original 8 fields but required functionality to make Auto Backup meaningful) → triggers `/settings/backup/run`, shows a progress spinner, then polls `/settings/backup/list` every 3s and renders a live-updating backups table (filename, size, date, status badge, Download button, Delete button) — all via AJAX, no reload.
- **Maintenance Mode** → toggle that, when switched ON, shows a confirmation modal ("This will block all non-admin access immediately. Continue?") before committing via AJAX, and shows a persistent red banner at the top of the admin panel while active, with a one-click "Disable Maintenance Mode" button in that banner.
- **Maintenance Message** → live preview of exactly what visitors will see, rendered in a small mocked "maintenance page" card.
- **Enable Caching** → toggle + a **"Clear Cache Now"** button next to it that calls `/settings/cache/clear` and shows a spinner → success check.
- **Log Level** → select with inline description per option (Debug = verbose/dev only, Error = production recommended).
- **Enable Health Monitoring** → toggle that, when ON, reveals a small live **System Health widget** (Database ✓, Storage ✓, Queue ✓/✗, Cache ✓) calling `/settings/health` on tab load and every 30s, each item a colored status pill.

### 5.10 API & Integrations (7 fields)
- **JazzCash Merchant ID / Password / Integrity Salt** and **EasyPaisa Store ID / Hash Key** → each gateway in its own bordered card with a **"Test Connection"** button hitting `/settings/test/jazzcash` and `/settings/test/easypaisa` respectively, showing a sandbox-vs-live badge based on whether the merchant ID contains "sandbox".
- **Gemini AI API Key / OpenAI API Key** → masked input with eye-toggle, plus a **"Verify Key"** button that calls the provider's lightweight models-list endpoint server-side and reports back "✓ Valid key — 12 models available" or "✗ Invalid or expired key", and a small badge showing which AI-powered features in the app depend on this key (e.g. "Used by: AI Grading Assistant, Document Enhancement").
- All secret fields here are part of `SECRET_KEYS` (§3) — encrypted at rest, masked on display, never reissued in API responses.

---

## 6. Cross-Cutting Functional Requirements (apply to ALL 81 fields)

- **Per-field autosave**: text/select/textarea fields save 600ms after the user stops typing (debounced `blur`/`input`); toggles, colors, and image uploads save instantly on change.
- **Per-field status indicator**: spinner while saving → green checkmark for 2s → fades, or red ✗ with the validation error shown as a tooltip on hover.
- **Per-tab "unsaved changes" sticky bar**: appears only if any field in the active tab is dirty; has "Save All in this Tab" and "Discard Changes" buttons.
- **Global "Reset to Default" per field**: small reset icon next to each field that calls `/settings/reset/{key}`, restores the seeded default value, and updates the input live.
- **Search box at the top of the page**: filters all 81 fields across all tabs by label/description as the admin types (pure client-side, jump-to-tab on match).
- **Export / Import**: "Export Settings" button downloads a JSON snapshot (secrets excluded); "Import Settings" button uploads a JSON file and applies it via AJAX with a diff-preview confirmation modal before committing.
- **Audit Log modal**: a "View Change History" button opens a modal listing the last 50 settings changes (who, what, when, old→new) paginated via AJAX, sourced from `settings_audit_log`.
- **Validation must be both client-side (instant UX) and server-side (`validateFieldValue()` switching on `$setting->type`/`$setting->key`)** — never trust the browser alone. Server validation errors must surface back into the specific field's `.field-status` as a red ✗ with a tooltip, not a generic alert box.
- **CSRF token** included on every fetch call via the `<meta name="csrf-token">` tag already in the layout's `<head>`.
- **Accessibility**: every input keeps a real `<label for="">`, toggles are real checkboxes (not div-only fakes) so screen readers and keyboard nav work.
- **No inline `onclick=""` spaghetti** — wire everything through `addEventListener` in `settings.js`, using `data-*` attributes to pass context (key, type, endpoint).

---

## 7. UI Components Needed (build once, reuse everywhere)

1. **Toast notification system** (success green / error red / info blue) — small, dismissible, top-right.
2. **Confirmation modal** (reusable, takes title + body + confirm callback) — used for high-impact toggles (Maintenance Mode, Auto-generate Results, Academic Session change).
3. **Tag-input component** — used for IP Whitelist, Student Status Options, Exam Types.
4. **Searchable select component** — used for Timezone, Language.
5. **Image dropzone component** — used for Logo, Favicon, Signature, Stamp, Watermark, Login Background.
6. **JSON-builder table component** — used for Grading System (and reusable for Promotion Rules).
7. **Masked-password input with eye toggle** — used for all 8 `SECRET_KEYS`.
8. **Audit Log modal** with paginated AJAX-loaded table.

Build each as a small, self-contained JS function/class in `settings.js` (or split into `settings/components/*.js` if the project already uses a module bundler — check `vite.config.js` / `package.json` first and match the existing frontend build pattern; don't introduce a new bundler).

---

## 8. Testing Checklist (verify after implementation)

- [ ] Loading `/admin/settings` shows all 10 tabs and all 81 fields, with values matching the `settings` table exactly (secrets shown masked).
- [ ] Editing any text/select/textarea field and clicking away (blur) triggers an AJAX save with no page reload; network tab shows a `POST /admin/settings/field` call, not a full page request.
- [ ] Toggling any switch field saves instantly (no debounce delay) and persists after a manual page refresh.
- [ ] Uploading Logo/Favicon/Signature/Stamp/Watermark/Login Background updates the preview immediately and persists after refresh; old file is not orphaned (optionally delete old file from storage on replace).
- [ ] "Send Test Email" actually delivers a real email using the **current unsaved form values**, not just the last-saved DB values.
- [ ] "Run Backup Now" creates a real downloadable backup file and the backups table updates live via polling.
- [ ] "Clear Cache Now" actually runs `cache:clear`/`config:clear`/`view:clear` and confirms success.
- [ ] Enabling Maintenance Mode shows the confirmation modal, then the persistent banner, and actually blocks non-admin routes (test by visiting any public page in an incognito window).
- [ ] Grading System builder prevents overlapping/gapped ranges and the resulting JSON in the DB is well-formed.
- [ ] IP Whitelist rejects malformed entries client-side and the "Add my current IP" button works.
- [ ] Secret fields (SMTP password, all API keys/hashes) are stored encrypted in the DB (`SELECT value FROM settings WHERE key='notification.smtp_password'` shows ciphertext, not plaintext) and the API never returns them unmasked.
- [ ] Settings Audit Log modal shows real entries after making changes, with correct user attribution.
- [ ] Export downloads a valid JSON file with secrets excluded; Import correctly applies a previously exported file (re-upload it) without errors.
- [ ] Every change made by a Super Admin is reflected live in the relevant part of the actual app where that setting is consumed (e.g., changing Primary Color visibly re-themes the sidebar; changing App Name updates the browser tab title and any header logo text) — at minimum on next page load if true live-apply isn't feasible everywhere.
- [ ] No console errors; no native browser `alert()`/`confirm()` dialogs used anywhere (use the custom toast/modal components instead) so the experience feels like a single-page app.
- [ ] Theme/CSS classes throughout the new Settings page visually match the rest of the admin panel exactly — sidebar, cards, buttons, badges, spacing, fonts.

---

*Generated by Claude Sonnet 4.6 — Settings Module Full Master Upgrade Prompt for Newmkhanschool*
*Database: newschool · 10 groups · 81 fields · PHP 8.2 · Laravel · MariaDB 10.4*
