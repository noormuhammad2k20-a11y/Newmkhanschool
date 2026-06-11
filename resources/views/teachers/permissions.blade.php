@extends('layouts.app')

@section('title', 'Manage Teacher Permissions')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-background pb-24">
    <div class="max-w-[1000px] mx-auto relative">
        <!-- Header -->
        <div class="mb-lg flex flex-col md:flex-row md:items-center justify-between border-b border-outline-variant pb-md gap-4">
            <div class="flex items-center gap-md">
                @php
                    $names = explode(' ', $teacher->full_name);
                    $initials = strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
                @endphp
                <div class="w-14 h-14 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-headline-sm shrink-0 shadow-sm border border-primary/20">
                    {{ $initials }}
                </div>
                <div>
                    <h2 class="text-headline-lg font-headline-lg text-on-surface">Manage Permissions</h2>
                    <p class="text-body-md font-body-md text-secondary mt-xs">{{ $teacher->full_name }} • {{ $teacher->employee_number ?? 'Teacher' }}</p>
                </div>
            </div>
            <div class="flex gap-sm shrink-0">
                <a href="{{ route('admin.teachers') }}" class="text-secondary font-label-md text-label-md px-md py-sm hover:bg-surface-container-low hover:text-primary rounded-DEFAULT border border-outline-variant transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Directory
                </a>
            </div>
        </div>



<form action="{{ route('admin.teachers.permissions.update', $teacher->id) }}" method="POST" id="permissions-form">
            @csrf
            
            <div class="flex flex-col gap-xl">
                <!-- Modules Section -->
                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden transition-all hover:shadow-md">
                    <div class="bg-surface-container-lowest p-md border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-sm">
                        <div>
                            <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">apps</span>
                                System Access
                            </h3>
                            <p class="text-body-sm text-secondary mt-1">Select which modules and pages this teacher can access.</p>
                        </div>
                        <button type="button" class="select-all-btn text-primary text-label-md font-label-md hover:bg-primary-container hover:text-on-primary-container px-3 py-1.5 rounded-md transition-colors border border-transparent hover:border-primary/20 shrink-0" data-target="modules[]">
                            Select All
                        </button>
                    </div>
                    <div class="p-0">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 divide-y md:divide-y-0 border-t border-outline-variant">
                            @foreach($modules as $key => $name)
                            <label class="flex items-center justify-between cursor-pointer hover:bg-surface-container-lowest p-md transition-colors group md:border-b md:border-r border-outline-variant last:border-r-0">
                                <span class="text-body-md font-medium text-on-surface select-none group-hover:text-primary transition-colors">{{ $name }}</span>
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="modules[]" value="{{ $key }}" class="peer sr-only" 
                                    @if(in_array($key, $assignedModules)) checked @endif>
                                    <div class="w-10 h-5 bg-surface-container-high rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary border border-outline-variant"></div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Academic Assignments Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg mb-4">
                    
                    <!-- Classes -->
                    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col h-[500px] transition-all hover:shadow-md">
                        <div class="bg-surface-container-lowest p-md border-b border-outline-variant shrink-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-sm mb-3">
                                <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">meeting_room</span>
                                    Assigned Classes
                                </h3>
                                <button type="button" class="select-all-btn text-primary text-label-md font-label-md hover:bg-primary-container hover:text-on-primary-container px-3 py-1.5 rounded-md transition-colors border border-transparent hover:border-primary/20 shrink-0" data-target="classes[]">
                                    Select All
                                </button>
                            </div>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px] group-focus-within:text-primary transition-colors">search</span>
                                <input type="text" class="search-input w-full bg-surface border border-outline-variant rounded-lg py-2 pl-10 pr-4 text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="Search classes..." data-list="classes-list">
                            </div>
                        </div>
                        <div class="p-0 overflow-y-auto flex-1 bg-surface-container-lowest/30 custom-scrollbar" id="classes-list">
                            <div class="divide-y divide-outline-variant">
                                @foreach($classes as $class)
                                <label class="flex items-center gap-4 cursor-pointer hover:bg-surface-container-low p-md transition-colors list-item-row group">
                                    <input type="checkbox" name="classes[]" value="{{ $class->id }}" class="rounded text-primary focus:ring-primary focus:ring-2 w-5 h-5 border-outline transition-all cursor-pointer"
                                    @if(in_array($class->id, $assignedClassIds)) checked @endif>
                                    <span class="text-body-md font-medium text-on-surface select-none item-text group-hover:text-primary transition-colors">{{ $class->name }}</span>
                                </label>
                                @endforeach
                            </div>
                            @if($classes->isEmpty())
                                <div class="p-lg flex flex-col items-center justify-center h-full text-secondary opacity-70">
                                    <span class="material-symbols-outlined text-display-sm mb-2">meeting_room</span>
                                    <span class="text-body-md">No classes available.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Subjects -->
                    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col h-[500px] transition-all hover:shadow-md">
                        <div class="bg-surface-container-lowest p-md border-b border-outline-variant shrink-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-sm mb-3">
                                <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">book</span>
                                    Assigned Subjects
                                </h3>
                                <button type="button" class="select-all-btn text-primary text-label-md font-label-md hover:bg-primary-container hover:text-on-primary-container px-3 py-1.5 rounded-md transition-colors border border-transparent hover:border-primary/20 shrink-0" data-target="subjects[]">
                                    Select All
                                </button>
                            </div>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px] group-focus-within:text-primary transition-colors">search</span>
                                <input type="text" class="search-input w-full bg-surface border border-outline-variant rounded-lg py-2 pl-10 pr-4 text-body-md focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="Search subjects..." data-list="subjects-list">
                            </div>
                        </div>
                        <div class="p-0 overflow-y-auto flex-1 bg-surface-container-lowest/30 custom-scrollbar" id="subjects-list">
                            <div class="divide-y divide-outline-variant">
                                @foreach($subjects as $subject)
                                <label class="flex items-center gap-4 cursor-pointer hover:bg-surface-container-low p-md transition-colors list-item-row group">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" class="rounded text-primary focus:ring-primary focus:ring-2 w-5 h-5 border-outline transition-all cursor-pointer"
                                    @if(in_array($subject->id, $assignedSubjectIds)) checked @endif>
                                    <div class="flex flex-col">
                                        <span class="text-body-md font-medium text-on-surface select-none item-text group-hover:text-primary transition-colors">{{ $subject->name }}</span>
                                        <span class="text-label-sm font-label-sm text-secondary select-none mt-0.5">{{ $subject->code }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @if($subjects->isEmpty())
                                <div class="p-lg flex flex-col items-center justify-center h-full text-secondary opacity-70">
                                    <span class="material-symbols-outlined text-display-sm mb-2">book</span>
                                    <span class="text-body-md">No subjects available.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- Floating Action Bar -->
            <div class="sticky bottom-6 bg-surface/95 backdrop-blur-md p-4 rounded-xl border border-outline-variant shadow-lg z-20 flex justify-end gap-md mt-6">
                <a href="{{ route('admin.teachers') }}" class="text-secondary font-label-md text-label-md px-lg py-2 hover:bg-surface-container-low rounded-lg border border-transparent hover:border-outline-variant transition-all">Cancel</a>
                <button type="submit" class="bg-primary text-on-primary font-label-md text-label-md px-xl py-2 rounded-lg hover:bg-primary-container hover:text-on-primary-container hover:shadow-md transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Save Permissions
                </button>
            </div>
        </form>
    </div>
</main>

<style>
    /* Custom thin scrollbar for lists */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 10px;
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.8);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select All Logic
        document.querySelectorAll('.select-all-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetName = this.getAttribute('data-target');
                // Only toggle visible checkboxes
                const checkboxes = Array.from(document.querySelectorAll(`input[name="${targetName}"]`)).filter(cb => {
                    const row = cb.closest('.list-item-row') || cb.closest('label');
                    return !row || row.style.display !== 'none';
                });
                
                const allChecked = checkboxes.every(cb => cb.checked);
                checkboxes.forEach(cb => {
                    cb.checked = !allChecked;
                });
                this.textContent = allChecked ? 'Deselect All' : 'Select All';
            });
        });

        // Update Select All text on load and checkbox change
        function updateSelectAllText(targetName) {
            const btn = document.querySelector(`.select-all-btn[data-target="${targetName}"]`);
            if(!btn) return;
            const checkboxes = Array.from(document.querySelectorAll(`input[name="${targetName}"]`)).filter(cb => {
                const row = cb.closest('.list-item-row') || cb.closest('label');
                return !row || row.style.display !== 'none';
            });
            if(checkboxes.length === 0) return;
            const allChecked = checkboxes.every(cb => cb.checked);
            btn.textContent = allChecked ? 'Deselect All' : 'Select All';
        }

        ['modules[]', 'classes[]', 'subjects[]'].forEach(name => {
            updateSelectAllText(name);
            document.querySelectorAll(`input[name="${name}"]`).forEach(cb => {
                cb.addEventListener('change', () => updateSelectAllText(name));
            });
        });

        // Search Filter Logic
        document.querySelectorAll('.search-input').forEach(input => {
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const listId = this.getAttribute('data-list');
                const listContainer = document.getElementById(listId);
                const items = listContainer.querySelectorAll('.list-item-row');
                
                items.forEach(item => {
                    const textElement = item.querySelector('.item-text');
                    if (textElement) {
                        const text = textElement.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });

                // Update select all button state after filtering
                const targetMatch = listId === 'classes-list' ? 'classes[]' : 'subjects[]';
                updateSelectAllText(targetMatch);
            });
        });
    });
</script>
@endsection
