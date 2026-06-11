@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="mb-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">Edit Template</h2>
            <p class="text-body-md text-secondary">Customize {{ $template->name }}</p>
        </div>
        <a href="{{ route('admin.documents.templates') }}" class="inline-flex items-center gap-1 px-md py-sm bg-surface-container-high text-on-surface hover:bg-surface-variant rounded-lg text-label-md font-label-md transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back
        </a>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-lg max-w-4xl">
        <form method="POST" action="{{ route('admin.documents.templates.update', $template->id) }}" class="space-y-lg">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div class="space-y-1">
                    <label class="block text-label-md font-label-md text-secondary">Template Name</label>
                    <input type="text" name="name" value="{{ $template->name }}" required
                           class="w-full rounded-lg border border-outline-variant focus:border-primary focus:ring-primary text-body-md px-3 py-2 bg-surface-container-lowest">
                </div>
                
                <div class="space-y-1">
                    <label class="block text-label-md font-label-md text-secondary">Design Type</label>
                    <div class="relative">
                        <select name="design_type" class="w-full rounded-lg border border-outline-variant focus:border-primary focus:ring-primary text-body-md px-3 py-2 bg-surface-container-lowest appearance-none">
                            <option value="classic" {{ $template->design_type == 'classic' ? 'selected' : '' }}>Classic Border</option>
                            <option value="modern" {{ $template->design_type == 'modern' ? 'selected' : '' }}>Modern Clean</option>
                            <option value="elegant" {{ $template->design_type == 'elegant' ? 'selected' : '' }}>Elegant Ribbon</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-secondary">
                            <span class="material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-lg p-md bg-surface-container-low rounded-lg border border-outline-variant">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="has_qr" value="1" {{ $template->has_qr ? 'checked' : '' }}
                               class="peer w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                    </div>
                    <div>
                        <span class="block text-body-md font-medium text-on-surface group-hover:text-primary transition-colors">Include QR Code</span>
                        <span class="block text-label-sm text-secondary">Adds a scannable verification code</span>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="has_signature" value="1" {{ $template->has_signature ? 'checked' : '' }}
                               class="peer w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                    </div>
                    <div>
                        <span class="block text-body-md font-medium text-on-surface group-hover:text-primary transition-colors">Include Digital Signature</span>
                        <span class="block text-label-sm text-secondary">Embeds the principal's signature</span>
                    </div>
                </label>
            </div>

            <div class="space-y-1">
                <label class="block text-label-md font-label-md text-secondary">Content (HTML/Variables)</label>
                <div class="border border-outline-variant rounded-lg overflow-hidden focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-colors">
                    <textarea name="content" rows="12" required
                              class="w-full border-0 focus:ring-0 text-body-md px-4 py-3 bg-surface-container-lowest font-mono text-sm leading-relaxed">{{ $template->content }}</textarea>
                </div>
                <p class="text-label-sm text-secondary mt-2 flex items-start gap-1">
                    <span class="material-symbols-outlined text-[16px] text-primary">info</span>
                    <span><strong>Variables available:</strong> <code class="bg-surface-container-high px-1 py-0.5 rounded text-primary">{{ $template->variables }}</code></span>
                </p>
            </div>

            <div class="pt-sm flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-xl py-2.5 bg-primary text-on-primary hover:bg-primary-hover rounded-lg font-label-md shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">save</span> Save Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
