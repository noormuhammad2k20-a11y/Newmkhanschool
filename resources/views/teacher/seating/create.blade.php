@extends('layouts.app')

@section('content')
<div class="px-md py-lg max-w-3xl mx-auto">
    <div class="mb-lg">
        <h2 class="text-headline-lg font-headline-lg text-primary">New Seating Plan</h2>
        <p class="text-body-md text-secondary">Set up a seating grid for one of your assigned classes.</p>
    </div>

    <form method="POST" action="{{ route('teacher.seating.store') }}" class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm">
        @csrf
        
        <div class="mb-md">
            <label class="block text-label-md text-on-surface-variant mb-xs">Plan Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., Exam Seating, Regular Seating" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-md">
            <label class="block text-label-md text-on-surface-variant mb-xs">Class & Section *</label>
            <select name="class_section_id" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                <option value="">Select Class/Section</option>
                @foreach($classes as $class)
                    @foreach($sections->where('class_id', $class->id) as $section)
                        <option value="{{ $class->id }}_{{ $section->id }}" {{ old('class_section_id') == $class->id . '_' . $section->id ? 'selected' : '' }}>
                            {{ $class->name }} - {{ $section->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
            @error('class_section_id') <span class="text-error text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-md mb-lg">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Number of Rows *</label>
                <input type="number" name="rows" value="{{ old('rows', 5) }}" min="1" max="20" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                @error('rows') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Number of Columns *</label>
                <input type="number" name="cols" value="{{ old('cols', 6) }}" min="1" max="20" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                @error('cols') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-lg">
            <label class="block text-label-md text-on-surface-variant mb-xs">Seating Mode</label>
            <select name="mode" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                <option value="Regular" {{ old('mode') == 'Regular' ? 'selected' : '' }}>Regular Seating</option>
                <option value="Exam" {{ old('mode') == 'Exam' ? 'selected' : '' }}>Exam Seating (Separated)</option>
            </select>
            @error('mode') <span class="text-error text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-sm">
            <a href="{{ route('teacher.seating.index') }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high">Cancel</a>
            <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant">Create Grid & Assign Seats</button>
        </div>
    </form>
</div>
@endsection
