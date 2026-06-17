@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-lg">

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- PAGE HEADER --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-end gap-md">
            <div>
                <div class="flex items-center gap-sm mb-1">
                    <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">description</span>
                    </div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Document & Certificate Management Center</h2>
                </div>
                <p class="text-body-lg font-body-lg text-secondary ml-[52px]">Generate, manage, and track all student documents and certificates from one place.</p>
            </div>
            <div class="flex items-center gap-sm flex-wrap">
                <a href="{{ route('admin.documents.templates') }}" class="inline-flex items-center gap-xs px-md py-sm border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">design_services</span>
                    Manage Templates
                </a>
                <a href="{{ route('admin.documents.signatures') }}" class="inline-flex items-center gap-xs px-md py-sm border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">draw</span>
                    Signatures
                </a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- SECTION 1: QUICK STATISTICS --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div id="ajaxQuickStats" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-md">
            {{-- Total Documents --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider leading-tight">Total Documents</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[18px]">folder</span>
                    </div>
                </div>
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ number_format($totalDocuments) }}</span>
                <div class="mt-1.5 text-xs font-medium text-secondary">All time</div>
                <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Character Certificates --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider leading-tight">Character Certs</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                    </div>
                </div>
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ number_format($templateStats['Character Certificate'] ?? 0) }}</span>
                <div class="mt-1.5 text-xs font-medium text-secondary">Issued</div>
                <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Transfer Certificates --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider leading-tight">Transfer Certs</h3>
                    <div class="w-8 h-8 rounded-lg bg-surface-variant flex items-center justify-center text-on-surface-variant shrink-0">
                        <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                    </div>
                </div>
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ number_format($templateStats['Transfer Certificate'] ?? 0) }}</span>
                <div class="mt-1.5 text-xs font-medium text-secondary">Issued</div>
                <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-surface-variant rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Marksheets --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider leading-tight">Marksheets</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 shrink-0">
                        <span class="material-symbols-outlined text-[18px]">grading</span>
                    </div>
                </div>
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ number_format($templateStats['Marksheet'] ?? 0) }}</span>
                <div class="mt-1.5 text-xs font-medium text-secondary">Generated</div>
                <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- This Month --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider leading-tight">This Month</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                    </div>
                </div>
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ number_format($thisMonthDocuments) }}</span>
                <div class="mt-1.5 text-xs font-medium text-emerald-700">{{ now()->format('F Y') }}</div>
                <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Templates Active --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider leading-tight">Templates</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0">
                        <span class="material-symbols-outlined text-[18px]">layers</span>
                    </div>
                </div>
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ $templates->count() }}</span>
                <div class="mt-1.5 text-xs font-medium text-secondary">Active</div>
                <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- SECTION 2: QUICK ACTIONS --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary text-[20px]">bolt</span>
                <h3 class="text-headline-md font-headline-md text-on-surface">Quick Actions</h3>
            </div>
            <div class="p-md">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-sm">
                    @foreach($templates as $tpl)
                    <button type="button" onclick="quickGenerate({{ $tpl->id }}, '{{ addslashes($tpl->name) }}')" class="flex flex-col items-center gap-2 p-md border border-outline-variant rounded-xl hover:border-primary hover:bg-primary-fixed/30 transition-all group cursor-pointer">
                        <div class="w-11 h-11 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[22px]">
                                @switch($tpl->slug)
                                    @case('character-certificate') verified @break
                                    @case('transfer-certificate') swap_horiz @break
                                    @case('marksheet') grading @break
                                    @case('excellence-award') emoji_events @break
                                    @case('certificate-of-participation') workspace_premium @break
                                    @case('official-transcript-of-marks') receipt_long @break
                                    @default description
                                @endswitch
                            </span>
                        </div>
                        <span class="text-label-md font-label-md text-on-surface text-center leading-tight">{{ $tpl->name }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- SECTION 3: CERTIFICATE GENERATOR (Full Width) --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div class="w-full">

            {{-- Certificate Generator Form --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">edit_document</span>
                    <h3 class="text-headline-md font-headline-md text-on-surface">Certificate Generator</h3>
                </div>
                {{-- Mode Toggle --}}
                <div class="p-sm border-b border-outline-variant bg-surface-container-lowest">
                    <div class="bg-surface-container-high p-1 rounded-lg flex gap-1">
                        <button type="button" onclick="setGenerationMode('single')" id="modeSingleBtn" class="flex-1 py-2 text-label-md font-label-md bg-surface text-on-surface shadow-sm rounded-md transition-all">Single Student</button>
                        <button type="button" onclick="setGenerationMode('bulk')" id="modeBulkBtn" class="flex-1 py-2 text-label-md font-label-md text-on-surface-variant hover:text-on-surface rounded-md transition-all">Bulk Generation</button>
                    </div>
                </div>
                <div class="p-md flex-1 flex flex-col">
                    <form id="generatorForm" class="grid grid-cols-1 md:grid-cols-2 gap-md flex-1">
                        @csrf
                        {{-- Document Type --}}
                        <div class="md:col-span-2">
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Document Type <span class="text-error">*</span></label>
                            <select name="template_id" id="templateSelect" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md">
                                <option value="">— Select Document Type —</option>
                                @foreach($templates as $tpl)
                                    <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="singleModeContainer" class="md:col-span-2 flex flex-col gap-md">
                            {{-- Student Search --}}
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Search Student <span class="text-error">*</span></label>
                                <div class="relative">
                                    <input type="text" id="studentSearchInput" placeholder="Type name, admission no, or roll no..." autocomplete="off" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md pr-10">
                                    <span class="material-symbols-outlined text-[20px] text-secondary absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                                </div>
                                <input type="hidden" name="student_id" id="selectedStudentId">
                                {{-- Search results dropdown --}}
                                <div id="studentSearchResults" class="hidden mt-1 bg-surface-container-lowest border border-outline-variant rounded-lg shadow-lg max-h-48 overflow-y-auto z-50 relative"></div>
                            </div>

                            {{-- Selected Student Card --}}
                            <div id="selectedStudentCard" class="hidden border border-primary/30 bg-primary-fixed/20 rounded-lg p-sm">
                                <div class="flex items-center gap-sm">
                                    <div id="studentAvatar" class="w-10 h-10 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center font-bold text-sm shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <div id="studentNameDisplay" class="font-headline-md text-on-surface truncate"></div>
                                        <div class="flex items-center gap-2 flex-wrap mt-0.5">
                                            <span id="studentAdmNoDisplay" class="text-xs px-1.5 py-0.5 bg-surface-container-high rounded text-secondary"></span>
                                            <span id="studentClassDisplay" class="text-xs px-1.5 py-0.5 bg-secondary-container text-on-secondary-container rounded"></span>
                                        </div>
                                    </div>
                                    <button type="button" onclick="clearStudent()" class="text-secondary hover:text-error p-1 rounded-full hover:bg-error-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="bulkModeContainer" class="hidden md:col-span-2 flex-col gap-md">
                            <div class="grid grid-cols-2 gap-sm">
                                <div>
                                    <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Class</label>
                                    <select id="bulkClassSelect" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md" onchange="loadBulkStudents()">
                                        <option value="">— Select Class —</option>
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Section</label>
                                    <select id="bulkSectionSelect" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md" onchange="loadBulkStudents()">
                                        <option value="">— All Sections —</option>
                                        @foreach($sections as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="border border-outline-variant rounded-lg overflow-hidden flex flex-col h-64">
                                <div class="bg-surface-container-low border-b border-outline-variant px-sm py-2 flex items-center justify-between">
                                    <label class="flex items-center gap-2 text-label-md cursor-pointer">
                                        <input type="checkbox" id="bulkSelectAll" class="rounded border-outline-variant text-primary focus:ring-primary" onchange="toggleBulkSelectAll(this)">
                                        <span>Select All</span>
                                    </label>
                                    <span class="text-xs text-secondary"><span id="bulkSelectedCount">0</span> selected</span>
                                </div>
                                <div id="bulkStudentList" class="flex-1 overflow-y-auto p-sm flex flex-col gap-1">
                                    <div class="text-center py-4 text-secondary text-sm">Select a class to load students</div>
                                </div>
                            </div>
                        </div>


                        {{-- Purpose --}}
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Purpose <span class="text-xs text-secondary">(Optional)</span></label>
                            <input type="text" name="purpose" id="purposeInput" placeholder="e.g. For passport application" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md">
                        </div>

                        {{-- Academic Year --}}
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Academic Year</label>
                            <input type="text" name="academic_year" id="academicYearInput" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary text-body-md" value="{{ $academicYear ? $academicYear->start_date . ' to ' . $academicYear->end_date : '' }}">
                        </div>

                        {{-- AI Enhance --}}
                        <label class="md:col-span-2 flex items-center gap-sm cursor-pointer p-sm border border-primary-fixed-dim bg-primary-fixed/40 rounded-lg">
                            <input type="checkbox" name="ai_enhance" value="1" class="text-primary focus:ring-primary rounded">
                            <div>
                                <span class="font-label-md text-primary font-bold flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[16px]">auto_awesome</span> AI Enhance
                                </span>
                                <span class="text-[11px] text-secondary block">Improve tone & professionalism</span>
                            </div>
                        </label>

                        {{-- Actions --}}
                        <div class="md:col-span-2 mt-auto pt-md border-t border-outline-variant flex justify-end">
                            <button type="button" onclick="processGeneration()" id="generateBtn" class="px-xl py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center justify-center gap-xs disabled:opacity-50" disabled>
                                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> <span id="generateBtnText">Generate PDF</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- SECTION 5: RECENT DOCUMENTS TABLE --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div id="ajaxRecentDocuments" class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden relative transition-opacity duration-300">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center justify-between">
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">history</span>
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Recent Documents</h3>
                        <p class="text-xs text-secondary">All generated documents and certificates</p>
                    </div>
                </div>
                <div class="flex items-center gap-xs">
                    <button onclick="deleteSelectedDocuments()" id="btnDeleteSelected" class="hidden items-center gap-xs px-md py-sm bg-error-container text-error rounded-lg font-label-md hover:bg-error hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[18px]">delete</span> Delete Selected
                    </button>
                    <button onclick="deleteAllDocuments()" class="inline-flex items-center gap-xs px-md py-sm border border-error text-error rounded-lg font-label-md hover:bg-error hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[18px]">delete_forever</span> Delete All
                    </button>
                    <a href="{{ route('admin.documents.create') }}" class="inline-flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors">
                        <span class="material-symbols-outlined text-[18px]">add</span> New Document
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant font-label-md">
                            <th class="p-md border-b border-outline-variant w-10 text-center">
                                <input type="checkbox" id="selectAllDocs" class="rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" onchange="toggleSelectAllDocs(this)">
                            </th>
                            <th class="p-md border-b border-outline-variant">Document No</th>
                            <th class="p-md border-b border-outline-variant">Student</th>
                            <th class="p-md border-b border-outline-variant">Class</th>
                            <th class="p-md border-b border-outline-variant">Document Type</th>
                            <th class="p-md border-b border-outline-variant">Created By</th>
                            <th class="p-md border-b border-outline-variant">Created Date</th>
                            <th class="p-md border-b border-outline-variant text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md text-on-surface divide-y divide-outline-variant">
                        @forelse($documents as $doc)
                        <tr class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="p-md text-center">
                                <input type="checkbox" value="{{ $doc->id }}" class="doc-checkbox rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" onchange="updateDeleteSelectedButton()">
                            </td>
                            <td class="p-md">
                                <span class="font-mono text-xs bg-surface-container-high px-2 py-1 rounded">{{ $doc->document_no }}</span>
                            </td>
                            <td class="p-md">
                                <div class="flex items-center gap-sm">
                                    <div class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                        {{ substr($doc->student->first_name ?? 'S', 0, 1) }}{{ substr($doc->student->last_name ?? '', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-medium text-on-surface">{{ $doc->student->first_name ?? '' }} {{ $doc->student->last_name ?? '' }}</span>
                                        <div class="text-label-md text-secondary">{{ $doc->student->admission_no ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-md text-secondary">{{ $doc->student->currentClass->name ?? '—' }}</td>
                            <td class="p-md">
                                <span class="px-2.5 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs font-medium">
                                    {{ $doc->template->name ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="p-md text-secondary text-sm">{{ $doc->issuedBy->name ?? 'System' }}</td>
                            <td class="p-md text-secondary text-sm">{{ $doc->issued_at ? $doc->issued_at->format('d M Y') : '—' }}</td>
                            <td class="p-md">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.documents.download', $doc->id) }}" class="p-1.5 rounded-lg hover:bg-primary-fixed text-secondary hover:text-primary transition-colors" title="Download">
                                        <span class="material-symbols-outlined text-[18px]">download</span>
                                    </a>
                                    <button onclick="viewStudentHistory({{ $doc->student->id ?? 0 }})" class="p-1.5 rounded-lg hover:bg-surface-container-high text-secondary hover:text-on-surface transition-colors" title="Student History">
                                        <span class="material-symbols-outlined text-[18px]">person_search</span>
                                    </button>
                                    <button onclick="deleteDocument({{ $doc->id }}, '{{ $doc->document_no }}')" class="p-1.5 rounded-lg hover:bg-error-container text-secondary hover:text-error transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-xl text-center">
                                <div class="flex flex-col items-center py-md">
                                    <span class="material-symbols-outlined text-[48px] text-outline mb-sm">inbox</span>
                                    <p class="text-body-lg text-secondary">No documents generated yet.</p>
                                    <p class="text-body-md text-outline mt-1">Use the Certificate Generator above to create your first document.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($documents->hasPages())
            <div class="p-md bg-surface-container-lowest border-t border-outline-variant">
                {{ $documents->links('pagination::tailwind') }}
            </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        {{-- SECTION 6: TEMPLATES + SECTION 8: RECENT ACTIVITY (Side by Side) --}}
        {{-- ═══════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">

            {{-- Templates Overview --}}
            <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center justify-between">
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-primary text-[20px]">layers</span>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Available Templates</h3>
                    </div>
                    <a href="{{ route('admin.documents.templates') }}" class="text-primary text-label-md font-label-md hover:underline">Manage All</a>
                </div>
                <div class="p-md">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-sm">
                        @foreach($templates as $tpl)
                        <div class="border border-outline-variant rounded-xl p-md hover:border-primary transition-colors group">
                            <div class="flex items-start gap-sm mb-sm">
                                <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary-container flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">
                                        @switch($tpl->slug)
                                            @case('character-certificate') verified @break
                                            @case('transfer-certificate') swap_horiz @break
                                            @case('marksheet') grading @break
                                            @case('excellence-award') emoji_events @break
                                            @case('certificate-of-participation') workspace_premium @break
                                            @case('official-transcript-of-marks') receipt_long @break
                                            @default description
                                        @endswitch
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-headline-md text-on-surface text-sm leading-tight truncate">{{ $tpl->name }}</h4>
                                    <span class="text-[11px] text-secondary">Updated {{ $tpl->updated_at ? $tpl->updated_at->diffForHumans() : 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-sm pt-sm border-t border-outline-variant">
                                <div class="flex items-center gap-1.5">
                                    @if($tpl->has_qr)
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800">
                                        <span class="material-symbols-outlined text-[12px]">qr_code_2</span>QR
                                    </span>
                                    @endif
                                    @if($tpl->has_signature)
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800">
                                        <span class="material-symbols-outlined text-[12px]">draw</span>Sig
                                    </span>
                                    @endif
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-medium {{ $tpl->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-surface-container-high text-secondary' }}">
                                        {{ $tpl->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <a href="{{ route('admin.documents.templates.edit', $tpl->id) }}" class="text-primary text-label-md hover:underline text-xs">Edit</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div id="ajaxRecentActivity" class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col relative transition-opacity duration-300">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">schedule</span>
                    <h3 class="text-headline-md font-headline-md text-on-surface">Recent Activity</h3>
                </div>
                <div class="p-0 flex-1 overflow-y-auto" style="max-height: 400px;">
                    @forelse($recentActivity as $activity)
                    <div class="px-md py-sm border-b border-outline-variant last:border-b-0 hover:bg-surface-container-lowest transition-colors flex gap-sm">
                        <div class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[16px]">description</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-body-md text-on-surface font-medium truncate">{{ $activity->template->name ?? 'Document' }}</p>
                            <p class="text-xs text-secondary truncate">{{ $activity->student->first_name ?? '' }} {{ $activity->student->last_name ?? '' }} · {{ $activity->document_no }}</p>
                            <p class="text-[11px] text-outline mt-0.5">
                                {{ $activity->issued_at ? $activity->issued_at->diffForHumans() : '' }}
                                @if($activity->issuedBy)
                                 · by {{ $activity->issuedBy->name }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('admin.documents.download', $activity->id) }}" class="text-secondary hover:text-primary self-center shrink-0 p-1" title="Download">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                        </a>
                    </div>
                    @empty
                    <div class="p-xl text-center text-secondary flex flex-col items-center">
                        <span class="material-symbols-outlined text-[32px] opacity-40 mb-sm">event_busy</span>
                        <p class="text-body-md">No recent activity.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</main>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- STUDENT HISTORY MODAL --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="studentHistoryModal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" style="display: none;">
    <div class="bg-surface-container-lowest rounded-xl max-w-2xl w-full shadow-lg border border-outline-variant max-h-[80vh] flex flex-col transform scale-95 transition-transform duration-200">
        <div class="p-md border-b border-outline-variant flex items-center justify-between bg-surface-bright rounded-t-xl">
            <div class="flex items-center gap-sm">
                <div id="historyAvatar" class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm"></div>
                <div>
                    <h3 id="historyStudentName" class="text-headline-md font-headline-md text-on-surface"></h3>
                    <p id="historyStudentMeta" class="text-label-md text-secondary"></p>
                </div>
            </div>
            <button onclick="closeHistoryModal()" class="p-1.5 rounded-full hover:bg-surface-container-high transition-colors text-secondary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div id="historyContent" class="p-md overflow-y-auto flex-1">
            <div class="flex items-center justify-center py-xl">
                <div class="w-8 h-8 border-3 border-primary border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- BULK GENERATION PROGRESS MODAL --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="bulkProgressModal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" style="display: none;">
    <div class="bg-surface-container-lowest w-full max-w-sm rounded-2xl shadow-lg border border-outline-variant overflow-hidden transform scale-95 transition-transform duration-300 p-xl text-center flex flex-col items-center">
        <h3 class="text-headline-md font-headline-md text-on-surface mb-sm">Generating Documents</h3>
        <p class="text-body-md text-secondary mb-xl">Please wait while the batch is processing...</p>
        
        <div class="w-full bg-surface-container-high rounded-full h-3 overflow-hidden mb-3">
            <div id="bulkProgressBar" class="bg-primary h-full transition-all duration-300" style="width: 0%"></div>
        </div>
        
        <div class="w-full flex justify-between items-center text-label-md font-label-md text-secondary">
            <span id="bulkProgressText">0 / 0</span>
            <span id="bulkProgressPercent">0%</span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- JAVASCRIPT --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('studentSearchInput');
    const searchResults = document.getElementById('studentSearchResults');
    let searchTimeout;

    // Student search with debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }
        searchTimeout = setTimeout(() => searchStudents(q), 300);
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });

    // Validate form state
    document.getElementById('templateSelect').addEventListener('change', validateForm);
});

function searchStudents(query) {
    const results = document.getElementById('studentSearchResults');
    results.innerHTML = '<div class="p-sm text-center text-secondary text-sm">Searching...</div>';
    results.classList.remove('hidden');

    fetch(`{{ route('admin.documents.ajax-search') }}?query=${encodeURIComponent(query)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (!data || (!data.data && !Array.isArray(data))) {
            // Fallback: simple client-side approach using fetch to students index
            results.innerHTML = '<div class="p-sm text-center text-secondary text-sm">Type a name or admission number above</div>';
            return;
        }
        const students = data.data || data;
        if (students.length === 0) {
            results.innerHTML = '<div class="p-sm text-center text-secondary text-sm">No students found</div>';
            return;
        }
        results.innerHTML = students.slice(0, 10).map(s => `
            <div class="px-sm py-2 hover:bg-surface-container-high cursor-pointer flex items-center gap-sm border-b border-outline-variant last:border-b-0 transition-colors"
                 onclick="selectStudent(${s.id}, '${(s.first_name || '').replace(/'/g, "\\'")} ${(s.last_name || '').replace(/'/g, "\\'")}', '${s.admission_no || ''}', '${s.current_class?.name || s.class_name || 'N/A'}')">
                <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs shrink-0">
                    ${(s.first_name || 'S')[0]}${(s.last_name || '')[0] || ''}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-body-md text-on-surface font-medium truncate">${s.first_name || ''} ${s.last_name || ''}</div>
                    <div class="text-xs text-secondary">${s.admission_no || ''} · ${s.current_class?.name || s.class_name || ''}</div>
                </div>
            </div>
        `).join('');
    })
    .catch(() => {
        results.innerHTML = '<div class="p-sm text-center text-secondary text-sm">Search not available — use the full page to select students</div>';
    });
}

function selectStudent(id, name, admNo, className) {
    document.getElementById('selectedStudentId').value = id;
    document.getElementById('studentSearchInput').value = '';
    document.getElementById('studentSearchResults').classList.add('hidden');

    // Show card
    const card = document.getElementById('selectedStudentCard');
    card.classList.remove('hidden');
    document.getElementById('studentAvatar').textContent = name.trim()[0] || 'S';
    document.getElementById('studentNameDisplay').textContent = name;
    document.getElementById('studentAdmNoDisplay').textContent = admNo;
    document.getElementById('studentClassDisplay').textContent = className;

    validateForm();
}

function clearStudent() {
    document.getElementById('selectedStudentId').value = '';
    document.getElementById('selectedStudentCard').classList.add('hidden');
    validateForm();
}

function validateForm() {
    const hasTemplate = document.getElementById('templateSelect').value !== '';
    const hasStudent = document.getElementById('selectedStudentId').value !== '';
    const valid = hasTemplate && hasStudent;
    document.getElementById('generateBtn').disabled = !valid;
}

function quickGenerate(templateId, templateName) {
    document.getElementById('templateSelect').value = templateId;
    document.getElementById('studentSearchInput').focus();
    validateForm();
    // Scroll to generator
    document.getElementById('generatorForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// previewDocument removed

function refreshDashboardData(url = window.location.href) {
    const containers = ['ajaxQuickStats', 'ajaxRecentDocuments', 'ajaxRecentActivity'];
    
    // Add loading state
    containers.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.opacity = '0.5';
    });

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        containers.forEach(id => {
            const newEl = doc.getElementById(id);
            const oldEl = document.getElementById(id);
            if (newEl && oldEl) {
                oldEl.innerHTML = newEl.innerHTML;
                oldEl.style.opacity = '1';
            }
        });

        // Update URL history if pagination was clicked
        if (url !== window.location.href) {
            window.history.pushState(null, '', url);
        }
    })
    .catch(err => {
        console.error('Error refreshing dashboard:', err);
        containers.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.opacity = '1';
        });
    });
}

// Intercept pagination links to use AJAX
document.addEventListener('click', function(e) {
    const paginationLink = e.target.closest('#ajaxRecentDocuments nav a');
    if (paginationLink && paginationLink.href) {
        e.preventDefault();
        refreshDashboardData(paginationLink.href);
    }
});

function generateDocument() {
    const form = document.getElementById('generatorForm');
    const formData = new FormData(form);

    // The loading spinner on button
    const generateBtn = document.getElementById('generateBtn');
    const origHTML = generateBtn.innerHTML;
    generateBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Generating...';
    generateBtn.disabled = true;

    fetch('{{ route("admin.documents.generate") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        generateBtn.innerHTML = origHTML;
        generateBtn.disabled = false;
        if (data.success) {
            UI.showToast('Document generated successfully!', 'success');
            // Auto-print instead of download using a hidden iframe
            if (data.document && data.document.print_html) {
                const decodedHtml = decodeURIComponent(escape(window.atob(data.document.print_html)));
                
                const iframe = document.createElement('iframe');
                iframe.style.position = 'absolute';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = 'none';
                document.body.appendChild(iframe);

                const iframeDoc = iframe.contentWindow.document;
                iframeDoc.open();
                iframeDoc.write(decodedHtml);
                iframeDoc.close();

                setTimeout(() => {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    setTimeout(() => {
                        document.body.removeChild(iframe);
                        refreshDashboardData();
                    }, 1000);
                }, 500);
            } else {
                refreshDashboardData();
            }
        } else {
            UI.showToast(data.message || 'Error generating document. Please try again.', 'error');
        }
    })
    .catch(err => {
        generateBtn.innerHTML = origHTML;
        generateBtn.disabled = false;
        UI.showToast('Error: ' + (err.message || 'Error generating document. Please try again.'), 'error');
        console.error("Generate error: ", err);
    });
}

// printPreview removed

function deleteDocument(id, docNo) {
    UI.confirm('Delete Document', `Are you sure you want to permanently delete document ${docNo}?`, 'Delete', 'error')
    .then(confirmed => {
        if (!confirmed) return;
        fetch(`{{ url('admin/advanced/documents') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                UI.showToast('Document deleted successfully.', 'success');
                refreshDashboardData();
            } else {
                UI.showToast(data.message || 'Failed to delete.', 'error');
            }
        })
        .catch(() => UI.showToast('Error deleting document.', 'error'));
    });
}

function toggleSelectAllDocs(checkbox) {
    const checkboxes = document.querySelectorAll('.doc-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateDeleteSelectedButton();
}

function updateDeleteSelectedButton() {
    const checkboxes = document.querySelectorAll('.doc-checkbox:checked');
    const btn = document.getElementById('btnDeleteSelected');
    if (checkboxes.length > 0) {
        btn.classList.remove('hidden');
        btn.classList.add('inline-flex');
    } else {
        btn.classList.add('hidden');
        btn.classList.remove('inline-flex');
    }
}

function deleteSelectedDocuments() {
    const checkboxes = document.querySelectorAll('.doc-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) return;

    UI.confirm('Delete Selected Documents', `Are you sure you want to permanently delete ${ids.length} selected documents?`, 'Delete', 'error')
    .then(confirmed => {
        if (!confirmed) return;
        fetch(`{{ route('admin.documents.bulk-destroy') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                UI.showToast(data.message || 'Documents deleted successfully.', 'success');
                refreshDashboardData();
            } else {
                UI.showToast(data.message || 'Failed to delete documents.', 'error');
            }
        })
        .catch(() => UI.showToast('Error deleting documents.', 'error'));
    });
}

function deleteAllDocuments() {
    UI.confirm('Delete ALL Documents', `WARNING: Are you sure you want to permanently delete ALL generated documents? This action cannot be undone.`, 'Delete All', 'error')
    .then(confirmed => {
        if (!confirmed) return;
        fetch(`{{ route('admin.documents.destroy-all') }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                UI.showToast(data.message || 'All documents deleted successfully.', 'success');
                refreshDashboardData();
            } else {
                UI.showToast(data.message || 'Failed to delete all documents.', 'error');
            }
        })
        .catch(() => UI.showToast('Error deleting all documents.', 'error'));
    });
}

function viewStudentHistory(studentId) {
    if (!studentId) return;
    const modal = document.getElementById('studentHistoryModal');
    const content = document.getElementById('historyContent');

    // Show modal
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.style.opacity = '1';
        modal.querySelector('div').style.transform = 'scale(1)';
    });

    content.innerHTML = '<div class="flex items-center justify-center py-xl"><div class="w-8 h-8 border-3 border-primary border-t-transparent rounded-full animate-spin"></div></div>';

    fetch(`{{ url('admin/advanced/documents/student-history') }}/${studentId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const s = data.student;
            document.getElementById('historyAvatar').textContent = s.name[0] || 'S';
            document.getElementById('historyStudentName').textContent = s.name;
            document.getElementById('historyStudentMeta').textContent = `${s.admission_no} · ${s.class} ${s.section}`;

            if (data.documents.length === 0) {
                content.innerHTML = `
                    <div class="text-center py-xl">
                        <span class="material-symbols-outlined text-[40px] text-outline mb-sm">folder_open</span>
                        <p class="text-body-lg text-secondary">No documents generated for this student.</p>
                    </div>`;
                return;
            }

            content.innerHTML = `
                <div class="space-y-0 divide-y divide-outline-variant">
                    ${data.documents.map(doc => `
                        <div class="py-sm flex items-center gap-sm hover:bg-surface-container-lowest px-sm rounded-lg transition-colors">
                            <div class="w-9 h-9 rounded-lg bg-primary-fixed text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">description</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-on-surface text-sm">${doc.template_name}</div>
                                <div class="text-xs text-secondary">${doc.document_no} · ${doc.issued_at}</div>
                                ${doc.purpose ? `<div class="text-[11px] text-outline mt-0.5">Purpose: ${doc.purpose}</div>` : ''}
                            </div>
                            <a href="${doc.download_url}" class="p-1.5 rounded-lg hover:bg-primary-fixed text-secondary hover:text-primary transition-colors shrink-0" title="Download">
                                <span class="material-symbols-outlined text-[18px]">download</span>
                            </a>
                        </div>
                    `).join('')}
                </div>
                <div class="mt-md pt-md border-t border-outline-variant text-center">
                    <p class="text-xs text-secondary">Total: ${data.documents.length} document(s) generated</p>
                </div>`;
        } else {
            content.innerHTML = '<div class="text-center py-xl text-secondary">Failed to load history.</div>';
        }
    })
    .catch(() => {
        content.innerHTML = '<div class="text-center py-xl text-secondary">Error loading student history.</div>';
    });
}

function closeHistoryModal() {
    const modal = document.getElementById('studentHistoryModal');
    modal.style.opacity = '0';
    modal.querySelector('div').style.transform = 'scale(0.95)';
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }, 200);
}

// ═══════════════════════════════════════════════════════════════════
// BULK GENERATION LOGIC
// ═══════════════════════════════════════════════════════════════════
let generationMode = 'single';

function setGenerationMode(mode) {
    generationMode = mode;
    const singleBtn = document.getElementById('modeSingleBtn');
    const bulkBtn = document.getElementById('modeBulkBtn');
    
    if (mode === 'single') {
        singleBtn.className = "flex-1 py-2 text-label-md font-label-md bg-surface text-on-surface shadow-sm rounded-md transition-all";
        bulkBtn.className = "flex-1 py-2 text-label-md font-label-md text-on-surface-variant hover:text-on-surface rounded-md transition-all";
        
        document.getElementById('singleModeContainer').classList.remove('hidden');
        document.getElementById('singleModeContainer').classList.add('flex', 'flex-col', 'gap-md');
        document.getElementById('bulkModeContainer').classList.add('hidden');
        document.getElementById('bulkModeContainer').classList.remove('flex', 'flex-col', 'gap-md');
    } else {
        bulkBtn.className = "flex-1 py-2 text-label-md font-label-md bg-surface text-on-surface shadow-sm rounded-md transition-all";
        singleBtn.className = "flex-1 py-2 text-label-md font-label-md text-on-surface-variant hover:text-on-surface rounded-md transition-all";
        
        document.getElementById('bulkModeContainer').classList.remove('hidden');
        document.getElementById('bulkModeContainer').classList.add('flex', 'flex-col', 'gap-md');
        document.getElementById('singleModeContainer').classList.add('hidden');
        document.getElementById('singleModeContainer').classList.remove('flex', 'flex-col', 'gap-md');
        
        if (document.getElementById('bulkStudentList').children.length <= 1) {
            // First time load, leave as is (prompt user to select class)
        }
    }
    validateForm();
}

function loadBulkStudents() {
    const classId = document.getElementById('bulkClassSelect').value;
    const sectionId = document.getElementById('bulkSectionSelect').value;
    const list = document.getElementById('bulkStudentList');
    
    if (!classId) {
        list.innerHTML = '<div class="text-center py-4 text-secondary text-sm">Select a class to load students</div>';
        updateBulkCount();
        return;
    }
    
    list.innerHTML = '<div class="text-center py-4"><div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div></div>';
    
    fetch(`{{ route('admin.documents.ajax-search') }}?class_id=${classId}&section_id=${sectionId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(students => {
        if (students.length === 0) {
            list.innerHTML = '<div class="text-center py-4 text-secondary text-sm">No students found</div>';
            updateBulkCount();
            return;
        }
        
        list.innerHTML = students.map(s => `
            <label class="flex items-center gap-3 p-2 hover:bg-surface-container-lowest rounded cursor-pointer border border-transparent hover:border-outline-variant transition-colors">
                <input type="checkbox" value="${s.id}" class="bulk-student-cb rounded border-outline-variant text-primary focus:ring-primary" onchange="updateBulkCount()">
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm text-on-surface">${s.first_name} ${s.last_name || ''}</div>
                    <div class="text-xs text-secondary">${s.admission_no} • ${s.class_name} ${s.section_name}</div>
                </div>
            </label>
        `).join('');
        document.getElementById('bulkSelectAll').checked = false;
        updateBulkCount();
    })
    .catch(() => {
        list.innerHTML = '<div class="text-center py-4 text-error text-sm">Error loading students</div>';
    });
}

function toggleBulkSelectAll(cb) {
    document.querySelectorAll('.bulk-student-cb').forEach(box => box.checked = cb.checked);
    updateBulkCount();
}

function updateBulkCount() {
    const count = document.querySelectorAll('.bulk-student-cb:checked').length;
    document.getElementById('bulkSelectedCount').textContent = count;
    validateForm();
}

// Override validateForm to support both modes
const originalValidateForm = validateForm;
validateForm = function() {
    const templateId = document.getElementById('templateSelect').value;
    const generateBtn = document.getElementById('generateBtn');
    
    let hasStudent = false;
    if (generationMode === 'single') {
        hasStudent = document.getElementById('selectedStudentId').value !== '';
        if (hasStudent && templateId) {
            generateBtn.disabled = false;
        } else {
            generateBtn.disabled = true;
        }
        document.getElementById('generateBtnText').textContent = 'Generate PDF';
    } else {
        const count = document.querySelectorAll('.bulk-student-cb:checked').length;
        hasStudent = count > 0;
        if (hasStudent && templateId) {
            generateBtn.disabled = false;
        } else {
            generateBtn.disabled = true;
        }
        document.getElementById('generateBtnText').textContent = count > 0 ? `Generate PDF (${count})` : 'Generate PDF';
    }
};

function processGeneration() {
    if (generationMode === 'single') {
        generateDocument();
    } else {
        generateBulkDocuments();
    }
}

async function generateBulkDocuments() {
    const templateId = document.getElementById('templateSelect').value;
    const purpose = document.getElementById('purposeInput').value;
    const academicYear = document.getElementById('academicYearInput').value;
    const aiEnhance = document.querySelector('input[name="ai_enhance"]').checked ? '1' : '0';
    
    const checkboxes = document.querySelectorAll('.bulk-student-cb:checked');
    const studentIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (studentIds.length === 0 || !templateId) return;

    // Show Progress Modal
    const modal = document.getElementById('bulkProgressModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.style.opacity = '1';
        modal.querySelector('div').style.transform = 'scale(1)';
    });

    const progressBar = document.getElementById('bulkProgressBar');
    const progressText = document.getElementById('bulkProgressText');
    const progressPercent = document.getElementById('bulkProgressPercent');
    
    let combinedHtmlPrefix = '';
    let combinedHtmlBodies = '';
    let combinedHtmlSuffix = '';
    let errorCount = 0;

    for (let i = 0; i < studentIds.length; i++) {
        // Update UI
        const percent = Math.round((i / studentIds.length) * 100);
        progressBar.style.width = percent + '%';
        progressText.textContent = `${i + 1} / ${studentIds.length}`;
        progressPercent.textContent = percent + '%';

        // Prepare FormData
        const formData = new FormData();
        formData.append('template_id', templateId);
        formData.append('student_id', studentIds[i]);
        formData.append('purpose', purpose);
        formData.append('academic_year', academicYear);
        if (aiEnhance === '1') formData.append('ai_enhance', '1');

        try {
            const res = await fetch('{{ route("admin.documents.generate") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            const data = await res.json();
            
            if (data.success && data.document && data.document.print_html) {
                const decodedHtml = decodeURIComponent(escape(window.atob(data.document.print_html)));
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(decodedHtml, 'text/html');
                
                if (i === 0) {
                    // Extract head and wrapper
                    const bodyMatch = decodedHtml.match(/<body[^>]*>/i);
                    const bodyTag = bodyMatch ? bodyMatch[0] : '<body>';
                    combinedHtmlPrefix = decodedHtml.substring(0, decodedHtml.indexOf(bodyTag) + bodyTag.length);
                    combinedHtmlSuffix = '</body></html>';
                    combinedHtmlBodies += doc.body.innerHTML;
                } else {
                    combinedHtmlBodies += '<div style="page-break-after: always; clear: both;"></div>';
                    combinedHtmlBodies += doc.body.innerHTML;
                }
            } else {
                errorCount++;
            }
        } catch (err) {
            errorCount++;
            console.error(err);
        }
    }

    // Finish 100%
    progressBar.style.width = '100%';
    progressPercent.textContent = '100%';
    
    if (errorCount > 0) {
        UI.showToast(`Batch completed with ${errorCount} errors.`, 'error');
    } else {
        UI.showToast('Batch generation successful!', 'success');
    }

    // Print Combined PDF
    if (combinedHtmlBodies) {
        const finalHtml = combinedHtmlPrefix + combinedHtmlBodies + combinedHtmlSuffix;
        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);

        const iframeDoc = iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(finalHtml);
        iframeDoc.close();

        setTimeout(() => {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            setTimeout(() => {
                document.body.removeChild(iframe);
                refreshDashboardData();
            }, 1000);
        }, 500);
    } else {
        refreshDashboardData();
    }

    // Close Modal
    setTimeout(() => {
        modal.style.opacity = '0';
        modal.querySelector('div').style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }, 300);
    }, 500);
}

</script>
@endsection
