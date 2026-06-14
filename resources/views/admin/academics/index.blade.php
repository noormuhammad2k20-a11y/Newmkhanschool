@extends('layouts.app')

@section('title', 'Academic Management')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface">Academic Management</h2>
            <p class="text-body-md font-body-md text-secondary mt-1">Manage classes, subjects, and teacher assignments.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-error-container text-on-error-container px-4 py-3 rounded-lg mb-lg font-body-md border border-error">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Academic Management Container -->
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden mb-xl">
        <div class="p-lg border-b border-outline-variant bg-surface-container-lowest">
            <h3 class="text-headline-md font-headline-md text-on-surface">Core Academics</h3>
            <p class="text-body-sm font-body-sm text-secondary">Configure your core academic structure and assignments.</p>
        </div>

        <div class="p-lg grid grid-cols-1 xl:grid-cols-2 gap-xl">
            
            <!-- Unified Academics Management -->
            <div class="xl:col-span-2 flex flex-col gap-md border border-outline-variant rounded-xl p-md bg-surface-container-lowest">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-outline-variant pb-md mb-2 gap-4">
                    <div>
                        <h4 class="text-label-lg font-label-lg text-on-surface font-semibold">Academics Management</h4>
                        <p class="text-body-sm font-body-sm text-secondary mt-1">Manage classes and their assigned subjects</p>
                    </div>
                    <button onclick="openWizardModal()" type="button" class="bg-primary text-on-primary px-4 py-2 rounded-md text-label-sm font-semibold hover:bg-primary-dark transition-colors flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">account_tree</span> Create Academic Structure
                    </button>
                </div>
                
                <div class="overflow-hidden border border-outline-variant rounded-lg flex-1 flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-body-sm whitespace-nowrap">
                            <thead class="bg-surface border-b border-outline-variant">
                                <tr>
                                    <th class="py-3 px-4 font-medium text-secondary w-48">Class</th>
                                    <th class="py-3 px-4 font-medium text-secondary min-w-[300px]">Subjects</th>
                                    <th class="py-3 px-4 font-medium text-secondary text-center w-32">Total Subjects</th>
                                    <th class="py-3 px-4 font-medium text-secondary text-right w-32">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant whitespace-normal">
                                @forelse($classes as $class)
                                    @php
                                        $classSubjects = $subjects->where('class_id', $class->id);
                                    @endphp
                                    <tr class="hover:bg-surface-container-low align-top transition-colors">
                                        <td class="py-4 px-4 text-on-surface font-medium text-base">{{ $class->name }}</td>
                                        <td class="py-4 px-4">
                                            @if($classSubjects->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($classSubjects as $subject)
                                                        <span class="inline-flex items-center bg-surface-variant text-on-surface text-xs font-medium px-2.5 py-1 rounded-md border border-outline-variant/50 shadow-sm">
                                                            {{ $subject->name }}
                                                            @if($subject->code)
                                                                <span class="text-secondary ml-1 font-normal">({{ $subject->code }})</span>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-secondary italic text-sm">No subjects assigned</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary font-semibold text-xs">
                                                {{ $classSubjects->count() }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <button type="button" onclick="openManageModal({{ $class->id }}, '{{ addslashes($class->name) }}')" class="btn-outline py-1.5 px-3 text-sm h-auto inline-flex items-center gap-1 hover:text-primary hover:border-primary/50 transition-colors">
                                                <span class="material-symbols-outlined text-[16px]">settings</span> Manage
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-8 text-center text-secondary text-body-md">No classes found. Add a class to get started.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Teacher Assignments -->
            <div class="xl:col-span-2 flex flex-col gap-md border border-outline-variant rounded-xl p-md bg-surface-container-lowest mt-4">
                <div class="border-b border-outline-variant pb-md mb-2">
                    <h4 class="text-label-lg font-label-lg text-on-surface font-semibold">Teacher Assignments</h4>
                    <p class="text-body-sm font-body-sm text-secondary mt-1">Assign teachers to specific classes and subjects</p>
                </div>

                <form action="{{ route('admin.academics.assignments.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-sm items-end mb-4">
                    @csrf
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Teacher</label>
                        <select name="teacher_id" class="w-full bg-surface border border-outline-variant rounded-md py-1.5 px-3 text-body-sm focus:border-primary focus:ring-1 focus:ring-primary text-on-surface" required>
                            <option value="">Select Teacher...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Class</label>
                        <select name="class_id" id="assign_class_id" class="w-full bg-surface border border-outline-variant rounded-md py-1.5 px-3 text-body-sm focus:border-primary focus:ring-1 focus:ring-primary text-on-surface" required>
                            <option value="">Select Class...</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Subject</label>
                        <select name="subject_id" id="assign_subject_id" class="w-full bg-surface border border-outline-variant rounded-md py-1.5 px-3 text-body-sm focus:border-primary focus:ring-1 focus:ring-primary text-on-surface" required disabled>
                            <option value="">Select Subject...</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-primary text-on-primary px-3 py-1.5 rounded-md text-label-sm hover:bg-primary-dark transition-colors h-[34px]">
                            Assign Teacher
                        </button>
                    </div>
                </form>

                <div class="overflow-hidden border border-outline-variant rounded-lg flex-1 flex flex-col max-h-[400px]">
                    <div class="overflow-y-auto flex-1">
                        <table class="w-full text-left border-collapse text-body-sm">
                            <thead class="sticky top-0 bg-surface border-b border-outline-variant">
                                <tr>
                                    <th class="py-2 px-4 font-medium text-secondary">Teacher</th>
                                    <th class="py-2 px-4 font-medium text-secondary">Class</th>
                                    <th class="py-2 px-4 font-medium text-secondary">Subject</th>
                                    <th class="py-2 px-4 font-medium text-secondary text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                @forelse($assignments as $assignment)
                                    <tr class="hover:bg-surface-container-low">
                                        <td class="py-3 px-4 text-on-surface font-medium">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                                    {{ substr($assignment->teacher->full_name ?? 'U', 0, 1) }}
                                                </div>
                                                {{ $assignment->teacher->full_name ?? 'Unknown' }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-on-surface">{{ $assignment->class_->name ?? 'Unknown' }}</td>
                                        <td class="py-3 px-4 text-primary font-medium">{{ $assignment->subject->name ?? 'Unknown' }}</td>
                                        <td class="py-3 px-4 text-right">
                                            <form action="{{ route('admin.academics.assignments.destroy', $assignment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove this assignment?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-error hover:text-error-dark px-2 py-1 border border-error rounded hover:bg-error-container text-xs" title="Remove">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-8 text-center text-secondary text-body-md">No teacher assignments found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Assignment Matrix View -->
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden mb-xl">
        <div class="p-lg border-b border-outline-variant bg-surface-container-lowest">
            <h3 class="text-headline-md font-headline-md text-on-surface">Assignment Matrix Overview</h3>
            <p class="text-body-sm font-body-sm text-secondary">A visual mapping of subjects to classes and their assigned teachers.</p>
        </div>
        
        <div class="overflow-x-auto p-lg">
            @if($classes->count() > 0 && $subjects->count() > 0)
                <table class="w-full text-left border-collapse border border-outline-variant text-body-sm">
                    <thead class="bg-surface-container-lowest">
                        <tr>
                            <th class="py-3 px-4 font-bold text-on-surface border border-outline-variant w-48">Subject / Class</th>
                            @foreach($classes as $class)
                                <th class="py-3 px-4 font-bold text-center text-primary border border-outline-variant min-w-[120px]">{{ $class->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group subjects by name to create unique rows
                            $uniqueSubjectNames = $subjects->pluck('name')->unique()->sort();
                        @endphp
                        
                        @foreach($uniqueSubjectNames as $subjectName)
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="py-3 px-4 font-medium text-on-surface border border-outline-variant bg-surface-container-lowest">{{ $subjectName }}</td>
                                @foreach($classes as $class)
                                    @php
                                        // Find the subject id for this specific class and subject name combination
                                        $subject = $subjects->where('name', $subjectName)->where('class_id', $class->id)->first();
                                        $teacherName = null;
                                        if ($subject && isset($matrixData[$subject->id][$class->id])) {
                                            $teacherName = $matrixData[$subject->id][$class->id];
                                        }
                                    @endphp
                                    <td class="py-3 px-4 text-center border border-outline-variant {{ $teacherName ? 'bg-primary-container/20 text-on-surface font-medium' : 'text-secondary/50' }}">
                                        @if($subject)
                                            @if($teacherName)
                                                <div class="flex items-center justify-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px] text-primary">person</span>
                                                    {{ $teacherName }}
                                                </div>
                                            @else
                                                <span class="text-[11px] uppercase tracking-wide">Unassigned</span>
                                            @endif
                                        @else
                                            <span class="text-[11px] text-outline italic">N/A</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-8 text-center text-secondary">
                    <span class="material-symbols-outlined text-4xl mb-2 text-outline">grid_off</span>
                    <p>Add classes and subjects to view the matrix.</p>
                </div>
            @endif
        </div>
    </div>
</main>

<!-- Academic Structure Wizard Modal -->
<div id="wizardModal" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeWizardModal()"></div>
    
    <!-- Modal Panel -->
    <div class="fixed inset-0 m-auto max-w-2xl w-full h-fit max-h-[90vh] bg-surface shadow-2xl flex flex-col rounded-xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 ease-out" id="wizardModalPanel">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between bg-surface-container-lowest">
            <div>
                <h3 class="text-headline-md font-headline-md text-on-surface">Create Academic Structure</h3>
                <p class="text-body-sm font-body-sm text-secondary">Bulk create class, sections, and subjects</p>
            </div>
            <button type="button" onclick="closeWizardModal()" class="text-secondary hover:text-on-surface p-2 rounded-full hover:bg-surface-container h-10 w-10 flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Form -->
        <form action="{{ route('admin.academics.classes.store') }}" method="POST" id="wizardForm" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            
            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6 bg-surface">
                
                <!-- Step 1: Class & Section -->
                <div id="wizardStep1" class="flex flex-col gap-6">
                    <div>
                        <label class="block text-label-lg font-label-lg font-semibold text-on-surface mb-2">1. Class Details</label>
                        <p class="text-body-sm text-secondary mb-4">Enter the main class name and its sections.</p>
                        
                        <div class="flex flex-col gap-4">
                            <div>
                                <label class="block text-label-sm font-label-sm text-secondary mb-1">Class Name <span class="text-error">*</span></label>
                                <input name="name" id="wizardClassName" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="e.g. Grade 10" type="text" required />
                            </div>
                            
                            <div>
                                <label class="block text-label-sm font-label-sm text-secondary mb-1">Sections (Comma Separated)</label>
                                <input name="sections" id="wizardSections" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="e.g. A, B, C or Rose, Lily" type="text" />
                                <p class="text-xs text-secondary mt-1">Leave empty if this class has no distinct sections.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Subjects -->
                <div id="wizardStep2" class="flex flex-col gap-6 hidden">
                    <div>
                        <label class="block text-label-lg font-label-lg font-semibold text-on-surface mb-2">2. Bulk Subjects</label>
                        <p class="text-body-sm text-secondary mb-4">Add all subjects for this class at once. Separate them with commas.</p>
                        
                        <div>
                            <label class="block text-label-sm font-label-sm text-secondary mb-1">Subjects List <span class="text-error">*</span></label>
                            <textarea name="subjects" id="wizardSubjects" rows="4" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md focus:border-primary focus:ring-1 focus:ring-primary resize-none" placeholder="e.g. English, Mathematics, Science, History, Computer" required></textarea>
                            <p class="text-xs text-secondary mt-1">These will be created and assigned to the class automatically.</p>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Footer Controls -->
            <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest flex items-center justify-between">
                <button type="button" id="wizardBtnBack" onclick="wizardGoBack()" class="btn-outline px-4 py-2 text-sm font-semibold hidden">
                    Back
                </button>
                <div class="flex-1"></div> <!-- Spacer -->
                <button type="button" id="wizardBtnNext" onclick="wizardGoNext()" class="bg-primary text-on-primary px-6 py-2 rounded-md text-label-sm font-semibold hover:bg-primary-dark transition-colors">
                    Next Step
                </button>
                <button type="submit" id="wizardBtnSubmit" class="bg-primary text-on-primary px-6 py-2 rounded-md text-label-sm font-semibold hover:bg-primary-dark transition-colors hidden">
                    Create Structure
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Manage Class/Subjects Modal -->
<div id="manageModal" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeManageModal()"></div>
    
    <!-- Modal Panel -->
    <div class="fixed inset-y-0 right-0 max-w-md w-full bg-surface shadow-2xl flex flex-col transform transition-transform translate-x-full duration-300 ease-in-out" id="manageModalPanel">
        <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between bg-surface-container-lowest">
            <div>
                <h3 class="text-headline-md font-headline-md text-on-surface" id="modalClassName">Class Name</h3>
                <p class="text-body-sm font-body-sm text-secondary">Manage class subjects</p>
            </div>
            <button type="button" onclick="closeManageModal()" class="text-secondary hover:text-on-surface p-2 rounded-full hover:bg-surface-container h-10 w-10 flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6">
            
            <!-- Existing Sections List -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-label-lg font-label-lg font-semibold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">groups</span> Sections
                    </h4>
                    <span class="text-xs font-semibold bg-primary-container text-on-primary-container px-2 py-0.5 rounded-full" id="modalSectionCount">0</span>
                </div>
                <div id="modalSectionsList" class="flex flex-wrap gap-2">
                    <!-- Sections will be injected here via JS -->
                </div>
            </div>

            <!-- Add Subject Form -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4">
                <h4 class="text-label-lg font-label-lg font-semibold mb-3 flex items-center gap-2 text-on-surface">
                    <span class="material-symbols-outlined text-primary text-[20px]">add_circle</span> Add New Subject
                </h4>
                <form action="{{ route('admin.academics.subjects.store') }}" method="POST" class="flex flex-col gap-3">
                    @csrf
                    <input type="hidden" name="class_id" id="modalClassId">
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Subjects (Comma Separated) <span class="text-error">*</span></label>
                        <input name="name" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="e.g. Math, Science" type="text" required />
                    </div>
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Subject Code (Optional)</label>
                        <input name="code" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="e.g. MTH-101" type="text" />
                    </div>
                    <button type="submit" class="bg-primary text-on-primary py-2 rounded-md text-label-sm hover:bg-primary-dark transition-colors w-full font-semibold mt-1">
                        Add Subject
                    </button>
                </form>
            </div>

            <!-- Existing Subjects List -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-label-lg font-label-lg font-semibold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">menu_book</span> Existing Subjects
                    </h4>
                    <span class="text-xs font-semibold bg-primary-container text-on-primary-container px-2 py-0.5 rounded-full" id="modalSubjectCount">0</span>
                </div>
                
                <div id="modalSubjectsList" class="flex flex-col gap-2">
                    <!-- Subjects will be injected here via JS -->
                </div>
            </div>
            
            <!-- Delete Class -->
            <div class="mt-auto pt-4 border-t border-outline-variant">
                <form id="deleteClassForm" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to delete this class? This will also delete all its subjects. This action cannot be undone.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full border border-error text-error py-2 rounded-md hover:bg-error-container transition-colors text-label-sm font-semibold flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">delete</span> Delete Entire Class
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Pass subjects data to JS
    const subjectsData = @json($subjects);
    const sectionsData = @json($sections);

    // CSRF for inline forms
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const manageModal = document.getElementById('manageModal');
    const manageModalPanel = document.getElementById('manageModalPanel');
    const modalClassName = document.getElementById('modalClassName');
    const modalClassId = document.getElementById('modalClassId');
    const modalSubjectsList = document.getElementById('modalSubjectsList');
    const modalSubjectCount = document.getElementById('modalSubjectCount');
    const modalSectionsList = document.getElementById('modalSectionsList');
    const modalSectionCount = document.getElementById('modalSectionCount');
    const deleteClassForm = document.getElementById('deleteClassForm');

    window.openManageModal = function(classId, className) {
        modalClassName.textContent = className;
        modalClassId.value = classId;
        
        // Update delete class form action
        deleteClassForm.action = `/admin/academics/classes/${classId}`;
        
        renderSectionsList(classId);
        renderSubjectsList(classId);
        
        manageModal.classList.remove('hidden');
        setTimeout(() => {
            manageModalPanel.classList.remove('translate-x-full');
        }, 10);
    };

    window.closeManageModal = function() {
        manageModalPanel.classList.add('translate-x-full');
        setTimeout(() => {
            manageModal.classList.add('hidden');
        }, 300);
    };

    function renderSectionsList(classId) {
        const classSections = sectionsData.filter(s => s.class_id == classId);
        modalSectionCount.textContent = classSections.length;
        
        if (classSections.length === 0) {
            modalSectionsList.innerHTML = `<span class="text-secondary italic text-sm">No sections</span>`;
            return;
        }
        
        let html = '';
        classSections.forEach(section => {
            html += `<span class="inline-flex items-center bg-surface-variant text-on-surface text-xs font-medium px-2.5 py-1 rounded-md border border-outline-variant/50 shadow-sm">${section.name}</span>`;
        });
        modalSectionsList.innerHTML = html;
    }

    function renderSubjectsList(classId) {
        const classSubjects = subjectsData.filter(s => s.class_id == classId);
        modalSubjectCount.textContent = classSubjects.length;
        
        if (classSubjects.length === 0) {
            modalSubjectsList.innerHTML = `<div class="text-center py-6 bg-surface-container-lowest border border-outline-variant rounded-lg border-dashed">
                <span class="material-symbols-outlined text-outline text-3xl mb-1">inbox</span>
                <p class="text-secondary text-sm">No subjects assigned yet.</p>
            </div>`;
            return;
        }
        
        let html = '';
        classSubjects.forEach(subject => {
            const updateUrl = `/admin/academics/subjects/${subject.id}`;
            const deleteUrl = `/admin/academics/subjects/${subject.id}`;
            
            html += `
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-3 group hover:border-primary/50 transition-colors">
                <!-- View Mode -->
                <div id="subject-view-${subject.id}" class="flex items-center justify-between">
                    <div>
                        <div class="font-medium text-on-surface text-sm flex items-center gap-2">
                            ${subject.name}
                        </div>
                        <div class="text-xs text-secondary mt-0.5">Code: ${subject.code || 'N/A'}</div>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" onclick="toggleEditSubject(${subject.id})" class="text-secondary hover:text-primary p-1.5 rounded-md hover:bg-primary-container/20 transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Delete subject ${subject.name}?');" class="inline">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-secondary hover:text-error p-1.5 rounded-md hover:bg-error-container transition-colors" title="Delete">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Edit Mode -->
                <form id="subject-edit-${subject.id}" action="${updateUrl}" method="POST" class="hidden flex-col gap-2 mt-2 pt-2 border-t border-outline-variant">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="PUT">
                    <div>
                        <input name="name" class="w-full bg-surface border border-outline-variant rounded-md py-1 px-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary" value="${subject.name}" required />
                    </div>
                    <div>
                        <input name="code" class="w-full bg-surface border border-outline-variant rounded-md py-1 px-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary" value="${subject.code || ''}" placeholder="Code" />
                    </div>
                    <div class="flex gap-2 justify-end mt-1">
                        <button type="button" onclick="toggleEditSubject(${subject.id})" class="text-xs px-3 py-1.5 rounded-md bg-surface-container hover:bg-surface-container-high transition-colors text-secondary font-medium">Cancel</button>
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-md bg-primary text-on-primary hover:bg-primary-dark transition-colors font-medium">Save</button>
                    </div>
                </form>
            </div>`;
        });
        
        modalSubjectsList.innerHTML = html;
    }

    window.toggleEditSubject = function(subjectId) {
        const viewDiv = document.getElementById(`subject-view-${subjectId}`);
        const editForm = document.getElementById(`subject-edit-${subjectId}`);
        
        if (editForm.classList.contains('hidden')) {
            editForm.classList.remove('hidden');
            editForm.classList.add('flex');
            viewDiv.classList.add('hidden');
            viewDiv.classList.remove('flex');
        } else {
            editForm.classList.add('hidden');
            editForm.classList.remove('flex');
            viewDiv.classList.remove('hidden');
            viewDiv.classList.add('flex');
        }
    };

    // Wizard Logic
    const wizardModal = document.getElementById('wizardModal');
    const wizardModalPanel = document.getElementById('wizardModalPanel');
    const wizardStep1 = document.getElementById('wizardStep1');
    const wizardStep2 = document.getElementById('wizardStep2');
    const wizardBtnBack = document.getElementById('wizardBtnBack');
    const wizardBtnNext = document.getElementById('wizardBtnNext');
    const wizardBtnSubmit = document.getElementById('wizardBtnSubmit');
    const wizardClassName = document.getElementById('wizardClassName');
    
    window.openWizardModal = function() {
        document.getElementById('wizardForm').reset();
        wizardStep1.classList.remove('hidden');
        wizardStep2.classList.add('hidden');
        wizardBtnBack.classList.add('hidden');
        wizardBtnNext.classList.remove('hidden');
        wizardBtnSubmit.classList.add('hidden');
        
        wizardModal.classList.remove('hidden');
        setTimeout(() => {
            wizardModalPanel.classList.remove('scale-95', 'opacity-0');
            wizardModalPanel.classList.add('scale-100', 'opacity-100');
        }, 10);
    };

    window.closeWizardModal = function() {
        wizardModalPanel.classList.remove('scale-100', 'opacity-100');
        wizardModalPanel.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            wizardModal.classList.add('hidden');
        }, 300);
    };
    
    window.wizardGoNext = function() {
        if (!wizardClassName.value.trim()) {
            wizardClassName.reportValidity();
            return;
        }
        wizardStep1.classList.add('hidden');
        wizardStep2.classList.remove('hidden');
        wizardBtnBack.classList.remove('hidden');
        wizardBtnNext.classList.add('hidden');
        wizardBtnSubmit.classList.remove('hidden');
    };
    
    window.wizardGoBack = function() {
        wizardStep2.classList.add('hidden');
        wizardStep1.classList.remove('hidden');
        wizardBtnBack.classList.add('hidden');
        wizardBtnNext.classList.remove('hidden');
        wizardBtnSubmit.classList.add('hidden');
    };

    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('assign_class_id');
        const subjectSelect = document.getElementById('assign_subject_id');
        
        if (classSelect && subjectSelect) {
            classSelect.addEventListener('change', function() {
                const classId = this.value;
                
                subjectSelect.innerHTML = '<option value="">Select Subject...</option>';
                subjectSelect.disabled = true;
                
                if (!classId) return;
                
                const classSubjects = subjectsData.filter(s => s.class_id == classId);
                
                if (classSubjects.length > 0) {
                    classSubjects.forEach(s => {
                        const option = document.createElement('option');
                        option.value = s.id;
                        option.textContent = s.name + (s.code ? ' (' + s.code + ')' : '');
                        subjectSelect.appendChild(option);
                    });
                    subjectSelect.disabled = false;
                } else {
                    subjectSelect.innerHTML = '<option value="">No subjects found for this class</option>';
                }
            });
        }
    });
</script>
@endsection
