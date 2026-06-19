@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-lg">

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- PAGE HEADER --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-end gap-md">
            <div>
                <div class="flex items-center gap-sm mb-1">
                    <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center">
                        <span class="material-symbols-rounded text-[22px]">design_services</span>
                    </div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Edit Template</h2>
                </div>
                <p class="text-body-lg font-body-lg text-secondary ml-[52px]">Customize the layout and structure for <strong>{{ $template->name }}</strong></p>
            </div>
            <div class="flex items-center gap-sm flex-wrap">
                <a href="{{ route('admin.documents.templates') }}" class="inline-flex items-center gap-xs px-md py-sm border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                    Back to Templates
                </a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- MAIN CONTENT --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <form method="POST" action="{{ route('admin.documents.templates.update', $template->id) }}" class="grid grid-cols-1 xl:grid-cols-3 gap-lg">
            @csrf
            @method('PUT')

            {{-- LEFT: Editor Column (Span 2) --}}
            <div class="xl:col-span-2 flex flex-col gap-lg">
                
                {{-- Template Content Editor --}}
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col">
                    <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center justify-between">
                        <div class="flex items-center gap-sm">
                            <span class="material-symbols-rounded text-primary text-[20px]">code</span>
                            <h3 class="text-headline-md font-headline-md text-on-surface">Template Content (HTML)</h3>
                        </div>
                        <div class="flex items-center gap-xs text-xs text-secondary bg-surface-container px-2 py-1 rounded">
                            <span class="material-symbols-rounded text-[14px]">html</span>
                            Accepts HTML & Inline CSS
                        </div>
                    </div>
                    <div class="flex-1 bg-surface-container-lowest p-0">
                        <textarea name="content" rows="22" required spellcheck="false"
                                  class="w-full border-0 focus:ring-0 text-body-md p-md bg-[#1e1e1e] text-[#d4d4d4] font-mono text-[13px] leading-relaxed resize-y outline-none"
                                  style="min-height: 500px;">{{ $template->content }}</textarea>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Settings & Variables Column --}}
            <div class="flex flex-col gap-lg">
                
                {{-- Template Settings --}}
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
                    <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-sm">
                        <span class="material-symbols-rounded text-primary text-[20px]">settings</span>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Template Settings</h3>
                    </div>
                    <div class="p-md space-y-md">
                        
                        {{-- Name --}}
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Template Name <span class="text-error">*</span></label>
                            <input type="text" name="name" value="{{ $template->name }}" required
                                   class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md bg-surface-container-lowest">
                        </div>
                        
                        {{-- Design Type --}}
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Base Design Style</label>
                            <select name="design_type" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md bg-surface-container-lowest">
                                <option value="classic" {{ $template->design_type == 'classic' ? 'selected' : '' }}>Classic Border (Standard)</option>
                                <option value="modern" {{ $template->design_type == 'modern' ? 'selected' : '' }}>Modern Clean (Minimalist)</option>
                                <option value="elegant" {{ $template->design_type == 'elegant' ? 'selected' : '' }}>Elegant Ribbon (Premium)</option>
                            </select>
                        </div>

                        <div class="pt-2 pb-2 border-y border-outline-variant space-y-3">
                            {{-- QR Code Toggle --}}
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="relative flex items-center mt-0.5">
                                    <input type="checkbox" name="has_qr" value="1" {{ $template->has_qr ? 'checked' : '' }}
                                           class="peer w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary bg-surface-container-lowest">
                                </div>
                                <div>
                                    <span class="block text-label-md font-label-md text-on-surface group-hover:text-primary transition-colors">Include QR Code</span>
                                    <span class="block text-xs text-secondary mt-0.5">Appends a scannable verification code to the bottom right.</span>
                                </div>
                            </label>

                            {{-- Signature Toggle --}}
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="relative flex items-center mt-0.5">
                                    <input type="checkbox" name="has_signature" value="1" {{ $template->has_signature ? 'checked' : '' }}
                                           class="peer w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary bg-surface-container-lowest">
                                </div>
                                <div>
                                    <span class="block text-label-md font-label-md text-on-surface group-hover:text-primary transition-colors">Include Digital Signature</span>
                                    <span class="block text-xs text-secondary mt-0.5">Embeds the authorized signatory's image automatically.</span>
                                </div>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-sm">
                            <button type="submit" class="w-full py-2.5 bg-primary text-on-primary hover:bg-on-primary-fixed-variant rounded-lg font-label-lg transition-colors flex items-center justify-center gap-xs shadow-sm">
                                <span class="material-symbols-rounded text-[18px]">save</span> Save Changes
                            </button>
                        </div>
                    </div>
                </div>



            </div>
        </form>

    </div>
</main>

<script>
// Enable tab character in textarea
document.querySelector('textarea[name="content"]').addEventListener('keydown', function(e) {
    if (e.key == 'Tab') {
        e.preventDefault();
        var start = this.selectionStart;
        var end = this.selectionEnd;
        // set textarea value to: text before caret + tab + text after caret
        this.value = this.value.substring(0, start) + "\t" + this.value.substring(end);
        // put caret at right position again
        this.selectionStart = this.selectionEnd = start + 1;
    }
});
</script>
@endsection
