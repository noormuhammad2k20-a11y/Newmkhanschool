@extends('layouts.app')

@section('title', 'Create Promotion Batch')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-3xl mx-auto space-y-xl">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.promotions.batches.index') }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:text-primary transition-colors">
                <span class="material-symbols-rounded">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Create Auto Promotion Batch</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Generate a new batch for approval</p>
            </div>
        </div>

        @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-lg flex items-start gap-3">
            <span class="material-symbols-rounded mt-0.5 text-[20px]">error</span>
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <form action="{{ route('admin.promotions.batches.store') }}" method="POST" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 space-y-6">
            @csrf

            <div>
                <h3 class="text-title-lg font-title-lg text-on-surface mb-4">Source Criteria</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">From Academic Session <span class="text-red-500">*</span></label>
                        <select name="from_academic_year_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                                    {{ $year->year ?? ($year->start_date . ' – ' . $year->end_date) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">From Class <span class="text-red-500">*</span></label>
                        <select name="from_class_id" id="from_class_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors" required>
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('from_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">From Section (Optional)</label>
                        <select name="from_section_id" id="from_section_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors">
                            <option value="">-- All Sections --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="h-px bg-outline-variant"></div>

            <div>
                <h3 class="text-title-lg font-title-lg text-on-surface mb-4">Target Destination</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">To Academic Session <span class="text-red-500">*</span></label>
                        <select name="to_academic_year_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors" required>
                            <option value="">-- Select Session --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('to_academic_year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year ?? ($year->start_date . ' – ' . $year->end_date) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">To Class <span class="text-red-500">*</span></label>
                        <select name="to_class_id" id="to_class_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors" required>
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('to_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">To Section (Optional)</label>
                        <select name="to_section_id" id="to_section_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors">
                            <option value="">-- Auto/Same Section --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-outline-variant">
                <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-all flex items-center gap-2">
                    <span class="material-symbols-rounded text-[18px]">magic_button</span>
                    Generate Batch
                </button>
            </div>
        </form>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function fetchSections(classId, targetSelectId) {
        const select = document.getElementById(targetSelectId);
        select.innerHTML = '<option value="">-- Loading --</option>';
        fetch(`{{ route('admin.promotions.get-sections') }}?class_id=${classId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            select.innerHTML = targetSelectId === 'from_section_id' ? '<option value="">-- All Sections --</option>' : '<option value="">-- Auto/Same Section --</option>';
            if (data.sections && data.sections.length > 0) {
                data.sections.forEach(s => {
                    select.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                });
            }
        });
    }

    document.getElementById('from_class_id').addEventListener('change', function() {
        if(this.value) fetchSections(this.value, 'from_section_id');
    });

    document.getElementById('to_class_id').addEventListener('change', function() {
        if(this.value) fetchSections(this.value, 'to_section_id');
    });
});
</script>
@endsection
