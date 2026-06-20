@extends('layouts.app')

@section('title', 'How to Use Student Promotions')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[960px] mx-auto space-y-xl">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">How to Use Student Promotions</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">A step-by-step guide to the complete promotion process</p>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors self-start">
                <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                Back to Promotions
            </a>
        </div>

        <!-- Steps -->
        <div class="space-y-6">

            <!-- Step 1 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">1</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Select the Current Academic Session</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Choose the session that currently contains the students you want to promote</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed">
                        Start by selecting the academic session (year) from which you want to promote students. The active session is pre-selected by default.
                    </p>
                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="material-symbols-rounded text-blue-600 text-[18px] mt-0.5 shrink-0">lightbulb</span>
                        <p class="text-sm text-blue-800">Example: <strong>2025–2026</strong></p>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">2</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Select the Current Class</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Choose the class from which students will be promoted</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed">
                        Select the class you want to promote students from. Available sections for that class will automatically load.
                    </p>
                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="material-symbols-rounded text-blue-600 text-[18px] mt-0.5 shrink-0">lightbulb</span>
                        <p class="text-sm text-blue-800">Example: <strong>Grade 5</strong></p>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">3</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Select the Current Section</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Optionally narrow down by section</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed">
                        If the class has multiple sections, you can filter by a specific section. Leave as "All Sections" to load students from all sections.
                    </p>
                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="material-symbols-rounded text-blue-600 text-[18px] mt-0.5 shrink-0">lightbulb</span>
                        <p class="text-sm text-blue-800">Example: <strong>Section A</strong></p>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">4</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Load Students</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Click "Load Students" to display eligible students</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed mb-3">
                        Click the <strong>Load Students</strong> button. The system will fetch all active students matching your filters and display them in a table showing:
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-primary text-[16px]">person</span>
                            <span class="text-sm text-on-surface">Student Name</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-primary text-[16px]">badge</span>
                            <span class="text-sm text-on-surface">Admission Number</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-primary text-[16px]">school</span>
                            <span class="text-sm text-on-surface">Current Class</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-primary text-[16px]">group</span>
                            <span class="text-sm text-on-surface">Current Section</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-primary text-[16px]">calendar_today</span>
                            <span class="text-sm text-on-surface">Current Session</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">5</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Select Students</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Choose individual students or select all</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed">
                        Use the checkboxes to select which students to promote. By default, all loaded students are selected. You can:
                    </p>
                    <ul class="mt-2 space-y-2 text-body-md text-on-surface">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-emerald-600 text-[16px]">check_box</span>
                            Use <strong>Select All</strong> to promote the entire class
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-primary text-[16px]">search</span>
                            Use the <strong>search box</strong> to find specific students
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-orange-600 text-[16px]">check_box_outline_blank</span>
                            Uncheck individual students to exclude them
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">6</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Choose Promotion Destination</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Set the target session, class, and section</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed mb-3">
                        In the left panel, select where to promote students:
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 bg-surface-variant rounded-lg p-3">
                            <span class="material-symbols-rounded text-primary text-[20px]">calendar_month</span>
                            <div>
                                <span class="font-medium text-on-surface">New Academic Session</span>
                                <span class="text-secondary text-sm ml-2">e.g., 2026–2027</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-surface-variant rounded-lg p-3">
                            <span class="material-symbols-rounded text-primary text-[20px]">school</span>
                            <div>
                                <span class="font-medium text-on-surface">New Class</span>
                                <span class="text-secondary text-sm ml-2">e.g., Grade 6</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-surface-variant rounded-lg p-3">
                            <span class="material-symbols-rounded text-primary text-[20px]">group</span>
                            <div>
                                <span class="font-medium text-on-surface">New Section</span>
                                <span class="text-secondary text-sm ml-2">e.g., Section A</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-lg p-3 flex items-start gap-2">
                        <span class="material-symbols-rounded text-emerald-600 text-[18px] mt-0.5 shrink-0">arrow_forward</span>
                        <p class="text-sm text-emerald-800">Example: <strong>Grade 5 → Grade 6</strong>, Session <strong>2025–2026 → 2026–2027</strong></p>
                    </div>
                </div>
            </div>

            <!-- Step 7 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">7</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Review Promotion Details</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Verify everything before proceeding</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed">
                        Before clicking "Promote Selected Students", carefully review:
                    </p>
                    <ul class="mt-2 space-y-1 text-body-md text-on-surface list-disc list-inside">
                        <li>Number of selected students (shown in the stats card)</li>
                        <li>Destination class is correct</li>
                        <li>Destination section is appropriate</li>
                        <li>Destination academic session is the next session</li>
                    </ul>
                </div>
            </div>

            <!-- Step 8 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-lg shrink-0">8</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Confirm Promotion</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Click "Promote Selected Students" and confirm</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed mb-3">
                        Click <strong>Promote Selected Students</strong>. A confirmation dialog will appear showing a complete summary. After you confirm, the system will:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-emerald-600 text-[16px]">check</span>
                            <span class="text-sm text-emerald-800">Validate all records</span>
                        </div>
                        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-emerald-600 text-[16px]">check</span>
                            <span class="text-sm text-emerald-800">Create promotion history</span>
                        </div>
                        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-emerald-600 text-[16px]">check</span>
                            <span class="text-sm text-emerald-800">Update student enrollments</span>
                        </div>
                        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-emerald-600 text-[16px]">check</span>
                            <span class="text-sm text-emerald-800">Record audit logs</span>
                        </div>
                        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-2">
                            <span class="material-symbols-rounded text-emerald-600 text-[16px]">check</span>
                            <span class="text-sm text-emerald-800">Prevent duplicates</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 9 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">9</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Review Results</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Check the promotion summary</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed mb-3">
                        After the promotion is complete, a results dialog will display:
                    </p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <span class="text-sm text-on-surface">Total Selected</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span class="text-sm text-on-surface">Successfully Promoted</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-sm text-on-surface">Failed Promotions</span>
                        </div>
                        <div class="flex items-center gap-2 bg-surface-variant rounded-lg px-3 py-2">
                            <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                            <span class="text-sm text-on-surface">Validation Errors</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 10 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden group hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4 p-md bg-surface-bright border-b border-outline-variant">
                    <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-lg shrink-0">10</div>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">View Promotion History</h3>
                        <p class="text-body-sm text-secondary mt-0.5">Review all completed promotions</p>
                    </div>
                </div>
                <div class="p-md">
                    <p class="text-body-md text-on-surface leading-relaxed">
                        Click the <strong>Promotion History</strong> button in the top right of the promotions page to view a complete, searchable log of all promotions. You can filter by session, class, status, and date range. You can also export the history as a CSV file.
                    </p>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
            <h3 class="text-headline-md font-headline-md text-orange-900 flex items-center gap-2 mb-4">
                <span class="material-symbols-rounded text-[22px]">warning</span>
                Important Notes
            </h3>
            <ul class="space-y-3 text-body-md text-orange-900">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-orange-600 text-[18px] mt-0.5 shrink-0">block</span>
                    <span>Students <strong>cannot be promoted twice</strong> to the same destination class and session.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-orange-600 text-[18px] mt-0.5 shrink-0">history</span>
                    <span>All promotions are <strong>permanently recorded</strong> in the promotion history.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-orange-600 text-[18px] mt-0.5 shrink-0">admin_panel_settings</span>
                    <span>Only <strong>authorized administrators</strong> can perform promotions.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-orange-600 text-[18px] mt-0.5 shrink-0">verified</span>
                    <span>Always <strong>verify the destination</strong> class and session before confirming.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-orange-600 text-[18px] mt-0.5 shrink-0">fact_check</span>
                    <span><strong>Review the promotion summary</strong> after every operation to ensure accuracy.</span>
                </li>
            </ul>
        </div>

        <!-- FAQ Section -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright">
                <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                    <span class="material-symbols-rounded text-[22px] text-primary">quiz</span>
                    Frequently Asked Questions
                </h3>
            </div>
            <div class="divide-y divide-outline-variant">
                <div class="p-md">
                    <h4 class="text-label-lg font-label-lg text-on-surface mb-1">Can I undo a promotion?</h4>
                    <p class="text-body-md text-secondary">Promotions are permanent records. If a mistake was made, contact the system administrator who can manually adjust the student's class assignment.</p>
                </div>
                <div class="p-md">
                    <h4 class="text-label-lg font-label-lg text-on-surface mb-1">What happens if a student is already promoted?</h4>
                    <p class="text-body-md text-secondary">The system automatically detects duplicates. If a student has already been promoted to the same class and session, they will be skipped with a notification.</p>
                </div>
                <div class="p-md">
                    <h4 class="text-label-lg font-label-lg text-on-surface mb-1">Can I promote students to a different section?</h4>
                    <p class="text-body-md text-secondary">Yes. When selecting the promotion destination, you can choose any section available for the target class. This is useful when redistributing students across sections.</p>
                </div>
                <div class="p-md">
                    <h4 class="text-label-lg font-label-lg text-on-surface mb-1">What are Promotion Rules?</h4>
                    <p class="text-body-md text-secondary">Promotion Rules define minimum marks percentage and attendance requirements for each class. These rules are used during the preview process to identify eligible and ineligible students.</p>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
