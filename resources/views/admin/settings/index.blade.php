@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-[1440px] mx-auto" x-data="settingsManager()">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-headline-lg font-headline-lg text-on-surface flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-[#000444] flex items-center justify-center">
                    <i class="ri-settings-3-line text-white text-[20px]"></i>
                </div>
                Settings
            </h1>
            <p class="text-body-md text-secondary mt-1">Manage your application configuration from one place</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            {{-- Search --}}
            <div class="relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[18px]"></i>
                <input type="text"
                       x-model="searchQuery"
                       @input="filterSettings()"
                       class="input-field pl-10 w-64"
                       placeholder="Search settings..."
                       id="settings-search" />
            </div>
            {{-- Actions --}}
            <button @click="clearCache()" class="btn-outline text-[13px] gap-2" :disabled="cacheClearing">
                <i class="ri-refresh-line" :class="cacheClearing && 'animate-spin'"></i>
                <span x-text="cacheClearing ? 'Clearing...' : 'Clear Cache'"></span>
            </button>
            <a href="{{ route('admin.settings.export') }}" class="btn-outline text-[13px] gap-2">
                <i class="ri-download-line"></i> Export
            </a>
            <label class="btn-outline text-[13px] gap-2 cursor-pointer">
                <i class="ri-upload-line"></i> Import
                <input type="file" accept=".json" @change="importSettings($event)" class="hidden" />
            </label>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Left: Tab Navigation --}}
        <div class="lg:w-72 flex-shrink-0">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 overflow-hidden sticky top-4 shadow-sm">
                <div class="p-4 border-b border-outline-variant/20">
                    <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Categories</h3>
                </div>
                <nav class="py-2 max-h-[calc(100vh-200px)] overflow-y-auto">
                    @foreach($groups as $i => $group)
                        <button @click="switchTab('{{ $group->slug }}')"
                                :class="activeTab === '{{ $group->slug }}'
                                    ? 'bg-primary text-on-primary font-semibold'
                                    : 'text-secondary hover:bg-surface-container-high'"
                                class="w-full flex items-center gap-3 px-4 py-3 transition-all duration-200 text-left group">
                            <div :class="activeTab === '{{ $group->slug }}'
                                    ? 'bg-white/20'
                                    : 'bg-surface-container-high group-hover:bg-surface-container'"
                                 class="w-8 h-8 rounded-lg flex items-center justify-center transition-all">
                                <i class="{{ $group->icon }} text-[18px]"
                                   :class="activeTab === '{{ $group->slug }}' ? 'text-white' : 'text-primary'"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="font-label-md text-[13px] block truncate">{{ $group->name }}</span>
                                <span class="text-[11px] block truncate mt-0.5"
                                      :class="activeTab === '{{ $group->slug }}' ? 'text-white/70' : 'text-on-surface-variant'">
                                    {{ $group->settings->count() }} settings
                                </span>
                            </div>
                            <i class="ri-arrow-right-s-line text-[18px] opacity-0 group-hover:opacity-100 transition-opacity"
                               :class="activeTab === '{{ $group->slug }}' && '!opacity-100'"></i>
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Right: Settings Content --}}
        <div class="flex-1 min-w-0">
            @foreach($groups as $group)
                <div x-show="activeTab === '{{ $group->slug }}'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6">

                    {{-- Group Header Card --}}
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                                    <i class="{{ $group->icon }} text-primary text-[24px]"></i>
                                </div>
                                <div>
                                    <h2 class="text-headline-md font-headline-md text-on-surface">{{ $group->name }}</h2>
                                    <p class="text-body-md text-secondary mt-0.5">{{ $group->description }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-semibold text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-full">
                                    {{ $group->settings->count() }} fields
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Settings Form --}}
                    <form @submit.prevent="saveGroup('{{ $group->slug }}')"
                          id="form-{{ $group->slug }}"
                          class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">

                        <div class="divide-y divide-outline-variant/20">
                            @foreach($group->settings as $setting)
                                <div class="p-5 hover:bg-surface-container-low/50 transition-colors setting-row"
                                     data-search="{{ strtolower($setting->label . ' ' . $setting->description . ' ' . $setting->key) }}"
                                     x-show="isSettingVisible('{{ strtolower($setting->label . ' ' . ($setting->description ?? '') . ' ' . $setting->key) }}')">
                                    <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                                        {{-- Label & Description --}}
                                        <div class="lg:w-2/5 flex-shrink-0">
                                            <label for="setting-{{ $setting->key }}"
                                                   class="font-semibold text-[14px] text-on-surface block">
                                                {{ $setting->label }}
                                            </label>
                                            @if($setting->description)
                                                <p class="text-[12px] text-on-surface-variant mt-1 leading-relaxed">
                                                    {{ $setting->description }}
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Input Field --}}
                                        <div class="lg:w-3/5">
                                            @switch($setting->type)
                                                @case('text')
                                                @case('email')
                                                @case('url')
                                                @case('number')
                                                    <input type="{{ $setting->type }}"
                                                           id="setting-{{ $setting->key }}"
                                                           name="{{ $setting->key }}"
                                                           value="{{ $setting->value }}"
                                                           class="input-field"
                                                           @if($setting->type === 'number') min="0" @endif
                                                           placeholder="Enter {{ strtolower($setting->label) }}" />
                                                    @break

                                                @case('textarea')
                                                    <textarea id="setting-{{ $setting->key }}"
                                                              name="{{ $setting->key }}"
                                                              class="input-field min-h-[80px] resize-y"
                                                              rows="3"
                                                              placeholder="Enter {{ strtolower($setting->label) }}">{{ $setting->value }}</textarea>
                                                    @break

                                                @case('select')
                                                    <select id="setting-{{ $setting->key }}"
                                                            name="{{ $setting->key }}"
                                                            class="input-field">
                                                        @if($setting->options)
                                                            @foreach($setting->options as $opt)
                                                                <option value="{{ $opt['value'] }}"
                                                                        {{ $setting->value === $opt['value'] ? 'selected' : '' }}>
                                                                    {{ $opt['label'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @break

                                                @case('toggle')
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox"
                                                               id="setting-{{ $setting->key }}"
                                                               name="{{ $setting->key }}"
                                                               value="1"
                                                               {{ $setting->value ? 'checked' : '' }}
                                                               class="sr-only peer" />
                                                        <div class="w-11 h-6 bg-outline-variant rounded-full peer
                                                                    peer-checked:after:translate-x-full
                                                                    peer-checked:after:border-white
                                                                    after:content-[''] after:absolute after:top-[2px] after:start-[2px]
                                                                    after:bg-white after:border-gray-300 after:border
                                                                    after:rounded-full after:h-5 after:w-5
                                                                    after:transition-all after:shadow-sm
                                                                    peer-checked:bg-primary transition-colors duration-200">
                                                        </div>
                                                        <span class="ml-3 text-[13px] text-secondary"
                                                              x-text="document.getElementById('setting-{{ $setting->key }}')?.checked ? 'Enabled' : 'Disabled'">
                                                            {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                                        </span>
                                                    </label>
                                                    @break

                                                @case('color')
                                                    <div class="flex items-center gap-3">
                                                        <input type="color"
                                                               id="setting-{{ $setting->key }}"
                                                               name="{{ $setting->key }}"
                                                               value="{{ $setting->value ?: '#000666' }}"
                                                               class="w-12 h-10 rounded-lg border border-outline-variant cursor-pointer p-0.5" />
                                                        <input type="text"
                                                               value="{{ $setting->value ?: '#000666' }}"
                                                               class="input-field w-32 font-mono text-[13px]"
                                                               oninput="document.getElementById('setting-{{ $setting->key }}').value = this.value"
                                                               onchange="document.getElementById('setting-{{ $setting->key }}').value = this.value" />
                                                    </div>
                                                    @break

                                                @case('image')
                                                @case('file')
                                                    <div class="space-y-3">
                                                        {{-- Current Preview --}}
                                                        @if($setting->value)
                                                            <div class="flex items-center gap-3 p-3 bg-surface-container rounded-lg">
                                                                @if($setting->type === 'image')
                                                                    <img src="{{ asset('storage/' . $setting->value) }}"
                                                                         alt="{{ $setting->label }}"
                                                                         class="w-16 h-16 object-contain rounded-lg border border-outline-variant/30"
                                                                         onerror="this.style.display='none'" />
                                                                @endif
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-[12px] text-on-surface truncate">{{ basename($setting->value) }}</p>
                                                                    <p class="text-[11px] text-on-surface-variant">Currently uploaded</p>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        {{-- Drop Zone --}}
                                                        <div class="relative border-2 border-dashed border-outline-variant/50 rounded-xl p-6 text-center
                                                                    hover:border-primary/50 hover:bg-primary/[0.02] transition-all duration-200 cursor-pointer group"
                                                             @dragover.prevent="$event.currentTarget.classList.add('border-primary', 'bg-primary/5')"
                                                             @dragleave="$event.currentTarget.classList.remove('border-primary', 'bg-primary/5')"
                                                             @drop.prevent="handleFileDrop($event, '{{ $setting->key }}')"
                                                             @click="$refs['file_{{ str_replace('.', '_', $setting->key) }}'].click()">
                                                            <input type="file"
                                                                   x-ref="file_{{ str_replace('.', '_', $setting->key) }}"
                                                                   accept="image/*,.ico,.svg"
                                                                   @change="uploadFile($event, '{{ $setting->key }}')"
                                                                   class="hidden" />
                                                            <div class="w-10 h-10 mx-auto rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:bg-primary/20 transition-colors">
                                                                <i class="ri-upload-cloud-2-line text-primary text-[20px]"></i>
                                                            </div>
                                                            <p class="text-[13px] font-medium text-on-surface">
                                                                Drop file here or <span class="text-primary underline">browse</span>
                                                            </p>
                                                            <p class="text-[11px] text-on-surface-variant mt-1">PNG, JPG, SVG, ICO — Max 2MB</p>
                                                        </div>

                                                        {{-- Upload Progress --}}
                                                        <div x-show="uploadProgress['{{ $setting->key }}']" class="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                                                            <div class="bg-primary h-2 rounded-full transition-all duration-300"
                                                                 :style="'width:' + (uploadProgress['{{ $setting->key }}'] || 0) + '%'"></div>
                                                        </div>
                                                    </div>
                                                    @break

                                                @case('json')
                                                    <textarea id="setting-{{ $setting->key }}"
                                                              name="{{ $setting->key }}"
                                                              class="input-field font-mono text-[12px] min-h-[100px] resize-y"
                                                              rows="4"
                                                              placeholder='Enter JSON data'>{{ is_string($setting->value) ? $setting->value : json_encode($setting->value, JSON_PRETTY_PRINT) }}</textarea>
                                                    <p class="text-[11px] text-on-surface-variant mt-1.5 flex items-center gap-1">
                                                        <i class="ri-code-line text-[13px]"></i>
                                                        Enter valid JSON data
                                                    </p>
                                                    @break

                                                @default
                                                    <input type="text"
                                                           id="setting-{{ $setting->key }}"
                                                           name="{{ $setting->key }}"
                                                           value="{{ $setting->value }}"
                                                           class="input-field" />
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Save Button --}}
                        <div class="p-5 bg-surface-container-low/30 border-t border-outline-variant/20 flex items-center justify-between">
                            <p class="text-[12px] text-on-surface-variant flex items-center gap-1.5">
                                <i class="ri-information-line"></i>
                                Changes are saved per section
                            </p>
                            <button type="submit"
                                    class="btn-primary text-[13px] gap-2 min-w-[140px]"
                                    :disabled="saving['{{ $group->slug }}']">
                                <i class="ri-save-line" x-show="!saving['{{ $group->slug }}']"></i>
                                <i class="ri-loader-4-line animate-spin" x-show="saving['{{ $group->slug }}']"></i>
                                <span x-text="saving['{{ $group->slug }}'] ? 'Saving...' : 'Save Changes'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach

            {{-- No results state --}}
            <div x-show="searchQuery && !hasVisibleSettings" class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-full bg-surface-container flex items-center justify-center mb-4">
                    <i class="ri-search-line text-on-surface-variant text-[28px]"></i>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">No results found</h3>
                <p class="text-body-md text-secondary mt-2">Try a different search term</p>
            </div>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         :class="toast.type === 'success' ? 'bg-[#065f46] border-[#059669]' : 'bg-[#991b1b] border-[#dc2626]'"
         class="fixed bottom-6 right-6 z-[9999] px-5 py-3.5 rounded-xl shadow-2xl text-white flex items-center gap-3 min-w-[300px] border"
         style="display:none">
        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
             :class="toast.type === 'success' ? 'bg-white/20' : 'bg-white/20'">
            <i :class="toast.type === 'success' ? 'ri-check-line' : 'ri-error-warning-line'" class="text-[18px]"></i>
        </div>
        <div class="flex-1">
            <p class="text-[13px] font-semibold" x-text="toast.title"></p>
            <p class="text-[12px] text-white/80 mt-0.5" x-text="toast.message"></p>
        </div>
        <button @click="toast.show = false" class="text-white/60 hover:text-white ml-2">
            <i class="ri-close-line text-[18px]"></i>
        </button>
    </div>
</div>

{{-- Alpine.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
function settingsManager() {
    return {
        activeTab: '{{ $groups->first()?->slug ?? "general" }}',
        searchQuery: '',
        hasVisibleSettings: true,
        saving: {},
        cacheClearing: false,
        uploadProgress: {},
        toast: { show: false, type: 'success', title: '', message: '' },

        switchTab(slug) {
            this.activeTab = slug;
        },

        isSettingVisible(searchText) {
            if (!this.searchQuery) return true;
            return searchText.includes(this.searchQuery.toLowerCase());
        },

        filterSettings() {
            this.$nextTick(() => {
                // Check if any settings are visible across all tabs
                const rows = document.querySelectorAll('.setting-row');
                let found = false;
                rows.forEach(row => {
                    const text = row.dataset.search || '';
                    if (text.includes(this.searchQuery.toLowerCase())) {
                        found = true;
                    }
                });
                this.hasVisibleSettings = found;
            });
        },

        async saveGroup(slug) {
            this.saving[slug] = true;

            const form = document.getElementById('form-' + slug);
            const formData = new FormData();

            // Collect all form inputs
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file' || !input.name) return;
                if (input.type === 'checkbox') {
                    formData.append(input.name, input.checked ? '1' : '0');
                } else {
                    formData.append(input.name, input.value);
                }
            });

            try {
                const response = await fetch(`{{ url('admin/settings/group') }}/${slug}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast('success', 'Saved!', data.message);
                } else {
                    this.showToast('error', 'Error', data.message || 'Failed to save settings.');
                }
            } catch (err) {
                this.showToast('error', 'Error', 'Network error. Please try again.');
            } finally {
                this.saving[slug] = false;
            }
        },

        async uploadFile(event, key) {
            const file = event.target.files[0];
            if (!file) return;

            this.uploadProgress[key] = 10;

            const formData = new FormData();
            formData.append('key', key);
            formData.append('file', file);

            try {
                // Simulate progress
                let progress = 10;
                const interval = setInterval(() => {
                    progress = Math.min(progress + 20, 90);
                    this.uploadProgress[key] = progress;
                }, 200);

                const response = await fetch('{{ route("admin.settings.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                clearInterval(interval);
                const data = await response.json();

                if (data.success) {
                    this.uploadProgress[key] = 100;
                    this.showToast('success', 'Uploaded!', data.message);
                    // Reload page after short delay to show new preview
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showToast('error', 'Upload Failed', data.message);
                }
            } catch (err) {
                this.showToast('error', 'Error', 'Upload failed. Please try again.');
            } finally {
                setTimeout(() => { this.uploadProgress[key] = 0; }, 2000);
            }
        },

        handleFileDrop(event, key) {
            event.currentTarget.classList.remove('border-primary', 'bg-primary/5');
            const file = event.dataTransfer.files[0];
            if (!file) return;

            // Create a synthetic event-like object
            const syntheticEvent = { target: { files: [file] } };
            this.uploadFile(syntheticEvent, key);
        },

        async clearCache() {
            this.cacheClearing = true;
            try {
                const response = await fetch('{{ route("admin.settings.clear-cache") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                if (data.success) {
                    this.showToast('success', 'Cache Cleared', data.message);
                }
            } catch (err) {
                this.showToast('error', 'Error', 'Failed to clear cache.');
            } finally {
                this.cacheClearing = false;
            }
        },

        async importSettings(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);

            try {
                const response = await fetch('{{ route("admin.settings.import") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();
                if (data.success) {
                    this.showToast('success', 'Imported!', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showToast('error', 'Import Failed', data.message);
                }
            } catch (err) {
                this.showToast('error', 'Error', 'Import failed. Please try again.');
            }

            event.target.value = '';
        },

        showToast(type, title, message) {
            this.toast = { show: true, type, title, message };
            setTimeout(() => { this.toast.show = false; }, 4000);
        }
    };
}
</script>
@endsection
