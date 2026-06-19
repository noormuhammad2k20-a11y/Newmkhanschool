{{-- Universal field renderer — switch on $setting->type --}}
@php
    $fieldId = 'setting-' . str_replace('.', '_', $setting->key);
    $isSecret = in_array($setting->key, \App\Models\Setting::SECRET_KEYS);
@endphp

<div class="field-wrap p-5 hover:bg-primary/[0.02] transition-all duration-200 setting-row border-b border-primary/5 last:border-b-0"
     data-search="{{ strtolower($setting->label . ' ' . ($setting->description ?? '') . ' ' . $setting->key) }}"
     data-key="{{ $setting->key }}"
     data-group="{{ $group->slug }}">
    <div class="flex flex-col lg:flex-row lg:items-center gap-4">
        {{-- Label & Description --}}
        <div class="lg:w-2/5 flex-shrink-0">
            <div class="flex items-center gap-2">
                <label for="{{ $fieldId }}" class="font-semibold text-[14px] text-on-surface block">
                    {{ $setting->label }}
                </label>
                <span class="field-status hidden" data-key="{{ $setting->key }}"></span>
                {{-- Reset to default button --}}
                <button type="button"
                        class="reset-btn opacity-0 group-hover:opacity-100 transition-opacity text-on-surface-variant hover:text-error"
                        data-key="{{ $setting->key }}"
                        title="Reset to default"
                        onclick="resetToDefault('{{ $setting->key }}', this)">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                </button>
            </div>
            @if($setting->description)
                <p class="text-[12px] text-on-surface-variant mt-1 leading-relaxed">{{ $setting->description }}</p>
            @endif
        </div>

        {{-- Input Field --}}
        <div class="lg:w-3/5 group">
            @if($isSecret)
                {{-- Password/Secret field with eye toggle --}}
                <div class="relative">
                    <input type="password"
                           id="{{ $fieldId }}"
                           name="{{ $setting->key }}"
                           value="{{ $setting->value }}"
                           data-key="{{ $setting->key }}"
                           data-type="secret"
                           class="input-field pr-10 font-mono text-[13px]"
                           placeholder="Enter {{ strtolower($setting->label) }}"
                           autocomplete="off" />
                    <button type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors"
                            onclick="togglePasswordVisibility(this)">
                        <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                    </button>
                </div>

            @else
                @switch($setting->type)
                    @case('text')
                    @case('email')
                    @case('url')
                        <input type="{{ $setting->type }}"
                               id="{{ $fieldId }}"
                               name="{{ $setting->key }}"
                               value="{{ $setting->value }}"
                               data-key="{{ $setting->key }}"
                               data-type="{{ $setting->type }}"
                               class="input-field"
                               placeholder="Enter {{ strtolower($setting->label) }}" />
                        @break

                    @case('number')
                        <div class="flex items-center gap-3">
                            <input type="number"
                                   id="{{ $fieldId }}"
                                   name="{{ $setting->key }}"
                                   value="{{ $setting->value }}"
                                   data-key="{{ $setting->key }}"
                                   data-type="number"
                                   class="input-field w-40"
                                   min="0"
                                   placeholder="0" />
                            @if(str_contains(strtolower($setting->description ?? ''), 'minute'))
                                <span class="text-[12px] text-on-surface-variant font-medium">minutes</span>
                            @elseif(str_contains(strtolower($setting->description ?? ''), 'attempt'))
                                <span class="text-[12px] text-on-surface-variant font-medium">attempts</span>
                            @elseif(str_contains(strtolower($setting->description ?? ''), '%') || str_contains(strtolower($setting->key), 'opacity') || str_contains(strtolower($setting->key), 'passing'))
                                <span class="text-[12px] text-on-surface-variant font-medium">%</span>
                            @elseif(str_contains(strtolower($setting->key), 'retention'))
                                <span class="text-[12px] text-on-surface-variant font-medium">days</span>
                            @endif
                        </div>
                        @break

                    @case('textarea')
                        <textarea id="{{ $fieldId }}"
                                  name="{{ $setting->key }}"
                                  data-key="{{ $setting->key }}"
                                  data-type="textarea"
                                  class="input-field min-h-[80px] resize-y"
                                  rows="3"
                                  placeholder="Enter {{ strtolower($setting->label) }}">{{ $setting->value }}</textarea>
                        @break

                    @case('select')
                        <select id="{{ $fieldId }}"
                                name="{{ $setting->key }}"
                                data-key="{{ $setting->key }}"
                                data-type="select"
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
                        {{-- Live preview for date format --}}
                        @if($setting->key === 'general.date_format')
                            <p class="text-[11px] text-primary mt-1.5 font-medium date-format-preview">
                                Preview: <span id="date-format-preview">{{ now()->format($setting->value ?: 'd-m-Y') }}</span>
                            </p>
                        @endif
                        @break

                    @case('toggle')
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   id="{{ $fieldId }}"
                                   name="{{ $setting->key }}"
                                   value="1"
                                   {{ $setting->value ? 'checked' : '' }}
                                   data-key="{{ $setting->key }}"
                                   data-type="toggle"
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
                            <span class="ml-3 text-[13px] text-secondary toggle-label">
                                {{ $setting->value ? 'Enabled' : 'Disabled' }}
                            </span>
                        </label>
                        @break

                    @case('color')
                        <div class="flex items-center gap-3">
                            <input type="color"
                                   id="{{ $fieldId }}"
                                   name="{{ $setting->key }}"
                                   value="{{ $setting->value ?: '#000666' }}"
                                   data-key="{{ $setting->key }}"
                                   data-type="color"
                                   class="w-12 h-10 rounded-lg border border-outline-variant cursor-pointer p-0.5" />
                            <input type="text"
                                   value="{{ $setting->value ?: '#000666' }}"
                                   data-sync-color="{{ $setting->key }}"
                                   class="input-field w-32 font-mono text-[13px]"
                                   maxlength="7"
                                   placeholder="#000000" />
                            <div class="w-10 h-10 rounded-lg border border-outline-variant/50 color-swatch"
                                 data-swatch="{{ $setting->key }}"
                                 style="background-color: {{ $setting->value ?: '#000666' }}"></div>
                        </div>
                        @break

                    @case('image')
                    @case('file')
                        <div class="space-y-3">
                            {{-- Current Preview --}}
                            @if($setting->value)
                                <div class="flex items-center gap-3 p-3 bg-surface-container rounded-lg image-preview-wrap" data-key="{{ $setting->key }}">
                                    @if(str_contains($setting->key, 'signature') || str_contains($setting->key, 'stamp'))
                                        {{-- Checkerboard background for transparency --}}
                                        <div class="w-16 h-16 rounded-lg border border-outline-variant/30 flex items-center justify-center"
                                             style="background-image: linear-gradient(45deg, #e0e0e0 25%, transparent 25%),
                                                    linear-gradient(-45deg, #e0e0e0 25%, transparent 25%),
                                                    linear-gradient(45deg, transparent 75%, #e0e0e0 75%),
                                                    linear-gradient(-45deg, transparent 75%, #e0e0e0 75%);
                                                    background-size: 10px 10px;
                                                    background-position: 0 0, 0 5px, 5px -5px, -5px 0px;">
                                            <img src="{{ asset('storage/' . $setting->value) }}"
                                                 alt="{{ $setting->label }}"
                                                 class="max-w-[60px] max-h-[60px] object-contain"
                                                 id="preview-{{ str_replace('.', '_', $setting->key) }}"
                                                 onerror="this.style.display='none'" />
                                        </div>
                                    @else
                                        <img src="{{ asset('storage/' . $setting->value) }}"
                                             alt="{{ $setting->label }}"
                                             class="w-16 h-16 object-contain rounded-lg border border-outline-variant/30"
                                             id="preview-{{ str_replace('.', '_', $setting->key) }}"
                                             onerror="this.style.display='none'" />
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[12px] text-on-surface truncate">{{ basename($setting->value) }}</p>
                                        <p class="text-[11px] text-on-surface-variant">Currently uploaded</p>
                                    </div>
                                    <button type="button"
                                            class="text-error hover:bg-error/10 p-1.5 rounded-lg transition-colors"
                                            onclick="removeImage('{{ $setting->key }}')"
                                            title="Remove image">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            @endif

                            {{-- Drop Zone --}}
                            <div class="relative border-2 border-dashed border-outline-variant/50 rounded-xl p-6 text-center
                                        hover:border-primary/50 hover:bg-primary/[0.02] transition-all duration-200 cursor-pointer group dropzone"
                                 data-key="{{ $setting->key }}"
                                 ondragover="event.preventDefault(); this.classList.add('border-primary', 'bg-primary/5')"
                                 ondragleave="this.classList.remove('border-primary', 'bg-primary/5')"
                                 ondrop="event.preventDefault(); this.classList.remove('border-primary', 'bg-primary/5'); handleFileDrop(event, '{{ $setting->key }}')"
                                 onclick="this.querySelector('input[type=file]').click()">
                                <input type="file"
                                       accept="image/*,.ico,.svg"
                                       data-upload-key="{{ $setting->key }}"
                                       onchange="uploadImage(event, '{{ $setting->key }}')"
                                       class="hidden" />
                                <div class="w-10 h-10 mx-auto rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:bg-primary/20 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-[20px]">cloud_upload</span>
                                </div>
                                <p class="text-[13px] font-medium text-on-surface">
                                    Drop file here or <span class="text-primary underline">browse</span>
                                </p>
                                <p class="text-[11px] text-on-surface-variant mt-1">PNG, JPG, SVG, ICO — Max 2MB</p>
                            </div>

                            {{-- Upload Progress --}}
                            <div class="upload-progress hidden" data-progress-key="{{ $setting->key }}">
                                <div class="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                                    <div class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        @break

                    @case('json')
                        {{-- JSON fields get special treatment per key in tab partials --}}
                        {{-- This is the fallback raw JSON editor --}}
                        <div class="json-field-wrap" data-key="{{ $setting->key }}">
                            <textarea id="{{ $fieldId }}"
                                      name="{{ $setting->key }}"
                                      data-key="{{ $setting->key }}"
                                      data-type="json"
                                      class="input-field font-mono text-[12px] min-h-[100px] resize-y"
                                      rows="4"
                                      placeholder="Enter valid JSON data">{{ is_string($setting->value) ? $setting->value : json_encode($setting->value, JSON_PRETTY_PRINT) }}</textarea>
                            <p class="text-[11px] text-on-surface-variant mt-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">code</span>
                                Enter valid JSON data
                            </p>
                        </div>
                        @break

                    @default
                        <input type="text"
                               id="{{ $fieldId }}"
                               name="{{ $setting->key }}"
                               value="{{ $setting->value }}"
                               data-key="{{ $setting->key }}"
                               data-type="text"
                               class="input-field" />
                @endswitch
            @endif

            {{-- Live preview for format fields --}}
            @if(str_contains($setting->key, '_format') && $setting->type === 'text')
                <p class="text-[11px] text-primary mt-1.5 font-medium format-preview" data-format-key="{{ $setting->key }}">
                    Preview: <span class="format-preview-text">—</span>
                </p>
            @endif
        </div>
    </div>
</div>
