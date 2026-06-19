@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="mb-lg">
        <h2 class="text-headline-lg font-headline-lg text-primary">Step 2: Select Document Template</h2>
        <p class="text-body-md text-secondary">Choose the type of document to issue for <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        <!-- Student Info Card -->
        <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm h-fit">
            <h3 class="font-headline-md border-b border-outline-variant pb-sm mb-md">Student Info</h3>
            <ul class="space-y-sm text-body-md text-on-surface">
                <li><strong>Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</li>
                <li><strong>Admn No:</strong> {{ $student->admission_no }}</li>
                <li><strong>Father Name:</strong> {{ $student->father_name ?? 'N/A' }}</li>
                <li><strong>Class:</strong> {{ $student->currentClass?->name ?? 'N/A' }}</li>
            </ul>
        </div>

        <!-- Templates -->
        <div class="lg:col-span-2">
            <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
                <form method="POST" action="{{ route('admin.documents.preview') }}">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    
                    <div class="mb-md">
                        <label class="block text-label-md text-on-surface-variant mb-xs">Select Template</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            @foreach($templates as $template)
                            <label class="border border-outline-variant rounded-lg p-md cursor-pointer hover:border-primary transition-colors flex items-start gap-sm">
                                <input type="radio" name="template_id" value="{{ $template->id }}" required class="mt-1 text-primary focus:ring-primary">
                                <div>
                                    <span class="font-headline-md block text-on-surface">{{ $template->name }}</span>
                                    <span class="text-label-md text-secondary block mt-xs">{{ Str::limit($template->content, 50, '...') }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('template_id') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-md">
                        <label class="block text-label-md text-on-surface-variant mb-xs">Purpose (Optional)</label>
                        <input type="text" name="purpose" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary" placeholder="e.g. For passport application">
                        @error('purpose') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-md">
                        <label class="block text-label-md text-on-surface-variant mb-xs">Academic Year Reference</label>
                        <input type="text" name="academic_year" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary" value="{{ $academicYear ? $academicYear->start_date . ' to ' . $academicYear->end_date : '' }}">
                    </div>

                    <div class="mb-lg">
                        <label class="flex items-center gap-sm cursor-pointer p-md border border-primary-fixed-dim bg-primary-fixed rounded-lg">
                            <input type="checkbox" name="ai_enhance" value="1" class="text-primary focus:ring-primary">
                            <div>
                                <span class="font-headline-md block text-primary font-bold flex items-center gap-xs">
                                    <span class="material-symbols-rounded text-[18px]">auto_awesome</span> AI Enhance Document
                                </span>
                                <span class="text-label-md text-secondary block mt-xs">Improves the tone and professionalism using OpenAI.</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-between items-center border-t border-outline-variant pt-md">
                        <a href="{{ route('admin.documents.create') }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high transition-colors">Back</a>
                        <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-xs">
                            Preview Document <span class="material-symbols-rounded text-[18px]">visibility</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
