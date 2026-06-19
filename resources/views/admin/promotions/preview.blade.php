@extends('layouts.app')

@section('title', 'Promotion Preview')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Promotion Preview</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">
                    Reviewing students from <span class="font-bold text-on-surface">{{ $class->name }}</span> 
                    for Academic Year <span class="font-bold text-on-surface">{{ $academicYear->start_date }}</span>
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                    Back to Promotions
                </a>
            </div>
        </div>

@if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Rule Summary -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-2">Applied Rule</h3>
                @if($rule)
                    <p class="text-body-md text-on-surface">
                        Promote to: <strong class="text-primary">{{ $rule->toClass->name }}</strong><br>
                        Min. Percentage: <strong>{{ $rule->min_percentage }}%</strong><br>
                        Min. Attendance: <strong>{{ $rule->min_attendance_pct }}%</strong>
                    </p>
                @else
                    <p class="text-body-md text-orange-600 flex items-center gap-2">
                        <span class="material-symbols-rounded text-[18px]">warning</span>
                        No active rule configured for this class. Defaulting to 40% Marks and 75% Attendance.
                    </p>
                @endif
            </div>
            <div class="flex-1 bg-surface-bright rounded-lg p-4 border border-outline-variant">
                <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-2">Summary</h3>
                <div class="flex gap-4">
                    <div class="text-center">
                        <span class="block text-headline-sm font-headline-sm text-emerald-600">{{ $results->where('is_eligible', true)->count() }}</span>
                        <span class="text-xs text-secondary">Eligible</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-headline-sm font-headline-sm text-red-600">{{ $results->where('is_eligible', false)->count() }}</span>
                        <span class="text-xs text-secondary">Ineligible</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-headline-sm font-headline-sm text-on-surface">{{ $results->count() }}</span>
                        <span class="text-xs text-secondary">Total</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student List & Execute Form -->
        <form action="{{ route('admin.promotions.execute') }}" method="POST">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
            <input type="hidden" name="from_class_id" value="{{ $class->id }}">

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden mb-6">
                <div class="p-md border-b border-outline-variant flex flex-col sm:flex-row sm:justify-between sm:items-center bg-surface-bright gap-4">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Eligible Students</h3>
                    
                    <div class="flex items-center gap-4 bg-surface-container p-2 rounded-lg border border-outline-variant">
                        <div>
                            <label class="text-xs text-secondary mr-2">Target Class:</label>
                            <select name="to_class_id" class="bg-surface-container-lowest border border-outline-variant rounded text-sm px-2 py-1" required>
                                <option value="">-- Select --</option>
                                @foreach($nextClasses as $nc)
                                    <option value="{{ $nc->id }}" {{ ($rule && $rule->to_class_id == $nc->id) ? 'selected' : '' }}>{{ $nc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-secondary mr-2">Target Section:</label>
                            <select name="default_section_id" class="bg-surface-container-lowest border border-outline-variant rounded text-sm px-2 py-1" required>
                                <option value="">-- Select --</option>
                                @php
                                    $allSections = \App\Models\Section::orderBy('name')->get();
                                @endphp
                                @foreach($allSections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-1.5 bg-primary text-on-primary rounded-lg text-label-sm font-label-sm hover:bg-primary-hover shadow-sm transition-colors" data-confirm-click="Are you sure you want to process this bulk promotion?">
                            Execute Promotion
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                <th class="py-3 px-4 font-semibold w-[50px]">
                                    <input type="checkbox" id="selectAll" class="rounded border-outline-variant text-primary focus:ring-primary">
                                </th>
                                <th class="py-3 px-4 font-semibold">Student Name</th>
                                <th class="py-3 px-4 font-semibold">Admission No</th>
                                <th class="py-3 px-4 font-semibold">Current Section</th>
                                <th class="py-3 px-4 font-semibold text-center">Marks %</th>
                                <th class="py-3 px-4 font-semibold text-center">Attendance %</th>
                                <th class="py-3 px-4 font-semibold text-center">Eligibility</th>
                            </tr>
                        </thead>
                        <tbody class="text-body-md font-body-md">
                            @forelse($results as $res)
                                <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors {{ !$res->is_eligible ? 'opacity-60 bg-red-50/20' : '' }}">
                                    <td class="py-3 px-4">
                                        <input type="checkbox" name="student_ids[]" value="{{ $res->student->id }}" class="student-checkbox rounded border-outline-variant text-primary focus:ring-primary" {{ $res->is_eligible ? 'checked' : '' }}>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center font-bold text-xs uppercase">
                                                {{ substr($res->student->first_name, 0, 1) }}
                                            </div>
                                            <span class="text-on-surface font-medium">{{ $res->student->first_name }} {{ $res->student->last_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-secondary">{{ $res->student->admission_no }}</td>
                                    <td class="py-3 px-4 text-secondary">{{ $res->student->currentSection->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="{{ $res->passes_marks ? 'text-emerald-600 font-bold' : 'text-red-600 font-bold' }}">
                                            {{ $res->marks_pct }}%
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="{{ $res->passes_attend ? 'text-emerald-600 font-bold' : 'text-red-600 font-bold' }}">
                                            {{ $res->attendance_pct }}%
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($res->is_eligible)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                <span class="material-symbols-rounded text-[14px] mr-1">check_circle</span>
                                                Eligible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <span class="material-symbols-rounded text-[14px] mr-1">cancel</span>
                                                Ineligible
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-secondary">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-rounded text-4xl mb-3 opacity-50">group_off</span>
                                            <p class="text-lg font-medium text-on-surface mb-1">No active students found in this class</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.student-checkbox');
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
            
            // Set initial state of "select all" based on checkboxes
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const someChecked = Array.from(checkboxes).some(cb => cb.checked);
            selectAll.checked = allChecked && checkboxes.length > 0;
            selectAll.indeterminate = someChecked && !allChecked;
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    const someChecked = Array.from(checkboxes).some(c => c.checked);
                    selectAll.checked = allChecked;
                    selectAll.indeterminate = someChecked && !allChecked;
                });
            });
        }
    });
</script>
@endsection
