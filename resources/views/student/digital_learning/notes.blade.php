@extends('layouts.app')
@section('title', 'Digital Notes Library')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Digital Notes Library</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Access and download your course materials, slides, and references.</p>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">search</span>
                <input type="text" id="searchInput" placeholder="Search notes..." class="w-full md:w-72 pl-10 pr-4 py-2 border border-outline-variant rounded-xl bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            {{-- Total Notes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Notes</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">library_books</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ $totalNotes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Downloaded Notes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Downloaded</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">download_done</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ $downloadedNotes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Pending Notes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending</h3>
                    <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                        <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ $pendingNotes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-error-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Subjects Covered Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Subjects Covered</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[18px]">category</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ $subjectsCovered }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Middle Section: Filters & Content Grid -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <!-- Header & Filter Row -->
            <div class="p-4 border-b border-outline-variant flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-surface-bright">
                <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">folder_open</span>
                    Available Resources
                </h3>
                
                <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar w-full lg:w-auto" id="filterContainer">
                    <button class="filter-btn active px-4 py-1.5 rounded-full bg-primary text-on-primary text-label-md font-bold whitespace-nowrap transition-colors" data-subject="all">All Subjects</button>
                    @php $uniqueSubjects = $notes->pluck('subject.name')->unique()->filter(); @endphp
                    @foreach($uniqueSubjects as $subjName)
                        <button class="filter-btn px-4 py-1.5 rounded-full border border-outline-variant bg-surface-container-lowest text-secondary hover:bg-surface-container hover:text-on-surface transition-colors text-label-md font-bold whitespace-nowrap" data-subject="{{ strtolower($subjName) }}">{{ $subjName }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Notes Grid -->
            <div class="p-4 sm:p-6 bg-surface-container-lowest">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-md" id="notesGrid">
                    @forelse($notes as $note)
                        @php 
                            $subjNameStr = strtolower($note->subject->name ?? 'general');
                        @endphp
                        <div class="note-card bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden group hover:border-primary hover:shadow-sm transition-all" data-subject="{{ $subjNameStr }}" data-title="{{ strtolower($note->title) }}">
                            
                            <div class="p-4 flex-1 flex flex-col">
                                <!-- Card Header -->
                                <div class="flex justify-between items-start mb-3">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                                        @if($note->file_type == 'pdf') bg-red-100 text-red-600
                                        @elseif(in_array($note->file_type, ['doc', 'docx', 'text'])) bg-blue-100 text-blue-600
                                        @elseif($note->file_type == 'ppt') bg-orange-100 text-orange-600
                                        @elseif($note->file_type == 'image') bg-purple-100 text-purple-600
                                        @else bg-surface-variant text-on-surface-variant @endif
                                    ">
                                        <span class="material-symbols-outlined text-[24px]">
                                            @if($note->file_type == 'pdf') picture_as_pdf
                                            @elseif(in_array($note->file_type, ['doc', 'docx', 'text'])) description
                                            @elseif($note->file_type == 'ppt') slides
                                            @elseif($note->file_type == 'image') image
                                            @else insert_drive_file @endif
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-primary bg-primary-fixed px-2 py-0.5 rounded">{{ $note->subject->name ?? 'General' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Card Body -->
                                <h4 class="font-headline-sm text-headline-sm text-on-surface line-clamp-2 mb-1 group-hover:text-primary transition-colors" title="{{ $note->title }}">{{ $note->title }}</h4>
                                
                                @if($note->description)
                                    <p class="text-body-md font-body-md text-secondary line-clamp-2 mb-4">{{ $note->description }}</p>
                                @else
                                    <div class="mb-4"></div>
                                @endif

                                <!-- Card Meta -->
                                <div class="mt-auto pt-4 border-t border-outline-variant border-dashed flex flex-col gap-2">
                                    <div class="flex items-center gap-2 text-label-sm font-label-sm text-secondary">
                                        <span class="material-symbols-outlined text-[16px]">person</span>
                                        <span class="truncate">{{ $note->uploader->name ?? 'Teacher' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-label-sm font-label-sm text-secondary">
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                            <span>{{ $note->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <span class="font-bold uppercase text-on-surface-variant bg-surface-container px-2 py-0.5 rounded">{{ $note->file_type }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Action Button (Always Visible) -->
                            <div class="p-4 bg-surface-container-lowest border-t border-outline-variant">
                                @if($note->file_path)
                                    <a href="{{ Storage::url($note->file_path) }}" target="_blank" class="w-full py-2 bg-primary-fixed text-primary rounded-lg font-bold text-label-md hover:bg-primary hover:text-on-primary transition-colors flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">download</span> Download File
                                    </a>
                                @elseif($note->external_url)
                                    <a href="{{ $note->external_url }}" target="_blank" class="w-full py-2 bg-secondary-container text-on-secondary-container rounded-lg font-bold text-label-md hover:bg-secondary hover:text-on-secondary transition-colors flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">open_in_new</span> Open Link
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 flex flex-col items-center justify-center border border-outline-variant border-dashed rounded-xl bg-surface-container-lowest">
                            <span class="material-symbols-outlined text-[48px] text-secondary mb-4">folder_off</span>
                            <h4 class="text-headline-md font-headline-md text-on-surface mb-1">No Notes Available</h4>
                            <p class="text-body-md font-body-md text-secondary text-center max-w-sm">No digital notes have been uploaded for your current subjects.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- No Results Message -->
                <div id="noResultsMsg" class="hidden py-16 flex-col items-center justify-center border border-outline-variant border-dashed rounded-xl bg-surface-container-lowest">
                    <span class="material-symbols-outlined text-[48px] text-secondary mb-4">search_off</span>
                    <h4 class="text-headline-md font-headline-md text-on-surface mb-1">No matching notes</h4>
                    <p class="text-body-md font-body-md text-secondary">Try adjusting your filters or search query.</p>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const noteCards = document.querySelectorAll('.note-card');
        const noResultsMsg = document.getElementById('noResultsMsg');

        let currentSubject = 'all';
        let currentSearch = '';

        function filterNotes() {
            let visibleCount = 0;

            noteCards.forEach(card => {
                const cardSubject = card.getAttribute('data-subject');
                const cardTitle = card.getAttribute('data-title');
                
                const matchesSubject = (currentSubject === 'all' || cardSubject === currentSubject);
                const matchesSearch = cardTitle.includes(currentSearch);

                if (matchesSubject && matchesSearch) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0 && noteCards.length > 0) {
                noResultsMsg.classList.remove('hidden');
                noResultsMsg.classList.add('flex');
            } else {
                noResultsMsg.classList.add('hidden');
                noResultsMsg.classList.remove('flex');
            }
        }

        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value.toLowerCase();
            filterNotes();
        });

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-on-primary', 'active');
                    b.classList.add('bg-surface-container-lowest', 'text-secondary', 'border-outline-variant');
                });
                btn.classList.add('bg-primary', 'text-on-primary', 'active');
                btn.classList.remove('bg-surface-container-lowest', 'text-secondary', 'border-outline-variant');

                currentSubject = btn.getAttribute('data-subject');
                filterNotes();
            });
        });
    });
</script>
@endsection
