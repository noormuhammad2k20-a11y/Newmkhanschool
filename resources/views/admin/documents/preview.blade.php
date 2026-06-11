@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="mb-lg">
        <h2 class="text-headline-lg font-headline-lg text-primary">Step 3: Preview Document</h2>
        <p class="text-body-md text-secondary">Review the document before generating the PDF. You can make manual adjustments if needed.</p>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
        <form method="POST" action="{{ route('admin.documents.generate') }}">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="template_id" value="{{ $template->id }}">
            <input type="hidden" name="purpose" value="{{ $extra['purpose'] ?? '' }}">

            <div class="mb-md">
                <label class="flex items-center justify-between text-label-md text-on-surface-variant mb-sm">
                    <span>Document Content (HTML)</span>
                    <span class="text-xs text-secondary bg-surface-container-high px-2 py-1 rounded">You can edit the HTML directly below</span>
                </label>
                <textarea name="manual_content" rows="15" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary font-mono text-sm p-md">{{ $content }}</textarea>
            </div>

            <div class="mb-lg border border-outline-variant rounded-lg p-md bg-surface overflow-x-auto shadow-inner">
                <h4 class="text-label-md text-secondary mb-sm uppercase border-b border-outline-variant pb-xs">Live Preview</h4>
                <div class="text-on-surface flex justify-center" style="background-color: #e5e7eb; padding: 20px; border-radius: 8px;">
                    {!! $content !!}
                </div>
            </div>

            <div class="flex justify-between items-center border-t border-outline-variant pt-md">
                <a href="{{ route('admin.documents.select-template', $student->id) }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high transition-colors">Back</a>
                <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-xs">
                    Generate PDF <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
