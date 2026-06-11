@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="mb-lg">
        <h2 class="text-headline-lg font-headline-lg text-primary">Step 1: Select Student</h2>
        <p class="text-body-md text-secondary">Search and filter to find the student you want to issue a document for.</p>
    </div>

    @if(session('error'))
        <div class="mb-md p-md bg-error-container text-on-error-container rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Advanced Filter Panel -->
    <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm mb-lg">
        <form method="GET" action="{{ route('admin.documents.create') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-md items-end">
                <!-- Search Keyword -->
                <div class="lg:col-span-1">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Search</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Name or Admission No..." 
                           class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md">
                </div>

                <!-- Class -->
                <div class="lg:col-span-1">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Class</label>
                    <select name="class_id" id="classSelect" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md">
                        <option value="">All Classes</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}" {{ $class_id == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Section -->
                <div class="lg:col-span-1">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Section</label>
                    <select name="section_id" id="sectionSelect" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md">
                        <option value="">All Sections</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" data-class="{{ $sec->class_id }}" {{ $section_id == $sec->id ? 'selected' : '' }}>
                                {{ $sec->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender -->
                <div class="lg:col-span-1">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Gender</label>
                    <select name="gender" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md">
                        <option value="">All</option>
                        <option value="Male" {{ $gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Status & Submit -->
                <div class="lg:col-span-1 flex flex-col justify-end">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Status</label>
                    <div class="flex gap-2">
                        <select name="status" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md">
                            <option value="">All</option>
                            <option value="Regular" {{ $status == 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Transferred" {{ $status == 'Transferred' ? 'selected' : '' }}>Transferred</option>
                            <option value="Graduated" {{ $status == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                        </select>
                        <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md flex items-center justify-center hover:bg-primary-hover transition-colors" title="Apply Filters">
                            <span class="material-symbols-outlined text-[20px]">filter_alt</span>
                        </button>
                        <a href="{{ route('admin.documents.create') }}" class="px-md py-sm bg-surface-container-high text-on-surface rounded-lg font-label-md flex items-center justify-center hover:bg-surface-variant transition-colors" title="Clear Filters">
                            <span class="material-symbols-outlined text-[20px]">clear</span>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Grid -->
    <div class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm">
        <div class="mb-md flex justify-between items-center">
            <h3 class="font-headline-md text-on-surface">Students Found ({{ $students->total() }})</h3>
        </div>

        @if($students->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-md">
                @foreach($students as $student)
                <div class="border border-outline-variant rounded-lg p-md hover:border-primary transition-colors bg-surface-container-lowest flex flex-col justify-between">
                    <div class="flex items-start gap-md mb-md">
                        <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center font-bold text-lg shrink-0">
                            {{ substr($student->first_name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-headline-md text-on-surface line-clamp-1" title="{{ $student->first_name }} {{ $student->last_name }}">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </h3>
                            <div class="flex flex-wrap gap-2 mt-1">
                                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-surface-container-high rounded text-secondary">
                                    <span class="material-symbols-outlined text-[14px]">badge</span> {{ $student->admission_no }}
                                </span>
                                @if($student->currentClass)
                                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-secondary-container text-on-secondary-container rounded">
                                    <span class="material-symbols-outlined text-[14px]">class</span> {{ $student->currentClass->name }} 
                                    @if($student->currentSection)
                                        ({{ $student->currentSection->name }})
                                    @endif
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto pt-sm border-t border-outline-variant flex justify-end">
                        <a href="{{ route('admin.documents.select-template', $student->id) }}" class="inline-flex items-center gap-xs px-md py-sm bg-primary-container text-on-primary-container rounded-lg font-label-md hover:bg-primary hover:text-on-primary transition-colors w-full justify-center">
                            Select for Document <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-lg flex justify-center">
                {{ $students->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center py-xl text-secondary flex flex-col items-center">
                <span class="material-symbols-outlined text-[48px] mb-sm opacity-50">group_off</span>
                <p class="font-body-lg">No students found matching your filters.</p>
                <p class="text-body-md mt-1">Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('classSelect');
    const sectionSelect = document.getElementById('sectionSelect');
    const originalSections = Array.from(sectionSelect.options);

    function filterSections() {
        const selectedClass = classSelect.value;
        const currentSectionValue = sectionSelect.value;
        
        // Clear current options
        sectionSelect.innerHTML = '';
        
        // Re-add options based on class
        let hasSelectedOption = false;
        
        originalSections.forEach(option => {
            if (option.value === "" || option.getAttribute('data-class') === selectedClass || selectedClass === "") {
                sectionSelect.appendChild(option.cloneNode(true));
                if (option.value === currentSectionValue && currentSectionValue !== "") {
                    hasSelectedOption = true;
                }
            }
        });
        
        // Reset section value if it's no longer valid
        if (!hasSelectedOption && currentSectionValue !== "") {
            sectionSelect.value = "";
        } else {
            sectionSelect.value = currentSectionValue;
        }
    }

    classSelect.addEventListener('change', filterSections);
    
    // Initial filter on page load
    filterSections();
});
</script>
@endsection
