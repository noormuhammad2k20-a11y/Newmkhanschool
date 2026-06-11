@extends('layouts.app')

@section('content')
<div class="container max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-xl text-center">
        <h2 class="text-headline-lg font-headline-lg text-primary mb-2">New Seating Plan</h2>
        <p class="text-body-lg text-secondary">Set up a seating grid for one of your assigned classes.</p>
    </div>

    <form method="POST" action="{{ route('teacher.seating.store') }}" class="bg-surface border border-outline-variant rounded-2xl shadow-sm overflow-hidden">
        @csrf
        
        <div class="p-lg lg:p-xl space-y-xl">
            <!-- Basic Information Section -->
            <section>
                <h3 class="text-title-md font-semibold text-on-surface mb-md flex items-center gap-xs pb-sm border-b border-outline-variant">
                    <span class="material-symbols-outlined text-[20px] text-primary">info</span>
                    Basic Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <div>
                        <label class="block text-label-md font-medium text-on-surface mb-xs">Plan Name <span class="text-error">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., Midterm Exam Seating" required class="w-full rounded-lg border-outline-variant bg-surface focus:border-primary focus:ring-primary transition-colors">
                        @error('name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-label-md font-medium text-on-surface mb-xs">Class & Section <span class="text-error">*</span></label>
                        <select name="class_section_id" required class="w-full rounded-lg border-outline-variant bg-surface focus:border-primary focus:ring-primary transition-colors">
                            <option value="">Select Class/Section</option>
                            @foreach($classes as $class)
                                @foreach($sections->where('class_id', $class->id) as $section)
                                    <option value="{{ $class->id }}_{{ $section->id }}" {{ old('class_section_id') == $class->id . '_' . $section->id ? 'selected' : '' }}>
                                        {{ $class->name }} - {{ $section->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('class_section_id') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <!-- Grid Configuration Section -->
            <section>
                <h3 class="text-title-md font-semibold text-on-surface mb-md flex items-center gap-xs pb-sm border-b border-outline-variant">
                    <span class="material-symbols-outlined text-[20px] text-primary">grid_on</span>
                    Grid Configuration
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                    <div>
                        <label class="block text-label-md font-medium text-on-surface mb-xs">Number of Rows <span class="text-error">*</span></label>
                        <input type="number" name="rows" value="{{ old('rows', 5) }}" min="1" max="20" required class="w-full rounded-lg border-outline-variant bg-surface focus:border-primary focus:ring-primary transition-colors">
                        @error('rows') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-label-md font-medium text-on-surface mb-xs">Number of Columns <span class="text-error">*</span></label>
                        <input type="number" name="cols" value="{{ old('cols', 6) }}" min="1" max="20" required class="w-full rounded-lg border-outline-variant bg-surface focus:border-primary focus:ring-primary transition-colors">
                        @error('cols') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-label-md font-medium text-on-surface mb-xs">Seating Mode</label>
                        <select name="mode" class="w-full rounded-lg border-outline-variant bg-surface focus:border-primary focus:ring-primary transition-colors">
                            <option value="Regular" {{ old('mode') == 'Regular' ? 'selected' : '' }}>Regular Seating</option>
                            <option value="Exam" {{ old('mode') == 'Exam' ? 'selected' : '' }}>Exam Seating (Separated)</option>
                        </select>
                        @error('mode') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-sm p-sm bg-surface-container-lowest rounded-lg border border-outline-variant text-body-sm text-secondary flex gap-sm">
                    <span class="material-symbols-outlined text-[18px] text-primary shrink-0">lightbulb</span>
                    <p>Exam mode will automatically attempt to place an empty seat between students when using the Auto Arrange feature.</p>
                </div>
            </section>
        </div>

        <div class="px-lg py-md bg-surface-container-lowest border-t border-outline-variant flex items-center justify-between">
            <a href="{{ route('teacher.seating.index') }}" class="px-md py-sm text-secondary hover:text-on-surface font-label-md transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-xl py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant shadow-sm transition-colors flex items-center gap-xs">
                Create Grid & Continue
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </div>
    </form>
</div>
@endsection
