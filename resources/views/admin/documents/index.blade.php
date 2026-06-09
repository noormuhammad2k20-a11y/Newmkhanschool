@extends('layouts.app')

@section('title', 'Documents Management')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Documents Management</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Generate, track, and securely manage all institutional records</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.documents.templates') }}" class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">draft</span>
                    Manage Templates
                </a>
                <button class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors" data-modal-target="generateDocModal">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Generate Document
                </button>
            </div>
        </div>

        <!-- Stats Grid (4 cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Stat Card 1 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Documents</h3>
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                        <span class="material-symbols-outlined text-[18px]">folder_open</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">{{ $documents->total() }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-emerald-700 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> +12 this week
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Shared Documents</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">share</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">45</span>
                </div>
                <div class="mt-2 text-xs font-medium text-secondary flex items-center gap-1">
                    Active shares
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending Approvals</h3>
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700">
                        <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">8</span>
                </div>
                <div class="mt-2 text-xs font-medium text-orange-700 flex items-center gap-1">
                    Requires attention
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-orange-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Archived</h3>
                    <div class="w-8 h-8 rounded-lg bg-surface-variant flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">1,024</span>
                </div>
                <div class="mt-2 text-xs font-medium text-secondary flex items-center gap-1">
                    Securely stored
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-surface-variant rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Search</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[18px]">search</span>
                        <input type="text" placeholder="Doc ID, Student..." class="w-full bg-surface-container border border-outline-variant rounded-lg pl-9 pr-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                    </div>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Category</label>
                    <select class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option>All Categories</option>
                        <option>Certificates</option>
                        <option>Academic</option>
                        <option>Administrative</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Status</label>
                    <select class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option>All Statuses</option>
                        <option>Active</option>
                        <option>Pending</option>
                        <option>Archived</option>
                    </select>
                </div>
                <div class="flex-none">
                    <button class="px-4 py-2 bg-surface-container-high text-on-surface rounded-lg text-label-md font-label-md hover:bg-surface-variant transition-colors">
                        Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                <h3 class="text-headline-md font-headline-md text-on-surface">Recent Documents</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Document No</th>
                            <th class="py-3 px-4 font-semibold">Student / Entity</th>
                            <th class="py-3 px-4 font-semibold">Template</th>
                            <th class="py-3 px-4 font-semibold">Generated Date</th>
                            <th class="py-3 px-4 font-semibold">Issued By</th>
                            <th class="py-3 px-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($documents as $doc)
                            <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3 px-4 text-primary font-medium">{{ $doc->document_no }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr($doc->student->first_name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-on-surface font-medium">{{ $doc->student->first_name }} {{ $doc->student->last_name }}</span>
                                            <span class="text-secondary text-xs">{{ $doc->student->admission_no }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-surface-variant text-on-surface-variant">
                                        {{ $doc->template->name }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-secondary">{{ $doc->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-secondary">{{ $doc->issuedBy->name ?? 'System' }}</td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <!-- Note: Actual download link requires re-generating or saving to storage. For UI, we show the action. -->
                                        <button class="p-1.5 text-secondary hover:text-primary hover:bg-primary-fixed rounded-lg transition-colors" title="Download">
                                            <span class="material-symbols-outlined text-[18px]">download</span>
                                        </button>
                                        <button class="p-1.5 text-secondary hover:text-primary hover:bg-primary-fixed rounded-lg transition-colors" title="Share">
                                            <span class="material-symbols-outlined text-[18px]">share</span>
                                        </button>
                                        <button class="p-1.5 text-secondary hover:text-error hover:bg-red-50 rounded-lg transition-colors" title="Archive">
                                            <span class="material-symbols-outlined text-[18px]">archive</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-secondary">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl mb-3 opacity-50">description</span>
                                        <p class="text-lg font-medium text-on-surface mb-1">No documents generated yet</p>
                                        <p class="text-sm">Use the 'Generate Document' button to create certificates, leaving letters, etc.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($documents->hasPages())
            <div class="p-4 border-t border-outline-variant bg-surface-bright">
                {{ $documents->links() }}
            </div>
            @endif
        </div>

    </div>
</main>

<!-- Generate Document Modal -->
<div id="generateDocModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center overflow-y-auto">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-2xl shadow-xl border border-outline-variant m-4 transform transition-all">
        <div class="flex justify-between items-center p-6 border-b border-outline-variant">
            <h3 class="text-headline-sm font-headline-sm text-on-surface">Generate Document</h3>
            <button class="text-secondary hover:text-on-surface modal-close">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.documents.generate') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Select Student</label>
                    <select name="student_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" required>
                        <option value="">-- Choose a student --</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Document Template</label>
                    <select name="template_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" required>
                        <option value="">-- Choose a template --</option>
                        @foreach($templates as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Purpose / Remarks</label>
                    <textarea name="purpose" rows="2" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" placeholder="e.g. Visa application, further studies"></textarea>
                </div>
            </div>
            <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-bright rounded-b-2xl">
                <button type="button" class="px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors modal-close">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors">Generate PDF</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple Modal Logic
    document.querySelectorAll('[data-modal-target]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = document.getElementById(btn.getAttribute('data-modal-target'));
            if(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        });
    });
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const modal = e.target.closest('.fixed');
            if(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });
</script>
@endpush
