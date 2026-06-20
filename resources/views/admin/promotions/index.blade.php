@extends('layouts.app')

@section('title', 'Student Promotions')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Student Promotions</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage academic year transitions and class promotions</p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('admin.promotions.help') }}" class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-rounded text-[18px]">help</span>
                    How to Use
                </a>
                <a href="{{ route('admin.promotions.rules') }}" class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-rounded text-[18px]">rule_settings</span>
                    Promotion Rules
                </a>
                <a href="{{ route('admin.promotions.history') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors">
                    <span class="material-symbols-rounded text-[18px]">history</span>
                    Promotion History
                </a>
            </div>
        </div>

        <!-- Stats Grid (4 cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Stat Card 1: Total Active Students -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Active Students</h3>
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                        <span class="material-symbols-rounded text-[18px]">school</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-total-students" class="text-headline-lg font-headline-lg text-on-surface">{{ number_format($stats['total_students']) }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-secondary flex items-center gap-1">
                    Currently enrolled
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 2: Selected Students -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Selected Students</h3>
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700">
                        <span class="material-symbols-rounded text-[18px]">checklist</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-selected" class="text-headline-lg font-headline-lg text-on-surface">0</span>
                    <span id="stat-loaded" class="text-body-md text-secondary">/ 0 loaded</span>
                </div>
                <div class="mt-2 text-xs font-medium text-orange-700 flex items-center gap-1">
                    Ready for promotion
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-orange-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 3: Promoted -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Promoted</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-rounded text-[18px]">verified</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-promoted" class="text-headline-lg font-headline-lg text-on-surface">{{ number_format($stats['promoted']) }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-emerald-700 flex items-center gap-1">
                    <span class="material-symbols-rounded text-[14px]">check_circle</span> Successfully processed
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 4: Completion Rate -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Success Rate</h3>
                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700">
                        <span class="material-symbols-rounded text-[18px]">trending_up</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span id="stat-rate" class="text-headline-lg font-headline-lg text-on-surface">{{ $stats['completion_rate'] }}%</span>
                </div>
                <div class="mt-2 text-xs font-medium text-secondary flex items-center gap-1">
                    @if($stats['failed'] > 0)
                        <span class="text-red-600">{{ $stats['failed'] }} failed</span>
                    @else
                        All promotions successful
                    @endif
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-violet-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Workflow Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-md">

            <!-- Left Column: Filters Panel -->
            <div class="lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Filter Students</h3>
                    <p class="text-xs text-secondary mt-1">Select source class to load students</p>
                </div>
                <div class="p-md flex-1 space-y-4">
                    <!-- Current Academic Session -->
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Current Academic Session</label>
                        <select id="filter-session" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                                    {{ $year->year ?? ($year->start_date . ' – ' . $year->end_date) }}
                                    {{ $year->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Current Class -->
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Current Class</label>
                        <select id="filter-class" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Current Section (loaded dynamically) -->
                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Current Section</label>
                        <select id="filter-section" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors" disabled>
                            <option value="">-- All Sections --</option>
                        </select>
                    </div>

                    <!-- Load Students Button -->
                    <button id="btn-load-students" class="w-full py-2.5 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <span class="material-symbols-rounded text-[18px]">group</span>
                        <span id="btn-load-text">Load Students</span>
                        <span id="btn-load-spinner" class="hidden animate-spin">
                            <span class="material-symbols-rounded text-[18px]">progress_activity</span>
                        </span>
                    </button>

                    <div class="h-px bg-outline-variant my-2"></div>

                    <!-- Destination Selection -->
                    <h4 class="text-label-md font-label-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-rounded text-[16px] text-primary">arrow_forward</span>
                        Promotion Destination
                    </h4>

                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Target Academic Session</label>
                        <select id="dest-session" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors">
                            <option value="">-- Select Session --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">
                                    {{ $year->year ?? ($year->start_date . ' – ' . $year->end_date) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Target Class</label>
                        <select id="dest-class" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-label-sm font-label-sm text-secondary mb-1">Target Section</label>
                        <select id="dest-section" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary transition-colors" disabled>
                            <option value="">-- Select Section --</option>
                        </select>
                    </div>

                    <!-- Promote Button -->
                    <button id="btn-promote" class="w-full py-2.5 bg-emerald-600 text-white rounded-lg text-label-md font-label-md hover:bg-emerald-700 shadow-sm transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed mt-4" disabled>
                        <span class="material-symbols-rounded text-[18px]">upgrade</span>
                        Promote Selected Students
                    </button>
                </div>
            </div>

            <!-- Right Column: Student Table -->
            <div class="lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Students</h3>
                        <p id="student-count-label" class="text-xs text-secondary mt-1">Select a class to load students</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="text" id="student-search" placeholder="Search students..." class="bg-surface-container border border-outline-variant rounded-lg pl-9 pr-3 py-1.5 text-body-sm text-on-surface focus:outline-none focus:border-primary w-48 transition-colors" disabled>
                            <span class="material-symbols-rounded text-[16px] text-secondary absolute left-2.5 top-1/2 -translate-y-1/2">search</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                <th class="py-3 px-4 font-semibold w-[50px]">
                                    <input type="checkbox" id="selectAll" class="rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" disabled>
                                </th>
                                <th class="py-3 px-4 font-semibold">Student Name</th>
                                <th class="py-3 px-4 font-semibold">Admission No</th>
                                <th class="py-3 px-4 font-semibold">Class</th>
                                <th class="py-3 px-4 font-semibold">Section</th>
                                <th class="py-3 px-4 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="students-tbody" class="text-body-md font-body-md">
                            <tr id="empty-state">
                                <td colspan="6" class="py-16 text-center text-secondary">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-rounded text-5xl mb-3 opacity-30">group</span>
                                        <p class="text-lg font-medium text-on-surface mb-1">No students loaded</p>
                                        <p class="text-sm">Select an academic session, class, and click "Load Students"</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Loading overlay -->
                <div id="table-loading" class="hidden absolute inset-0 bg-surface-container-lowest/80 backdrop-blur-sm flex items-center justify-center z-10 rounded-xl">
                    <div class="flex flex-col items-center gap-3">
                        <span class="material-symbols-rounded text-4xl text-primary animate-spin">progress_activity</span>
                        <span class="text-label-md text-secondary">Loading students...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Rules Summary -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <div>
                    <h3 class="text-headline-md font-headline-md text-on-surface">Active Promotion Rules</h3>
                    <p class="text-xs text-secondary mt-1">Current criteria for the active academic year</p>
                </div>
                <a href="{{ route('admin.promotions.rules') }}" class="text-primary text-label-md font-label-md hover:underline">Manage Rules</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">From Class</th>
                            <th class="py-3 px-4 font-semibold">To Class</th>
                            <th class="py-3 px-4 font-semibold">Min Percentage</th>
                            <th class="py-3 px-4 font-semibold">Min Attendance</th>
                            <th class="py-3 px-4 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($rules as $rule)
                            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3 px-4 text-on-surface font-medium">{{ $rule->fromClass->name }}</td>
                                <td class="py-3 px-4 text-on-surface font-medium">{{ $rule->toClass->name }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 h-2 bg-surface-variant rounded-full overflow-hidden">
                                            <div class="h-full bg-primary" style="width: {{ $rule->min_percentage }}%"></div>
                                        </div>
                                        <span class="text-secondary text-xs">{{ $rule->min_percentage }}%</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 h-2 bg-surface-variant rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500" style="width: {{ $rule->min_attendance_pct }}%"></div>
                                        </div>
                                        <span class="text-secondary text-xs">{{ $rule->min_attendance_pct }}%</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Active</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-secondary">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-rounded text-4xl mb-2 opacity-50">rule_settings</span>
                                        <p>No promotion rules defined for this academic year.</p>
                                        <a href="{{ route('admin.promotions.rules') }}" class="text-primary hover:underline mt-2">Setup Rules</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-lg border border-outline-variant animate-[scaleIn_0.2s_ease-out]">
        <div class="p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-emerald-700 text-[22px]">upgrade</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">Confirm Promotion</h3>
            </div>
            <p class="text-body-md text-secondary mt-2">Please review the promotion details below before proceeding.</p>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-surface-variant rounded-lg p-3">
                    <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">Students Selected</span>
                    <span id="modal-count" class="text-headline-sm font-headline-sm text-on-surface">0</span>
                </div>
                <div class="bg-surface-variant rounded-lg p-3">
                    <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">From Session</span>
                    <span id="modal-from-session" class="text-body-md font-medium text-on-surface">—</span>
                </div>
                <div class="bg-surface-variant rounded-lg p-3">
                    <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">From Class</span>
                    <span id="modal-from-class" class="text-body-md font-medium text-on-surface">—</span>
                </div>
                <div class="bg-surface-variant rounded-lg p-3">
                    <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">To Session</span>
                    <span id="modal-to-session" class="text-body-md font-medium text-on-surface">—</span>
                </div>
                <div class="bg-surface-variant rounded-lg p-3">
                    <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">To Class</span>
                    <span id="modal-to-class" class="text-body-md font-medium text-on-surface">—</span>
                </div>
                <div class="bg-surface-variant rounded-lg p-3">
                    <span class="text-label-sm text-secondary uppercase tracking-wider block mb-1">To Section</span>
                    <span id="modal-to-section" class="text-body-md font-medium text-on-surface">—</span>
                </div>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 flex items-start gap-2">
                <span class="material-symbols-rounded text-orange-600 text-[18px] mt-0.5">warning</span>
                <p class="text-sm text-orange-800">This action will update student enrollments permanently. A promotion history record will be created for each student.</p>
            </div>
        </div>
        <div class="p-6 border-t border-outline-variant flex items-center justify-end gap-3">
            <button id="modal-cancel" class="px-5 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                Cancel
            </button>
            <button id="modal-confirm" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-label-md font-label-md hover:bg-emerald-700 shadow-sm transition-colors flex items-center gap-2">
                <span class="material-symbols-rounded text-[18px]">check</span>
                <span id="modal-confirm-text">Confirm Promotion</span>
                <span id="modal-confirm-spinner" class="hidden animate-spin">
                    <span class="material-symbols-rounded text-[18px]">progress_activity</span>
                </span>
            </button>
        </div>
    </div>
</div>

<!-- Results Modal -->
<div id="results-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-lg border border-outline-variant">
        <div class="p-6 border-b border-outline-variant">
            <div class="flex items-center gap-3">
                <div id="results-icon" class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                    <span class="material-symbols-rounded text-emerald-700 text-[22px]">check_circle</span>
                </div>
                <h3 id="results-title" class="text-headline-md font-headline-md text-on-surface">Promotion Complete</h3>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-center">
                    <span class="block text-headline-sm font-headline-sm text-emerald-700" id="result-success">0</span>
                    <span class="text-xs text-emerald-600">Successful</span>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                    <span class="block text-headline-sm font-headline-sm text-red-700" id="result-failed">0</span>
                    <span class="text-xs text-red-600">Failed</span>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-center">
                    <span class="block text-headline-sm font-headline-sm text-orange-700" id="result-skipped">0</span>
                    <span class="text-xs text-orange-600">Skipped</span>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                    <span class="block text-headline-sm font-headline-sm text-blue-700" id="result-total">0</span>
                    <span class="text-xs text-blue-600">Total</span>
                </div>
            </div>
            <div id="result-errors" class="hidden">
                <h4 class="text-label-md font-label-md text-red-700 mb-2">Issues:</h4>
                <div id="result-errors-list" class="max-h-40 overflow-y-auto space-y-1 text-sm"></div>
            </div>
        </div>
        <div class="p-6 border-t border-outline-variant flex items-center justify-end">
            <button id="results-close" class="px-5 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes scaleIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Elements
    const filterClass       = document.getElementById('filter-class');
    const filterSection     = document.getElementById('filter-section');
    const filterSession     = document.getElementById('filter-session');
    const destClass         = document.getElementById('dest-class');
    const destSection       = document.getElementById('dest-section');
    const btnLoad           = document.getElementById('btn-load-students');
    const btnPromote        = document.getElementById('btn-promote');
    const selectAll         = document.getElementById('selectAll');
    const studentsTbody     = document.getElementById('students-tbody');
    const studentSearch     = document.getElementById('student-search');
    const statSelected      = document.getElementById('stat-selected');
    const statLoaded        = document.getElementById('stat-loaded');

    let allStudents = [];

    // --- Cascading Sections for Source ---
    filterClass.addEventListener('change', function() {
        const classId = this.value;
        btnLoad.disabled = !classId;
        filterSection.innerHTML = '<option value="">-- All Sections --</option>';
        filterSection.disabled = true;

        if (classId) {
            fetch(`{{ route('admin.promotions.get-sections') }}?class_id=${classId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.sections && data.sections.length > 0) {
                    data.sections.forEach(s => {
                        filterSection.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                    });
                    filterSection.disabled = false;
                }
            });
        }
    });

    // --- Cascading Sections for Destination ---
    destClass.addEventListener('change', function() {
        const classId = this.value;
        destSection.innerHTML = '<option value="">-- Select Section --</option>';
        destSection.disabled = true;

        if (classId) {
            fetch(`{{ route('admin.promotions.get-sections') }}?class_id=${classId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.sections && data.sections.length > 0) {
                    data.sections.forEach(s => {
                        destSection.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                    });
                    destSection.disabled = false;
                }
            });
        }
        updatePromoteButton();
    });

    // --- Load Students ---
    btnLoad.addEventListener('click', function() {
        const classId = filterClass.value;
        if (!classId) return;

        const sectionId = filterSection.value;
        const btnText = document.getElementById('btn-load-text');
        const btnSpinner = document.getElementById('btn-load-spinner');

        btnLoad.disabled = true;
        btnText.textContent = 'Loading...';
        btnSpinner.classList.remove('hidden');

        let url = `{{ route('admin.promotions.load-students') }}?class_id=${classId}`;
        if (sectionId) url += `&section_id=${sectionId}`;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            allStudents = data.students || [];
            renderStudents(allStudents);
            statLoaded.textContent = `/ ${allStudents.length} loaded`;
            document.getElementById('student-count-label').textContent = `${allStudents.length} student(s) found`;
            selectAll.disabled = allStudents.length === 0;
            studentSearch.disabled = allStudents.length === 0;
            updateSelectedCount();
        })
        .catch(err => {
            showToast('Failed to load students: ' + err.message, 'error');
        })
        .finally(() => {
            btnLoad.disabled = false;
            btnText.textContent = 'Load Students';
            btnSpinner.classList.add('hidden');
        });
    });

    // --- Render Student Rows ---
    function renderStudents(students) {
        if (students.length === 0) {
            studentsTbody.innerHTML = `
                <tr id="empty-state">
                    <td colspan="6" class="py-16 text-center text-secondary">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-rounded text-5xl mb-3 opacity-30">group_off</span>
                            <p class="text-lg font-medium text-on-surface mb-1">No students found</p>
                            <p class="text-sm">Try a different class or section filter</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        studentsTbody.innerHTML = students.map(s => `
            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors student-row" data-name="${(s.first_name + ' ' + s.last_name).toLowerCase()}" data-admission="${(s.admission_no || '').toLowerCase()}">
                <td class="py-3 px-4">
                    <input type="checkbox" class="student-checkbox rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" value="${s.id}" checked>
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs uppercase">
                            ${(s.first_name || '?').charAt(0)}
                        </div>
                        <span class="text-on-surface font-medium">${s.first_name} ${s.last_name}</span>
                    </div>
                </td>
                <td class="py-3 px-4 text-secondary">${s.admission_no || '—'}</td>
                <td class="py-3 px-4 text-secondary">${s.class_name}</td>
                <td class="py-3 px-4 text-secondary">${s.section_name}</td>
                <td class="py-3 px-4 text-center">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        ${s.status}
                    </span>
                </td>
            </tr>
        `).join('');

        // Bind checkbox events
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });

        selectAll.checked = true;
        selectAll.indeterminate = false;
        updateSelectedCount();
    }

    // --- Select All ---
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            if (cb.closest('.student-row').style.display !== 'none') {
                cb.checked = selectAll.checked;
            }
        });
        updateSelectedCount();
    });

    // --- Search ---
    studentSearch.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.student-row').forEach(row => {
            const name = row.dataset.name || '';
            const adm = row.dataset.admission || '';
            row.style.display = (name.includes(term) || adm.includes(term)) ? '' : 'none';
        });
    });

    // --- Update Selected Count ---
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.student-checkbox:checked').length;
        statSelected.textContent = checked;
        updatePromoteButton();

        // Update select-all state
        const total = document.querySelectorAll('.student-checkbox').length;
        if (total > 0) {
            selectAll.checked = checked === total;
            selectAll.indeterminate = checked > 0 && checked < total;
        }
    }

    // --- Update Promote Button State ---
    function updatePromoteButton() {
        const hasSelection = document.querySelectorAll('.student-checkbox:checked').length > 0;
        const hasDestSession = document.getElementById('dest-session').value;
        const hasDestClass = document.getElementById('dest-class').value;
        btnPromote.disabled = !(hasSelection && hasDestSession && hasDestClass);
    }

    // Bind change events for destination fields
    document.getElementById('dest-session').addEventListener('change', updatePromoteButton);
    destSection.addEventListener('change', updatePromoteButton);

    // --- Promote Button → Show Confirmation Modal ---
    btnPromote.addEventListener('click', function() {
        const selectedIds = [...document.querySelectorAll('.student-checkbox:checked')].map(cb => cb.value);
        if (selectedIds.length === 0) return;

        // Fill modal info
        document.getElementById('modal-count').textContent = selectedIds.length;
        document.getElementById('modal-from-session').textContent =
            filterSession.options[filterSession.selectedIndex]?.text || '—';
        document.getElementById('modal-from-class').textContent =
            filterClass.options[filterClass.selectedIndex]?.text || '—';
        document.getElementById('modal-to-session').textContent =
            document.getElementById('dest-session').options[document.getElementById('dest-session').selectedIndex]?.text || '—';
        document.getElementById('modal-to-class').textContent =
            destClass.options[destClass.selectedIndex]?.text || '—';
        document.getElementById('modal-to-section').textContent =
            destSection.value ? destSection.options[destSection.selectedIndex]?.text : 'Not specified';

        showModal('confirm-modal');
    });

    // --- Modal Cancel ---
    document.getElementById('modal-cancel').addEventListener('click', () => hideModal('confirm-modal'));

    // --- Modal Confirm → Execute Promotion ---
    document.getElementById('modal-confirm').addEventListener('click', function() {
        const selectedIds = [...document.querySelectorAll('.student-checkbox:checked')].map(cb => cb.value);
        const confirmText = document.getElementById('modal-confirm-text');
        const confirmSpinner = document.getElementById('modal-confirm-spinner');

        confirmText.textContent = 'Processing...';
        confirmSpinner.classList.remove('hidden');
        this.disabled = true;

        fetch('{{ route('admin.promotions.execute') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                student_ids: selectedIds,
                from_academic_year_id: filterSession.value,
                to_academic_year_id: document.getElementById('dest-session').value,
                from_class_id: filterClass.value,
                to_class_id: destClass.value,
                to_section_id: destSection.value || null,
            })
        })
        .then(r => r.json())
        .then(data => {
            hideModal('confirm-modal');

            if (data.status === 'success' && data.summary) {
                document.getElementById('result-success').textContent = data.summary.success;
                document.getElementById('result-failed').textContent = data.summary.failed;
                document.getElementById('result-skipped').textContent = data.summary.skipped;
                document.getElementById('result-total').textContent = data.summary.total;

                if (data.summary.errors && data.summary.errors.length > 0) {
                    document.getElementById('result-errors').classList.remove('hidden');
                    document.getElementById('result-errors-list').innerHTML = data.summary.errors.map(e =>
                        `<div class="bg-red-50 border border-red-100 rounded px-3 py-2 text-red-700">
                            <strong>${e.student_name}</strong>: ${e.reason}
                        </div>`
                    ).join('');
                } else {
                    document.getElementById('result-errors').classList.add('hidden');
                }

                showModal('results-modal');

                // Refresh stats
                document.getElementById('stat-promoted').textContent =
                    parseInt(document.getElementById('stat-promoted').textContent.replace(/,/g, '')) + data.summary.success;
            } else if (data.status === 'error') {
                let msg = data.message || 'Promotion failed.';
                if (data.errors && Array.isArray(data.errors)) {
                    msg += '\n' + data.errors.join('\n');
                }
                showToast(msg, 'error');
            }
        })
        .catch(err => {
            hideModal('confirm-modal');
            showToast('Request failed: ' + err.message, 'error');
        })
        .finally(() => {
            confirmText.textContent = 'Confirm Promotion';
            confirmSpinner.classList.add('hidden');
            document.getElementById('modal-confirm').disabled = false;
        });
    });

    // --- Results Close ---
    document.getElementById('results-close').addEventListener('click', () => {
        hideModal('results-modal');
        // Reload students to reflect changes
        btnLoad.click();
    });

    // --- Utility: Show / Hide Modals ---
    function showModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function hideModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // --- Utility: Toast Notification ---
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        const bgClass = type === 'error' ? 'bg-red-600' : 'bg-emerald-600';
        toast.className = `fixed top-6 right-6 z-[60] ${bgClass} text-white px-6 py-3 rounded-xl shadow-2xl text-sm font-medium flex items-center gap-2 animate-[slideIn_0.3s_ease-out]`;
        toast.innerHTML = `
            <span class="material-symbols-rounded text-[18px]">${type === 'error' ? 'error' : 'check_circle'}</span>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
});
</script>

@push('styles')
<style>
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>
@endpush
@endsection
